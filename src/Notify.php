<?php

namespace Rcady\Notify;

use Twilio\Rest\Client as TwilioService;
use NotificationChannels\Twilio\TwilioConfig;
use NotificationChannels\Twilio\Exceptions\CouldNotSendNotification;

class Notify
{
    /**
     * @var TwilioService
     */
    protected $twilioService;

    /**
     * @var TwilioConfig
     */
    private $config;

    /**
     * Twilio constructor.
     *
     * @param  TwilioService $twilioService
     * @param TwilioConfig $config
     */
    public function __construct(TwilioService $twilioService, TwilioConfig $config)
    {
        $this->twilioService = $twilioService;
        $this->config = $config;
    }

    public function sendMessage(NotifyMessage $message, $to)
    {
        if ($message instanceof NotifyMessage) {
            $params = $message->params;

            if (!empty($message->sms)) {
                $params['sms'] = $message->sms;
            }

            $notification = $this->twilioService
                ->notify->services(config('services.twilio.notify_service_id'))
                ->notifications->create($params);
        }

        throw CouldNotSendNotification::invalidMessageObject($message);
    }
}
