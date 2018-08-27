<?php if (!defined('ROOT_PATH')) {exit('No Permission');}?>
<?php
include $this->view->getTplPath() . '/'  . 'header.php';
?>
<link href="<?=$this->view->css?>/index.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="<?=$this->view->css_com?>/jquery/plugins/validator/jquery.validator.css">
<script type="text/javascript" src="<?=$this->view->js_com?>/plugins/validator/jquery.validator.js" charset="utf-8"></script>
<script type="text/javascript" src="<?=$this->view->js_com?>/plugins/validator/local/zh_CN.js" charset="utf-8"></script>
<script type="text/javascript" src="<?=$this->view->js_com?>/jquery.nyroModal.js" charset="utf-8"></script>
</head>
<body>
<style type="text/css">
 
.grade-template-thumb { background-color: #FFF; width: 100px; height: 100px; padding: 4px; border: solid 1px #E6E6E6; margin: 0 20px 0 0; position: relative;}
.grade-template-thumb a { line-height: 0; text-align: center; vertical-align: middle; display: table-cell; *display: block; width: 100px; height: 100px; overflow: hidden;}
.grade-template-thumb a img { max-width: 100px; max-height: 100px; margin-top:expression(100-this.height/2);}
.grade-template-thumb .checked { position: absolute; z-index: 1; top: -2px; left: -2px;}
</style>
<div class="wrapper page">
    <div class="fixed-bar">
        <div class="item-title">
            <div class="subject">
                <h3> 店铺等级 - 选择“店铺默认”可用模板</h3>
                <h5>商城预设店铺等级功能及收费</h5>
            </div>
      
        </div>
    </div>
    <!-- 操作说明 -->
    <p class="warn_xiaoma"><span></span><em></em></p><div class="explanation" id="explanation">
        <div class="title" id="checkZoom"><i class="iconfont icon-lamp"></i>
            <h4 title="提示相关设置操作时应注意的要点">操作提示</h4>
            <span id="explanationZoom" title="收起提示"></span><em class="close_warn iconfont icon-guanbifuzhi"></em></div>
        <ul>
            <li>点击图片可放大查看店铺首页模板预览图。</li>
            <li>模板勾选并提交后，“系统默认”等级所属店铺可选择使用。</li>
        </ul>
    </div>
    <form method="post" id="grade-template" name="form1">
        <input type="hidden" value="<?= $data['grade_temp']['shop_grade_id']?>" name="shop_grade_id">
    <div class="ncap-form-default">
      <dl class="row">
        <dt class="tit">店铺模板预览</dt>
        <dd class="opt">
          <ul class="nc-row">
            <?php
                $shop_url = str_replace('index.php','',Yf_Registry::get('shop_api_url'));
              if($data['temp']){
              foreach ($data['temp'] as $key => $value) {
                $image_url = $shop_url.'shop/static/default/images/template/'.$value['shop_temp_name'].'.jpg'
            ?>
             <li>
                 <div class="grade-template-thumb"> <a class="nyroModal" rel="gal" href="<?=$value['shop_temp_img']?>"><img src="<?=$image_url?>"></a>
                     <input type="checkbox" value="<?=$value['shop_temp_name']?>" name="shop_grade_template[]" <?php if($value['shop_temp_name']=="default"){?>disabled="disabled"<?php } ?> <?php if(in_array($value['shop_temp_name'], $data['grade_temp']['shop_grade_temp'])){ ?>checked="checked" <?php }?>class="checked">
                   <?php if($value['shop_temp_name']=="default"){?><input type="hidden" value="default" name="shop_grade_template[]"><?php } ?> 
              </div>
            </li>
            <?php } } ?>
            </ul>
          <p class="notic"></p>
        </dd>
      </dl>
      <div class="bot"><a href="JavaScript:void(0);" class="ui-btn ui-btn-sp submit-btn" >确认提交</a></div>
    </div>
  </form>
 <script>
//按钮先执行验证再提交表单
	$(function(){

	// 点击查看图片
	$('.nyroModal').nyroModal();
        
        
         $('#grade-template').validator({
            ignore: ':hidden',
            theme: 'yellow_bottom',
            timely: 1,
            stopOnError: true,
        
            valid: function (form)
            {
                parent.$.dialog.confirm('修改立马生效,是否继续？', function ()
                    {
                        Public.ajaxPost(SITE_URL + '?ctl=Shop_Grade&met=editGradeTemp&typ=json', $('#grade-template').serialize(), function (data)
                        {
                            if (data.status == 200)
                            {
                                parent.Public.tips({content: '修改操作成功！'});
                            }
                            else
                            {
                                parent.Public.tips({type: 1, content: data.msg || '操作无法成功，请稍后重试！'});
                            }
                        });
                    },
                    function ()
                    {

                    });
            },
        }).on("click", "a.submit-btn", function (e)
        {
            $(e.delegateTarget).trigger("validate");
        });
});
</script> 


<?php
include $this->view->getTplPath() . '/' . 'footer.php';
?>