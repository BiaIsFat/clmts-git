<?php

$openid = $_POST["openid"];
$date = date('y-m-d h:i:s',time());
$name = $_POST["name"];
$phone = $_POST["phone"];
$address = $_POST["address"];
$temperature = $_POST["temperature"];
$sign = $_POST["sign"];

$con = mysql_connect("120.79.33.215:3306","jinhong","123");
if (!$con){
        die('Could not connect: ' . mysql_error());
    }

//判断今天是否有出入记录
mysql_select_db("clmts", $con);
$sql1 = "select * from login where openid = '".$openid."' and to_days(date) = to_days(now()) and sign = '".$sign."'";   //查询是否有当天的记录存在
$result1 = mysql_query($sql1,$con);
if (!$result1){
        die('Could not connect: ' . mysql_error());
    }

if(!mysql_fetch_array($result1)){
    //插入数据库
    mysql_select_db("clmts", $con);
    $sql2 = "insert into login(openid,date,name,phone,address,temperature,sign) values('".$openid."','".$date."','".$name."','".$phone."','".$address."','".$temperature."','".$sign."')";
    $result2 = mysql_query($sql2,$con);
    if (!$result2){
        die('Could not connect: ' . mysql_error());
    }
}
else{
    echo "Don't go out!<br><br>";
}

//查找最新一条记录
mysql_select_db("clmts", $con);
$sql = "select * from login where openid = '".$openid."' order by date desc";
$result = mysql_query($sql,$con);
if (!$result){
        die('Could not connect: ' . mysql_error());
}

//最新一条记录展示
if($row = mysql_fetch_array($result)){
    echo "The lasted record:<br>";
    echo "openid:".$row['openid']."<br>";
    echo "date:".$row['date']."<br>";
    echo "name:".$row['name']."<br>";
    echo "phone:".$row['phone']."<br>";
    echo "address:".$row['address']."<br>";
    echo "temperature:".$row['temperature']."<br>";
    echo "sign:".$row['sign']."<br>";
    }

mysql_close($con);

?>