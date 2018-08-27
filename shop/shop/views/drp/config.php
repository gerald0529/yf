<?php
//配置风格信息, bbc|shop|drp

$preview_img = Yf_Registry::get('static_url') . '/preview.png';
return array('theme_name'=>'drp', 'theme_label'=>'默认', 'index_tpl'=>true, 'index_slider'=>true, 'index_slider_img'=>true, 'preview_img'=>$preview_img);