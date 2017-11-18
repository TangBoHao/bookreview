<?php
require_once('config.php');
require_once('function.php');
$url = trim($_SERVER['REQUEST_URI'],'/');
$arr = explode('/',$url);
//var_dump($arr);


//根据截取的url关键字来调用对应功能
switch ($arr[2]){
	case 'getbybook':
		$bookid=$_GET['bookid'];
		$page=$_GET['page'];
		get_bybookid($bookid,$page);
		break;
	case 'getbyuser':
		$userid=$_GET['userid'];
		get_byuserid($userid);
		break;
	case 'addreview':
		$bookid=$_GET['bookid'];
		$from_userid=$_GET['from_userid'];
		$to_userid=$_GET['to_userid'];
		$content=$_GET['content'];
		if(insert_review($bookid,$from_userid,$to_userid,$content)){
			$re['code']=200;
			$re['tip']="数据插入成功";
			$re=json_encode($re,JSON_UNESCAPED_UNICODE);
		}else{
			$re['code']=200;
			$re['tip']="数据库操作错误";
			$re=json_encode($re,JSON_UNESCAPED_UNICODE);
		}
		break;
		//如果用户没有点赞 就点个赞 如果点过了 就取消
	case 'likereview':
		$reviewid=$_GET['reviewid'];
		$userid=$_GET['userid'];
		likeonebook($reviewid,$userid);
		break;
	case 'reviewcount':
		$bookid=$_GET['bookid'];
		countreview($bookid);
		break;

	default:
		echo "404";
}