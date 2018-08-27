<?php if (!defined('ROOT_PATH')) {exit('No Permission');}?>
<?php
include $this->view->getTplPath() . '/'  . 'header.php';
?>
<link rel="stylesheet" href="<?=$this->view->css_com?>/jquery/plugins/validator/jquery.validator.css">
<script type="text/javascript" src="<?=$this->view->js_com?>/plugins/validator/jquery.validator.js" charset="utf-8"></script>
<script type="text/javascript" src="<?=$this->view->js_com?>/plugins/validator/local/zh_CN.js" charset="utf-8"></script>

<link rel="stylesheet" href="<?=$this->view->css_com?>/jquery/plugins/datepicker/dateTimePicker.css">
<script type="text/javascript" src="<?=$this->view->js_com?>/plugins/jquery.datetimepicker.js" charset="utf-8"></script>

<link href="<?= $this->view->css_com ?>/webuploader.css" rel="stylesheet" type="text/css">

<link href="<?=$this->view->css?>/index.css" rel="stylesheet" type="text/css">
<link href="<?=$this->view->css?>/complain.css" rel="stylesheet" type="text/css">
</head>
<style>
     .ncap-form-default{padding-top:0;}
</style>

<body>
<div class="manage-wrap">
    <div class="ncap-form-default">
        <form id="points_goods_form" action="" method="post">
            <input type="hidden" name="points_goods_id" value="<?=@($data['points_goods_id'])?>">
            <input type="hidden" name="operate" value="<?php if(request_int('id')){ ?>edit<?php }else{ ?>add<?php } ?>">
            <dl class="row row-item">
                <dt class="tit"><em>*</em><label>礼品名称</label>：</dt>
                <dd class="opt"><input type="text" class="input-txt" name="points_goods_name" value="<?=@($data['points_goods_name'])?>"></dd>
            </dl>
            <dl class="row row-item">
                <dt class="tit"><em>*</em><label>礼品原价</label>：</dt>
                <dd class="opt"><input type="text" class="input-txt" name="points_goods_price" value="<?=@($data['points_goods_price'])?>"></dd>
            </dl>
            <dl class="row row-item">
                <dt class="tit"><em>*</em><label>兑换积分</label>：</dt>
                <dd class="opt"><input type="text" class="input-txt" name="points_goods_points" value="<?=@($data['points_goods_points'])?>"></dd>
            </dl>
            <dl class="row row-item">
                <dt class="tit"><em>*</em><label>礼品编号</label>：</dt>
                <dd class="opt"><input type="text" class="input-txt" name="points_goods_serial" value="<?=@($data['points_goods_serial'])?>"></dd>
            </dl>
            <dl class="row">
                <dt class="tit">
                    <label for="">礼品图片</label>
                </dt>
                <dd class="opt">
                    <img id="points_goods_review" src="<?=@($data['points_goods_image'])?>" width="400" height="300"/>
                    <input type="hidden" id="points_goods_image" name="points_goods_image" value="<?=@($data['points_goods_image'])?>" />
                    <div  id='points_goods_upload' class="image-line upload-image" >图片上传</div>
                </dd>
            </dl>
            <dl class="row">
                <dt class="tit">礼品标签：</dt>
                <dd class="opt"><input type="text" class="input-txt" name="points_goods_tag" value="<?=@($data['points_goods_tag'])?>"></dd>
            </dl>
            <dl class="row row-item">
                <dt class="tit"><em>*</em><label>库存</label>：</dt>
                <dd class="opt"><input type="text" class="input-txt" name="points_goods_storage" value="<?=@($data['points_goods_storage'])?>"></dd>
            </dl>
            <dl class="row">
                <dt class="tit">
                    <label>限制每个会员兑换数量</label>
                </dt>
                <dd class="opt">
                    <input type="radio" name="islimit" id="islimit_1" value="1" <?php if($data['points_goods_islimit']){echo 'checked="checked"';} ?> onclick="showlimit();">
                    &nbsp;限制&nbsp;
                    <input type="radio" name="islimit" id="islimit_0" value="0" <?php if(!$data['points_goods_islimit']){echo 'checked="checked"';} ?> onclick="showlimit();">
                    &nbsp;不限制<span class="err"></span>
                    <p class="notic"></p>
                </dd>
            </dl>
            <dl class="row" id="limitnum_div" style="display: none;">
                <dt class="tit">
                    <label for="limitnum"> 每个会员限兑数量 </label>
                </dt>
                <dd class="opt">
                    <input type="text" name="limitnum" id="limitnum" class="input-txt" value="<?=@$data['points_goods_limitnum']?>" placeholder="请填写不大于库存的正整数">
                    <p class="notic"></p>
                </dd>
            </dl>
            <!--<dl class="row">
                <dt class="tit">
                    <label> 限制兑换时间 </label>
                </dt>
                <dd class="opt">
                    <input type="radio" name="islimittime" id="islimittime_1" value="1" onclick="showlimittime();">
                    &nbsp;限制&nbsp;
                    <input type="radio" name="islimittime" id="islimittime_0" value="0" checked="checked" onclick="showlimittime();">
                    &nbsp;不限制          <p class="notic"></p>
                </dd>
            </dl> -->
            <dl class="row" name="limittime_div" style="display: none;">
                <dt class="tit">
                    <label> 开始时间 </label>
                </dt>
                <dd class="opt">
                    <input type="text" name="starttime" id="starttime" class="input-txt hasDatepicker" style="width:100px;" value="<?=@($data['points_goods_starttime'])?>" readonly="readonly">
                    <p class="notic"></p>
                </dd>
            </dl>

            <dl class="row" name="limittime_div" style="display: none;">
                <dt class="tit">
                    <label> 结束时间 </label>
                </dt>
                <dd class="opt">
                    <input type="text" name="endtime" id="endtime" class="input-txt hasDatepicker" style="width:100px;" value="<?=@($data['points_goods_endtime'])?>" readonly="readonly">
                    <p class="notic"></p>
                </dd>
            </dl>
            <dl class="row">
                <dt class="tit">
                    <label> 限制参与兑换的会员级别 </label>
                </dt>
                <dd class="opt">
                    <select name="limitgrade" id="limitgrade">
                        <?php if($data['user_grade']){
                            foreach($data['user_grade'] as $key=>$grade){
                                ?>
                                <option value="<?=$grade['user_grade_id']?>" <?=@($data['points_goods_limitgrade']==$grade['user_grade_id']?'selected':'')?>><?=$grade['user_grade_name']?></option>
                            <?php } } ?>
                    </select>
                    <span class="err"></span>
                    <p class="notic">当会员兑换积分商品时，需要达到该级别或者以上级别后才能参与兑换</p>
                </dd>
            </dl>

            <dl class="row">
                <dt class="tit">
                    <label> 是否上架 </label>
                </dt>
                <dd class="opt">
                    <div class="onoff">
                        <label for="showstate_1" class="cb-enable <?=(@$data['points_goods_shelves'] == 1 ? 'selected' : '')?>"><span>是</span></label>
                        <label for="showstate_0" class="cb-disable <?=(@$data['points_goods_shelves'] == 0 ? 'selected' : '')?>"><span>否</span></label>
                        <input id="showstate_1" name="points_goods_shelves" <?=(@$data['points_goods_shelves']==1 ? 'checked' : '')?> value="1" type="radio">
                        <input id="showstate_0" name="points_goods_shelves" <?=(@$data['points_goods_shelves']==0 ? 'checked' : '')?> value="0" type="radio">
                    </div>
                    <p class="notic"></p>
                </dd>
            </dl>
            <dl class="row">
                <dt class="tit">
                    <label> 是否推荐 </label>
                </dt>
                <dd class="opt">
                    <div class="onoff">
                        <label for="commendstate_1" class="cb-enable <?=(@$data['points_goods_recommend'] == 1 ? 'selected' : '')?>"><span>是</span></label>
                        <label for="commendstate_0" class="cb-disable <?=(@$data['points_goods_recommend'] == 0 ? 'selected' : '')?>"><span>否</span></label>
                        <input id="commendstate_1" name="points_goods_recommend" <?=(@$data['points_goods_recommend']==1 ? 'checked' : '')?>  value="1" type="radio">
                        <input id="commendstate_0" name="points_goods_recommend" <?=(@$data['points_goods_recommend']==0 ? 'checked' : '')?>  value="0" type="radio">
                    </div>
                    <p class="notic"></p>
                </dd>
            </dl>
            <dl class="row" id="forbidreason_div" style="display: none;">
                <dt class="tit">
                    <label for="forbidreason"> 禁售原因 </label>
                </dt>
                <dd class="opt">
                    <textarea name="forbidreason" id="forbidreason" rows="6" class="tarea"></textarea>
                    <span class="err"></span>
                    <p class="notic"></p>
                </dd>
            </dl>

            <dl class="row">
                <dt class="tit">
                    <label for="keywords"> 关键字 </label>
                </dt>
                <dd class="opt">
                    <input type="text" name="keywords" id="keywords" class="input-txt" value="<?=@$data['points_goods_keywords']?>">
                    <span class="err"></span>
                    <p class="notic"></p>
                </dd>
            </dl>
            <dl class="row">
                <dt class="tit">
                    <label for="description"> SEO描述</label>
                </dt>
                <dd class="opt">
                    <textarea class="tarea" rows="6" id="description" name="description"></textarea>
                    <span class="err"></span>
                    <p class="notic"></p>
                </dd>
            </dl>

            <dl class="row">
                <dt class="tit">
                    <label for="sort">礼品排序</label>
                </dt>
                <dd class="opt">
                    <input type="text" name="points_goods_sort" id="sort" class="input-txt" value="<?=@$data['points_goods_sort']?>">
                    <span class="err"></span>
                    <p class="notic">注：数值越小排序越靠前</p>
                </dd>
            </dl>
            <dl class="row">
                <dt class="tit">礼品描述</dt>
                <dd class="opt">
                    <textarea id="container"  style="width:750px;height:300px;" name="points_goods_body" type="text/plain" ><?=@$data['points_goods_body']?></textarea>
                    <!-- 配置文件 -->
                    <script type="text/javascript" src="<?= $this->view->js_com ?>/ueditor/ueditor.config.js"></script>
                    <!-- 编辑器源码文件 -->
                    <script type="text/javascript" src="<?= $this->view->js_com ?>/ueditor/ueditor.all.js"></script>
                    <script type="text/javascript" src="<?= $this->view->js_com ?>/upload/addCustomizeButton.js"></script>
                    <!-- 实例化编辑器 -->
                    <script type="text/javascript">
                        var ue = UE.getEditor('container', {
                            toolbars: [
                                [
                                    'bold', 'italic', 'underline', 'forecolor', 'backcolor', 'justifyleft', 'justifycenter', 'justifyright', 'insertunorderedlist', 'insertorderedlist', 'blockquote',
                                    'emotion', 'link', 'removeformat', 'rowspacingtop', 'rowspacingbottom', 'lineheight', 'paragraph', 'fontsize', 'inserttable', 'deletetable', 'insertparagraphbeforetable',
                                    'insertrow', 'deleterow', 'insertcol', 'deletecol', 'mergecells', 'mergeright', 'mergedown', 'splittocells', 'splittorows', 'splittocols'
                                ]
                            ],
                            autoClearinitialContent: false,
                            //关闭字数统计
                            wordCount: false,
                            //关闭elementPath
                            elementPathEnabled: false
                        });
                    </script>
                    <link href="<?= $this->view->css_com ?>/jquery/plugins/dialog/green.css" rel="stylesheet">
                    <script type="text/javascript" src="<?= $this->view->js_com ?>/plugins/jquery.dialog.js"></script>

                    <span class="err"></span>
                    <p class="notic"></p>
                </dd>
            </dl>
            <div class="bot"><a href="javascript:void(0);" class="ui-btn ui-btn-sp submit-btn" id="submitBtn">确认提交</a></div>
        </form>
    </div>
</div>



<script type="text/javascript" src="<?=$this->view->js_com?>/webuploader.js" charset="utf-8"></script>
<script type="text/javascript" src="<?=$this->view->js?>/models/upload_image.js" charset="utf-8"></script>

<script>
   $(function(){


       var agent = navigator.userAgent.toLowerCase();

       if ( agent.indexOf("msie") > -1 && (version = agent.match(/msie [\d]/), ( version == "msie 8" || version == "msie 9" )) ) {
           new UploadImage({
               thumbnailWidth: 400,
               thumbnailHeight: 400,
               imageContainer: '#points_goods_review',
               uploadButton: '#points_goods_upload',
               inputHidden: '#points_goods_image'
           });
       } else {
           $('#points_goods_upload').on('click', function () {
               $.dialog({
                   title: '图片裁剪',
                   content: "url: <?= Yf_Registry::get('url') ?>?ctl=Index&met=cropperImage&typ=e",
                   data: {SHOP_URL:SHOP_URL,width:400,height:400 , callback: callback },    // 需要截取图片的宽高比例
                   width: '800px',
                   height:$(window).height()*0.9,
                   lock: true,
                   zIndex: 1999
               })
           });

           function callback ( respone , api ) {
               $('#points_goods_review').attr('src', respone.url);
               $('#points_goods_image').attr('value', respone.url);
               api.close();
           }
       }
   })

    //限制兑换数量
    function showlimit(){
        var islimit = $(":radio[name=islimit]:checked").val();
        if(islimit == '1'){
            $("#limitnum_div").show();
            $("#limitnum").val("<?=@($data['points_goods_limitnum'])?>");
        }else{
            $("#limitnum_div").hide();
            $("#limitnum").val('1');//为了减少提交表单的验证，所以添加一个虚假值
        }
    }
    function showforbidreason(){
        var forbidstate = $(":radio[name=forbidstate]:checked").val();
        if(forbidstate == '1'){
            $("#forbidreason_div").show();
        }else{
            $("#forbidreason_div").hide();
        }
    }

   //限制兑换时间
    function showlimittime(){
        var islimit = $(":radio[name=islimittime]:checked").val();
        if(islimit == '1'){
            $("[name=limittime_div]").show();
            $("#starttime").val('');
            $("#endtime").val('');
        }else{
            $("[name=limittime_div]").hide();
            $("#starttime").val('2016-05-18');
            $("#endtime").val('2016-05-18');
        }
    }

    $(function(){

        showlimit();
        showforbidreason();
        showlimittime();

        $('#starttime').datetimepicker({dateFormat: 'yy-mm-dd'});
        $('#endtime').datetimepicker({dateFormat: 'yy-mm-dd'});
        
        var t = $('input[name="operate"]').val();
        $('#points_goods_form').validator({

            ignore: ":hidden",
            theme: "yellow_right",
            timely: 1,
            stopOnError: true,
            debug:true,
            rules: {
                goodsNameMaxLength: [/^[\u4e00-\u9fa5]{1,32}$/, "礼品名称最长32个中文字符"],
            },
            fields: {
                points_goods_name: "required;goodsNameMaxLength",
                points_goods_price: "required;digital[+]",
                points_goods_points: "required;integer[+]",
                points_goods_serial: "required;",
                //库存不能为0
                points_goods_storage: "required;integer[+]"
            },
            display: function (a)
            {
                return $(a).closest(".row-item").find("label").text().replace(":","");
            },
            valid: function(form){
                //限购状态下限购数量必须是不大于库存数量的正整数
                if($('#limitnum').val() == parseInt($('#limitnum').val()))
                {
                    var limitnum_val = parseInt($('#limitnum').val());
                    var storage_val = parseInt($('input[name=points_goods_storage]').val());
                    if(limitnum_val > storage_val || limitnum_val <= 0)
                    {
                        parent.parent.Public.tips({type: 1, content: "限兑数量有误！"});
                        $('#limitnum').val('').focus();
                        return false;
                    }
                }
                else
                {
                    parent.parent.Public.tips({type: 1, content: "限兑数量不能是小数！"});
                    $('#limitnum').val('').focus();
                    return false;
                }

                Public.ajaxPost( SITE_URL + "?ctl=Promotion_Points&typ=json&met=addPointsGoods", $(form).serialize(), function (e)
                {
                    if (200 == e.status)
                    {
                        parent.parent.Public.tips({content:  "成功！"});
                        callback && "function" == typeof callback && callback(e.data, t, window)
                    }
                    else
                    {
                        parent.parent.Public.tips({type: 1, content: "失败！" + e.msg})
                    }
                })
            }
        }).on("click", "a#submitBtn", function (e)
        {
            $(e.delegateTarget).trigger("validate");
        });
    });

   var curRow, curCol, curArrears, $grid = $("#grid"),  $_form = $("#manage-form"), api = frameElement.api, oper = api.data.oper, rowData = api.data.rowData || {}, callback = api.data.callback;

</script>

<?php
include $this->view->getTplPath() . '/' . 'footer.php';
?>
