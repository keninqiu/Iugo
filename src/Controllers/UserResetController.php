<?php

namespace Controllers;

require_once __DIR__ . "/AbstractController.php";
require_once __DIR__ . "/../Models/UserSetting.php";
require_once __DIR__ . "/../Repository/UserSettingRepository.php";

use Models\UserSetting;
use Repository\UserSettingRepository;

class UserResetController extends AbstractController {

    protected function actionPost() {
        $data = $this->data;

        if(
            !$data 
            || !isset($data["UserId"])
        ) {
            $response = [
                "Error" => true,
                "ErrorMessage" => "Invalid Parameters"
            ];
            return $response;
        }

        $userId = $data["UserId"];

        $userSettingRepository = new UserSettingRepository();
        $userSettingRepository->resetByUserId($userId);

        $response = [
            "Success" => true
        ];
        return $response;      
    }
}