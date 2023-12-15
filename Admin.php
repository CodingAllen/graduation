<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>管理システム</title>
    <style>
        body {
    font-family: Arial, sans-serif;
    margin: 0;
    background-color: #f0f0f0;
    display: flex;
    flex-direction: column;
    align-items: center;
    height: 100vh;
}
.header {
    width: 100%;
    text-align: center;
    padding: 20px 0;
    font-size: 30px;
    color: #333;
}
.main-content {
    display: flex;
    width: 80%;
    flex-grow: 1;
    max-height: 90%; /* 调整这个值以确保容纳更大的显示框 */
    justify-content: center;
    align-items: flex-start; 
}
.tabs {
    display: flex;
    flex-direction: column;
    width: 20%;

}
.tab {
    padding: 10px;
    background-color: white;
    border: 1px solid #ddd;
    text-align: center;
    cursor: pointer;
}
.tab.active {
    background-color: blue;
    color: white;
}
.display-box {
    flex-grow: 1;
    min-width: 60%;
    min-height: 90%; /* 调整这个值使显示框更接近底部 */
    border: 1px solid #ddd;
    padding: 20px;
    background-color: white;
}

    </style>
</head>
<body>
    <div class="header">管理システム</div>
    <div class="main-content">
        <div class="tabs">
            <div class="tab" onclick="changeTab(0)">选项卡 1</div>
            <div class="tab" onclick="changeTab(1)">选项卡 2</div>
            <div class="tab" onclick="changeTab(2)">选项卡 3</div>
            <div class="tab" onclick="changeTab(3)">选项卡 4</div>
            <div class="tab" onclick="changeTab(4)">选项卡 5</div>
        </div>
        <div class="display-box" id="display-box">
            请从左侧选择一个选项卡。
        </div>
    </div>

    <script>
        function changeTab(index) {
            var tabs = document.getElementsByClassName('tab');
            for (var i = 0; i < tabs.length; i++) {
                tabs[i].classList.remove('active');
            }
            tabs[index].classList.add('active');

            var displayBox = document.getElementById('display-box');
            displayBox.innerHTML = '选项卡 ' + (index + 1) + ' 的内容显示在这里。';
        }
    </script>
</body>
</html>
