<?php
require_once('config.php');

// 创建连接
$conn = new mysqli($servername, $username, $password);
// 检测连接
if ($conn->connect_error) {
    die("connect error: " . $conn->connect_error);
} 

// 创建数据库
$sql = "CREATE DATABASE book";
if ($conn->query($sql) === TRUE) {
    echo "succes";
} else {
    echo "Error creating database: " . $conn->error;
}

$conn->close();


//创建储存书评的数据表
// 创建连接
$conn = new mysqli($servername, $username, $password, $dbname);
// 检测连接
if ($conn->connect_error) {
    die("连接失败: " . $conn->connect_error);
} 

// 使用 sql 创建数据表
$sql = "CREATE TABLE review (
id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
bookid VARCHAR(30) NOT NULL,
from_userid VARCHAR(30) NOT NULL,
from_userpet VARCHAR(30) NOT NULL,
from_userimg VARCHAR(600) NOT NULL,
to_reviewid VARCHAR(30) NOT NULL default 0,
content VARCHAR(5000) NOT NULL,
likeamount INT NOT NULL default 0,
commentamount INT NOT NULL default 0,
likepeople VARCHAR(3000) NOT NULL,
reg_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)default charset=utf8";

if ($conn->query($sql) === TRUE) {
    echo "Table review created successfully";
} else {
    echo "创建数据表错误: " . $conn->error;
}


$conn->close();