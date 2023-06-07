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
    $year = $_POST['selectedYear'];
    $semester = $_POST['selectedSemester'];
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
            align-items: center;
            height: 100%;
            width: 100%;
            background-image: url("https://images.pexels.com/photos/1525041/pexels-photo-1525041.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2");
            background-size: cover; /* 調整圖片大小以填滿背景 */
            background-repeat: no-repeat; /* 避免重複平鋪 */
        }

        .options {
            position: fixed;
            left: 0;
            top: 0;
            height: 100vh;
            width: 140px;
            background-color: grey;
            padding: 10px;
            transition: transform 0.3s ease-in-out;
            transform: translateX(-220px);
        }

        .options.show {
            transform: translateX(0);
        }

        .button {
            position: fixed;
            top: 10px;
            left: 10px;
            border: 2px solid black;
            transition: transform 0.3s ease-in-out;
        }

        .logout{
            position: fixed;
            top: 10px;
            right: 10px;
            border: 2px solid black;
        }

        .studentINFO{
            border-collapse: separate;
            border-spacing:30px 0px;
            background-color: #f1f1f1;
            padding: 10px;
            width: 100%;
        }
        .studentINFO tr:first-child,
        .studentINFO tr:last-child {
            border-spacing: 0;
        }

        .studentINFO tr:nth-child(odd) td{
            border: none;
        }

        .studentINFO tr:nth-child(even) td{
            border: 1px solid black;
            background-color: darkgoldenrod;
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
    <form method="post" action="student.php">
        <input type="hidden" name="username" value=<?php echo $account?> >
        <input type="hidden" name="selectedYear" value=<?php echo $year ?> >
        <input type="hidden" name="selectedSemester" value=<?php echo $semester?> >
        <input type="submit" value="學生成績查詢" style="width: 130px ;height: 60px ;background-color: darkgoldenrod">
    </form>
    <br>
    <form method="post" action="studentinfo.php">
        <input type="hidden" name="username" value=<?php echo $account?> >
        <input type="hidden" name="selectedYear" value=<?php echo  $year?> >
        <input type="hidden" name="selectedSemester" value=<?php echo $semester?> >
        <input type="submit" value="學生基本資料" style="width: 130px ;height: 60px ;background-color: darkgoldenrod">
    </form>
</div>
<!--選項列表-->

<!--主體-->
<div style="display: flex;flex-direction: column;justify-content: center;align-items: center;width: 100%;">
    <div>
        <h1 style="font-size: 50px ;color: darkgreen">個人學籍資料</h1>
    </div>

    <div style="align-items: flex-start;width: 60%">
        <h3 style="background-color: #f1f1f1 ;display: inline-block; padding: 5px">學生基本資料</h3>
    </div>
    <div style="width: 60%">
        <?php
            $sql = "SELECT student_id AS id , SSN AS SSN , student_name AS name , sex AS sex ,
                    bdate AS birthday , address AS address ,telephone AS telephone ,department AS department
                    FROM student 
                    WHERE account='$account'";
            $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
        }
        ?>
        <table class="studentINFO">
            <tr><td style="width: 50%">學號</td><td style="width: 50%">身分證</td></tr>
            <tr><td><?php echo $row['id'] ?></td><td><?php echo $row['SSN'] ?></td></tr>
            <tr><td style="width: 50%">姓名</td><td style="width: 50%">性別</td></tr>
            <tr><td><?php echo $row['name'] ?></td><td><?php echo $row['sex'] ?></td></tr>
            <tr><td style="width: 50%">生日</td><td style="width: 50%">地址</td></tr>
            <tr><td><?php
                    $inputString = $row['birthday'];
                    $year = substr($inputString, 0, 4);
                    $month = substr($inputString, 4, 2);
                    $day = substr($inputString, 6, 2);
                    $formattedDate = $year . "-" . $month . "-" . $day;
                    echo $formattedDate ?></td>
                <td><?php echo $row['address'] ?></td></tr>
            <tr><td style="width: 50%">電話</td><td style="width: 50%">系所</td></tr>
            <tr><td><?php echo $row['telephone'] ?></td><td><?php echo $row['department'] ?></td></tr>
        </table>
    </div>
    <br><br>
    <div style="align-items: flex-start;width: 60%">
        <h3 style="background-color: #f1f1f1 ;display: inline-block; padding: 5px">緊急聯絡人資料</h3>
    </div>
    <div style="width: 60%">
        <?php
        $sql = "SELECT DISTINCT E.name AS name, E.sex AS sex,E.job AS job, E.address AS address,E.telephone AS telephone,E.relationship AS relationship
                    FROM student AS S , emergency AS E
                    WHERE E.Sid='$account'";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo '<table class="studentINFO">';
                echo '<tr><td style="width: 33%">姓名</td><td style="width: 33%">關係</td><td style="width: 33%">職業</td></tr>';
                echo '<tr><td>' . $row['name'] . '</td><td>' . $row['relationship'] . '</td><td>' . $row['job'] . '</td></tr>';
                echo '<tr><td>性別</td><td>行動電話</td><td>地址</td></tr>';
                echo '<tr><td>' . $row['sex'] . '</td><td>' . $row['telephone'] . '</td><td>' . $row['address'] . '</td></tr>';
                echo '</table>';
                echo '<br>';
            }
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

