
<script type="text/javascript" src="<?=$this->view->js_com ?>/plugins/jquery.yf_slider.js" charset="utf-8"></script>


<div id="store_decoration_area" class="store-decoration-page">
<textarea class="lazyload_container" rows="10" cols="30" style="display:none;">
    <?php if(!empty($decoration_detail['block_list']) && is_array($decoration_detail['block_list'])) {?>
    <?php foreach($decoration_detail['block_list'] as $block) {?>
    <?php require('store_decoration_block.php');?>
    <?php } ?>
    <?php } ?>
</textarea>
</div>
<script type="text/javascript">
    $(document).ready(function(){
 

        //幻灯片
        $('[nctype="store_decoration_slide"]').yf_slider();
    });
</script>
