<?php if (!defined('ROOT_PATH')) exit('No Permission'); ?>

<?php
    include $this->view->getTplPath() . '/' . 'seller_header.php';
?>
    <style type="text/css">
        .bd-line {
            height: 40px;
        }
        
        .submit_div {
            margin: 30px 0 0 300px;
        }
        
        /*运费模板选择地区弹出层*/
        .ks-ext-mask {
            filter: progid:DXImageTransform.Microsoft.gradient(enabled='true', startColorstr='#BFFFFFFF', endColorstr='#BFFFFFFF');
            background: rgba(255, 255, 255, 0.75);
        }
        
        .dialog-areas, .dialog-batch {
            background-color: #FFF;
            width: 680px;
            height: 400px;
            margin: 0 auto;
            position: relative;
            z-index: 9999;
        }
        
        .dialog-batch {
            top: 40%;
        }
        
        .ks-contentbox {
            display: block;
        }
        
        .ks-contentbox .title {
            font-size: 14px;
            line-height: 20px;
            font-weight: bold;
            color: #555;
            background-color: #FFF;
            height: 20px;
            padding: 10px;
            border-bottom: solid 1px #E6E6E6;
            position: relative;
            z-index: 1;
        }
        
        a.ks-ext-close {
            font: lighter 14px/20px Verdana;
            color: #999;
            text-align: center;
            display: block;
            width: 20px;
            height: 20px;
            position: absolute;
            z-index: 1;
            top: 10px;
            right: 10px;
            cursor: pointer;
        }
        
        a:hover.ks-ext-close {
            text-decoration: none;
            color: #27A9E3;
        }
        
        .dialog-areas ul {
            display: block;
            padding: 10px;
        }
        
        .dialog-areas li {
            display: block;
            width: 100%;
            clear: none;
        }
        
        .dialog-areas li.even {
            background-color: #F7F7F7;
        }
        
        .district-region {
            font-size: 0;
            *word-spacing: -1px /*IE6、7*/;
            overflow: visible !important;
        }
        
        .district-region-title {
            font-size: 12px;
            line-height: normal !important;
            vertical-align: top;
            letter-spacing: normal;
            word-spacing: normal;
            text-align: left !important;
            display: inline-block;
            padding: 0 !important;
            width: 100px !important;
        }
        
        .district-region-title span {
            line-height: 20px;
            color: #333;
            font-weight: bold;
            display: block;
            height: 20px;
            padding: 5px 0 4px 10px;
        }
        
        .district-province-list {
            font-size: 0 !important;
            *word-spacing: -1px /*IE6、7*/;
            vertical-align: top;
            letter-spacing: normal;
            word-spacing: normal;
            display: inline-block;
            width: 550px !important;
            padding: 0 !important;
        }
        
        .district-province {
            font-size: 12px;
            vertical-align: top;
            letter-spacing: normal;
            word-spacing: normal;
            display: inline-block;
            width: 105px;
            height: 30px;
            position: relative;
            z-index: 1;
        }
        
        .district-province-tab {
            line-height: 20px;
            display: block;
            height: 20px;
            padding: 4px;
            margin: 1px 1px 0 1px;
            width: 120px;
        }
        
        .district-province-tab input, .district-province-tab label {
            vertical-align: middle;
        }
        
        .district-province-tab .check_num {
            font: 12px/16px Verdana, Geneva, sans-serif;
            color: red;
            letter-spacing: -1px;
            vertical-align: middle;
            padding-right: 1px;
        }
        
        .district-province-tab i {
            font-size: 12px;
            color: #CCC;
            margin-left: 4px;
            cursor: pointer;
        }
        
        .district-province-tab:hover i {
            color: #555;
        }
        
        .showCityPop {
            z-index: 2;
        }
        
        .showCityPop .district-province-tab {
            background-color: #FFFEC6;
            margin: 0;
            border-style: solid;
            border-width: 1px 1px 0 1px;
            border-color: #F7E4A5 #F7E4A5 transparent #F7E4A5;
        }
        
        .district-citys-sub {
            background-color: #FFFEC6;
            white-space: normal;
            display: none;
            border: 1px solid #F7E4A5;
            position: absolute;
            z-index: -1;
            top: 28px;
            width: 600px;
            left: 0;
        }
        
        .district-citys-sub-1 {
            left: -110px;
        }
        
        .district-citys-sub-2 {
            left: -180px;
        }
        
        .district-citys-sub-3 {
            left: -200px;
        }
        
        .district-citys-sub-4 {
            left: -350px;
        }
        
        .district-citys-sub-5 {
            left: -450px;
        }
        
        .showCityPop .district-citys-sub {
            font-size: 0;
            *word-spacing: -1px /*IE6、7*/;
            display: block;
        }
        
        .district-citys-sub .areas {
            font-size: 12px;
            line-height: 20px;
            vertical-align: middle;
            letter-spacing: normal;
            word-spacing: normal;
            display: inline-block;
            padding: 4px;
            margin-right: 4px;
        }
        
        .ks-contentbox .bottom {
            padding: 10px;
        }
        
        .ks-contentbox .batch {
            line-height: 30px;
            background-color: #FFF;
            text-align: center;
            height: 30px;
            padding: 20px 0;
            border-bottom: solid 1px #E6E6E6;
        }
        
        .checkbox {
            padding: 0;
            vertical-align: middle;
        }
        
        table th {
            text-align: left;
        }
    </style>
    <body>

<div class="freight">
    <div class="tabmenu">
        <ul>
            <li><a href="<?= Yf_Registry::get('url') ?>?ctl=Seller_Transport&met=transport&typ=e"><?= __('运费模板设置') ?></a></li>
            <?php if (isset($data['template'])) { ?>
                <li class="active bbc_seller_bg"><a href="javascript:void(0);"><?= __('编辑运费模板') ?></a></li>
            <?php } else { ?>
                <li class="active bbc_seller_bg"><a href="javascript:void(0);"><?= __('添加运费模板') ?></a></li>
            <?php } ?>
        </ul>
    
    </div>
    
    <form id="transport_form">
        <div class="form-style">
            <input type="hidden" name="template_id" value="<?php echo isset($data['template']['id']) ? $data['template']['id'] : ''; ?>" />
            <dl class="dl">
                <dt style="width:8%"><i><?= __('*') ?></i><?= __('模板名称：') ?></dt>
                <dd style="width:25%"><input type="text" class="text w200" name="template_name" id="template_name" value="<?php echo isset($data['template']['name']) ? $data['template']['name'] : ''; ?>" /></dd>
            </dl>
            <dl class="dl">
                <dt style="width:8%"><i><?= __('*') ?></i><?= __('模板状态：') ?></dt>
                <dd style="width:25%">
                    <label><input type="radio" class="radio" name="template_status" value="1" <?php if (isset($data['template']['status']) && $data['template']['status'] == 1){ ?>checked="checked"<?php } ?> /><?= __('开启') ?></label>
                    <label><input type="radio" class="radio" name="template_status" value="0" <?php if (!isset($data['template']['status']) || $data['template']['status'] == 0){ ?>checked="checked"<?php } ?> /><?= __('关闭') ?></label>
                
                </dd>
            </dl>
            <dl class="dl">
                <dt style="width:8%"><i><?= __('*') ?></i><?= __('计费规则：') ?></dt>
                <dd style="width:25%">
                    <label><input type="radio" class="radio" name="rule_type" value="1" checked="checked" /><?= __('按重量') ?></label>
                
                </dd>
            </dl>
            <dl>
                <dt style="width:8%"><i><?= __('*') ?></i><?= __('详细设置') ?>：</dt>
                <dd class="trans-line">
                    <div class="ncsu-trans-type" data-delivery="kd">
                        <div class="entity">
                            <div class="tbl-except">
                                <table cellspacing="0" class="district-table-style" style="width:100%" cellpadding="0" border="0">
                                    <thead>
                                    <tr style='border-bottom: 1px solid #ddd'>
                                        <th style='padding-left:5px'><?= __('运送地区') ?></th>
                                        <th class="w110"><?= __('首重') ?>(KG)</th>
                                        <th class="w110"><?= __('首费') ?></th>
                                        <th class="w110"><?= __('续重') ?>(KG)</th>
                                        <th class="w110"><?= __('续费') ?></th>
                                        <th class="w110"><?= __('操作') ?></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php if ($data['rule']) {
                                        $num = 0;
                                        foreach ($data['rule'] as $key => $value) {
                                            $num++; ?>
                                            <tr class="bd-line" data-group="n<?= $num ?>">
                                                <td><span class="area-group"><p style="display:inline-block"><?= $value['area_name'] ?></p></span><input type="hidden" value="<?= $value['area_ids'] ?>|||<?= $value['area_name'] ?>" name="areas[kd][<?= $num ?>]"></td>
                                                <td><input class="w50 text" type="text" maxlength="4" autocomplete="off" value="<?= $value['default_num'] ?>" data-field="default_num" name="transport[kd][<?= $num ?>][default_num]"></td>
                                                <td><input class="w50 text" type="text" maxlength="6" autocomplete="off" value="<?= $value['default_price'] ?>" data-field="default_price" name="transport[kd][<?= $num ?>][default_price]"><em class="add-on"><?= __('元') ?></em></td>
                                                <td><input class="w50 text" type="text" maxlength="4" autocomplete="off" value="<?= $value['add_num'] ?>" data-field="add_num" name="transport[kd][<?= $num ?>][add_num]"></td>
                                                <td><input class="w50 text" type="text" maxlength="6" autocomplete="off" value="<?= $value['add_price'] ?>" data-field="add_price" name="transport[kd][<?= $num ?>][add_price]"><em class="add-on"><?= __('元') ?></em></td>
                                                <td>
                                                    <span><a class="t_deleteRule" ncNum="n<?= $num ?>"><i class="iconfont icon-lajitong"></i><?= __('删除') ?></a></span>
                                                    <span><a data-group="n<?= $num ?>" title="<?= __('编辑运送区域') ?>" area-haspopup="true" entype='t_editArea' data-acc="event:enter"><i class="iconfont icon-zhifutijiao"></i><?= __('编辑') ?></a></span>
                                                </td>
                                            </tr>
                                        
                                        <?php }
                                    } ?>
                                    </tbody>
                                </table>
                            </div>
                            <div class="tbl-attach">
                                <a class="t_addRule ncbtn-mini button bbc_seller_btns" href="JavaScript:void(0);">
                                    <i class="icon-map-marker iconfont icon-jia bbc_seller_btns"></i><?= __('添加规则') ?></a>
                            </div>
                        </div>
                    </div>
                </dd>
            </dl>
            <dl class="dl">
                <dt style="width:8%"></dt>
                <dt style="width:24%"><i><?= __('*') ?></i><?= __('注：没有设置规则的地区，默认免运费。') ?></dt>
            </dl>
        </div>
        <div class="submit_div">
            <a class="button bbc_seller_submit_btns" onclick="transport_submit()"><?= __('确认提交') ?></a>
        </div>
    
    </form>
</div>
<div class="ks-ext-mask" style="position: fixed; left: 0px; top: 0px; width: 100%; height: 100%; z-index: 999; display: block; display:none"></div>
<div id="dialog_areas" class="dialog-areas" style="display:none">
    <div class="ks-contentbox">
        <div class="title"><?= __('选择地区') ?><a class="ks-ext-close" href="javascript:void(0)">X</a></div>
        <form id="area_form">
            <ul id="J_CityList">
                <?php $num = 0;
                    foreach ($data['district'] as $key => $value) {
                        $num++; ?>
                        <!--大区-->
                        <li>
                            <dl class="district-region">
                                <dt class="district-region-title">
              <span>
              <input type="checkbox" id="region_group_<?= __($num) ?>" class="region_group" value="" />
              <label for="region_group_<?= __($num) ?>"><?= __($key) ?></label>
              </span>
                                </dt>
                                <dd class="district-province-list">
                                    <!--省-->
                                    <?php $n = 0;
                                        foreach ($value as $k => $val) {
                                            $n++; ?>
                                            
                                            <div class="district-province"><span class="district-province-tab">
                <input type="checkbox" class="J_Province" id="J_Province_<?= $val['district_id'] ?>" value="<?= $val['district_id'] ?>" />
                <label for="J_Province_<?= $val['district_id'] ?>"><?= __($val['district_name']) ?></label>
                <span class="check_num" /> </span><i class="iconfont icon-iconjiantouxia trigger"></i>
                                                <div class="district-citys-sub district-citys-sub-<?= $n ?>">
                                                    <!--市-->
                                                    <?php foreach ($val['city'] as $v) { ?>
                                                        <span class="areas">
                    <input type="checkbox" class="J_City" id="J_City_<?= $v['district_id'] ?>" value="<?= __($v['district_id']) ?>" />
                    <label for="J_City_<?= $v['district_id'] ?>"><?= __($v['district_name']) ?></label>
                    </span>
                                                    <?php } ?>
                                                    <p class="tr hr8"><label class="areas_icon_close"></label></p>
                                                </div>
                                                </span>
                                            </div>
                                        <?php } ?>
                                </dd>
                            </dl>
                        </li>
                    <?php } ?>
            </ul>
            
            <div class="submit_div"><a class="button bbc_seller_submit_btns" id="area_submit"><?= __('确定') ?></a></div>
        </form>
    
    </div>
</div>


<script type="text/javascript">
    //定义运费模板主体模板、头模板、单行显示模板
    RuleCell = '';
    //单行内容模板
    RuleCell += "<tr class=\"bd-line\" data-group=\"nCurNum\"><td><span class=\"area-group\"><p style=\"display:inline-block\"><?=__('未添加地区')?><\/p><\/span><input type=\"hidden\" value=\"\" name=\"areas[kd][CurNum]\"><\/td>\n";
    RuleCell += "<td><input class=\"w50 text\" type=\"text\" maxlength=\"4\" autocomplete=\"off\" value=\"1\" data-field=\"default_num\" name=\"transport[kd][CurNum][default_num]\"><\/td>\n";
    RuleCell += "<td><input class=\"w50 text\" type=\"text\" maxlength=\"6\" autocomplete=\"off\" value=\"\" data-field=\"default_price\" name=\"transport[kd][CurNum][default_price]\"><em class=\"add-on\"><?=__('元')?><\/em><\/td>\n";
    RuleCell += "<td><input class=\"w50 text\" type=\"text\" maxlength=\"4\" autocomplete=\"off\" value=\"1\" data-field=\"add_num\" name=\"transport[kd][CurNum][add_num]\"><\/td>\n";
    RuleCell += "<td><input class=\"w50 text\" type=\"text\" maxlength=\"6\" autocomplete=\"off\" value=\"\" data-field=\"add_price\" name=\"transport[kd][CurNum][add_price]\"><em class=\"add-on\"><?=__('元')?><\/em><\/td>\n";
    RuleCell += "<td><span><a class=\"btn-grapefruit t_deleteRule\" ncNum=\"nCurNum\" href=\"JavaScript:void(0);\"><i class=\"iconfont icon-lajitong\"><\/i><?=__('删除')?><\/a><\/span>\n";
    RuleCell += "<span><a data-group=\"nCurNum\" title=\"<?=__('编辑运送区域')?>\" area-haspopup=\"true\" entype=\'t_editArea\' data-acc=\"event:enter\" href=\"JavaScript:void(0);\">\n";
    RuleCell += "<i class=\"iconfont icon-zhifutijiao\"><\/i><?=__('编辑')?><\/a><\/span><\/td>";
    RuleCell += "<\/tr>\n";
    
    
    function transport_submit() {
        var form_data = $('#transport_form').serialize();
        var url = "<?=Yf_Registry::get('url')?>?ctl=Seller_Transport&met=transportSubmit&typ=json";
        $.post(url, form_data, function (resp) {
            if (resp.status == 200) {
                window.location.href = "<?=Yf_Registry::get('url')?>?ctl=Seller_Transport&met=transport&typ=e";
            } else {
                Public.tips({type: 1, content: resp.msg});
            }
        }, 'json');
    }
    
    /*删除一行运费规则*/
    $('.trans-line').on('click', '.t_deleteRule', function () {
        curDelNum = $(this).attr('ncNum');
        $.dialog.confirm('<?=__('确认删除吗')?>?', function () {
            curTransType = 'kd';
            obj_parent = $('tr[data-group="' + curDelNum + '"]').parent();
            $('tr[data-group="' + curDelNum + '"]').remove();
            if ($(obj_parent).find('tr').html() == null) {
                $(obj_parent).parent().parent().parent().find('.batch').css('display', 'none');
                $(obj_parent).parent().parent().parent().find('.J_ToggleBatch').css('display', 'none');
                $(obj_parent).parent().parent().parent().find('.batch').next().find('span').css('display', 'none');
            } else {
                //如果该配送方式，地区都不为空，隐藏地区的提示层
                isRemove = true;
                $('div[data-delivery="' + curTransType + '"]').find('input[type="hidden"]').each(function () {
                    if ($(this).val() == '') {
                        isRemove = false;
                        return false;
                    }
                });
                if (isRemove == true) {
                    $('div[data-delivery="' + curTransType + '"]').find('span[error_type="area"]').css('display', 'none');
                }
            }
        });
    });
    
    
    /*	选择完区域后，确定事件*/
    $('#dialog_areas').on('click', '#area_submit', function () {
        var CityText = '', CityText2 = '', CityValue = '';
        //记录已选择的所有省及市的value，SelectArea下标为value值，值为true，如江苏省SelectArea[320000]=true,南京市SelectArea[320100]=true
        //取得已选的省市的text，返回给父级窗口，如果省份下的市被全选择，只返回显示省的名称，否则显示已选择的市的名称
        //首先找市被全部选择的省份
        $('#J_CityList').find('.district-province-tab').each(function () {
            var a = $(this).find('input[type="checkbox"]').size();
            var b = $(this).find('input:checked').size();
            //市被全选的情况
            if (a == b) {
                CityText += ($(this).find('.J_Province').next().html()) + ',';
            } else {
                //市被部分选中的情况
                $(this).find('.J_City').each(function () {
                    //计算并准备传输选择的区域值（具体到市级ID），以，隔开
                    if ($(this).attr('checked')) {
                        CityText2 += ($(this).next().html()) + ',';
                    }
                });
            }
        });
        CityText += CityText2;
        
        //记录弹出层内所有已被选择的checkbox的值(省、市均记录)，记录到CityValue，SelectArea中
        $('#J_CityList').find('.district-province-list').find('input[type="checkbox"]').each(function () {
            if ($(this).attr('checked')) {
                CityValue += $(this).val() + ',';
            }
        });
        
        //去掉尾部的逗号
        CityText = CityText.replace(/(,*$)/g, '');
        CityValue = CityValue.replace(/(,*$)/g, '');
        
        //返回选择的文本内容
        if (CityText == '') CityText = '<?=__('未添加地区')?>';
        if (CityText.length > 20) {
            CityText = CityText.substr(0, 10) + '...';
        }
        $(objCurlArea).find('.area-group>p').html(CityText);
        //返回选择的值到隐藏域
        $('input[name="areas[' + curTransType + '][' + curIndex.substring(1) + ']"]').val(CityValue + '|||' + CityText);
        //关闭弹出层与遮罩层
        $("#dialog_areas").css('display', 'none');
        $('.ks-ext-mask').css('display', 'none');
        //清空check_num显示的数量
        $(".check_num").html('');
        $('#J_CityList').find('input[type="checkbox"]').attr('checked', false);
        //如果该配送方式，地区都不为空，隐藏地区的提示层
        isRemove = true;
        $('div[data-delivery="' + curTransType + '"]').find('input[type="hidden"]').each(function () {
            if ($(this).val() == '') {
                isRemove = false;
                return false;
            }
        });
        if (isRemove == true) {
            $('div[data-delivery="' + curTransType + '"]').find('span[error_type="area"]').css('display', 'none');
        }
    });

</script>
<script type="text/javascript" src="<?= $this->view->js ?>/transport.js"></script>
<?php
    include $this->view->getTplPath() . '/' . 'seller_footer.php';
?>