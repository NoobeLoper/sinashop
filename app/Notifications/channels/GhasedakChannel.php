<?php

namespace App\Notifications\Channels;

use Illuminate\Notifications\Notification;
use Ghasedak\Exceptions\ApiException;
use ghasedak\Exceptions\HttpException;

class GhasedakChannel
{
    public function send($notifiable ,Notification $notification)
    {
        if(!method_exists($notification, 'toGhasedakSms')){
            throw new \Exception('Function Not Found');
        }

        $data = $notification->toGhasedakSms($notifiable);

        $message = $data['text'];
        $receptor = $data['number'];

        $apiKey = config('services.ghasedak.key');

        try
        {
            $lineNumber = "10008566";
            $api = new \Ghasedak\GhasedakApi($apiKey);
            $api->SendSimple($receptor,$message,$lineNumber);
        }
        catch(ApiException $e){
            throw $e;
        }
        catch(HttpException $e){
            throw $e;
        }

    }
}
