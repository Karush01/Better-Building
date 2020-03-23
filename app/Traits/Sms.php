<?php

namespace App\Traits;

use Config;

trait Sms
{
	function sendSms($user, $action) {

		$textme_user = Config::get('settings.sms.user');
		$textme_pass = Config::get('settings.sms.pass');

		$msg = '';
		switch ($action['action']):
			case 'ticket':
				$msg = 'נסגרה פניה :' . $action['data'];
				break;
			case 'task':
				$msg = 'נסגרה משימה :' . $action['data'];
				break;
		endswitch;

		$curl = curl_init();

		curl_setopt_array($curl, array(
			CURLOPT_URL => "https://my.textme.co.il/api",
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 30,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "POST",
			CURLOPT_POSTFIELDS => "<?xml version='1.0' encoding='UTF-8'?>
                        \r\n <sms>\r\n <user>\r\n <username>$textme_user</username>
                        \r\n <password>$textme_pass</password>\r\n </user>\r\n <source>sender</source>
                        \r\n <destinations>\r\n <phone id='someid1'>$user->phone</phone>
                        \r\n </destinations>\r\n <message>$msg</message>\r\n </sms>",
			CURLOPT_HTTPHEADER => array(
				"Cache-Control: no-cache",
				"Content-Type: application/xml"

			),
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);

		if ($err) {
			return "cURL Error #:" . $err;
		} else {
			return $response;
		}
	}
}
