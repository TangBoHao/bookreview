<?php
function connect_db()
{
	global $servername;
	global $username;
	global $password;
	global $dbname;

	$conn = new mysqli($servername, $username, $password, $dbname);
// 检测连接
if ($conn->connect_error) {
    die("连接失败: " . $conn->connect_error);
} else{
	mysqli_set_charset($conn, "utf8");
	return $conn;
	}
}



//插入评论
function insert_review($bookid,$from_userid,$to_userid,$content)
{
	$conn=connect_db();
	$sql="INSERT INTO review (bookid,from_userid,to_userid,content)
	VALUE('$bookid','$from_userid','$to_userid','$content')";
	mysqli_set_charset($conn, "utf8");
	if ($conn->query($sql) === TRUE) {
    	//echo "sucess";
    	return ture;
	} else {
    	//echo "Error: " . $sql . "<br>" . $conn->error;
    	return false;
			}

	$conn->close();
}



//获取某个用户发表的书评
function get_byuserid($from_userid)
{
	$conn=$connect_db();
	mysqli_set_charset($conn, "utf8");
	$sql="select * from review WHERE from_userid='$from_userid'";
	$result = $conn->query($sql);
 
if ($result->num_rows > 0) {
    // 输出数据
    $i=0;
    while($row = $result->fetch_assoc()) {
        $re[$i]['reviewid']=$row['id'];
        $re[$i]['bookid']=$row['bookid'];
        $re[$i]['to_userid']=$row['to_userid'];
        $re[$i]['content']=$row['content'];
        $i++;
         }
         echo json_encode($re, JSON_UNESCAPED_UNICODE);
} else {
    echo "null";
}
$conn->close();
}


//根据书籍的id获取特定页数书评
function get_bybookid($bookid,$page)
{
	$start=($page-1)*8;
	$end=$start+8;
	$conn=connect_db();
	mysqli_set_charset($conn, "utf8");
	$sql="select * from review WHERE from_userid='$bookid' limit $start,8";
	$result = $conn->query($sql);
 	echo $sql;
if ($result->num_rows > 0) {
    // 输出数据
    $i=0;
    while($row = $result->fetch_assoc()) {
        $re[$i]['reviewid']=$row['id'];
        $re[$i]['from_userid']=$row['from_userid'];
        $re[$i]['to_userid']=$row['to_userid'];
        $re[$i]['content']=$row['content'];
        $re[$i]['likeamount']=$row['likeamount'];
        $i++;
         }
        // echo json_encode($re, JSON_UNESCAPED_UNICODE);
         return $re;
} else {
    //echo "null";
    return false;
}
$conn->close();
}

//给某一本书点赞 如果点过了就取消
function likeonebook($reviewid,$userid)
{
	$conn=connect_db();
	$sql = "SELECT * FROM review WHERE id=$reviewid";
	$count=0;
	$result = $conn->query($sql);
	if ($result->num_rows > 0) {
    // 输出每行数据
    while($row = $result->fetch_assoc()) {
        $data=$row['likepeople'];
        $people= json_decode($data,1);
        
    	}
		} else {
		$re=array();
   		$people=$re;
		}
	if($people["$userid"]){  //判断 用户是否点过赞
		$people["$userid"]=0;
		$people=json_encode($people,JSON_UNESCAPED_UNICODE);
	$sql="UPDATE review SET likepeople='$people' 
	WHERE id=$reviewid;
	";
	
	if ($conn->query($sql) === TRUE) {
    	//echo "sucess";
	} else {
    	echo "Error: " . $sql . "<br>" . $conn->error;
			}
		$sql="UPDATE review SET likeamount=likeamount-1
	WHERE id=$reviewid;";
	if ($conn->query($sql) === TRUE) {
    	echo "unlikesucess";
	} else {
    	echo "Error: " . $sql . "<br>" . $conn->error;
			}
			
	}else{
		$people["$userid"]=1;
		$people=json_encode($people,JSON_UNESCAPED_UNICODE);
	$sql="UPDATE review SET likepeople='$people' 
	WHERE id=$reviewid;
	";
	
	if ($conn->query($sql) === TRUE) {
    	//echo "sucess";
	} else {
    	echo "Error: " . $sql . "<br>" . $conn->error;
			}

	
	$sql="UPDATE review SET likeamount=likeamount+1
	WHERE id=$reviewid;";
	if ($conn->query($sql) === TRUE) {
    	echo "likesucess";
	} else {
    	echo "Error: " . $sql . "<br>" . $conn->error;
			}
	$conn->close();
	}

	

}


//统计某本书的评论量
function countreview($bookid)
{
	$conn=connect_db();
	$sql="select * from review WHERE bookid='$bookid'";
	$result = $conn->query($sql);
 	var_dump($result);
 	echo mysqli_num_rows($result);
$conn->close();
}