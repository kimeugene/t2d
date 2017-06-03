<?php

namespace App\Services;


use Symfony\Component\Config\Definition\Exception\Exception;

class MemcachedService extends BaseService
{
    private $connection;

    public function __construct($logger, $connection)
    {
        parent::__construct($logger);

        $this->connection = $connection;
    }

    public function get($key)
    {
        $value = $this->connection->get($key);
        if ($this->connection->getResultCode() == \Memcached::RES_NOTFOUND)
        {
            return false;
        }
        else
        {
            return $value;
        }
    }

    public function set($key, $value, $ttl)
    {
        $this->logger->info("setting " . $value . " under key: " . $key);
        if (!$this->connection->set($key, $value, $ttl))
        {
            throw new Exception("Cannot save to memcached");
        }
    }

    public function replace($key, $value)
    {
        $this->logger->info("replacing " . $value . " under key: " . $key);
        if (!$this->connection->replace($key, $value))
        {
            throw new Exception("Cannot save to memcached");
        }
    }
}





