<?php
session_start();
// 如果已经通过密码验证，直接进入首页
if (isset($_SESSION['book_access']) && $_SESSION['book_access'] === true) {
    header("Location: home.php");
    exit;
}

$error = $_GET['error'] ?? '';
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>图书管理系统入口</title>
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, "Microsoft YaHei", sans-serif;
            background: linear-gradient(135deg, #f2f6ff, #dfe9f3);
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .login-box {
            width: 380px;
            background: #fff;
            padding: 35px 30px;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.12);
            text-align: center;
        }

        .login-box h1 {
            margin: 0 0 10px;
            font-size: 26px;
            color: #222;
        }

        .login-box p {
            margin: 0 0 20px;
            color: #666;
            font-size: 14px;
        }

        .login-box input[type="password"] {
            width: 100%;
            padding: 12px 14px;
            border: 1px solid #ccc;
            border-radius: 10px;
            font-size: 15px;
            outline: none;
            margin-bottom: 15px;
        }

        .login-box input[type="password"]:focus {
            border-color: #4a90e2;
        }

        .login-box button {
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 10px;
            background: #4a90e2;
            color: white;
            font-size: 16px;
            cursor: pointer;
        }

        .login-box button:hover {
            background: #357bd8;
        }

        .error {
            color: #d93025;
            font-size: 14px;
            margin-bottom: 12px;
        }
    </style>
</head>
<body>
    <div class="login-box">
        <h1>老李的私人收藏</h1>
        <p>请输入访问密码</p>

        <?php if ($error === '1'): ?>
            <div class="error">密码错误，请重新输入</div>
        <?php endif; ?>

        <form action="login.php" method="post">
            <input type="password" name="password" placeholder="请输入密码" required>
            <button type="submit">进入系统</button>
        </form>
    </div>
</body>
</html>