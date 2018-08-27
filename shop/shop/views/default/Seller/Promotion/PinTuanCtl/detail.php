<?php if (!defined('ROOT_PATH')){exit('No Permission');}?>
<?php
include $this->view->getTplPath() . '/' . 'seller_header.php';
?>

<div class="form-style">
    <form method="post" id="form" action="<?=Yf_Registry::get('url')?>?ctl=Seller_Promotion_PinTuan&met=add&typ=e">
        <dl>
            <dt><?=__('活动名称')?>：</dt>
            <dd>
              <span><?=$data['name']?></span>
            </dd>
        </dl>

        <dl>
            <dt><?=__('拼团商品')?>：</dt>
            <dd>
              <p> <span></span> </p>
                <table class="table-list-style mb15">
                    <thead>
                        <tr>
                            <th><?=__('商品图片')?></th>
                            <th><?=__('商品名称')?></th>
                            <th><?=__('商品规格')?></th>
                            <th width="90"><?=__('商品价格')?></th>
                        </tr>
                    </thead>
                    <tbody class="join-act-goods-sku">
                            <tr data-goods-id="<?=@$data['goods']['goods_id']?>">
                                <td width="50">
         <!--                            <input type="hidden" name="join_act_goods_id[]" value="<?=@$goods['goods_id']?>" />
                                    <input type="hidden" name="join_act_common_id[]" value="<?=@$goods['common_id']?>" /> -->
                                    <div>
                                        <div class="pic-thumb">
                                            <img alt="" src="<?=@image_thumb($data['goods']['goods_image'],36,36)?>" data-src="<?=@$data['goods']['goods_image']?>" style="max-width:36px;max-height:36px;border:solid 1px #ccc;"/>
                                        </div>
                                    </div>
                                </td>
                                <td><a href="<?=Yf_Registry::get('url')?>?ctl=Goods_Goods&met=goods&type=goods&gid=<?=@$data['goods']['goods_id']?>" target="_blank"> <?=$data['goods']['goods_name']?> </a></td>
                                <td class="goods-price" width="90"> 
                                    <?php
                                    if(is_array($data['goods']['spec'])){
                                        foreach($data['goods']['spec'] as $k=>$v){
                                     ?>
                                    <?= $k ?>: <?= $v ?><br/>
                                    <?php
                                        }
                                    }else{
                                    ?>
                                        <?= 无 ?>
                                    <?php } ?>
                                </td>
                                <td class="goods-price" width="90"> <?=@format_money($data['goods']['goods_price'])?> </td>
                            </tr>
                    </tbody>
                </table>

            </dd>
        </dl>

        <dl>
            <dt><?=__('成团人数')?>：</dt>
            <dd>
                <span><?=$data['person_num']?></span>
            </dd>
        </dl>
        <dl>
            <dt><?=__('拼团库存')?>：</dt>
            <dd>
                <span><?=$data['goods']['goods_stock']?></span>
            </dd>
        </dl>
        <dl>
            <dt><?=__('商品原价')?>：</dt>
            <dd>
                <span><?=$data['detail']['price_ori']?></span>
            </dd>
        </dl>
        <dl>
            <dt><?=__('单人买价格')?>：</dt>
            <dd>
                <span><?=$data['detail']['price_one']?></span>
            </dd>
        </dl>
        <dl>
            <dt><?=__('拼团价格')?>：</dt>
            <dd>
                <span><?=$data['detail']['price']?></span>
            </dd>
        </dl>

        <dl>
            <dt><?=__('开始时间')?>：</dt>
            <dd>
                <span><?=$data['start_time']?></span>
            </dd>
        </dl>
        <dl>
            <dt><?=__('结束时间')?>：</dt>
            <dd>
                <span><?=$data['end_time']?></span>
            </dd>
        </dl>
    </form>
</div>

<script type="text/javascript">
    $(document).ready(function(){

    })

</script>
<?php
include $this->view->getTplPath() . '/' . 'seller_footer.php';
?>

