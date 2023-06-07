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
//取的課程id


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $course = $_POST['course']; // 接收課程ID
    $year = $_POST['selectedYear'];
    $semester = $_POST['selectedSemester'];

    // 處理學生成績
    foreach ($_POST as $key => $value) {
        // 忽略course ID和submit
        if ($key == "course" || $key == "submit" ||$key == "selectedYear" ||$key == "selectedSemester") {
            continue;//跳過此循環
        }

        $studentId = $key; // 學生id
        $grade = $conn->real_escape_string($value); // 轉役成績

        // 更新学生成绩
        $sql = "UPDATE choosecourse SET grade = '$grade' WHERE courseid = '$course' AND studentid = '$studentId'";
        if ($conn->query($sql) === TRUE) {
        } else {
            echo $conn->error;
        }
    }
}
echo '<form id="studentForm" method="POST" action="course.php">';
echo '<input type="hidden" name="id" value=' . $course .'>';
echo '<input type="hidden" name="selectedYear" value=' . $year .'>';
echo '<input type="hidden" name="selectedSemester" value=' . $semester .'>';
echo '</form>';
echo '<script>document.getElementById("studentForm").submit();</script>';
?>
