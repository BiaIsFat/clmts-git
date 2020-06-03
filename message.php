<?php
/**
 * 用于构造微信消息
 */
include_once("./config.php");
/**
 * 构造图文消息
 * @return array
 */
function diagram_text() {
    $redirect_uri = "http://clmts.applinzi.com/oauth.php";
    $content = array();
    $content[] = array("Title" => "点击打卡",
                        "PicUrl" => "http://chuantu.xyz/t6/738/1591083740x992239408.jpg",
                        "Url" => "https://open.weixin.qq.com/connect/oauth2/authorize?appid="
                                         .APPID.
                                         "&redirect_uri="
                                         .$redirect_uri.
                                         "&response_type=code&scope=snsapi_userinfo&state=1&connect_redirect=1#wechat_redirect");
    return $content;
}

?>