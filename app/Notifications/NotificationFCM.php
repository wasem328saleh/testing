<?php

namespace App\Notifications;

//use App\Enums\NotificationTitle;
//use App\Models\Notification;
//use App\Models\UserNotification;
//use Auth;
use Exception;

//use http\Client;
//use GuzzleHttp\Client;


use GuzzleHttp\Client;
use Illuminate\Support\Str;

class NotificationFCM
{
//    public function initSendNotify($users, $title, $data, $type, $description)
//    {
//        $notify = Notification::create([
//            'title' => $title,
//            'type' => $type,
//            'data' => json_encode($data),
//            'is_read' => false,
//            'description' => $description,
//        ]);
//        $tokens = [];
//        foreach ($users as $user) {
//            UserNotification::create([
//                'user_id' => $user->id,
//                'notification_id' => $notify->id
//            ]);
//            foreach ($user->device_tokens as $device_token) {
//                $tokens[] = $device_token->token;
//
//            }
//        }
//
//        $body = [
//            'id' => strval($notify->id),
//            'type' => $type,
//            'data' => json_encode($data),
//            'description' => $description,
//        ];
//        foreach ($tokens as $token) {
//
//            $this->send_notify(
//                $token,
//                $title,
//                $body,
//            );
//
//        }
//    }

    public function send_notify($Tokens, $notify)
    {
        $serviceAccountPath = public_path('service-account-file.json');
        $projectId = 'signature-grup';


        $id = $notify->id;
        $type = $notify->type;
        foreach ($Tokens as $token)
        {

            $message = [
                'token' => $token,
                "data" => [
                    'id' => strval($id),
                    'title' => $notify->title,
                    'body' => $notify->body,
                    'type' => strval($type),
                    "click_action" => "FLUTTER_NOTIFICATION_CLICK",
                ]
            ];

            try {
                $accessToken = $this->getAccessToken($serviceAccountPath);
                $response = $this->sendMessage($accessToken, $projectId, $message);
//            return $response;
            } catch (Exception $e) {
                return 'Error: ' . $e->getMessage();
            }
        }
    }
    public function send_notify_mobile($Tokens, $notify)
    {
        $serviceAccountPath = public_path('service-account-file.json');
        $projectId = 'signature-grup';
        $id=$notify->id;
        $type=$notify->type;
        foreach ($Tokens as $token){

            $message = [
                'token' => $token,
                'notification' => [
                    'title' => $notify->title,
                    'body' => $notify->body,
                ],
                "data" => [
                    'id' =>strval($id),
                    'type' =>strval($type),
                    "click_action" => "FLUTTER_NOTIFICATION_CLICK",
                ]
            ];

            try {
                $accessToken = $this->getAccessToken($serviceAccountPath);
                $response = $this->sendMessage($accessToken, $projectId, $message);
//                return  $response;
            } catch (Exception $e) {
                return 'Error: ' . $e->getMessage();
            }
        }
    }
    function getAccessToken($serviceAccountPath)
    {
        $client = new Client();
        $client->setAuthConfig($serviceAccountPath);
        $client->addScope('https://www.googleapis.com/auth/firebase.messaging');
        $client->useApplicationDefaultCredentials();
        $token = $client->fetchAccessTokenWithAssertion();
        return $token['access_token'];
    }
    function sendMessage($accessToken, $projectId, $message)
    {
        $url = 'https://fcm.googleapis.com/v1/projects/' . $projectId . '/messages:send';
        $headers = [
            'Authorization: Bearer ' . $accessToken,
            'Content-Type: application/json',
        ];
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(['message' => $message]));
        $response = curl_exec($ch);
        if ($response === false) {
            throw new Exception('Curl error: ' . curl_error($ch));
        }
        curl_close($ch);
        return json_decode($response, true);

    }
}


