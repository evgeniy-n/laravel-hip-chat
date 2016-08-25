<?php

namespace EvgeniyN\LaravelHipChat\Connection;

use GorkaLaucirica\HipchatAPIv2Client\Auth\AuthInterface;
use GorkaLaucirica\HipchatAPIv2Client\Client;

class Connection extends Client
{
    protected $rooms;

    public function setRooms(array $config)
    {
        $this->rooms = $config;
    }

    public function getRoom($name)
    {
        $settings = array_get($this->rooms, $name);
        if (!$settings) {
            throw new \Exception("Room '$name' not defined");
        }

        return $settings;
    }

    public function setAuth(AuthInterface $auth)
    {
        $this->auth = $auth;
    }
}