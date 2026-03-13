<?php
require_once 'auth.php';

// upload_process.php

// ======================
// 1. 数据库连接配置
// ======================
$host = "127.0.0.1";
$dbname = "hbook";
$username = "root";
$password = ""; // XAMPP 默认通常是空密码，如果你设置了密码就在这里改

$conn = new mysqli($host, $username, $password, $dbname);

// 检查连接
if ($conn->connect_error) {
    die("数据库连接失败：" . $conn->connect_error);
}

$conn->set_charset("utf8mb4");

// ======================
// 2. 获取表单数据
// ======================
$title = trim($_POST['title'] ?? '');
$author = trim($_POST['author'] ?? '');
$description = trim($_POST['description'] ?? '');

// 简单校验
if ($title === '' || $author === '' || $description === '' ) {
    header("Location: upload.php?error=" . urlencode("书名作者和简介不能为空"));
    exit;
}

// 检查是否上传文件
if (!isset($_FILES['cover_image']) || $_FILES['cover_image']['error'] !== UPLOAD_ERR_OK) {
    header("Location: upload.php?error=" . urlencode("图片上传失败"));
    exit;
}

$file = $_FILES['cover_image'];

// ======================
// 3. 图片校验
// ======================
$allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
$fileType = mime_content_type($file['tmp_name']);

if (!in_array($fileType, $allowedTypes)) {
    header("Location: upload.php?error=" . urlencode("只允许上传 jpg、png、gif、webp 图片"));
    exit;
}

// 限制大小：5MB
$maxSize = 50 * 10000 * 10000;
if ($file['size'] > $maxSize) {
    header("Location: upload.php?error=" . urlencode("图片不能超过 5MB"));
    exit;
}

// ======================
// 4. 创建上传目录
// ======================
$uploadDir = "uploads/";

if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

// 生成唯一文件名
$ext = pathinfo($file['name'], PATHINFO_EXTENSION);
$newFileName = uniqid("book_", true) . "." . $ext;
$targetPath = $uploadDir . $newFileName;

// 移动文件到服务器目录
if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
    header("Location: upload.php?error=" . urlencode("保存图片到服务器失败"));
    exit;
}

// ======================
// 5. 写入数据库
// ======================
// 这次 description 先写空字符串
$sql = "INSERT INTO book (title, author, cover_image, description) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($sql);



$stmt->bind_param("ssss", $title, $author, $targetPath, $description);

if ($stmt->execute()) {
    header("Location: upload.php?success=1");
    exit;
} else {
    header("Location: upload.php?error=" . urlencode("数据库写入失败：" . $stmt->error));
    exit;
}

$stmt->close();
$conn->close();
?>