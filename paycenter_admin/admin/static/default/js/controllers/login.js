function get_randfunc(obj)
{
    var sj = new Date();
    url = obj.src;
    obj.src = url + '?' + sj;
}
function check()
{
    if (user_account.value == placeholder.user_account)
    {
        return alert(placeholder.user_account), user_account.focus(), !1;
    }
    if (user_password.value == placeholder.user_password)
    {
        return alert(placeholder.user_password), user_password.focus(), !1;
    }
    if (yzm.value == placeholder.yzm)
    {
        return alert(placeholder.yzm), yzm.focus(), !1;
    }
}
var placeholder = {
    user_account: '　请输入用户名/手机号码',
    user_password: '　请输入密码',
    yzm: '　请输入验证码'
}, user_account = document.getElementById('user_account'), user_password = document.getElementById('user_password'), yzm = document.getElementById('yzm'), form = document.getElementById('login_form'), left_img = document.getElementById('left_img'), wh = ((document.documentElement.clientHeight || document.body.clientHeight) - 488 - 50) / 2;
//window.onload = function ()
//{
//    form.style.marginTop = wh + 'px';
//    left_img.style.marginTop = wh - 20 + 'px';
//    user_account.value = placeholder.user_account;
//    user_password.value = placeholder.user_password;
//    user_password.type = 'text';
//    yzm.value = placeholder.yzm;
//    user_account.className += ' ' + 'placeholder';
//    user_password.className += ' ' + 'placeholder';
//    yzm.className += ' ' + 'placeholder';
//    user_account.onfocus = function ()
//    {
//        if (this.value == placeholder.user_account)
//        {
//            this.value = '';
//            this.className = 'texts';
//        }
//    }
//    user_account.onblur = function ()
//    {
//        if (this.value == '')
//        {
//            this.value = placeholder.user_account;
//            this.className += ' ' + 'placeholder';
//        }
//    }
//    user_password.onfocus = function ()
//    {
//        if (this.value == placeholder.user_password)
//        {
//            this.value = '';
//            this.type = 'password';
//            this.className = 'texts';
//        }
//    }
//    user_password.onblur = function ()
//    {
//        if (this.value == '')
//        {
//            this.type = 'text';
//            this.value = placeholder.user_password;
//            this.className += ' ' + 'placeholder';
//        }
//    }
//    yzm.onfocus = function ()
//    {
//        if (this.value == placeholder.yzm)
//        {
//            this.value = '';
//            this.className = 'texts';
//        }
//    }
//    yzm.onblur = function ()
//    {
//        if (this.value == '')
//        {
//            this.value = placeholder.yzm;
//            this.className += ' ' + 'placeholder';
//        }
//    }
//};
function resizeWin()
{
    wh = ((document.documentElement.clientHeight || document.body.clientHeight) - 488 - 50) / 2;
    form.style.marginTop = wh + 'px';
    left_img.style.marginTop = wh - 20 + 'px';
}

