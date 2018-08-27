<?php if (!defined('ROOT_PATH')) exit('No Permission');?>

<?php
include $this->view->getTplPath() . '/' . 'seller_header.php';
?>
<link href="<?=$this->view->css_com?>/webuploader.css" rel="stylesheet">
<script src="<?=$this->view->js_com?>/webuploader.js"></script>
<script src="<?=$this->view->js_com?>/upload/upload_image.js"></script>
</head>
<body>

    <div class="tabmenu">
        <ul>
            <li ><a href="./index.php?ctl=Seller_Shop_Chain&met=chain&typ=e"><?=__('门店列表')?></a></li>

            <?php if($act == 'add') {?>
    <li  class="active bbc_seller_bg"><a href="javascript:void(0);"><?=__('添加门店')?></a></li>
<?php }
if($act == 'edit'){?>
    <li class="active bbc_seller_bg"><a href="javascript:void(0);"><?=__('编辑门店')?></a></li>
<?php }?>
        </ul>

    </div>
    
    <?php if($act == 'add') {?>
        <div class="alert">
            <ul>
                <li>1、<?=__('可添加多个门店,同时管理。')?></li>
                <li>2、<strong class="bbc_seller_color"><?=__('所填门店信息真实准确。')?></strong></li>
            </ul>
        </div>
        <form method="post" action="#" id="form" enctype="multipart/form-data">
            <div class="ncsc-form-default">
                <h3><?=__('门店账户注册')?></h3>
                <dl class="dl">
                    <dt><i class="required">*</i><?=__('登录名')?>：</dt>
                    <dd>
                        <input type="text" class="text w200" name="chain_user" id="chain_user" value="">
                        <p class="hint"><?=__('登录名请使用中文、字母、数字、下划线（最低三个字符）。')?></p>
                    </dd>
                </dl>
                <dl class="dl">
                    <dt>
                        <i class="required">*</i>
                        <?=__('登录密码')?>：</dt>
                    <dd>
                        <input type="password" class="text w200" name="chain_pwd" id="chain_pwd" autocomplete="off" value="">
                        <p class="hint"><?=__('密码请使用6--20个字符（区分大小写），由字母(必填)、数字(必填)、下划线(可选)组成。')?></p>
                    </dd>
                </dl>
                <dl class="dl">
                    <dt>
                        <i class="required">*</i>
                        <?=__('确认密码')?>：</dt>
                    <dd>
                        <input type="password" class="text w200" name="confirm_pwd" id="confirm_pwd" value="">
                        <p class="hint"><?=__('请再次输入登录密码，确保前后输入一致。')?></p>
                    </dd>
                </dl>
                <h3><?=__('门店相关信息')?></h3>
                <dl class="dl">
                    <dt><i class="required">*</i><?=__('门店名称')?>：</dt>
                    <dd>
                        <input type="text" class="text w200" name="chain_name" id="chain_name" value="">
                        <p class="hint"><?=__('请认真填写您的门店名称，以确保用户（购买者）线下到店自提时查找。')?></p>
                    </dd>
                </dl>
                <dl class="dl">
                    <dt><i class="required">*</i><?=__('所在地区')?>：</dt>
                    <dd>
                        <input type="hidden" name="address_area" id="t" value="" />
                        <input type="hidden" name="province_id" id="id_1" value="" />
                        <input type="hidden" name="city_id" id="id_2" value="" />
                        <input type="hidden" name="area_id" id="id_3" value="" />
                        <div id="d_2">
                            <select id="select_1" name="select_1" onChange="district(this);">
                                <option value="">--<?=__('请选择')?>--</option>
                                <?php foreach($district['items'] as $key=>$val){ ?>
                                    <option value="<?=$val['district_id']?>|1"><?=$val['district_name']?></option>
                                <?php } ?>
                            </select>
                            <select id="select_2" name="select_2" onChange="district(this);" class="hidden"></select>
                            <select id="select_3" name="select_3" onChange="district(this);" class="hidden"></select>
                        </div>
                        <p class="hint"><?=__('所在地区将直接影响购买者在选择线下自提时的地区筛选，因此请如实认真选择全部地区级。')?></p>
                    </dd>
                </dl>
                <dl class="dl">
                    <dt><i class="required">*</i><?=__('详细地址')?>：</dt>
                    <dd>
                        <input type="text" class="text w400" name="chain_address" id="chain_address" value="">
                        <p class="hint"><?=__('请认真填写详细地址，以确保用户（购物者）线下到店自提时能最准确的到达您的门店。')?></p>
                    </dd>
                </dl>
                <dl class="dl">
                    <dt><i class="required">*</i><?=__('联系电话')?>：</dt>
                    <dd>
                        <input type="text" class="text w200" name="chain_phone" id="chain_phone" value="">
                        <p class="hint"><?=__('请认真填写门店联系电话，方便用户（购物者）通过该电话与您直接取得联系。')?></p>
                    </dd>
                </dl>
                <dl class="dl">
                    <dt><i class="required">*</i><?=__('营业时间')?>：</dt>
                    <dd>
                        <textarea class="textarea w400" maxlength="50" rows="2" name="chain_opening_hours" id="chain_opening_hours"></textarea>
                        <p class="hint"><?=__('如实填写您的线下门店营业时间，以免用户（购物者）在营业时间外到店产生误会。')?></p>
                    </dd>
                </dl>
                <dl class="dl">
                    <dt><i class="required"></i><?=__('交通线路')?>：</dt>
                    <dd>
                        <textarea class="textarea w400" maxlength="50" rows="2" name="chain_traffic_line" id="chain_traffic_line"></textarea>
                        <p class="hint"><?=__('如您的门店周围有公交、地铁线路到达，请填写该选项，多条线路请以“、”进行分隔。')?></p>
                    </dd>
                </dl>
                <dl class="dl">
                    <dt><i class="required">*</i><?=__('实拍照片')?>：</dt>
                    <dd>
                        <div class="image">
                            <img id="chainImage" height="160px" width="160px" src="" />
                            <input id="chainimagePath" name="chainimagePath" type="hidden" value=""  />
                        </div>
                        <div id="uploadButton" style="width: 81px;height: 28px;float: left;margin-top:5px;">
                            <i class="iconfont icon-tupianshangchuan"></i><?=__('图片上传')?></div>
                        <div><p class="hint"><?=__('将您的实体店面沿街图上传，方便用户（购物者）线下到店自提时能最准确直观的找到您的门店。')?></p></div>
                    </dd>
                </dl>
                <dl class="dl">
                    <dt></dt>
                    <dd><input type="submit" class="button bbc_seller_submit_btns" value="<?=__('确认提交')?>" /></dd>
                </dl>
            </div>
        </form>
    <script>
        $(document).ready(function(){

            var ajax_url = './index.php?ctl=Seller_Shop_Chain&met=<?=$act?>Chain&typ=json';

            $('#form').validator({
                ignore: '',
                theme: 'yellow_right',
                timely: 1,
                stopOnError: false,
                rules: {
                    password: [/^(?![0-9]+$)(?![a-zA-Z]+$)[0-9A-Za-z_]{6,20}$/, '密码请使用6--20个字符（区分大小写），由字母(必填)、数字(必填)、下划线(可选)组成'],
                },
                fields: {
                    "chain_user": {
                        rule: "required;length[3~]",
                        msg: {
                            required :"<?=__('请填写门店登录名')?>",
                            length:"<?=__('请填写正确的门店登录名')?>" 
                        }
                    },
                    'select_1':'required',
                    'select_2':'required',
                    //'select_3':'required',
                    'chain_name':'required',
                    'chain_address':'required',
                    'chain_phone':'required;mobile',
                    'chain_opening_hours':'required',
                    'chainimagePath':{
                        rule: "required",
                        msg: {
                            required : "<?=__('请上传图片')?>"
                        }
                    },
                    "chain_pwd": {
                        rule: "required;length[6~20];password",
                        msg: {
                            required : "<?=__('请填写门店登录密码')?>",
                            length : "<?=__('请填写正确密码')?>"
                        }
                    },
                    "confirm_pwd": {
                        rule: "required;match(chain_pwd);password",
                        msg: {
                            required : "<?=__('请填写确认密码')?>",
                            match : "<?=__('与登录密码不同，请重新填写')?>"
                        }
                    },
                    'chain_name': {
                        rule: 'required;length[~25]',
                        length: "<?=__('门店名称不能超过25个字符')?>"
                    }
                },
                valid:function(form){
                    var me = this;
                    // 提交表单之前，hold住表单，防止重复提交
                    me.holdSubmit();
                    //表单验证通过，提交表单
                    $.ajax({
                        url: ajax_url,
                        data:$("#form").serialize(),
                        success:function(a){
                            if(a.status == 200)
                            {
                                Public.tips.success("<?=__('操作成功！')?>");
                                me.holdSubmit(false);
                                setTimeout(' location.href="./index.php?ctl=Seller_Shop_Chain&met=chain&typ=e"',3000); //成功后跳转
                            }
                            else
                            {
                                Public.tips.error(a.msg);

                                me.holdSubmit(false);
                                //setTimeout('window.location.reload();', 3000); //成功后跳转
                            }
                        }
                    });
                }

            });
        });
    </script>


<?php }
if($act == 'edit' && $data){?>
    <div class="alert">
        <ul>
             <li><strong class="bbc_seller_color"><?=__('所填门店信息真实准确。')?></strong></li>
        </ul>
    </div>
    <form method="post" action="#" id="form" enctype="multipart/form-data">
        <div class="ncsc-form-default">
            <input type="hidden" name="chain_id" value="<?=@$data['chain_id']?>">
            <h3><?=__('门店账户注册')?></h3>
            <dl class="dl">
                <dt><i class="required">*</i><?=__('登录名')?>：</dt>
                <dd>
                    <input type="text" class="text w200" name="chain_user" id="chain_user" value="<?=@$data['chain_user']?>" readOnly="true">
                    <p class="hint"><?=__('登录名请使用中文、字母、数字、下划线（最低三个字符）。')?></p>
                </dd>
            </dl>
            <dl class="dl">
                <dt>
                    <i class="required">*</i>
                    <?=__('登录密码')?>：</dt>
                <dd>
                    <input type="password" class="text w200" name="chain_pwd" id="chain_pwd" autocomplete="off" value="">
                    <p class="hint"><?=__('密码请使用6--20个字符（区分大小写），由字母(必填)、数字(必填)、下划线(可选)组成。')?></p>
                </dd>
            </dl>
            <dl class="dl">
                <dt>
                    <i class="required">*</i>
                    <?=__('确认密码')?>：</dt>
                <dd>
                    <input type="password" class="text w200" name="confirm_pwd" id="confirm_pwd" value="">
                    <p class="hint"><?=__('请再次输入登录密码，确保前后输入一致。')?></p>
                </dd>
            </dl>
            <h3><?=__('门店相关信息')?></h3>
            <dl class="dl">
                <dt><i class="required">*</i><?=__('门店名称')?>：</dt>
                <dd>
                    <input type="text" class="text w200" name="chain_name" id="chain_name" value="<?=@$data['chain_name']?>">
                    <p class="hint"><?=__('请认真填写您的门店名称，以确保用户（购买者）线下到店自提时查找。')?></p>
                </dd>
            </dl>
            <dl class="dl">
                <dt><i class="required">*</i><?=__('所在地区')?>：</dt>
                <dd>
                    <input type="hidden" name="address_area" id="t" value="<?=@$data['chain_area']?>" />
                    <input type="hidden" name="province_id" id="id_1" value="<?=@$data['chain_province_id']?>" />
                    <input type="hidden" name="city_id" id="id_2" value="<?=@$data['chain_city_id']?>" />
                    <input type="hidden" name="area_id" id="id_3" value="<?=@$data['chain_county_id']?>" />

                    <?php if(@$data['chain_area']){ ?>
                        <div id="d_1"><?=@$data['chain_area'] ?>&nbsp;&nbsp;<a href="javascript:sd();"><?=__('编辑')?></a></div>
                    <?php } ?>

                    <div id="d_2"  class="<?php if(@$data['chain_area']) echo 'hidden';?>">
                        <select id="select_1" name="select_1" onChange="district(this);">
                            <option value="">--<?=__('请选择')?>--</option>
                            <?php foreach($district['items'] as $key=>$val){ ?>
                                <option value="<?=$val['district_id']?>|1"><?=$val['district_name']?></option>
                            <?php } ?>
                        </select>
                        <select id="select_2" name="select_2" onChange="district(this);" class="hidden"></select>
                        <select id="select_3" name="select_3" onChange="district(this);" class="hidden"></select>
                    </div>
                    <p class="hint"><?=__('所在地区将直接影响购买者在选择线下自提时的地区筛选，因此请如实认真选择全部地区级。')?></p>
                </dd>
            </dl>
            <dl class="dl">
                <dt><i class="required">*</i><?=__('详细地址')?>：</dt>
                <dd>
                    <input type="text" class="text w400" name="chain_address" id="chain_address" value="<?=@$data['chain_address']?>">
                    <p class="hint"><?=__('请认真填写详细地址，以确保用户（购物者）线下到店自提时能最准确的到达您的门店。')?></p>
                </dd>
            </dl>
            <dl class="dl">
                <dt><i class="required">*</i><?=__('联系电话')?>：</dt>
                <dd>
                    <input type="text" class="text w200" name="chain_phone" id="chain_phone" value="<?=@$data['chain_mobile']?>">
                    <p class="hint"><?=__('请认真填写门店联系电话，方便用户（购物者）通过该电话与您直接取得联系。')?></p>
                </dd>
            </dl>
            <dl class="dl">
                <dt><i class="required">*</i><?=__('营业时间')?>：</dt>
                <dd>
                    <textarea class="textarea w400" maxlength="50" rows="2" name="chain_opening_hours" id="chain_opening_hours"><?=@$data['chain_opening_hours']?></textarea>
                    <p class="hint"><?=__('如实填写您的线下门店营业时间，以免用户（购物者）在营业时间外到店产生误会。')?></p>
                </dd>
            </dl>
            <dl class="dl">
                <dt><i class="required"></i><?=__('交通线路')?>：</dt>
                <dd>
                    <textarea class="textarea w400" maxlength="50" rows="2" name="chain_traffic_line" id="chain_traffic_line"><?=@$data['chain_traffic_line']?></textarea>
                    <p class="hint"><?=__('如您的门店周围有公交、地铁线路到达，请填写该选项，多条线路请以“、”进行分隔。')?></p>
                </dd>
            </dl>
            <dl class="dl">
                <dt><i class="required">*</i><?=__('实拍照片')?>：</dt>
                <dd>
                    <div class="image">
                        <img id="chainImage" height="160px" width="160px" src="<?=@$data['chain_img']?>" />
                        <input id="chainimagePath" name="chainimagePath" type="hidden" value="<?=@$data['chain_img']?>"  />
                    </div>
                    <div id="uploadButton" style="width: 81px;height: 28px;float: left;margin-top:5px;">
                        <i class="iconfont icon-tupianshangchuan"></i><?=__('图片上传')?></div>
                    <div><p class="hint"><?=__('将您的实体店面沿街图上传，方便用户（购物者）线下到店自提时能最准确直观的找到您的门店。')?></p></div>
                </dd>
            </dl>
            <dl class="dl">
                <dt></dt>
                <dd><input type="submit" class="button bbc_seller_submit_btns" value="<?=__('确认提交')?>" /></dd>
            </dl>
        </div>
    </form>
    <script>
        $(document).ready(function(){

            var ajax_url = './index.php?ctl=Seller_Shop_Chain&met=<?=$act?>Chain&typ=json';

            $('#form').validator({
                ignore: ':hidden',
                theme: 'yellow_right',
                timely: 1,
                stopOnError: false,
                rules: {
                    password: [/^(?![0-9]+$)(?![a-zA-Z]+$)[0-9A-Za-z_]{6,20}$/, '密码请使用6--20个字符（区分大小写），由字母(必填)、数字(必填)、下划线(可选)组成'],
                },
                fields: {
                    "chain_user": {
                        rule: "required;length[3~]",
                        msg: {
                            required : "<?=__('请填写门店登录名')?>",
                            length: "<?=__('请填写正确的门店登录名')?>"
                        }
                    },
                    'select_1':'required',
                    'select_2':'required',
                    //'select_3':'required',
                    'chain_name':'required',
                    'chain_address':'required',
                    'chain_phone':'required;mobile',
                    'chain_opening_hours':'required',
                    'chain_img':'required',
                    "chain_pwd": {
                        rule: "length[6~20];password",
                        msg: {
                            length : "<?=__('请填写正确密码')?>"
                        }
                    },
                    "confirm_pwd": {
                        rule: "match(chain_pwd)",
                        msg: {
                            match : "<?=__('与登录密码不同，请重新填写')?>"
                        }
                    },
                    'chain_name': {
                        rule: 'required;length[~25]',
                        length: "<?=__('门店名称不能超过25个字符')?>"
                    }
                },
                valid:function(form){
                    var me = this;
                    // 提交表单之前，hold住表单，防止重复提交
                    me.holdSubmit();
                    //表单验证通过，提交表单
                    $.ajax({
                        url: ajax_url,
                        data:$("#form").serialize(),
                        success:function(a){
                            if(a.status == 200)
                            {
                                Public.tips.success("<?=__('操作成功！')?>");
                                setTimeout(' location.href="./index.php?ctl=Seller_Shop_Chain&met=chain&typ=e"',3000); //成功后跳转
                            }
                            else
                            {
                                Public.tips.error("<?=__('操作失败！')?>");
                            }
                        }
                    });
                }

            });
        });
    </script>
<?php }?>
<script type="text/javascript" src="<?=$this->view->js?>/district.js"></script>
<script>
    //图片上传
    $(function(){

        var uploadImage = new UploadImage({

            thumbnailWidth: 160,
            thumbnailHeight: 160,
            imageContainer: '#chainImage',
            uploadButton: '#uploadButton',
            inputHidden: '#chainimagePath',
            callback: function () {
                $('#chainimagePath').isValid();
            }
        });

    })
</script>
<?php
include $this->view->getTplPath() . '/' . 'seller_footer.php';
?>