<?php
namespace Models;

class UserSetting {

	public $userId;
	public $dataKey;
	public $dataValue;

	public function __construct($userId,$dataKey,$dataValue) {		
		$this->userId = $userId;
		$this->dataKey = $dataKey;
		$this->dataValue = $dataValue;		
	}	
	
}