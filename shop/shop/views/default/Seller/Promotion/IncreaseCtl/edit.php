<?php if (!defined('ROOT_PATH')){exit('No Permission');}?>
<?php
include $this->view->getTplPath() . '/' . 'seller_header.php';
?>

<!--添加活动商品模板-->
<table style="display:none;">
    <tbody id="goods-sku-item-tpl">
        <tr data-goods-id="__id">
            <td width="50">
                <input type="hidden" name="join_act_goods_id[]" value="__id" />
                <input type="hidden" name="join_act_common_id[]" value="__common" />
                <div>
                    <div class="pic-thumb">
                        <img alt="" data-src="__image" style="max-width:36px;max-height:36px;border:solid 1px #ccc;"/>
                    </div>
                </div>
            </td>
            <td class="tl"><a href="<?=Yf_Registry::get('url')?>?ctl=Goods_Goods&met=goods&type=goods&gid=__id" target="_blank"> __name </a></td>
            <td class="goods-price" width="90"> __price </td>
            <td width="50">
                <span class="handel">
                    <a href="javascript:void(0);" class="remove-join-goods" data-good-id="__id">
                        <i class="iconfont icon-quxiaodingdan"></i><p><?=__('移除')?></p>
                    </a>
                </span>
            </td>
        </tr>
    </tbody>
</table>


<div class="form-style">
    <form id="form" action="<?=Yf_Registry::get('url')?>?ctl=Seller_Promotion_Increase&met=add&typ=e" method="post" onSubmit="return checkPrice();">
	    <input name="increase_id" value="<?=$data['increase_id']?>" type="hidden">
	    <dl>
	        <dt><i class="required">*</i><?=__('活动名称')?>：</dt>
	        <dd>
		        <input id="increase_name" name="increase_name" class="text w450" value="<?=$data['increase_name']?>" type="text">
		        <span></span>
		        <p class="hint"><?=__('活动名称将显示在加价购活动列表中，方便商家管理使用')?>。</p>
	        </dd>
	    </dl>
        <dl>
            <dt><?=__('开始时间')?>：</dt>
            <dd><?=$data['increase_start_time']?></dd>
        </dl>
        <dl>
            <dt><?=__('结束时间')?>：</dt>
            <dd><?=$data['increase_end_time']?></dd>
        </dl>

        <!--参加活动的商品--start-->
        <dl>
            <dt><?=__('活动商品')?>：</dt>
            <dd>
                <p> <span></span> </p>
                <table class="table-list-style mb15">
                    <thead>
                        <tr>
                            <th class="tl pl-50" colspan="2"><?=__('商品名称')?></th>
                            <th width="90"><?=__('商品价格')?></th>
                            <th width="90"><?=__('操作')?></th>
                        </tr>
                    </thead>
                    <tbody class="join-act-goods-sku">
                    <?php
                    if(@$data['goods'])
                    {
                        foreach($data['goods'] as $key=>$goods)
                        {
                            ?>
                            <tr data-goods-id="<?=@$goods['goods_id']?>">
                                <td width="50">
                                    <input type="hidden" name="join_act_goods_id[]" value="<?=@$goods['goods_id']?>" />
                                    <input type="hidden" name="join_act_common_id[]" value="<?=@$goods['common_id']?>" />
                                    <div>
                                        <div class="pic-thumb">
                                            <img alt="" src="<?=@image_thumb($goods['goods_image'],36,36)?>" data-src="<?=@$goods['goods_image']?>" style="max-width:36px;max-height:36px;border:solid 1px #ccc;"/>
                                        </div>
                                    </div>
                                </td>
                                <td class="tl"><a href="<?=Yf_Registry::get('url')?>?ctl=Goods_Goods&met=goods&type=goods&gid=<?=@$goods['goods_id']?>" target="_blank"> <?=@$goods['goods_name']?> </a></td>
                                <td class="goods-price" width="90"> <?=@format_money($goods['goods_price'])?> </td>
                                <td width="50">
                            <span class="handel">
                                <a href="javascript:void(0);" class="remove-join-goods" data-good-id="<?=@$goods['goods_id']?>">
                                    <i class="iconfont icon-quxiaodingdan"></i><p><?=__('移除')?></p>
                                </a>
                            </span>
                                </td>
                            </tr>
                        <?php
                        }
                    }
                    ?>
                    </tbody>
                </table>

                <div class="mm"><a href="javascript:void(0)" data-increase-id='<?=@$data['increase_id']?>' class="button btn-ctl-select-goods bbc_seller_btns"><?=__('选择活动商品')?></a></div>

                <div class="div-shop-goods-box search-goods-list">
                    <div id="cou-sku-options"></div>
                    <a class="btn_hide_search_goods close" href="javascript:void(0);" style="display:none;">&#215;</a>
                </div>
                <p class="hint">
                    <?=__('同一商品SKU不能参加多个加价购活动；同一个加价购活动可以选择多个商品SKU参与')?>。<br/>
                    <?=__('同一订单中，参与同一活动的SKU共同累加的金额用于判断是否满足换购资格；同一订单中可以使用多组加价购活动')?>。
                </p>
            </dd>
        </dl>
        <!--参加活动的商品--end-->

        <!--活动规则与规则下的换购商品-->

        <style>

            .bbc-cou-rule {
                background-color: #FFF;
                padding: 9px;
                border: dashed 1px #E6E6E6;
                position: relative;
                z-index: 1;
            }
            .table-list-style th {
                /* color: #999; */
                 background-color: #FFF;
                text-align: center;
                height: 20px;
                padding: 10px 15px;
                border-bottom: solid 1px #E7E7E7;
                font-weight: normal;
            }
        </style>
        <dl>
            <dt><?=__('活动规则')?>：</dt>
            <dd>
                <div class="rule-container">
                    <?php
                    if(@$data['rule'])
                    {
                        foreach($data['rule'] as $key=>$rule)
                        {
                    ?>
                    <div data-cou-level-item="<?=$key+1?>" class="inc-rule bbc-cou-rule mb10">
                        <div class="rule-note mb10">
                            <h5 class="add-rule"><a href="javascript:javascript:void(0);"  class="mini-btn bbc_seller_btns bg-red remove-rule" data-rule-level-remove="<?=$key+1?>"><i class="iconfont icon-lajitong"></i><?=__('删除此规则')?></a></h5>
                            <span><?=__('购买同一加价购活动商品消费满')?>
                                <?=Web_ConfigModel::value('monetary_unit')?><input type="text" class="text w50 rule-price-limit" name="rule_levle[<?=$key+1?>][mincost]" value="<?=@$rule['rule_price']?>" data-rule-price="<?=@$rule['rule_price']?>" />
                                <?=__('，即可换购最多')?>
                                <input type="text" class="text w40" name="rule_levle[<?=$key+1?>][maxrebuy]" value="<?=@$rule['rule_goods_limit']?>" data-rule-limit="<?=@$rule['rule_goods_limit']?>"/>
                                <?=__('件（0为不限）优惠商品，换购商品如下')?>：<a btn-choose-rule-ex-goods="<?=$key+1?>" href="javascript:;" class="button bbc_seller_btns"><i class="iconfont icon-jia"></i><?=__('添加换购商品')?></a>
                            </span>
                        </div>
                        <div data-cou-level-item="<?=$key+1?>">
                            <div class="div-shop-goods-box">
                                <div rule-shop-goods-container="<?=$key+1?>"></div>
                                <a data-cou-level-sku-close-button="<?=$key+1?>" class="close goods-sku goods-sku-close" href="javascript:;" style="display:none;right:-10px;">&#215;</a>
                            </div>
                        </div>
                        <!--规则下换购商品-->
                        <table class="table-list-style mt10">
                            <thead>
                            <tr>
                                <th colspan="2" class="tl pl-50"><?=__('换购商品')?></th>
                                <th width="100"><?=__('原价')?></th>
                                <th width="100"><?=__('换购价')?></th>
                                <th class="handle" width="70"><?=__('操作')?></th>
                            </tr>
                            </thead>
                            <tbody class="bd-line" id="cou-level-sku-container-<?=$key+1?>">
                                <?php
                                    if(@$rule['redemption_goods'])
                                    {
                                        foreach($rule['redemption_goods'] as $kk=>$redpemp_goods)
                                        {
                                ?>
                                <tr data-cou-level-selected-sku="<?=@$redpemp_goods['goods_id']?>" data-level="<?=$key+1?>">
                                    <td width="50">
                                        <div>
                                            <div class="pic-thumb">
                                                <img alt="" data-src="<?=@$redpemp_goods['goods_image']?>" src="<?=@image_thumb($redpemp_goods['goods_image'],36,36)?>" style="max-width:36px;max-height:36px;"/>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="tl"><a href="<?=Yf_Registry::get('url')?>?ctl=Goods_Goods&met=goods&type=goods&gid=<?=@$redpemp_goods['goods_id']?>" target="_blank"> <?=@$redpemp_goods['goods_name']?></a></td>
                                    <td width="100" nctype="bundling_data_price"><s> <?=@format_money($redpemp_goods['goods_price'])?> </s></td>
                                    <td width="100"><input type="text" class="text w50 cost-limit" name="rule_levle[<?=$key+1?>][skus][<?=@$redpemp_goods['goods_id']?>]" value="<?=@$redpemp_goods['redemp_price']?>" data-max-price="<?=@$redpemp_goods['goods_price']?>" /></td>
                                    <td width="70">
                                    <span>
                                        <a href="javascript:void(0);" class="" remove-goods-sku="<?=@$redpemp_goods['goods_id']?>"> <i class="iconfont icon-quxiaodingdan"></i><p><?=__('移除')?></p></a>
                                    </span>
                                    </td>
                                </tr>
                                <?php
                                        }
                                    }
                                ?>
                            </tbody>
                        </table>
                    </div>
                    <?php
                        }
                    }
                    ?>
                </div>

                <div class="mm"> <a href="javascript:void(0);"  class="button bbc_seller_btns mt10 btn-add-level"> <i class="iconfont icon-jia"></i> <?=__('添加规则')?> </a></div>
                <p class="hint"><?=__('1.换购购满金额不能重复')?>。</p>
                <p class="hint"><?=__('2.最多换购数量为0时，则换购商品数不限')?>。</p>
            </dd>
        </dl>
        <dl>
            <dt></dt>
            <dd>
                <input type="submit" class="button button_blue bbc_seller_submit_btns" value="提交">
                <input type="hidden" name="act" value="save">
            </dd>
        </dl>

    </form>
</div>

<!--添加规则模板-->
<div id="add-rule-temp" style="display:none;">
    <div data-cou-level-item="__level" class="inc-rule bbc-cou-rule mb10">
        <div class="rule-note mb10">
            <h5 class="add-rule"><a href="javascript:;"  class="mini-btn bbc_seller_btns bg-red remove-rule" data-rule-level-remove="__level"><i class="iconfont icon-lajitong"></i><?=__('删除此规则')?></a></h5>
            <span><?=__('购买同一加价购活动商品消费满')?>
                <?=Web_ConfigModel::value('monetary_unit')?><input type="text" class="text w50 rule-price-limit" name="rule_levle[__level][mincost]" value="" data-rule-price="0.00"/>
                <?=__('，即可换购最多')?>
                <input type="text" class="text w40" name="rule_levle[__level][maxrebuy]" value="0" />
                <?=__('件（0为不限）优惠商品，换购商品如下')?>：<a btn-choose-rule-ex-goods="__level" href="javascript:;" class="button bbc_seller_btns"><i class="iconfont icon-jia"></i><?=__('添加换购商品')?></a>
            </span>
        </div>
        <div data-cou-level-item="__level">
            <div class="div-shop-goods-box">
                <div rule-shop-goods-container="__level"></div>
                <a data-cou-level-sku-close-button="__level" class="close goods-sku goods-sku-close" href="javascript:;" style="display:none;right:-10px;">&#215;</a>
            </div>
        </div>
        <table class="table-list-style mt10">
            <thead>
                <tr>
                    <th colspan="2" class="pl-50 tl"><?=__('换购商品')?></th>
                    <th width="100"> <?=__('原价')?></th>
                    <th width="100"> <?=__('换购价')?></th>
                    <th class="handle" width="70"><?=__('操作')?></th>
                </tr>
            </thead>
            <tbody class="bd-line" id="cou-level-sku-container-__level">

            </tbody>
        </table>
    </div>
</div>


<!-- 设置换购商品模板 -->
<table style="display:none;">
    <tbody id="goods-sku-temp">
        <tr data-cou-level-selected-sku="__id" data-level="__level">
            <td width="50">
                <div>
                    <div class="pic-thumb">
                        <img alt="" data-src="__image" style="max-width:36px;max-height:36px;"/>
                    </div>
                </div>
            </td>
            <td class="tl"><a href="<?=Yf_Registry::get('url')?>?ctl=Goods_Goods&met=goods&type=goods&gid=__id" target="_blank"> __name </a></td>
            <td width="100" nctype="bundling_data_price"><s> __price </s></td>
            <td width="100"><input type="text" class="text w50" name="rule_levle[__level][skus][__id]" value="__goodsprice" data-max-price="__goodsprice" /></td>
            <td width="70">
                <span>
                    <a href="javascript:void(0);" class="" remove-goods-sku="__id"> <i class="iconfont icon-quxiaodingdan"></i><p><?=__('移除')?></p></a>
                </span>
            </td>
        </tr>
    </tbody>
</table>

<script>

$(function(){

	$('.btn-ctl-select-goods').click(function(){
		$(this).hide();
        $('.btn_hide_search_goods').show();		//关闭按钮显示
        $('.search-goods-list').show();
		
		var increase_id = $(this).attr('data-increase-id');
		var url = SITE_URL + '?ctl=Seller_Promotion_Increase&met=getShopGoods&typ=e&op=edit';
        $('#cou-sku-options').load(url,{id:increase_id});
	});

    //分页
    $('#cou-sku-options').on('click', '.page a', function() {
        $('#cou-sku-options').load($(this).attr('href'));
        return false;
    });
	
    $('.btn_hide_search_goods').click(function() {
        $(this).hide();
        $('#cou-sku-options').html('');
        $('.btn-ctl-select-goods').show();
    });

    /*移除活动商品
    * 更改商品可参加活动状态
    * */
    $('body').on('click','.remove-join-goods',function(){
        $(this).parents('tr').remove();
        var id = $(this).attr('data-good-id');
        $("div[btn-disabled='"+id+"']").hide();//按钮隐藏
        //$("div[btn-enabled='"+id+"']").show();//按钮显示
        $("div[btn-enabled='"+id+"']").css({display:"inline-block"});//按钮显示
        $("div[btn-enabled='"+id+"']").addClass('button');//按钮显示
    });

    var nextId = (function(){
        var i = 10000;
        return function() {
            return ++i;
        };
    })();

    // 添加活动规则
    $('.btn-add-level').click(function() {
        var id = nextId();
        var h = $('#add-rule-temp').html();
        h = h.replace(/__level/g, id);

        var $h = $(h);
        $('.rule-container').append($h);
       // $('.rule-container').append(h);
    });

    // 删除活动规则
    $('body').on('click','a.remove-rule', function() {
        var id = $(this).attr('data-rule-level-remove');
        $("[data-cou-level-item='"+id+"']").remove();
    });

    //载入店铺商品列表，选择加入换购
    /*
    * id 对应的规则等级
    * url 异步请求服务器地址
    * */
    var getShopGoodsSku = function(id, url) {
        $("[rule-shop-goods-container='"+id+"']").load(
            url || SITE_URL +'?ctl=Seller_Promotion_Increase&met=getShopGoodsSku&typ=e&level='+id,
            function() {
                $("[data-cou-level-selected-sku]").each(function() {
                    var sku = $(this).attr('data-cou-level-selected-sku');
                    switchSkuButton(sku, 0);
                });
            }
        );
    };

    // 选择换购商品按钮
    $('body').on('click','[btn-choose-rule-ex-goods]', function() {
        var id = $(this).attr('btn-choose-rule-ex-goods');
        $("[btn-choose-rule-ex-goods='"+id+"']").hide();
        $("[data-cou-level-sku-close-button='"+id+"']").show();
        getShopGoodsSku(id);
    });

    //换购商品分页
    $('body').on('click','.page a', function() {
        var id = $(this).parents('[rule-shop-goods-container]').attr('rule-shop-goods-container');
        var url = this.href;
        //var url = $(this).attr('href');
       getShopGoodsSku(id, url);
        return false;
    });

	//搜索换购商品
    $('body').on('click','.btn-sku-search-goods', function() {
        var id = $(this).parents('[rule-shop-goods-container]').attr('rule-shop-goods-container');
        var url = this.href;
        //url += '&stc_id=' + $('#rule_levle_sku_stc_id_'+id).val();
        url += '&goods_name=' + encodeURIComponent($('#rule_sku_key_'+id).val());
        getShopGoodsSku(id, url);
        return false;
    });

    // 关闭选择换购商品选择框
    $('body').on('click','[data-cou-level-sku-close-button]', function() {
        var id = $(this).attr('data-cou-level-sku-close-button');
        $(this).hide();
        $("[btn-choose-rule-ex-goods='"+id+"']").show();
        $("[rule-shop-goods-container='"+id+"']").html('');
    });

    //切换设置换购商品按钮
    var switchSkuButton = function(sku, b) {
        if (b)
        {
            $("div[btn-sku-enabled='"+sku+"']").show();
            $("div[btn-sku-disabled='"+sku+"']").hide();
        }
        else
        {
            $("div[btn-sku-enabled='"+sku+"']").hide();
            $("div[btn-sku-disabled='"+sku+"']").show();
        }
    };

    window.couLevelSkuInSearch = {};

    // 设置为换购商品
    $('body').on('click','[data-type="btn_add_sku_goods"]', function() {
        var sku =$(this).attr('data-id');
        var id = $(this).attr('data-level');

        var h = $('#goods-sku-temp').html();
        h = h.replace(/__level/g, id);
        h = h.replace(/__(\w+)/g, function($m, $1){
            return window.couLevelSkuInSearch[sku][$1];
        });
        var $h = $(h);

        $h.find('img[data-src]').each(function() {
            this.src = $(this).attr('data-src');
        });
        
        $('#cou-level-sku-container-'+id).append($h);
        switchSkuButton(sku, 0);
    });

    // 移除已选换购商品按钮
    $("body").on('click','[remove-goods-sku]', function() {
        var sku = $(this).attr('remove-goods-sku');
        $("[data-cou-level-selected-sku='"+sku+"']").remove();
        switchSkuButton(sku, 1);
    });

  // 换购商品换购价不能高于原价
  $('body').on('keyup','input[data-max-price]', function() {
        var p = parseFloat(this.value) || 0;
        var mp = parseFloat($(this).attr('data-max-price')) || 0;
        if (p > mp) {
            Public.tips.error('换购商品换购价不能高于原价，请重新填写！');
            this.value = '';
            this.focus();
            return false;
        }
        if(p < 0)
        {
            Public.tips.error('换购商品价格不能为负数，请重新填写！');
            this.value = '';
            this.focus();
            return false;
        }
    });


    //规则中满足活动规则金额的限制

    $('body').on('blur','.rule-price-limit', function() {
        var p = parseFloat(this.value)|| 0 ;
        if(p <= 0)
        {
            Public.tips.error('规则金额设置有误，请重新填写！');
            this.value = '';
            this.focus();
            return false;
        }
        var cost_limit = [];
        $('#form').find('.rule-price-limit').each(function(){
            cost_limit.push(parseFloat(Number($(this).val()).toFixed(2)));
        });

        if(cost_limit.length != $.unique(cost_limit).length){
            Public.tips.error('规则中购满金额有重复，请重新填写！！');
            this.value = '';
            this.focus();
            return false;
        }else{
            return true;
        }
    });

    $('#form').validator({
        debug:true,
        ignore: ':hidden',
        theme: 'yellow_right',
        timely: false,
        stopOnError: false,
        rules:{
            norepeat:function(element,param)
            {
                var cost_limit = [];
                $('#form').find('.cost-limit').each(function(){
                    cost_limit.push($(this).val());
                });
                if(cost_limit.length==$.unique(cost_limit).length){
                    return false;
                }else{
                    return true;
                }
            }
            },
        messages: {
            required: "请填写{0}",
            norepeat:"规则价格不能重复"
        },
        fields: {
            'increase_name': 'required;length[4~30];'

        },
        valid: function(form){
            var me = this;
            // 提交表单之前，hold住表单，并且在以后每次hold住时执行回调
            me.holdSubmit(function(){
                Public.tips.error('正在处理中...');
            });
            $.ajax({
                url: SITE_URL  + "?ctl=Seller_Promotion_Increase&met=editIncrease&typ=json",
                data: $(form).serialize(),
                async:'false',
                type: "POST",
                success:function(e){
                    if(e.status == 200)
                    {
                        Public.tips.success('操作成功!');
                        setTimeout(window.location.href='index.php?ctl=Seller_Promotion_Increase&met=index&typ=e',5000);
                    }
                    else
                    {
                        Public.tips.error('操作失败！');
                    }
                    me.holdSubmit(false);
                }
            });
        }

    });

});
    function checkPrice()
    {
        $('#form').find('.rule-price-limit').each(function(){
            var cost_limit = [];
            if(parseFloat(Number($(this).val()).toFixed(2)) > 0.00 && !isNaN($(this).val()))
            {
               ;
            }
            else
           {
               Public.tips.error('规则金额设置有误，请重新填写！');
               this.value = '';
               this.focus();
               return false;
           }
        });
    }
</script>

<?php
include $this->view->getTplPath() . '/' . 'seller_footer.php';
?>

