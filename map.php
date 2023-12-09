<!DOCTYPE html>
<html>
<head>
    <title>地图卡片页面</title>
    <style>
        .map-container {
            display: flex;
            justify-content: space-between;
            padding: 20px;
        }

        .map-card {
            flex: 1;
            margin: 10px;
            box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2);
            transition: 0.3s;
            border-radius: 5px;
        }

        .map-card:hover {
            box-shadow: 0 8px 16px 0 rgba(0,0,0,0.2);
        }

        .map-header {
            padding: 2px 16px;
            background-color: #f1f1f1;
            border-radius: 5px 5px 0 0;
            text-align: center;
            font-weight: bold;
        }

        iframe {
            width: 100%;
            height: 300px;
            border: 0;
            border-radius: 0 0 5px 5px;
            
        }
    </style>
</head>
<body>
<?php include('header.php'); ?>
<hr>
    <div class="map-container">
        <div class="map-card">
            <div class="map-header">本社</div>
            <iframe
                src="https://www.google.com/maps/embed/v1/place?key=AIzaSyCB4J5BH14tpPEGeffAhQx21TA2czW79XE&q=〒169-8522+東京都新宿区百人町1-25-4">
            </iframe>
        </div>
        <div class="map-card">
            <div class="map-header">支社</div>
            <iframe
                src="https://www.google.com/maps/embed/v1/place?key=AIzaSyCB4J5BH14tpPEGeffAhQx21TA2czW79XE&q=〒164-0012+東京都中野区本町6-9-2">
            </iframe>
        </div>
    </div>
</body>
<?php include('footer.php'); ?>
</html>
