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
		$response = [];
		switch($this->method) {
			case self::METHOD_GET:
				$response = $this->actionGet();
				break;
			case self::METHOD_POST:
				$response = $this->actionPost();
				break;				
		}
	    
	    return $response;	
	}
}