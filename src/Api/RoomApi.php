<?php

namespace EvgeniyN\LaravelHipChat\Api;

use GorkaLaucirica\HipchatAPIv2Client\Model\Message;

class RoomApi
{
    /**
     * @var \GorkaLaucirica\HipchatAPIv2Client\API\RoomAPI
     */
    protected $api;

    protected $id;

    protected $message = [];

    public function __construct($api, $id, array $messageConfig)
    {
        $this->api     = $api;
        $this->id      = $id;
        $this->message = $messageConfig;
    }

    /**
     * @param      $text
     * @param null $callback
     */
    public function notify($text, $callback = null)
    {
        $message = $this->newMessage();

        $message->setMessage($text);

        if (is_callable($callback)) {
            $message = call_user_func($callback, $message);
        }

        return $this->api->sendRoomNotification($this->id, $message);
    }

    public function newMessage()
    {
        $message = new Message();
        if ($c = $this->getMessageColor()) {
            $message->setColor($c);
        }

        if ($f = $this->getMessageFormat()) {
            $message->setMessageFormat($f);
        }

        if ($n = $this->getMessageNotify() !== null) {
            $message->setNotify($n);
        }

        return $message;
    }

    protected function getMessageColor()
    {
        return array_get($this->message, 'color');
    }

    protected function getMessageFormat()
    {
        return array_get($this->message, 'format');

    }

    protected function getMessageNotify()
    {
        return array_get($this->message, 'notify');

    }
}