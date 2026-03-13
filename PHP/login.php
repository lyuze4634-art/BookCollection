<?php
session_start();
// 固定密码
$fixed_password = "114514";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $password = $_POST['password'] ?? '';

    if ($password === $fixed_password) {
        $_SESSION['book_access'] = true;
        header("Location: home.php");
        exit;
    } else {
        header("Location: index.php?error=1");
        exit;
    }
}

// 如果不是 POST 访问，直接回入口页
header("Location: index.php");
exit;