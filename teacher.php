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
    ?>
    <?php
    // 檢查是否有表單提交
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['selectedYear']) && isset($_POST['selectedSemester'])) {
            $year = $_POST['selectedYear'];
            $semester = $_POST['selectedSemester'];
            $account = $_POST['username'];
        }
    }
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

        .logout{
            position: fixed;
            top: 10px;
            right: 10px;
            border: 2px solid black;

        }

        /*顯示教師資訊的button*/
        #myButton {
            position: fixed;
            display: flex;
            justify-content: center;
            align-items: center;
            text-align: center;
            top: 10px;
            left: 10px;
            width: 80px;
            height: 60px;
            border: 2px solid black;
            cursor: pointer;
            background-color: darkgoldenrod;
        }

        #myPage {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 9999;
        }

        #teacherData {
            border-collapse: collapse; /* 合併邊框 */
            border: 2px solid darkgreen;
            background-color: #f1f1f1;
            text-align: center;
            align-items: center;
            justify-content: center;
        }

        /*顯示教師資訊的button*/

        /*顯示教師該學期課程的table*/
        #courseTable{
            border-collapse: collapse; /* 合併邊框 */
            border: 2px solid darkgreen;
            background-color: #f1f1f1;
            text-align: center;
            align-items: center;
            justify-content: center;
            width: 50%;
        }

        /*查詢學年學期的table*/
        #serachTable{
            border-collapse: collapse; /* 合併邊框 */
            border: 2px solid darkgreen;
            background-color: #f1f1f1;
            text-align: center;
            align-items: center;
            justify-content: center;
            width: 30%;
        }

        th, td {
            border: 2px solid darkgreen; /* 設置邊框 */
            padding: 8px; /* 設置內邊距 */
            text-align: center; /* 文字居中對齊 */
            width: auto; /* 將每個欄位的寬度設置為自動調整 */
        }

        .divContent{
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            width: 100%;
        }


    </style>
</head>
<body>



<!--右上角登出-->
<button class="logout" onclick="redirectToAnotherPage()" style="height: 60px ;width: 80px ;background-color: darkgoldenrod">登出</button>
<script>
    function redirectToAnotherPage() {
        window.location.href = "login.php"; // 將這裡的網址替換為您想要跳轉到的頁面的網址
    }
</script>
<!--右上角登出-->

<div style="width: 100%">
    <!--標題-->
    <div class="content" >
        <h1 style="font-size: 50px ;color: darkgreen">課程管理</h1>
    </div>
    <!--顯示教師資料-->
    <div id="myButton"  onmouseover="showPage()" onmouseout="hidePage()">
        <?php
        $sql = "SELECT name FROM teacher T WHERE T.account='$account' ";
        $result = $conn->query($sql);
        if (isset($result)) {
            $row = $result->fetch_assoc();
            echo "教師:<br>";
            echo $row['name'] ;
        }
        ?>
    </div>
    <div id="myPage" style="text-align: center">
        <table id="teacherData">
            <tr><th>身分證字號</th><th>姓名</th><th>性別</th><th>生日</th><th>系所</th></tr>
            <?php
            $sql = "SELECT SSN,name,sex,bdate,department FROM teacher WHERE account='$account' ";
            $result = $conn->query($sql);
            if (isset($result)) {
                while ($row = $result->fetch_assoc()) {
                    $inputString = $row['bdate'];
                    $Year = substr($inputString, 0, 4);
                    $month = substr($inputString, 4, 2);
                    $day = substr($inputString, 6, 2);
                    $formattedDate = $Year . "/" . $month . "/" . $day;
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
    <div class="divContent">
        <table id="serachTable">
            <form method="POST" action="teacher.php">
                <tr>
                    <td><label for="year">學年:</label></td>
                    <td><select id="year" name="selectedYear" style="width: 100% ;height: 100%">
                            <?php
                            $currentYear = date('Y')-1911;
                            $startYear = $currentYear - 5;
                            $endYear = $currentYear + 5;
                            for ($i = $startYear; $i <= $endYear; $i++) {
                                $selected = '';
                                if (isset($_POST['selectedYear']) && $_POST['selectedYear'] == $i) {
                                    $selected = 'selected';
                                }
                                echo "<option value=\"$i\" $selected>$i</option>";
                            }
                            ?>
                        </select></td>
                    <td><label for="semester">學期:</label></td>
                    <td><select id="semester" name="selectedSemester" style="width: 100% ;height: 100%">
                            <option value="上學期" <?php if(isset($_POST['selectedSemester']) && $_POST['selectedSemester'] === '上學期') echo 'selected'; ?>>上學期</option>
                            <option value="下學期" <?php if(isset($_POST['selectedSemester']) && $_POST['selectedSemester'] === '下學期') echo 'selected'; ?>>下學期</option>
                        </select></td>
                </tr>
        </table>
        <br>
        <input type="hidden" name="username" value=<?php echo $account ?>>
        <input type="submit" value="查詢" style="height: 40px ;width: 60px ;background-color: darkgoldenrod">
        </form>
    </div>
    <br>
    <!--課程列表-->
    <div  class="divContent">
        <?php
        //查詢控制的課程id及名字
        $sql = "SELECT courseid AS id,Cname AS name FROM course WHERE teacherSSN='$account' AND year='$year' AND semester='$semester'";
        $result = $conn->query($sql);
        //顯示所控制的課程
        if (isset($result)) {
            echo "<table id='courseTable'>";
            echo "<tr><th>課程編號</th><th>課程名稱</th></tr>";
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . str_pad($row['id'], 4, '0', STR_PAD_LEFT)  . "</td>";
                //跳去課程頁面
                echo '<td>';
                echo '<form method="post" action="course.php">';
                echo '<input type="submit" value="' . $row['name'] . '">';
                echo '<input type="hidden" name="id" value="' . $row['id'] . '">';
                echo '<input type="hidden" name="selectedYear" value="' . $year . '">';
                echo '<input type="hidden" name="selectedSemester" value="' . $semester . '">';
                echo '</form>';
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

