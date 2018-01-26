<?php
date_default_timezone_set("Asia/Bangkok");
$access_token = 'I/LJS0JniZFVPHHlag/+mp8HpLqhtizFs9Pxj6UkdJnVio4lIMmjnhB8/OFYN4CTwNoa1f1hnvV74wnc7eFFYn5Y70qi3eFCXSO5IfFKf82oyLS0dMVyeAAxUE/DI24J+5XM5p4Y4QCJf5on24aemgdB04t89/1O/w1cDnyilFU=';

	// Get replyToken
	$to = 'Cb71b716341c030faec503e47720f812a';
	// Build message to reply back
        
        $text = 'ทดสอบ bot ส่งข้อความ';
        
	$messages = [
		'type' => 'text',
		'text' => $text
	];
	$data = [
		'to' => $to,
		'messages' => [$messages],
	];
	$post = json_encode($data);
	$url = 'https://api.line.me/v2/bot/message/push';
	$headers = array('Content-Type: application/json;', 'Authorization: Bearer ' . $access_token);
	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);	
	curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	$result = curl_exec($ch);
        curl_close($ch);
        echo $result . "\r\n";
echo "OK";
