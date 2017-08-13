<?php
namespace Repository;
require_once __DIR__ . "/AbstractRepository.php";
require_once __DIR__ . "/../Models/UserSetting.php";
use Repository\AbstractRepository;
use \PDO;
use Models\UserSetting;
class UserSettingRepository extends AbstractRepository {

    public function resetByUserId($userId) {
        $sql = '
            delete from user_setting where user_id = :userId
        ';
        $stmt = $this->connection->prepare($sql);
        $stmt->bindParam(':userId', $userId);
        return $stmt->execute();
    }

    public function findByKey($userId,$dataKey)
    {
        // Set the fetchmode to populate an instance of 'Transaction'
        // This enables us to use the following:
        // $transaction = $repository->find(1234);
        // echo $transaction->UserId;

        $stmt = $this->connection->prepare('
            SELECT * 
             FROM user_setting 
             WHERE user_id = :userId and data_key = :dataKey
        ');
        $stmt->bindParam(':userId', $userId);
        $stmt->bindParam(':dataKey', $dataKey);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'UserSetting');
        return $stmt->fetch();
        
    }

    public function getAllByUserId($userId)
    {
        // fetchAll() will do the same as above, but we'll have an array. ie:
        //    $users = $repository->findAll();
        //    echo $users[0]->firstname;

        $stmt = $this->connection->prepare('
            SELECT * FROM user_setting where user_id = :userId
        ');
        $stmt->bindParam(':userId', $userId);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'UserSetting');

        return $stmt->fetchAll();
    }

    public function save(UserSetting $userSetting)
    {
        $stmt = $this->connection->prepare('
            INSERT INTO user_setting 
                (user_id, data_key, data_value) 
            VALUES 
                (:userId, :dataKey , :dataValue)
        ');
        $stmt->bindParam(':userId', $userSetting->userId);
        $stmt->bindParam(':dataKey', $userSetting->dataKey);
        $stmt->bindParam(':dataValue', $userSetting->dataValue);
        return $stmt->execute();
    }

    public function update(UserSetting $userSetting)
    {
        $stmt = $this->connection->prepare('
            UPDATE user_setting 
            SET data_value = :dataValue
            WHERE user_id = :userId and data_key = :dataKey
        ');
        $stmt->bindParam(':userId', $userSetting->userId);
        $stmt->bindParam(':dataKey', $userSetting->dataKey);
        $stmt->bindParam(':dataValue', $userSetting->dataValue);
        return $stmt->execute();
    }

}