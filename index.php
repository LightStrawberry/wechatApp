<?php

require "url.php";
require "tools.php";
require 'db.php';

header("Content-Type: text/html;charset=utf-8");

        //构造url管理器
$wechat_name = "湖工大在线";

$ret = get_open_id_from_name($wechat_name);

$page = 1;

for($page = 1; $page <= 10; $page++)
{

    //http://weixin.sogou.com/gzh?openid=oIWsFt_pdO6l0yAhJaGHnuokXYu4&ext=7muVIkIDeYvnt2jzb9oKPIe5yjE8XiJ7vw6NpQ8brae-LaR3JpZUkYh4QR6twKFr 武汉吃喝玩乐
    //http://weixin.sogou.com/gzh?openid=oIWsFtyU_rHhZ4RHhZuFxHebat5U&ext=7muVIkIDeYtU_TElitYIkXBuehdpuySjK88y-VFIPaBXeFLL6KHAQl4X6kHEcHqU 湖工大在线
    //http://weixin.sogou.com/gzh?openid=oIWsFt7YYaY9jQePCR4MyQxkLOQo&ext=meTK-Q6CgYRzLem_Dup2aGrvDJ8awd_Es0FD-zw6WKlKTEEnHhqnQrtUsWE4ZE4W 之式

    $weixin_gzh = array(['name' => "武汉吃喝玩乐", 'openid' => "oIWsFt_pdO6l0yAhJaGHnuokXYu4", 'ext' => "7muVIkIDeYvnt2jzb9oKPIe5yjE8XiJ7vw6NpQ8brae-LaR3JpZUkYh4QR6twKFr"],
                        ['name' => "湖工大在线", 'openid' => "oIWsFtyU_rHhZ4RHhZuFxHebat5U", 'ext' => "meTK-Q6CgYRoD8SJVZaGgQ0SN5gKEPuRNEap337uGS6dbRE7Ckvwirtg26jTVlLt"],
                        ['name' => "之式Gishtone", 'openid' => "oIWsFt7YYaY9jQePCR4MyQxkLOQo", 'ext' => "meTK-Q6CgYRzLem_Dup2aGrvDJ8awd_Es0FD-zw6WKlKTEEnHhqnQrtUsWE4ZE4W"]);

    $url = "http://weixin.sogou.com/gzhjs?openid=".$ret['open_id']."&ext=".$ret['ext']."&cb=sogou.weixin_gzhcb&page=".$page."&gzhArtKeyWord=&tsn=0&t=1452072186637&_=1452072186462";

    //$url = "http://weixin.sogou.com/gzhjs?openid=oIWsFt7YYaY9jQePCR4MyQxkLOQo&ext=meTK-Q6CgYRzLem_Dup2aGrvDJ8awd_Es0FD-zw6WKlKTEEnHhqnQrtUsWE4ZE4W&cb=sogou.weixin_gzhcb&page=1&gzhArtKeyWord=&tsn=0&t=1452072186637&_=1452072186462";
    //$url = "http://weixin.sogou.com/gzhjs?openid=oIWsFtyU_rHhZ4RHhZuFxHebat5U&ext=meTK-Q6CgYRoD8SJVZaGgQ0SN5gKEPuRNEap337uGS6dbRE7Ckvwiq3C2f8xZn-0&cb=sogou.weixin_gzhcb&page=1&gzhArtKeyWord=&tsn=0&t=1452067211769&_=1452067211552";
    //$url = "http://weixin.sogou.com/gzhjs?openid=oIWsFt_pdO6l0yAhJaGHnuokXYu4&ext=meTK-Q6CgYR0GTTEwAMdVVs_IQ5SfCg4FhANfVlp3Yfrd4eeyxZaOhbhV7h0F1xp&cb=sogou.weixin_gzhcb&page=1&gzhArtKeyWord=&tsn=0&t=1452062763761&_=1452062763354";

    //抓取数据的部分

    // create curl resource 
    $ch = curl_init(); 

    // set url 
    curl_setopt($ch, CURLOPT_URL, $url);

    //return the transfer as a string 
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 

    // $output contains the output string 
    $output = curl_exec($ch);

    // close curl resource to free up system resources 
    curl_close($ch);

    $output = substr($output,19,-10);

    $a = json_decode($output);

    echo "全部微信文:".$a->totalItems;

    $weixin = $a->items;

    //var_dump($weixin[0]);
    foreach ($weixin as $key) {
        $b = xmltoarray($key);

        $data = array();

        $data['docid'] = cut_char($b['item']['display']['docid']);
        $data['tplid'] = cut_char($b['item']['display']['tplid']);
        $data['title'] = cut_char($b['item']['display']['title']);
        $data['url'] = cut_char($b['item']['display']['url']);
        $data['imglink'] = cut_char($b['item']['display']['imglink']);
        $data['headimage'] = cut_char($b['item']['display']['headimage']);
        $data['content168'] = cut_char($b['item']['display']['content168']);
        $data['content'] = cut_char($b['item']['display']['content168']);
        $data['showurl'] = cut_char($b['item']['display']['sourcename']);
        $data['publish_date'] = cut_char($b['item']['display']['showurl']);
        $data['sourcename'] = cut_char($b['item']['display']['date']);

        //$data['url'] = get_weixin_real_url($data['url']);

        insert_weixin_data($data);

        echo "ok";

        //暂停 10 秒
        sleep(10);

        //docid, tplid, title, url, imglink, headimage, content168, content, showurl, publish_date, sourcename
    }
    sleep(10);
}



        // $b = xmltoarray($weixin[0]);

        // var_dump($b);

        // $title = substr($b['item']['display']['title1'],9,-3);

        // $imglink = substr($b['item']['display']['imglink'],9,-3);

        // echo $imglink;
        // echo "<br>";
        // echo $title;

        

        //echo $output;

function xmltoarray($xml)
{
    $arr = xml_to_array($xml);
    $key = array_keys($arr);
    return $arr[$key[0]];
}

function xml_to_array($xml)
{
    $reg = "/<(\\w+)[^>]*?>([\\x00-\\xFF]*?)<\\/\\1>/";
    if(preg_match_all($reg, $xml, $matches))
    {
        $count = count($matches[0]);
        $arr = array();
        for($i = 0; $i < $count; $i++)
        {
            $key= $matches[1][$i];
            $val = xml_to_array( $matches[2][$i] );  // 递归
            if(array_key_exists($key, $arr))
            {
                if(is_array($arr[$key]))
                {
                    if(!array_key_exists(0,$arr[$key]))
                    {
                        $arr[$key] = array($arr[$key]);
                    }
                }else{
                    $arr[$key] = array($arr[$key]);
                }
                $arr[$key][] = $val;
            }else{
                $arr[$key] = $val;
            }
        }
        return $arr;
    }else{
        return $xml;
    }
}

function cut_char($char)
{
    return substr($char,9,-3);
}

/**
function uni_decode($uncode)
{
        $word = json_decode(preg_replace_callback('/&#(\d{5});/', create_function('$dec', 'return \'\\u\'.dechex($dec[1]);'), '"'.$uncode.'"'));
        return $word;
}
**/ 
function pregCh($test){  
//utf8下匹配中文  
    $rule ='/([\x{4e00}-\x{9fa5}]){1}/u';  
    preg_match_all($rule,$test,$result);  
    return $result;
}
?>