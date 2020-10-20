<?php
date_default_timezone_set("Asia/Bangkok");
//$access_token = 'I/LJS0JniZFVPHHlag/+mp8HpLqhtizFs9Pxj6UkdJnVio4lIMmjnhB8/OFYN4CTwNoa1f1hnvV74wnc7eFFYn5Y70qi3eFCXSO5IfFKf82oyLS0dMVyeAAxUE/DI24J+5XM5p4Y4QCJf5on24aemgdB04t89/1O/w1cDnyilFU=';
$access_token = 'uynS6eoeBDwQONY+FTnV9lchz8PoL68MaWu7lxSRF4sSSwjoh3vCVlmtMwF2QjbwzY4ZWbzJ/q8P+H9U8bIeHIV8/IW3Y6BBa/vjT/MHJg1DL8D6mSvhZVc1mVcAEYOv4ODG2xwQUFabgGs7hSgryAdB04t89/1O/w1cDnyilFU=';

// Get POST body content
$content = file_get_contents('php://input');
// Parse JSON
$events = json_decode($content, true);
// Validate parsed JSON data
if (!is_null($events['events'])) {
	// Loop through each event
	foreach ($events['events'] as $event) {
		// Reply only when message sent is in 'text' format
		if ($event['type'] == 'message' && $event['message']['type'] == 'text') {
			// Get text sent
			
			$text = $event['message']['text'].'type'.$event['source']['type'];
			// Get replyToken
			$replyToken = $event['replyToken'];

			// Build message to reply back
			$messages = [
				'type' => 'text',
				'text' => $text
			];

			// Make a POST Request to Messaging API to reply to sender
			$url = 'https://api.line.me/v2/bot/message/reply';
			$data = [
				'replyToken' => $replyToken,
				'messages' => [$messages],
			];
			$post = json_encode($data);
			$headers = array('Content-Type: application/json', 'Authorization: Bearer ' . $access_token);

			$ch = curl_init($url);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
			$result = curl_exec($ch);
			curl_close($ch);

			echo $result . "\r\n";
		}else if($event['type'] != ''){
		
			
			// Get text sent
			if($event['source']['type'] == 'user'){
			
				$show_user_id = $event['source']['userId'];
				
			}else if($event['source']['type'] == 'group'){
				
				$show_user_id = $event['source']['groupId'];
				
			}
			
			$text = $event['type'].'</br> userID'.$show_user_id.' </br>'.$event['source']['type'].'</br> time'.$event['timestamp'];
			//timestamp substr left 10 charecter or php date('Y-m-d H:i:s',time);
			// Get replyToken
			$replyToken = $event['replyToken'];

			// Build message to reply back
			$messages = [
				'type' => 'text',
				'text' => $text
			];

			// Make a POST Request to Messaging API to reply to sender
			$url = 'https://api.line.me/v2/bot/message/reply';
			$data = [
				'replyToken' => $replyToken,
				'messages' => [$messages],
			];
			$post = json_encode($data);
			$headers = array('Content-Type: application/json', 'Authorization: Bearer ' . $access_token);

			$ch = curl_init($url);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
			$result = curl_exec($ch);
			curl_close($ch);

			echo $result . "\r\n";
			
			if($event['type'] == 'follow'){
			
				$url = 'https://api.line.me/v2/bot/profile/'.$show_user_id;

				$headers = array('Authorization: Bearer ' . $access_token);
				$ch = curl_init($url);
				curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);			
				curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
				curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
				$result = curl_exec($ch);

				if($result){

					$userData = json_decode($result, true);
					// Get text sent

					$text = $userData['displayName'].'</br>'.$userData['userId'];
					// Get replyToken
					$to = $event['source']['userId'];

					// Build message to reply back
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

					$headers = array('Content-Type: application/json', 'Authorization: Bearer ' . $access_token);
					$ch = curl_init($url);
					curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);	
					curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
					curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
					curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
					$result = curl_exec($ch);

				}

				curl_close($ch);
				echo $result . "\r\n";
				
			}else if($event['type'] == 'join'){
			
				$text = 'Join GroupID:'.$event['source']['groupId'].' </br>เวลา :'.date('Y-m-d H:i:s',substr($event['timestamp'],0,10)).'['.substr($event['timestamp'],0,10).']';
				// Get replyToken
				$to = $event['source']['groupId'];

				// Build message to reply back
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

				$headers = array('Content-Type: application/json', 'Authorization: Bearer ' . $access_token);
				$ch = curl_init($url);
				curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);	
				curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
				curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
				curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
				$result = curl_exec($ch);
				curl_close($ch);
				echo $result . "\r\n";
			
			}
			
			
		}
	}
}else{

	$text = 'ทดสอบ Bot ส่งข้อมความ';
	// Get replyToken
	$to = 'U4185f679aa03ed00d85b3448abbfc72e';

	// Build message to reply back
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

	$headers = array('Content-Type: application/json', 'Authorization: Bearer ' . $access_token);
	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);	
	curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	$result = curl_exec($ch);

}

echo "OK";
