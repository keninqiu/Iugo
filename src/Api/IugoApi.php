<?php
namespace Api;
require_once __DIR__ . "/../Settings/HttpMethod.php";
use Settings\HttpMethod;
class IugoApi
{
    protected $method = '';    
    protected $endpoint = '';
    protected $data = [];

    public function __construct($request) {
        /*
        initialization of IugoApi object for the fields of method and endpoint
        */
        $args = explode('/', rtrim($request, '/'));
        $this->endpoint = array_shift($args);

        $this->method = strtoupper($_SERVER['REQUEST_METHOD']);

        if ($this->method == HttpMethod::POST && array_key_exists('HTTP_X_HTTP_METHOD', $_SERVER)) {

            if(in_array($_SERVER['HTTP_X_HTTP_METHOD'],[HttpMethod::PUT,HttpMethod::DELETE])) {
                $this->method = $_SERVER['HTTP_X_HTTP_METHOD'];
            }
            else {
                $this->method = HttpMethod::UNSUPPORTED;
            }
        }
    }

    public function processAPI() {
        /*
        get data of restful request with different methods, in this case we only need "POST" method.
        */
        $file = __dir__ . "/../Controllers/" . $this->endpoint . "Controller.php";

        if (file_exists($file)) {
            require $file;
            $class = "\\Controllers\\" .$this->endpoint . "Controller";

        } else {
            throw new \Exception($file . " does not exist.");
        }

        $response = [];
        switch($this->method) {
            case HttpMethod::DELETE:
            case HttpMethod::POST:
            case HttpMethod::PUT:
            case HttpMethod::PATCH:
                $data = file_get_contents("php://input");
                $this->data = json_decode($data,true);
                break;
            case HttpMethod::GET:
                break;
            default:
                $response = [
                    "Error" => true,
                     "ErrorMessage" => "Unsupported Method: ".$this->method
                ];  
                $status = 405;          
                return $this->_response($response,$status);
                break;
        }

        $worker = new $class($this->method,$this->data);
        $response = $worker->actionJson();

        return $this->_response($response);

    }

    private function _response($data, $status = 200) {
        /*
        generate output with data and status, if status is not provided, it will be 200 as default
        */        
        header("HTTP/1.1 " . $status . " " . $this->_requestStatus($status));
        if($data) {
            return json_encode($data, JSON_PRETTY_PRINT); // JSON_PRETTY_PRINT only works in PHP >= 5.4
        }
        return '{}';
    }


    private function _requestStatus($code) {
        /*
        return status message with code provided
        */
        $status = array(  
            200 => 'OK',
            404 => 'Not Found',   
            405 => 'Method Not Allowed',
            500 => 'Internal Server Error',
        ); 
        return ($status[$code]) ? $status[$code] : $status[500]; 
    }
 }

?>