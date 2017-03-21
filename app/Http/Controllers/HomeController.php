<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    public function getWebhook() {
        if(isset($_REQUEST['hub_challenge'])) {
            $challenge = $_REQUEST['hub_challenge'];
            $hub_verify_token = $_REQUEST['hub_verify_token'];

	        if ($hub_verify_token === env('MESSENGER_TOKEN'))
	            echo $challenge;
        }

        $input = json_decode(file_get_contents('php://input'), true);

		// Get the Senders Graph ID
		$sender = $input['entry'][0]['messaging'][0]['sender']['id'];

		// Get the returned message
		$message = $input['entry'][0]['messaging'][0]['message']['text'];

		//API Url and Access Token, generate this token value on your Facebook App Page
		$url = 'https://graph.facebook.com/v2.6/me/messages?access_token='.env('PAGE_TOKEN');

		//Initiate cURL.
		$ch = curl_init($url);

		//The JSON data.
		$jsonData = '{
		    "recipient":{
		        "id":"' . $sender . '"
		    }, 
		    "message":{
		        "text":"The message you want to return"
		    }
		}';

		//Tell cURL that we want to send a POST request.
		curl_setopt($ch, CURLOPT_POST, 1);
         
    }
}