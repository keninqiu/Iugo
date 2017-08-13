<?php

namespace Controllers;

require_once __DIR__ . "/AbstractController.php";
require_once __DIR__ . "/../Models/UserSetting.php";
require_once __DIR__ . "/../Repository/UserSettingRepository.php";

use Models\UserSetting;
use Repository\UserSettingRepository;

class UserLoadController extends AbstractController {

    protected function actionPost() {
        /*
        method for UserLoad endpoint
        */  

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

        if(!$data) {
            $response = [
                "Error" => true,
                "ErrorMessage" => "Data is not in JSON format"
            ];
            return $response;            
        }

        $userSettingRepository = new UserSettingRepository();
        $userSettings = $userSettingRepository->getAllByUserId($userId);

        $response = [];

        foreach($userSettings as $userSetting) {
            $dataKey = $userSetting["data_key"];
            $dataValue = $userSetting["data_value"];
            $dataValue = is_int($dataValue) ? intval($dataValue) : $dataValue;
            $dataKeyArray = explode(".", $dataKey);
            $arr = [];
            $i=0;
            for($i=count($dataKeyArray)-1;$i>0;$i--) {

                $arr[$dataKeyArray[$i]] = $dataValue;
                $dataValue = $arr;
                if($i == 1) {
                    break;
                }
                $arr = [];
            }
            $key = key($arr);
            if(isset($response[$key])) {
                $response[$key] = [
                    $response[$key],
                    $arr[$key]
                ];
            }
            else {
                $response[$key] = $arr[$key];
            }
            
        }

        return $response;      
    }
}