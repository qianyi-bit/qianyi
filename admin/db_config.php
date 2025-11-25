<?php
// db_config.php - 数据库配置与安全连接
$servername = "localhost";
$db_username = "root";       // 数据库用户名（非root，权限最小化）
$db_password = "123456"; // 数据库密码
$dbname = "your_database";    // 数据库名

// 初始化数据库连接
$conn = new mysqli($servername, $db_username, $db_password, $dbname);
if ($conn->connect_error) {
    // 生产环境隐藏具体错误信息
    die("数据库连接失败，请稍后再试");
}

// 设置字符集，防止宽字节注入
$conn->set_charset("utf8mb4");
?>