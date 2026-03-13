<?php
require_once 'auth.php';
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>上传图书</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f5f5f5;
            margin: 0;
            padding: 40px 20px;
        }

        .container {
            max-width: 500px;
            margin: 0 auto;
            background: #fff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        }

        h1 {
            text-align: center;
            margin-bottom: 25px;
        }

        label {
            display: block;
            margin-top: 15px;
            margin-bottom: 8px;
            font-weight: bold;
        }

        input[type="text"],
        input[type="file"] {
            width: 100%;
            padding: 10px;
            box-sizing: border-box;
        }

        button {
            width: 100%;
            margin-top: 25px;
            padding: 12px;
            background: #007bff;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
        }

        button:hover {
            background: #0056b3;
        }

        .message {
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .success {
            background: #e8f9e8;
            color: #1f7a1f;
        }

        .error {
            background: #fdeaea;
            color: #b30000;
        }

        .back-link {
            display: block;
            text-align: center;
            margin-top: 18px;
            text-decoration: none;
            color: #333;
        }

        .back-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>上传图书</h1>

        <?php if (isset($_GET['success'])): ?>
            <div class="message success">上传成功，已写入数据库。</div>
        <?php endif; ?>

        <?php if (isset($_GET['error'])): ?>
            <div class="message error">
                <?php echo htmlspecialchars($_GET['error']); ?>
            </div>
        <?php endif; ?>

        <form action="upload_process.php" method="post" enctype="multipart/form-data">
            <label for="title">书名</label>
            <input type="text" id="title" name="title" required>

            <label for="author">作者</label>
            <input type="text" id="author" name="author" required>

            <label for="description">简介</label>
            <input type="text" id="description" name="description" required>

            <label for="cover_image">封面图片</label>
            <input type="file" id="cover_image" name="cover_image" accept="image/*" required>

           

            <button type="submit">提交上传</button>
        </form>

        <a class="back-link" href="home.php">返回首页</a>
    </div>
</body>
</html>