<?php if (!defined('ROOT_PATH'))
{
    exit('No Permission');
} ?>
<?php
include $this->view->getTplPath() . '/' . 'seller_header.php';
?>
<link rel="stylesheet" type="text/css" href="<?= $this->view->css ?>/seller_center.css"/>
<link rel="stylesheet" type="text/css" href="<?= $this->view->css ?>/detail.css"/>

<div id="mainContent">
    <div class="fl mr50" style="width: 100%;">
        <div class="padding20">
        <table width="100%" class="table-list-style table-promotion-list" border="0" cellpadding="0" cellspacing="0">
            <tr>
                <th><?=__('序号')?></th>
                <th><?=__('商品名称')?></th>
                <th><?=__('下单数')?></th>
                <th><?=__('下单金额')?></th>
                <th><?=__('操作')?></th>
            </tr>

            <?php

            if($data_productbase)
            {
                foreach($data_productbase as $k => $v) {
                    echo '<tr>
								<td>'.($k+1).'</td>
								<td>
									<div class="padding10fl">
										<div class="pic80 ">
											 <a href="'.$v["apb_url"].'"><img src="'.$v["apb_pic"].'"></a>
										</div>
										<div class="padding10fl">
											<h4 class="pro_title tl"><a href="'.$v["apb_url"].'">'.$v["apb_name"].'</a><p  class="one-overflow w280" align="left">'.__('规格').'：'.$v["spec"].'</p></h4>
											
										</div>
									</div>
								</td>
								<td>'.$v["apb_sales_num"].'</td>
								<td>'.$v["apb_total_price"].'</td>
								<td><a href="javascript:;" onclick="getAjax('.$v['apb_product_id'].')">'.__('单品分析').'</a></td>
							</tr>';
                }
            }
            ?>
        </table>
    </div>
        <div class="page">
            <?php
            echo $page_nav;
            //输出分页信息，显示上一页和下一页的连接
//            if($page>1)			  echo " <a href='?ctl=Seller_Analysis_Goods&met=detail&plat_id=".$plat_id."&shop_id=".$shop_id."&keywords=".$keywords."&page=".($page-1)."'>".__('上一页')."</a> ";
//            if($page-1>1)		  echo " <a href='?ctl=Seller_Analysis_Goods&met=detail&plat_id=".$plat_id."&shop_id=".$shop_id."&keywords=".$keywords."&page=".($page-2)."'>".($page-2)."</a> ";
//            if($page>1)			  echo " <a href='?ctl=Seller_Analysis_Goods&met=detail&plat_id=".$plat_id."&shop_id=".$shop_id."&keywords=".$keywords."&page=".($page-1)."'>".($page-1)."</a> ";
//            if($maxPages>1)		  echo " <b>".($page)."</b> ";
//            if($page<$maxPages)   echo " <a href='?ctl=Seller_Analysis_Goods&met=detail&plat_id=".$plat_id."&shop_id=".$shop_id."&keywords=".$keywords."&page=".($page+1)."'>".($page+1)."</a> ";
//            if($page+1<$maxPages) echo " <a href='?ctl=Seller_Analysis_Goods&met=detail&plat_id=".$plat_id."&shop_id=".$shop_id."&keywords=".$keywords."&page=".($page+2)."'>".($page+2)."</a> ";
//            if($page<$maxPages)   echo " <a href='?ctl=Seller_Analysis_Goods&met=detail&plat_id=".$plat_id."&shop_id=".$shop_id."&keywords=".$keywords."&page=".($page+1)."'>".__('下一页')."</a> ";
            ?>
        </div>
    </div>
    <div class="h30 cb">&nbsp;</div>
    <div style="clear:both"></div>
</div>

<script>
    function getAjax(a){
        var product_id = a;
        var analysis = 'analysis';
        $('.padding20').html('<div class="loading"></div>');
        var url = "?ctl=Seller_Analysis_Goods&met=" + analysis + "&plat_id=" + <?php echo $plat_id?> + "&shop_id=" + <?php echo $shop_id?> + "&product_id=" + product_id;
        var pars = {};
        $.post(url,pars,showResponse);
        function showResponse(originalRequest)
        {
            $(".padding20").html('');
            $(".page").html('');
            $(".padding20").html(originalRequest);
        }
//        $(".left_content p").removeClass("cur");
//        $('.' + a + ' p').addClass("cur");
    }
</script>

<?php
include $this->view->getTplPath() . '/' . 'seller_footer.php';
?>
