<?php
header('content-type:text/html;charset=utf-8');
include_once './lib/fun.php';

if(!checkLogin()){
    msg(2,'请登录','login.php');
}

$user=$_SESSION['user'];

//表单进行提交处理
if(!empty($_POST['name'])){

    //连接数据库
    $con=mysqli_connect('','','','');  //连接数据库，数据库结构在mysql文件
    mysqli_set_charset($con,'utf8');
    if(!$con){
        die('Could not connect'.mysqli_error());
    }

    //时间戳
    $now=$_SERVER['REQUEST_TIME'];
    //商品名称
    $name=mysqli_real_escape_string($con,trim($_POST['name']));
    //商品价格
    $price=intval($_POST['price']);
    //商品简介
    $des=mysqli_real_escape_string($con,trim($_POST['des']));

    $nameLength=mb_strlen($name,'utf-8');
    if($nameLength<=0 || $nameLength>30){
        msg(2,'商品名称应在1-30字符之内');
    }
    if($price<0 || $price>999999999){
        msg(2,'请输入正确的价格');
    }
    $desLength=mb_strlen($des,'utf-8');
    if ($desLength<=0 || $desLength>100){
        msg(2,'商品简介应在1-100字符之间');
    }
    //用户id
    $userId=$user['id'];
    //商品上传图片
    $pic=imgUpload($_FILES['file']);

    //数据入库处理
    $sql="INSERT gq_goods(name,price,pic,des,user_id,create_time,update_time) VALUES ('$name','$price','$pic','$des','$userId','$now','$now')";
    $obj=mysqli_query($con,$sql);
    if($obj){
        msg(1,'发布成功','index.php');
    }else{
        echo mysqli_error();
    }

    //关闭先前打开的数据库连接
    mysqli_close($con);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>M-GALLARY|发布商品</title>
    <link type="text/css" rel="stylesheet" href="./static/css/common.css">
    <link type="text/css" rel="stylesheet" href="./static/css/add.css">
</head>
<body>
<div class="header">
    <div class="logo f1">
        <img src="./static/image/logo.png">
    </div>
    <div class="auth fr">
        <ul>
            <li><a href="index.php">首页</a></li>
            <li><span>用户: <?php echo $user['username']?></span></li>
            <li><a href="#">退出</a></li>
        </ul>
    </div>
</div>
<div class="content">
    <div class="addwrap">
        <div class="addl fl">
            <header>发布商品</header>
            <!--enctype表明有上传文件处理-->
            <form name="publish-form" id="publish-form" action="publish.php" method="post"
                  enctype="multipart/form-data">
                <div class="additem">
                    <label id="for-name">商品名称</label><input type="text" name="name" id="name" placeholder="请输入商品名称">
                </div>
                <div class="additem">
                    <label id="for-price">价值</label><input type="text" name="price" id="price" placeholder="请输入商品价值">
                </div>
                <div class="additem">
                    <!-- 使用accept html5属性 声明仅接受png gif jpeg格式的文件                -->
                    <label id="for-file">商品</label><input type="file" accept="image/png,image/gif,image/jpeg" id="file"
                                                          name="file">
                </div>
                <div class="additem textwrap">
                    <label class="ptop" id="for-des">商品简介</label>
                    <textarea id="des" name="des" placeholder="请输入商品简介"></textarea>
                </div>
                <div style="margin-top: 20px">
                    <button type="submit">发布</button>
                </div>

            </form>
        </div>
        <div class="addr fr">
            <img src="./static/image/index_banner.png">
        </div>
    </div>

</div>
<div class="footer">
</div>

</body>
<script src="./static/js/jquery-1.10.2.min.js"></script>
<script src="./static/js/layer.js"></script>
<script>
    $(function () {
        $('#publish-form').submit(function () {
            var name = $('#name').val(),
                price = $('#price').val(),
                file = $('#file').val(),
                des = $('#des').val(),
                content = $('#content').val();
            if (name.length <= 0 || name.length > 30) {
                layer.tips('画品名应在1-30字符之内', '#name', {time: 2000, tips: 2});
                $('#name').focus();
                return false;
            }
            //验证为正整数
            if (!/^[1-9]\d{0,8}$/.test(price)) {
                layer.tips('请输入最多9位正整数', '#price', {time: 2000, tips: 2});
                $('#price').focus();
                return false;
            }

            if (file == '' || file.length <= 0) {
                layer.tips('请选择图片', '#file', {time: 2000, tips: 2});
                $('#file').focus();
                return false;

            }

            if (des.length <= 0 || des.length >= 100) {
                layer.tips('画品简介应在1-100字符之内', '#content', {time: 2000, tips: 2});
                $('#des').focus();
                return false;
            }

            if (content.length <= 0) {
                layer.tips('请输入画品详情信息', '#container', {time: 2000, tips: 3});
                $('#content').focus();
                return false;
            }
            return true;

        })
    })
</script>
</html>

