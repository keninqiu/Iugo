<?php

namespace Controllers;

require_once __DIR__ . "/AbstractController.php";
require_once __DIR__ . "/../Models/UserSetting.php";
require_once __DIR__ . "/../Repository/UserSettingRepository.php";

use Models\UserSetting;
use Repository\UserSettingRepository;

class UserLoadController extends AbstractController {


    private function convert2TreeArray($array) {
        $newArray = [];
        foreach($array as $key => $value) {
            if(count(explode(".", $key)) == 1) {
                $newArray[$key] = $value;
            }
            else {

                list($before, $after) = explode('.', $key, 2);

                if($before && $after) {
                    if(!isset($newArray[$before])) {
                        $newArray[$before] = [$after => $value];
                    }
                    else {
                        $newArray[$before][$after] = $value;
                    }
                }
            }
        }
        $retArray = [];
        foreach($newArray as $key => $value) {
            if($key && $value) {
                if(is_array($value)) {
                    $retArray[$key] = self::convert2TreeArray($value);
                }
                else {
                    $retArray[$key] = $value;
                }
            }
        }
        return $retArray;
    }

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

        $response = self::convert2TreeArray($userSettings);

        return $response;      
    }
}