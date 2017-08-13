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
        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
        header("Cache-Control: no-store, no-cache, must-revalidate");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");
        header("Access-Control-Allow-Orgin: *");
        header("Access-Control-Allow-Methods: *");
        header("Content-Type: application/json");
        
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
                $data = file_get_contents("php://input");
                $this->data = json_decode($data,true);
                break;
            case 'GET':
                $this->data = $this->_cleanInputs($_GET);
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
        header("HTTP/1.1 " . $status . " " . $this->_requestStatus($status));
        return json_encode($data, JSON_PRETTY_PRINT); // JSON_PRETTY_PRINT only works in PHP >= 5.4
    }

    private function _cleanInputs($data) {
        $clean_input = Array();
        if (is_array($data)) {
            foreach ($data as $k => $v) {
                $clean_input[$k] = $this->_cleanInputs($v);
            }
        } 
        else {
            $clean_input = trim(strip_tags($data));
        }
        return $clean_input;
    }

    private function _requestStatus($code) {
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