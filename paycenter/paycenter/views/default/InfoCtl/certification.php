<?php if (!defined('ROOT_PATH'))
{
    exit('No Permission');
} ?>
<?php
include $this->view->getTplPath() . '/' . 'header.php';
?>
    <div class="main_cont wrap clearfix">
        <form id="form" name="form" action="" method="post" >
            <input name="from"  type="hidden" id='page_from' value="<?=$from?>" />
            <div class="account_left fl">
                <div class="account_mes">
                    <h4><?=_('实名认证')?></h4>
                    <table class="account_table">
                        <tbody>
                        <tr>
                            <td class="check_name"><?=_('用户名称：')?></td>
                            <td><?=$data['user_nickname']?></td>
                        </tr>
                        <tr>
                            <td class="check_name"><?=_('真实姓名：')?></td>
                            <td><input type="text" class="w168" value="<?=$data['user_realname']?>" name="user_realname" id="real_name" ><div class="form-error"></div></td>
                        </tr>
                        <tr>
                            <td class="check_name"><?=_('证件类型：')?></td>
                            <td>
                                <select name="user_identity_type" id="user_identity_type">
                                    <option value="1"  <?php if($data['user_identity_type']==1){?>selected<?php }?>><?=_('身份证')?></option>
                                    <option value="2"  <?php if($data['user_identity_type']==2){?>selected<?php }?>><?=_('护照')?></option>
                                    <option value="3" <?php if($data['user_identity_type']==3){?>selected<?php }?>><?=_('军官证')?></option>
                                </select>
                                <div class="form-error"></div></td>
                        </tr>
                        <tr>
                            <td class="check_name"><?=_('证件号码：')?></td>
                            <td><input type="text" maxlength="18" class="w168" value="<?=$data['user_identity_card']?>" name="user_identity_card" id="identity_card" onbeforepaste="clipboardData.setData('text',clipboardData.getData('text').replace(/[^\d[a-zA-Z].]/g,''))" onkeyup="value=value.replace(/[^\d[a-zA-Z].]/g,'')" ><div class="form-error"></div></td>
                        </tr>
                        <tr>
                            <td><?=_('证件有效期：')?></td>
                            <td><input readonly="readonly"  id="start_time"  name="user_identity_start_time"  class="w90 hasDatepicker" type="text" value="<?php echo $data['user_identity_start_time']>0 ? $data['user_identity_start_time'] : '';?>" /><em><i class="iconfont icon-rili"></i></em>
                                <span></span>-
                                <input readonly="readonly" id="end_time" name="user_identity_end_time"  class="w90 hasDatepicker" type="text" value="<?php echo $data['user_identity_end_time']>0 ? $data['user_identity_end_time'] : '';?>" /><em><i class="iconfont icon-rili"></i></em>
                            </td>
                        </tr>
                        <tr>
                            <td class="check_name"><?=_('正面照预览：')?></td>
                            <td>
                                <div class="user-avatar">
	                    <span>
	                 		   <img  id="image_img"  src="<?=$data['user_identity_face_logo']?$data['user_identity_face_logo']:'holder.js/240x151?text=预览区'; ?>"  width="" height="151" nc_type="avatar">
	                    </span>
                                </div>
                                <p class="hint mt5"><span style="color:orange;"><?=_('正面照尺寸为')?><span class="phosize">240x151</span><?=_('像素，请根据系统操作提示进行裁剪并生效。')?></span></p>
                            </td>
                        </tr>
                        <tr>
                            <td class="check_name"><?=_('证件正面照：')?></td>
                            <td>
                                <div > <a href="javascript:void(0);"><span>
                     <input name="user_identity_face_logo" id="user_identity_face_logo" type="hidden" value="<?=$data['user_identity_face_logo']?>" />
                      </span>
                                        <p id='user_upload' style="float:left;" class="bbc_btns"><i class="iconfont icon-upload-alt"></i><?=_('图片上传')?></p>

                                    </a> </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="check_name"><?=_('背面照预览：')?></td>
                            <td>
                                <div class="user-avatar"><span><img  id="image_font_img"  src="<?=$data['user_identity_font_logo']?$data['user_identity_font_logo']:'holder.js/240x151?text=预览区' ?>" width="" height="151" nc_type="avatar"></span></div>
                                <p class="hint mt5"><span style="color:orange;"><?=_('背面照尺寸为')?><span class="phosize">240x151</span><?=_('像素，请根据系统操作提示进行裁剪并生效。')?></span></p>
                            </td>
                        </tr>
                        <tr>
                            <td class="check_name"><?=_('证件背面照：')?></td>
                            <td>
                                <div > <a href="javascript:void(0);"><span>
                     <input name="user_identity_font_logo" id="user_identity_font_logo" type="hidden" value="<?=$data['user_identity_font_logo']?>" />
                      </span>
                                        <p id='user_font_upload' style="float:left;" class="bbc_btns"><i class="iconfont icon-upload-alt"></i><?=_('图片上传')?></p>

                                    </a> </div>
                            </td>
                        </tr>



                        <tr>
                            <td></td>
                            <td><input type="submit" value="<?=_('提交')?>" class="submit btn_active"></td
                        </tr>

                        </tbody>
                    </table>
                </div>
            </div>
            <div class="account_right fr">
                <div class="account_right_con">
                    <ul class="cert_instructions">
                        <li>
                            <h5><?=_('什么是实名认证？')?></h5>
                            <p><?=_('实名认证，是利用其国家级身份认证平台“身份通实名认证平台”推出的实名身份认证服务。在Pay Center平台进行实名认证无需繁琐步骤，只需如实填写您的姓名和身份证号，并支付5元实名认证费用（国家发改委定价，线上线下均可支付），就能完成实名认证。')?></p>
                        </li>
                        <li>
                            <h5><?=_('为什么要实名认证')?></h5>
                            <p><?=_('只有通过身份通实名身份认证的用户，才能使用Pay Center服务，从而实现真正的、全面的实名制平台。为保护用户隐私，用户之间只有在得到对方授权的情况下才可以交换实名认证信息。为保护用户信息，用户提供的身份证信息，将直接传输到“全国公民身份信息系统”系统数据库中，并即时返回认证结果，Pay Center并不保留用户的身份证号码。')?></p>
                        </li>
                        <li>
                            <h5><?=_('温馨提示')?></h5>
                            <p><?=_('通过实名认证表示该用户提交了真实存在的身份证，但我们无法完全确认该证件是否为其本人持有，您还需要通过和对方交换实名信息来获取对方全名及身份证照片，并与对方照片或本人进行比对，核实对方是否该身份证的持有人。实名认证也不能代表除身份证信息外的其他信息是否真实。因此，Pay Center提醒广大家庭用户在使用过程中，须保持谨慎理性，增强防范意识，避免产生经济等其他往来。')?></p>
                        </li>
                    </ul>
                </div>
            </div>
        </form>
    </div>
    <link href="<?= $this->view->css_com ?>/jquery/plugins/datepicker/dateTimePicker.css?ver=<?= VER ?>" rel="stylesheet" type="text/css">
    <script type="text/javascript" src="<?=$this->view->js?>/webuploader.js" charset="utf-8"></script>
    <script type="text/javascript" src="<?=$this->view->js?>/upload/upload_image.js" charset="utf-8"></script>
    <link href="<?= $this->view->css ?>/webuploader.css" rel="stylesheet" type="text/css">
    <script type="text/javascript" src="<?= $this->view->js_com ?>/plugins/jquery.datetimepicker.js"></script>
    <script type="text/javascript" src="<?php echo $this->view->js; ?>/holder.js" ></script>
    <script>
        var uploadw = 240;
        var uploadh = 151;
        //护照（1476*1062）
        if('<?=$data['user_identity_type']?>' == 2)
        {
            uploadw = 1476;
            uploadh = 1062;

            $(".phosize").html('1476x1062');
        } //军官（1748*1299）
        else if('<?=$data['user_identity_type']?>' == 3)
        {
            uploadh = 1748;
            uploadw = 1299;
            $(".phosize").html('1748x1299');
        }

        $(function(){
            $('#start_time').datetimepicker({
                controlType: 'select',
                format:"Y-m-d",
                timepicker:false
            });

            $('#end_time').datetimepicker({
                controlType: 'select',
                format:"Y-m-d",
                timepicker:false,
                onShow:function( ct ){
                    this.setOptions({
                        minDate:($('#start_time').val())
                    })
                }
            });
        });
        $(".test").on('click', function () {alert('sssdddd');});
        $(function(){
            $("#user_identity_type").change(function() {
                var selectValue = $("#user_identity_type").val();
                if(selectValue == 2)
                {
                    uploadw = 1476;uploadh = 1062;$(".phosize").html('1476x1062');
                }
                else if(selectValue == 3)
                {
                    uploadw = 1748;uploadh = 1299;$(".phosize").html('1748x1299');
                }
                else
                {
                    uploadw = 240;uploadh = 151;$(".phosize").html('240x151');
                }
            });
        })

        //图片上传
        $(function(){
            $('#user_upload').on('click', function () {
                $.dialog({
                    title: '图片裁剪',
                    content: "url: <?= Yf_Registry::get('url') ?>?ctl=Upload&met=cropperImage&typ=e",
                    data: { width: uploadw, height: uploadh, callback: callback },    // 需要截取图片的宽高比例
                    width: '800px',
                    /*height: '310px',*/
                    lock: true
                })
            });

            function callback ( respone , api ) {
                $('#image_img').attr('src', respone.url);
                $('#user_identity_face_logo').attr('value', respone.url);
                api.close();
            }

        })
        //图片上传
        $(function(){
            $('#user_font_upload').on('click', function () {
                $.dialog({
                    title: '图片裁剪',
                    content: "url: <?= Yf_Registry::get('url') ?>?ctl=Upload&met=cropperImage&typ=e",
                    data: { width: uploadw, height: uploadh, callback: callback },    // 需要截取图片的宽高比例
                    width: '800px',
                    /*height: '310px',*/
                    lock: true
                })
            });

            function callback ( respone , api ) {
                $('#image_font_img').attr('src', respone.url);
                $('#user_identity_font_logo').attr('value', respone.url);
                api.close();
            }

        })
        $(document).ready(function(){
            var ajax_url = '<?= Yf_Registry::get('url');?>?ctl=Info&met=editCertification&typ=json';
            $('#form').validator({
                ignore: ':hidden',
                theme: 'yellow_right',
                timely: 1,
                stopOnError: false,
                rules: {
                    identity_type:function()
                    {
                        var user_identity_type = $('#user_identity_type').val();
                        var identity_card = $("#identity_card").val();

                        if(user_identity_type == 1)
                        {
                            //验证身份证是否正确
                            if(!checkCard(identity_card)){
                                return '身份证号码格式错误';
                            }
                        }

                    },
                    times:function(){
                        var start_time = $('#start_time').val();
                        var end_time = $('#end_time').val();
                        if(start_time>end_time && end_time){
                            return '<?=_('不能小于起始时间')?>';
                        }
                    },
                },
                fields : {
                    'user_realname':'required;',
                    'user_identity_card':'required;identity_type',
                    'user_identity_start_time':'required;',
                    'user_identity_end_time':'times;',
                },
                valid:function(form){
                    //表单验证通过，提交表单
                    $.ajax({
                        url: ajax_url,
                        data:$("#form").serialize(),
                        success:function(a){
                            if(a.status == 200)
                            {
                                Public.tips.success("<?=_('操作成功')?>");
                                var from = $('#page_from').val();
                                if(from == 'bt'){
                                    location.href= "<?= Yf_Registry::get('url');?>?ctl=Info&met=btinfo";
                                }else{
                                    location.href= "<?= Yf_Registry::get('url');?>?ctl=Info&met=account";
                                }

                            }
                            else
                            {
                                if(typeof(a.msg) != 'undefined'){
                                    Public.tips.error(a.msg);
                                }else{
                                    Public.tips.error("<?=_('操作失败')?>");
                                }
                                return false;
                            }
                        }
                    });
                }
            });
        });

        var vcity={ 11:"北京",12:"天津",13:"河北",14:"山西",15:"内蒙古",
            21:"辽宁",22:"吉林",23:"黑龙江",31:"上海",32:"江苏",
            33:"浙江",34:"安徽",35:"福建",36:"江西",37:"山东",41:"河南",
            42:"湖北",43:"湖南",44:"广东",45:"广西",46:"海南",50:"重庆",
            51:"四川",52:"贵州",53:"云南",54:"西藏",61:"陕西",62:"甘肃",
            63:"青海",64:"宁夏",65:"新疆",71:"台湾",81:"香港",82:"澳门",91:"国外"
        };
        checkCard = function(obj)
        {
            //var card = document.getElementById('card_no').value;
            //是否为空
            // if(card === '')
            // {
            //  return false;
            //}
            //校验长度，类型
            if(isCardNo(obj) === false)
            {
                return false;
            }
            //检查省份
            if(checkProvince(obj) === false)
            {
                return false;
            }
            //校验生日
            if(checkBirthday(obj) === false)
            {
                return false;
            }
            //检验位的检测
            if(checkParity(obj) === false)
            {
                return false;
            }
            return true;
        };
        //检查号码是否符合规范，包括长度，类型
        isCardNo = function(obj)
        {
            //身份证号码为15位或者18位，15位时全为数字，18位前17位为数字，最后一位是校验位，可能为数字或字符X
            var reg = /(^\d{15}$)|(^\d{17}(\d|X)$)/;
            if(reg.test(obj) === false)
            {
                return false;
            }
            return true;
        };
        //取身份证前两位,校验省份
        checkProvince = function(obj)
        {
            var province = obj.substr(0,2);
            if(vcity[province] == undefined)
            {
                return false;
            }
            return true;
        };
        //检查生日是否正确
        checkBirthday = function(obj)
        {
            var len = obj.length;
            //身份证15位时，次序为省（3位）市（3位）年（2位）月（2位）日（2位）校验位（3位），皆为数字
            if(len == '15')
            {
                var re_fifteen = /^(\d{6})(\d{2})(\d{2})(\d{2})(\d{3})$/;
                var arr_data = obj.match(re_fifteen);
                var year = arr_data[2];
                var month = arr_data[3];
                var day = arr_data[4];
                var birthday = new Date('19'+year+'/'+month+'/'+day);
                return verifyBirthday('19'+year,month,day,birthday);
            }
            //身份证18位时，次序为省（3位）市（3位）年（4位）月（2位）日（2位）校验位（4位），校验位末尾可能为X
            if(len == '18')
            {
                var re_eighteen = /^(\d{6})(\d{4})(\d{2})(\d{2})(\d{3})([0-9]|X)$/;
                var arr_data = obj.match(re_eighteen);
                var year = arr_data[2];
                var month = arr_data[3];
                var day = arr_data[4];
                var birthday = new Date(year+'/'+month+'/'+day);
                return verifyBirthday(year,month,day,birthday);
            }
            return false;
        };
        //校验日期
        verifyBirthday = function(year,month,day,birthday)
        {
            var now = new Date();
            var now_year = now.getFullYear();
            //年月日是否合理
            if(birthday.getFullYear() == year && (birthday.getMonth() + 1) == month && birthday.getDate() == day)
            {
                //判断年份的范围（3岁到100岁之间)
                var time = now_year - year;
                if(time >= 0 && time <= 130)
                {
                    return true;
                }
                return false;
            }
            return false;
        };
        //校验位的检测
        checkParity = function(obj)
        {
            //15位转18位
            obj = changeFivteenToEighteen(obj);
            var len = obj.length;
            if(len == '18')
            {
                var arrInt = new Array(7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2);
                var arrCh = new Array('1', '0', 'X', '9', '8', '7', '6', '5', '4', '3', '2');
                var cardTemp = 0, i, valnum;
                for(i = 0; i < 17; i ++)
                {
                    cardTemp += obj.substr(i, 1) * arrInt[i];
                }
                valnum = arrCh[cardTemp % 11];
                if (valnum == obj.substr(17, 1))
                {
                    return true;
                }
                return false;
            }
            return false;
        };
        //15位转18位身份证号
        changeFivteenToEighteen = function(obj)
        {
            if(obj.length == '15')
            {
                var arrInt = new Array(7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2);
                var arrCh = new Array('1', '0', 'X', '9', '8', '7', '6', '5', '4', '3', '2');
                var cardTemp = 0, i;
                obj = obj.substr(0, 6) + '19' + obj.substr(6, obj.length - 6);
                for(i = 0; i < 17; i ++)
                {
                    cardTemp += obj.substr(i, 1) * arrInt[i];
                }
                obj += arrCh[cardTemp % 11];
                return obj;
            }
            return obj;
        };


    </script>
<?php
include $this->view->getTplPath() . '/' . 'footer.php';
?>