<?php
    $servername = "localhost";
    $username = "root";
    $password = "leela@491";
    $dbname = "neerajvkdixitdb";
    $port = "3306";
    switch ($_SERVER['REQUEST_METHOD']){
      case 'POST':
	  //  echo "response";
	//	exit();
            $contactername = $_POST["name"];
            $email = $_POST["email"];
            $subject = $_POST["subject"];
            $msg = $_POST["message"];
            addcontactordetails($contactername, $email, $subject , $msg);
	    break;
      case 'GET':
	    echo "inside getter";
    }
            
    function addcontactordetails($name, $email, $subject_val, $msg){
        global $servername ;
        global $username;
        global $password;
        global $dbname;
        global $port;
        $mysqli = new mysqli($servername,$username , $password, $dbname);
        mysqli_set_charset( $mysqli, 'utf8' );
        if (mysqli_connect_errno()) {
                $arr = array ('status'=>'fail','text'=>"Connect failed: " . mysqli_connect_error());
                echo json_encode($arr);
            exit(0);
        }
        if($name == "" || $email == "" ||  $subject = "" || $msg == ""){
            $arr = array ('status'=>'erro','msg'=>'please enter all values ');
        }

        $query = "INSERT INTO whocontactmeinfo (CONT_NAME ,CONT_EMAIL , SUBJECT , MESSAGE) VALUES ('". $name ."', '".$email."', '".$subject_val ."', '".$msg."')";

        //echo $query;

        $mysqli->query($query);

        //echo $query;

        //printf ("New Record has id %d.\n", $mysqli->insert_id);

        $vol_id = $mysqli->insert_id;

        //echo $vol_id;
        //echo $query;

        $mysqli->close();

        $arr = array ('query'=>$query ,'status'=>'success','text'=>'Thank you for your time. Message sent successfully');
        echo json_encode($arr);
        exit(0);
	break;
	
}

    
?>
