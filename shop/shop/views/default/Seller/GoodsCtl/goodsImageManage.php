<?php if (!defined('ROOT_PATH')) {
    exit('No Permission');
}
    include $this -> view -> getTplPath() . '/' . 'seller_header.php';
    // $goods_image_default = Web_ConfigModel ::value('photo_goods_logo');
?>
<link href="./shop/static/common/css/jquery/plugins/dialog/green.css" rel="stylesheet">
<script type="text/javascript" src="<?= $this -> view -> js_com ?>/plugins/jquery.dialog.js"></script>
<script src="<?= $this -> view -> js_com ?>/webuploader.js"></script>
<script src="<?= $this -> view -> js_com ?>/upload/upload_image.js"></script>
<style>
    input[type=file] {
        opacity: 0;
    }
</style>
<div class="">
    <div class="goods">
        <ol class="step fn-clear clearfix add-goods-step">
            <li>
                <i class="icon iconfont icon-icoordermsg"></i>
                <h6><?= __('STEP 1') ?></h6>
                <h2><?= __('选择分类') ?></h2>
                <i class="arrow iconfont icon-btnrightarrow"></i>
            </li>
            <li class="cur">
                <i class="icon iconfont icon-shangjiaruzhushenqing"></i>
                <h6><?= __('STEP 2') ?></h6>
                <h2><?= __('填写信息') ?></h2>
                <i class="arrow iconfont icon-btnrightarrow"></i>
            </li>
            <li>
                <i class="icon iconfont icon-zhaoxiangji bbc_seller_color"></i>
                <h6 class="bbc_seller_color"><?= __('STEP 3') ?></h6>
                <h2 class="bbc_seller_color"><?= __('上传图片') ?></h2>
                <i class="arrow iconfont icon-btnrightarrow"></i>
            </li>
            <li>
                <i class="icon iconfont icon-icoduigou"></i>
                <h6><?= __('STEP 4') ?></h6>
                <h2><?= __('发布成功') ?></h2>
            </li>
            <li>
                <i class="icon iconfont icon-pingtaishenhe"></i>
                <h6><?= __('STEP 5') ?></h6>
                <h2><?= __('平台审核') ?></h2>
            </li>
        </ol>
        <div class="form-style">
            <div class="form-style-left">
                <form method="post" id="form">
                    <?php if (empty($color)) { ?>
                        <table class="image-list-table" cellpadding="5" cellspacing="1">
                            <tbody>
                            <tr>
                                <th colspan="5"><?= __('颜色') ?>:&nbsp;&nbsp;<?= __('无颜色') ?></th>
                            </tr>
                            <tr>
                                <td>
                                    <div class="fore1">
                                        <img id="image_0_0_img" src="">
                                        <input id="image_0_0" type="hidden" name="image[0][0][name]" value="">
                                    </div>
                                    <div class="fore2">
                                        <p>
                                            <i class="icon-ok-circle"></i> <span><?= __('设为主图') ?></span> <input type="hidden" name="image[0][0][default]" value="1">
                                        </p>
                                        <a href="javascript:void(0)" nctype="del" class="del" title="<?= __('移除') ?>" data-id="<?= $data['goods_images'][0]['images_id'] ?>">X</a>
                                    </div>
                                    <div class="fore3 up-label" id="image_0_0_button">
                                        <a href="javascript:void(0)"><?= __('上传') ?></a>
                                    </div>
                                    <div class="fore4">
                                        <?= __('排序') ?>: <input class="text" maxlength="1" value="0" type="text" name="image[0][0][displayorder]">
                                    </div>
                                </td>
                                <td>
                                    <div class="fore1">
                                        <img width="100" id="image_0_1_img" src=""> <input id="image_0_1" type="hidden" name="image[0][1][name]" value="">
                                    </div>
                                    <div class="fore2">
                                        <p>
                                            <i class="icon-ok-circle"></i> <span><?= __('设为主图') ?></span> <input type="hidden" name="image[0][1][default]" value="0">
                                        </p>
                                        <a href="javascript:void(0)" nctype="del" class="del" title="<?= __('移除') ?>" data-id="<?= $data['goods_images'][1]['images_id'] ?>">X</a>
                                    </div>
                                    <div class="fore3 up-label" id="image_0_1_button"><a href="javascript:void(0)"><?= __('上传') ?></a>
                                    </div>
                                    <div class="fore4">
                                        <?= __('排序') ?>: <input class="text" maxlength="1" value="0" type="text" name="image[0][1][displayorder]"></div>
                                </td>
                                <td>
                                    <div class="fore1">
                                        <img width="100" id="image_0_2_img" src=""> <input id="image_0_2" type="hidden" name="image[0][2][name]" value="">
                                    </div>
                                    <div class="fore2">
                                        <p>
                                            <i class="icon-ok-circle"></i> <span><?= __('设为主图') ?></span> <input type="hidden" name="image[0][2][default]" value="0">
                                        </p>
                                        <a href="javascript:void(0)" nctype="del" class="del" title="<?= __('移除') ?>" data-id="<?= $data['goods_images'][2]['images_id'] ?>">X</a>
                                    </div>
                                    <div class="fore3 up-label" id="image_0_2_button"><a href="javascript:void(0)"><?= __('上传') ?></a>
                                    </div>
                                    <div class="fore4">
                                        <?= __('排序') ?>: <input class="text" maxlength="1" value="0" type="text" name="image[0][2][displayorder]"></div>
                                </td>
                                <td>
                                    <div class="fore1">
                                        <img width="100" id="image_0_3_img" src=""> <input id="image_0_3" type="hidden" name="image[0][3][name]" value="">
                                    </div>
                                    <div class="fore2">
                                        <p><i class="icon-ok-circle"></i><span><?= __('设为主图') ?></span><input type="hidden" name="image[0][3][default]" value="0"></p>
                                        <a href="javascript:void(0)" nctype="del" class="del" title="<?= __('移除') ?>" data-id="<?= $data['goods_images'][3]['images_id'] ?>">X</a>
                                    </div>
                                    <div class="fore3 up-label" id="image_0_3_button"><a href="javascript:void(0)"><?= __('上传') ?></a>
                                    </div>
                                    <div class="fore4">
                                        <?= __('排序') ?>: <input class="text" maxlength="1" value="0" type="text" name="image[0][3][displayorder]"></div>
                                </td>
                                <td>
                                    <div class="fore1">
                                        <img width="100" id="image_0_4_img" src=""> <input id="image_0_4" type="hidden" name="image[0][4][name]" value="">
                                    </div>
                                    <div class="fore2">
                                        <p><i class="icon-ok-circle"></i><span><?= __('设为主图') ?></span><input type="hidden" name="image[0][4][default]" value="0"></p>
                                        <a href="javascript:void(0)" nctype="del" class="del" title="<?= __('移除') ?>" data-id="<?= $data['goods_images'][4]['images_id'] ?>">X</a>
                                    </div>
                                    <div class="fore3 up-label" id="image_0_4_button"><a href="javascript:void(0)"><?= __('上传') ?></a>
                                    </div>
                                    <div class="fore4">
                                        <?= __('排序') ?>: <input class="text" maxlength="1" value="0" type="text" name="image[0][4][displayorder]">
                                    </div>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                        <a class="button quota bbc_seller_btns " nctype="select-image" href="javascript:void(0)"><i class="iconfont icon-jia"></i><?= __('从图片空间中选择') ?></a>
                    <?php } else { ?><?php foreach ($color as $key => $val) { ?>
                        <input type="hidden" name="is_color" value="1"/>
                        <table class="image-list-table" cellpadding="5" cellspacing="1">
                            <tbody>
                            <tr>
                                <th colspan="5"><?= __('颜色') ?>:&nbsp;&nbsp;<?php echo $val; ?></th>
                            </tr>
                            <tr>
                                <td>
                                    <div class="fore1">
                                        <img width="100" width="102px" height="102px" id="image_<?php echo $key; ?>_0_img" src="<?php echo $common_image; ?>"> <input id="image_<?= $key; ?>_0" type="hidden" name="image[<?php echo $key; ?>][0][name]" value="<?php echo $common_image; ?>">
                                    </div>
                                    <div class="fore2">
                                        <p>
                                            <i class="icon-ok-circle"></i> <span><?= __('设为主图') ?></span> <input type="hidden" name="image[<?= $key ?>][0][default]" value="<?php echo $data['color_images'][$key][0]['images_is_default']; ?>">
                                        </p>
                                        <a href="javascript:void(0)" nctype="del" class="del" title="<?= __('移除') ?>" data-id="<?= $data['goods_images'][0]['images_id'] ?>">X</a>
                                    </div>
                                    <div class="fore3 up-label" id="image_<?php echo $key; ?>_0_button">
                                        <a href="javascript:void(0)"><?= __('上传') ?></a>
                                    </div>
                                    <div class="fore4">
                                        <?= __('排序') ?>: <input class="text" maxlength="1" value="0" type="text" name="image[<?php echo $key; ?>][0][displayorder]">
                                    </div>
                                </td>
                                <td>
                                    <div class="fore1">
                                        <img width="100" id="image_<?php echo $key; ?>_1_img" src=""> <input id="image_<?= $key; ?>_1" type="hidden" name="image[<?php echo $key; ?>][1][name]" value="">
                                    </div>
                                    <div class="fore2">
                                        <p>
                                            <i class="icon-ok-circle"></i> <span><?= __('设为主图') ?></span> <input type="hidden" name="image[<?= $key ?>][1][default]" value="<?php echo $data['color_images'][$key][1]['images_is_default']; ?>">
                                        </p>
                                        <a href="javascript:void(0)" nctype="del" class="del" title="<?= __('移除') ?>" data-id="<?= $data['goods_images'][1]['images_id'] ?>">X</a>
                                    </div>
                                    <div class="fore3 up-label" id="image_<?php echo $key; ?>_1_button"><a href="javascript:void(0)"><?= __('上传') ?></a>
                                    </div>
                                    <div class="fore4">
                                        <?= __('排序') ?>: <input class="text" maxlength="1" value="0" type="text" name="image[<?php echo $key; ?>][1][displayorder]">
                                    </div>
                                </td>
                                <td>
                                    <div class="fore1">
                                        <img width="100" id="image_<?php echo $key; ?>_2_img" src=""> <input id="image_<?php echo $key; ?>_2" type="hidden" name="image[<?php echo $key; ?>][2][name]" value="">
                                    </div>
                                    <div class="fore2">
                                        <p>
                                            <i class="icon-ok-circle"></i> <span><?= __('设为主图') ?></span> <input type="hidden" name="image[<?= $key ?>][2][default]" value="<?php echo $data['color_images'][$key][2]['images_is_default']; ?>">
                                        </p>
                                        <a href="javascript:void(0)" nctype="del" class="del" title="<?= __('移除') ?>" data-id="<?= $data['goods_images'][2]['images_id'] ?>">X</a>
                                    </div>
                                    <div class="fore3 up-label" id="image_<?php echo $key; ?>_2_button"><a href="javascript:void(0)"><?= __('上传') ?></a>
                                    </div>
                                    <div class="fore4">
                                        <?= __('排序') ?>: <input class="text" maxlength="1" value="0" type="text" name="image[<?php echo $key; ?>][2][displayorder]"></div>
                                </td>
                                <td>
                                    <div class="fore1">
                                        <img width="100" id="image_<?php echo $key; ?>_3_img" src=""> <input id="image_<?php echo $key; ?>_3" type="hidden" name="image[<?php echo $key; ?>][3][name]" value="">
                                    </div>
                                    <div class="fore2">
                                        <p>
                                            <i class="icon-ok-circle"></i> <span><?= __('设为主图') ?></span> <input type="hidden" name="image[<?= $key ?>][3][default]" value="<?php echo $data['color_images'][$key][3]['images_is_default']; ?>">
                                        </p>
                                        <a href="javascript:void(0)" nctype="del" class="del" title="<?= __('移除') ?>" data-id="<?= $data['goods_images'][3]['images_id'] ?>">X</a>
                                    </div>
                                    <div class="fore3 up-label" id="image_<?php echo $key; ?>_3_button">
                                        <a href="javascript:void(0)"><?= __('上传') ?></a>
                                    </div>
                                    <div class="fore4">
                                        <?= __('排序') ?>: <input class="text" maxlength="1" value="0" type="text" name="image[<?php echo $key; ?>][3][displayorder]">
                                    </div>
                                </td>
                                <td>
                                    <div class="fore1">
                                        <img width="100" id="image_<?php echo $key; ?>_4_img" src=""> <input id="image_<?php echo $key; ?>_4" type="hidden" name="image[<?php echo $key; ?>][4][name]" value="">
                                    </div>
                                    <div class="fore2">
                                        <p><i class="icon-ok-circle"></i><span><?= __('设为主图') ?></span><input type="hidden" name="image[<?= $key ?>][4][default]" value="<?php echo $data['color_images'][$key][4]['images_is_default']; ?>"></p>
                                        <a href="javascript:void(0)" nctype="del" class="del" title="<?= __('移除') ?>" data-id="<?= $data['goods_images'][4]['images_id'] ?>">X</a>
                                    </div>
                                    <div class="fore3 up-label" id="image_<?php echo $key; ?>_4_button"><a href="javascript:void(0)"><?= __('上传') ?></a></a>
                                    </div>
                                    <div class="fore4">
                                        <?= __('排序') ?>: <input class="text" maxlength="1" value="0" type="text" name="image[<?php echo $key; ?>][4][displayorder]">
                                    </div>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                        <a class="button quota bbc_seller_btns " nctype="select-image" href="javascript:void(0)"> <i class="iconfont icon-jia"></i> <?= __('从图片空间中选择') ?></a>
                    <?php } ?>
                    <?php } ?>
                    <div class="image-button">
                        <input type="button" class="button button_black bbc_seller_submit_btns" value="<?= __('提交') ?>">
                    </div>
                </form>
            </div>
            <div class="form-style-right">
                <h4><?= __('上传要求：') ?></h4>
                <ul>
                    <li>1. <?= __('请使用jpg\jpeg\png等格式、单张大小不超过1M的正方形图片。') ?></li>
                    <li>2. <?= __('上传图片最大尺寸将被保留为1280像素。') ?></li>
                    <li>3. <?= __('每种颜色最多可上传5张图片。') ?></li>
                    <li>4. <?= __('通过更改排序数字修改商品图片的排列显示顺序。') ?></li>
                    <li>5. <?= __('图片质量要清晰，不能虚化，要保证亮度充足。') ?></li>
                    <li>6. <?= __('操作完成后请点下一步，否则无法在网站生效。') ?></li>
                </ul>
                <h4><?= __('建议') ?>:</h4>
                <ul>
                    <li>1. <?= __('主图为白色背景正面图') ?>。</li>
                    <li>2. <?= __('排序依次为正面图') ?>-&gt;<?= __('背面图') ?>-&gt;<?= __('侧面图') ?>-&gt;<?= __('细节图') ?>。</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<?php
    include $this -> view -> getTplPath() . '/' . 'seller_footer.php';
?>

<script>
  $(function () {
    //打开相册空间
    $('a[nctype="select-image"]').on('click', function () {
      var _this = $(this);
      aloneImage = $.dialog({
        content: 'url: ' + SITE_URL + '?ctl=Upload&met=image&typ=e',
        data: {callback: getImageList},
        height: 600,
        width: 900,
      });
      
      function getImageList(imagelist) {
        //取出前五个
        var images = [], count = 0;
        for (var i = 0; i < imagelist.length; i++) {
          images.push(imagelist[i].src);
        }
        _this.prev('table').find('[name$="[name]"]').each(function (index, element) {
          if (element.value == '' && images.length >= count) {
            if (images[count] != undefined) {
              $(element).prev().attr('src', images[count]);
              element.value = images[count];
              count++;
            }
          }
        });
      }
    });
    
    //图片上传
      <?php if ( empty($color) ) { ?>
    for (var i = 0; i < 5; i++) {
      new UploadImage({
        thumbnailWidth: 102,
        thumbnailHeight: 102,
        imageContainer: '#image_0_' + i + '_img',
        uploadButton: '#image_0_' + i + '_button',
        inputHidden: '#image_0_' + i,
      });
    }
      <?php } else { ?>
      <?php foreach ($color as $key => $val) { ?>
    for (var i = 0; i < 5; i++) {
      new UploadImage({
        thumbnailWidth: 102,
        thumbnailHeight: 102,
        imageContainer: '#image_<?php echo $key; ?>_' + i + '_img',
        uploadButton: '#image_<?php echo $key; ?>_' + i + '_button',
        inputHidden: '#image_<?php echo $key; ?>_' + i,
      });
    }
      <?php } ?>
      <?php } ?>
  });
  
  if (window.location.href.indexOf('action') > -1) {
    //编辑商品  编辑图片
    var common_id = <?= $common_data['common_id']; ?>;
    $li_img = $('.tabmenu').find('.active').removeClass('active bbc_seller_bg').children('a').prop('href', window.location.href.replace('edit_image', 'edit_goods')).html("<?=__('编辑商品')?>").parent('li').clone();
    $li_img.addClass('active bbc_seller_bg').children('a').html("<?=__('编辑图片')?>").prop('href', window.location.href);
    $('.tabmenu').find('ul').append($li_img);
    $('ol.step.fn-clear').remove();
  }
  
  $('#form').find('img').attr('width', '100px').attr('height', '85.22px');
  
  <?php if ( !empty($data['color_images']) ){ ?>
  <?php foreach ($data['color_images'] as $key => $val){ ?>
  <?php foreach ($val as $k => $v){ ?>
  $('#image_<?= $key; ?>_<?= $k; ?>_img').attr('src', '<?= $v['images_image']; ?>');
  $('#image_<?= $key; ?>_<?= $k; ?>').attr('value', '<?= $v['images_image']; ?>');
  $('[name="image[<?= $key; ?>][<?= $k; ?>][displayorder]"]').attr('value', '<?= $v['images_displayorder']; ?>');
  <?php } ?>
  <?php } ?>
  <?php } elseif ( !empty($data['goods_images']) ) { ?>
  <?php foreach ($data['goods_images'] as $k => $v){ ?>
  $('#image_0_<?= $k; ?>_img').attr('src', '<?= $v['images_image']; ?>');
  $('#image_0_<?= $k; ?>').attr('value', '<?= $v['images_image']; ?>');
  $('[name="image[0][<?= $k; ?>][displayorder]"]').attr('value', '<?= $v['images_displayorder']; ?>');
  <?php } ?>
  <?php } ?>
  
  var def_img = BASE_URL + '/shop/static/common/images/image.png';
  
  var def_img_src = $("a[nctype='del']").parent().prev().find("img").val();
  console.log(def_img_src);
  
  $('a[nctype="del"]').on('click', function () {
    var $div = $($(this).parents('div').prev()[0]), $img = $div.find('img'), $input = $div.find('input'), images_id = $(this).attr('data-id');
    
    if ($img.attr('src') != def_img) {
      $img.attr('src', def_img);
      $input.attr('value', '');
      
      $(this).parent().removeClass('bbc_seller_border').find('span').html("<?=__('设为主图')?>");
      $(this).parent().find('p').css('display', '');
      
      //如果删除的是主图，则选取有图片的规格的第一张图片作为主图
      if ($(this).parent().find('input[name$="[default]"]').attr('value') == 1) {
        $(this).parent().find('input[name$="[default]"]').attr('value', 0);
        $('.image-list-table').find('.fore1 img').each(function () {
          if ($(this).attr('src') != def_img) {
            $(this).parent().next().find('input[name$="[default]"]').attr('value', 1);
            $(this).parent().next().addClass('bbc_seller_border').find('p').css('display', 'block').find('span').html("<?=__('默认主图')?>");
            return false;
          }
        });
      }
    }else{
      Public.tips.warning("系统默认底色图，不能删除！");
      return false;
    }
    
    console.log(images_id);
    Public.ajaxPost(SITE_URL + "?ctl=Seller_Goods&met=delGoodsImage&typ=json", {image_id: images_id}, function (data) {
      if (data.status == 200) {
        Public.tips.success(data.msg);
        window.location.reload();
      } else {
        Public.tips.warning(data.msg);
      }
    });
  });
  
  //设置主图
  $('.fore2').on('click', 'p', function () {
    var $div = $($(this).parents('div').prev()[0]), $img = $div.find('img'), $input = $(this).find('input');
    if ($img.attr('src') != def_img && $input.attr('value') == 0) {
      var $table = $('.image-list-table');
      $table.find('input[name$="[default]"]').attr('value', 0);
      $(this).find('input').attr('value', 1);
    }
    
    $('.fore2.bbc_seller_border').removeClass('bbc_seller_border').find('p').css('display', '').find('span').html("<?=__('设为主图')?>");
    $('.image-list-table').find('input[name$="[default]"]').each(function () {
      if ($(this).val() == 1) {
        $(this).parent().parent().addClass('bbc_seller_border').find('p').css('display', 'block').find('span').html("<?=__('默认主图')?>");
      }
    });
  });
  
  //页面加载好加载点击事件
  //    $('.fore2:eq(0) > p').trigger('click');
  $(function () {
    //根据来源，手动更新tab
    if (getQueryString("source") == "stock") {
      var $leftLayout = $(".left-layout");
      $leftLayout.find(".active").removeClass("active");
      $leftLayout.find("ul li:eq(2) > a").addClass("active");
      $(".right-layout").find(".path").html('<i class="iconfont icon-diannao"></i>商家管理中心<i class="iconfont icon-iconjiantouyou"></i>商品<i class="iconfont icon-iconjiantouyou"></i>仓库中的商品');
    }
    var default_count = 0;
    $('.image-list-table').find('input[name$="[default]"]').each(function () {
      if ($(this).val() == 1) {
        default_count += 1;
        $(this).parent().parent().addClass('bbc_seller_border').find('p').css('display', 'block').find('span').html("<?=__('默认主图')?>");
      }
    });
    if (default_count < 1) {
      $('.image-list-table:first').find('.fore2:first input[name$="[default]"]').val(1);
      $('.image-list-table:first').find('.fore2:first').addClass('bbc_seller_border').find('p').css('display', 'block').find('span').html("<?=__('默认主图')?>");
    }
  });
  
  //点击提交按钮判断当前颜色属性是不是都有默认主图
  $('.bbc_seller_submit_btns').click(function () {
    var kind_count = $('.image-list-table').length;
    var img_main_count = 0;
    $('.image-list-table').each(function () {
      var a = 0;
      $(this).find('.fore1 input').each(function () {
        if ($(this).val() == '') {
          a += 0;
        } else {
          a += 1;
        }
      });
      if (a == 0) {
        img_main_count += 0;
      } else {
        img_main_count += 1;
      }
    });
    //如果各个颜色属性都有默认主图或者都没有，则允许提交
    if ((kind_count == img_main_count) || img_main_count == 0) {
      if (img_main_count == 0) {
        $('.image-list-table').find('input[name$="[default]"]').attr('value', 0);
      }
      $("#form").attr('action', "<?php echo Yf_Registry ::get('url') . '?ctl=Seller_Goods&met=saveGoodsImage&typ=json&common_id=' . $common_id; ?>");
      $('#form').submit();
    } else {
      //否则将每个颜色属性的默认主图设为一个属性的颜色主图
      $.dialog({
        title: '提示',
        content: '是否将缺失的颜色属性图片默认为主图显示？',
        height: 100,
        width: 410,
        lock: true,
        drag: false,
        ok: function () {
          $('.image-list-table').find('.fore2 input').each(function () {
            if ($(this).val() == 1) {
              img_url = $(this).parent().parent().prev().find('img').attr('src');
            }
          });
          $('.image-list-table').find('.fore1:first input').val(img_url);
          $('.image-list-table').find('.fore1:first img').attr('src', img_url);
        },
      });
    }
  });
</script>
