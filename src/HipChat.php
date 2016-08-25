<?php
/**
 * Created by PhpStorm.
 * User: jekon
 * Date: 25.08.16
 * Time: 21:12
 */

namespace EvgeniyN\LaravelHipChat;


use EvgeniyN\LaravelHipChat\Api\RoomApi;
use EvgeniyN\LaravelHipChat\Connection\ConnectionFactory;
use EvgeniyN\LaravelHipChat\ExceptionHandlers\DefaultExceptionHandler;

class HipChat
{
    /**
     * @var \EvgeniyN\LaravelHipChat\Connection\ConnectionFactory
     */
    protected $connectionFactory;

    /**
     * @var RoomApi[]
     */
    protected $rooms = [];

    /**
     * @var RoomApi
     */
    protected $room;

    protected $roomName = 'default';

    protected $roomConnection = 'default';

    public function __construct(ConnectionFactory $connections)
    {
        $this->connectionFactory = $connections;
    }

    public function notify($value, $callback = null)
    {
        if ($value instanceof \Exception) {
            return $this->sendExceptionNotification($value, $callback);
        }

        if (is_object($value)) {
            return $value = (string)$value;
        }

        if (!is_string($value)) {
            throw new \Exception('Invalid value passed');
        }

        return $this->room()->notify($value, $callback);
    }

    public function sendExceptionNotification($e, $callback)
    {
        $classes = array_keys(config('hipchat.exceptions', []));
        $config  = config("hipchat.exceptions.default");
        foreach ($classes as $class) {
            if ($e instanceof $class) {
                $config = config("hipchat.exceptions.$class");
            }
        }

        $handler = new $config['handler'];
        if (!($handler instanceof DefaultExceptionHandler)) {
            throw new \Exception('Invalid handler');
        }

        $message        = $handler->convert($e);
        $roomName       = array_get($config, 'room');
        $connectionName = array_get($config, 'connection');

        $room = $this->getRoom($roomName, $connectionName);

        return $room->notify($message, $callback);
    }

    /**
     * @return \EvgeniyN\LaravelHipChat\Api\RoomApi
     */
    public function room()
    {
        if (!$this->room) {
            $this->room = $this->getRoom();
        }

        return $this->room;
    }

    public function getRoom($name = null, $connection = null)
    {
        $key = "$connection.$name";
        if (!isset($this->rooms[$key])) {
            $this->rooms[$key] = $this->newRoom($name, $connection);
        }

        return $this->rooms[$key];
    }

    public function newRoom($name = null, $connectionName = null)
    {
        $connection = $this->connectionFactory->connection($connectionName ?: 'default');
        $connection = clone $connection;

        $config  = $connection->getRoom($name ?: 'default');
        $api     = new \GorkaLaucirica\HipchatAPIv2Client\API\RoomAPI($connection);
        $id      = array_get($config, 'id');
        $apiKey  = array_get($config, 'api_key');
        $message = array_get($config, 'message', []);

        if ($apiKey) {
            $connection->setAuth($this->connectionFactory->createAuth($apiKey));
        }

        return new RoomApi($api, $id, $message);
    }
}