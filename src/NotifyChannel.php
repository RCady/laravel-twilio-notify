<?php

namespace Rcady\Notify;

use Exception;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Events\Dispatcher;
use NotificationChannels\Twilio\TwilioChannel;
use Illuminate\Notifications\Events\NotificationFailed;
use NotificationChannels\Twilio\Exceptions\CouldNotSendNotification;

class NotifyChannel
{
    /**
     * @var Notify
     */
    protected $notify;

    /**
     * @var Dispatcher
     */
    protected $events;

    /**
     * NotifyChannel constructor.
     *
     * @param Notify     $notify
     * @param Dispatcher $events
     */
    public function __construct(Notify $notify, Dispatcher $events)
    {
        $this->notify = $notify;
        $this->events = $events;
    }

    /**
     * Send the given notification.
     *
     * @param  mixed                                  $notifiable
     * @param  \Illuminate\Notifications\Notification $notification
     * @return mixed
     * @throws CouldNotSendNotification
     */
    public function send($notifiable, Notification $notification)
    {
        try {
            $message = $notification->toNotify($notifiable);

            return $this->notify->sendMessage($message, $notifiable->id);
        } catch (Exception $exception) {
            $event = new NotificationFailed($notifiable, $notification, 'twilio', ['message' => $exception->getMessage(), 'exception' => $exception]);
            if (function_exists('event')) { // Use event helper when possible to add Lumen support
                event($event);
            } else {
                $this->events->fire($event);
            }
        }
    }

    /**
     * Get the address to send a notification to.
     *
     * @param mixed $notifiable
     * @return mixed
     * @throws CouldNotSendNotification
     */
    protected function getTo($notifiable)
    {
        if ($notifiable->routeNotificationFor('notify')) {
            return $notifiable->routeNotificationFor('notify');
        }
        if (isset($notifiable->phone_number)) {
            return $notifiable->phone_number;
        }

        throw CouldNotSendNotification::invalidReceiver();
    }
}
