<link rel="shortcut icon" href="favicon.ico" type="image/x-icon"/>
<link href="<?= $this->view->css ?>/seller.css?ver=<?= VER ?>" rel="stylesheet">
<link href="<?= $this->view->css ?>/iconfont/iconfont.css?ver=<?= VER ?>" rel="stylesheet" type="text/css">

<script type="text/javascript" src="<?= $this->view->js_com ?>/jquery.js" charset="utf-8"></script>

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
                <input type="hidden" name="consult_id" id="consult_id" value="<?= $data['id'] ?>">
                <dl>
                    <dt><?=__('回复内容：')?></dt>
                    <dd>
                        <textarea name="consult_answer" class="textarea_text"></textarea>
                    </dd>
                </dl>
                <div class="bottom">
                    <label class="submit-border bbc_btns"><input id="handle_submit" class="submit bbc_btns" value="<?=__('回复')?>"/></label>
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
            fields: {
                consult_answer:"required"
            },
            valid: function (form)
            {
                //表单验证通过，提交表单
                $.ajax({
                    url: SITE_URL + '?ctl=Buyer_Service_Consult&met=replyConsult&typ=json',
                    data: $("#form").serialize(),
                    success: function (a)
                    {
                        if (a.status == 200)
                        {
                            window.parent.location.href="<?= Yf_Registry::get('url') ?>?ctl=Buyer_Service_Consult&met=index";
                            var index = parent.layer.getFrameIndex(window.name);
                            parent.layer.close(index);
                        }
                        else
                        {
                            Public.tips.error('<?=__('操作失败！')?>');
                        }
                    }
                });
            }

        }).on("click", "#handle_submit", function (e)
        {
            $(e.delegateTarget).trigger("validate");
        });
    });
</script>