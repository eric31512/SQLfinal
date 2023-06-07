<!DOCTYPE html>
<html>
<head>
    <title>php</title>
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
    $course = $_GET['id'];
    ?>
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

        .content{
            text-align: center;
            width: 100%;
        }

        /* 登出*/
        .logout_button {
            position: fixed;
            top: 10px;
            right: 10px;
            height: 60px;
            width: 80px;
            background-color: darkgoldenrod
        }

        /* 返回上一頁*/
        .back_button {
            position: fixed;
            top: 10px;
            left: 10px;
            height: 60px;
            width: 80px;
            background-color: darkgoldenrod
        }

        #studentTable {
            border-collapse: collapse; /* 合併邊框 */
            border: 2px solid darkgreen;
            background-color: #f1f1f1;
            text-align: center;
            align-items: center;
            justify-content: center;
            width: 50%;
        }

        th, td {
            border: 2px solid darkgreen; /* 設置邊框 */
            padding: 8px; /* 設置內邊距 */
            text-align: center; /* 文字居中對齊 */
        }

        .input {
            width: 30px;
            height: 30px;
        }

        .save_button_container {
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }

        .save_button {
            height: 60px;
            width: 80px;
            background-color: darkgoldenrod;
        }

        .tablecontent {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            width: 100%;
        }

    </style>
</head>
<body>
<!--返回上一頁-->
<form action="teacher.php" method="GET">
    <?php
    $sql = "SELECT T.account FROM teacher T,course C WHERE C.courseid='$course' AND C.teacherSSN = T.SSN ";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        //
        $row = $result->fetch_assoc();
        echo '<input type="hidden" name="username" value=' . $row['account'] .'>';
    } else {
        echo "沒有結果";
    }
    ?>
    <button type="submit" class="back_button">返回</button>
</form>

<!--右上角登出-->
<button class="logout_button" onclick="redirectToAnotherPage()">登出</button>
<script>
    function redirectToAnotherPage() {
        window.location.href = "login.php"; // 將這裡的網址替換為您想要跳轉到的頁面的網址
    }
</script>
<!--右上角登出-->

<div style="width: 100%">
    <!--標題-->
    <div class="content">
        <h1 style="font-size: 40px ;color: darkgreen">課程成績管理</h1>
        <?php
        $sql = "SELECT Cname AS name FROM course  WHERE courseid='$course' ";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            //
            while ($row = $result->fetch_assoc()) {
                echo '<h2 style="font-size: 20px ;color: black"> ' . $row['name'] .'</h2>';//課程名稱顯示
            }
        } else {
            echo "沒有結果";
        }
        ?>
    </div>

    <!--學生成績學號顯示-->
    <div class="tablecontent">
        <?php
        $sql = "SELECT S.student_id AS id,S.student_name AS name,H.grade AS grade
                FROM course C,choosecourse H ,student S
                WHERE C.courseid='$course'AND C.courseid=H.courseid AND S.student_id = H.studentid 
                ORDER BY id";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            //
            echo '<form method="POST" action="gradeinput.php" class="tablecontent">';
            echo '<table id="studentTable">';
            echo "<tr><th>學號</th><th>學生姓名</th><th>成績</th></tr>";
            while ($row = $result->fetch_assoc()) {
                echo '<tr>';
                echo '<td>' . $row['id'] .'</td>';//學生學號
                echo '<td>' . $row['name'] .'</td>';//學生姓名
                echo '<td><input class="input" onblur="validateNumericInput(this)  type="text" name="'.$row['id'].'" value="'.$row['grade'].'"></td>';

                echo '</tr>';
            }
            echo "</table>";
            echo '<br>';
            echo '<input type="hidden" name="course" value=' . $course .'>';
            echo '<div class="save_button_container">';
            echo '<button type="submit" class="save_button">儲存</button>';
            echo '</div>';
            echo '</form>';
        }
        ?>

    </div>


</div>



<!--關閉連線-->
<?php
// 關閉連線
$conn->close();
?>
</body>
</html>
