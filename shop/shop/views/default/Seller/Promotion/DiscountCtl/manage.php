<?php if (!defined('ROOT_PATH')){exit('No Permission');}?>
<?php
include $this->view->getTplPath() . '/' . 'seller_header.php';
?>
<link href="<?= $this->view->css?>/seller_center.css?ver=<?=VER?>" rel="stylesheet" type="text/css">
<link href="<?= $this->view->css_com ?>/jquery/plugins/dialog/green.css?ver=<?=VER?>" rel="stylesheet" type="text/css">
<script type="text/javascript" src="<?=$this->view->js_com?>/plugins/jquery.dialog.js" charset="utf-8"></script>

<style>
    .table-list-style td
    {
        padding: 12px 0.2%;
    }
</style>

<div class="exchange">
    <input type="hidden" name="discount_id" value="<?=$discount_id?>">
    <div class="alert">
        <strong><?=__('说明')?>：</strong>
        <ul>
            <li><?=__('1、限时折扣商品的时间段不能重叠')?></li>
            <li><?=__('2、点击添加商品按钮可以搜索并添加参加活动的商品，点击删除按钮可以删除该商品')?></li>
        </ul>
    </div>
    <table class="table-list-style mb10">
        <tbody>
        <tr>
            <td class="w80 tr"><strong><?=__('活动名称')?>：</strong></td>
            <td class="w140 tl"><?=@$data['discount_detail']['discount_name']?></td>
            <td class="w90 tr"><strong><?=__('开始时间')?>：</strong></td>
            <td class="w120 tl"><?=@$data['discount_detail']['discount_start_time']?></td>
            <td class="w90 tr"><strong><?=__('结束时间')?>：</strong></td>
            <td class="w120 tl"><?=@$data['discount_detail']['discount_end_time']?></td>
            <td class="w90 tr"><strong><?=__('购买下限')?>：</strong></td>
            <td class="w120 tl"><?=@$data['discount_detail']['discount_lower_limit']?></td>
            <td class="w90 tr"><strong><?=__('状态')?>：</strong></td>
            <td class="w70 tl"><?=@$data['discount_detail']['discount_state_label']?></td>
        </tr>
        </tbody>
    </table>

    <?php if($data['discount_detail']['discount_state'] == Discount_BaseModel::NORMAL){ ?>
    <div class="mb10 clearfix">
        <a class="button btn_search_goods fr btn_show_search_goods bbc_seller_btns"  href="javascript:void(0);"><i class="iconfont icon-jia"></i><?=__('添加商品')?></a>
    </div>
    <?php } ?>

    <div class="search-goods-list fn-clear" id="div_goods_select" style="line-height: 32px;">
        <div class="search-goods-list-hd">
            <label><?=__('第一步：搜索店内商品')?></label>
            <input id="search_goods_name" type="text w150" class="text" name="goods_name" value=""/>
            <a class="button btn_search_goods" id="btn_search_goods" href="javascript:void(0);"><i class="iconfont icon-btnsearch"></i><?=__('搜索')?></a>
        </div>
        <div  class="search-goods-list-bd fn-clear search-result" data-attr="<?=$data['discount_detail']['discount_id']?>"></div>
        <a href="javascript:void(0);" id="btn_hide_goods_select" class="close btn_hide_search_goods">X</a>
    </div>

    <table class="table-list-style" id="table_list" width="100%" cellpadding="0" cellspacing="0">
        <tr>
            <th width="50"></td>
            <th class="tl" width="250"><?=__('商品名称')?></th>
            <th width="120"><?=__('商品价格')?></th>
            <th width="120"><?=__('折扣价格')?></th>
            <th width="80"><?=__('折扣率')?></th>
            <th width="80"><?=__('操作')?></th>
        </tr>
        <tbody id="discount_goods_list">
        <?php
        if($data['discount_goods_rows'])
        {
            foreach($data['discount_goods_rows'] as $key=>$value)
            {
                ?>
                <tr class="row_line">
                    <td width="50">
                        <div class="pic-thumb">
                            <a href="<?=Yf_Registry::get('url')?>?ctl=Goods_Goods&met=goods&type=goods&gid=<?=$value['goods_id']?>&typ=e" target="_blank">
                                <img alt="" data-src="<?=image_thumb($value['goods_image'],36,36)?>" src="<?=$value['goods_image']?>" style="max-width:36px;max-height:36px;"/>
                            </a>
                        </div>
                    </td>
                    <td class="tl"><a target="_blank" href="<?=Yf_Registry::get('url')?>?ctl=Goods_Goods&met=goods&type=goods&gid=<?=$value['goods_id']?>&typ=e"><?=$value['goods_name']?></a></td>
                    <td><?=format_money($value['goods_price'])?></td>
                    <td><span optype="discount_price" data-discount-price="<?=$value['discount_price']?>"><?=format_money($value['discount_price'])?></span></td>
                    <td><span optype="discount"><?=@$value['discount_percent']?></span><?=__('折')?></td>
                    <td>
                        <span class="edit"><a href="javascript:void(0);" optype="btn_edit_discount_goods" data-discount-goods-id="<?=$value['discount_goods_id']?>" data-goods-price-format = "<?=format_money($value['goods_price'])?>" data-goods-price="<?=$value['goods_price']?>" ><i class="iconfont icon-zhifutijiao"></i><?=__('编辑')?></a></span>
                        <span class="del"><a optype="btn_del_discount_goods"  data-discount-goods-id="<?=$value['discount_goods_id']?>" data-param="{'ctl':'Seller_Promotion_Discount','met':'removeDiscountGoods','id':'<?=$value['discount_goods_id']?>'}" href="javascript:void(0)"><i class="iconfont icon-lajitong"></i><?=__('删除')?></a></span>
                    </td>
                </tr>
        <?php
            }
        }
        else{
        ?>
        <tr class="row_line no-record">
            <td colspan="99">
                <div class="no_account">
                    <img src="<?=$this->view->img?>/ico_none.png">
                    <p>暂无符合条件的数据记录</p>
                </div>
            </td>
        </tr>
        <?php  } ?>
        </tbody>
    </table>

</div>

<!--编辑限时折扣商品价格-->
<div id="dialog_edit_discount_goods" class="eject_con" style="display:none;">
    <input id="dialog_discount_goods_id" type="hidden">
    <dl>
		<dt><?=__('商品价格')?>：</dt>
		<dd><span id="dialog_edit_goods_price" data-price = 0></dd>
    </dl>
    <dl>
		<dt><?=__('折扣价格')?>：</dt>
		<dd><input id="dialog_edit_discount_price" type="text" class="text w70"><em class="rmb">￥</em></dd>
		<p id="dialog_edit_discount_goods_error" style="display:none;"><label for="dialog_edit_discount_goods_error" class="error"><i class='icon-exclamation-sign'></i><?=__('折扣价格不能为空，且必须小于商品价格')?></label></p>
    </dl>
    <div class="eject_con mb10">
        <div class="bottom"><a id="btn_edit_discount_goods_submit" class="button bbc_seller_submit_btns" href="javascript:void(0);"><?=__('提交')?></a></div>
    </div>
</div>

<!--动态添加限时折扣商品表格行元素-->
<table style="display:none;">
	<tbody id="table_list_template">
		<tr class="row_line">
			<td width="50">
				<div class="pic-thumb">
					<a href="__goodsid" target="_blank">
						<img alt="" data-src="__imageurl" style="max-width:36px;max-height:36px;"/>
					</a>
				</div>
			</td>
			<td class="tl">
				<a href="__id" target="_blank">__goodsname</a>
			</td>
			<td>__goodsprice</td>
			<td><span optype="discount_price" data-discount-price="__discountprice">__discountprice</span></td>
			<td><span optype="discount">__discount</span><?=__('折')?></td>
			<td>
				<span class="edit"><a href="javascript:void(0);" optype="btn_edit_discount_goods" data-discount-goods-id="__discountgoodsid" data-goods-price-format = "__goodspriceformat" data-goods-price="__goodsprice" ><i class="iconfont icon-zhifutijiao"></i><?=__('编辑')?></a></span>
				<span class="del"><a optype="btn_del_discount_goods"  data-discount-goods-id="__discountgoodsid" data-param="{'ctl':'Seller_Promotion_Discount','met':'removeDiscountGoods','id':'__discountgoodsid'}" href="javascript:void(0)"><i class="iconfont icon-lajitong"></i><?=__('删除')?></a></span>
			</td>
		</tr>
	</tbody>
</table>

<script type="text/javascript">
    $(document).ready(function(){
        var discount_id = "<?=$data['discount_detail']['discount_id']?>";
        $edit_item = {};

        $('.btn_show_search_goods').on('click', function() {
            $('#div_goods_select').show();
        });

        //隐藏商品搜索
        $('#btn_hide_goods_select').on('click', function() {
            $('#div_goods_select').hide();
        });

        //搜索店铺商品
        $('.btn_search_goods').on('click', function() {
            var url = SITE_URL + '?ctl=Seller_Promotion_Discount&met=getShopGoods&typ=e&discount_id=' + discount_id;
            var key = $('#search_goods_name').val();
            url = key ? url + "&goods_name=" + key : url;
            $('.search-goods-list-bd').load(url);
        });
        //店铺商品分页
        $('.search-goods-list-bd').on('click', '.page a', function() {
            $('.search-goods-list-bd').load($(this).attr('href'));
            return false;
        });

        $('.search-goods-list-bd').on('click', 'a.demo', function() {
            $('.search-goods-list-bd').load($(this).attr('href'));
            return false;
        });


        //添加限时折扣商品弹出窗口
        $('.search-goods-list-bd').on('click', '[data-type="btn_add_goods"]', function() {
            $('#dialog_goods_id').val($(this).attr('data-goods-id'));
            $('#dialog_common_id').val($(this).attr('data-common-id'));
            $('#dialog_goods_name').text($(this).attr('data-goods-name'));
            $('#dialog_goods_price').text($(this).attr('data-goods-price'));
            $('#dialog_input_goods_price').val($(this).attr('data-goods-price'));
            $('#dialog_input_goods_price_format').val($(this).attr('data-goods-price-format'));
            $('#dialog_goods_img').attr('src', $(this).attr('data-goods-img'));
            $('#dialog_goods_storage').text($(this).attr('data-storage'));

            //如果商品本身存在限购，并且限购数量低于活动最低购买下限时，则不能添加为限时折扣商品
            // var common_id = $(this).attr('common-id');
            // var discount_id = $("input[name='discount_id']").val();
            // $.ajax({
            //     url: "index.php?ctl=Seller_Promotion_Discount&met=check_limit&typ=json",
            //     data: {common_id:common_id,discount_id:discount_id},
            //     type: "POST",
            //     success:function(e){
            //         console.log(e);
            //         if(e.status != 200)
            //         {
            //            Public.tips.error('最低购买下限不能高于商品限购数量！');
            //            return false;
            //         }
            //         else
            //         {
            //             $('#dialog_add_discount_goods').yf_show_dialog({width: 640,title: '限时折扣商品规则设定'});
            //             $('#dialog_discount_price').val('');
            //             $('#dialog_add_discount_goods_error').hide();
            //         }
            //     }
            // });
            $('#dialog_add_discount_goods').yf_show_dialog({width: 640,title: '限时折扣商品规则设定'});
            $('#dialog_discount_price').val('');
            $('#dialog_add_discount_goods_error').hide();
        });

        //添加限时折扣商品
        $('.search-goods-list-bd').on('click', '#btn_submit', function() {
            var discount_goods_param = {};
            discount_goods_param.goodsname = $('#dialog_goods_name').html();
            discount_goods_param.imageurl = $('#dialog_goods_img').attr('src');
            var goods_id = discount_goods_param.goodsid = $('#dialog_goods_id').val();
            var common_id = discount_goods_param.commonid = $('#dialog_common_id').val();
            var discount_id = discount_goods_param.discountid = Number($('.search-goods-list-bd').attr('data-attr'));
            var goods_price = discount_goods_param.goodsprice = Number($('#dialog_input_goods_price').val());
            var goodspriceformat = discount_goods_param.goodspriceformat = $('#dialog_input_goods_price_format').val();

            var discount_price = discount_goods_param.discountprice = (Number($('#dialog_discount_price').val())).toFixed(2);
            discount_goods_param.discount = (discount_price/goods_price*10).toFixed(1);
            if(!isNaN(discount_price) && discount_price > 0 && discount_price < goods_price)
            {
                $('#dialog_add_discount_goods_error').hide();
                $.post(SITE_URL + '?ctl=Seller_Promotion_Discount&met=addDiscountGoods&typ=json',
                    {goods_id: goods_id,common_id:common_id,goods_price:goods_price,discount_id:discount_id,discount_price: discount_price},
                    function(d){
                        if(d &&　200 == d.status) {
                            var data = d.data;
                            $('#dialog_add_discount_goods').hide();
                            $('#list_norecord').hide();
                            Public.tips.success('操作成功!');
                            discount_goods_param.discountgoodsid = data.discount_goods_id;

                            var h = $('#table_list_template').html();
                            h = h.replace(/__(\w+)/g, function(r, $1) {
                                return discount_goods_param[$1];
                            });
                            var $h = $(h);
                            $h.find('img[data-src]').each(function() {
                                this.src = $(this).attr('data-src');
                            });

                            $('#discount_goods_list').prepend($h);
                            $('#table_list').find('.no_account').remove();

                            //当选择商品为加价购商品时，动态展示添加后的效果
                            $('.ncbtn-mini').each(function(){
                                if($(this).attr('data-goods-id') == goods_id)
                                {
                                    var html = '<div class="goods-btn"><div class="ncbtn-mini"><a onclick="add_goods_tips()" data-id="<?=$goods['goods_id']?>" common-id="<?=$goods['common_id']?>" class="button button_green had"><i class="iconfont icon-jia"></i><?=__('选择为折扣商品')?></a></div></div><i class="icon-had"></i>';
                                    $(this).parent().text('').html(html);
                                }
                            })
                            setTimeout(function(){
                                window.location.reload();
                            },3000);
                        } else {

                            Public.tips.error(d.msg);
                            $('#dialog_add_discount_goods').hide();
                        }
                    },
                    'json');
            }
            else
            {
                $('#dialog_add_discount_goods_error').show();
            }
        });

        //编辑限时活动商品,修改折扣价格
        $('#table_list').on('click', '[optype="btn_edit_discount_goods"]', function() {
            $edit_item = $(this).parents('tr.row_line');
            var discount_goods_id = $(this).attr('data-discount-goods-id');
            var discount_price = $edit_item.find('[optype="discount_price"]').attr('data-discount-price');
            var goods_price = $(this).attr('data-goods-price');
            var goods_price_format = $(this).attr('data-goods-price-format');
            $('#dialog_discount_goods_id').val(discount_goods_id);
            $('#dialog_edit_goods_price').text(goods_price_format); //格式化的价格
            $('#dialog_edit_goods_price').attr('data-price',goods_price);
            $('#dialog_edit_discount_price').val(discount_price);
            $('#dialog_edit_discount_goods').yf_show_dialog({width: 450, title: '修改价格'});
        });

        //提交修改后的价格
        $('#btn_edit_discount_goods_submit').on('click', function(){
            var discount_goods_id = $('#dialog_discount_goods_id').val();
            var discount_price = Number($('#dialog_edit_discount_price').val());
            var goods_price = Number($('#dialog_edit_goods_price').attr('data-price'));
            if(!isNaN(discount_price) && discount_price > 0 && discount_price < goods_price) {
                $.post(SITE_URL + '?ctl=Seller_Promotion_Discount&met=editDiscountGoodsPrice&typ=json',
                    {discount_goods_id: discount_goods_id, discount_price: discount_price},
                    function(d) {
                        if(d.status == 200) {
                            var data = d.data;
                            $edit_item.find('[optype="discount_price"]').text((data.discount_price).toFixed(2));
                            $edit_item.find('[optype="discount"]').text((data.discount_price/goods_price*10).toFixed(1));

                            Public.tips.success('修改成功!');
                            $('#dialog_edit_discount_goods').hide();
                        } else {
                            Public.tips.error('操作失败！');
                            $('#dialog_edit_discount_goods').hide();
                        }
                    }, 'json'
                );
            } else {
                $('#dialog_edit_discount_goods_error').show();
            }
        });
		
    });
    
    function add_goods_tips(){
        Public.tips.warning('<?=__('该商品已参加活动！')?>');
        return ;
    }
</script>

<?php
include $this->view->getTplPath() . '/' . 'seller_footer.php';
?>



