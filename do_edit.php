<?php
include_once './lib/fun.php';
if(!checkLogin()){
    msg(2,'请登录','login.php');
}

//提交表单进行处理
if(!empty($_POST['name'])){
    //连接数据库
    $con=mysqli_connect('','','','');  //连接数据库，数据库结构在mysql文件
    mysqli_set_charset($con,'utf8');
    if(!$con){
        die('Could not connect'.mysqli_error());
    }

   $goodsId=intval($_POST['id']);

    if(!$goodsId){
        msg(2,'参数非法');
    }

    //查询数据库中是否有指定id
    $sql="SELECT * FROM gq_goods WHERE id=$goodsId";
    $obj=mysqli_query($con,$sql);

    //当根据id查询为空时，跳转到商品表页
    if(!$goods=mysqli_fetch_assoc($obj)){
        msg(2,'商品不存在','index.php');
    }

    //处理表单数据
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

    $now=$_SERVER['REQUEST_TIME'];
    //更新数组
    $update=array(
        'name'=>$name,
        'price'=>$price,
        'des'=>$des,
        'update_time'=>$now
    );

    //仅当用户选择上传图片 才进行图片处理
    if($_FILES['file']['size']>0){
        $pic=imgUpload($_FILES['file']);
        $update['pic']=$pic;
    }

    //只更新被用户更改的信息 对比数据库数据跟用户数据表单
    foreach($update as $key=>$value){
        if($goods[$key]==$value){  //对应key相等 删除要更新字段
            unset($update[$key]);
        }
    }

    //对比2个数组 如果没有需要更新的字段
    if(empty($update)){
        msg(1,'操作成功','edit.php?id='.$goodsId);
    }

    $updateSql='';
    foreach($update as $k=>$v){
        $updateSql.="$k='$v',";
    }
    //去除最后的多余 , 号
    $updateSql=rtrim($updateSql,',');

    //操作数据库更改适数据
    //unset释放变量
    unset($sql,$obj);
    $sql="UPDATE gq_goods SET $updateSql WHERE id=$goodsId";
    $result=mysqli_query($con,$sql);
    if($result){
        msg(1,'操作成功','edit.php?id='.$goodsId);
    }else{
       msg(2,'操作失败','edit.php?id='.$goodsId);
    }





    }else{
    msg(2,'参数非法','index.php');
    }