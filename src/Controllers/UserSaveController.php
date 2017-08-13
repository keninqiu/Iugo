<?php

namespace Controllers;

require_once __DIR__ . "/AbstractController.php";
require_once __DIR__ . "/../Models/UserSetting.php";
require_once __DIR__ . "/../Repository/UserSettingRepository.php";

use Models\UserSetting;
use Repository\UserSettingRepository;

class UserSaveController extends AbstractController {

    private function arrToOne($multi,$key_prefix="") { 
        $arr = array(); 
        foreach ($multi as $key => $val) {
            if( is_array($val) ) { 
                $arr = array_merge($arr, self::arrToOne($val,$key_prefix.".".$key)); 
            } else { //phpfensi.com 
                $comboKey = $key_prefix.".".$key;
                $arr[$comboKey] = $val; 
            } 
        } 
        return $arr; 
    } 
    protected function actionPost() {
        $data = $this->data;

        if(
            !$data 
            || !isset($data["UserId"])
            || !isset($data["Data"])
        ) {
            $response = [
                "Error" => true,
                "ErrorMessage" => "Invalid Parameters"
            ];
            return $response;
        }

        $userId = $data["UserId"];
        $data = $data["Data"];

        if(!$data) {
            $response = [
                "Error" => true,
                "ErrorMessage" => "Data is not in JSON format"
            ];
            return $response;            
        }

        $userSettingRepository = new UserSettingRepository();
        $dataArray = self::arrToOne($data);
        foreach($dataArray as $dataKey => $dataValue) {
            $existedSetting = $userSettingRepository->findByKey($userId,$dataKey);
            $userSetting = new UserSetting($userId,$dataKey,$dataValue);
            if($existedSetting) {
                $userSettingRepository->update($userSetting);
            }
            else {
                $userSettingRepository->save($userSetting);
            }
        }

        $response = [
            "Success" => true
        ];
        return $response;      
    }
}