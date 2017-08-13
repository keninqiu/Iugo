<?php

namespace Controllers;

class AbstractController {

	const METHOD_POST = "POST";
	const METHOD_GET = "GET";
	protected $method;
	protected $data;

	public function __construct($method,$data) {
		$this->method = $method;
		$this->data = $data;
	}
	public function actionJson() {
		/*
		Redirect actionJson to different function based on method.
		Currently we only support POST method
		*/
		$response = [];
		switch($this->method) {
			case self::METHOD_POST:
				$response = $this->actionPost();
				break;				
		}
	    
	    return $response;	
	}
}