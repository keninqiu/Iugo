<?php
/*
$string = '{
"Piece1": {
"SubData": 1234,
"SubData2": "abcd" },
"Piece2": { "SubData": {
"SubSubData": 9999 }
} }';
$array = json_decode($string,true);
var_dump($array);
$string2 = json_encode($array);
echo $string2;
*/
function convert2TreeArray($array) {
	$newArray = [];
	foreach($array as $key => $value) {
		if(count(explode(".", $key)) == 1) {
			$newArray[$key] = $value;
		}
		else {

			list($before, $after) = explode('.', $key, 2);

			if($before && $after) {
				if(!isset($newArray[$before])) {
					$newArray[$before] = [$after => $value];
				}
				else {
					$newArray[$before][$after] = $value;
				}
			}
		}
	}
	$retArray = [];
	foreach($newArray as $key => $value) {
		if($key && $value) {
			if(is_array($value)) {
				$retArray[$key] = convert2TreeArray($value);
			}
			else {
				$retArray[$key] = $value;
			}
		}
	}
	return $retArray;
}

$array = [
	"Piece1.SubData" => 1234,
	"Piece1.SubData2" => "abcd",
	"Piece2.SubData.SubSubData" => 9999,
];
$treeArray = convert2TreeArray($array);
$json = json_encode($treeArray);
echo $json;
//var_dump($treeArray);