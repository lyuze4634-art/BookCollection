<?php
require_once 'auth.php';
/* =========================
   2. 数据库连接
   请改成你自己的数据库信息
   ========================= */
$host = "127.0.0.1";
$dbname = "hbook";
$username = "root";
$password = ""; 

$conn = new mysqli($host, $username, $password, $dbname);
if ($conn->connect_error) {
    die("数据库连接失败：" . $conn->connect_error);
}
$conn->set_charset("utf8mb4");

/* =========================
   3. 获取图书 ID
   ========================= */
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("无效的图书 ID");
}

$id = intval($_GET['id']);
$message = "";

/* =========================
   4. 处理更新操作
   只能修改 title / author / description
   ========================= */
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["action"]) && $_POST["action"] === "update") {
    $title = trim($_POST["title"] ?? "");
    $author = trim($_POST["author"] ?? "");
    $description = trim($_POST["description"] ?? "");

    if ($title === "" || $author === "") {
        $message = "书名和作者不能为空";
    } else {
        $stmt = $conn->prepare("UPDATE book SET title = ?, author = ?, description = ? WHERE id = ?");
        $stmt->bind_param("sssi", $title, $author, $description, $id);

        if ($stmt->execute()) {
            $message = "图书信息修改成功";
        } else {
            $message = "修改失败：" . $stmt->error;
        }
        $stmt->close();
    }
}

/* =========================
   5. 处理删除操作
   删除数据库记录，并尝试删除图片文件
   ========================= */
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["action"]) && $_POST["action"] === "delete") {
    // 先查图片路径
    $stmt = $conn->prepare("SELECT cover_image FROM book WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $book = $result->fetch_assoc();
    $stmt->close();

    if ($book) {
        $coverImage = $book["cover_image"];

        // 删除数据库记录
        $stmt = $conn->prepare("DELETE FROM book WHERE id = ?");
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            // 尝试删除服务器上的图片文件
            if (!empty($coverImage) && file_exists($coverImage)) {
                unlink($coverImage);
            }

            $stmt->close();
            $conn->close();

            header("Location: library.php?msg=deleted");
            exit();
        } else {
            $message = "删除失败：" . $stmt->error;
        }
        $stmt->close();
    } else {
        $message = "未找到要删除的图书";
    }
}

/* =========================
   6. 查询当前图书信息
   ========================= */
$stmt = $conn->prepare("SELECT * FROM book WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("未找到该图书");
}

$book = $result->fetch_assoc();
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($book['title']); ?> - 图书详情</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f5f5f5;
            margin: 0;
            padding: 30px;
        }

        .container {
            max-width: 1000px;
            margin: 0 auto;
            background: #fff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.08);
        }

        .top-bar {
            margin-bottom: 20px;
        }

        .top-bar a {
            text-decoration: none;
            color: #333;
            background: #eaeaea;
            padding: 8px 14px;
            border-radius: 8px;
        }

        .detail-wrapper {
            display: flex;
            gap: 30px;
            flex-wrap: wrap;
        }

        .cover-area {
            flex: 1;
            min-width: 280px;
            max-width: 350px;
        }

        .cover-area img {
            width: 100%;
            max-width: 350px;
            border-radius: 12px;
            object-fit: cover;
            border: 1px solid #ddd;
        }

        .info-area {
            flex: 2;
            min-width: 320px;
        }

        h1 {
            margin-top: 0;
            margin-bottom: 10px;
        }

        .meta {
            margin-bottom: 12px;
            color: #555;
            line-height: 1.8;
        }

        .desc-box {
            background: #fafafa;
            border: 1px solid #ddd;
            padding: 15px;
            border-radius: 10px;
            white-space: pre-wrap;
            line-height: 1.7;
        }

        .message {
            margin-bottom: 20px;
            padding: 12px 15px;
            border-radius: 8px;
            background: #eef7ee;
            color: #1e6b1e;
            border: 1px solid #b7dfb7;
        }

        .form-section {
            margin-top: 35px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
        }

        .form-section h2 {
            margin-bottom: 15px;
        }

        form {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 6px;
            font-weight: bold;
        }

        input[type="text"], textarea {
            width: 100%;
            padding: 10px 12px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 14px;
            box-sizing: border-box;
        }

        textarea {
            min-height: 140px;
            resize: vertical;
        }

        .btn-group {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
        }

        button {
            border: none;
            padding: 10px 18px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 14px;
        }

        .btn-update {
            background: #2d7ef7;
            color: white;
        }

        .btn-delete {
            background: #e74c3c;
            color: white;
        }

        .note {
            color: #888;
            font-size: 13px;
            margin-top: -8px;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="top-bar">
            <a href="library.php">← 返回图书列表</a>
        </div>

        <?php if ($message !== ""): ?>
            <div class="message"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>

        <div class="detail-wrapper">
            <div class="cover-area">
                <?php if (!empty($book['cover_image'])): ?>
                    <img src="<?php echo htmlspecialchars($book['cover_image']); ?>" alt="封面图">
                <?php else: ?>
                    <div style="width:100%;height:450px;background:#eee;border-radius:12px;display:flex;align-items:center;justify-content:center;color:#999;">
                        暂无封面
                    </div>
                <?php endif; ?>
            </div>

            <div class="info-area">
                <h1><?php echo htmlspecialchars($book['title']); ?></h1>

                <div class="meta">
                    <strong>作者：</strong><?php echo htmlspecialchars($book['author']); ?><br>
                    <strong>ID：</strong><?php echo htmlspecialchars($book['id']); ?><br>
                    <strong>添加时间：</strong><?php echo htmlspecialchars($book['created_at']); ?>
                </div>

                <h3>简介</h3>
                <div class="desc-box">
                    <?php echo htmlspecialchars($book['description'] ?? '暂无简介'); ?>
                </div>
            </div>
        </div>

        <div class="form-section">
            <h2>修改图书信息</h2>
            <form method="post">
                <input type="hidden" name="action" value="update">

                <label for="title">书名</label>
                <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($book['title']); ?>" required>

                <label for="author">作者</label>
                <input type="text" id="author" name="author" value="<?php echo htmlspecialchars($book['author']); ?>" required>

                <label>封面图片</label>
                <div class="note">图片不可修改</div>

                <label for="description">简介</label>
                <textarea id="description" name="description"><?php echo htmlspecialchars($book['description']); ?></textarea>

                <div class="btn-group">
                    <button type="submit" class="btn-update">保存修改</button>
                </div>
            </form>

            <h2>删除图书</h2>
            <form method="post" onsubmit="return confirm('确定要删除这本书吗？删除后无法恢复。');">
                <input type="hidden" name="action" value="delete">
                <button type="submit" class="btn-delete">删除此书</button>
            </form>
        </div>
    </div>
</body>
</html>