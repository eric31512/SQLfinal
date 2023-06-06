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
        $account = $_GET['username'];
        if(isset($_GET['currentValue']) ){
            $account = $_GET['currentValue'];
        }
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
            align-items: flex-start;
            height: 100%;
            width: 100%;
        }
        .main{
            width: 100%;
        }
        .content{
            text-align: center;
            width: 100%;

        }
        .maincontent{
            text-align: center;
            width: 100%;
        }
        .options {
            position: fixed;
            left: 0;
            top: 0;
            height: 100vh;
            width: 180px;
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

        table {
            width: 100%; /* 表格寬度佔滿父容器 */
            border-collapse: collapse; /* 合併邊框 */
        }
        th, td {
            border: 1px solid black; /* 設置邊框 */
            padding: 8px; /* 設置內邊距 */
            text-align: center; /* 文字居中對齊 */
        }

    </style>
    <!-- 設定button是否被按到-->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var button = document.querySelector('.button');
            var isMoved = false; // 按鈕是否已經移動的標記

            button.addEventListener('click', function() {
                if (!isMoved) {
                    button.style.transform = 'translateX(200px)'; // 移動到目標位置
                } else {
                    button.style.transform = 'translateX(0)'; // 返回原位
                }
                isMoved = !isMoved; // 切換移動狀態
            });
        });
    </script>
</head>
<body>
<button id="showOptionsBtn" class="button">切換網頁</button>
<button class="logout" onclick="redirectToAnotherPage()">登出</button>
<script>
    function redirectToAnotherPage() {
        window.location.href = "login.php"; // 將這裡的網址替換為您想要跳轉到的頁面的網址
    }
</script>
<div class="options">
    <ul>
        <li>學生成績查詢</li>
        <li>學生基本資料</li>
    </ul>
</div>
<div class="main">
    <div class="content">
        <h1>學期成績查詢</h1>
    </div>
    <div class="maincontent">

        <?php
        $sql = "SELECT * FROM student WHERE account='$account'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            //
            while ($row = $result->fetch_assoc()) {
                //
                echo "學號: " . $row["student_id"] . " 姓名: " . $row["student_name"] . "<br>" ;
            }
        } else {
            echo "没有结果";
        }
        ?>
        <!-- 確認資料庫連線-->

        <br>
        <?php
        // 預設的年份和學期
        $defaultYear = "2022";
        $defaultSemester = "上學期";

        // 檢查是否有表單提交
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['selectedYear']) && isset($_POST['selectedSemester'])) {
                $year = $_POST['selectedYear'];
                $semester = $_POST['selectedSemester'];


                //取得資料庫內容
                $sql = "SELECT S.courseid ,C.Cdepartment,C.Cname,C.reqORele,C.credits, T.name ,S.grade
                        FROM choosecourse S ,course C, teacher T 
                        WHERE S.studentid='$account' AND C.year='$year' AND C.semester='$semester' AND S.courseid = C.courseid 
                        AND C.teacherSSN = T.SSN ";
                $result1 = $conn->query($sql);


            }
        }
        ?>
        <form method="POST" action="">
            <label for="year">學年：</label>
            <select id="year" name="selectedYear">
                <?php
                $currentYear = date('Y')-1911;
                $startYear = $currentYear - 5;
                $endYear = $currentYear + 5;

                for ($year = $startYear; $year <= $endYear; $year++) {
                    $selected = '';
                    if (isset($_POST['selectedYear']) && $_POST['selectedYear'] == $year) {
                        $selected = 'selected';
                    }
                    echo "<option value=\"$year\" $selected>$year</option>";
                }
                ?>
            </select>
            <label for="semester">學期：</label>
            <select id="semester" name="selectedSemester">
                <option value="上學期" <?php if(isset($_POST['selectedSemester']) && $_POST['selectedSemester'] === '上學期') echo 'selected'; ?>>上學期</option>
                <option value="下學期" <?php if(isset($_POST['selectedSemester']) && $_POST['selectedSemester'] === '下學期') echo 'selected'; ?>>下學期</option>
            </select>
            <br><br>

            <input type="submit" value="查詢">
        </form>
        <?php
        // 如果有查詢結果，顯示在頁面上
        if (isset($result1)) {
            echo "<table>";
            echo "<tr><th>課程編號</th><th>開課系所</th><th>課程名稱</th><th>必修或選修</th><th>成績</th></tr>";
            while ($row = $result1->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . str_pad($row['courseid'], 4, '0', STR_PAD_LEFT)  . "</td>";
                echo "<td>" . $row['Cdepartment'] . "</td>";
                echo "<td>" . $row['Cname'] . "</td>";
                echo "<td>" . $row['reqORele'] . "</td>";
                echo "<td>" . $row['grade'] . "</td>";
                echo "</tr>";
            }
            echo "</table>";
        }
        ?>
    </div>
</div>
<?php
// 關閉連線
$conn->close();
?>
</body>
</html>
