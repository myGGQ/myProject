<?php
include_once './lib/fun.php';

if(!checkLogin()){
    msg(2,'请登录','login.php');
}

$goodsId=isset($_GET['id']) && is_numeric($_GET['id'])?intval($_GET['id']):'';

//id不存 跳转到商品页
if(!$goodsId){
    msg(2,'参数非法','index.php');
}

$con=mysqli_connect('','','','');  //连接数据库，数据库结构在mysql文件
mysqli_set_charset($con,'utf8');
if(!$con){
    die('Could not connect'.mysqli_error());
}

$sql="SELECT * FROM gq_goods WHERE id=$goodsId";
$obj=mysqli_query($con,$sql);

//当根据id查询为空时，跳转到商品表页
if(!$goods=mysqli_fetch_assoc($obj)){
    msg(2,'商品不存在', 'index.php');
}

//删除处理
unset($sql,$obj);
$sql="DELETE FROM gq_goods WHERE id=$goodsId";
$obj=mysqli_query($con,$sql);
if($obj){
    msg(1,'操作成功','index.php');
}else{
    msg(2,'操作失败','inde.php');
}
