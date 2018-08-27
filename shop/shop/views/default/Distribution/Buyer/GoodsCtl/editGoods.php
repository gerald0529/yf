<?php if (!defined('ROOT_PATH')){exit('No Permission');}

include $this->view->getTplPath() . '/' . 'buyer_header.php';
?>
<script type="text/javascript" src="<?= $this->view->js_com ?>/plugins/jquery.dialog.js"></script>
<script src="<?=$this->view->js_com?>/webuploader.js"></script>
<script src="<?=$this->view->js_com?>/upload/upload_image.js"></script>
<style>
.goods .form-style-left {float: left;}
.goods .form-style-right {
    float: right;
    width: 200px;
    background-color: #fcf8e3;
    border: 1px solid #fbeed5;
    padding: 10px 30px 20px 20px;
}

.goods .form-style-right * {color: #c09853;}
.goods .form-style-right h4 {
    font-size: 14px;
    font-weight: bold;
    line-height: 1.5em;
    margin: 10px 0 8px;
}
.goods .form-style-right li {
    margin: 5px 0;
    line-height: 20px;
}
.goods .form-style-left {float: left;}
.goods .form-style-left .image-button {margin: 30px auto 40px;text-align: center;}
.image-list-table {
    margin-bottom:25px;
    border:1px solid #e1e1e1;
    border-collapse:collapse
}
input[type=file] {opacity: 0;}
.image-list-table th {
    padding: 10px;
    text-align: left;
    background: #F5F5F5;
    border-bottom: 1px solid #e1e1e1;
}

.image-list-table td {
    width: 152px;
    height: 160px;
    text-align: center;
    position: relative;
    border-right: 1px solid #e1e1e1;
}

.image-list-table td .fore1 {
    position: absolute;
    top: 20px;
    left: 23px;
    border: 1px solid #EEE;
    height: 100px;
    width: 100px;
}
.image-list-table td .fore3 {
    border: 1px solid #E6E6E6;
    width: 100px;
    height: 28px;
    line-height: 28px;
    position: absolute;
    z-index: 1px;
    left:23px;
    top: 122px;
}
.image-list-table td .fore3 a {
    width: 100px;
	display:inline-block;
    height: 28px;
    background:#e1e1e1;
	margin:0px;
	color:#333;
}
.image-list-table td .fore2 {
    position: absolute;
    top: 20px;
    left:23px;
    border: 1px solid #EEE;
    height: 30px;
    width: 100px;
    padding: 70px 0 0;
    cursor: pointer;
}

.image-list-table td .fore2:hover {
    border-color: #27a9e3;
}

.image-list-table td .fore2:hover p {
    display: block;
}

.image-list-table td .fore2:hover a {
    display: block;
}
.image-list-table td .fore2.sele:hover a {
    display: block;
}
.image-list-table td .fore2 a {
    font-family: Tahoma, Geneva, sans-serif;
    font-size: 9px;
    line-height: 14px;
    text-align: center;
    display: none;
    width: 14px;
    height: 14px;
    border-style: solid;
    border-width: 1px;
    border-radius: 8px;
    position: absolute;
    z-index: 3;
    top: -8px;
    right: -8px;
    color: #27a9e3;
}
</style>
    <div class="aright">
		<div class="member_infor_content">
			<div class="div_head tabmenu clearfix">
				<ul class="tab clearfix">
					<li><a href="<?= Yf_Registry::get('url') ?>?ctl=Distribution_Buyer_Directseller&met=index&typ=e"><?=__('分销申请')?></a></li>
					<li class="active"><a href="<?= Yf_Registry::get('url') ?>?ctl=Distribution_Buyer_Goods&met=index"><?=__('商品列表')?></a></li>
					<li><a href="<?= Yf_Registry::get('url') ?>?ctl=Distribution_Buyer_Directseller&met=directsellerList"><?=__('推广用户')?></a></li>
					<li><a href="<?= Yf_Registry::get('url') ?>?ctl=Distribution_Buyer_Directseller&met=directsellerOrder"><?=__('推广订单')?></a></li>
					<li><a href="<?= Yf_Registry::get('url') ?>?ctl=Distribution_Buyer_Directseller&met=directsellerCommission"><?=__('佣金记录')?></a></li>
				</ul>
			</div>
	
			<div class="goods">
			<div class="form-style">
				<div class="form-style-left">
					<form method="post" id="form" action="<?php echo Yf_Registry::get('url'). '?ctl=Distribution_Buyer_Goods&met=editGoods&op=save&typ=json'?>">
						<input type="hidden" name="shop_id" value="<?=$data['shop_id']?>" />
						<input type="hidden" name="common_id" value="<?=$data['common_id']?>" />
						<table class="image-list-table" cellpadding="5" cellspacing="1">
							<tbody>
								<tr><th colspan="5"><?=__('上传图片')?></th></tr>
								<tr>
									<td>
										<div class="fore1">
											<img id="image_0_0_img" src="">
											<input id="image_0_0" type="hidden" name="image[]" value="">
										</div>
										<div class="fore2">                                     
                                            <a href="javascript:void(0)" nctype="del" class="del" title="<?=__('移除')?>">X</a>
                                        </div>
										<div class="fore3 up-label" id="image_0_0_button">
											<a href="javascript:void(0)"><?=__('上传')?></a>
										</div>
									</td>
									
									<td>
										<div class="fore1">
											<img width="100" id="image_0_1_img" src="">
											<input id="image_0_1" type="hidden" name="image[]" value="">
										</div>
										<div class="fore2">
											<a href="javascript:void(0)" nctype="del" class="del" title="<?=__('移除')?>">X</a>
										</div>
										<div class="fore3 up-label" id="image_0_1_button"><a href="javascript:void(0)"><?=__('上传')?></a>
										</div>                    
									</td>
									<td>
										<div class="fore1">
											<img width="100" id="image_0_2_img" src="">
											<input id="image_0_2" type="hidden" name="image[]" value="">
										</div>
										<div class="fore2">
											<a href="javascript:void(0)" nctype="del" class="del" title="<?=__('移除')?>">X</a>
										</div>
										<div class="fore3 up-label" id="image_0_2_button"><a href="javascript:void(0)"><?=__('上传')?></a>
										</div>
									</td>
									<td>
										<div class="fore1">
											<img width="100" id="image_0_3_img" src="">
											<input id="image_0_3" type="hidden" name="image[]" value="">
										</div>
										<div class="fore2">
											<a href="javascript:void(0)" nctype="del" class="del" title="<?=__('移除')?>">X</a>
										</div>
										<div class="fore3 up-label" id="image_0_3_button"><a href="javascript:void(0)"><?=__('上传')?></a>
										</div>
									</td>
									<td>
										<div class="fore1">
											<img width="100" id="image_0_4_img" src="">
											<input id="image_0_4" type="hidden" name="image[]" value="">
										</div>
										<div class="fore2">
											<a href="javascript:void(0)" nctype="del" class="del" title="<?=__('移除')?>">X</a>
										</div>
										<div class="fore3 up-label" id="image_0_4_button"><a href="javascript:void(0)"><?=__('上传')?></a>
										</div>
									</td>
								</tr>
							</tbody>
						</table>
						<div class="image-button">
							<input type="submit" class="button button_black bbc_seller_submit_btns" value="<?=__('提交')?>">
						</div>
					</form>
				</div>
				<div class="form-style-right">
					<h4><?=__('上传要求：')?></h4>
					<ul>
						<li><?=__('1. 请使用jpg\jpeg\png等格式、单张大小不超过1M的正方形图片。')?></li>
						<li><?=__('2. 上传图片最大尺寸将被保留为1280像素。')?></li>
						<li><?=__('3. 每种颜色最多可上传5张图片。')?></li>
						<li><?=__('4. 通过更改排序数字修改商品图片的排列显示顺序。')?></li>
						<li><?=__('5. 图片质量要清晰，不能虚化，要保证亮度充足。')?></li>
						<li><?=__('6. 操作完成后请点下一步，否则无法在网站生效。')?></li>
					</ul>
					<h4><?=__('建议:')?></h4>
					<ul>
						<li><?=__('1. 主图为白色背景正面图。')?></li>
						<li><?=__('2. 排序依次为正面图-&gt;背面图-&gt;侧面图-&gt;细节图。')?></li>
					</ul>
				</div>
			</div>
			</div>
		</div>
    </div>
 <!-- 尾部 -->
<script>
    $(function () {
        //图片上传
        for ( var i = 0; i < 5; i++ ) {
            new UploadImage({
                thumbnailWidth: 102,
                thumbnailHeight: 102,
                imageContainer: '#image_0_' + i + '_img',
                uploadButton: '#image_0_' + i + '_button',
                inputHidden: '#image_0_' + i
            });
        }     
    });

    /* if ( window.location.href.indexOf('action') > -1 ) 
	{
        //编辑商品  编辑图片
        var common_id = <?= $data['common_id']; ?>;
        $li_img = $('.tabmenu').find('.active').removeClass('active bbc_seller_bg').children('a').prop('href', window.location.href.replace('edit_image', 'edit_goods')).html('编辑商品').parent('li').clone();
        $li_img.addClass('active bbc_seller_bg').children('a').html('编辑图片').prop('href', window.location.href);
        $('.tabmenu').find('ul').append($li_img);
        $('ol.step.fn-clear').remove();
    } */

    $('#form').find('img').attr('width', '100px').attr('height', '100px');
    
    <?php if ( !empty($data['goods_images']) ) { ?>
    <?php foreach ($data['goods_images'] as $k => $v){ ?>
    $('#image_0_<?= $k; ?>_img').attr('src', '<?= $v; ?>');
    $('#image_0_<?= $k; ?>').attr('value', '<?= $v; ?>');
    <?php } ?>
    <?php } ?>

    var def_img = BASE_URL + '/shop/static/common/images/image.png';
    $('a[nctype="del"]').on('click', function() {
        var $div = $($(this).parents('div').prev()[0]),
            $img = $div.find('img'),
            $input = $div.find('input');

        if ( $img.attr('src') != def_img ) {
            $img.attr('src', def_img);
            $input.attr('value', '');
        }
    });
</script>
 <?php
include $this->view->getTplPath() . '/' . 'buyer_footer.php';
?>