<?php
namespace Settings;
class HttpMethod {
	/*
	Currently we only support POST method
	*/
	const POST = "POST";
	const GET = "GET";	
	const PUT = "PUT";	
	const DELETE = "DELETE";
	const PATCH = "PATCH";
	const UNSUPPORTED = "UNSUPPORTED";
}