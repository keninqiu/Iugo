<?php

namespace Controllers;
require_once __DIR__ . "/AbstractController.php";

class TimestampController extends AbstractController {

	protected function actionGet() {
	    $currentTimeStamp = time();
	    $response = [
	        "Timestamp" => $currentTimeStamp
	    ];
	    return $response;			
		
	}

}