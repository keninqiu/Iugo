<?php

include "src/Api/IugoApi.php";
use Api\IugoApi;
try {
    $API = new IugoApi($_REQUEST['request']);
    echo $API->processAPI();
} 
catch (Exception $e) {
    echo json_encode(Array('error' => $e->getMessage()));
}

?>