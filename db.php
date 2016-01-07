<?php

function db_conn()
{
	$servername = "127.0.0.1";
	$username = "root";
	$password = "root";
	$dbname = "weixin";

	// Create connection
	$conn = new mysqli($servername, $username, $password, $dbname);
	// Check connection
	if ($conn->connect_error) {
	    die("Connection failed: " . $conn->connect_error);
	}

	echo "fuck";
}

db_conn();

function insert_weixin_data()
{
	$sql = "";
	
}

?>