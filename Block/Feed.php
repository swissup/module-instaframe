<?php
namespace Swissup\Instaframe\Block;

use Magento\Framework\View\Element\Template;

class Feed extends Template
{
    // public function __construct($Token = null) {
    //     $this->_scopeConfig   = $context->getScopeConfig();

    //     if(!empty($Token)){
    //         self::$access_token = $Token;
    //         // Remove from memory -- not sure if really needed.
    //         $Token = null;
    //         unset($Token);
    //     }

    //     self::$result = json_decode(self::fetch("https://api.instagram.com/v1/users/self/media/recent?count=" . self::$count . "&access_token=" . self::$access_token), true);

    //     parent::__construct($result);
    // }

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

    // $config = $this->_scopeConfig->getValue("instaframe/general");

    // public static $result;
    // public static $display_size = $config["layout"];
    // public static $access_token = $config["token"]; // default access token, optional
    // public static $count = $config["images_quantity"];

    // public static function fetch($url){
    //     $ch = curl_init();
    //     curl_setopt($ch, CURLOPT_URL, $url);
    //     curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    //     curl_setopt($ch, CURLOPT_TIMEOUT, 20);
    //     $result = curl_exec($ch);
    //     curl_close($ch);
    //     return $result;
    // }

}
