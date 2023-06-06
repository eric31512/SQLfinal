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
    ?>
    <?php
    // 檢查是否有表單提交
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['selectedYear']) && isset($_POST['selectedSemester'])) {
            $year = $_POST['selectedYear'];
            $semester = $_POST['selectedSemester'];
            $account = $_POST['username'];
            //取得資料庫內容
            $sql1 = "SELECT S.courseid ,C.Cdepartment,C.Cname,C.reqORele,C.credits, T.name ,S.grade
                        FROM choosecourse S ,course C, teacher T 
                        WHERE S.studentid='$account' AND C.year='$year' AND C.semester='$semester' AND S.courseid = C.courseid 
                        AND C.teacherSSN = T.SSN ";

            $sql2 = "SELECT C.credits
                        FROM choosecourse S ,course C, teacher T 
                        WHERE S.studentid='$account' AND C.year='$year' AND C.semester='$semester' AND S.courseid = C.courseid 
                        AND C.teacherSSN = T.SSN ";

            $YSresult = $conn->query($sql1);
            $Search = $conn->query($sql2);
        }
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
            background-color: #f1f1f1;
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

<button class="logout" onclick="redirectToAnotherPage()" style="background-color: darkgoldenrod ;width: 80px ;height: 60px ">登出</button>

<!--選項列表-->
<button id="showOptionsBtn" class="button" style="background-color: darkgoldenrod ;width: 80px ;height: 60px">切換網頁</button>
<script>
    function redirectToAnotherPage() {
        window.location.href = "login.php"; // 將這裡的網址替換為您想要跳轉到的頁面的網址
    }
</script>
<div class="options" style="justify-content: flex-start ; align-items: center">
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
        <form method="POST" action="student.php">
            <input type="hidden" name="username" value=<?php echo $account?> >
            <input type="hidden" name="selectedYear" value=<?php echo $currentYear ?> >
            <input type="hidden" name="selectedSemester" value=<?php echo $currentSemester?> >
            <input type="submit" value="學生成績查詢" style="width: 130px ;height: 60px ;background-color: darkgoldenrod">
        </form>
        <br>
        <form method="POST" action="studentinfo.php">
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
        <h1 style="font-size: 50px ;color: darkgreen">學期成績查詢</h1>
    </div>
    <div>
        <table style="background-color: #f1f1f1">
        <?php
        $sql = "SELECT student_id AS id , student_name AS name FROM student WHERE account='$account'";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            echo "<tr>";
            echo "<td style='width: 20%'>學號:</td><td style='width: 30%'>" . $row['id'] ."</td>";
            echo "<td style='width: 20%'>姓名:</td><td style='width: 30%'>" . $row['name'] ."</td>";
            echo "</tr>";
        }
        ?>

        <form method="POST" action="student.php">
            <tr>
            <td><label for="year">學年:</label></td>
            <td><select id="year" name="selectedYear" style="width: 100% ;height: 100%">
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
            </select></td>
            <td><label for="semester">學期:</label></td>
            <td><select id="semester" name="selectedSemester" style="width: 100% ;height: 100%">
                <option value="上學期" <?php if(isset($_POST['selectedSemester']) && $_POST['selectedSemester'] === '上學期') echo 'selected'; ?>>上學期</option>
                <option value="下學期" <?php if(isset($_POST['selectedSemester']) && $_POST['selectedSemester'] === '下學期') echo 'selected'; ?>>下學期</option>
            </select></td>
            </tr>
            <?php
            $totalcredits=0;
            $count=0;
            if(isset($Search)){
                while($row = $Search->fetch_assoc()) {
                    $totalcredits += $row['credits'];
                    $count++;
                }
            }
            echo '<td>已選課程總數</td>';
            echo "<td>" . $count . "</td>";
            echo '<td>總學分</td>';
            echo "<td>" . $totalcredits . "</td>";
            ?>
            <tr>
            </table>
            <br>
            <input type="hidden" name="username" value=<?php echo $account ?>>
            <input type="submit" value="查詢" style="height: 40px ;width: 60px ;background-color: darkgoldenrod">
        </form>
    </div>
    <br>
    <div>
        <?php
        // 如果有查詢結果，顯示在頁面上
        if (isset($YSresult)) {
            echo "<table id='table_class'>";
            echo "<tr><th>課程編號</th><th>開課系所</th><th>課程名稱</th><th>必修或選修</th><th>學分</th><th>老師</th><th>成績</th></tr>";
            while ($row = $YSresult->fetch_assoc()) {
                echo "<tr>";
                echo "<td style='width: 10%'>" . str_pad($row['courseid'], 4, '0', STR_PAD_LEFT)  . "</td>";
                echo "<td style='width: 20%'>" . $row['Cdepartment'] . "</td>";
                echo "<td style='width: 20% word-wrap: break-word white-space: normal'>" . $row['Cname'] . "</td>";
                echo "<td style='width: 10%'>" . $row['reqORele'] . "</td>";
                echo "<td style='width: 10%'>" . $row['credits'] . "</td>";
                echo "<td style='width: 20%'>" . $row['name'] . "</td>";
                echo "<td style='width: 10%'>" . $row['grade'] . "</td>";
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
