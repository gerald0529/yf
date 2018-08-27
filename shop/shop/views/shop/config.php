<?php
//配置风格信息, bbc|shop|drp

$preview_img = Yf_Registry::get('static_url') . '/preview.png';
return array('theme_name'=>'shop', 'theme_label'=>'商城', 'index_tpl'=>true, 'index_slider'=>true, 'index_slider_img'=>false, 'preview_img'=>$preview_img);