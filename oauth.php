<?php
require_once('config.php');
$weixin = new class_weixin();

$weixin->logger("GETcode:   ".$_GET["code"]);	

$access_token_oauth2 = $weixin->oauth2_access_token($_GET["code"]);

$weixin->logger("access_token_oauth2:   ".$access_token_oauth2['access_token']);	
$weixin->logger("openid_oauth2:     ".$access_token_oauth2['openid']);	

$userinfo = $weixin->oauth2_get_user_info($access_token_oauth2['access_token'], $access_token_oauth2['openid']); 


// 跳转至搜索数据库
// 0-业主打卡; 2-志愿者注册
$state = $_GET['s'];
$url = "./record.php?state=".$state."&openid=".$access_token_oauth2['openid'];
echo "<script>window.location.href='$url'</script>";
?>

<?php
class class_weixin
{
    var $appid = APPID;
    var $appsecret = APPSECRET;

    //构造函数，获取Access Token
    public function __construct($appid = NULL, $appsecret = NULL)
    {
        if($appid && $appsecret){
            $this->appid = $appid;
            $this->appsecret = $appsecret;
        }
        //2. 缓存形式
        if (isset($_SERVER['HTTP_APPNAME'])){        //SAE环境，需要开通memcache
            $mem = memcache_init();
        }else {                                        //本地环境，需已安装memcache
            $mem = new Memcache;
            $mem->connect('localhost', 11211) or die ("Could not connect");
        }
        $this->access_token = $mem->get($this->appid);
        if (!isset($this->access_token) || empty($this->access_token)){
            $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$this->appid."&secret=".$this->appsecret;
            $res = $this->http_request($url);
            $result = json_decode($res, true);
            $this->access_token = $result["access_token"];
            $mem->set($this->appid, $this->access_token, 0, 3600);
        }
		//$this->logger("TraceToken \r\n".$this->access_token);		       
    }

    //生成OAuth2的URL
    public function oauth2_authorize($redirect_url, $scope, $state = NULL)
    {
        $url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=".$this->appid."&redirect_uri=".$redirect_url."&response_type=code&scope=".$scope."&state=".$state."#wechat_redirect";
        return $url;
    }

    //生成OAuth2的Access Token
    public function oauth2_access_token($code)
    {
        $url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=".$this->appid."&secret=".$this->appsecret."&code=".$code."&grant_type=authorization_code";
        $res = $this->http_request($url);
        return json_decode($res, true);
    }

    //获取用户基本信息（OAuth2 授权的 Access Token 获取 未关注用户，Access Token为临时获取）
    public function oauth2_get_user_info($access_token, $openid)
    {
        $url = "https://api.weixin.qq.com/sns/userinfo?access_token=".$access_token."&openid=".$openid."&lang=zh_CN";
        $res = $this->http_request($url);
        return json_decode($res, true);
    }

    //获取用户基本信息
    public function get_user_info($openid)
    {
        $url = "https://api.weixin.qq.com/cgi-bin/user/info?access_token=".$this->access_token."&openid=".$openid."&lang=zh_CN";
        $res = $this->http_request($url);
        return json_decode($res, true);
    }

    //HTTP请求（支持HTTP/HTTPS，支持GET/POST）
    protected function http_request($url, $data = null)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        if (!empty($data)){
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        }
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        $output = curl_exec($curl);
        curl_close($curl);
        return $output;
    }
	   //日志记录
    
	//private function logger($log_content)
	public function logger($log_content)	
    {
        if(isset($_SERVER['HTTP_APPNAME'])){   //SAE
            sae_set_display_errors(false);
            sae_debug($log_content);
            sae_set_display_errors(true);
        }else if($_SERVER['REMOTE_ADDR'] != "127.0.0.1"){ //LOCAL
            $max_size = 1000000;
            $log_filename = "log.xml";
            if(file_exists($log_filename) and (abs(filesize($log_filename)) > $max_size)){unlink($log_filename);}
            file_put_contents($log_filename, date('Y-m-d H:i:s')." ".$log_content."\r\n", FILE_APPEND);
        }
    }
}
