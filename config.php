<?php
header("Access-Control-Allow-Origin: *");


$servername = "localhost";
$username = "root";
$password = "";
$dbname="book";
//error_reporting(0); //关闭所有的错误提示


//过滤函数
function gjj($str)
{
    $farr = array(
        "/\\s+/",
        "/<(\\/?)(script|i?frame|style|html|body|title|link|meta|object|\\?|\\%)([^>]*?)>/isU",
        "/(<[^>]*)on[a-zA-Z]+\s*=([^>]*>)/isU",
    );
    $str = preg_replace($farr,"",$str);
    return addslashes($str);
}

function hg_input_bb($array)
{
    if (is_array($array))
    {
        foreach($array AS $k => $v)
        {
            $array[$k] = hg_input_bb($v);
        }
    }
    else
    {
        $array = gjj($array);
    }
    return $array;
}
//$_REQUEST = hg_input_bb($_REQUEST);
//$_GET = hg_input_bb($_GET);
//$_POST = hg_input_bb($_POST);