<?php
	
//header("Content-Type: text/html;charset=utf-8");

require 'simple_html_dom.php';

//$url = "http://weixin.sogou.com/weixin?type=1&query=%E7%BD%91%E6%98%93%E4%BA%91%E9%9F%B3%E4%B9%90&ie=utf8";
function get($base_url, $query = array(), $header = array(), $cookie_file = NULL) {

	$ch = curl_init(); 
	$url = $base_url."?".http_build_query($query);

	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
	curl_setopt($ch, CURLOPT_URL, $url); 
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $header); 

	if(TRUE) {
		// Only calling the head
		curl_setopt($ch, CURLOPT_HEADER, true); // header will be at output
		curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1); // ADD THIS
	}

	if($cookie_file) {
    	curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_file);
    	curl_setopt($ch, CURLOPT_COOKIEFILE,$cookie_file);
	}

	$data = curl_exec($ch);
	$http_code = curl_getinfo($ch)['http_code'];
	curl_close($ch);

	if($http_code === 200) {
		return $data;
	}
	return false;
}

function get_open_id_from_name($wechat_name) {
	// 
	// curl 'http://weixin.sogou.com/weixin?query=%E6%AD%A6%E6%B1%89%E5%90%83%E5%96%9D%E7%8E%A9%E4%B9%90' 
	// -H 'Accept-Encoding: gzip, deflate, sdch' 
	// -H 'Accept-Language: zh-CN,zh;q=0.8,en;q=0.6,zh-TW;q=0.4,ja;q=0.2' 
	// -H 'Upgrade-Insecure-Requests: 1' 
	// -H 'User-Agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10_10_5) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/47.0.2526.106 Safari/537.36' 
	// -H 'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8' 
	// -H 'Cookie: ABTEST=8|1451531283|v1; SUID=7E5ABB6A6B20900A0000000056849C13; SUV=008878376ABB5A7E56849C14AA540016; SUID=7E5ABB6A1508990A0000000056849C14; weixinIndexVisited=1; SNUID=4019FE1C686C40CCDB90A7FD69F87481; sct=20; IPLOC=JP; ld=Lyllllllll2Q06yWlllllVznSxclllllzrHzKkllll9lllllxZlll5@@@@@@@@@@; PHPSESSID=849tv01r9t3eh2bt37ar0illi5; SUIR=4019FE1C686C40CCDB90A7FD69F87481; seccodeRight=success; successCount=1|Thu, 07 Jan 2016 12:05:54 GMT; LSTMV=397%2C116; LCLKINT=8296' 
	// -H 'Connection: keep-alive' 
	// --compressed

	$base_url = "http://weixin.sogou.com/weixin";
	$query = array(
        "query" => $wechat_name,
    );

	$header = array(
		'Accept-Language: zh-CN,zh;q=0.8,en;q=0.6,zh-TW;q=0.4,ja;q=0.2',
		'Upgrade-Insecure-Requests: 1',
		'User-Agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10_10_5) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/47.0.2526.106 Safari/537.36',
		'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
		'Connection: keep-alive' 
	);

	$response = get($base_url, $query, $header, "/tmp/cookie");
	// dd($response);

	if($response) {
		// get open_id & ext here
		$html = new simple_html_dom();

		$html->load($response);

		$ret = $html->find('.wx-rb');
		$href = $ret[0]->href;

		$openid = substr($href, 12, 28);
		$ext = substr($href, 49);

		return array('open_id' => $openid, 'ext' => $ext);
	}
	// wait minites
	return false;
}

function sogou_weixin($url) { 

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
	curl_setopt($ch, CURLOPT_URL, $url); 
	curl_setopt($ch, CURLOPT_POST, 1); 
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	$data = curl_exec($ch);
	$Headers = curl_getinfo($ch);
	curl_close($ch);

	// echo $data;
	// var_dump($data);

	$html = new simple_html_dom();

	$html->load($data);

	$ret = $html->find('.wx-rb');

	return $ret[0]->href;
}

function get_weixin_real_url($url)
{
	$base_url = "http://weixin.sogou.com".$url;

	$query = array();

	$header = array(
			'Accept-Language: zh-CN,zh;q=0.8,en;q=0.6,zh-TW;q=0.4,ja;q=0.2',
			'Upgrade-Insecure-Requests: 1',
			'User-Agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10_10_5) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/47.0.2526.106 Safari/537.36',
			'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
			'Connection: keep-alive' 
		);

	$response = get($base_url, $query, $header, "/tmp/cookie");

	dd($response);
}

?>