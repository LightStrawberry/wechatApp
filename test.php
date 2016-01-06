<?php
	
	header("Content-Type: text/html;charset=utf-8");

	require 'simple_html_dom.php';

	$url = "http://weixin.sogou.com/weixin?type=1&query=%E7%BD%91%E6%98%93%E4%BA%91%E9%9F%B3%E4%B9%90&ie=utf8";

	function sogou_weixin($url) { 

	$ch = curl_init(); 
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
	curl_setopt($ch, CURLOPT_URL, $url); 
	curl_setopt($ch, CURLOPT_POST, 1); 
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	$data = curl_exec($ch);
	$Headers = curl_getinfo($ch);
	curl_close($ch);

	//echo $data;

	$html = new simple_html_dom();

	$html->load($data);

	$ret = $html->find('.wx-rb');

	return $ret[0]->href;

	}

?>