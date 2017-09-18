<?php
    require_once "Mail.php";
    $servername = "localhost";
    $username = "root";
    $password = "leela@491";
    $dbname = "neerajvkdixitdb";
    $port = "3306";


   $mysqli = new mysqli($servername,$username , $password, $dbname);
   mysqli_set_charset( $mysqli, 'utf8' );
   if (mysqli_connect_errno()) {
       $arr = array ('status'=>'fail','text'=>"Connect failed: " . mysqli_connect_error());
       echo json_encode($arr);
       exit(0);
   }
   $query = "SELECT * from whocontactmeinfo where NOTF_SENT=0";
   $result = $mysqli->query($query);
   if ($result->num_rows > 0) {
       $smtp_obj = createSMTPFactory();
       echo("there");
       while($row = $result->fetch_assoc()) {
           echo "row found";
           $name = $row["CONT_NAME"];
           $email = $row["CONT_EMAIL"];
           $sub = $row["SUBJECT"];
           $message = $row["MESSAGE"];
           $rowno = $row["ID"];
           $res = sendMail($name,$email,$sub,$message);
           if($res == true){
               $query = "UPDATE whocontactmeinfo SET NOTF_SENT=1 WHERE ID=".(string)$rowno;
               var_dump($query);
               $mysqli->query($query);
           }
       }
    } else {
       echo "0 results";
   } 

   function createSMTPFactory(){
       $smtp = Mail::factory('smtp', array(
           'host' => 'ssl://smtp.gmail.com',
           'port' => '465',
           'auth' => true,
           'username' => 'neerajvkdixit.com@gmail.com',
           'password' => 'neeraj@1234'
       ));
       //echo $smtp;
       //var_dump($smtp);
       return $smtp;
   }

   function sendMail($name,$email,$sub,$msg){
       global $smtp_obj;
       $from = 'neerajvkdixit.com@gmail.com';
       $to = 'nkpy19@gmail.com';
       $subject = 'Mail In your neerajvkdixit.com site';
       $body = "Hi, ".$name."(".$email.") contact you on your site neerajvkdixit.com. \n\n".$sub."\n\n".$msg;
       var_dump($body);
       $headers = array(
           'From' => $from,
           'To' => $to,
           'Subject' => $subject
       );

       $mail = $smtp_obj->send($to, $headers, $body);

       if (PEAR::isError($mail)) {
           echo('<p>' . $mail->getMessage() . '</p>');
           return false;
       } else {
           echo('<p>Message successfully sent!</p>');
           return true;
       }
       
   }
   $mysqli->close();
