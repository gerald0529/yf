<?php if (!defined('ROOT_PATH')){exit('No Permission');}?>
<?php
include $this->view->getTplPath() . '/' . 'header.php';
?>
<link rel="stylesheet" href="<?= $this->view->css ?>/PP2.css" type="text/css" />
<script type="text/javascript" src="<?= $this->view->js_com ?>/jquery.js"></script>
<script type="text/javascript" src="<?= $this->view->js ?>/tuangou-index.js"></script>
<script src="<?=$this->view->js_com?>/plugins/jquery.slideBox.min.js" type="text/javascript"></script>
<script type="text/javascript" src="<?= $this->view->js ?>/base.js"></script>
<script type="text/javascript"  src="<?= $this->view->js ?>/brank.js"></script>
<div class="goods_det_about clearfix">
    <div class="wrap clearfix">
        <div style="float:left; padding-left:30px; margin-top:25px;">
        <p style="width:183px;"><?=__('品牌')?><i class="iconfont icon-iconjiantouyou"></i> <span style="color:#000"><?=$data_brand['brand_name'] ?></span></p>
        </div>
        <div class="Topdiv clearfix"  style="float:left">
            <ul class="topUI clearfix">
                <?php
                    if(!empty($data_cat_goods)):
                    foreach($data_cat_goods as $key_cat_goods=>$value_cat_goods):
                ?>
                        <li><a href="index.php?ctl=Goods_Brand&met=brandList&brand_id=<?=$value_cat_goods['brand_id'] ?>"><img src="<?=$value_cat_goods['brand_pic'] ?>" style="width:92px;height:38px; " /></a></li>
                <?php
                    endforeach;
                    endif;
                ?>
            </ul>
        </div>
        <div class="paging clearfix">
                <a class="top iconfont icon-iconjiantoushang"> </a>
                <a class="next iconfont icon-iconjiantouxia"></a>
            </div>
    </div> 
</div>
<div class="wrapsa clearfix">
    <div class="wrapleftS clearfix">
        <div class="divleft">
            <p><img style="width:100%;" src="<?=$data_brand['brand_pic']; ?>" /></p>
            <p style="margin:10px 0px;"><em class="num-color"><?=$data_brand['brand_collect']; ?></em><?=__('人已关注')?></p>
            <a class="btn bbc_bg">
                <?php if(!isset($data_favorites)||empty($data_favorites)){ ?>
                    <div  onclick="collectBrand(<?=($data_brand['brand_id'])?>)" class="brand_list_d"><i class="iconfont icon-iconxihuan"></i><?=__('关注')?></div>
                <?php }else{ ?>
                    <div  onclick="canleCollectBrand(<?=($data_brand['brand_id'])?>)" class="cancel_save"><i class="iconfont icon-iconxihuan"></i><?=__('取消关注')?></div>
                <?php } ?>
            </a>
        </div>
        <div class="divleft_2">
            <p><?=__('品牌信息 ')?><span class="Fcolor">Information</span></p>
            <p style="color:#CCC;font-weight:bold">--</p>
            <p><span  class="Fcolor"><?=__('品牌名:')?></span><?=$data_brand['brand_name'] ?></p>
            <!--<p><span  class="Fcolor">创建年代:</span> 1921</p>-->
        </div>
    </div>
    <div class="wrapright">
        <div class="martop">
            <div class="divhead clearfix">
                <p style="float:left" class="fontc"><?=__('品牌商品')?>  <b style="color:#CCC; font-weight:100">new</b> </p>
                <p style="float:right"><a class="aHover" href="index.php?ctl=Goods_Goods&met=goodslist&brand_id=<?=$data_brand['brand_id']; ?>"><?=__('查看全部新品')?> <i class="iconfont icon-youyuan"></i></a></p>
            </div>
            <div class="paipaisy_div_1"> <a class="itemList-prev  iconfont icon-iconjiantoushang" ></a>
                <ul class="ml">
                    <?php if(!empty($data_goods_common)):
                            foreach($data_goods_common as $key=>$value):
                        ?>
                                <li><a href="index.php?ctl=Goods_Goods&met=goods&type=goods&gid=<?=$value['goods_id'] ?>"><img src="<?=$value['common_image'] ?>" width="190px" /></a>
                                    <p class="iLsc-date bbc_bg"><?=$value['common_add_time'] ?></p>
                                    <p class="Price bbc_color"><?=format_money($value['common_price']) ?></p>
                                    <p class="pFont"><a href="index.php?ctl=Goods_Goods&met=goods&type=goods&gid=<?=$value['goods_id'] ?>"><?=$value['common_name'] ?></a></p>
                                </li>
                    <?php
                            endforeach;
                        endif; ?>
                </ul>
                <a class="itemList-next iconfont icon-iconjiantouxia"></a> </div>
        </div>
        <div  class="martop">
            <div class="divhead clearfix">
                <p style="float:left" class="fontc"><?=__('大家都在买')?><b style="color:#CCC; font-weight:100">  hot 	</b> </p>
                <p style="float:right"><a class="aHover" href="index.php?ctl=Goods_Goods&met=goodslist&brand_id=<?=$data_brand['brand_id'];?>"><?=__('查看更多')?><i class="iconfont icon-youyuan"></i></a></p>
            </div>
            <ul  class="Coupons clearfix">
                <?php if(!empty($data_all_buy)): foreach($data_all_buy as $k=>$v): ?>
                    <li>
                        <a href="index.php?ctl=Goods_Goods&met=goods&type=goods&gid=<?=$v['goods_id'] ?>"><img src="<?=$v['common_image'] ?>" width="200"  /></a>
                        <div>
                            <p class="flagShipRed bbc_color" style="display:inline-block;"><?=format_money($v['common_price']) ?> </p>
                            <p style="display:inline-block; margin-left:15px;" class="del"><?=format_money($v['common_market_price']) ?></p>
                            <br />
                            <p class="pFont"><a href="index.php?ctl=Goods_Goods&met=goods&type=goods&gid=<?=$v['goods_id'] ?>"> <?=$v['common_name'] ?></a></p>
                            <p class="font" style="display:inline-block;"><?=__('销量:')?><span  style="color:#005aa0;margin-left:4px;"><?=$v['common_salenum'] ?></span></p>
                            <!--<p style="float:left; margin-left:10px;" class="font" >评价<span style="color:#3687be">918</span></p>-->
                        </div>
                    </li>
                <?php  endforeach; endif; ?>
             </ul>
        </div>
    </div>
</div>
</div>
<script>
    //关注商品
		window.collectBrand = function(e){
			if (<?=Perm::checkUserPerm()?1:0?>)
			{
				$.post(SITE_URL  + '?ctl=Goods_Brand&met=collectBrand&typ=json',{brand_id:e},function(data)
				{
					if(data.status == 200)
					{
						//$.dialog.alert(data.data.msg);
						location.reload();
						Public.tips({ content: data.data.msg});
					}
					else
					{
						//$.dialog.alert(data.data.msg);
						Public.tips({ content: data.data.msg});
					}
				});
			}
			else
			{
				$.dialog.alert("<?=__('请先登录!')?>");
			}

		}
		window.canleCollectBrand = function(e){
			if (<?=Perm::checkUserPerm()?1:0?>)
			{
				$.post(SITE_URL  + '?ctl=Goods_Brand&met=canleCollectBrand&typ=json',{brand_id:e},function(data)
				{
					if(data.status == 200)
					{
						//$.dialog.alert(data.data.msg);
						location.reload();
						Public.tips({ content: data.data.msg});
					}
					else
					{
						//$.dialog.alert(data.data.msg);
						Public.tips({ content: data.data.msg});
					}
				});
			}
			else
			{
				$.dialog.alert("<?=__('请先登录!')?>");
			}

		}
</script>
<!-- 尾部 -->
<?php
include $this->view->getTplPath() . '/' . 'footer.php';
?>