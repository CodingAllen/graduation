<?php
require_once './helpers/GoodsDAO.php';
$goodsDAO = new GoodsDAO();
$categories = $goodsDAO->get_all_categories();
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

  <style>
    .site-footer {
      background-color: #87CEFA;
      /* 天蓝色 */

      font-size: 15px;
      line-height: 24px;
      color: #FFFFFF;
      /* 文字颜色改为白色 */
      width: 100%;
      /* 设置宽度为100% */
      margin: 0;
      /* 移除外边距 */
      padding: 0;
      /* 移除内边距 */
    }

    .site-footer hr {
      border-top-color: #bbb;
      opacity: 0.5
    }

    .site-footer hr.small {
      margin: 20px 0
    }

    .site-footer h6 {
      color: #FFFFFF;
      font-size: 16px;
      text-transform: uppercase;
      margin-top: 5px;
      letter-spacing: 2px
    }

    .site-footer a {
      color: #FFFFFF;
    }

    .site-footer a:hover {
      color: #3366cc;
      text-decoration: none;
    }

    .footer-links {
      padding-left: 0;
      list-style: none
    }

    .footer-links li {
      display: block
    }

    .footer-links a {
      color: #737373
    }

    .footer-links a:active,
    .footer-links a:focus,
    .footer-links a:hover {
      color: #3366cc;
      text-decoration: none;
    }

    .footer-links.inline li {
      display: inline-block
    }

    .site-footer .social-icons {
      text-align: right
    }

    .site-footer .social-icons a {
      width: 40px;
      height: 40px;
      line-height: 40px;
      margin-left: 6px;
      margin-right: 0;
      border-radius: 100%;
      background-color: #33353d
    }

    .copyright-text {
      margin: 0
    }

    @media (max-width:991px) {
      .site-footer [class^=col-] {
        margin-bottom: 30px
      }
    }

    @media (max-width:767px) {
      .site-footer {
        padding-bottom: 0
      }

      .site-footer .copyright-text,
      .site-footer .social-icons {
        text-align: center
      }
    }

    .social-icons {
      padding-left: 0;
      margin-bottom: 0;
      list-style: none
    }

    .social-icons li {
      display: inline-block;
      margin-bottom: 4px
    }

    .social-icons li.title {
      margin-right: 15px;
      text-transform: uppercase;
      color: #96a2b2;
      font-weight: 700;
      font-size: 13px
    }

    .social-icons a {
      background-color: #eceeef;
      color: #FFFFFF;
      /* 图标颜色改为白色 */
      font-size: 16px;
      display: inline-block;
      line-height: 44px;
      width: 44px;
      height: 44px;
      text-align: center;
      margin-right: 8px;
      border-radius: 100%;
      -webkit-transition: all .2s linear;
      -o-transition: all .2s linear;
      transition: all .2s linear;
    }

    .social-icons a:active,
    .social-icons a:focus,
    .social-icons a:hover {
      color: #fff;
      background-color: #29aafe;
    }

    .social-icons.size-sm a {
      line-height: 34px;
      height: 34px;
      width: 34px;
      font-size: 14px
    }

    .social-icons a.facebook:hover {
      background-color: #3b5998
    }

    .social-icons a.twitter:hover {
      background-color: #00aced
    }

    .social-icons a.linkedin:hover {
      background-color: #007bb6
    }

    .social-icons a.dribbble:hover {
      background-color: #ea4c89
    }

    @media (max-width:767px) {
      .social-icons li.title {
        display: block;
        margin-right: 0;
        font-weight: 600
      }
    }

    .site-footer h6,
    .site-footer p,
    .site-footer a,
    .site-footer .footer-links li,
    .site-footer .social-icons li.title {
      color: #FFFFFF !important;
      /* 强制覆盖所有可能的颜色设置 */
    }

    /* 更改链接颜色 */
    .site-footer a {
      color: #FFFFFF;
    }

    .site-footer a:hover {
      color: #3366cc;
      /* 鼠标悬停时的颜色 */
    }

    /* 更改 FontAwesome 图标颜色 */
    .site-footer .social-icons a {
      color: #FFFFFF;
      /* 图标颜色改为白色 */
      background-color: #33353d;
      /* 可根据需要调整背景颜色 */
    }
  </style>

</head>

<body>
  <!-- Site footer -->
  <footer class="site-footer">
    <div class="container">
      <div class="row">
        <!-- カテゴリー and Quick Links -->
        <div class="col-xs-6 col-md-3">
          <!-- カテゴリー Section -->
          <h6>カテゴリー</h6>
          <ul class="footer-links">
            <?php foreach ($categories as $category) : ?>
              <li><a href="index.php?category=<?= $category->category_id ?>">><?= htmlspecialchars($category->category_name) ?></a></li>
            <?php endforeach; ?>
          </ul>
        </div>

        <!-- Quick Links Section -->
        <div class="col-xs-6 col-md-3">
          <h6>ヘルプ＆ガイド</h6>
          <ul class="footer-links">
            <li><a href="https://www.pexels.com/zh-cn/@wenchengphoto/">>私たちについて</a></li>
            <li>>配送料と配送情報</li>
            <li>>商品の返品・交換</li>
            <li>>価格について</li>
            <li>>お客様サポート</li>
          </ul>
        </div>

        <!-- 本社アクセス and Google Map -->
        <div class="col-md-6">
          <!-- 本社アクセス Section -->
          <h6><a href="map.php">>本社アクセス</a></h6>
          <p>ビジネスの協力があれば、弊社にご相談ください。</p>
        </div>
      </div>
      <hr>
    </div>
    <div class="container">
      <div class="row">
        <div class="col-md-8 col-sm-6 col-xs-12">
          <p class="copyright-text">Copyright &copy; CodingAllen All Rights Reserved by
            <a href="#">Scanfcode</a>.
          </p>
        </div>

        <div class="col-md-4 col-sm-6 col-xs-12">
          <ul class="social-icons">
            <li><a class="facebook" href="https://www.facebook.com/wencheng.jiang.142"><i class="fa fa-facebook"></i></a></li>
            <li><a class="twitter" href="https://twitter.com/wencheng_allen"><i class="fa fa-twitter"></i></a></li>
            <li><a class="dribbble" href="https://www.instagram.com/allen_in_jp/"><i class="fa fa-instagram"></i></a></li>
            <li><a class="linkedin" href="https://www.linkedin.com/in/%E6%96%87%E8%AA%A0-%E8%92%8B-9a0052258/"><i class="fa fa-linkedin"></i></a></li>
          </ul>
        </div>
      </div>
    </div>
  </footer>
</body>

</html>