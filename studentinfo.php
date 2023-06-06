<!DOCTYPE html>
<html>
<head>
    <title>php</title>
    <!-- 確認資料庫連線-->
    <?php
    if (class_exists('mysqli')) {
    } else {
        echo "mysqli failure"."<br>";
    }
    $servername = "127.0.0.1";
    $username = "root";
    $password = "10600508Eric";
    $database = "event";
    // 建立連線
    $conn = new mysqli($servername, $username, $password ,$database );

    // 檢查連線是否成功
    if ($conn->connect_error) {
        die("connect fail： " . $conn->connect_error);
    }
    $account = $_POST['username'];
    ?>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $("#showOptionsBtn").click(function() {
                $(".options").toggleClass("show");
            });
        });
    </script>

    <style>
        body {
            display: flex;
            justify-content: center;
            height: 100%;
            width: 100%;
        }

        .options {
            position: fixed;
            left: 0;
            top: 0;
            height: 100vh;
            width: 140px;
            background-color: #f1f1f1;
            padding: 10px;
            transition: transform 0.3s ease-in-out;
            transform: translateX(-220px);
        }

        .options.show {
            transform: translateX(0);
        }

        .button {
            position: fixed;
            top: 0;
            left: 0;
            transition: transform 0.3s ease-in-out;
        }

        .logout{
            position: fixed;
            top: 0;
            right: 0;
        }

        table{
            border-collapse:collapse ;
        }

        #table_class{
            width: 90%;
        }

        th, td {
            border: 1px solid black; /* 設置邊框 */
            padding: 8px; /* 設置內邊距 */
            text-align: center; /* 文字居中對齊 */
            width: auto; /* 將每個欄位的寬度設置為自動調整 */
        }



        div {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            width: 100%;
        }



    </style>
    <!-- 設定button是否被按到-->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var button = document.querySelector('.button');
            var isMoved = false; // 按鈕是否已經移動的標記

            button.addEventListener('click', function() {
                if (!isMoved) {
                    button.style.transform = 'translateX(160px)'; // 移動到目標位置
                } else {
                    button.style.transform = 'translateX(0)'; // 返回原位
                }
                isMoved = !isMoved; // 切換移動狀態
            });
        });
    </script>
</head>
<body>

<button class="logout" onclick="redirectToAnotherPage()" style="height: 60px ;width: 80px ;background-color: darkgoldenrod">登出</button>

<!--選項列表-->
<button id="showOptionsBtn" class="button" style="height: 60px ;width: 80px ;background-color: darkgoldenrod">切換網頁</button>
<script>
    function redirectToAnotherPage() {
        window.location.href = "login.php"; // 將這裡的網址替換為您想要跳轉到的頁面的網址
    }
</script>
<div class="options" style="justify-content: flex-start">
    <br>
    <?php
    $currentYear = date('Y')-1911;
    $currentMonth = date('M');
    $currentMonth = date('m', strtotime($currentMonth));
    if($currentMonth<8 && $currentMonth>1){
        $currentSemester = "下學期";
        $currentYear--;
    }else{
        $currentSemester = "上學期";
    }
    ?>
    <form method="post" action="student.php">
        <input type="hidden" name="username" value=<?php echo $account?> >
        <input type="hidden" name="selectedYear" value=<?php echo $currentYear ?> >
        <input type="hidden" name="selectedSemester" value=<?php echo $currentSemester?> >
        <input type="submit" value="學生成績查詢" style="width: 130px ;height: 60px ;background-color: darkgoldenrod">
    </form>
    <br>
    <form method="post" action="studentinfo.php">
        <input type="hidden" name="username" value=<?php echo $account?> >
        <input type="hidden" name="selectedYear" value=<?php echo  $currentYear?> >
        <input type="hidden" name="selectedSemester" value=<?php echo $currentSemester?> >
        <input type="submit" value="學生基本資料" style="width: 130px ;height: 60px ;background-color: darkgoldenrod">
    </form>
</div>
<!--選項列表-->

<!--主體-->
<div>
    <div>
        <h1>個人學籍資料</h1>
    </div>
    <div>
        <h3>學生基本資料</h3>
    </div>

</div>
<?php
// 關閉連線
$conn->close();
?>
</body>
</html>

