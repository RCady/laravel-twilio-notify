<?php

namespace Rcady\Notify;

use NotificationChannels\Twilio;
use NotificationChannels\TwilioMessage;

class Notify extends Twilio
{
    public function sendMessage(TwilioMessage $message, $to)
    {
        if ($message instanceof TwilioMessage) {
            $params = [
                'identity' => $to,
                'body' => trim($message->content),
            ];

            return $this->twilioService
                ->notify->services(config('services.notify_service_id'))
                ->notifications->create($params);
        }

        throw CouldNotSendNotification::invalidMessageObject($message);
    }
}