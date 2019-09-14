<?php
header('content-type:text/html;charset=utf-8');
include_once './lib/fun.php';
if($login = checkLogin())
{
    $user = $_SESSION['user'];
}

//查询商品

//检查page参数
$page=isset($_GET['page'])?intval($_GET['page']):1;
//$page与1对比 取最大值
$page=max($page,1);
//每页显示的条数
$pageSize=6;

$offset=($page-1)*$pageSize;

$con=mysqli_connect('','','','');  //连接数据库，数据库结构在mysql文件
mysqli_set_charset($con,'utf8');
if(!$con){
    die('Could not connect'.mysqli_error());
}
$sql="SELECT COUNT(id) as total FROM gq_goods WHERE user_id={$user['id']}";
$obj=mysqli_query($con,$sql);
$result=mysqli_fetch_assoc($obj);
$total=isset($result['total'])?$result['total']:0;

unset($sql,$obj,$result);
//BY id asc,view desc
$sql="select * from gq_user
INNER join gq_goods
on gq_user.id=gq_goods.user_id
where gq_user.id={$user['id']}
limit $offset,$pageSize";
$goods=array();

if($obj=mysqli_query($con,$sql)){
    while($result=mysqli_fetch_assoc($obj)){
        $goods[]=$result;
    }
}



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>个人中心</title>
    <link rel="stylesheet" type="text/css" href="./static/css/common.css"/>
    <link rel="stylesheet" type="text/css" href="./static/css/index.css"/>
</head>
<body>
<div class="header">
    <div class="logo f1">
        <img src="./static/image/logo.png">
    </div>
    <div class="auth fr">
        <ul>
                <li><a href="index.php">首页</a></li>
                <li><span>管理员：<?php echo $user['username']?></span></li>
                <li><a href="publish.php">发布商品</a></li>
                <li><a href="login_out.php">退出</a></li>
        </ul>
    </div>
</div>
<div class="content">
    <div class="img-content">
        <ul>
            <?php if(empty($goods)):?>
            <li>
                <img class="img-li-fix" src="./static/image/timg.jpg" height="300px">
                <div class="info">
                    <h3 class="img_title">还没发布商品，马上去发布吧~~</h3>

                </div>
            </li>
            <?php else:?>
            <?php foreach($goods as $v):?>
                <li>
                    <img class="img-li-fix" src="<?php echo $v['pic']?>" alt="<?php echo $v['name']?>" height="300px">
                    <div class="info">
                        <a href=""><h3 class="img_title"><?php echo $v['name']?></h3></a>
                        <p>
                            <?php echo $v['des']?>
                        </p>
                        <div class="btn">
                            <p class="price">售价：<?php echo $v['price']?>元</p>
                            <a href="edit.php?id=<?php echo $v['id']?>" class="edit">编辑</a>
                            <a href="delete.php?id=<?php echo $v['id']?>" class="del">删除</a>
                        </div>
                    </div>
                </li>
            <?php endforeach;?>
            <?php endif;?>
        </ul>
    </div>

    <?php echo pages($total,$page,$pageSize,6);?>
</div>

<div class="footer">
</div>
</body>
<script src="./static/js/jquery-1.10.2.min.js"></script>
<script>
    $(function () {
        $('.del').on('click',function () {
            if(confirm('确认删除该画品吗?'))
            {
                window.location = $(this).attr('href');
            }
            return false;
        })
    })
</script>


</html>
