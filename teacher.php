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
        $account = $_GET['username'];
    ?>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: flex-start;
            height: 100%;
            width: 100%;
        }

        .content{
            text-align: center;
        }

        .logout{
            position: fixed;
            top: 0;
            right: 0;
        }

        /*顯示教師資訊的button*/
        #myButton {
            border: 1px solid black;
            cursor: pointer;
        }

        #myPage {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: #808080;
            padding: 20px;
            z-index: 9999;
        }

        table {
            border-collapse: collapse; /* 合併邊框 */
        }

        th, td {
            border: 1px solid black; /* 設置邊框 */
            padding: 8px; /* 設置內邊距 */
            text-align: center; /* 文字居中對齊 */
        }
        /*顯示教師資訊的button*/
    </style>
</head>
<body>



<!--右上角登出-->
<button class="logout" onclick="redirectToAnotherPage()" style="height: 40px ;width: 60px ;background-color: darkgoldenrod">登出</button>
<script>
    function redirectToAnotherPage() {
        window.location.href = "login.php"; // 將這裡的網址替換為您想要跳轉到的頁面的網址
    }
</script>
<!--右上角登出-->

<div>
    <!--標題-->
    <div class="content">
        <h1>課程管理</h1>
    </div>
    <!--顯示教師資料-->
    <div id="myButton" class="content" onmouseover="showPage()" onmouseout="hidePage()">
        <?php
        $sql = "SELECT name FROM teacher T WHERE T.account='$account' ";
        $result = $conn->query($sql);
        if (isset($result)) {
            $row = $result->fetch_assoc();
            echo "教師:";
            echo $row['name'] ;
        }
        ?>
    </div>
    <div id="myPage" class="content">
        <table>
            <tr><th>身分證字號</th><th>姓名</th><th>性別</th><th>生日</th><th>系所</th></tr>
            <?php
            $sql = "SELECT SSN,name,sex,bdate,department FROM teacher WHERE account='$account' ";
            $result = $conn->query($sql);
            if (isset($result)) {
                while ($row = $result->fetch_assoc()) {
                    $inputString = $row['bdate'];
                    $year = substr($inputString, 0, 4);
                    $month = substr($inputString, 4, 2);
                    $day = substr($inputString, 6, 2);
                    $formattedDate = $year . "/" . $month . "/" . $day;
                    echo "<tr>";
                    echo "<td>" . $row['SSN'] ."</td>";
                    echo "<td>" . $row['name'] ."</td>";
                    echo "<td>" . $row['sex'] ."</td>";
                    echo "<td>" . $formattedDate ."</td>";
                    echo "<td>" . $row['department'] ."</td>";
                    echo "</tr>";
                }
            }
            ?>
        </table>
    </div>
    <script>
        function showPage() {
            var page = document.getElementById('myPage');
            page.style.display = 'block';
        }
        function hidePage() {
            var page = document.getElementById('myPage');
            page.style.display = 'none';
        }
    </script>
    <!--顯示教師資料-->
    <br>
    <!--課程列表-->
    <div class="content">
        <?php
        //查詢控制的課程id及名字
        $sql = "SELECT courseid,Cname FROM course C,teacher T WHERE T.account='$account' AND T.SSN = C.teacherSSN";
        $result = $conn->query($sql);

        //顯示所控制的課程
        if (isset($result)) {
            echo "<table>";
            echo "<tr><th>課程編號</th><th>課程名稱</th></tr>";
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . str_pad($row['courseid'], 4, '0', STR_PAD_LEFT)  . "</td>";
                //跳去課程頁面
                echo "<td><a href='course.php?id=" . $row['courseid'] . "'>" . $row['Cname'] . "</a></td>";
                echo "</tr>";
            }
            echo "</table>";
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

