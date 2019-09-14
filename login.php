<?php
header('content-type:text/html;charset=utf-8');
include_once './lib/fun.php';

if(checkLogin()){
    msg(1,'你已登录','index.php');
}


//表单进行提交处理
if(!empty($_POST['username'])) {

    $username = trim($_POST['username']); //mysql_real_escape_string()进行过滤
    $password = trim($_POST['password']);

    //判断用户名不能为空
    if(!$username){
        msg(2,'用户名不能为空');
    }
    //判断密码不能为空
    if(!$password){
        msg(2,'密码不能为空');
    }

    //数据库连接操作

    $con=mysqli_connect('','','','');  //连接数据库，数据库结构在mysql文件
    //设置字符集
    mysqli_set_charset($con,'utf8');
    //判断是否成功连接数据库
    if(!$con){
        die('Could not connect'.mysqli_error());
    }
    $sql="SELECT * FROM gq_user WHERE username='$username'";

    //执行数据库查询命令
    $obj=mysqli_query($con,$sql);
    //把查询出来的结果放在数组，不存在则为空数组
    $result=mysqli_fetch_assoc($obj);

    //查询用户是否存在
    if(is_array($result) && !empty($result)){
        //判断密码是否一致，一致则登陆成功
        if(createPassword($password) === $result['password']){
            $_SESSION['user']=$result;
            msg(1,'登录成功','index.php');
            exit;
        }else{
            msg(2,'密码不正确，请重新输入');
        }
    }else{
        msg(2,'用户名不存在');
    }

    //关闭先前打开的数据库连接
    mysqli_close($con);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>登陆界面</title>
    <link href="./static/css/common.css" type="text/css" rel="stylesheet">
    <link href="./static/css/add.css" type="text/css" rel="stylesheet">
    <link rel="stylesheet" href="static/css/login_first.css">
</head>
<body>
<div class="header">
    <div class="logo f1">
        <img src="./static/image/logo.png">
    </div>
    <div class="auth fr">
        <ul>
            <li><a href="login.php">登录</a></li>
            <li><a href="register.php">注册</a></li>
        </ul>
    </div>
</div>
<div class="content">
    <div class="center">
        <div class="center-login">
            <div class="login-banner">
                <a href="#"><img src="./static/image/login_banner.png" alt=""></a>
            </div>
            <div class="user-login">
                <div class="user-box">
                    <div class="user-title">
                        <p>用户登录</p>
                    </div>
                    <form class="login-table" name="login" id="login-form" action="login.php" method="post">
                        <div class="login-left">
                            <label class="username">用户名</label>
                            <input type="text" class="yhmiput" name="username" placeholder="Username" id="username">
                        </div>
                        <div class="login-right">
                            <label class="passwd">密码</label>
                            <input type="password" class="yhmiput" name="password" placeholder="Password" id="password">
                        </div>
                        <div class="login-btn">
                            <button type="submit">登录</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
<div class="footer">

</div>

<script src="./static/js/jquery-1.10.2.min.js"></script>
<script src="./static/js/layer.js"></script>
<script>
    $(function () {
        $('#login-form').submit(function () {
            var username = $('#username').val(),
                password = $('#password').val();
            if (username == '' || username.length <= 0) {
                layer.tips('用户名不能为空', '#username', {time: 2000, tips: 2});
                $('#username').focus();
                return false;
            }

            if (password == '' || password.length <= 0) {
                layer.tips('密码不能为空', '#password', {time: 2000, tips: 2});
                $('#password').focus();
                return false;
            }


            return true;
        })

    })
</script>
</body>
</html>

