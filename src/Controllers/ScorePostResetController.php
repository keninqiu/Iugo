<?php

namespace Controllers;

require_once __DIR__ . "/AbstractController.php";
require_once __DIR__ . "/../Models/ScorePost.php";
require_once __DIR__ . "/../Repository/ScorePostRepository.php";

use Models\ScorePost;
use Repository\ScorePostRepository;

class ScorePostResetController extends AbstractController {
        /*
        method for ScorePostReset endpoint
        */ 
    protected function actionPost() {
        $data = $this->data;

        if(
            !$data 
            || (!isset($data["UserId"]) && !isset($data["LeaderboardId"]))
        ) {
            $response = [
                "Error" => true,
                "ErrorMessage" => "Invalid Parameters"
            ];
            return $response;
        }

        $scorePostRepository = new ScorePostRepository();
        if(isset($data["UserId"])) {
            $scorePostRepository->resetByUserId($data["UserId"]);
        }
        else if(isset($data["LeaderboardId"])) {
            $scorePostRepository->resetByLeaderboardId($data["LeaderboardId"]);
        }


        $response = [
            "Success" => true
        ];
        

        return $response;      
    }
}