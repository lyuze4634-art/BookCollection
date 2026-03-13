<?php
require_once 'auth.php';

$host = "127.0.0.1";
$dbname = "hbook";
$username = "root";
$password = "";  // 改成你的数据库名

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("数据库连接失败：" . $conn->connect_error);
}
$conn->set_charset("utf8mb4");

$title = isset($_GET['title']) ? trim($_GET['title']) : '';
$author = isset($_GET['author']) ? trim($_GET['author']) : '';

$sql = "SELECT * FROM book WHERE 1";
$params = [];
$types = "";

if ($title !== '') {
    $sql .= " AND title LIKE ?";
    $params[] = "%" . $title . "%";
    $types .= "s";
}

if ($author !== '') {
    $sql .= " AND author LIKE ?";
    $params[] = "%" . $author . "%";
    $types .= "s";
}

$sql .= " ORDER BY id DESC";

$stmt = $conn->prepare($sql);

if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <title>搜索图书</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f8f8f8;
            margin: 0;
            padding: 30px;
        }

        .container {
            width: 900px;
            margin: 0 auto;
        }

        h1 {
            margin-bottom: 20px;
        }

        .search-box {
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 30px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }

        .search-box input[type="text"] {
            width: 250px;
            padding: 10px;
            margin-right: 10px;
            margin-bottom: 10px;
        }

        .search-box button {
            padding: 10px 20px;
            cursor: pointer;
        }

        .book-list {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
        }

        .book-card {
            background: #fff;
            padding: 15px;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            text-align: center;
        }

        .book-card img {
            width: 150px;
            height: 200px;
            object-fit: cover;
            border: 1px solid #ddd;
            margin-bottom: 10px;
        }

        .book-card h3 {
            font-size: 18px;
            margin: 10px 0 5px;
        }

        .book-card p {
            margin: 5px 0;
            color: #555;
        }

        .book-card a {
            display: inline-block;
            margin-top: 10px;
            color: blue;
            text-decoration: none;
        }

        .no-result {
            background: #fff;
            padding: 20px;
            border-radius: 10px;
        }

        .top-links {
            margin-bottom: 20px;
        }

        .top-links a {
            margin-right: 15px;
            text-decoration: none;
            color: blue;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="top-links">
            <a href="home.php">返回首页</a>
            <a href="library.php">查看全部图书</a>
        </div>

        <h1>搜索图书</h1>

        <div class="search-box">
            <form method="GET" action="search.php">
                <input type="text" name="title" placeholder="请输入书名" value="<?php echo htmlspecialchars($title); ?>">
                <input type="text" name="author" placeholder="请输入作者" value="<?php echo htmlspecialchars($author); ?>">
                <button type="submit">搜索</button>
            </form>
        </div>

        <div class="book-list">
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <div class="book-card">
                        <img src="<?php echo htmlspecialchars($row['cover_image']); ?>" alt="封面">
                        <h3><?php echo htmlspecialchars($row['title']); ?></h3>
                        <p>作者：<?php echo htmlspecialchars($row['author']); ?></p>
                        <a href="book_detail.php?id=<?php echo $row['id']; ?>">查看详情</a>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="no-result">
                    没有找到相关图书
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>