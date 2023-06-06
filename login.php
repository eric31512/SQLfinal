<!DOCTYPE html>
<html>
<head>
    <title>登入</title>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-image: url("https://images.pexels.com/photos/1525041/pexels-photo-1525041.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2");
            background-size: cover; /* 調整圖片大小以填滿背景 */
            background-repeat: no-repeat; /* 避免重複平鋪 */
        }
        .content{
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 200px; /* 設定高度 */
        }
        .mainContent{
            text-align: center;
            background-color: #f1f1f1;
        }
        #error-message{
            text-align: center;
            clear: both;
        }
    </style>
</head>
<body>
<div>
    <div class="content">
        <h1 style="font-size: 50px ;color: darkgreen">登入</h1>
    </div>
    <div class="mainContent">
        <form action="login_process.php" method="POST">
            <label for="username" style="background-color: #f1f1f1 ;width: 20%">帳號：</label>
            <input type="text" id="username" name="username" style="width: 70%"><br><br>
            <label for="password" style="background-color: #f1f1f1;width: 20%"">密碼：</label>
            <input type="password" id="password" name="password" style="width: 70%"><br><br>
            <input type="submit" value="登入" style="width: 40px ; height: 30px ; background-color: darkgoldenrod">
        </form>
        <H5 style="background-color: #f1f1f1">教師帳號密碼:身分證字號<BR>學生帳號密碼:學號</H5>
    </div>

    <div id="error-message">
        <?php
        if (isset($_GET['error'])) {
            $error_message = $_GET['error'];
            echo $error_message;
        }
        ?>
    </div>
</div>


</body>
</html>

