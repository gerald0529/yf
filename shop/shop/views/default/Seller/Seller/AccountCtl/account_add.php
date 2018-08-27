<?php if (!defined('ROOT_PATH')){exit('No Permission');}?>
<?php
include $this->view->getTplPath() . '/' . 'seller_header.php';
?>

<div class="tabmenu">
    <ul>
        <li><a href="<?=Yf_Registry::get('url')?>?ctl=Seller_Seller_Account&met=accountList&typ=e"><?=__('账号列表')?></a></li>
        <li  class="active bbc_seller_bg"><a href="javascript:void(0);"><?=__('添加账号')?></a></li>
    </ul>
</div>


<div class="form-style">
    <form method="post" id="form">
        <dl>
            <dt><i>*</i><?=__('前台用户名')?>：</dt>
            <dd>
                <input type="text" name="seller_name" class="text w120"/>
                <span class="colred"> <?=__('用户名必须为已注册的普通会员')?></span>
            </dd>
        </dl>
        <dl>
            <dt><i>*</i><?=__('用户密码')?>：</dt>
            <dd>
                <input type="password" name="password" class="text w120"/>
            </dd>
        </dl>
        <dl class="dl">
            <dt><i>*</i><?=__('账号组：')?></dt>
            <dd >
                <select name="group_id">
                    <?php if($data) {
                        foreach ($data as $key => $value){ ?>
                            <option value="<?=$value['group_id']?>"><?=$value['group_name']?></option>
                        <?php }} ?>
                </select>
            </dd>
        </dl>
        <dl>
            <dt></dt>
            <dd>
                <input type="submit" class="button button_blue bbc_seller_submit_btns" value="提交"  />
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
                'seller_name': 'required;',
                'password': 'required;',
                'group_id': 'integer[+];'
            },
            valid: function(form){
                var me = this;
                // 提交表单之前，hold住表单，并且在以后每次hold住时执行回调
                me.holdSubmit(function(){
                    Public.tips.error('正在处理中...');
                });
                $.ajax({
                    url: "index.php?ctl=Seller_Seller_Account&met=saveAccount&typ=json",
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

