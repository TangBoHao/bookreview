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
function insert_review($bookid,$bookname,$bookauthor,$bookpicurl,$from_userid,$from_userpet,$from_userimg,$to_reviewid,$content)
{
	$conn=connect_db();
	$sql="INSERT INTO review (bookid,bookname,bookauthor,bookpicurl,from_userid,from_userpet,from_userimg,to_reviewid,content)
	VALUE('$bookid','$bookname','$bookauthor','$bookpicurl','$from_userid','$from_userpet','$from_userimg','$to_reviewid','$content')";
	mysqli_set_charset($conn, "utf8");
	if ($conn->query($sql) === TRUE) {
    	//echo "sucess";
    	//return TRUE;
	} else {
    	//echo "Error: " . $sql . "<br>" . $conn->error;
    	return false;
			}

	$sql="UPDATE review SET commentamount=commentamount+1
	WHERE id=$to_reviewid;";

	if ($conn->query($sql) === TRUE) {
    	//echo "sucess";
    	return TRUE;
	} else {
    	//echo "Error: " . $sql . "<br>" . $conn->error;
    	return false;
			}


	$conn->close();
}



//获取某个用户发表的书评
function get_byuserid($from_userid)
{
	$conn=connect_db();
	mysqli_set_charset($conn, "utf8");
	$sql="select * from review WHERE from_userid='$from_userid' ORDER BY reg_date DESC";
	$result = $conn->query($sql);
 
if ($result->num_rows > 0) {
    // 输出数据
    $i=0;
    while($row = $result->fetch_assoc()) {
    	
    		$re[$i]['reviewid']=$row['id'];
       		$re[$i]['bookid']=$row['bookid'];
        	$re[$i]['bookname']=$row['bookname'];
        	$re[$i]['bookauthor']=$row['bookauthor'];
        	$re[$i]['bookpicurl']=$row['bookpicurl'];
        	$re[$i]['to_reviewid']=$row['to_reviewid'];
        	$re[$i]['content']=$row['content'];
        	$re[$i]['likeamount']=$row['likeamount'];
        	$re[$i]['commentamount']=$row['commentamount'];
        	$re[$i]['reg_date']=$row['reg_date'];
        	$i++;
    	
        
         }
         //echo json_encode($re, JSON_UNESCAPED_UNICODE);
         $conn->close();
         return $re;
} else {
    //echo "null";
    $conn->close();
    return false;
}

}

//获取某个书评的评论
function get_byreviewid($to_reviewid)
{
	$conn=connect_db();
	mysqli_set_charset($conn, "utf8");
	$sql="select * from review WHERE to_reviewid='$to_reviewid' ORDER BY reg_date DESC";
	$result = $conn->query($sql);
 
if ($result->num_rows > 0) {
    // 输出数据
    $i=0;
    while($row = $result->fetch_assoc()) {
    	$re[$i]['bookid']=$row['bookid'];
        $re[$i]['reviewid']=$row['id'];
        $re[$i]['from_userid']=$row['from_userid'];
        $re[$i]['from_userpet']=$row['from_userpet'];
        $re[$i]['from_userimg']=$row['from_userimg'];       
        $re[$i]['content']=$row['content'];
        $re[$i]['likeamount']=$row['likeamount'];
        $re[$i]['commentamount']=$row['commentamount'];
        $re[$i]['reg_date']=$row['reg_date'];
        $i++;
         }
         //echo json_encode($re, JSON_UNESCAPED_UNICODE);
         $conn->close();
         return $re;
} else {
    //echo "null";
    $conn->close();
    return false;
}

}
//根据书籍的id获取特定页数书评
function get_bybookid($bookid)
{
	
	$conn=connect_db();
	mysqli_set_charset($conn, "utf8");
	$sql="select * from review WHERE bookid='$bookid'  ORDER BY reg_date DESC ";
	$result = $conn->query($sql);
 	
if ($result->num_rows > 0) {
    // 输出数据
    $i=0;
    while($row = $result->fetch_assoc()) {
    	if($row['to_reviewid']==0){
    		  $re[$i]['reviewid']=$row['id'];
        $re[$i]['from_userid']=$row['from_userid'];
        $re[$i]['from_userpet']=$row['from_userpet'];
        $re[$i]['from_userimg']=$row['from_userimg'];
        $re[$i]['to_reviewid']=$row['to_reviewid'];
        $re[$i]['content']=$row['content'];
        $re[$i]['likeamount']=$row['likeamount'];
        $re[$i]['commentamount']=$row['commentamount'];
        $re[$i]['reg_date']=$row['reg_date'];
        $i++;
    	}
      
         }
        // echo json_encode($re, JSON_UNESCAPED_UNICODE);
         $conn->close();
         echo $conn->error;
         return $re;
} else {
    //echo "null";
    $conn->close();
    return false;
}

}

//根据书籍的id获取特定页数书评并按热度排序
function get_bybookidbyhot($bookid,$page)
{
   
    $conn=connect_db();
    mysqli_set_charset($conn, "utf8");
    $sql="select * from review WHERE bookid='$bookid'  ORDER BY likeamount DESC ";
    $result = $conn->query($sql);
    
if ($result->num_rows > 0) {
    // 输出数据
    $i=0;
    while($row = $result->fetch_assoc()) {
        if($row['to_reviewid']==0){
              $re[$i]['reviewid']=$row['id'];
        $re[$i]['from_userid']=$row['from_userid'];
        $re[$i]['from_userpet']=$row['from_userpet'];
        $re[$i]['from_userimg']=$row['from_userimg'];
        $re[$i]['to_reviewid']=$row['to_reviewid'];
        $re[$i]['content']=$row['content'];
        $re[$i]['likeamount']=$row['likeamount'];
        $re[$i]['commentamount']=$row['commentamount'];
        $re[$i]['reg_date']=$row['reg_date'];
        $i++;
        }
      
         }
        // echo json_encode($re, JSON_UNESCAPED_UNICODE);
         $conn->close();
         echo $conn->error;
         return $re;
} else {
    //echo "null";
    $conn->close();
    return false;
}
}

//判断一个书评是否点过赞
function islike($reviewid,$userid)
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
		
    	return 2;
	} else{
	

    	return 1;
	
	$conn->close();
	}

	

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
        //echo "Error: " . $sql . "<br>" . $conn->error;
            }
        $sql="UPDATE review SET likeamount=likeamount-1
    WHERE id=$reviewid;";
    if ($conn->query($sql) === TRUE) {
        //echo "unlikesucess";
        return 2;
    } else {
        return "Error: " . $sql . "<br>" . $conn->error;
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
        return "Error: " . $sql . "<br>" . $conn->error;
            }
    
    $sql="UPDATE review SET likeamount=likeamount+1
    WHERE id=$reviewid;";
    if ($conn->query($sql) === TRUE) {
        //echo "likesucess";
        return 1;
    } else {
        return "Error: " . $sql . "<br>" . $conn->error;
            }
    $conn->close();
    }
    
}



//统计某本书的评论量
function countreview($bookid)
{
	$conn=connect_db();
	$sql="select * from review WHERE bookid='$bookid' and to_reviewid=0";
	$result = $conn->query($sql);
 	//var_dump($result);
 	
    $conn->close();
    return mysqli_num_rows($result);
}