<?php
namespace Swissup\Instaframe\Block;

use Magento\Framework\View\Element\Template;

class Feed extends Template
    implements \Magento\Widget\Block\BlockInterface
{
    public static $result;
    public static $display_size;
    public static $access_token;
    public static $userid;
    public static $count;

    /**
     * Class constructor
     *
     * @param      Template\Context             $context   The context
     * @param      \Magento\Framework\Registry  $registry  The registry
     * @param      array                        $data      The data
     */

    public function __construct(
        Template\Context $context,
        \Magento\Framework\Registry $registry,
        array $data = []
    ) {
        $this->_scopeConfig   = $context->getScopeConfig();
        $this->_coreRegistry = $registry;

        parent::__construct($context, $data);

    }

    /**
     * Gets the template.
     *
     * @return     string  The template.
     */

    public function getTemplate() {
        return 'feed.phtml';
    }

    /**
     * Getting items resolution from config [low_resolution, standard_resolution]
     */

    public function getDisplaySize() {
        return $this->_scopeConfig->getValue("instaframe/general/layout");
    }

    /**
     * Getting user Token from config
     *
     * @return     string  The token.
     */
    public function getToken() {
        return $this->_scopeConfig->getValue("instaframe/general/token");
    }

    /**
     * Getting UserID from config
     *
     * @return    string  The user identifier.
     */
    public function getUserId() {
        return $this->_scopeConfig->getValue("instaframe/general/userid");
    }

    /**
     * Getting Instaframe items quantity from config
     *
     * @return     int  The images quantity.
     */
    public function getImagesQuantity() {
        $qty = $this->getData('images_quantity');
        if (!is_numeric($this->getData('images_quantity'))){
            $qty = 10;
        }
        return (int)$qty;
    }

    /**
     * Determines ability to add follow us link.
     *
     * @return     boolean  True if able to add follow us link, False otherwise.
     */

    public function canAddFollowUsLink() {
        return $this->_scopeConfig->getValue("instaframe/general/follow_us");
    }

    /**
     * Fetching the generated instagram url
     * and generating data array
     *
     * @param      string  $url    The url
     *
     * @return     array  ( description_of_the_return_value )
     */
    public static function fetch($url){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 20);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }

    /**
     * Gets the feed data.
     *
     * @return     array  The feed data.
     */
    public function getFeedData() {

        /* Applying config items count */
        $count = $this->getImagesQuantity();

        /* Applying config Instagram token */
        $access_token = $this->getToken();

        /* Decoding data from generated URL */
        $urlJson = json_decode($this->fetch("https://api.instagram.com/v1/users/self/media/recent?count=" . $count . "&access_token=" . $access_token), true);

        $images = [];
        if (!isset($urlJson['data'])){
            return $images;
        }
        // \Zend_Debug::dump($urlJson['data']);die;
        foreach ($urlJson['data'] as $photo) {

            // Applying config items resolution
            $resolution = $this->getDisplaySize();

            /**
             * Creating Instaframe items array
             *
             * @var        array
             */
            $image = array(
                'likes'                 => $photo['likes']['count'],
                'comments'              => $photo['comments']['count'],
                'link'                  => $photo['link'],
                'caption'               => $photo['caption']['text'],
                'username'              => $photo['user']['username'],
                'userpic'               => $photo['user']['profile_picture'],
                'full_name'             => $photo['user']['full_name'],
                'type'                  => $photo['type'],
                'video_first'           => false,

                'images'                => array(
                    'width'                 => $photo['images'][$resolution]['width'],
                    'height'                => $photo['images'][$resolution]['height']
                )
            );

            /**
             * Fill Instaframe items array
             */
            if ($image['type'] == "image") { /* for Image */
                $image['images']['url'] = $photo['images'][$resolution]['url'];
            } elseif ($image['type'] == "carousel") {                           /* for Story Carousel */
                if (isset($photo['carousel_media']["0"]["images"][$resolution]['url'])) {    /* if first slide - image */
                    $image['images']['url'] = $photo['carousel_media']["0"]["images"][$resolution]['url'];
                } elseif (isset($photo['carousel_media']["0"]["videos"][$resolution]['url'])) {    /* if first slide - video */
                    $image['video_first'] = true;
                    $image['images']['url'] = $photo['carousel_media']["0"]["videos"][$resolution]['url'];
                }
            } elseif ($image['type'] == "video") { /* for Video */
                $image['images']['url'] = $photo['videos'][$resolution]['url'];
            }
            $images[] = $image;
        }
        return $images;
    }
}
