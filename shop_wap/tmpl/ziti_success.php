<?php
include __DIR__ . '/../includes/header.php';
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <title>提交成功</title>
    <link rel="stylesheet" href="../css/base.css">
    <link rel="stylesheet" type="text/css" href="../css/private-store.css"/>
    <script src="../js/jquery.js"></script>
</head>
<body>
<header id="header" class="fixed">
    <div class="header-wrap">
        <div class="header-l">
            <a href="javascript:history.go(-1)"> <i class="back"></i> </a>
        </div>
        <div class="header-title">
            <h1>提交成功</h1>
        </div>
    </div>
</header>
<div class="norecord tc">
    <div class="ziti-success">
        <i></i>
    </div>
    <p class="fz-30 col6 mt-40">订单提交成功</p>
    <p class="fz-30 col6 mt-20">请尽快前往门店付款</p>
</div>
</body>
</html>

<script type="text/javascript">
    setTimeout(function () {
        window.location.href = WapSiteUrl + '/tmpl/member/chain_order_list.html';
    }, 3000);
</script>>

<?php
include __DIR__ . '/../includes/footer.php';
?>
