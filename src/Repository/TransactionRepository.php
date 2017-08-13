<?php
namespace Repository;
require_once __DIR__ . "/AbstractRepository.php";
require_once __DIR__ . "/../Models/Transaction.php";
use Repository\AbstractRepository;
use \PDO;
use Models\Transaction;
class TransactionRepository extends AbstractRepository{

    public function find($id)
    {
        // Set the fetchmode to populate an instance of 'Transaction'
        // This enables us to use the following:
        //     $transaction = $repository->find(1234);
        //     echo $transaction->UserId;

        $stmt = $this->connection->prepare('
            SELECT * 
             FROM transaction 
             WHERE id = :id
        ');
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'Transaction');
        return $stmt->fetch();
        
    }

    public function findAllByUserId($userId) {
        // findAllByUserId() will do the same as above, but we'll have an array. ie:
        //    $transactions = $repository->findAllByUserId();
        //    echo $transactions[0]->UserId;

        $stmt = $this->connection->prepare('
            SELECT * FROM transaction where user_id = :userId
        ');
        $stmt->bindParam(':userId', $userId);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'Transaction');
        return $stmt->fetchAll();
    }


    public function save(Transaction $transaction)
    {

        $sql = '
            INSERT INTO transaction 
                (id, user_id, currency_amount) 
            VALUES 
                (:id, :userId , :currencyAmount)
        ';
        $stmt = $this->connection->prepare($sql);

        $stmt->bindParam(':id', $transaction->transactionId);
        $stmt->bindParam(':userId', $transaction->userId);
        $stmt->bindParam(':currencyAmount', $transaction->currencyAmount);
        return $stmt->execute();
    } 
	       	
}