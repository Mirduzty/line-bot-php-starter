<?php
header('Content-Type: text/html; charset=utf-8');

date_default_timezone_set('Asia/Bangkok');
 
require 'PHPMailer-master/PHPMailerAutoload.php';

$rand_otp = rand(100, 999);

$strMessage = "กรุณาใช้รหัสนี้ในการเข้าใช้ระบบ ระหัสคือ ".$rand_otp;
 
//Create a new PHPMailer instance
$mail = new PHPMailer;
//Tell PHPMailer to use SMTP
$mail->isSMTP();
//Enable SMTP debugging
// 0 = off (for production use)
// 1 = client messages
// 2 = client and server messages
$mail->SMTPDebug = 0;

$mail->CharSet = "utf-8";
//Ask for HTML-friendly debug output
$mail->Debugoutput = 'html';
//Set the hostname of the mail server
$mail->Host = "smtp.gmail.com";
//Set the SMTP port number - likely to be 25, 465 or 587
$mail->Port = 587;
//Set the encryption system to use - ssl (deprecated) or tls
$mail->SMTPSecure = 'tls';
//Whether to use SMTP authentication
$mail->SMTPAuth = true;
//Username to use for SMTP authentication
$mail->Username = "suteerawad.tui@gmail.com";
//Password to use for SMTP authentication
$mail->Password = "tui043261832";
//Set who the message is to be sent from
$mail->setFrom('suteerawad.tui@gmail.com', 'Lodner');
//Set who the message is to be sent to
$mail->addAddress($_REQUEST['email'], '');

$mail->isHTML(true);
//Set the subject line
$mail->Subject = 'รหัสสมาชิกเข้าใช้งาน Lodner ';
//Read an HTML message body from an external file, convert referenced images to embedded,
//convert HTML into a basic plain-text alternative body
//$mail->msgHTML(file_get_contents('content.html'), dirname(__FILE__));
$mail->msgHTML($strMessage);
 
//send the message, check for errors
if (!$mail->send()) {
    echo "Mailer Error: " . $mail->ErrorInfo;
} else {
    echo "1/success_mail ";
}


?>