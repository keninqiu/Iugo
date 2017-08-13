<?php
namespace Settings;
class Config {
	/*
	Config file to set constants , can be extended to get them from database
	*/
	const HOST = "localhost";
	const DB_NAME = "iugo";
	const DB_USER = "root";
	const DB_PASS = "mysql";
	public static function getSecretKey() {
		return "NwvprhfBkGuPJnjJp77UPJWJUpgC7mLz";
	}

}