<?php
/**
 * 用于构造微信消息
 */
include_once("./config.php");
/**
 * 构造进出打卡图文消息
 * @return array
 */
function clock_diagram_text() {
    $redirect_uri = "http://clmts.applinzi.com/oauth.php?s=0";
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

/**
 * 构造志愿者注册图文消息
 * @return array
 */
function volunteer_diagram_text() {
    $redirect_uri = "http://clmts.applinzi.com/oauth.php?s=2";
    $content = array();
    $content[] = array("Title" => "志愿者注册",
                        "PicUrl" => "http://chuantu.xyz/t6/738/1591159652x977013264.png",
                        "Url" => "https://open.weixin.qq.com/connect/oauth2/authorize?appid="
                                         .APPID.
                                         "&redirect_uri="
                                         .$redirect_uri.
                                         "&response_type=code&scope=snsapi_userinfo&state=1&connect_redirect=1#wechat_redirect");
    return $content;
}
?>