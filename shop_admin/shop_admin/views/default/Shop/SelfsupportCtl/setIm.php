<?php if (!defined('ROOT_PATH')) {exit('No Permission');}?>
<?php
include $this->view->getTplPath() . '/'  . 'header.php';
?>
<link href="<?=$this->view->css?>/index.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="<?=$this->view->css_com?>/jquery/plugins/validator/jquery.validator.css">
<script type="text/javascript" src="<?=$this->view->js_com?>/plugins/validator/jquery.validator.js" charset="utf-8"></script>
<script type="text/javascript" src="<?=$this->view->js_com?>/plugins/validator/local/zh_CN.js" charset="utf-8"></script>

<style type="text/css">

.warning,i{color:red;}
.warning{padding-left: 60px;}
</style>
</head>
<body>
    <form method="post"  id="set_im" name="form">
        <div class="ncap-form-default">
            <dl class="row">
                <dt class="tit">
                    <label for="user_account"><i>*</i>IM账号</label>
                    
                </dt>
                <dd class="opt">
                    <input id="user_account"  name="user_account" value="<?=$user_account?>" class="ui-input w200" type="text"/>
                    <p class="notice">要绑定的IM的账号</p>
                </dd>
                
            </dl>
            <dl class="row">
                <dt class="tit">
                    <label for="user_password"><i>*</i>登录密码</label>
                    
                </dt>
                <dd class="opt">    
                    <input id="user_password"  name="user_password" value="" class="ui-input w200" type="password"/>
                    <p class="notice">该IM账号在商城的登录密码</p>
                </dd>
                
            </dl>
            <dl class="row">
                <dt class="tit">
                    <label for="user_password"><i>*</i>操作</label>
                    
                </dt>
                <dd class="opt">   
                    <input id="bind_type2"  name="bind_type" value="1" type="radio" checked="checked"/>绑定
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <input id="bind_type1"  name="bind_type" value="0" type="radio" />解绑
                </dd>
                
            </dl>
          <p class="warning">绑定后，所有自营店铺的IM消息将由该账号接收</p>
        </div>
    </form>

    <script>

function initPopBtns()
{
    var t = "add" == oper ? ["保存", "关闭"] : ["确定", "取消"];
    api.button({
        id: "confirm", name: t[0], focus: !0, callback: function ()
        {
            postData();
            return cancleGridEdit(),$("#set_im").trigger("validate"), !1;
        }
    }, {id: "cancel", name: t[1]})
}
function postData()
{
    var params = $('#set_im').serialize();
    $.post(SITE_URL  + '?ctl=Shop_Selfsupport&met=bindIm&typ=json',params,function(e){
        if(200 == e.status){ 
            parent.parent.Public.tips({content:"设置成功！"});
            api.close();
        }else{
            var msg = '设置失败!';
            if(e.msg != 'undefined' && !e.status){
                msg = e.status;
            }
            parent.parent.Public.tips({type: 1, content:msg});
        }
    });
 
}

var curRow, curCol, curArrears, $grid = $("#grid"),  $_form = $("#shop_add"), api = frameElement.api, oper = api.data.oper, rowData = api.data.rowData || {}, callback = api.data.callback;
initPopBtns();

</script>
<?php
include $this->view->getTplPath() . '/' . 'footer.php';
?>
