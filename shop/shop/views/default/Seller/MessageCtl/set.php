<link rel="shortcut icon" href="favicon.ico" type="image/x-icon"/>
<link href="<?= $this->view->css ?>/seller.css?ver=<?= VER ?>" rel="stylesheet">
<link href="<?= $this->view->css ?>/iconfont/iconfont.css?ver=<?= VER ?>" rel="stylesheet" type="text/css">
<link href="<?= $this->view->css ?>/base.css?ver=<?= VER ?>" rel="stylesheet" type="text/css">

<script type="text/javascript" src="<?= $this->view->js_com ?>/jquery.js" charset="utf-8"></script>
<style>
.eject_con dl dt {
    display: inline-block;
    font-size: 12px;
    letter-spacing: normal;
    line-height: 32px;
    margin: 0;
    padding: 10px 1% 10px 0;
    text-align: right;
    vertical-align: top;
    width: 30%;
    word-spacing: normal;
}
.checkbox {
    padding: 0;
    vertical-align: middle;
}
.mr5 {
    margin-right: 5px !important;
}
</style>
<script type="text/javascript">
    var BASE_URL = "<?=Yf_Registry::get('base_url')?>";
    var SITE_URL = "<?=Yf_Registry::get('url')?>";
    var INDEX_PAGE = "<?=Yf_Registry::get('index_page')?>";
    var STATIC_URL = "<?=Yf_Registry::get('static_url')?>";

    var DOMAIN = document.domain;
    var WDURL = "";
    var SCHEME = "default";
    try
    {
        //document.domain = 'ttt.com';
    } catch (e)
    {
    }

    var SYSTEM = SYSTEM || {};
    SYSTEM.skin = 'green';
    SYSTEM.isAdmin = true;
    SYSTEM.siExpired = false;
</script>
<div class="eject_con">
    <div id="warning" class="alert alert-error"></div>
    <form id="form" action="#" method="post">
        <input type="hidden" name="id" id="id" value="<?= $data['t_id'] ?>">
        <dl>
            <dt><input id='is_receive' class="checkbox mr5" name="is_receive" type="checkbox" value="<?=$data['is_receive'];?>" <?php if($data['is_receive'] == 1){?> checked <?php }?> ><?=__('商家消息提醒：')?></dt>
            <dd>
                <p><?=__('系统将自动发送站内消息给商家。')?></p>
            </dd>
        </dl>
        <div class="bottom">
            <input  type="submit" id="handle_submit" class="button bbc_seller_submit_btns" value="<?=__('提交')?>"/>
        </div>
    </form>
</div>
<link href="./shop/static/common/css/jquery/plugins/dialog/green.css" rel="stylesheet">
<script type="text/javascript" src="<?= $this->view->js_com ?>/plugins/jquery.dialog.js"></script>
<link href="<?= $this->view->css_com ?>/jquery/plugins/validator/jquery.validator.css?ver=<?=VER?>" rel="stylesheet" type="text/css">
<script type="text/javascript" src="<?=$this->view->js_com?>/plugins/validator/jquery.validator.js" charset="utf-8"></script>
<script>
    $(document).ready(function ()
    {
        $('#form').validator({
            ignore: ':hidden',
            theme: 'yellow_right',
            timely: 1,
            stopOnError: false,
            valid: function (form)
            {
                if($('#is_receive').attr('checked') == 'checked'){
                    $('#is_receive').val('1');
                }else{
                    $('#is_receive').val('0');
                }
				var me = this;
				// 提交表单之前，hold住表单，防止重复提交
				me.holdSubmit();
                //表单验证通过，提交表单
                $.ajax({
                    url: SITE_URL + '?ctl=Seller_Message&met=setMessage&typ=json',
                    data: $("#form").serialize(),
                    success: function (a)
                    {
                        if (a.status == 200)
                        {
                            window.parent.location.href="<?= Yf_Registry::get('url') ?>?ctl=Seller_Message&met=messageManage"; 
                        }
                        else
                        {
                           Public.tips.error("<?=__('操作失败！')?>");
                        }
						// 提交表单成功后，释放hold，如果不释放hold，就变成了只能提交一次的表单
						me.holdSubmit(false);
                    },
					function ()
					 {
						me.holdSubmit(false);
					 }
                });
            }

        })
    });
</script>