<?php
require_once './helpers/AdminDAO.php';
require_once './helpers/GoodsDAO.php';
require_once './helpers/UserDAO.php';
session_start();
// 生成 CSRF 令牌
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
// 确保管理员已经登录并获取其 ID
if (isset($_SESSION['admin'])) {
    $admin = $_SESSION['admin'];
    $adminId = $admin->admin_id;
    $dao = new AdminDAO();

    $currentTabIndex = 0; // 默认显示用户选项卡
    $search = '';
    $goods_search = '';

    // 处理 POST 请求（通知发送）
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            die('CSRF token validation failed');
        }
        $type = $_POST['type'];
        $content = $_POST['content'];

        if ($type == 'personal' && isset($_POST['user_id'])) {
            $userId = $_POST['user_id'];
            $dao->sendPersonalNotification($adminId, $userId, $content);
            $_SESSION['notificationMessage'] = "個人通知の発送は成功しました。";
        } elseif ($type == 'all') {
            $dao->sendAllNotification($content);
            $_SESSION['notificationMessage'] = "全体通知の発送は成功しました。";
        }

        // 保持在当前选项卡
        $currentTabIndex = isset($_POST['current_tab']) ? intval($_POST['current_tab']) : 0;
        header('Location: Admin.php?tab=' . $currentTabIndex);
        exit();
    }

    // 处理 GET 请求（选项卡切换和搜索）
    if (isset($_GET['tab'])) {
        $currentTabIndex = $_GET['tab'] == 'goods' ? 1 : 0;

        if ($currentTabIndex == 0) {
            $search = isset($_GET['search']) ? $_GET['search'] : '';
            $users = $dao->getUsers($search);
        } elseif ($currentTabIndex == 1) {
            $goods_search = isset($_GET['goods_search']) ? $_GET['goods_search'] : '';
            $goods = $dao->getGoods($goods_search);
        }
    } else {
        $users = $dao->getUsers($search);
        $goods = $dao->getGoods($goods_search);
    }
}

?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>管理システム</title>
    <link rel="stylesheet" href="./css/Admin.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>

<body>
    <div class="header">管理システム</div>
    <div class="header">
    <button id="go-to-client-homepage" class="btn btn-primary">クライアント側に行く</button>
    </div>

    <div class="main-content">
        <div class="tabs">
            <div class="tab" onclick="changeTab(0)">ユーザー</div>
            <div class="tab" onclick="changeTab(1)">商品</div>
            <div class="tab" onclick="changeTab(2)">通知</div>
        </div>
        <div class="display-box" id="display-box">

            <div id="user-table" style="<?php echo $currentTabIndex === 0 ? 'display: block;' : 'display: none;'; ?>">
                <form id="search-form" method="get">
                    <input type="hidden" name="tab" value="users">
                    <input type="text" name="search" placeholder="IDまたはユーザー名を入力してください" value="<?php echo htmlspecialchars(isset($_GET['search']) ? $_GET['search'] : ''); ?>">
                    <button type="submit">検索</button>
                    <a href="Admin.php">リセット</a>
                </form>
                <?php if (!empty($users)) : ?>
                    <table>
                        <tr>
                            <th>userid</th>
                            <th>username</th>
                            <th>通知操作</th>
                            <th>削除操作</th>
                        </tr>
                        <?php foreach ($users as $user) : ?>
                            <tr>
                                <td><?php echo htmlspecialchars($user['user_id']); ?></td>
                                <td><?php echo htmlspecialchars($user['username']); ?></td>
                                <td><button class="notify-btn" data-userid="<?php echo $user['user_id']; ?>">通知を送る</button></td>
                                <td><button onclick="confirmDelete('user', <?php echo $user['user_id']; ?>)">削除</button></td>
                            </tr>
                        <?php endforeach; ?>
                    </table>
                <?php else : ?>
                    <p>没有找到用户数据。</p>
                <?php endif; ?>
            </div>

            <div id="goods-table" style="<?php echo $currentTabIndex === 1 ? 'display: block;' : 'display: none;'; ?>">
                <form class="search-form" method="get">
                    <input type="hidden" name="tab" value="goods">
                    <input type="text" name="goods_search" placeholder="输入商品ID或名称进行搜索">
                    <button type="submit">検索</button>
                    <a href="Admin.php?tab=goods">リセット</a>
                </form>
                <?php if (!empty($goods)) : ?>
                    <table>
                        <tr>
                            <th>goods_id</th>
                            <th>goods_name</th>
                            <th>price</th>
                            <th>削除操作</th>
                        </tr>
                        <?php foreach ($goods as $good) : ?>
                            <tr>
                                <td><?php echo htmlspecialchars($good['goods_id']); ?></td>
                                <td><?php echo htmlspecialchars($good['goods_name']); ?></td>
                                <td><?php echo htmlspecialchars($good['price']); ?></td>
                                <td><button onclick="confirmDelete('good', <?php echo $good['goods_id']; ?>)">削除</button></td>
                            </tr>
                        <?php endforeach; ?>
                    </table>
                <?php else : ?>
                    <p>商品情報は存在しないです。</p>
                <?php endif; ?>
            </div>

            <div id="notification-table" style="<?php echo $currentTabIndex === 2 ? 'display: block;' : 'display: none;'; ?>">
                <form id="notification-form" method="post" action="Admin.php">
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
    <input type="hidden" name="current_tab" value="<?php echo $currentTabIndex; ?>">
                    <input type="hidden" name="current_tab" value="<?php echo $currentTabIndex; ?>">
                    <div>
                        <label for="user-id">ユーザーid(個人通知だけ):</label>
                        <input type="text" id="user-id" name="user_id" placeholder="ユーザーIDを入力してください">
                    </div>
                    <div>
                        <label for="notification-content">通知内容:</label>
                        <textarea id="notification-content" name="content" placeholder="通知の内容を入力してください" rows="4"></textarea>
                    </div>
                    <button type="submit" name="type" value="personal" onclick="return validatePersonalNotification();">個人通知</button>
                    <button type="submit" name="type" value="all" onclick="return validateAllNotification();">全体通知</button>
                    <p id="error-message" style="color: red;"></p> <!-- 错误消息 -->
                </form>
                <div id="notification-result">
                    <?php
                    if (!empty($_SESSION['notificationMessage'])) :
                        echo '<p style="color: green;">' . $_SESSION['notificationMessage'] . '</p>';
                        unset($_SESSION['notificationMessage']);
                    endif;
                    ?>
                </div>
            </div>
        </div>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // 设置当前选项卡
                changeTab(<?php echo $currentTabIndex; ?>);
            });

            function changeTab(index) {
                var tabs = document.getElementsByClassName('tab');
                for (var i = 0; i < tabs.length; i++) {
                    tabs[i].classList.remove('active');
                }
                tabs[index].classList.add('active');

                // 获取各个表格的容器
                var userTableContainer = document.getElementById('user-table');
                var goodsTableContainer = document.getElementById('goods-table');
                var notificationTableContainer = document.getElementById('notification-table');

                // 隐藏所有表格
                if (userTableContainer) userTableContainer.style.display = 'none';
                if (goodsTableContainer) goodsTableContainer.style.display = 'none';
                if (notificationTableContainer) notificationTableContainer.style.display = 'none';

                // 根据选项卡索引显示相应的表格
                if (index === 0 && userTableContainer) {
                    userTableContainer.style.display = 'block';
                } else if (index === 1 && goodsTableContainer) {
                    goodsTableContainer.style.display = 'block';
                } else if (index === 2 && notificationTableContainer) {
                    notificationTableContainer.style.display = 'block';
                }
            }

            function validatePersonalNotification() {
                var userId = document.getElementById('user-id').value;
                var errorMessage = document.getElementById('error-message');

                if (!userId) {
                    errorMessage.textContent = 'ユーザーidを入力してください';
                    return false; // 阻止表单提交
                }

                errorMessage.textContent = ''; // 清除错误消息
                return true; // 允许表单提交
            }

            function validateAllNotification() {
                var userId = document.getElementById('user-id').value;
                var errorMessage = document.getElementById('error-message');

                if (userId) {
                    errorMessage.textContent = 'ユーザーIDを入力しないでください';
                    return false; // 阻止表单提交
                }

                errorMessage.textContent = ''; // 清除错误消息
                return true; // 允许表单提交
            }

            function confirmDelete(type, id) {
                if (confirm(type + 'を削除しますか？')) {
                    window.location.href = 'delete.php?type=' + type + '&id=' + id;
                }
            }
            document.querySelectorAll('.notify-btn').forEach(function(btn) {
                btn.addEventListener('click', function() {
                    var userId = btn.getAttribute('data-userid');
                    changeTab(2); // 切换到通知选项卡
                    document.getElementById('user-id').value = userId; // 设置用户ID
                });
            });
        </script>
        <script>
            document.getElementById('go-to-client-homepage').addEventListener('click', function() {
                window.location.href = 'index.php';
            });
        </script>

</body>

</html>