<?php

namespace Controllers;

require_once __DIR__ . "/AbstractController.php";
require_once __DIR__ . "/../Models/Transaction.php";
require_once __DIR__ . "/../Repository/TransactionRepository.php";

use Models\Transaction;
use Repository\TransactionRepository;

class TransactionController extends AbstractController {

    protected function actionPost() {
        $data = $this->data;
        if(
            !$data 
            || !isset($data["TransactionId"]) 
            || !isset($data["UserId"]) 
            || !isset($data["CurrencyAmount"])
            || !isset($data["Verifier"])
        ) {
            $response = [
                "Error" => true,
                "ErrorMessage" => "Invalid Parameters"
            ];
            return $response;
        }

        $transactionId = $data["TransactionId"];
        $userId = $data["UserId"];
        $currencyAmount = $data["CurrencyAmount"];
        $verifier = $data["Verifier"];

        $transaction = new Transaction($transactionId,$userId,$currencyAmount);
        $isValidTransaction = $transaction->isValid($verifier);
        if(!$isValidTransaction) {
            $response = [
                "Error" => true,
                "ErrorMessage" => "Invalid Verifier"
            ];
            return $response;            
        }

        $transactionRepository = new TransactionRepository();
        $existedTransaction = $transactionRepository->find($transactionId);

        if($existedTransaction) {
            $response = [
                "Error" => true,
                "ErrorMessage" => "Duplicate transaction with TransactionId:$transactionId"
            ];
            return $response;               
        }

        $transactionRepository->save($transaction);

        $response = [
            "Success" => true
        ];
        

        return $response;      
    }
}