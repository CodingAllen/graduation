<?php
require_once './helpers/GoodsDAO.php';

require_once './helpers/StatusDAO.php';
require_once './helpers/UserDAO.php';

session_start();
// 检查用户是否登录
if (!isset($_SESSION['user']) || !($_SESSION['user'] instanceof User)) {
    header("Location: login.php"); // 如果用户未登录或User对象不完整，重定向到登录页
    exit;
}
$user_id = $_SESSION['user']->user_id;
// 获取分类和状态选项
$goodsDAO = new GoodsDAO();
$categories = $goodsDAO->get_all_categories();

$statusDAO = new StatusDAO();
$statuses = $statusDAO->get_all_statuses();

// 表单提交处理
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // 商品信息验证

    $goods_name = $_POST['goods_name'] ?? '';
    $category_id = $_POST['category_id'] ?? '';
    $status_id = $_POST['status_id'] ?? '';
    $price = $_POST['price'] ?? '';
    $origin = $_POST['origin'] ?? '';
    $delivery_days = $_POST['delivery_days'] ?? '';
    $stock = 1; // 检查是否勾选在库
    $payer = isset($_POST['payer']) ? 1 : 0; // 检查是否勾选卖家支付邮费
    if (!isset($_SESSION['user']) || !($_SESSION['user'] instanceof User)) {
        // 如果User对象不存在或不是预期类型，则重定向到登录页面
        header("Location: login.php");
        exit;
    }
    $goods = new Goods();
    $goods->user_id = $user_id;

    // 文件上传处理
    $goods_img_large = $_FILES['goods_img_large'] ?? null;
    $goods_img_small = $_FILES['goods_img_small'] ?? null;

    // 这里可以添加文件有效性检查，如文件大小限制或文件类型验证等

    // 确保文件上传成功并且没有错误
    if ($goods_img_large && $goods_img_large['error'] == UPLOAD_ERR_OK) {
        $large_filename = time() . '_' . basename($goods_img_large['name']); // 只获取文件名
        move_uploaded_file($goods_img_large['tmp_name'], 'images/goodsimagesL/' . $large_filename);
    }

    if ($goods_img_small && $goods_img_small['error'] == UPLOAD_ERR_OK) {
        $small_filename = time() . '_' . basename($goods_img_small['name']); // 只获取文件名
        move_uploaded_file($goods_img_small['tmp_name'], 'images/goodsimagesS/' . $small_filename);
    }

    // 创建Goods对象并设置属性
    $goods = new Goods();
    $goods->user_id = $user_id; // 从会话获取用户ID
    $goods->goods_name = $goods_name;
    $goods->category_id = $category_id;
    $goods->status_id = $status_id;
    $goods->price = $price;
    $goods->stock = $stock;
    $goods->payer = $payer;
    $goods->origin = $origin;
    $goods->delivery_days = $delivery_days;
    $goods->goods_img_large = $large_filename ?? ''; // 使用上传的文件名或默认值
    $goods->goods_img_small = $small_filename ?? ''; // 使用上传的文件名或默认值
    $goods->goods_detail = $_POST['goods_detail'] ?? '';
    // 插入商品信息到数据库
    $goodsDAO = new GoodsDAO();
    if ($goodsDAO->add_goods($goods)) {
        echo "出品できました！ありがとうございました。";
    } else {
        echo "商品出品失败しました";
    }
}

?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>商品出品</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- 自定义CSS -->
    <link rel="stylesheet" type="text/css" href="./css/custom-style.css"> <!-- 假设您有自定义的样式 -->
</head>

<body>
    <?php include "header.php"; ?>

    <div class="container mt-4">
        <h2 class="mb-4">商品出品</h2>
        <form action="produce.php" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="goods_name">商品名:</label>
                <input type="text" class="form-control" id="goods_name" name="goods_name" required>
            </div>
            <div class="form-group">
                <label for="category_id">商品分類名:</label>
                <select class="form-control" id="category_id" name="category_id" required>
                    <?php foreach ($categories as $category) : ?>
                        <option value="<?= $category->category_id ?>"><?= $category->category_name ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="status_id">商品状態:</label>
                <select class="form-control" id="status_id" name="status_id" required>
                    <?php foreach ($statuses as $status) : ?>
                        <option value="<?= $status->status_id ?>"><?= $status->status_name ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="goods_img_large">商品画像大:</label>
                <input type="file" class="form-control-file" id="goods_img_large" name="goods_img_large" required>
            </div>
            <div class="form-group">
                <label for="goods_img_small">商品画像小:</label>
                <input type="file" class="form-control-file" id="goods_img_small" name="goods_img_small" required>
            </div>
            <div class="form-group">
                <label for="goods_detail">商品詳細:</label>
                <input type="textarea" class="form-control" id="goods_detail" name="goods_detail">
            </div>
            <div class="form-group">
                <label for="price">単価:</label>
                <input type="text" class="form-control" id="price" name="price" required>
            </div>
           
            <div class="form-group">
                <label>発送料金負担者:</label>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="payer" id="sellerPays" value="1" checked>
                    <label class="form-check-label" for="sellerPays">売手</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="payer" id="buyerPays" value="0">
                    <label class="form-check-label" for="buyerPays">買手</label>
                </div>
            </div>
            <div class="form-group">
                <label for="origin">発送元:</label>
                <input type="text" class="form-control" id="origin" name="origin" required>
            </div>
            <div class="form-group">
                <label for="delivery_days">発送までの日数:</label>
                <input type="text" class="form-control" id="delivery_days" name="delivery_days" required>
            </div>
            <button type="submit" class="btn btn-primary">出品</button>
        </form>
    </div>

    <!-- Bootstrap JS, Popper.js, and jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <?php include('footer.php'); ?>
</body>

</html>