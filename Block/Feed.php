<?php
namespace Swissup\Instaframe\Block;

use Magento\Framework\View\Element\Template;

class Feed extends Template
{
    public static $result;
    public static $display_size;
    public static $access_token;
    public static $userid;
    public static $count;

    public function __construct(
        Template\Context $context,
        \Magento\Framework\Registry $registry,
        array $data = []
    ) {
        $this->_scopeConfig   = $context->getScopeConfig();
        $this->_coreRegistry = $registry;

        parent::__construct($context, $data);
    }


    public function getDisplaySize() {
        return $this->_scopeConfig->getValue("instaframe/general/layout");
    }
    public function getToken() {
        return $this->_scopeConfig->getValue("instaframe/general/token");
    }
    public function getUserId() {
        return $this->_scopeConfig->getValue("instaframe/general/userid");
    }
    public function getImagesQuantity() {
        return $this->_scopeConfig->getValue("instaframe/general/images_quantity");
    }

    public static function fetch($url){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 20);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }

    public function getFeedData() {
        $count = $this->getImagesQuantity();
        $access_token = $this->getToken();
        $urlJson = json_decode($this->fetch("https://api.instagram.com/v1/users/self/media/recent?count=" . $count . "&access_token=" . $access_token), true);

        $images = [];

        foreach ($urlJson['data'] as $photo) {
            $image = array(
                'likes'                 => $photo['likes']['count'],
                'comments'              => $photo['comments']['count'],
                'link'                  => $photo['link'],
                'caption'               => $photo['caption']['text'],
                'username'              => $photo['user']['username'],
                'type'                  => $photo['type'],

                'images'                => array(
                    'width'                 => $photo['images'][$this->getDisplaySize()]['width'],
                    'height'                => $photo['images'][$this->getDisplaySize()]['height']
                )
            );

            if ($image['type'] == "image") {
                $image['images']['url'] = $photo['images'][$this->getDisplaySize()]['url'];
            } elseif ($image['type'] == "carousel") {
                $image['images']['url'] = $photo['carousel_media']["0"]["images"][$this->getDisplaySize()]['url'];
            } elseif ($image['type'] == "video") {
                $resolution = $this->getDisplaySize();
                if ($resolution == 'thumbnail') {
                    $resolution = 'low_bandwidth';
                }
                $image['images']['url'] = $photo['videos'][$resolution]['url'];
            }
            $images[] = $image;
        }
        return $images;
    }
}