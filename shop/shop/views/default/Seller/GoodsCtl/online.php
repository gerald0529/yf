<?php if (!defined('ROOT_PATH')) {
    exit('No Permission');
} ?>
<?php
    include $this -> view -> getTplPath() . '/' . 'seller_header.php';
?>
<script type='text/jade' id='thrid_opt'>
<?php
        if (count($goods) < $shop_GradeInfo['shop_grade_goods_limit']) {
            ?>
		<a class="bbc_seller_btns button add button_blue" href="<?= Yf_Registry ::get('index_page') ?>?ctl=Seller_Goods&met=add&typ=e"><i class="iconfont icon-jia"></i><?= __('发布新商品') ?></a>
<!--				<a class="bbc_seller_btns button add button_blue" id="import_goods" style="margin-right: 111px;" href="javascript:void(0)"><i class="iconfont icon-jia"></i>商品导入</a>-->
<?php } ?>



</script>
<style>
    .dis_flag {
        display: inline-block;
        width: 40px;
        background: red;
        color: #FFF;
        font-size: 12px;
        text-align: center;
    }
    
    .w320 {
        width: 320px !important;
    }
    
    .stock_wring {
        color: red;
    }
</style>
<script type="text/javascript">
  $(function () {
    $(".tabmenu").append($("#thrid_opt").html());
  });
</script>
<div class="search fn-clear">
    <form id="search_form" class="search_form_reset" method="get" action="<?= Yf_Registry ::get('url') ?>">
        <input class="text w150" type="text" name="goods_key" value="<?= ($goods_key ? $goods_key : ''); ?>"
               placeholder="<?= __('请输入商品名称') ?>"/> <input type="hidden" name="ctl" value="Seller_Goods"> <input type="hidden" name="met" value="<?= $met ? $met : 'online'; ?>"> <a class="button refresh" onclick="location.reload()"><i class="iconfont icon-huanyipi"></i></a> <a class="button btn_search_goods" href="javascript:void(0);"><i
                    class="iconfont icon-btnsearch"></i><?= __('搜索') ?></a>
    </form>
</div>
<script type="text/javascript">
  $(".search").on("click", "a.button", function () {
    $("#search_form").submit();
  });
</script>
<?php
    if (!empty($goods)) {
        if ($this -> shopBase['shop_type'] == 2) {
            $ctl = 'Supplier_Goods';
        } else {
            $ctl = 'Goods_Goods';
        }
        ?>
        <form id="form" method="post" action="index.php?ctl=Seller_Goods&met=editGoodsCommon&typ=json">
            <table class="table-list-style" width="100%" cellpadding="0" cellspacing="0">
                <tr>
                    <th class="tl">
                        <label class="checkbox"><input class="checkall" type="checkbox"/></label><?= __('商品名称') ?>
                    </th>
                    <th width="80"><?= __('价格') ?></th>
                    <th width="80"><?= __('库存') ?></th>
                    <th width="80"><?= __('发布时间') ?></th>
                    <th width="120"><?= __('操作') ?></th>
                </tr>
                <?php
                    foreach ($goods as $item) {
                        ?>
                        <tr id="tr_common_id_<?= $item['common_id']; ?>">
                            <td class="tl th" colspan="99">
                                <label class="checkbox">
                                    <input <?php if (isset($item['disabled_up']) && $item['disabled_up']) { echo 'disabled'; } ?> class="checkitem" type="checkbox" name="chk[]" value="<?= $item['common_id'] ?>" is_virtual="<?= $item['common_is_virtual'] ?>" common_virtual_date="<?= $item['common_virtual_date'] ?>">
                                </label>
                                <?= __('平台货号') ?>:<?= $item['common_id']; ?>
                                <?php if (isset($item['disabled_up']) && $item['disabled_up']) {
                                    echo '<span style="color:red;">（' . __('供应商下架商品') . '）</span>';
                                } ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="tl">
                                <dl class="fn-clear fn_dl">
                                    <dt>
                                        <i date-type="ajax_goods_list" data-id="237" class="iconfont icon-jia disb"></i>
                                        <?php if ($met == 'offline' || $met == 'OutsideImport' || $met == 'verify' || $met == 'waitReleaseGoods' || $met == 'verifyDeny' || $met == 'lockup') { ?>
                                        <a target="_blank" href="<?php echo Yf_Registry ::get('url'); ?>?ctl=Goods_Goods&met=goodspreview&typ=e&gid=<?= $item['goods_id'] ?>">
                                            <?php } else { ?>
                                            <a href="index.php?ctl=<?= $ctl ?>&met=goods&gid=<?= $item['goods_id'] ?>" target="_blank">
                                                <?php } ?>
                                                <p style="display:inline" target="_blank">
                                                    <img width="60" src="<?= $item['common_image']; ?>">
                                                </p>
                                            </a>
                                    </dt>
                                    
                                    <?php if ($met == 'offline' || $met == 'OutsideImport' || $met == 'verify' || $met == 'waitReleaseGoods' || $met == 'verifyDeny' || $met == 'lockup') { ?>
                                        <a target="_blank" href="<?php echo Yf_Registry ::get('url'); ?>?ctl=Goods_Goods&met=goodspreview&typ=e&gid=<?= $item['goods_id'] ?>">
                                    <?php } else { ?>
                                        <a href="index.php?ctl=<?= $ctl ?>&met=goods&gid=<?= $item['goods_id'] ?>" target="_blank">
                                    <?php } ?>
                                            <dd>
                                                <h3 class="w320">
                                                    <?php if ($item['common_parent_id']) { ?>
                                                        <span class="dis_flag"><?= __('分销') ?></span>
                                                    <?php } ?>
                                                    <p target="_blank" class="one-overflow w320"><?= $item['common_name'] ?></p>
                                                </h3>
                                                <p class="one-overflow w320"><?= $item['cat_name'] ?></p>
                                                <p class="one-overflow w320"><?= ($item['common_code'] ? sprintf(__('商家货号') . '：%s', $item['common_code']) : '') ?></p>
                                            </dd>
                                        </a>
                                </dl>
                            </td>
                            <td><?= format_money($item['common_price']); ?></td>
                            <td <?php if ($item['common_stock'] < $item['common_alarm']) { ?> class="colred" <?php } ?>><?= $item['common_stock'] ?> <?= __('件') ?></td>
                            <td><?php $item['common_sell_time'] !== '0000-00-00 00:00:00' ? print($item['common_sell_time']) : print($item['common_add_time']); ?></td>
                            <td>
                                <span class="edit">
                                    <a href="<?php echo Yf_Registry ::get('url'); ?>?ctl=Seller_Goods&met=online&typ=e&common_id=<?= $item['common_id'] ?>&action=edit_goods">
                                        <i class="iconfont icon-zhifutijiao"></i>
                                        <?= __('编辑') ?>
                                    </a>
                                </span>
                                <span class="del">
                                    <a data-param="{'id':'<?= $item['common_id'] ?>','ctl':'Seller_Goods','met':'deleteGoodsCommonRows','act':'del'}" href="javascript:void(0)">
                                        <i class="iconfont icon-lajitong"></i>
                                        <?= __('删除') ?>
                                    </a>
                                </span>
                            </td>
                        </tr>
                        <?php
                        // echo '<pre>';
                        // print_r($goods_detail_rows);
                        ?>
                        <tr class="tr-goods-list" style="display: none;">
                            <td colspan="5" class="tl">
                                <ul class="fn-clear">
                                    <?php
                                        if (!empty($goods_detail_rows[$item['common_id']])):
                                        foreach ($goods_detail_rows[$item['common_id']] as $g_k => $g_v):
                                            ?>
                                            <li>
                                                <?php if ($met == 'offline' || $met == 'OutsideImport' || $met == 'verify' || $met == 'waitReleaseGoods' || $met == 'verifyDeny' || $met == 'lockup') { ?>
                                                <a target="_blank" href="<?php echo Yf_Registry ::get('url'); ?>?ctl=Goods_Goods&met=goodspreview&typ=e&gid=<?= $g_v['goods_id'] ?>">
                                                    <?php } else { ?>
                                                    <a href="index.php?ctl=<?= $ctl ?>&met=goods&gid=<?= $g_v['goods_id'] ?>" target="_blank">
                                                        <?php } ?>
                                                        <div class="goods-image">
<!--                                                            <img width="100" src="--><?//= $g_v['goods_image']; ?><!--">-->
                                                            <img width="100" src="<?= $item['common_image']; ?>">
                                                        </div>
                                                        <?php if (!empty($g_v['spec'])) {
                                                            foreach ($g_v['spec'] as $ks => $vs):?>
                                                                <div class="goods_spec">
                                                                    <?= $ks; ?>：
                                                                    <span><?= $vs ?></span>
                                                                </div>
                                                            <?php endforeach;
                                                        } ?>
                                                        <div class="goods-price">
                                                            <?= __('价格') ?>：<span><?= format_money($g_v['goods_price']); ?></span>
                                                        </div>
                                                        <div class="goods-stock">
                                                            <?= __('库存') ?>
                                                            ：<span class="<?php if ($g_v['goods_alarm'] != 0 && ($g_v['goods_stock'] <= $g_v['goods_alarm'])) {echo "stock_wring";} ?>"><?= $g_v['goods_stock'] ?> <?= __('件') ?></span>
                                                        </div>
                                                        <?php if ($met == 'offline' || $met == 'OutsideImport' || $met == 'verify' || $met == 'waitReleaseGoods' || $met == 'verifyDeny' || $met == 'lockup') { ?>
                                                            <span class="view">
                                                                <i class="iconfont icon-chakan"></i>
                                                                <?= __('商品预览') ?>
                                                            </span>
                                                        <?php } else { ?>
                                                            <?= __('查看商品详情') ?>
                                                        <?php } ?>
                                                    </a>
                                            </li>
                                        <?php
                                        endforeach;
                                    endif;
                                    ?>
                                
                                </ul>
                            </td>
                        </tr>
                    
                    <?php } ?>
                <tr>
                    <td class="toolBar" colspan="1">
                        <input type="hidden" name="act" value="del"/> <label class="checkbox"><input class="checkall" type="checkbox"/></label><?= __('全选') ?>
                        <span>|</span>
                        <!--<label class="del"><i class="iconfont icon-trash"></i>删除</label>-->
                        <label class="del" data-param="{'ctl':'Seller_Goods','met':'deleteGoodsCommonRows','act':'del'}"><i
                                    class="iconfont icon-lajitong"></i><?= __('删除') ?></label>
                        
                        <?php if ($met == 'online') { ?>
                            <span>|</span>
                            <label class="down"><i class="iconfont icon-xiajia"></i><?= __('下架') ?></label>
                        <?php } elseif ($met != 'verify') { ?>
                            <span>|</span>
                            <label class="up"><i class="iconfont icon-shangjia1"></i><?= __('上架') ?></label>
                        <?php } ?>
                    </td>
                    <td colspan="99">
                        <div class="page">
                            <?= $page_nav ?>
                        </div>
                    </td>
                </tr>
            </table>
        </form>
    <?php } else { ?>
        <form id="form" method="post" action="index.php?ctl=Seller_Goods&met=editGoodsCommon&typ=json">
            <table class="table-list-style" width="100%" cellpadding="0" cellspacing="0">
                <tr>
                    <th class="tl">
                        <label class="checkbox"><input class="checkall" type="checkbox"/></label><?= __('商品名称') ?>
                    </th>
                    <th width="80"><?= __('价格') ?></th>
                    <th width="80"><?= __('库存') ?></th>
                    <th width="80"><?= __('发布时间') ?></th>
                    <th width="120"><?= __('操作') ?></th>
                </tr>
            </table>
        </form>
        <div class="no_account">
            <img src="<?= $this -> view -> img ?>/ico_none.png">
            <p><?= __('暂无符合条件的数据记录'); ?></p>
        </div>
    <?php } ?>


<script type="text/javascript">
  $("label.down").click(function () {
    var length = $(".checkitem:checked").length;
    if (length > 0) {
      var chk_value = [];//定义一个数组
      $("input[name='chk[]']:checked").each(function () {
        chk_value.push($(this).val());//将选中的值添加到数组chk_value中
      });
      $.dialog.confirm("<?=__('您确定要下架吗')?>?", function () {
        $.post(SITE_URL + "?ctl=Seller_Goods&met=editGoodsCommon&typ=json&act=down", {chk: chk_value}, function (data) {
          if (data && 200 == data.status) {
            //$.dialog.alert('删除成功',function(){location.reload();});
            Public.tips({type: 3, content: "<?=__('下架成功')?>！"});
            location.reload();
          }
          else {
            //$.dialog.alert('删除失败');
            Public.tips({type: 1, content: "<?=__('下架失败')?>！"});
          }
        });
      });
    }
    else {
      $.dialog.alert("<?=__('请选择需要操作的记录')?>");
    }
  });
  $("label.up").click(function () {
    var me = '<?php echo $met?>';
    var length = $(".checkitem:checked").length;
    if (length > 0) {
      $.dialog.confirm("<?=__('您确定要上架吗')?>?", function () {
        var chk_value = [];//定义一个数组
        $("input[name='chk[]']:checked").each(function () {
          if ($(this).attr("is_virtual") == 1 && $(this).attr("common_virtual_date") <= "<?php echo date('Y-m-d'); ?>") {
            Public.tips({type: 1, content: "<?=__('请修改虚拟商品过期时间')?>！"});
            return false;
          }
          else {
            chk_value.push($(this).val());
            $.post(SITE_URL + "?ctl=Seller_Goods&met=editGoodsCommon&typ=json&act=up&me=" + me, {chk: chk_value}, function (data) {
              if (data && 200 == data.status) {
                //$.dialog.alert('删除成功',function(){location.reload();});
                Public.tips({type: 3, content: "<?=__('上架成功')?>！"});
                location.reload();
              }
              else {
                //$.dialog.alert('删除失败');
                Public.tips({type: 1, content: "<?=__('上架失败')?>！"});
              }
            });
          }
        });
        
      });
    }
    else {
      $.dialog.alert("<?=__('请选择需要操作的记录')?>");
    }
  });
</script>
<script type="text/javascript">
  var offt = true;
  $(document).ready(function () {
    $(".table-list-style .disb").click(function () {
      if (offt) {
        $(this).parent().parent().parent().parent().next().css("display", "table-row");
        $(this).removeClass("icon-jia");
        $(this).addClass("icon-jian");
        offt = false;
      }
      else {
        $(this).parent().parent().parent().parent().next().css("display", "none");
        $(this).removeClass("icon-jian");
        $(this).addClass("icon-jia");
        offt = true;
      }
      
    });
  });
</script>
<script type="text/javascript">
  $(".dropdown").hover(function () {
    $(this).addClass("hover");
  }, function () {
    $(this).removeClass("hover");
  });
</script>

<script type="text/javascript">
  
  $(function () {
    $("#import_goods").on("click", function () {
      $.dialog({
        width: 560,
        height: 300,
        title: "<?=__('批量导入')?>",
        content: "url:" + SITE_URL + "?ctl=Seller_Goods&met=importGoods&typ=e",
        lock: !0
      });
    });
    
    if (<?= empty($no_shangjia) ? 0 : true ?>) {
      var $label = $("i.icon-shangjia1").parent("label");
      $label.prev("span").remove(), $label.remove();
    }
  });

</script>
<?php
    include $this -> view -> getTplPath() . '/' . 'seller_footer.php';
?>



