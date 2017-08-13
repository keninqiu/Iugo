<?php

namespace Controllers;

require_once __DIR__ . "/AbstractController.php";
require_once __DIR__ . "/../Models/ScorePost.php";
require_once __DIR__ . "/../Repository/ScorePostRepository.php";

use Models\ScorePost;
use Repository\ScorePostRepository;

class LeaderboardGetController extends AbstractController {

    protected function actionPost() {
        /*
        method for LeaderboardGet endpoint
        */
        $data = $this->data;

        if(
            !$data 
            || !isset($data["UserId"])
            || !isset($data["LeaderboardId"])
            || !isset($data["Offset"])
            || !isset($data["Limit"])
        ) {
            $response = [
                "Error" => true,
                "ErrorMessage" => "Invalid Parameters"
            ];
            return $response;
        }

        $userId = $data["UserId"];
        $leaderboardId = $data["LeaderboardId"];
        $offset = $data["Offset"];
        if($offset < 0) {
            $offset = 0;
        }
        $limit = $data["Limit"];

        $scorePostRepository = new ScorePostRepository();

        $existedScorePost = $scorePostRepository->find($userId,$leaderboardId);
        $score = 0;
        if($existedScorePost) {
            $score = $existedScorePost["score"];
        }

        $rank = $scorePostRepository->getRank($userId,$leaderboardId);

        $allScorePosts = $scorePostRepository->findAllWithOffsetLimit($leaderboardId, $offset, $limit);
        $entries = [];
        $rankOfEntity = 1 + $offset;
        foreach($allScorePosts as $scorePost) {
            $userId = intval($scorePost["user_id"]);
            $score = intval($scorePost["score"]);
            $entries[] = [
                "UserId" => $userId,
                "Score" => $score,
                "Rank" => $rankOfEntity ++,
            ];
        }
        $response = [
            "UserId" => $userId,
            "LeaderboardId" => $leaderboardId,
            "Score" => $score,
            "Rank" => $rank,
            "Entries" => $entries
        ];
        

        return $response;      
    }
}