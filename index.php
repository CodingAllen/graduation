<?php
require_once './helpers/GoodsDAO.php';
require_once './helpers/CommentDAO.php';


$commentDAO = new CommentDAO();
$commentDAO->make_recommend();

$goodsDAO = new GoodsDAO();
$categories = $goodsDAO->get_all_categories();
// 分页参数
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$perPage = 8; // 每页显示的商品数量
$category_id = isset($_GET['category']) ? (int) $_GET['category'] : null;
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
// URLからソートオプションを取得
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'recommended';

// ソートオプションに基づいて商品を取得
// ソートオプションに基づいて商品を取得
switch ($sort) {
    case 'newest':
        $goods_list = $goodsDAO->get_goods_by_date($page, $perPage, $category_id);
        break;
    case 'oldest':
        $goods_list = $goodsDAO->get_goods_by_date($page, $perPage, $category_id, false);
        break;
    case 'recommended':
        $goods_list = $goodsDAO->get_all_goods($page, $perPage, $category_id);
        break;
}

// 検索クエリがある場合は、キーワードに基づく商品リストを取得
if ($search_query) {
    $goods_list = $goodsDAO->get_goods_by_keyword($search_query);
}


$recommended_goods = $goodsDAO->get_recommended_goods();

include('header.php');
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="./css/index.css">
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



        #prevBtn,
        #nextBtn {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background-color: rgba(0, 0, 0, 0.5);
            color: white;
            border: none;
            cursor: pointer;
            padding: 10px;
            font-size: 18px;
        }

        #prevBtn {
            left: 10px;
        }

        #nextBtn {
            right: 10px;
        }

        .text-center {
            text-align: center;
        }

        .carousel-container {
            width: 100%;
            position: relative;
            overflow: hidden;
            text-align: center;
        }

        .carousel-slides {
            display: flex;
            transition: transform 0.5s ease;
        }

        .carousel-slide {
            flex: 0 0 100%;
        }

        .carousel-slide img {
            width: 85%;
            height: 650x;
            object-fit: cover;
            object-position: center;
        }

        .carousel-slide {
            display: none;
        }

        .carousel-slide.active {
            display: block;
        }

        .card-img-top {
            width: 100%;
            height: 200px;
            object-fit: cover;
            object-position: center;
            transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out, border-radius 0.3s ease-in-out;
            border-radius: 4px;
        }

        .card-img-top:hover {
            transform: scale(1.1);
            box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2);
            border-radius: 10px;
        }

        .category-list {
            list-style: none;
            padding: 0;
        }

        .category-item {
            margin-bottom: 10px;
        }

        .category-item a {
            text-decoration: none;
            color: #333;
        }

        .carousel-control-prev-icon,
        .carousel-control-next-icon {
            background-image: none;
            /* 移除默认的箭头背景图 */
        }

        .category-list {
            list-style: none;
            padding: 0;
            margin: 0;
            /* 移除默认的外边距 */
        }

        .category-item {
            border-bottom: 1px solid #d3d3d3;
            /* 添加灰色分隔线 */
            margin-bottom: -1px;
            /* 移除叠加的边框间隙 */
        }

        .category-item:last-child {
            border-bottom: none;
            /* 最后一项不需要分隔线 */
        }

        .category-item a {
            display: block;
            /* 让链接填满整个列表项，更容易点击 */
            text-decoration: none;
            color: #808080;
            /* 高级时尚的灰色 */
            padding: 10px;
            /* 添加一些内边距 */
            transition: background-color 0.3s ease;
            /* 平滑过渡效果 */
        }

        .category-item a:hover {
            background-color: #f7f7f7;
            /* 鼠标悬停时的背景颜色 */
        }

        .card-body {
            height: 150px;
            /* 设置一个固定高度，或足够容纳您的内容 */
            overflow: hidden;
            /* 隐藏溢出的内容 */
        }

        .card {
            height: 100%;
            /* 让卡片填满列的高度 */
            display: flex;
            /* 启用flex布局 */
            flex-direction: column;
            /* 使内容垂直排列 */
            justify-content: space-between;
            /* 在卡片顶部和底部之间分配空间 */
        }

        /* 如果您的卡片标题或文本长度不同，请添加以下样式 */
        .card-title,
        .card-text {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            /* 这会在文本太长时显示省略号 */
        }

        /* 分页按钮专用样式 */
        .btn-page-navigation {
            background-color: #01596B;
            /* 设置按钮背景色 */
            color: white;
            /* 设置按钮文字颜色 */
            border: none;
            /* 移除边框 */
        }

        .btn-page-navigation:hover,
        .btn-page-navigation:focus {
            background-color: #014B59;
            /* 鼠标悬停或聚焦时的背景色，稍微深一点 */
            color: white;
        }

        .carousel-item img {
            width: 100%;
            /* 确保图片宽度与容器宽度一致 */
            height: 450px;
            /* 设定一个固定高度 */
            object-fit: cover;
            /* 图片会覆盖整个容器，可能被裁剪但不会被拉伸 */
            object-position: top;
            /* 图片对齐到容器的顶部，这样顶部不会被裁剪 */

            
        }
    </style>
</head>

<body>

    <header>
        <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
            <!-- Indicators -->
            <ol class="carousel-indicators">
                <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
                <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
            </ol>
            <!-- Wrapper for slides -->
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <a href="index.php?category=4">
                        <img src="./images/lunbo/xmas23_lp_fashion_dt.gif" class="d-block w-100" alt="Fashion">
                    </a>
                </div>
                <div class="carousel-item">
                    <a href="index.php?category=7">
                        <img src="./images/lunbo/xmas23_lp_toy_dt.gif" class="d-block w-100" alt="Toys">
                    </a>
                </div>
            </div>
            <!-- Left and right controls -->
            <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="sr-only">Previous</span>
            </a>
            <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="sr-only">Next</span>
            </a>
        </div>
    </header>

    <div class="container mt-4">
        <div class="row">
            <!-- 商品分类 -->
            <aside class="col-md-3">
                <h3>商品カテゴリー</h3>
                <ul class="category-list">
                    <?php foreach ($categories as $category): ?>
                        <li class="category-item">
                            <a href="index.php?category=<?= $category->category_id ?>"><?= htmlspecialchars($category->category_name) ?></a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </aside>

            <!-- 商品卡片 -->
            <main class="col-md-9">

            <div class="text-center my-4">
    <label for="sort">並び替え:</label>
    <select id="sort" class="form-control" onchange="changeSort(this.value)">
        <option value="recommended">オススメ順</option>
        <option value="newest">古いもの</option>
        <option value="oldest">新しいもの</option>
    </select>
</div>


                <div class="row">
                    <?php foreach ($goods_list as $goods): ?>
                        <div class="col-md-3 mb-4 d-flex align-items-stretch">
                            <!-- 添加了d-flex和align-items-stretch类 -->
                            <div class="card">
                                <a href="goods.php?goods_id=<?= $goods->goods_id ?>">
                                    <img src="./images/goodsimagesL/<?= $goods->goods_img_large ?>" class="card-img-top"
                                        alt="<?= $goods->goods_name ?>">
                                </a>
                                <div class="card-body d-flex flex-column">
                                    <!-- 用于Flexbox布局 -->
                                    <h5 class="card-title">
                                        <a href="goods.php?goods_id=<?= $goods->goods_id ?>"><?= $goods->goods_name ?></a>
                                    </h5>
                                    <p class="card-text">¥
                                        <?= number_format($goods->price) ?>
                                    </p>
                                    <?php if ($goodsDAO->is_goods_purchased($goods->goods_id)): ?>
                                        <div class="badge badge-danger">販売済み</div>
                                    <?php endif; ?>
                                    <?php if ($goods->recommend): ?>
                                        <span class="badge badge-success">おすすめ</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </main>
        </div>
    </div>

    <div class="text-center my-4">
    <?php if ($page > 1): ?>
    <a href="index.php?page=<?= $page - 1 ?>&sort=<?= $sort ?>" class="btn btn-page-navigation">前のページ</a>
<?php endif; ?>
<?php if ($page < $total_pages): ?>
    <a href="index.php?page=<?= $page + 1 ?>&sort=<?= $sort ?>" class="btn btn-page-navigation">次のページ</a>
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

            document.getElementById('prevBtn').addEventListener('click', function () {
                changeSlide(-1);
            });

            document.getElementById('nextBtn').addEventListener('click', function () {
                changeSlide(1);
            });

            // 初始更新
            updateCaption();
        });

 
        function changeSort(sortOption) {
    var currentUrl = window.location.href.split('?')[0];
    var newUrl = currentUrl + '?sort=' + sortOption + '&page=1';
    window.location.href = newUrl;
}


    document.addEventListener('DOMContentLoaded', function () {
        var sortSelect = document.getElementById('sort');
        var currentSort = new URLSearchParams(window.location.search).get('sort');

        if (currentSort) {
            sortSelect.value = currentSort;
        }
    });

    </script>
    <?php include('footer.php'); ?>

</body>

</html>