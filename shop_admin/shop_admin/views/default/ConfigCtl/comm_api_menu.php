<ul class="tab-base nc-row">
    <?php 
    foreach($menus['brother_menu'] as $key=>$val){ 
        if(in_array($val['rights_id'],$admin_rights)||$val['rights_id']==0){
    ?>
    
    
    <li><a <?php if(!array_diff($menus['this_menu'], $val)){?> class="current"<?php }?> href="<?= Yf_Registry::get('url') ?>?ctl=<?=$val['menu_url_ctl']?>&met=<?=$val['menu_url_met']?><?php if($val['menu_url_parem']){?>&<?=$val['menu_url_parem']?><?php }?>"><span><?=$val['menu_name']?></span></a></li>
    <?php 
        }
    }
    ?>
</ul>


 