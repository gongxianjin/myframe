<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $title;?></title>
    <link href="<?php echo __SADMIN_BASE__?>/style/style.css" rel="stylesheet">
</head>
<body>
<header>
    <div class="logo">
        <img class="center" src="<?php echo __SADMIN_BASE__?>/images/icon_03.png">
    </div>
    <div class="topDiv">
        <img src="<?php echo __SADMIN_BASE__?>/images/top3.png">
        <a href="<?php echo __SADMIN__?>/Public/logout"><span>退出系统</span></a>
    </div>
    <div class="topDiv" id="XGMM">
        <img src="<?php echo __SADMIN_BASE__?>/images/top2.png">
        <span>修改密码</span>
    </div>
    <div class="topDiv">
        <img src="<?php echo __SADMIN_BASE__?>/images/top1.png">
        <span><?php if($_SESSION['SADMIN_ID'] == '1'):?>超级管理员<?php else:?><?php echo $_SESSION['SADMIN_USER']?><?php endif;?></span>
    </div>
</header>