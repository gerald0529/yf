<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Document</title>
    <link href="<?=$this->view->css?>/seller.css" rel="stylesheet">
    <link href="<?=$this->view->css?>/base.css" rel="stylesheet" type="text/css">
    <script type="text/javascript" src="<?= $this->view->js_com?>/jquery.js" charset="utf-8"></script>
    <script>
        $(document).ready(function(){
            $(".dialog_close_button").click(function(){
                $(".set-stock-alert").css("display","none");
            })
        })
    </script>
</head>
<body>
<div class="set-stock-alert">
    <div class="set-stock" >
        <div class="dialog_body">
            </h3>
            <div class="dialog_content">
                <div class="">
                    <?php
                        if($common_base){
                    ?>
                            <div class="chain-goods-id clearfix">
                        <div class="pic-thumb"><img src="<?=$common_base['common_image']?>"></div>
                        <dl>
                            <dt><?=$common_base['common_name']?></dt>
                            <dd>SPU：<?=$common_base['common_id']?></dd>
                        </dl>
                    </div>
                    <form method="post" action="#" id="form">
                        <div class="content">
                            <table class="stock-table">
                                <thead>
                                <tr>
                                    <?php
                                    if($common_base['common_spec_name']){
                                        foreach($common_base['common_spec_name'] as $value){
                                    ?>
                                            <th class="w60"><?=$value?></th>
                                    <?php
                                        }}
                                    ?>
                                    <th>-</th>
                                    <th class="w100 tl"><?=__('商家货号')?></th>
                                    <th class="w100 tl"><?=__('价格')?></th>
                                    <th class="w50"><?=__('库存')?></th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                    if($goods_base){
                                        foreach($goods_base as $value){
                                ?>
                                        <tr>
                                            <?php
                                                if($value['goods_spec']){
                                                    foreach($value['goods_spec'] as $v){
                                            ?>
                                                        <td class="tc"><?=$v?></td>
                                            <?php
                                                }}
                                            ?>
                                            <td class="tc">-</td>
                                            <td class="tl"><?=$value['goods_code']?></td>
                                            <td class="tl"><?=format_money($value['goods_price'])?></td>
                                            <td><input type="text" class="text w40" name="stock[<?=$value['goods_id']?>]" id="stock" value="<?=$value['goods_stock']?>"></td>
                                            <input type="hidden" name="goods_id[]" value="<?=$value['goods_id']?>">
                                        </tr>
                                <?php
                                    }}
                                ?>
                                <input type="hidden" name="shop_id" value="<?=$value['shop_id']?>">
                                <input type="hidden" name="common_id" value="<?=$common_base['common_id']?>">
                                </tbody>
                            </table>
                        </div>
                        <div class="bottom">
                            <label class="submit-border">
                                <input type="submit" class="button bbc_seller_submit_btns" value="<?=__('提交门店库存设置')?>">
                            </label>
                        </div>
                    </form>
                    <?php
                        }
                    ?>
                </div>

            </div>
        </div>
    </div>
</div>

</body>
</html>
<script type="text/javascript" src="<?= $this->view->js_com ?>/plugins/validator/jquery.validator.js" charset="utf-8"></script>
<script>
    var api = frameElement.api;
    var callback = api.data.callback;


    $(document).ready(function(){

        var ajax_url = './index.php?ctl=Chain_Goods&met=setStock&typ=json';

        $('#form').validator({
            ignore: ':hidden',
            theme: 'yellow_right',
            timely: 1,
            stopOnError: false,
            valid:function(form){
                var me = this;
                // 提交表单之前，hold住表单，防止重复提交
                me.holdSubmit();
                //表单验证通过，提交表单
                $.ajax({
                    url: ajax_url,
                    data:$("#form").serialize(),
                    success:function(a){
                        if(a.status == 200)
                        {
                            parent.Public.tips.success("<?=__('操作成功！')?>");
                            if(callback && typeof callback == 'function')
                            {
                                callback(api);
                            }
                            //window.location.reload();
                        }
                        else
                        {
                            parent.Public.tips.error("<?=__('操作失败！')?>");
                            if(callback && typeof callback == 'function')
                            {
                                callback(api);
                            }
                        }
                    }
                });
            }

        });
    });
</script>