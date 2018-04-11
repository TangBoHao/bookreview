<?php
require_once('config.php');
require_once('function.php');
$url = trim($_SERVER['REQUEST_URI'],'/');
$arr = explode('/',$url);
//var_dump($arr);


$nodata=array(
	'code'=>400,
	'tip'=>"必须键值没有传过来数据"
);


//根据截取的url关键字来调用对应功能
switch ($arr[2]){
	case 'getbybook':
		if(!($_POST['bookid'])){
			echo json_encode($nodata,JSON_UNESCAPED_UNICODE);
			break;
		}
		$bookid=$_POST['bookid'];
		if(!$_POST['page']){
			$page=0;
		}else{
			$page=$_POST['page'];
		}
		
		$re['list']=get_bybookid($bookid);
		if($re['list']){
			$re['code']=200;
			$re['tip']='获取成功';
		}else{
			$re['list']=[];
			$re['code']=500;
			$re['tip']="没有数据(或数据库操作失败)";
		}

		for($i=0;$i<8;$i++)
		{
			
			if(!$re['list'][$page*8+$i])
			{
				break;
			}
			$res[$i]=$re['list'][$page*8+$i];
		}
		
		$re['list']=$res;
		$re=json_encode($re,JSON_UNESCAPED_UNICODE);
		echo $re;
		break;

	case 'getbybookbyhot':
		if(!($_POST['bookid'])){
			echo json_encode($nodata,JSON_UNESCAPED_UNICODE);
			break;
		}
		$bookid=$_POST['bookid'];
		if(!$_POST['page']){
			$page=0;
		}else{
			$page=$_POST['page'];
		}
		
		$re['list']=get_bybookidbyhot($bookid);
		if($re['list']){
			$re['code']=200;
			$re['tip']='获取成功';
		}else{
			$re['list']=[];
			$re['code']=500;
			$re['tip']="没有数据(或数据库操作失败)";
		}

		for($i=0;$i<8;$i++)
		{
			
			if(!$re['list'][$page*8+$i])
			{
				break;
			}
			$res[$i]=$re['list'][$page*8+$i];
		}
		
		$re['list']=$res;
		$re=json_encode($re,JSON_UNESCAPED_UNICODE);
		echo $re;
		break;


	case 'getbyreview':
		if(!($_POST['reviewid'])){
			echo json_encode($nodata,JSON_UNESCAPED_UNICODE);
			break;
		}
		$reviewid=$_POST['reviewid'];
		$re['list']=get_byreviewid($reviewid);
		if($re['list']){
			$re['code']=200;
			$re['tip']='获取成功';
		}else{
			$re['list']=[];
			$re['code']=500;
			$re['tip']="没有数据(或数据库操作失败)";
		}
		$re=json_encode($re,JSON_UNESCAPED_UNICODE);
		echo $re;
		break;
	case 'getbyuser':
	if(!($_POST['userid'])){
			echo json_encode($nodata,JSON_UNESCAPED_UNICODE);
			break;
		}
		$userid=$_POST['userid'];
		$re['list']=get_byuserid($userid);
		
		if($re['list']){

			$re['code']=200;
			$re['tip']='获取成功';
		}else{
			$re['list']=[];
			$re['code']=500;
			$re['tip']="没有数据(或数据库操作失败)";
		}
		$re=json_encode($re,JSON_UNESCAPED_UNICODE);
		echo $re;
		break;
	case 'addreview':
	if(!($_POST['bookid']&&$_POST['from_userid']&&$_POST['from_userpet']&&$_POST['from_userimg']&&$_POST['content'])){
			echo json_encode($nodata,JSON_UNESCAPED_UNICODE);
			break;
		}
		$bookid=$_POST['bookid'];
		$bookname=$_POST['bookname'];
		$bookauthor=$_POST['bookauthor'];
		$bookpicurl=$_POST['bookpicurl'];
		$from_userid=$_POST['from_userid'];
		$from_userpet=$_POST['from_userpet'];
		$to_reviewid=$_POST['to_reviewid'];
		$content=$_POST['content'];
		$from_userimg=$_POST['from_userimg'];
		if(insert_review($bookid,$bookname,$bookauthor,$bookpicurl,$from_userid,$from_userpet,$from_userimg,$to_reviewid,$content)){
			$re['code']=200;
			$re['tip']="数据插入成功";
		
		}else{
			$re['code']=500;
			$re['tip']="数据库操作错误";
			
		}
		$re=json_encode($re,JSON_UNESCAPED_UNICODE);
		echo $re;
		break;
		//如果用户没有点赞 就点个赞 如果点过了 就取消
	case 'likereview':
	if(!($_POST['reviewid']&&$_POST['userid'])){
			echo json_encode($nodata,JSON_UNESCAPED_UNICODE);
			break;
		}
		$reviewid=$_POST['reviewid'];
		$userid=$_POST['userid'];
		$re['status']=likeonebook($reviewid,$userid);
		if($re['status']==1)
		{
			$re['code']=200;
			$re['tip']='点赞成功';
		}elseif($re['status']==2){
			$re['code']=200;
			$re['tip']='取消赞成功';
		}else{
			$re['code']=500;
			$re['tip']='数据库操作错误';
		}
		$re=json_encode($re,JSON_UNESCAPED_UNICODE);
		echo $re;
		break;

//判断某用户是否给某书评点赞了
	case 'islike':
	if(!($_POST['reviewid']&&$_POST['userid'])){
			echo json_encode($nodata,JSON_UNESCAPED_UNICODE);
			break;
		}
		$reviewid=$_POST['reviewid'];
		$userid=$_POST['userid'];
		$re['status']=islike($reviewid,$userid);
		if($re['status']==1)
		{
			$re['code']=200;
			$re['tip']='没有点赞';
			$re['status']=0;
		}elseif($re['status']==2){
			$re['code']=200;
			$re['tip']='点了赞';
			$re['status']=1;
		}else{
			$re['code']=500;
			$re['tip']='数据库操作错误';
		}
		$re=json_encode($re,JSON_UNESCAPED_UNICODE);
		echo $re;
		break;

	case 'reviewcount':
	if(!($_POST['bookid'])){
			echo json_encode($nodata,JSON_UNESCAPED_UNICODE);
			break;
		}
		$bookid=$_POST['bookid'];
		$re['num']=countreview($bookid);
		$re['code']=200;
		$re=json_encode($re,JSON_UNESCAPED_UNICODE);
		echo $re;
		break;

	default:
		$re['code']="404";
		$re['tip']="没有找到相应的功能模块，请检查请求格式";

		$re=json_encode($re,JSON_UNESCAPED_UNICODE);
		echo $re;
}