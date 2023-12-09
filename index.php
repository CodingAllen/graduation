<?php
require_once './helpers/GoodsDAO.php';



$goodsDAO = new GoodsDAO();
$categories = $goodsDAO->get_all_categories();
// 分页参数
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$perPage = 8; // 每页显示的商品数量
$category_id = isset($_GET['category']) ? (int)$_GET['category'] : null;
$search_query = isset($_GET['query']) ? $_GET['query'] : null;

// 计算总页数
// 计算总页数
$total_goods_count = $goodsDAO->get_total_goods_count($category_id);
$total_pages = $total_goods_count > 0 ? ceil($total_goods_count / $perPage) : 1;


// 调整当前页码
$page = max(1, min($page, $total_pages));

// 在获取商品列表之前检查是否有商品
if ($total_goods_count > 0) {
    if ($search_query) {
        // 如果有搜索查询，则根据关键词获取商品
        $goods_list = $goodsDAO->get_goods_by_keyword($search_query);
    } elseif ($category_id !== null) {
        // 如果选择了类别，则获取该类别下的商品
        $goods_list = $goodsDAO->get_all_goods($page, $perPage, $category_id);
    } else {
        // 否则获取所有商品
        $goods_list = $goodsDAO->get_all_goods($page, $perPage);
    }
} else {
    // 如果没有商品，设置商品列表为空数组
    $goods_list = [];
}

$recommended_goods = $goodsDAO->get_recommended_goods();

?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="./css/index.css">
    <!-- 引入Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <title>TopPage</title>
    <style>
    



.carousel-slides {
    display: flex;
    transition: transform 0.5s ease-in-out;
}

.carousel-slide {
    min-width: 100%;
    transition: 0.5s ease;
}

.carousel-slide img {
    width: 100%;
    height: 450px; /* 或所需高度 */
    object-fit: cover;
    object-position: center;
}

#prevBtn, #nextBtn {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    background-color: rgba(0,0,0,0.5);
    color: white;
    border: none;
    cursor: pointer;
    padding: 10px;
    font-size: 18px;
    color: white;
}

#prevBtn { left: 10px; }
#nextBtn { right: 10px; }

.text-center { text-align: center; }
.carousel-container {
    
    width: 100%; /* 宽度设置为100% */
    position: relative;
    overflow: hidden;
    text-align: center;
}

.carousel-slides {
    display: flex;
    transition: transform 0.5s ease;
}

.carousel-slide {
    flex: 0 0 100%; /* 确保每个滑块都占据100%的宽度 */
}

.carousel-slide img {
    width: 85%;
    height: 650x; /* 可以调整高度 */
    object-fit: cover;
    object-position: center;
}
.carousel-slide {
    display: none; /* 默认隐藏所有幻灯片 */
}

.carousel-slide.active {
    display: block; /* 只显示带有.active类的幻灯片 */
}
.card-img-top {
    width: 100%; /* 设置图片宽度为卡片宽度的100% */
    height: 200px; /* 设置一个固定高度 */
    object-fit: cover; /* 保持图片的比例 */
    object-position: center; /* 图片居中显示 */
    transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out, border-radius 0.3s ease-in-out; /* 平滑过渡效果 */
    border-radius: 4px; /* 默认的圆角 */
}

.card-img-top:hover {
    transform: scale(1.1); /* 图片放大 */
    box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2); /* 添加阴影 */
    border-radius: 10px; /* 放大时的圆角 */
}

/* 增加特异性以覆盖 Bootstrap 按钮样式 */
.btn-primary,
html body .btn-primary {
    color: white !important;
    background-color: #87CEFA !important; /* 绿松石色背景 */
    border-color: #87CEFA !important; /* 绿松石色边框 */
}

.btn-primary:hover,
.btn-primary:focus,
.btn-primary:active,
html body .btn-primary:hover,
html body .btn-primary:focus,
html body .btn-primary:active {
    background-color: #3fbab5 !important; /* 按钮在悬浮、聚焦或激活时的颜色 */
    border-color: #3fbab5 !important;
    color: white !important;
}

/* 增加特异性以覆盖链接样式 */
a,
html body a {
    color: #87CEFA !important; /* 设置链接颜色为绿松石色 */
}

a:hover,
a:focus,
html body a:hover,
html body a:focus {
    color: #F08080 !important; /* 设置链接在悬浮或聚焦时的颜色 */
}
.category-list {
        list-style: none;
        padding: 0;
    }

    .category-item {
        margin: 0 10px;
        transition: box-shadow 0.3s ease-in-out;
    }

    .category-item a {
        text-decoration: none;
        color: #333; /* 或您喜欢的颜色 */
    }

    .category-item:hover {
        box-shadow: 0 2px 5px rgba(0,0,0,0.2);
    }

    </style>
</head>
<body>
    <?php include('header.php'); ?>
    <hr>
    <div class="carousel-container">
    <div id="carouselSlides" class="carousel-slides">
        <?php foreach ($recommended_goods as $index => $goods): ?>
            <div class="carousel-slide <?= $index == 0 ? 'active' : '' ?>"
                 data-name="<?= htmlspecialchars($goods->goods_name) ?>"
                 data-price="¥<?= number_format($goods->price) ?>">
                <img src="./images/goodsimagesL/<?= $goods->goods_img_large ?>" alt="<?= htmlspecialchars($goods->goods_name) ?>">
            </div>
        <?php endforeach; ?>
    </div>
    <button id="prevBtn">&#10094;</button>
    <button id="nextBtn">&#10095;</button>
</div>
<div id="carouselCaption" class="text-center">
    <h5 id="goodsName">商品名</h5>
    <p id="goodsPrice">価格</p>
</div>
<!-- 搜索结果展示 -->

<?php if (isset($_GET['query'])) : ?>
        <div id="search-result">
        <h2 style="text-align: center; font-size: 24px; color: #336633; font-weight: bold;"><?= htmlspecialchars($_GET['query']) ?></h2>
        </div>
        
    <?php endif; ?>


    <div class="container mt-4">
    <div class="row">
        <!-- 商品カテゴリー -->
        <div class="col-md-12">
            <ul class="category-list d-flex justify-content-around">
                <?php foreach ($categories as $category): ?>
                    <li class="category-item">
                        <a href="index.php?category=<?= $category->category_id ?>"><?= htmlspecialchars($category->category_name) ?></a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
    <hr>
        <div class="row">
         <!-- 商品リスト -->
<?php foreach ($goods_list as $goods) : ?>
    <div class="col-md-3 mb-4">
        <div class="card <?= $goodsDAO->is_goods_purchased($goods->goods_id) ? 'sold-out' : '' ?>">
            <a href="goods.php?goods_id=<?= $goods->goods_id ?>">
                <img src="./images/goodsimagesL/<?= $goods->goods_img_large ?>" class="card-img-top" alt="<?= $goods->goods_name ?>">
            </a>
            <div class="card-body">
                <h5 class="card-title">
                    <a href="goods.php?goods_id=<?= $goods->goods_id ?>"><?= $goods->goods_name ?></a>
                </h5>
                <p class="card-text">¥<?= number_format($goods->price) ?></p>
                <?php if ($goodsDAO->is_goods_purchased($goods->goods_id)) : ?>
                    <div class="badge badge-danger">販売済み</div>
                <?php endif; ?>
                <?php if ($goods->recommend) : ?>
                    <span class="badge badge-success">おすすめ</span>
                <?php endif; ?>
                
            </div>
        </div>
    </div>
<?php endforeach; ?>

        </div>
    </div>
    <div class="text-center my-4">
    <?php if ($page > 1): ?>
        <a href="index.php?page=<?= $page - 1 ?>" class="btn btn-primary">前のページ</a>
    <?php endif; ?>

    <?php if ($page < $total_pages): ?>
        <a href="index.php?page=<?= $page + 1 ?>" class="btn btn-primary">次のページ</a>
    <?php endif; ?>
</div>

    <!-- Bootstrap JS, Popper.js, and jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
document.addEventListener('DOMContentLoaded', function () {
    var index = 0;
    var slides = document.querySelectorAll('.carousel-slide');
    var totalSlides = slides.length;

    function changeSlide(step) {
        slides[index].classList.remove('active');
        index = (index + step + totalSlides) % totalSlides;
        slides[index].classList.add('active');
        updateCaption();
    }

    function updateCaption() {
        var activeSlide = slides[index];
        document.getElementById('goodsName').textContent = activeSlide.getAttribute('data-name');
        document.getElementById('goodsPrice').textContent = activeSlide.getAttribute('data-price');
    }

    document.getElementById('prevBtn').addEventListener('click', function() {
        changeSlide(-1);
    });

    document.getElementById('nextBtn').addEventListener('click', function() {
        changeSlide(1);
    });

    // 初始更新
    updateCaption();
});
</script>
<?php include('footer.php'); ?>

</body>
</html>

