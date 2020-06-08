<?php
include_once("database.php");
$db = new Mysql();
switch ($_GET['state']) {
    // oauth.php?state=0 -> this
    // User click the link in the Client.
    // Use openid to search the latest data to display.
    case '0':
        // Get openid passed by oauth.php 'record.php?openid=OPENID'
        $openid = $_GET["openid"];
        // Search latest data by openid
        $data = $db->searchLatest($openid);
        // Construct url
        $url = "./upload.html?openid=".$openid."&name=".$data['name']."&phone=".$data['phone']."&address=".$data['address']."&temperature=".$data['temperature'];
        echo "<script>window.location.href='$url'</script>";
        break;
    
    // upload.php -> this
    // Get the data user just filled in,
    // and save them into database.
    case '1':
        $openid = $_POST["openid"];
        $name = $_POST["name"];
        $phone = $_POST["phone"];
        $address = $_POST["address"];
        $temperature = $_POST["temperature"];
        $sign = $_POST["sign"];
        console($openid);
        console($name);
        console($phone);
        console($address);
        console($temperature);
        console($sign);
        if ($sign == 0) {
            // 0 means user is going out,
            // his/her data need to be saved.
            // 1. Whether the user has gone out once today.
            $flag = $db->todayRecord($openid, $sign);
            if ($flag) {
                // 1.1 There are records today, this person can not go out.
                $msg = "您今天已经外出一次，不允许再次外出。";
                $url = "./warm.html?msg=".$msg;
                echo "<script>window.location.href='$url'</script>";
                exit;
            }
            // 2. No records. Allow to go out.
            $flag = $db->addRecord($openid, $name, $phone, $address, $temperature, $sign);
            // 3. Present result
            $url = "./success.html?sign=$sign";
            echo "<script>window.location.href='$url'</script>";
        } else {
            // 1 means user is coming in.
            // Data need to be modified. Change sign from 0 to 1.
            // 1. Did the user go out.
            $flag = $db->todayRecord($openid, 0);
            if (!$flag) {
                // $flag == false
                // User has not went out today, can not come in.
                $msg = "您今天尚未外出，无法进入。";
                $url = "./warm.html?msg=".$msg;
                echo "<script>window.location.href='$url'</script>";
                exit;
            }
            // 2. Did the user come in.
            $flag = $db->todayRecord($openid, $sign);
            if ($flag) {
                // $flag == true
                // User has come in already.
                $msg = "您今天已经进出一次，不允许再次进入。";
                $url = "./warm.html?msg=".$msg;
                echo "<script>window.location.href='$url'</script>";
                exit;
            }
            // 3. User is allowed to come in.
            $flag = $db->addRecord($openid, $name, $phone, $address, $temperature, $sign);
            // 4. Present result
            $url = "./success.html?sign=$sign";
            echo "<script>window.location.href='$url'</script>";
            exit;
        }
        break;
    
    // oauth.php?state=2 -> this
    // Search volunteer information
    case '2':
        $openid = $_GET['openid'];
        $info = $db->searchVolunteer($openid);
        // Construct url
        $url = "./register.html?openid=".$openid."&name=".$info['name']."&id=".$info['id']
                ."&gender=".$info['gender']."&birth=".$info['birth']."&phone=".$info['phone']."&address=".$info['address'];
        go($url);
    break;

    // Save volunteer information
    case '3':
        $openid = $_POST["openid"];
        $name = $_POST["name"];
        $id = $_POST["id"];
        $gender = $_POST["sign"];
        $birth = $_POST["birth"];
        $phone = $_POST["phone"];
        $address = $_POST["address"];
        console($openid." ".$name." ".$id." ".$gender." ".$birth." ".$phone." ".$address);
        // Register already or not
        $info = $db->searchVolunteer($openid);
        if (!empty($info)) {
            // Registered
            $url = "./warm.html?msg="."您已注册，请勿重复注册。";
            go($url);
        }
        // Not register
        $flag = $db->addVolunteer($openid, $name, $id, $gender, $birth, $phone, $address);
        if (!$flag) {
            // Register faild
            $url = "./warm.html?msg="."注册失败，请重新操作。";
            go($url);
        } else {
            // Register success
            $url = "success.html";
            go($url);
        }
    default:
        # code...
        break;
}
?>