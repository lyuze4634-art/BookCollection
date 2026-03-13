<?php
require_once 'auth.php';
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>图书管理系统首页</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, "Microsoft YaHei", sans-serif;
            background: #f7f9fc;
            padding: 40px;
        }

        .box {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 16px;
            box-shadow: 0 8px 24px rgba(0,0,0,0.08);
        }

        h1 {
            margin-top: 0;
        }

        a.btn {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 16px;
            background: #e74c3c;
            color: white;
            text-decoration: none;
            border-radius: 10px;
        }

        a.btn:hover {
            background: #c0392b;
        }
    </style>
</head>
<body>
    <div class="box">
        <h1>已成功进入图书管理系统</h1>
        <p>这里就是你下一步要进入的页面。</p>
        <p>之后你可以在这里继续放：</p>
        <ul>
            <a class="btn" href="upload.php">上传图书</a>
            <a class="btn" href="library.php">查看图书列表</a>
            <a class="btn" href="search.php">搜索图书</a>
        </ul>

        <a class="btn" href="logout.php">退出系统</a>
    </div>
</body>
</html>