<?php

namespace Rcady\Notify;

class NotifyMessage
{
    public $sms;
    public $body;
    public $params;

    public function __construct(array $params)
    {
        if (isset($params['sms'])) {
            $this->sms = $params['sms'];
        }

        $this->params = $params;
    }
}
