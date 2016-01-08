<?php

header("Content-Type: text/html;charset=utf-8");

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

	return $conn;
}

function insert_weixin_data($val)
{
	$conn = db_conn();
	$data = array();
	foreach ($val as $key => $value) {
		$data[] = $value;
	}
	$sql = "insert into weixin_data (docid, tplid, title, url, imglink, headimage, content168, content, showurl, publish_date, sourcename) values('$data[0]', '$data[1]', '$data[2]', '$data[3]', '$data[4]', '$data[5]', '$data[6]', '$data[7]', '$data[8]', '$data[10]', '$data[9]')";
	error_log($sql);

    mysqli_query($conn, $sql);
}

?>