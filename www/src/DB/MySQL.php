<?php

namespace App\DB;

use App\Contracts\{
    ConfigLayerInterface,
    DbInterface
};
use App\Exceptions\{
    DbProcessingException,
    WrongCallException
};

class MySQL implements DbInterface
{
    /**
     * @var DbInterface $instance
     */
    private static $instance;
    /**
     * @var ConfigLayerInterface $connectionParams
     */
    private $connectionParams;
    /**
     * @var \PDO $connection
     */
    private $connection;

    final private function __construct()
    {

    }

    public static function getInstance() : DbInterface
    {
        if (!self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function query(string $sql, array $bindings = [])
    {
        $statement = $this->getConnection()->prepare($sql);
        if(!$statement) {
            throw new DbProcessingException("Can't prepare statement: ". $sql);
        }

        $statement->execute($bindings);

        $errorInfo = $statement->errorInfo();

        if ($errorInfo[0] != '00000') {
            throw new DbProcessingException($errorInfo[2]);
        }

        return $statement->fetchAll();
    }

    public function setConnectIonParams(ConfigLayerInterface $connectionParams) : DbInterface
    {
        $this->connectionParams = $connectionParams;

        return $this;
    }

    private function getConnection()
    {
         if(!$this->connection) {
             if(!$this->connectionParams) {
                 throw new WrongCallException("Connection params aren't set");
             }

             $this->connection = new \PDO(
                 $this->connectionParams->get(static::CONFIG_KEY_DSN),
                 $this->connectionParams->get(static::CONFIG_KEY_USERNAME),
                 $this->connectionParams->get(static::CONFIG_KEY_PASSWORD),
                 $this->connectionParams->get(static::CONFIG_KEY_OPTIONS));
         }

         return $this->connection;
    }

    public function getLastID()
    {
        $data = $this->query("select last_insert_id() as id");

        return $data && isset($data['id']) ? (int) $data['id'] : false;
    }

    private function __clone()
    {

    }

    private function __wakeup()
    {

    }
}