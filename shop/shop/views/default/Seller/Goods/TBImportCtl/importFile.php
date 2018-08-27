<?php if (!defined('ROOT_PATH')){exit('No Permission');}?>
<?php
include $this->view->getTplPath() . '/' . 'seller_header.php';
?>
<link href="<?=$this->view->css_com?>/webuploader.css" rel="stylesheet">
<style>
    .step {
        border: 0px;
    }

    .ncsc-form-goods {
        border: solid hsl(0, 0%, 90%);
        border-width: 1px 1px 0 1px;
    }

    .ncsc-form-goods dl {
        font-size: 0;
        line-height: 20px;
        clear: both;
        padding: 0;
        margin: 0;
        border-bottom: solid 1px hsl(0, 0%, 90%);
        overflow: hidden;
    }

    .ncsc-form-goods dl dt {
        font-size: 12px;
        line-height: 30px;
        color: hsl(0, 0%, 20%);
        vertical-align: top;
        letter-spacing: normal;
        word-spacing: normal;
        text-align: right;
        display: inline-block;
        width: 13%;
        padding: 8px 1% 8px 0;
        margin: 0;
    }

    .ncsc-form-goods dl dd {
        font-size: 12px;
        line-height: 30px;
        vertical-align: top;
        letter-spacing: normal;
        word-spacing: normal;
        display: inline-block;
        width: 84%;
        padding: 8px 0 8px 1%;
        border-left: solid 1px hsl(0, 0%, 90%);
    }

    .ncsc-form-goods dl dt i.required {
        font: 12px/16px Tahoma;
        color: hsl(12, 100%, 50%);
        vertical-align: middle;
        margin-right: 4px;
    }

    * {
        padding: 0px;
        margin: 0px;
    }

    .webuploader-pick{
        padding: 0 !important;
    }

    .js-file-name {
        font-weight: bold;
    }
</style>

<script src="<?=$this->view->js_com?>/webuploader.js"></script>
<ol class="step fn-clear add-goods-step clearfix">
    <li style="width:32%;">
        <i class="icon iconfont icon-shangjiaruzhushenqing bbc_seller_color"></i>
        <h6 class="bbc_seller_color"><?=__('STEP 1')?></h6>

        <h2 class="bbc_seller_color"><?=__('第一步')?>：<?=__('导入CSV文件')?></h2>
        <i class="arrow iconfont icon-btnrightarrow"></i>
    </li>
    <li style="width:32%;">
        <i class="icon iconfont icon-zhaoxiangji "></i>
        <h6><?=__('STEP 2')?></h6>

        <a href="<?=Yf_Registry::get('url')?>?ctl=Seller_Goods_TBImport&met=importImage&typ=e"><h2><?=__('第二步')?>：<?=__('上传商品图片')?></h2></a>
    </li>
</ol>
<div class="alert mt15 mb5"><strong><?=__('操作提示')?>：</strong>
    <ul>
        <li>1、<?=__('如果修改CSV文件请务必使用微软excel软件，且必须保证第一行表头名称含有如下项目: 宝贝名称、宝贝价格、宝贝数量、运费承担、平邮、EMS、快递、橱窗推荐、宝贝描述、新图片。')?></li>
        <li>2、<?=__('如果因为淘宝助理版本差异表头名称有出入，请先修改成上述的名称方可导入，不区分全新、二手、闲置等新旧程度，导入后商品类型都是全新。')?></li>
        <li>3、<?=__('如果CSV文件超过8M请通过excel软件编辑拆成多个文件进行导入')?>。</li>
        <li>4、<?=__('每个商品最多支持导入5张图片,暂不支持JIF格式。')?>。</li>
        <li>5、<?=__('必须保证文件编码为UTF')?>-8</li>
    </ul>
</div>

<div class="ncsc-form-goods">
    <dl>
        <dt><i class="required">*</i><?=__('CSV文件')?>：</dt>
        <dd>
            <div class="handle">
                <span class="js-file-name"></span>
                <div id="picker" style="width: 81px;height: 28px;float: left;">
                    <i class="iconfont icon-tupianshangchuan"></i><?=__('选择文件')?>
                </div>
            </div>
        </dd>
    </dl>
    <dl>
        <dt>商品分类：</dt>
        <dd id="gcategory">
            <span nctype="gc1">
                <select nctype="gc" data-deep="1">
                    <option><?=__('请选择')?>...</option>
                </select>
            </span>
            <span nctype="gc2"></span>
            <span nctype="gc3"></span>
            <span nctype="gc4"></span>
            <p><?=__('请选择商品分类')?>（<?=__('必须选到最后一级')?>）</p>
            <input type="hidden" id="gc_id" name="gc_id" value="" class="mls_id">
            <input type="hidden" id="cat_name" name="cat_name" value="" class="mls_names">
        </dd>
    </dl>

    <!--transport info begin-->

    <dl>
        <dt><?=__('所在地')?>：</dt>
        <dd>
            <p id="region">
                <select class="d_inline" name="province_id" id="province_id"></select>
            </p>
        </dd>
    </dl>
    <dl>
        <dt><?=__('本店分类')?>：</dt>
        <dd><span class="new_add"><a href="javascript:void(0)" id="add_sgcategory" class="ncbtn bbc_seller_btns"><?=__('新增分类')?></a> </span>
            <select name="sgcate_id[]" class="sgcategory">
                <option value="0"><?=__('请选择')?>...</option>
                <?php if (!empty($shop_goods_cat_rows)){ ?>
                    <?php foreach ($shop_goods_cat_rows as $shop_goods_cat_id => $shop_goods_cat_data) { ?>
                        <option data-parent_id="<?= $shop_goods_cat_data['parent_id'] ?>" value="<?= $shop_goods_cat_id ?>"><?= $shop_goods_cat_data['shop_goods_cat_name'] ?></option>
                    <?php } ?>
                <?php } else { ?>
                    <option value="-1"><?=__('暂无分类')?>...</option>
                <?php } ?>
            </select>
            <p class="hint"><?=__('商品可以从属于店铺的多个分类之下')?>，<?=__('店铺分类可以由')?> "<?=__('商家中心')?> -&gt; <?=__('店铺')?> -&gt; <?=__('店铺分类')?>" <?=__('中自定义')?></p>
        </dd>
    </dl>
    <dl>
        <dt><?=__('字符编码')?>：</dt>
        <dd>
            <p><?=__('unicode')?> </p>
        </dd>
    </dl>
    <dl>
        <dt><?=__('文件格式')?>：</dt>
        <dd>
            <p><?=__('csv文件')?></p>
        </dd>
    </dl>
    <dl>
        <dt>&nbsp;</dt>
        <dd>
            <a href="javascript:void(0)" class="js-submit ncbtn bbc_seller_btns"><?=__('导入')?></a>
        </dd>
    </dl>

</div>
<script>
    $(function() {
        // $('div.tabmenu').remove();

        var uploader;

        uploadFile();
        getCatList(0, $("span[nctype=gc1]>select"), false);
        getDistrictList(0, false);

        $("span[nctype=gc1], span[nctype=gc2], span[nctype=gc3], span[nctype=gc4]").on('change', 'select', function (){
            var $select = $(this),
                cat_id = $(this).val(),
                cat_name = $(this).children("option:checked").text();

            $("#gc_id").val(cat_id), $("#cat_name").val(cat_name);
            getCatList(cat_id, $select, true);
        });

        $('#province_id').on('change', function (){
            var province_id = $(this).val();
            getDistrictList(province_id, true);
        });

        $('#add_sgcategory').on('click', function () {
            $(".sgcategory:last").after($(".sgcategory:last").clone(true).val(0));
        });

        $('select.sgcategory').on('change', function (){

            var checked_sg_category_id = this.value, flag = true; this.value = 0;

            $("select[name='sgcate_id[]']").each(function() {
                var this_sg_category_id = this.value;
                if (checked_sg_category_id != 0 && checked_sg_category_id == this_sg_category_id )
                    Public.tips.warning("<?=__('该分类已经选择,请选择其他分类')?>"), flag = false;
            });

            if (flag) this.value = checked_sg_category_id;
        });

        $("a.js-submit").on('click', function(){
            //check goods cat
            var goods_category_id = $("#gc_id").val();
            if(!goods_category_id) return Public.tips.warning("<?=__('请选择商品分类')?>！");
            uploader.upload();
        });

        //商品分类
        function getCatList(cat_id, $select, change) {
            var deep = $select.data('deep');
            switch (deep) {
                case 1 :
                    $("span[nctype=gc2], span[nctype=gc3], span[nctype=gc4]").empty();
                    break;
                case 2 :
                    $("span[nctype=gc3], span[nctype=gc4]").empty();
                    break;
                case 3 :
                    $("span[nctype=gc4]").empty();
                    break;
            }
            $.get(SITE_URL + '?ctl=Seller_Goods_Cat&met=cat&typ=json', {cat_id: cat_id}, function(data){
                if (data.status == 200) {
                    //没有数据
                    if(data.data.length === 0){
                        return false;
                    }
                    var option_rows = [], cat_data = data.data;
                    if (change) {
                        option_rows.push("<option value='0'>请选择</option>");
                    }
                    for (var i = 0; i < cat_data.length; i++) {
                        option_rows.push("<option value=" + cat_data[i].cat_id + ">" + cat_data[i].cat_name + "</option>");
                    }
                    if (change){
                        //first create select
                        if (option_rows.length > 0) {
                            //default first goods_cat
                            $("#gc_id").val(cat_data[0].cat_id), $("#cat_name").val(cat_data[0].cat_name);
                            var next_deep = deep + 1;
                            $("span[nctype=gc" + next_deep + "]").append("<select data-deep=" + next_deep + ">" + option_rows.join("") + "</select>");
                        }
                    } else {
                        $select.append(option_rows.join(""));
                    }

                } else {
                    Public.tips.error(data.msg);
                }
            })
        }

        //所在地
        function getDistrictList(province_id, change) {
            change && $("#city_id").remove();
            if ($.inArray(province_id, [1, 2, 9, 22]) != -1) return;
            $.get(SITE_URL + '?ctl=Base_District&met=district&pid=0&typ=json', {pid: province_id}, function (data) {
                if (data.status == 200) {
                    var district_data = data.data.items, district_row = [];
                    for ( var i=0; i<district_data.length; i++ ) {
                        district_row.push("<option value=" + district_data[i].district_id + ">" + district_data[i].district_name + "</option>");
                    }
                    district_row = district_row.join("");
                    if (change) {
                        $('#province_id').after("<select id='city_id'>" + district_row + "</select>");
                    } else {
                        $('#province_id').append(district_row);
                    }
                } else {
                    Public.tips.error(data.msg)
                }
            });
        }

        //文件上传
        function uploadFile() {

            uploader = WebUploader.create({

                pick: "#picker",

                accept: {
                    title: 'TaoBaoImport',
                    extensions: 'csv,xls,xlsx'
                },

                swf: BASE_URL + '/shop/static/common/js/Uploader.swf',

                server: SITE_URL + "?ctl=Upload&action=uploadGoodsExcel&typ=json",

                fileVal: 'upfile',

            });

            // 文件上传成功，给item添加成功class, 用样式标记上传成功。
            uploader.on( 'uploadSuccess', function( file,res ) {
                var data = res.data;
                if ( data.state == "SUCCESS" ) {
                    submit(res.data.url_path), Public.tips.success("<?=__('上传成功')?>！");
                } else {
                    Public.tips.error("<?=__('上传失败')?>！")
                }

            });

            // 文件上传失败，现实上传出错。
            uploader.on( 'uploadError', function( file, reason ) {
                Public.tips.error("<?=__('上传失败')?>！")
            });

            uploader.on( 'beforeFileQueued', function( file ) {
                if ($.inArray(file.ext, ["csv", "xls", "xlsx"]) == -1)
                    return Public.tips.warning("<?=__('请上传csv、xls、xlsx格式的文件')?>");

                var queued_files = this.getFiles("inited");
                if (  queued_files.length > 0 ) this.removeFile(queued_files[0].id);
            });

            uploader.on( 'fileQueued', function( file ) {
                $("span.js-file-name").empty().text(file.name);
            });
        }

        function submit(file_path) {
            var param = {
                file_path: file_path,
                store_goods_category: [],
                goods_category_id: $("#gc_id").val(),
                goods_category_name: $("#cat_name").val(),
                province_id: $("#province_id").val(),
                city_id: $("#city_id").val() ? $("#city_id").val() : 0
            };

            $.each($("select[name='sgcate_id[]']"), function() {
                param.store_goods_category.push(this.value);
            });

            $.post(SITE_URL + "?ctl=Seller_Goods_TBImport&met=addGoods&typ=json", param, function( data ) {
                if ( data.status == 200 ) {
                    $.dialog({
                        title: "导入结果",
                        content: data.msg,
                        width: "500px",
                        ok: true
                    });
                } else {
                    Public.tips.error(data.msg);
                }
            })
        }
    })
</script>
<?php
include $this->view->getTplPath() . '/' . 'seller_footer.php';
?>