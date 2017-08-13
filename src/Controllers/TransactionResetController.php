<?php

namespace Controllers;

require_once __DIR__ . "/AbstractController.php";
require_once __DIR__ . "/../Models/Transaction.php";
require_once __DIR__ . "/../Repository/TransactionRepository.php";

use Models\Transaction;
use Repository\TransactionRepository;

class TransactionResetController extends AbstractController {

    protected function actionPost() {
        $data = $this->data;

        //echo "data=".json_encode($data);
        if(
            !$data 
            || (!isset($data["UserId"]) && !isset($data["TransactionId"]))
        ) {
            $response = [
                "Error" => true,
                "ErrorMessage" => "Invalid Parameters"
            ];
            return $response;
        }

        $transactionRepository = new TransactionRepository();

        if(isset($data["UserId"])) {
            $userId = $data["UserId"];
            $clearAll = $transactionRepository->resetByUserId($userId);            
        }
        else if(isset($data["TransactionId"])) {
            $transactionId = $data["TransactionId"];
            $clearAll = $transactionRepository->resetByTransactionId($transactionId);            
        }

        $response = [
            "Success" => true
        ];  

        return $response;      
    }
}