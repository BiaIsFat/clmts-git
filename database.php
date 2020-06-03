<?php
include_once('config.php');

class Mysql {
    private static  $con        = null;
    private         $host       = HOST;
    private         $port       = PORT;
    private         $user       = USER;
    private         $password   = PASSWORD;
    private         $db         = DB;

    function __construct($host=null, $port=null, $user=null, $password=null, $db=null)
    {
        if ($host != null) {
            $this->host = $host;
            $this->port = $port;
            $this->user = $user;
            $this->password = $password;
            $this->db = $db;
        }
        if(is_null(self::$con)) {
            $this->connect();
        }
        
    }
    
    protected function connect() 
    {
        self::$con = mysqli_connect($this->host, $this->user, $this->password, $this->db, $this->port);
        if (!is_null(mysqli_connect_error())){
            echo "<script>console.log('connect faild');</script>";
            die('Could not connect: ' . mysqli_error(self::$con));
        }
        mysqli_query(self::$con, "set names utf8");
        echo "<script>console.log('connect success');</script>";
    }

    function __destruct()
    {
        if(!is_null(self::$con)) {
            mysqli_close(self::$con);
        }
    }

    /**
     * 判断今天是否有出入记录
     * 有记录: true
     * 无记录: false
     * @param   string $openid
     * @return  bool
     */
    public function todayRecord($openid, $sign) 
    {
        $sql1 = "select * from login where openid = '".$openid."' and to_days(date) = to_days(now()) and sign = $sign";   //查询是否有当天的记录存在
        $result1 = mysqli_query(self::$con, $sql1);
        console(mysqli_num_rows($result1));
        if (mysqli_num_rows($result1)>0){
            echo "<script>console.log('you already have recorded today');</script>";
        	return true;
        }
        echo "<script>console.log('no records today');</script>";
        return false;
    }
    
    /**
     * 保存打卡记录
     * @param string $openid
     * @param string $name
     * @param string $phone
     * @param string $address
     * @param float  $temperature
     * @return boolean
     */
    public function addRecord($openid, $name, $phone, $address, $temperature, $sign)
    {
        $date = date('Y-m-d H:i:s',time());
        echo "<script>console.log('openid:$openid name:$name phone:$phone address:$address temperature:$temperature date:$date sign:$sign');</script>";
        $sql2 = "insert into login(openid,date,name,phone,address,temperature,sign) values('".$openid."','".$date."','".$name."','".$phone."','".$address."','".$temperature."','".$sign."')";
        $result2 = mysqli_query(self::$con, $sql2);
        if (!$result2) {
            echo "<script>console.log(\"".mysqli_error(self::$con)."\");</script>";
        }
        return $result2 ? true : false;
    }

    /**
     * 判断今天是否有出入记录
     * @param   string $openid
     * @return  array
     */
    public function searchLatest($openid)
    {
        $sql = "select * from login where openid = '".$openid."' order by date desc";
        $result = mysqli_query(self::$con, $sql);
        $row = mysqli_fetch_array($result);
        return $row;
    }
}
?>