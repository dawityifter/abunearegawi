<?php

//Include the function library
//require "Include/Config.php";
//
//require "Include/Functions.php";
//require "Include/Header.php";

$sFirstName = $_POST["fname"];
$sLastName = $_POST["lname"];
$sEmail = $_POST["email"];
$sIsMember = $_POST['isMember'];
$sEmailSubject = "New User registration";
$sSender = "admin";
$email_array = $_POST['emaillist'];
//$_SESSION['iUserID'] = 'admin';

$sEmailMessage = {"error"=> "false",
	"FirstName"=> $sFirstName .
	"LastName" => $sLastName .
	"isMember" =>$sIsMember  .
	"Email" => $sEmail.
        "EmailList" => $email_array };

// Poke the message into email_message_pending_emp so EmailSend can find it
//$sSQL = "INSERT INTO email_message_pending_emp ".
//        "SET " . 
//			"emp_usr_id='" .$sSender. "',".
//			"emp_to_send='0'," .
//			"emp_subject='" . mysql_real_escape_string($sEmailSubject). "',".
//			"emp_message='" . mysql_real_escape_string($sEmailMessage). "'";
//RunQuery($sSQL);
//require 'EmailSend.php';
//SendEmail(htmlspecialchars($sEmailSubject), htmlspecialchars($sEmailMessage), $email_array);
echo json_encode($sEmailMessage);
?>
