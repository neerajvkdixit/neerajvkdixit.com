<?php
 // create TABLE  if not exists  vksss_otp_registration (ID INT NOT NULL  //AUTO_INCREMENT PRIMARY KEY ,  SOURCE varchar(20) default 'way2sms' , //MOBILE_NUMBER   varchar(20) , OTP INT , STATUS varchar(10) , created_at //timestamp default current_timestamp , updated TIMESTAMP DEFAULT now() ON //UPDATE now() )
    include('way2sms-api.php');
    $servername = "localhost";
    $username = "root";
    $password = "leela@491";
    $dbname = "vksssdb";
    $port = "3306";


    switch ($_SERVER['REQUEST_METHOD']){
    case 'POST':
        $arr = array ('status'=>'fail','text'=>"request post not there");
        echo json_encode($arr);
        break;
    case 'GET':
    //echo "inside get";
        if(isset($_GET["method"]) && $_GET["method"] == "sendsms"){
            if (isset($_GET["mobile"]) ) {
                $mobile_number = $_GET["mobile"];
                $number = (string) rand(pow(10, 3), pow(10, 4)-1);
                $res = sendWay2SMS ( '7015484331' , 'C9655S' , $mobile_number , 'The OTP for registration at vksss is '.$number);
		$res = $res[0];
              if (isset($res["result"]) && $res["result"] == true) {
                    $mysqli = new mysqli($servername,$username , $password, $dbname);
                    mysqli_set_charset( $mysqli, 'utf8' );
                    if (mysqli_connect_errno()) {
                        $arr = array ('status'=>'fail','text'=>"Connect failed: " . mysqli_connect_error());
                        echo json_encode($arr);
                        exit(0);
                	}
                    $query = "INSERT INTO vksss_otp_registration (MOBILE_NUMBER ,OTP , STATUS) VALUES ('". $mobile_number  ."', '".$number."', 'PENDING')";
                    $mysqli->query($query);
                    $smsSentId = $mysqli->insert_id;
                    $mysqli->close();
                    $arr = array ('status'=>'success','code'=>' '.$smsSentId);
                    echo json_encode($arr);
                    exit(0);
                }else{
                    $arr = array ('status'=>'fail','text'=>"not able to send sms");
                    echo json_encode($arr);
                }
            }
        }
        break;
    default:
        // 405 = Method Not Allowed
        http_response_code(405); // for PHP >= 5.4.0
        exit;
}

    
    
?>
