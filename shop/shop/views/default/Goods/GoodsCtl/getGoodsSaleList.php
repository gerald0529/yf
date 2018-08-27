<li style="display:block">
    <div class=" Top">
		<p>
			<span style="color:red;"><?=__('注意：')?></span>
			<strong><?=__('购买的价格不可能是由于店铺往期促销活动引起的，详情库咨询卖家')?></strong>
		</p>
    </div>

    <table class="details_table">
        <tr>
            <td class="w200"><?=__('买家')?></td>
			<td class="w386"><?=__('宝贝名称')?></td>
			<td class="w100"><?=__('出价')?></td>
            <td class="w100"><?=__('购买数量')?></td>
            <td class="w200"><?=__('生成时间')?></td>
        </tr>

        <?php
            if (!empty($data['items'])):
                foreach ($data['items'] as $salekey => $salevalue):
        ?>
            <tr>
                <td><?=($salevalue['order']['buyer_user_name'])?></td>
                <td class="tlf"><?=($salevalue['goods_name'])?></td>
                <td style="color:#F00;"><?=format_money($salevalue['goods_price'])?></td>
                <td><?=($salevalue['order_goods_num'])?></td>
                <td><?=($salevalue['order']['order_create_time'])?></td>
            </tr>
        <?php
                endforeach;
        ?>
		<tr style="text-align:right;">
           <td colspan="99"><p class="page page_front" style="margin-right:6px;"><?=($page_nav)?></p></td>
        </tr>
		<?php else: ?>
                <tr>
                    <td colspan="99">
                        <div class="no_account">
                            <img src="<?= $this->view->img ?>/ico_none.png"/>
                            <p><?= __('暂无符合条件的数据记录') ?></p>
                        </div>
                    </td>
                </tr>
		<?php endif;?>
	</table>
            
</li>
<script>
    $(function(){
        $(".page").find("a").click(function(){
            var url = $(this).attr('url');
            $("#saleseval").load(url, function(){
                
            });
        });
    });
</script>