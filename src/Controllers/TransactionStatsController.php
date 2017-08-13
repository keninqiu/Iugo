<?php

namespace Controllers;

require_once __DIR__ . "/AbstractController.php";
require_once __DIR__ . "/../Models/Transaction.php";
require_once __DIR__ . "/../Repository/TransactionRepository.php";

use Models\Transaction;
use Repository\TransactionRepository;

class TransactionStatsController extends AbstractController {

    protected function actionPost() {
        /*
        method for TransactionStats endpoint
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

        $transactionRepository = new TransactionRepository();

        $existedTransaction = $transactionRepository->findAllByUserId($userId);

        
        $transactionCount = count($existedTransaction);
        $currencySum = 0;

        foreach($existedTransaction as $transaction) {
            $currencyAmount = $transaction["currency_amount"];
            $currencySum += $currencyAmount;
        }


        $response = [
            "UserId" => $userId,
            "TransactionCount" => $transactionCount,
            "CurrencySum" => $currencySum
        ];
        

        return $response;      
    }
}