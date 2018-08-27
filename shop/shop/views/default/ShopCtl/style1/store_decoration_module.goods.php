
<?php 
if(empty($decoration_detail['goods_list'])) {
    $block_content = empty($block_content) ? $decoration_detail['block_content'] : $block_content; 
    $goods_list = $block_content;
} else {
    $goods_list = $decoration_detail['goods_list'];
}
?>
<?php if(!empty($goods_list) && is_array($goods_list)){?>

<ul class="goods-list">
  <?php foreach($goods_list as $key=>$val){?>
    <?php $goods_url = "index.php?ctl=Goods_Goods&met=goods&gid=".$val['goods_id'];?>
  <li nctype="goods_item" data-goods-id="<?php echo $val['goods_id'];?>" data-goods-name="<?php echo $val['common_name'];?>" data-goods-price="<?php echo $val['common_price'];?>"  data-goods-image="<?php echo $val['common_image'];?>">
      <div class="goods-thumb"> <a href="<?php echo $goods_url;?>" target="_blank" title="<?php echo $val['common_name'];?>"> <img src="<?php echo $val['common_image']?>" alt="<?php echo $val['common_name'];?>"> </a> </div>
    <dl class="goods-info">
      <dt><a  target="_blank" title="<?php echo $val['common_name'];?>"><?php echo $val['common_name'];?></a></dt>
      <dd><?php echo "￥".$val['common_price'];?></dd>
    </dl>
    <?php if(!empty($decoration_detail['goods_list'])) { ?>
    <a nctype="btn_module_goods_operate" class="ncsc-btn-mini" href="javascript:;"><i class="icon-plus"></i><?=__('选择添加')?></a>
    <?php } ?>
  </li>
  <?php } ?>
</ul>
<?php if(!empty($decoration_detail['goods_list'])) { ?>
<div class="pagination"></div>
<?php } ?>
<?php } else { ?>
<?php if(!empty($decoration_detail['goods_list'])) { ?>
<div><?=__('店铺内无商品')?></div>
<?php } ?>
<?php } ?>
