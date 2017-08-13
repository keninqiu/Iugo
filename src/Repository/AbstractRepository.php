<?php 
namespace Repository;
require_once __DIR__ . "/../Settings/Config.php";
use \PDO;
use Settings\Config;
class AbstractRepository
{
    protected $connection;
    
    public function __construct(PDO $connection = null)
    {
    	/*
    	generate database connection
    	*/
        $this->connection = $connection;
        if ($this->connection === null) {
            $this->connection = new PDO(
                    'mysql:host='.Config::HOST.';dbname='.Config::DB_NAME, 
                    Config::DB_USER, 
                    Config::DB_PASS
                );
            $this->connection->setAttribute(
                PDO::ATTR_ERRMODE, 
                PDO::ERRMODE_EXCEPTION
            );
        }    	
    }
}