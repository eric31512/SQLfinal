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
        }
        .content{
            text-align: center;
            display: block;
            margin-bottom: 20px;
        }
        .container{
            text-align: center;
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
        <h1>登入</h1>
        <form action="login_process.php" method="POST">
            <label for="username">帳號：</label>
            <input type="text" id="username" name="username"><br><br>
            <label for="password">密碼：</label>
            <input type="password" id="password" name="password"><br><br>
            <input type="submit" value="登入" >
        </form>
        <H5>教師帳號密碼:身分證字號<BR>學生帳號密碼:學號</H5>
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

