<link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />
<link href="<?= $this->view->css ?>/seller.css?ver=<?= VER ?>" rel="stylesheet">
<link href="<?= $this->view->css ?>/iconfont/iconfont.css?ver=<?= VER ?>" rel="stylesheet" type="text/css">
<link rel="stylesheet" type="text/css" href="<?= $this->view->css ?>/base.css" />
<style>
    body {
        height: auto;
    }
    
    .eject_con dl dd {
        margin-bottom: 10px;
    }


</style>
<script type="text/javascript" src="<?= $this->view->js_com ?>/jquery.js" charset="utf-8"></script>

<script type="text/javascript">
    var BASE_URL = "<?=Yf_Registry::get('base_url')?>";
    var SITE_URL = "<?=Yf_Registry::get('url')?>";
    var INDEX_PAGE = "<?=Yf_Registry::get('index_page')?>";
    var STATIC_URL = "<?=Yf_Registry::get('static_url')?>";
    
    var DOMAIN = document.domain;
    var WDURL = "";
    var SCHEME = "default";
    try {
        //document.domain = 'ttt.com';
    } catch (e) {
    }
    
    var SYSTEM = SYSTEM || {};
    SYSTEM.skin = 'green';
    SYSTEM.isAdmin = true;
    SYSTEM.siExpired = false;
</script>
<div class="eject_con">
    <div id="warning" class="alert alert-error"></div>
    <form id="form" action="#" method="post">
        <input type="hidden" name="shop_id" id="shop_id" value="<?= $data['shop_id'] ?>">
        <input type="hidden" name="shop_name" id="shop_name" value="<?= $data['shop_name'] ?>">
        <input type="hidden" name="goods_id" id="goods_id" value="<?= $data['goods_id'] ?>">
        <input type="hidden" name="goods_name" id="goods_name" value="<?= $data['goods_name'] ?>">
        <dl>
            <dt class='mt6'><?= __('咨询分类：') ?></dt>
            <dd>
                <select name="consult_type_id">
                    <?php foreach ($type as $v) { ?>
                        <option value="<?= $v['consult_type_id'] ?>"><?= $v['consult_type_name'] ?></option>
                    <?php } ?>
                </select>
            </dd>
        </dl>
        <dl>
            <dt><?= __('商城承诺：') ?></dt>
            <dd>
                <?= __('商品均为原装正品行货，自带机打发票，严格执行国家三包政策，享受全国联保服务。') ?>
            </dd>
        </dl>
        <dl>
            <dt><?= __('功能咨询：') ?></dt>
            <dd>
                <?= __('咨询商品功能建议您拨打各品牌的官方客服电话，以便获得更准确的信息。') ?>
            </dd>
        </dl>
        <dl>
            <dt><?= __('用户名：') ?></dt>
            <dd>
                <?= Perm::$row['user_account'] ?> <input type="checkbox" name="no_show_user" value="1" style="vertical-align:middle;"> <?= __('匿名提问') ?>
            </dd>
        </dl>
        <dl>
            <dt><?= __('咨询问题：') ?></dt>
            <dd>
                <textarea name="consult_question" class="textarea_text" id="feedback" maxlength="200"></textarea>
                <span class="string-limit fz-28 colbc">
                    <i>
                        <span id="words">0</span>
                        / 200
                    </i>
                </span>
            </dd>
        </dl>
        <div class="bottom">
            <label class="submit-border"><input id="handle_submit" class="submit bbc_btns" value="<?= __('提问') ?>" /></label>
        </div>
    </form>
</div>
<link href="./shop/static/common/css/jquery/plugins/dialog/green.css" rel="stylesheet">
<script type="text/javascript" src="<?= $this->view->js_com ?>/plugins/jquery.dialog.js"></script>

<script>
    $(document).ready(function () {
        $("#feedback").keyup(function () {
            var lengths = $(this).val().length;
            if (lengths > 199) {
                $(this).val($(this).val().substring(0, 200));
            }
            if (lengths <= 0) {
                lengths = 0;
            }
            $("#words").text(lengths);
        });
        
        $('#form').validator({
            ignore: ':hidden',
            theme: 'yellow_right',
            timely: 1,
            stopOnError: false,
            messages: {
                required: "<?=__('请填写咨询内容')?>",
            },
            
            fields: {
                consult_question: "required"
            },
            valid: function (form) {
                //表单验证通过，提交表单
                $.ajax({
                    url: SITE_URL + '?ctl=Buyer_Service_Consult&met=addConsult&typ=json',
                    data: $("#form").serialize(),
                    success: function (a) {
                        console.log(a);
                        if (a.status == 200) {
                            //parent.$("#goodsadvisory").load();
                            // window.parent.consult();
                            //parent.location.reload();
                            // $.dialog().close();
                            parent.Public.tips.success('<?=__('提交成功！')?>');
                            setTimeout(function () {
                                $.dialog().close();
                                window.parent.consult();
                                // parent.location.reload();
                            }, 1500);
                        } else {
                            parent.Public.tips.error('<?=__('操作失败！')?>');
                        }
                    }
                });
            }
            
        }).on("click", "#handle_submit", function (e) {
            $(e.delegateTarget).trigger("validate");
        });
    });
</script>

<link href="<?= $this->view->css_com ?>/jquery/plugins/validator/jquery.validator.css?ver=<?= VER ?>" rel="stylesheet" type="text/css">
<script type="text/javascript" src="<?= $this->view->js_com ?>/plugins/validator/jquery.validator.js" charset="utf-8"></script>