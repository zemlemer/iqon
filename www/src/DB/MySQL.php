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

/**
 * Class MySQL
 *
 * @package App\DB
 */
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

    /**
     * MySQL constructor.
     */
    final private function __construct()
    {

    }

    /**
     * @return \App\Contracts\DbInterface
     */
    public static function getInstance() : DbInterface
    {
        if (!self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * @param string $sql
     * @param array $bindings
     * @return array
     */
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

    /**
     * @param \App\Contracts\ConfigLayerInterface $connectionParams
     * @return \App\Contracts\DbInterface
     */
    public function setConnectIonParams(ConfigLayerInterface $connectionParams) : DbInterface
    {
        $this->connectionParams = $connectionParams;

        return $this;
    }

    /**
     * @return \PDO
     */
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

    /**
     * @return bool|int
     */
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