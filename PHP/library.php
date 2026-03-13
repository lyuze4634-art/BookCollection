<?php
require_once 'auth.php';
/*
---------------------------------------------------
2. 连接数据库
如果你已经有 db.php / conn.php，就直接 require 你的文件
---------------------------------------------------
*/
$host = "127.0.0.1";
$dbname = "hbook";
$username = "root";
$password = "";  // 改成你的数据库名

$conn = new mysqli($host, $username, $password, $dbname);
if ($conn->connect_error) {
    die("数据库连接失败：" . $conn->connect_error);
}
$conn->set_charset("utf8mb4");

/*
---------------------------------------------------
3. 读取图书列表
如果你的表名不是 book，而是 books，就把 SQL 改掉
如果你的封面字段不是 cover_image，也改成你的字段名
---------------------------------------------------
*/
$sql = "SELECT id, title, author, cover_image FROM book ORDER BY id DESC";
$result = $conn->query($sql);

$books = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $books[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <title>图书列表</title>
    <style>
        *{
            box-sizing: border-box;
        }

        body{
            margin: 0;
            font-family: Arial, "Microsoft YaHei", sans-serif;
            background: #f1f3f6;
            color: #222;
        }

        .container{
            width: 1100px;
            max-width: 95%;
            margin: 40px auto;
            background: #fff;
            border-radius: 18px;
            padding: 30px;
            box-shadow: 0 8px 24px rgba(0,0,0,0.08);
        }

        h1{
            margin-top: 0;
            margin-bottom: 20px;
            font-size: 36px;
        }

        .top-bar{
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            flex-wrap: wrap;
            gap: 10px;
        }

        .btn{
            display: inline-block;
            background: #e74c3c;
            color: #fff;
            text-decoration: none;
            padding: 10px 18px;
            border-radius: 10px;
            font-size: 16px;
        }

        .btn:hover{
            background: #cf3f31;
        }

        .book-grid{
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
            gap: 22px;
        }

        .book-card{
            display: block;
            text-decoration: none;
            color: inherit;
            background: #fafafa;
            border-radius: 14px;
            overflow: hidden;
            border: 1px solid #e8e8e8;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .book-card:hover{
            transform: translateY(-4px);
            box-shadow: 0 10px 22px rgba(0,0,0,0.08);
        }

        .cover-wrap{
            width: 100%;
            height: 280px;
            background: #ececec;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        .cover-wrap img{
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .no-cover{
            color: #777;
            font-size: 18px;
        }

        .book-info{
            padding: 15px;
        }

        .book-title{
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 10px;
            line-height: 1.4;
        }

        .book-author{
            color: #666;
            font-size: 16px;
        }

        .empty-box{
            padding: 40px 20px;
            text-align: center;
            color: #777;
            background: #fafafa;
            border-radius: 12px;
            border: 1px dashed #ccc;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="top-bar">
            <h1>图书列表</h1>
            <a href="home.php" class="btn">返回首页</a>
        </div>

        <?php if (count($books) > 0): ?>
            <div class="book-grid">
                <?php foreach ($books as $book): ?>
                    <a class="book-card" href="book_detail.php?id=<?php echo (int)$book['id']; ?>">
                        <div class="cover-wrap">
                            <?php if (!empty($book['cover_image'])): ?>
                                <img src="<?php echo htmlspecialchars($book['cover_image']); ?>" alt="封面">
                            <?php else: ?>
                                <div class="no-cover">暂无封面</div>
                            <?php endif; ?>
                        </div>

                        <div class="book-info">
                            <div class="book-title">
                                <?php echo htmlspecialchars($book['title']); ?>
                            </div>
                            <div class="book-author">
                                作者：<?php echo htmlspecialchars($book['author']); ?>
                            </div>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="empty-box">
                现在数据库里还没有图书数据
            </div>
        <?php endif; ?>
    </div>
</body>
</html>