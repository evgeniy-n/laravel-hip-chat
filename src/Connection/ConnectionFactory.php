<?php

namespace EvgeniyN\LaravelHipChat\Connection;


use EvgeniyN\LaravelHipChat\Auth\OAuth2;

class ConnectionFactory
{
    /**
     * @var Connection[]
     */
    protected $connections = [];

    protected $config = [];

    public function __construct(array $config)
    {
        $this->config = $config;
    }


    public function connection($name = 'default')
    {
        if (!$this->hasConnection($name)) {
            $this->createConnection($name);
        }

        return $this->connections[$name];
    }

    public function hasConnection($name)
    {
        return isset($this->connections[$name]);
    }

    public function createConnection($name)
    {
        $settings = array_get($this->config, $name);
        if (!$settings) {
            throw new \Exception("No settings from '$name'' connection");
        }

        $key    = array_get($settings, 'api_key');
        $server = array_get($settings, 'server');

        $connection = new Connection($this->createAuth($key), null, $server);

        $rooms = array_get($settings, 'rooms', []);

        $connection->setRooms($rooms);

        $this->connections[$name] = $connection;
    }

    public function createAuth($key)
    {
        return new OAuth2($key);
    }
}