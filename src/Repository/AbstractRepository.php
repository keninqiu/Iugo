<?php 
namespace Repository;
use \PDO;
class AbstractRepository
{
    protected $connection;
    
    public function __construct(PDO $connection = null)
    {
        $this->connection = $connection;
        if ($this->connection === null) {
            $this->connection = new PDO(
                    'mysql:host=localhost;dbname=iugo', 
                    'root', 
                    'mysql'
                );
            $this->connection->setAttribute(
                PDO::ATTR_ERRMODE, 
                PDO::ERRMODE_EXCEPTION
            );
        }    	
    }
}