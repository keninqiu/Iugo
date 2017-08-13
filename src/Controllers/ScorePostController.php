<?php

namespace Controllers;

require_once __DIR__ . "/AbstractController.php";
require_once __DIR__ . "/../Models/ScorePost.php";
require_once __DIR__ . "/../Repository/ScorePostRepository.php";

use Models\ScorePost;
use Repository\ScorePostRepository;

class ScorePostController extends AbstractController {

    protected function actionPost() {
        $data = $this->data;

        if(
            !$data 
            || !isset($data["UserId"])
            || !isset($data["LeaderboardId"])
            || !isset($data["Score"])
        ) {
            $response = [
                "Error" => true,
                "ErrorMessage" => "Invalid Parameters"
            ];
            return $response;
        }

        $userId = $data["UserId"];
        $leaderboardId = $data["LeaderboardId"];
        $score = $data["Score"];

        $scorePost = new ScorePost($userId,$leaderboardId,$score);
        $scorePostRepository = new ScorePostRepository();

        $existedScorePost = $scorePostRepository->find($userId,$leaderboardId);
        
        if($existedScorePost) {
            //echo json_encode($existedScorePost);
            $existedScore = intval($existedScorePost["score"]);
            if($existedScore < $score) {
                $scorePostRepository->update($scorePost);
            }
            else {
                $score = $existedScore;
            }
        }
        else {
            $scorePostRepository->save($scorePost);
        }

        $rank = $scorePostRepository->getRank($userId,$leaderboardId);

        $response = [
            "UserId" => $userId,
            "LeaderboardId" => $leaderboardId,
            "Score" => $score,
            "Rank" => $rank
        ];
        

        return $response;      
    }
}