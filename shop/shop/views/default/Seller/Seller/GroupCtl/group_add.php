<?php if (!defined('ROOT_PATH')){exit('No Permission');}?>
<?php
include $this->view->getTplPath() . '/' . 'seller_header.php';
?>
<style>

    .ncsc-account-container:nth-child(even) {
        background: #FAFAFA;
    }
    .ncsc-account-container {
        line-height: 20px;
        display: block;
        min-height: 20px;
        padding: 15px 0 10px 0;
        border-top: dotted 1px #CCC;
    }
    .ncsc-account-container h4 {
        font-size: 12px;
        font-weight: 600;
        vertical-align: top;
        display: inline-block;
        width: 14%;
        border-right: dotted 1px #CCC;
        margin: 0 1%;
    }
    .ncsc-account-all .checkbox, .ncsc-account-container .checkbox {
        vertical-align: middle;
        margin-right: 4px;
    }
    .ncsc-account-container-list {
        font-size: 0;
        vertical-align: top;
        display: inline-block;
        width: 83%;
    }
    .ncsc-account-container-list li {
        font-size: 12px;
        line-height: 20px;
        vertical-align: middle;
        letter-spacing: normal;
        word-spacing: normal;
        display: inline-block;
        width: 20%;
        height: 20px;
        margin-bottom: 5px;
    }
</style>


<div class="tabmenu">
    <ul>
        <li><a href="<?=Yf_Registry::get('url')?>?ctl=Seller_Seller_Group&met=groupList&typ=e"><?=__('组列表')?></a></li>
        <li  class="active bbc_seller_bg"><a href="javascript:void(0);"><?=__('添加账号')?></a></li>
    </ul>
</div>


<div class="form-style">
    <form id="form"  method="post">
        <?php if(!empty($data['group_info'])) { ?>
            <input name="group_id" type="hidden" value="<?php echo $data['group_info']['group_id'];?>" />
        <?php } ?>
        <dl>
            <dt><i class="required">*</i>组名称：</dt>
            <dd>
                <input class="w120 text" name="seller_group_name" type="text" id="seller_group_name" value="<?php if(!empty($data['group_info'])) {echo $data['group_info']['group_name'];};?>" />
                <span></span>
                <p class="hint"><?=__('设定权限组名称，方便区分权限类型。')?></p>
            </dd>
        </dl>
        <dl id="function_list">
            <dt><i class="required">*</i><?=__('权限')?>：</dt>
            <dd>
                <div class="ncsc-account-all">
                    <input id="btn_select_all" name="btn_select_all" class="checkbox" type="checkbox" />
                    <label for="btn_select_all"><?=__('全选')?></label>
                    <span></span>
                    <?php if(!empty(Seller_Controller::$menu) && is_array(Seller_Controller::$menu)) {?>
                    <?php foreach(Seller_Controller::$menu as $key => $value) {?>
                </div>
                <div class="ncsc-account-container">
                    <h4>
                        <input id="<?php echo $key;?>" class="checkbox" nctype="btn_select_module" type="checkbox" />
                        <label for="<?php echo $key;?>"><?php echo $value['name'];?></label>
                    </h4>
                    <?php $submenu = $value['sub'];?>
                    <?php if(!empty($submenu) && is_array($submenu)) {?>
                        <ul class="ncsc-account-container-list">
                            <?php foreach($submenu as $submenu_value) {?>
                                <li>
                                    <input id="<?php echo $submenu_value['ctl'];?>" class="checkbox" name="limits[]" value="<?php echo $submenu_value['ctl'];?>" <?php if(!empty($data['group_limits'])) {if(in_array($submenu_value['ctl'], $data['group_limits'])) { echo 'checked'; }}?> type="checkbox" />
                                    <label for="<?php echo $submenu_value['ctl'];?>"><?php echo $submenu_value['name'];?></label>
                                </li>
                            <?php } ?>
                        </ul>
                    <?php } ?>
                    <?php } ?>
                </div>
                <?php } ?>
                <p class="hint"></p>
            </dd>
        </dl>
        <dl>
            <dt></dt>
            <dd>
                <input type="submit" class="button button_blue bbc_seller_submit_btns" value="提交">
            </dd>
        </dl>
    </form>
</div>


<script type="text/javascript">
    function delayer(){
        window.location = "index.php?ctl=Seller_Seller_Group&met=groupList&typ=e";
    }

    $(document).ready(function(){
        $('#btn_select_all').on('click', function() {
            if($(this).prop('checked')) {
                $(this).parents('dd').find('input:checkbox').prop('checked', true);
            } else {
                $(this).parents('dd').find('input:checkbox').prop('checked', false);
            }
        });
        $('[nctype="btn_select_module"]').on('click', function() {
            if($(this).prop('checked')) {
                $(this).parents('.ncsc-account-container').find('input:checkbox').prop('checked', true);
            } else {
                $(this).parents('.ncsc-account-container').find('input:checkbox').prop('checked', false);
            }
        });

        $('#form').validator({
            ignore: ':hidden',
            theme: 'yellow_right',
            timely: 1,
            stopOnError: false,
            rules: {
                function_check: function(){
                    var count = $('#function_list').find('input:checkbox:checked').length;
                    return count > 0;
                }
            },

            fields: {
                'seller_group_name': 'required;length[~50];',
                'btn_select_all':'function_check;',
            },
            valid: function(form){
                var me = this;
                // 提交表单之前，hold住表单，并且在以后每次hold住时执行回调
                me.holdSubmit(function(){
                    Public.tips.error('正在处理中...');
                });
                $.ajax({
                    url: "index.php?ctl=Seller_Seller_Group&met=saveGroup&typ=json",
                    data: $(form).serialize(),
                    type: "POST",
                    success:function(e){
                        if(e.status == 200)
                        {
                            var data = e.data;
                            Public.tips.success('操作成功!');
                            setTimeout('delayer()', 2000);
                        }
                        else
                        {
                            Public.tips.error(e.msg);
                        }
                        me.holdSubmit(false);
                    }
                });
            }
        });
    });
</script>


<?php
include $this->view->getTplPath() . '/' . 'seller_footer.php';
?>

