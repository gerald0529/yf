<?php if (!defined('ROOT_PATH')){exit('No Permission');}?>
<?php
include $this->view->getTplPath() . '/' . 'seller_header.php';
?>
	<link href="<?= $this->view->css ?>/seller_center.css" rel="stylesheet" type="text/css">
	<!--<script type="text/javascript" src="http://b2b2c.bbc-builder.com/tesa/shop/resource/js/seller.js"></script>-->
	<script type="text/javascript" src="<?= $this->view->js_com ?>/waypoints.js"></script>
	<script type="text/javascript" src="<?= $this->view->js_com ?>/plugins/jquery.ui.js"></script>
	<script type="text/javascript" src="<?= $this->view->js_com ?>/plugins/validator/jquery.validator.js"></script>
	<script type="text/javascript" src="<?= $this->view->js ?>/decoration/common.js"></script>
	<script type="text/javascript" src="<?=$this->view->js?>/decoration/decoration/dialog.js" id="dialog_js" charset="utf-8"></script>
	<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
	<!--[if lt IE 9]>
			<script src="<--?= $this->view->js ?>/decoration/html5shiv.js"></script>
			<script src="<--?= $this->view->js ?>/decoration/respond.min.js"></script>
	<![endif]-->
        <script type="text/javascript" src="<?=$this->view->js_com?>/webuploader.js" charset="utf-8"></script>
        <script type="text/javascript" src="<?=$this->view->js_com?>/upload/upload_image.js" charset="utf-8"></script>
        <link href="<?= $this->view->css_com ?>/webuploader.css" rel="stylesheet" type="text/css">
	<script type="text/javascript" src="<?= $this->view->js_com?>/plugins/ToolTip.js"></script>
<style>
    .webuploader-container div{
       min-width:80px !important;
       min-height:26px !important;
    }
</style>
	<div id="append_parent"></div>
	<div id="ajaxwaitid"></div>
	
	<div class="wrapper">
		<link href="<?= $this->view->css ?>/shop_custom.css" rel="stylesheet" type="text/css">
	
		
                    <div class="ncsc-decoration-layout">
              <div class="ncsc-decoration-menu" id="waypoints">
                <div class="title"><i class="icon"></i>
                  <h3><?=__('店铺装修选项')?></h3>
                  <h5><?=__('店铺首页模板设计操作')?></h5>
                </div>
                <ul class="menu">
                  <li><a id="btn_edit_background" href="javascript:void(0);"><i class="background"></i><?=__('编辑背景')?></a></li>
                  <li><a id="btn_edit_head" href="javascript:void(0);"><i class="head"></i><?=__('编辑头部')?></a></li>
                  <li><a id="btn_add_block" href="javascript:void(0);"><i class="block"></i><?=__('添加布局块')?></a></li>
                  <li><a id="btn_close" href="javascript:void(0);"><i class="close"></i><?=__('完成退出')?></a></li>
                </ul>
                <div class="faq"><?=__('下方区域为1200像素宽度即时编辑区域；“添加布局块”后选择模块类型进行详细设置；内容将实时保存，设置完成后直接选择“完成退出”。')?></div>
              </div>
              <div id="store_decoration_content" style="<?php echo $decoration_detail['decoration_background_style'];?>">
                <div id="decoration_banner" class="ncsl-nav-banner"> </div>
                <div id="decoration_nav" class="ncsl-nav">
                  <div class="ncs-nav">
                    <ul>
                      <li class="active"><a href="javascript:void(0);"><span><?=__('店铺首页')?><i></i></span></a></li>
                    </ul>
                  </div>
                </div>
                <div id="store_decoration_area" class="store-decoration-page">
                  <?php if(!empty($decoration_detail['block_list']) && is_array($decoration_detail['block_list'])) {?>
                  <?php foreach($decoration_detail['block_list'] as $block) {?>
                     <?php require('store_decoration_block.php');?>
                  <?php } ?>
                  <?php } ?>
                </div>
              </div>
            </div>
	<!-- 背景编辑对话框 -->
<div id="dialog_edit_background" class="eject_con dialog-decoration-edit" style="display:none;">
  <dl>
    <dt><?=__('背景颜色：')?></dt>
    <dd>
      <input id="txt_background_color" class="text w80" type="text" name="" value="<?php echo $decoration_detail['decoration_setting']['background_color'];?>" maxlength="7">
      <p class="hint"><?=__('设置背景颜色请使用十六进制形式(#XXXXXX)，默认留空为白色背景。')?></p>
    </dd>
  </dl>
  <dl>
    <dt><?=__('背景图：')?></dt>
    <dd>

        <div  id='icon-upload-alt'  ><i class="iconfont icon-tupianshangchuan" style="color:#fff;"></i><?=__('图片上传')?></div></p>
      <div id="div_background_image"  class="background-image-thumb"> <img id="img_background_image" src="<?php echo $decoration_detail['decoration_setting']['background_image_url'];?>" alt="">
        <input id="txt_background_image" type="hidden" name="" value="<?php echo $decoration_detail['decoration_setting']['background_image'];?>">
        <a id="btn_del_background_image" class="del" href="javascript:void(0);" title="<?=__('移除背景图')?>">X</a></div>
    </dd>
  </dl>
  <dl>
    <dt><?=__('背景图定位：')?></dt>
    <dd>
      <input id="txt_background_position_x" class="text w40" type="text" value="<?php echo $decoration_detail['decoration_setting']['background_position_x'];?>"><label class="add-on">X</label>
      &#12288;&#12288;
      <input id="txt_background_position_y" class="text w40" type="text" value="<?php echo $decoration_detail['decoration_setting']['background_position_y'];?>"><label class="add-on">Y</label>
      <p class="hint"><?=__('设置背景图像的起始位置，可以使用px，和 % 设置。')?></p>
    </dd>
  </dl>
  <dl>
    <dt><?=__('背景图填充方式：')?></dt>
    <dd>
      <?php $repeat = $decoration_detail['decoration_setting']['background_image_repeat'];?>
      <input id="input_no_repeat" type="radio" value="no-repeat" name="background_repeat" <?php if(empty($repeat) || $repeat == 'no-repeat') {echo 'checked';}?>>
      <label for="input_no_repeat"><?=__('不重复')?></label>
      <input id="input_repeat" type="radio" value="repeat" name="background_repeat" <?php if($repeat == 'repeat') {echo 'checked';}?>>
      <label for="input_repeat"><?=__('平铺')?></label>
      <input id="input_repeat_x" type="radio" value="repeat-x" name="background_repeat" <?php if($repeat == 'repeat-x') {echo 'checked';}?>>
      <label for="input_repeat_x"><?=__('x轴平铺')?></label>
      <input id="input_repeat_y" type="radio" value="repeat-y" name="background_repeat" <?php if($repeat == 'repeat-y') {echo 'checked';}?>>
      <label for="input_repeat_y"><?=__('y轴平铺')?></label>
    </dd>
  </dl>
  <dl>
    <dt><?=__('背景滚动模式：')?></dt>
    <dd>
      <input id="txt_background_attachment" class="text w80" type="text" value="<?php echo $decoration_detail['decoration_setting']['background_attachment'];?>">
      <p class="hint"><?=__('设置背景随屏幕滚动或固定，例如："scroll"或"fixed"。')?> </p>
    </dd>
  </dl>
  <div class="bottom">
    <label class="submit-border"><a id="btn_save_background" class="submit bbc_seller_submit_btns" href="javascript:void(0);"><?=__('保存')?></a></label>
  </div>
</div>
<div id="dialog_edit_head" class="eject_con dialog-decoration-edit" style="display:none;">
  <div id="dialog_edit_head_tabs">
    <ul>
      <li><a href="#dialog_edit_head_tabs_1"><?=__('头部导航')?></a></li>
      <li><a href="#dialog_edit_head_tabs_2"><?=__('头部图片')?></a></li>
    </ul>
    <div id="dialog_edit_head_tabs_1">
      <dl>
        <dt><?=__('是否显示：')?></dt>
        <dd>
             <?php if(!empty($decoration_detail['decoration_nav']['display'])){?>
          <label for="decoration_nav_display_true">
            <input id="decoration_nav_display_true" type="radio" class="radio" value="true" name="decoration_nav_display" <?php if($decoration_detail['decoration_nav']['display'] == 'true') { echo 'checked'; }?>>
            <?=__('显示')?></label>
          <label for="decoration_nav_display_false">
            <input id="decoration_nav_display_false" type="radio" class="radio" value="false" name="decoration_nav_display" <?php if($decoration_detail['decoration_nav']['display'] == 'false') { echo 'checked'; }?>>
            <?=__('不显示')?></label>
             <?php }else{ ?>
                <label for="decoration_nav_display_true">
            <input id="decoration_nav_display_true" type="radio" class="radio" value="true" name="decoration_nav_display" checked >
            <?=__('显示')?></label>
          <label for="decoration_nav_display_false">
            <input id="decoration_nav_display_false" type="radio" class="radio" value="false" name="decoration_nav_display" >
            <?=__('不显示')?></label>
              <?php }?>
   
          <p class="hint"><?=__('“头部导航”为店铺首页店铺导航条，可设置是否显示， 默认为显示。')?></p>
        </dd>
      </dl>
      <dl>
        <dt><?=__('导航样式：')?></dt>
        <dd>
            <textarea id="decoration_nav_style" class="w400" style="height:100px"><?php echo $decoration_detail['decoration_nav']['style'];?></textarea>
          <p> <a id="btn_default_nav_style" class="ncsc-btn-mini" href="javascript:void(0);"><i class="icon-refresh"></i><?=__('恢复默认')?></a> </p>
          <p class="hint"><?=__('导航条对应CSS文件，如修改后显示效果不符可恢复默认值。')?></p>
        </dd>
      </dl>
      <div class="bottom">
        <label class="submit-border"><a id="btn_save_decoration_nav" class="submit bbc_seller_submit_btns" href="javascript:void(0);"><?=__('保存')?></a></label>
      </div>
    </div>
    <div id="dialog_edit_head_tabs_2">
      <dl>
        <dt><?=__('是否显示：')?></dt>
        <dd>
          <?php if(!empty($decoration_detail['decoration_banner']['display'])){?>
    
          <label for="decoration_banner_display_true">
            <input id="decoration_banner_display_true" type="radio" class="radio" value="true" name="decoration_banner_display" <?php if($decoration_detail['decoration_banner']['display'] == 'true') { echo 'checked'; }?>>
            <?=__('显示')?></label>
          <label for="decoration_banner_display_false">
            <input id="decoration_banner_display_false" type="radio" class="radio" value="false" name="decoration_banner_display" <?php if($decoration_detail['decoration_banner']['display'] == 'false') { echo 'checked'; }?>>
            <?=__('不显示')?></label>
      
            <?php }else{ ?>
             <label for="decoration_banner_display_true">
            <input id="decoration_banner_display_true" type="radio" class="radio" value="true" name="decoration_banner_display"  >
           <?=__('显示')?> </label>
          <label for="decoration_banner_display_false">
            <input id="decoration_banner_display_false" type="radio" class="radio" value="false" name="decoration_banner_display" checked>
            <?=__('不显示')?></label>
            <?php }?>
          <p class="hint"><?=__('“头部图片”为店铺首页最上方图片，可设置是否显示。')?></p>
        </dd>
      </dl>
      <dl>
        <dt>图片：</dt>
        <dd>
          <div id="div_banner_image"  class="background-image-thumb"> <img id="img_banner_image" src="<?php echo $decoration_detail['decoration_banner']['image_url'];?>" alt="">
              <input id="txt_banner_image" type="hidden" name="" value="<?php if(!empty($decoration_detail['decoration_banner']['image'])){ echo $decoration_detail['decoration_banner']['image'];};?>">
            <a id="btn_del_banner_image" class="del" href="javascript:void(0);" title="<?=__('移除')?>">X</a> </div>
     
             <div  id='ncsc-upload-btn'  ><i class="iconfont icon-tupianshangchuan" style="color:#fff;" ></i><?=__('图片上传')?></div></p>
          <p class="hint"><?=__('选择上传头部图片，建议使用宽度为1200像素，大小不超过1M的gif\jpg\png格式图片。')?></p>
        </dd>
      </dl>
      <div class="bottom">
        <label class="submit-border"><a id="btn_save_decoration_banner" class="submit bbc_seller_submit_btns" href="javascript:void(0);"><?=__('保存设置')?></a></label>
      </div>
    </div>
  </div>
</div>
		<!-- 选择模块对话框 -->
		<div id="dialog_select_module" class="dialog-decoration-module" style="display:none;">
		  <ul>
			<li><a nctype="btn_show_module_dialog" data-module-type="slide" href="javascript:void(0);"><i class="slide"></i>
			  <dl>
				<dt><?=__('图片和幻灯')?></dt>
				<dd><?=__('添加图片和可切换幻灯')?></dd>
			  </dl>
			  </a></li>
<!--			<li><a nctype="btn_show_module_dialog" data-module-type="hot_area" href="javascript:void(0);"><i class="hotarea"></i>
			  <dl>
				<dt><--?=__('图片热点')?></dt>
				<dd><--?=__('添加图片并设置热点区域链接')?></dd>
			  </dl>
			  </a></li>-->
			<li> <a nctype="btn_show_module_dialog" data-module-type="goods" href="javascript:void(0);"><i class="goods"></i>
			  <dl>
				<dt><?=__('店铺商品')?></dt>
				<dd><?=__('选择添加店铺内的在售商品')?></dd>
			  </dl>
			  </a> </li>
			<li> <a nctype="btn_show_module_dialog" data-module-type="html" href="javascript:void(0);"><i class="html"></i>
			  <dl>
				<dt><?=__('自定义')?></dt>
				<dd><?=__('使用编辑器自定义编辑html')?></dd>
			  </dl>
			  </a> </li>
		  </ul>
		</div>
		<!-- 自定义模块编辑对话框 -->
		<div id="dialog_module_html" class="eject_con dialog-decoration-edit" style="display:none;">
                    <div  style="max-height: 700px;overflow: auto;">
                    <div class="alert">
                          <ul>
                            <li><?=__('1. 可将预先设置好的网页文件内容复制粘贴到文本编辑器内，或直接在工作窗口内进行编辑操作。')?></li>
                            <li><?=__('2. 默认为可视化编辑，选择第一个按钮切换到html代码编辑。css文件可以Style=“...”形式直接写在对应的html标签内。')?></li>
                          </ul>
                    </div>
  <!--		  <textarea id="module_html_editor" name="module_html_editor" style=" width:1016px; height:400px; visibility:hidden;"></textarea>-->
                     <script id="module_html_editor" name="module_html_editor" style=" width:1016px; height:400px;" type="text/plain">
                      </script>
                    <div class="bottom">
                          <label class="submit-border"><a id="btn_save_module_html" class="submit bbc_seller_submit_btns" href="javascript:void(0);"><?=__('保存设置')?></a></label>
                    </div>
		</div>
               </div>
                <!-- 幻灯模块编辑对话框 -->
<div id="dialog_module_slide" class="eject_con dialog-decoration-edit" style="display:none;">
  <div class="alert">
    <ul>
      <li><?=__('1. 可选择图片以全屏或非全屏形式显示，')?><strong class="bbc_seller_color"><?=__('且必须设定图片的高度')?></strong><?=__('，否则将无法正常浏览。')?></li>
      <li><?=__('2. 上传单张图片时系统默认显示为')?><strong class="bbc_seller_color"><?=__('“图片链接”')?></strong><?=__('形式显示，如一次上传多图将以')?><strong class="bbc_seller_color"><?=__('“幻灯片”')?></strong><?=__('形式显示。')?></li>
    </ul>
  </div>
  <div id="module_slide_html" class="slide-upload-thumb">
    <ul class="module-slide-content">
    </ul>
  </div>
  <h4><?=__('相关设置：')?></h4>
  <dl class="display-set">
    <dt><?=__('显示设置：')?></dt>
    <dd><span><?=__('全屏显示')?>
      <input id="txt_slide_full_width" type="checkbox" class="checkobx" name="">
      </span><span><strong class="orange">*</strong> <?=__('显示高度')?>
      <input id="txt_slide_height" type="text" class="text w40" value=""><em class="add-on"><?=__('像素')?></em></span>
      <p><a id="btn_add_slide_image" class="ncsc-btn mt5 lblock bbc_img_btns" href="javascript:void(0);"><i class="icon-plus"></i><?=__('添加图片')?></a></p>
    </dd>
  </dl>
  <div id="div_module_slide_upload" style="display:none;">
    <form action="">
      <dl>
        <dt><?=__('图片上传：')?></dt>
        <dd>

            <img style="max-width:400px;max-height: 150px;" id="img_slide_image" src="" alt="" > 
               <input id="input_slide_image" type="hidden" name="" value="">
              <p id="icon-slide-alt" ><i class="iconfont icon-tupianshangchuan" style="color:#fff;"></i><?=__('图片上传')?></p>
          <p class="hint"><?=__('请上传宽度为1200像素的jpg/gif/png格式图片。')?></p>
        </dd>
      </dl>
      <dl>
        <dt><?=__('图片链接：')?></dt>
        <dd>
          <input id="module_slide_url" class="text w400" type="text">
          <p class="hint"><?=__('请输入以http://为开头的图片链接地址，仅作为图片使用时请留空此选项')?></p>
          <p class="mt5"><a id="btn_save_add_slide_image" class="ncsc-btn ncsc-btn-acidblue alert_btns_reset mr10 bbc_seller_btns" href="javascript:void(0);"><?=__('添加')?></a> <a id="btn_cancel_add_slide_image" class="ncsc-btn ncsc-btn-orange alert_btns_reset bbc_gray_btns" href="javascript:void(0);"><?=__('取消')?></a></p>
        </dd>
      </dl>
    </form>
  </div>
  <div class="bottom">
    <label class="submit-border"><a id="btn_save_module_slide" class="submit bbc_seller_submit_btns" href="javascript:void(0);"><?=__('保存设置')?></a></label>
  </div>
</div>

		<!-- 图片热点模块编辑对话框 -->
		<div id="dialog_module_hot_area" class="eject_con dialog-decoration-edit" style="display:none;">
		  <div class="alert">
			<ul>
			  <li><?=__('1. 在已上传的图片范围拖动鼠标形成选择区域，对该区域添加以http://格式开头的链接地址并点击“添加网址”按钮生效。')?></li>
			  <li><?=__('2. 对已添加的热点可做编辑链接地址修改，如需调整位置，请删除该热点区域并保存，之后重新选择添加。')?></li>
			</ul>
		  </div>
		  <div id="div_module_hot_area_image" class="hot-area-image" style="position: relative;"></div>
		  <ul id="module_hot_area_select_list" class="hot-area-select-list">
		  </ul>
		  <h4><?=__('相关设置：')?></h4>
		  <form action="">
			<dl>
			  <dt><?=__('图片上传：')?></dt>
			  <dd>
      
                                <img style="max-width:400px;max-height: 150px;" id="img_hot_image" src="" alt="" > 
                                   <input id="input_hot_image" type="hidden" name="" value="">
                                  <p id="icon-hot-alt"><i class="iconfont icon-tupianshangchuan" style="color:#fff;"></i><?=__('图片上传')?></p>

				<p class="hint"><?=__('选择上传jpg/gif/png格式图片，建议宽度不超过1000像素，高度不超过400像素，如超出此范围，请先自行对图片进行裁切调整。')?></p>
			  </dd>
			</dl>
		  </form>
		  <dl>
			<dt><?=__('热点链接设置：')?></dt>
			<dd>
			  <input id="module_hot_area_url" class="text w400" type="text" />
			  <a id="btn_module_hot_area_add" class="ncbtn ml5" href="javascript:void(0);"><i class="icon-anchor"></i><?=__('添加网址')?></a>
			  <p class="hint"><?=__('在输入框中添加以“http://”格式开头的热点区域跳转网址。')?></p>
			</dd>
		  </dl>
		  <div class="bottom">
			<label class="submit-border"><a id="btn_save_module_hot_area" class="submit bbc_seller_submit_btns" href="javascript:void(0);"><?=__('保存设置')?></a></label>
		  </div>
		</div>
		<!-- 商品模块编辑对话框 -->
		<div id="dialog_module_goods" class="eject_con dialog-decoration-edit" style="display:none;">
		  <div class="alert">
			<ul>
			  <li><?=__('1. 搜索店铺内在售商品并“选择添加”，设置窗口上部将出现已选择的商品列表，也可对其进行“取消选择”操作，点击保存设置后生效。')?></li>
			  <li><?=__('2. 当已选择的商品超过10个时，系统默认未全部显示，可通过在已选区域滚动鼠标或拉动侧边条进行查看及操作。')?></li>
			</ul>
		  </div>
		  <div id="decorationGoods">
			<ul id="div_module_goods_list" class="goods-list">
			</ul>
		  </div>
		  <h4 class="mt10"><?=__('店铺在售商品选择')?></h4>
		  <div class="decoration-search-goods">
                      <div class="search-bar" style="height:35px;line-height: 33px;"><?=__('输入商品关键字：')?>
			  <input id="txt_goods_search_keyword" type="text" class="text w200 vm" name="">
                          <a id="btn_module_goods_search" class="ncbtn button" href="javascript:void(0);"><i style ="color:#000000;"class="iconfont icon-btnsearch"></i><?=__('搜索')?></a><span class="ml10" style="margin-left:10px;color:orange;margin-top: 5px;"><?=__('小提示： 留空搜索显示10个商品，可做搜索操作。')?></span></div>
			<div id="div_module_goods_search_list"></div>
		  </div>
                  <div class="bottom" style="clear: both;"><label class="submit-border"><a id="btn_save_module_goods" class="submit bbc_seller_submit_btns" href="javascript:void(0);"><?=__('保存设置')?></a></label></div>
		</div>
		<!-- 幻灯模板 --> 
		<script id="template_module_slide_image_list" type="text/html">
		<li data-image-name="<%=image_name%>" data-image-url="<%=image_url%>" data-image-link="<%=image_link%>">
		<span><img src="<%=image_url%>"></span>
		<a nctype="btn_del_slide_image" href="javascript:void(0);" title="<?=__('删除')?>">X</a>
		</li>
		</script> 
		<!-- 热点块控制模板 --> 
		<script id="template_module_hot_area_list" type="text/html">
		<li data-hot-area-link="<%=link%>" data-hot-area-position="<%=position%>">
		<i></i>
		<p><?=__('热点区域')?><%=index%></p>
		<p><a nctype="btn_module_hot_area_select" data-hot-area-position="<%=position%>" class="ncbtn-mini ncbtn-aqua" href="javascript:void(0);"><?=__('选择')?></a>
		<a data-index="<%=index%>" nctype="btn_module_hot_area_del" class="ncbtn-mini ncbtn-grapefruit" href="javascript:void(0);"><?=__('删除')?></a></p>
		</li>
		</script> 
		<!-- 热点块标识模板 --> 
		<script id="template_module_hot_area_display" type="text/html">
		<div class="store-decoration-hot-area-display" style="width:<%=width%>px;height:<%=height%>px;position:absolute;left:<%=left%>px;top:<%=top%>px;border:1px solid #cccccc;" id="hot_area_display_<%=index%>">热点区域<%=index%></div>
		</li>
		</script> 
<script type="text/javascript" src="<?= $this->view->js_com ?>/plugins/template.min.js" charset="utf-8"></script> 
<script type="text/javascript" src="<?= $this->view->js_com ?>/upload/jquery.iframe-transport.js" charset="utf-8"></script> 
<script type="text/javascript" src="<?= $this->view->js_com ?>/upload/jquery.ui.widget.js" charset="utf-8"></script> 
<script type="text/javascript" src="<?= $this->view->js_com  ?>/upload/jquery.fileupload.js" charset="utf-8"></script> 

<link media="all" rel="stylesheet" href="<?= $this->view->js_com ?>/jquery.imgareaselect/imgareaselect-animated.css" type="text/css" />
<script type="text/javascript" src="<?= $this->view->js_com ?>/jquery.imgareaselect/jquery.imgareaselect.min.js"></script> 
<script src="<?= $this->view->js_com ?>/plugins/jquery.poshytip.min.js"></script> 
<script type="text/javascript"> 
    //定义api常量
    var DECORATION_ID = <?=$decoration_detail["decoration_id"]?> ;
    var URL_DECORATION_ALBUM_UPLOAD = "<?=Yf_Registry::get('url')?>?ctl=Seller_Shop_Decoration&met=decoration_album_upload";
    var URL_DECORATION_BACKGROUND_SETTING_SAVE = "<?=Yf_Registry::get('url')?>?ctl=Seller_Shop_Decoration&met=decorationBackgroundSettingSave";
    var URL_DECORATION_BANNER_SAVE = "<?=Yf_Registry::get('url')?>?ctl=Seller_Shop_Decoration&met=decorationBannerSave&typ=json"; 
    var URL_DECORATION_BLOCK_ADD = "<?=Yf_Registry::get('url')?>?ctl=Seller_Shop_Decoration&met=decorationBlockAdd&typ=json";
    var URL_DECORATION_BLOCK_DEL = "<?=Yf_Registry::get('url')?>?ctl=Seller_Shop_Decoration&met=decorationBlockDel&typ=json"; ;
    var URL_DECORATION_BLOCK_SAVE =  "<?=Yf_Registry::get('url')?>?ctl=Seller_Shop_Decoration&met=decorationBlockSave&typ=json"; 
    var URL_DECORATION_BLOCK_SORT = "<?=Yf_Registry::get('url')?>?ctl=Seller_Shop_Decoration&met=decorationBlockSort&typ=json"; 
    var URL_DECORATION_GOODS_SEARCH = "<?=Yf_Registry::get('url')?>?ctl=Seller_Shop_Decoration&met=goodsSearch";

    var URL_DECORATION_NAV_SAVE = "<?=Yf_Registry::get('url')?>?ctl=Seller_Shop_Decoration&met=decorationNavSave&typ=json"; 
    var POSHYTIP = {
        className: 'tip-yellowsimple',
        showTimeout: 1,
        alignTo: 'target',
        alignX: 'top',
        alignY: 'left',
        offsetX: -300,
        offsetY: -5,
        allowTipHover: false
    };


    $(document).ready(function(){
        //浮动导航  waypoints.js
        $("#waypoints").waypoint(function(event, direction) {
            $(this).parent().toggleClass('sticky', direction === "down");
            event.stopPropagation();
        });

        //商品模块已选商品滚动条
        $('#decorationGoods').perfectScrollbar();

		//title提示
    	$('.tip').poshytip(POSHYTIP);
    });		

</script> 
<script type="text/javascript" src="<?= $this->view->js ?>/decoration/decoration/store_decoration.js" charset="utf-8"></script> 
</div>
<script type="text/javascript">
</script>
<script type="text/javascript">
//$(document).ready(function(){
//    //添加删除快捷操作
//    $('[nctype="btn_add_quicklink"]').on('click', function() {
//        var $quicklink_item = $(this).parent();
//        var item = $(this).attr('data-quicklink-act');
//        if($quicklink_item.hasClass('selected')) {
//            $.post("http://b2b2c.bbc-builder.com/tesa/shop/index.php?act=seller_center&op=quicklink_del", { item: item }, function(data) {
//                $quicklink_item.removeClass('selected');
//                $('#quicklink_' + item).remove();
//            }, "json");
//        } else {
//            var count = $('#quicklink_list').find('dd.selected').length;
//            if(count >= 8) {
//                showError('快捷操作最多添加8个');
//            } else {
//                $.post("http://b2b2c.bbc-builder.com/tesa/shop/index.php?act=seller_center&op=quicklink_add", { item: item }, function(data) {
//                    $quicklink_item.addClass('selected');
//                                    }, "json");
//            }
//        }
//    });

</script>
<script>
    //图片上传
    $(function(){
        background_upload = new UploadImage({
            imageContainer: '#img_background_image',
            uploadButton: '#icon-upload-alt',
            inputHidden: '#txt_background_image'
        });

        banner_upload = new UploadImage({
            thumbnailWidth: 1200,
            thumbnailHeight: 390,
            imageContainer: '#img_banner_image',
            uploadButton: '#ncsc-upload-btn',
            inputHidden: '#txt_banner_image'
        });
        
        slide_upload = new UploadImage({
            thumbnailWidth: 1200,
            thumbnailHeight: 390,
            imageContainer: '#img_slide_image',
            uploadButton: '#icon-slide-alt',
            inputHidden: '#input_slide_image'
        });
              hot_upload = new UploadImage({
            thumbnailWidth: 1200,
            thumbnailHeight: 400,
            imageContainer: '#img_hot_image',
            uploadButton: '#icon-hot-alt',
            inputHidden: '#input_hot_image'
        });
        
       
    })
</script>
<div id="cti">
  <div class="wrapper">
    <ul>
          </ul>
  </div>
</div>
<div id="faq">
  <div class="wrapper">
      </div>
</div>
    <!-- 配置文件 -->
<script type="text/javascript" src="<?= $this->view->js_com ?>/ueditor/ueditor.config.js"></script>
    <!-- 编辑器源码文件 -->
<script type="text/javascript" src="<?= $this->view->js_com ?>/ueditor/ueditor.all.js"></script>

<script type="text/javascript" src="<?= $this->view->js_com ?>/upload/addCustomizeButton.js"></script>
    
<script type="text/javascript" src="<?= $this->view->js_com ?>/plugins/jquery.cookie.js"></script>
<!--<link href="<!--?=$this->view->js_com ?>/perfect-scrollbar.min.css" rel="stylesheet" type="text/css">-->
<script type="text/javascript" src="<?= $this->view->js_com ?>/plugins/perfect-scrollbar.min.js"></script>
<script type="text/javascript" src="<?= $this->view->js_com ?>/qtip/jquery.qtip.min.js"></script>
<link href="<?= $this->view->js_com ?>/qtip/jquery.qtip.min.css" rel="stylesheet" type="text/css">
</body>
</html>