<?php if (!defined('ROOT_PATH')) {exit('No Permission');}?>
<?php
include $this->view->getTplPath() . '/'  . 'header.php';
?>
    <link href="<?=$this->view->css?>/index.css" rel="stylesheet" type="text/css">
    <link href="<?=$this->view->css?>/shop_table.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="<?=$this->view->css_com?>/jquery/plugins/validator/jquery.validator.css">
    <script type="text/javascript" src="<?=$this->view->js_com?>/plugins/validator/jquery.validator.js" charset="utf-8"></script>
    <script type="text/javascript" src="<?=$this->view->js_com?>/plugins/validator/local/zh_CN.js" charset="utf-8"></script>
    </head>
    <body>
    <style>

        .ui-jqgrid tr.jqgrow .img_flied{padding: 1px; line-height: 0px;}
        .img_flied img{width: 100px; height: 30px;}

    </style>
    <div style="   overflow: hidden;
    padding: 10px 3% 0;
    text-align: left;" >
        <?php
//        echo "<pre>";
//        print_r($data);
//        echo "</pre>";
        if (empty($data)){
            echo "<span>暂无信息</span>";
        }else{
        foreach ($data as $key => $value) {
                if(!empty($value['card_id'])){
         ?>

                    <table border="0" cellpadding="0" cellspacing="0" class="store-joinin">
                        <thead>
                        <tr>
                            <th colspan="20">购物卡信息</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <th class="w150">卡片激活码：</th>
                            <td colspan="20"><?=$value['card_code']?></td>

                        </tr>
                        <tr>
                            <th>卡片id：</th>
                            <td><?=$value['card_id']?></td>
                            <th>领奖时间：</th>
                            <td><?=$value['card_fetch_time']?></td>
                        </tr>
                        <tr>
                            <th>领卡人的服务器id：</th>
                            <td><?=$value['server_id']?></td>
                            <th>领卡人账号：</th>
                            <td><?=$value['user_account']?></td>

                        </tr>
                        <tr>
                            <th>购物卡余额：</th>
                            <td><?=$value['card_money']?></td>
                            <th>卡片密码：</th>
                            <td><?=$value['card_password']?></td><tr>
                        </tr>
                        </tr>
                        <tr>
                            <th>卡牌的名称：</th>
                            <td><?=$value['card_base']['card_name']?></td>
                            <th>卡牌生成时间：</th>
                            <td><?=$value['card_time']?></td>
                        </tr>
                        <tr>
                            <th>卡片数量：</th>
                            <td><?=$value['card_base']['card_num']?></td>
                            <th>卡片描述：</th>
                            <td><?=$value['card_base']['card_desc']?></td>
                        </tr>
                        <tr>
                            <th>卡的有效开始时间：</th>
                            <td><?=$value['card_base']['card_start_time']?></td>
                            <th>卡的有效结束时间：</th>
                            <td><?=$value['card_base']['card_end_time']?></td>
                        </tr>
<!--                        <tr>-->
<!--                            <th>卡的积分：</th>-->
<!--                            <td>--><?//=$value['card_base']['card_start_time']?><!--</td>-->
<!--                            <th>卡的余额：</th>-->
<!--                            <td>--><?//=$value['card_base']['card_end_time']?><!--</td>-->
<!--                        </tr>-->

                        </tbody>
                    </table>

            <?php }}}?>

    </div>

<?php
include $this->view->getTplPath() . '/' . 'footer.php';
?>