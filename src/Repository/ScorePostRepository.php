<?php
namespace Repository;
require_once __DIR__ . "/AbstractRepository.php";
require_once __DIR__ . "/../Models/ScorePost.php";
use Repository\AbstractRepository;
use \PDO;
use Models\ScorePost;
class ScorePostRepository extends AbstractRepository{

    public function resetByUserId($userId) {
        $sql = '
            delete from score_post where user_id = :userId
        ';
        $stmt = $this->connection->prepare($sql);
        $stmt->bindParam(':userId', $userId);
        return $stmt->execute();
    }

    public function resetByLeaderboardId($leaderboardId) {
        $sql = '
            delete from score_post where leaderboard_id = :leaderboardId
        ';
        $stmt = $this->connection->prepare($sql);
        $stmt->bindParam(':leaderboardId', $leaderboardId);
        return $stmt->execute();
    }

	public function getRank($userId,$leaderboardId) {
        $stmt = $this->connection->prepare('
            SELECT * 
             FROM score_post 
             WHERE leaderboard_id = :leaderboardId order by score desc
        ');
        $stmt->bindParam(':leaderboardId', $leaderboardId);
        $stmt->execute();
        $rank = 0;
        $allRecord = $stmt->fetchAll();    
        foreach ($allRecord as $record) {
        	$rank ++;
        	$userIdOfRecord = $record["user_id"];
        	if($userIdOfRecord == $userId) {
        		break;
        	}
        }  
        return $rank;
	}

    public function find($userId,$leaderboardId)
    {
        // Set the fetchmode to populate an instance of 'ScorePost'
        // This enables us to use the following:
        //     $scorePost = $repository->find(1234,12);
        //     echo $scorePost->Score;

        $stmt = $this->connection->prepare('
            SELECT * 
             FROM score_post 
             WHERE user_id = :userId and leaderboard_id = :leaderboardId
        ');
        $stmt->bindParam(':userId', $userId);
        $stmt->bindParam(':leaderboardId', $leaderboardId);
        $stmt->execute();
        
        $stmt->setFetchMode(PDO::FETCH_CLASS,  'ScorePost');
        return $stmt->fetch();
        
    }

    public function findAllWithOffsetLimit($leaderboardId, $offset, $limit) {
        // findAllByUserId() will do the same as above, but we'll have an array. ie:
        //    $transactions = $repository->findAllByUserId();
        //    echo $transactions[0]->UserId;
        $sql = "
            SELECT * FROM score_post where leaderboard_id = $leaderboardId order by score desc LIMIT $limit OFFSET $offset 
        ";    	
        $stmt = $this->connection->prepare($sql);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'Transaction');
        return $stmt->fetchAll();
    }

    public function save(ScorePost $scorePost)
    {
        $stmt = $this->connection->prepare('
            INSERT INTO score_post 
                (user_id, leaderboard_id, score) 
            VALUES 
                (:userId, :leaderboardId , :score)
        ');
        $stmt->bindParam(':userId', $scorePost->userId);
        $stmt->bindParam(':leaderboardId', $scorePost->leaderboardId);
        $stmt->bindParam(':score', $scorePost->score);
        return $stmt->execute();
    }

    public function update(ScorePost $scorePost)
    {
        $stmt = $this->connection->prepare('
            UPDATE score_post 
            SET score = :score
            WHERE user_id = :userId and leaderboard_id = :leaderboardId
        ');
        $stmt->bindParam(':userId', $scorePost->userId);
        $stmt->bindParam(':leaderboardId', $scorePost->leaderboardId);
        $stmt->bindParam(':score', $scorePost->score);
        return $stmt->execute();
    }
}