<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    public function anyWebhook() {
        if(isset($_REQUEST['hub_challenge'])) {
            $challenge = $_REQUEST['hub_challenge'];
            $hub_verify_token = $_REQUEST['hub_verify_token'];

	        if ($hub_verify_token === env('MESSENGER_TOKEN'))
	            echo $challenge;
        }

        /* receive and send messages */
		$input = json_decode(file_get_contents('php://input'), true);
		if (isset($input['entry'][0]['messaging'][0]['sender']['id'])) {

		    $sender = $input['entry'][0]['messaging'][0]['sender']['id']; //sender facebook id
		    $message = $input['entry'][0]['messaging'][0]['message']['text']; //text that user sent

		    $url = 'https://graph.facebook.com/v2.6/me/messages?access_token='.env('PAGE_TOKEN');

		    /*initialize curl*/
		    $ch = curl_init($url);
		    /*prepare response*/
		    $jsonData = '{
		    "recipient":{
		        "id":"' . $sender . '"
		        },
		        "message":{
		            "text":"You said, ' . $message . '"
		        }
		    }';
		    /* curl setting to send a json post data */
		    curl_setopt($ch, CURLOPT_POST, 1);
		    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
		    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
		    if (!empty($message)) {
		        $result = curl_exec($ch); // user will get the message
		    }
		}
         
    }
}