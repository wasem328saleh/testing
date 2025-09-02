<?php

namespace App\Traits;

use Illuminate\Support\Facades\Http;

trait WhatsAppFunctions
{

    use GeneralTrait;
    public function send_text_message($phone_number,$message){
        $wasem=Http::withHeaders([
            'Content-Type'=>'application/json',
            'Token'=>'95d2af7624f42c9d8be8e5f37c615bcf8f04d7c738b6fc5b7d04c34fea618ebf5ff2a0b85aca2960'
        ])->post('https://api.wassenger.com/v1/messages', [
            'phone' => $phone_number,
            'message' =>$message,

        ]);
        if ($wasem->successful()) {
            // Handle successful response
            return true;
        } else {
            // Handle error response
            return false;
        }
    }

    public function send_file_message($phone_number,$message,$file,$expiration,$viewOnce){

        $file_url=$this->UploadeImage('whatsapp_send',$file);
        $media=[
        'url'=>'https://test.spikecode.net/logo-send.png',
//        'url'=>url($file_url),
            'expiration'=>$expiration,
        'viewOnce'=>$viewOnce
        ];
//        if (!is_null($expiration)){
//            $media=[
//                'url'=>url($file_url),
//                'expiration'=>$expiration,
//                'viewOnce'=>$viewOnce
//            ];
//        }
        $wasem=Http::withHeaders([
            'Content-Type'=>'application/json',
            'Token'=>'95d2af7624f42c9d8be8e5f37c615bcf8f04d7c738b6fc5b7d04c34fea618ebf5ff2a0b85aca2960'
        ])->post('https://api.wassenger.com/v1/messages', [
            'phone' => $phone_number,
            'message' =>$message,
            'media'=>$media
        ]);
        if ($wasem->successful()) {
            // Handle successful response
            return true;
        } else {
            // Handle error response
            return false;
        }
    }

}
