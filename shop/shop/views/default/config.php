<?php
$preview_img = Yf_Registry::get('static_url') . '/preview.png';

return array(
    //设定模板风格所属系统类型, bbc|shop|drp
    'theme_name' => 'bbc',
    
    //风格标题
    'theme_label' => "<?=__('默认')?>",
    
    //首页模板功能是否具备
    'index_tpl' => true,
    
    //是否具有首页幻灯片
    'index_slider' => true,
    
    //首页联动小图
    'index_slider_img' => true,
    
    //风格预览图
    'preview_img' => $preview_img
);