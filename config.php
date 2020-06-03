<?php
//meta data
header("Content-type: text/html; charset=utf-8");

// weixin official accounts paltform
define('APPID'      , "wx218eace563f0c8f6");
define('APPSECRET'  , "cef7666c91e11176fc9a9deee7fd576c");
define('TOKEN'      , "weixin");

// mysql configuration
define('HOST'       , "120.79.33.215");
define('PORT'       , "3306");
define('USER'       , "jinhong");
define('PASSWORD'   , "123");
define('DB'         , "clmts");

// display message in console
function console($msg) {
    echo("<script>console.log(\"$msg\");</script>");
}
?>