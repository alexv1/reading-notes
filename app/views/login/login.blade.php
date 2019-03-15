<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="Joe&Alex">
    <title>用户登录</title>
    <link href="css/login.css" rel="stylesheet" type="text/css" />
</head>

<body>
<div class="login">
    <div class="loginContent">
        <h1>用户登录</h1>
        <form action="./user/login" method="post">
            <div class="loginup cf"><input type="text" id="user_name" name="user_name" class="txtUsername" /></div>
            <div class="loginpw cf"><input type="password" id="pw" name="pw" class="txtPassword" /></div>
            <div class="loginbtn"><input type="submit" id="btn_submit" class="subLogin" value="登录" /></div>
        </form>
    </div>
    <div class="copyRight">
        2016 &copy; Reading by Alex
    </div>
</div>
</body>
</html>