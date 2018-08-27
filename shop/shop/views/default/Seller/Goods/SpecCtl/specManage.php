<html>
<head>
<link href="<?= $this->view->css ?>/seller.css?ver=<?=VER?>" rel="stylesheet">
    <link href="<?= $this->view->css ?>/seller_center.css?ver=<?= VER ?>" rel="stylesheet">
    <link href="<?= $this->view->css ?>/base.css?ver=<?= VER ?>" rel="stylesheet">
    <link href="<?= $this->view->css ?>/iconfont/iconfont.css?ver=<?= VER ?>" rel="stylesheet" type="text/css">
<style>
    .text {
        height: 34px;
    }

    .table-list-style .button {
        margin-left: 4px;
    }
    
</style>

<script type="text/javascript">
    var SITE_URL = "<?=Yf_Registry::get('url')?>";
</script>
<script type="text/javascript" src="<?= $this->view->js_com ?>/jquery.js" charset="utf-8"></script>

<script type="text/javascript" src="<?= $this->view->js_com ?>/plugins/jquery.dialog.js"></script>
</head>

<div class="spec-list-style">
    <form id="form" method="post">
        <input type="hidden" name="cat_id" value="<?= $cat_id; ?>" />
        <table class="table-list-style" width="100%">
            <tbody><tr>
                <th width="80"><?=__('排序')?></th>
                <th class="tl"><?=__('规格值名称')?></th>
                <th width="110"><a id="add-sv" class="button button_blue bbc_seller_btns"><?=__('添加规格值')?></a></th>
            </tr>
            <?php if( !empty($data) && is_array($data) ) { ?>
            <?php foreach ($data as $key => $val) { ?>
            <tr>
                <td><input type="text" value="<?php echo $val['spec_value_displayorder']; ?>" class="text w60" name="old[<?php echo $val['spec_value_id']; ?>][displayorder]"></td>
                <td class="tl"><input value="<?php echo $val['spec_value_name']; ?>" maxlength="20" type="text" class="text w250" name="old[<?php echo $val['spec_value_id']; ?>][spec_value_name]"></td>
                <td>
            <span class="delete">
            	<a data-id="<?php echo $val['spec_value_id']; ?>"><i class="iconfont icon-lajitong"></i><?=__('删除')?></a>
            </span>
                </td>
            </tr>
            <?php } ?>
            <?php } ?>
            <tr>
                <td colspan="3" class="foot">
                    <input type="button" id="submit-button" class="button button_red bbc_seller_btns" value="<?=__('保存规格值')?>">
                </td>
            </tr>
            </tbody></table>
    </form>
</div>

<script>

    $(function() {

        var newid = 0, param_data = {};

        eval('param_data = ' + $(parent.window.document).find('li.selected').children('a').data('param'));

        $('#form').prop('action', SITE_URL + "?ctl=Seller_Goods_Spec&met=saveSpecValue&typ=json&spec_id=" + param_data.spec_id);

        $('#submit-button').on('click', function (e) {

            var submitFlag = true;

            $('input[name$="[spec_value_name]"').each(function (index, element) {

                if( element.value == '' ) {

                    submitFlag = false;

                    if ( !$(element).hasClass('error') ) {
                        $(element).addClass('error').after('<label class="error"><i class="iconfont icon-exclamation-sign"></i><?=__("请填写内容")?></label>');
                    }
                    return false;
                }
            });

            if (submitFlag) {

                $.post(SITE_URL + "?ctl=Seller_Goods_Spec&met=saveSpecValue&typ=json&spec_id=" + param_data.spec_id, $('#form').serialize(), function(data){

                    if(data.status == 200) {
                        parent.Public.tips({ type: 3, content: '<?=__("修改成功")?>!'}), window.location.reload();
                    } else {
                        parent.Public.tips({ type: 1, content: '<?=__("修改失败")?>!'})
                    }
                })
            }
        });

        $('.delete').click(function(){

            var _this = $(this);
            $.dialog.confirm('<?=__("删除的数据将不能恢复，请确认是否删除")?>?',function(){
                spec_value_id = _this.children('a').data('id');
                if (typeof spec_value_id != 'undefined') {
                    $.post( SITE_URL + "?ctl=Seller_Goods_Spec&met=removeSpecValue&typ=json", { spec_value_id: spec_value_id}, function(data) {

                        if( data.status == 200) {
                            _this.parent().parent().remove();
                            parent.Public.tips({ type: 3, content: '<?=__("删除成功")?>!'})
                        } else {
                            parent.Public.tips({ type: 1, content: '<?=__("删除失败")?>!'})
                        }
                    })
                } else {
                    _this.parent().parent().remove();
                }
            });
        });

//        $('.delete').on('click', function () {
//
//            if ( confirm('删除的数据将不能恢复，请确认是否删除') ) {
//
//                var _this = this;
//                spec_value_id = $(this).children('a').data('id');
//
//                if (typeof spec_value_id != 'undefined') {
//                    $.post( SITE_URL + "?ctl=Seller_Goods_Spec&met=removeSpecValue&typ=json", { spec_value_id: spec_value_id}, function(data) {
//
//                        if( data.status == 200) {
//                            $(_this).parent().parent().remove();
//                            parent.Public.tips({ type: 3, content: '删除成功!'})
//                        } else {
//                            parent.Public.tips({ type: 1, content: '删除失败!'})
//                        }
//                    })
//                } else {
//                    $(_this).parent().parent().remove();
//                }
//            }
//
//        })

        $('#add-sv').on('click', function () {

            var $addSpec = $(
                '<tr>' +
                '<td><input type="text" value="0" class="text w60" name="new[' + newid + '][displayorder]"></td>' +
                '<td class="tl"><input value="" maxlength="20" type="text" class="text w250" name="new[' + newid + '][spec_value_name]"></td>' +
                '<td><span class="delete"><a data-newId="' + newid + '"><i class="iconfont icon-lajitong"></i><?=__("删除")?></a></span></td>' +
                '</tr>');

            $addSpec.find('span.delete').on('click', function () {
                var _this = $(this);
                $.dialog.confirm('<?=__("删除的数据将不能恢复，请确认是否删除")?>?',function(){
                    _this.parent().parent().remove();
                });
            });

            $('#submit-button').parent().parent().before($addSpec);

            newid++;
        });
    });
</script>