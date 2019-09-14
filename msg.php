<?php

//url type参数处理 1：操作成功 2：操作失败
$type=isset($_GET['type'])&&in_array(intval($_GET['type']),array(1,2))?intval($_GET['type']):1;

//判断成功或失败之后传参
$title=$type==1?'操作成功':'操作失败';

//
$msg=isset($_GET['msg'])?trim($_GET['msg']):'操作成功';

//获取到跳转的url
$url=isset($_GET['url'])?trim($_GET['url']):'';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo $title;?></title>
    <script src="./static/js/jquery-1.10.2.min.js"></script>
    <link rel="stylesheet" type="text/css" href="./static/css/common.css" />
    <link rel="stylesheet" type="text/css" href="./static/css/msg.css" />
</head>
<body>
<div class="header">
    <div class="logo f1">
        <img src="./static/image/logo.png">
    </div>
    <div class="auth fr">

    </div>
</div>
<div class="content">
    <div class="center">
        <div class="image_center">
            <?php if($type==2):?>
            <span class="smile_face">:( </span>
            <?php else:?>
            <span class="smile_face">:) </span>
            <?php endif;?>
        </div>
        <div class="code"><?php echo $msg?></div>

        <div class="jump">
            页面自动&nbsp<b>跳转</b>&nbsp,等待时间 <strong id="time" style="color: #009f95">3</strong>秒
        </div>
    </div>



</div>
<div class="footer">
    <p><span>M-GALLARY</span>©2017 POWERED BY IMOOC.INC</p>
</div>

<script>
    $(function () {
        var time = 3;
        var url = "<?php echo $url?>" || null;//js读取php变量
        setInterval(function () {
            if (time > 1) {
                time--;
                console.log(time);
                $('#time').html(time);
            }
            else {
                $('#time').html(0);
                if(url){
                    location.href=url;
                }else{
                    history.go(-1);
                }
            }
        }, 1000);

    })
</script>
</body>
</html>
