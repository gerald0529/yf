  <li><a href="index.php?ctl=Shop&met=goodsList&id=<?=$shop_id?>"><?=__('全部商品')?></a></li>
           <?php if(!empty($shop_cat)){
                    foreach ($shop_cat as $key => $value) {
                ?>
          <li><a href="index.php?ctl=Shop&met=goodsList&id=<?=$shop_id?>&shop_cat_id=<?=$value['shop_goods_cat_id']?>"><?=$value['shop_goods_cat_name']?></a></li>
                  <?php if(!empty($value['subclass'])){
                              foreach ($value['subclass'] as $keys => $values) {  
                      ?>
          <li class="list_style"><a href="index.php?ctl=Shop&met=goodsList&id=<?=$shop_id?>&shop_cat_id=<?=$values['shop_goods_cat_id']?>"><?=$values['shop_goods_cat_name']?></a></li>
            <?php } } } }?>