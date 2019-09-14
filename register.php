<?php
header('content-type:text/html;charset=utf-8');
//表单进行提交处理
if(!empty($_POST['username'])){

    include_once './lib/fun.php';

    $username=trim($_POST['username']); //mysql_real_escape_string()进行过滤
    $password=trim($_POST['password']);
    $repassword=trim($_POST['repassword']);
    $time= intval(time());
    //判断用户名不能为空
    if(!$username){
        msg(2,"用户名不能为空");
    }
    //判断密码不能为空
    if(!$password){
        msg(2,"密码不能为空");
    }
    //判断确认密码不能为空
    if(!$repassword){
        msg(2,"确认密码不能为空");
    }
    //判断密码是否一致
    if($password!==$repassword){
        msg(2,"两次密码不一致，请重新输入");
    }

    //数据库连接操作

    $con=mysqli_connect('','','','');  //连接数据库，数据库结构在mysql文件
    //设置字符集
    mysqli_set_charset($con,'utf8');
    //判断是否成功连接数据库
    if(!$con){
        die('Could not connect'.mysqli_error());
    }

    //判断数据库是否已有username用户名的mysql语句
    $ql="SELECT COUNT(id) as total FROM gq_user WHERE username='$username'";
    //对数据库执行一次查询
    $obj=mysqli_query($con,$ql);
    //关联数组 ，把查询出来的结果放在数组，不存在则为空数组
    $result=mysqli_fetch_assoc($obj);

    //验证用户名是否已存在数据库
    if(isset($result['total']) && $result['total']>0){
        msg(2,"已有用户名，请重新输入");
    }

    //对密码进行加密
    $password=createPassword($password);

    //插入数据操作
    $sql="INSERT gq_user(username,password,create_time) VALUES ('$username','$password','$time')";
//    $sql="INSERT gq_user SET username=$username,password=$password,create_time=$time";
    if (!mysqli_query($con,$sql))
    {
        msg(2,'mysqli_error()');
    }else{
        msg(1,'注册成功','login.php');
    }

    //关闭先前打开的数据库连接
    mysqli_close($con);
}
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="utf-8">
    <title>注册界面</title>
    <link type="text/css" rel="stylesheet" href="./static/css/common.css">
    <link type="text/css" rel="stylesheet" href="./static/css/add.css">
    <link type="text/css" rel="stylesheet" href="static/css/login_first.css">
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
                        <p>用户注册</p>
                    </div>
                    <form class="login-table" name="register" id="register-form" action="register.php" method="post">
                        <div class="login-left">
                            <label class="username">用户名</label>
                            <input type="text" class="yhmiput" name="username" placeholder="Username" id="username">
                        </div>
                        <div class="login-right">
                            <label class="passwd">密码</label>
                            <input type="password" class="yhmiput" name="password" placeholder="Password" id="password">
                        </div>
                        <div class="login-right">
                            <label class="passwd">确认</label>
                            <input type="password" class="yhmiput" name="repassword" placeholder="Repassword"
                                   id="repassword">
                        </div>
                        <div class="login-btn">
                            <button type="submit">注册</button>
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
        $('#register-form').submit(function () {
            var username = $('#username').val(),
                password = $('#password').val(),
                repassword = $('#repassword').val();
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

            if (repassword == '' || repassword.length <= 0 || (password != repassword)) {
                layer.tips('两次密码输入不一致', '#repassword', {time: 2000, tips: 2});
                $('#repassword').focus();
                return false;
            }

            return true;
        })

    })
</script>
</body>
</html>
