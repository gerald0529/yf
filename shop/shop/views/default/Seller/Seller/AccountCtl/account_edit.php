<?php if (!defined('ROOT_PATH')){exit('No Permission');}?>
<?php
include $this->view->getTplPath() . '/' . 'seller_header.php';
?>

<div class="tabmenu">
    <ul>
        <li><a href="<?=Yf_Registry::get('url')?>?ctl=Seller_Seller_Account&met=accountList&typ=e"><?=__('账号列表')?></a></li>
        <li class="active bbc_seller_bg"><a href="javascript:void(0);"><?=__('编辑账号')?></a></li>
    </ul>
</div>

<div class="form-style">
    <form method="post" id="form">
        <input type="hidden" name="seller_id" id="id" value="<?=$data['seller_info']['seller_id']?>" />
        <dl>
            <dt><i>*</i><?=__('前台用户名')?>：</dt>
            <dd><?=$data['seller_info']['seller_name']?></dd>
        </dl>
        <dl class="dl">
            <dt><i>*</i><?=__('账号组：')?></dt>
            <dd >
                <select name="group_id">
                    <?php if($data['seller_group_list']) {
                        foreach ($data['seller_group_list'] as $key => $value){ ?>
                            <option value="<?=$value['group_id']?>" <?=$value['group_id'] == $data['seller_info']['seller_group_id']?'selected="selected"':''?>><?=$value['group_name']?></option>
                        <?php }} ?>
                </select>
            </dd>
        </dl>
        <dl>
            <dt></dt>
            <dd>
                <input type="submit" class="button button_blue bbc_seller_submit_btns" value="提交"  />
                <input type="hidden" name="act" value="save" />
            </dd>
        </dl>
    </form>
</div>

<script type="text/javascript">
    function delayer(){
        window.location = "index.php?ctl=Seller_Seller_Account&met=accountList&typ=e";
    }

    $(document).ready(function(){
        $('#form').validator({
            ignore: ':hidden',
            theme: 'yellow_right',
            timely: 1,
            stopOnError: false,
            rules: {
            },

            fields: {
                'group_id': 'integer[+];'
            },
            valid: function(form){
                var me = this;
                // 提交表单之前，hold住表单，并且在以后每次hold住时执行回调
                me.holdSubmit(function(){
                    Public.tips.error('正在处理中...');
                });
                $.ajax({
                    url: "index.php?ctl=Seller_Seller_Account&met=editAccountSave&typ=json",
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
                            Public.tips.error(e.data.msg);
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

