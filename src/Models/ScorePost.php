<?php
namespace Models;

class ScorePost {
        /*
        Model for table score_post
        */  
	public $userId;
	public $leaderboardId;
	public $score;

	public function __construct($userId,$leaderboardId,$score) {		
		$this->userId = $userId;
		$this->leaderboardId = $leaderboardId;
		$this->score = $score;		
	}	
	
}