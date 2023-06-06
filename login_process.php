<?php
session_start();

// 連接到資料庫
$servername = "127.0.0.1";
$username = "root";
$password = "10600508Eric";
$database = "event";

// 建立連線
$conn = new mysqli($servername, $username, $password ,$database );
// 檢查連線是否成功
if ($conn->connect_error) {
    die("連線失敗：" . $conn->connect_error);
}

// 接收使用者輸入的帳號和密碼
$username = $_POST['username'];
$password = $_POST['password'];

// 驗證登入資訊
$sqlstudent = "SELECT * FROM student WHERE account='$username' AND password='$password'";
$result_stu = $conn->query($sqlstudent);

$sqlteacher = "SELECT * FROM teacher WHERE account='$username' AND password='$password'";
$result_tea = $conn->query($sqlteacher);

$currentYear = date('Y')-1911;
$currentMonth = date('M');
$currentMonth = date('m', strtotime($currentMonth));
if($currentMonth<8 && $currentMonth>1){
    $currentSemester = "下學期";
    $currentYear--;
}else{
    $currentSemester = "上學期";
}

if ($result_stu->num_rows == 1) {
    // 登入成功，建立 Session
    $_SESSION['username'] = $username;
    echo '<form id="studentForm" method="POST" action="student.php">';
    echo '<input type="hidden" name="username" value=' . $username .'>';
    echo '<input type="hidden" name="selectedYear" value=' . $currentYear .'>';
    echo '<input type="hidden" name="selectedSemester" value=' . $currentSemester .'>';
    echo '</form>';
    echo '<script>document.getElementById("studentForm").submit();</script>';
}
else if ($result_tea->num_rows == 1) {// 登入成功，建立 Session
    $_SESSION['username'] = $username;
    header("Location:teacher.php?username=" . urlencode($username));
    // 跳轉到登入後的頁面
}
else{
    // 登入失敗，顯示錯誤訊息或重新導向到登入頁面
    $error_message = "帳號或密碼錯誤";
    header("Location: login.php?error=" . urlencode($error_message));
    exit();
}

$conn->close();
?>
