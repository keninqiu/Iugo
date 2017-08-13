<?php
namespace Models;

require_once __DIR__ . "/../Settings/Config.php";
use Settings\Config;

class Transaction {
        /*
        Model for table transaction
        */  
	public $transactionId;
	public $userId;
	public $currencyAmount;

	public function __construct($transactionId,$userId,$currencyAmount) {
		$this->transactionId = $transactionId;
		$this->userId = $userId;
		$this->currencyAmount = $currencyAmount;		
	}	

	public function isValid($verifier) {
		$secretKey = Config::getSecretKey();
		$concatString = $secretKey.$this->transactionId.$this->userId.$this->currencyAmount;
		$hashString = sha1($concatString);
		if($hashString == $verifier) {
			return true;
		}
		return false;
	}	
}