<?php
declare(strict_types=1);
require 'vendor/autoload.php';
require_once __DIR__ . "/../src/Settings/Config.php";
use GuzzleHttp\Client;
use Settings\Config;

use PHPUnit\Framework\TestCase;
class ProgrammerControllerTest extends TestCase
{
	private function getSecretKey() {
		return "NwvprhfBkGuPJnjJp77UPJWJUpgC7mLz";
	}

	private function getClient() {
		$client = new Client([
		    // Base URI is used with relative requests
		    'base_uri' => Config::BASE_URL,
		    'timeout'  => 2.0,
		]);
		return $client;		
	}


	public function assertRequest($endPoint,$inputArray,$expectedArray) {
		$client = self::getClient();
	    $response = $client->request('POST', $endPoint, ['json' => $inputArray]);
	    $stream = $response->getBody();
	    $body = $stream->getContents();
	    $bodyArray = json_decode($body,true);
        $this->assertEquals($bodyArray, $expectedArray);
	}

	public function testTransaction()
	{
	    $data = [
	        'UserId' => 2
	    ];

	    $expectedResponse = [
                "Success" => true
        ];
		self::assertRequest('/TransactionReset',$data,$expectedResponse);

	    $data = [
	        'TransactionId' => 1,
	        'UserId' => 2,
	        'CurrencyAmount' => 3,
	        'Verifier' => 'eerrff'
	    ];

	    $expectedResponse = [
                "Error" => true,
                "ErrorMessage" => "Invalid Verifier"
        ];
		self::assertRequest('/Transaction',$data,$expectedResponse);


	    $data = [
	        'TransactionId' => 1,
	        'UserId' => 2,
	        'CurrencyAmount' => 3
	    ];
	    $expectedResponse = [
                "Error" => true,
                "ErrorMessage" => "Invalid Parameters"
        ];
	    self::assertRequest('/Transaction',$data,$expectedResponse);

	    
	    $data = [
	        'TransactionId' => 1,
	        'UserId' => 2,
	        'CurrencyAmount' => 3,
	        'Verifier' => 'fd6b91387c2853ac8467bb4d90eac30897777fc6'
	    ];
	    $expectedResponse = [
                "Success" => true
        ];
		self::assertRequest('/Transaction',$data,$expectedResponse);
		

	    $data = [
	        'TransactionId' => 1,
	        'UserId' => 2,
	        'CurrencyAmount' => 3,
	        'Verifier' => 'fd6b91387c2853ac8467bb4d90eac30897777fc6'
	    ];
	    $expectedResponse = [
                "Error" => true,
                "ErrorMessage" => "Duplicate transaction with TransactionId:1"
        ];
		self::assertRequest('/Transaction',$data,$expectedResponse);
	}

	public function testTransactionStats() {

	    $data = [
	        'UserId' => 2
	    ];

	    $expectedResponse = [
                "Success" => true
        ];
		self::assertRequest('/TransactionReset',$data,$expectedResponse);

	    $data = [
	        'UserId' => 3
	    ];

	    $expectedResponse = [
                "Success" => true
        ];
		self::assertRequest('/TransactionReset',$data,$expectedResponse);

	    $data = [
	        'UserId' => 4
	    ];

	    $expectedResponse = [
                "Success" => true
        ];
		self::assertRequest('/TransactionReset',$data,$expectedResponse);

		$secretKey = self::getSecretKey();

		$TransactionId = 1;
		$UserId = 2;
		$CurrencyAmount = 3;
		$concatString = $secretKey.$TransactionId.$UserId.$CurrencyAmount;
		$hashString = sha1($concatString);

	    $data = [
	        'TransactionId' => $TransactionId,
	        'UserId' => $UserId,
	        'CurrencyAmount' => $CurrencyAmount,
	        'Verifier' => $hashString
	    ];
	    $expectedResponse = [
                "Success" => true
        ];
		self::assertRequest('/Transaction',$data,$expectedResponse);		

		$TransactionId = 2;
		$UserId = 2;
		$CurrencyAmount = 6;
		$concatString = $secretKey.$TransactionId.$UserId.$CurrencyAmount;
		$hashString = sha1($concatString);

	    $data = [
	        'TransactionId' => $TransactionId,
	        'UserId' => $UserId,
	        'CurrencyAmount' => $CurrencyAmount,
	        'Verifier' => $hashString
	    ];
	    $expectedResponse = [
                "Success" => true
        ];
		self::assertRequest('/Transaction',$data,$expectedResponse);

		$TransactionId = 3;
		$UserId = 3;
		$CurrencyAmount = 5;
		$concatString = $secretKey.$TransactionId.$UserId.$CurrencyAmount;
		$hashString = sha1($concatString);

	    $data = [
	        'TransactionId' => $TransactionId,
	        'UserId' => $UserId,
	        'CurrencyAmount' => $CurrencyAmount,
	        'Verifier' => $hashString
	    ];
	    $expectedResponse = [
                "Success" => true
        ];
		self::assertRequest('/Transaction',$data,$expectedResponse);		

		$TransactionId = 4;
		$UserId = 3;
		$CurrencyAmount = 8;
		$concatString = $secretKey.$TransactionId.$UserId.$CurrencyAmount;
		$hashString = sha1($concatString);

	    $data = [
	        'TransactionId' => $TransactionId,
	        'UserId' => $UserId,
	        'CurrencyAmount' => $CurrencyAmount,
	        'Verifier' => $hashString
	    ];
	    $expectedResponse = [
            "Success" => true
        ];
		self::assertRequest('/Transaction',$data,$expectedResponse);

		$TransactionId = 5;
		$UserId = 3;
		$CurrencyAmount = 1;
		$concatString = $secretKey.$TransactionId.$UserId.$CurrencyAmount;
		$hashString = sha1($concatString);

	    $data = [
	        'TransactionId' => $TransactionId,
	        'UserId' => $UserId,
	        'CurrencyAmount' => $CurrencyAmount,
	        'Verifier' => $hashString
	    ];
	    $expectedResponse = [
                "Success" => true
        ];
		self::assertRequest('/Transaction',$data,$expectedResponse);	

		$data = [
			'UserId' => 2,
		];
	    $expectedResponse = [
            "UserId" => 2,
            "TransactionCount" => 2,
            "CurrencySum" => 9,
        ];
        self::assertRequest('/TransactionStats',$data,$expectedResponse);	

		$data = [
			'UserId' => 3,
		];
	    $expectedResponse = [
            "UserId" => 3,
            "TransactionCount" => 3,
            "CurrencySum" => 14,
        ];
        self::assertRequest('/TransactionStats',$data,$expectedResponse);	        								

		$data = [
			'UserId' => 4,
		];
	    $expectedResponse = [
            "UserId" => 4,
            "TransactionCount" => 0,
            "CurrencySum" => 0,
        ];
        self::assertRequest('/TransactionStats',$data,$expectedResponse);	

		$data = [
			'UserId' => 3,
		];
	    $expectedResponse = [
            "Success" => true
        ];
        self::assertRequest('/TransactionReset',$data,$expectedResponse);	

		$data = [
			'UserId' => 3,
		];
	    $expectedResponse = [
            "UserId" => 3,
            "TransactionCount" => 0,
            "CurrencySum" => 0,
        ];
        self::assertRequest('/TransactionStats',$data,$expectedResponse);

		$data = [
			'TransactionId' => 2,
		];
	    $expectedResponse = [
            "Success" => true
        ];
        self::assertRequest('/TransactionReset',$data,$expectedResponse);	        	                        		
	}

	public function testScorePost() {

		$data = [
			'UserId' => 2,
		];
	    $expectedResponse = [
            "Success" => true
        ];
        self::assertRequest('/ScorePostReset',$data,$expectedResponse);	

		$data = [
			'UserId' => 3,
		];
	    $expectedResponse = [
            "Success" => true
        ];
        self::assertRequest('/ScorePostReset',$data,$expectedResponse);	

		$data = [
			'UserId' => 4,
		];
	    $expectedResponse = [
            "Success" => true
        ];
        self::assertRequest('/ScorePostReset',$data,$expectedResponse);	

		$data = [
			'UserId' => 5,
		];
	    $expectedResponse = [
            "Success" => true
        ];
        self::assertRequest('/ScorePostReset',$data,$expectedResponse);	

		$data = [
			'UserId' => 6,
		];
	    $expectedResponse = [
            "Success" => true
        ];
        self::assertRequest('/ScorePostReset',$data,$expectedResponse);	

	    $data = [
	        "UserId" => 2,
	        "LeaderboardId" => 100,
	        "Score" => 10,
	    ];

	    $expectedResponse = [
	        "UserId" => 2,
	        "LeaderboardId" => 100,
	        "Score" => 10,
	        "Rank" => 1
        ];
	    self::assertRequest('/ScorePost',$data,$expectedResponse);	

	    $data = [
	        "UserId" => 3,
	        "LeaderboardId" => 100,
	        "Score" => 50,
	    ];

	    $expectedResponse = [
	        "UserId" => 3,
	        "LeaderboardId" => 100,
	        "Score" => 50,
	        "Rank" => 1
        ];
	    self::assertRequest('/ScorePost',$data,$expectedResponse);		 

	    $data = [
	        "UserId" => 2,
	        "LeaderboardId" => 100,
	        "Score" => 5,
	    ];

	    $expectedResponse = [
	        "UserId" => 2,
	        "LeaderboardId" => 100,
	        "Score" => 10,
	        "Rank" => 2
        ];
	    self::assertRequest('/ScorePost',$data,$expectedResponse);		

	    $data = [
	        "UserId" => 2,
	        "LeaderboardId" => 100,
	        "Score" => 150,
	    ];

	    $expectedResponse = [
	        "UserId" => 2,
	        "LeaderboardId" => 100,
	        "Score" => 150,
	        "Rank" => 1
        ];
	    self::assertRequest('/ScorePost',$data,$expectedResponse);		

	    $data = [
	        "UserId" => 4,
	        "LeaderboardId" => 101,
	        "Score" => 4,
	    ];

	    $expectedResponse = [
	        "UserId" => 4,
	        "LeaderboardId" => 101,
	        "Score" => 4,
	        "Rank" => 1
        ];
	    self::assertRequest('/ScorePost',$data,$expectedResponse);	
	    
	    $data = [
	        "UserId" => 5,
	        "LeaderboardId" => 100,
	        "Score" => 2,
	    ];

	    $expectedResponse = [
	        "UserId" => 5,
	        "LeaderboardId" => 100,
	        "Score" => 2,
	        "Rank" => 3
        ];
	    self::assertRequest('/ScorePost',$data,$expectedResponse);		

	    $data = [
	        "UserId" => 6,
	        "LeaderboardId" => 100,
	        "Score" => 13,
	    ];

	    $expectedResponse = [
	        "UserId" => 6,
	        "LeaderboardId" => 100,
	        "Score" => 13,
	        "Rank" => 3
        ];
	    self::assertRequest('/ScorePost',$data,$expectedResponse);		

	    $data = [
	        "UserId" => 6,
	        "LeaderboardId" => 100,
	        "Offset" => 1,
	        "Limit" => 2,
	    ];

	    $expectedResponse = [
	        "UserId" => 6,
	        "LeaderboardId" => 100,
	        "Score" => 13,
	        "Rank" => 3,
	        "Entries" => [
				[
					"UserId" => 3,
					"Score" => 50,
					"Rank" => 2
				],
				[
					"UserId" => 6,
					"Score" => 13,
					"Rank" => 3
				],				
			]

        ];
	    self::assertRequest('/LeaderboardGet',$data,$expectedResponse);	

	    $data = [
	    	"UserId" => 6
	    ];	
	    $expectedResponse = [
	    	"Success" => true
	    ]; 
	    self::assertRequest('/ScorePostReset',$data,$expectedResponse);	

	    $data = [
	    	"LeaderboardId" => 101
	    ];	
	    $expectedResponse = [
	    	"Success" => true
	    ]; 
	    self::assertRequest('/ScorePostReset',$data,$expectedResponse);		               	               		
	}

	public function testUser()
	{
	    $data = [
	    	"UserId" => 1
	    ];	
	    $expectedResponse = [
	    	"Success" => true
	    ]; 
	    self::assertRequest('/UserReset',$data,$expectedResponse);	

		$data = '{
			"UserId": 1,
			"Data": { "Piece1": {
			"SubData": 1234,
			"SubData2": "abcd" },
			"Piece2": { "SubData": {
			"SubSubData": 5678 }
			} }
		}
		';
		$data = json_decode($data,true);
	    $expectedResponse = [
	    	"Success" => true
	    ]; 
	    self::assertRequest('/UserSave',$data,$expectedResponse);	

		$data = '{
			"UserId": 1,
			"Data": { 
				"Piece1": {
					"SubData": "tvb" 
				}
			}
		}
		';
		$data = json_decode($data,true);
	    $expectedResponse = [
	    	"Success" => true
	    ]; 
	    self::assertRequest('/UserSave',$data,$expectedResponse);	

	    $data = [
	    	"UserId" => 1
	    ];	

	    $expectedResponseString = '{ "Piece1": {
			"SubData": "tvb",
			"SubData2": "abcd" },
			"Piece2": { "SubData": {
			"SubSubData": 5678 }
			} }
		';

		$expectedResponse = json_decode($expectedResponseString,true);
		self::assertRequest('/UserLoad',$data,$expectedResponse);	


		$data = '
		{
			"UserId": 1,
			"Data": { "Piece2": {
			"SubData": { "SubSubData": 9999
			 } }
			} }
		';
		$data = json_decode($data,true);
	    $expectedResponse = [
	    	"Success" => true
	    ]; 
	    self::assertRequest('/UserSave',$data,$expectedResponse);

	    $data = [
	    	"UserId" => 1
	    ];	

	    $expectedResponseString = '{ "Piece1": {
			"SubData": "tvb",
			"SubData2": "abcd" },
			"Piece2": { "SubData": {
			"SubSubData": 9999 }
			} }
		';

		$expectedResponse = json_decode($expectedResponseString,true);
		self::assertRequest('/UserLoad',$data,$expectedResponse);		    			
	}	
}