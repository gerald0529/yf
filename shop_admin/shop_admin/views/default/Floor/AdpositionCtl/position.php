<?php if (!defined('ROOT_PATH')) {exit('No Permission');}?>
<?php
include $this->view->getTplPath() . '/'  . 'header.php';
?>
<link href="<?=$this->view->css?>/index.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="<?=$this->view->css_com?>/jquery/plugins/validator/jquery.validator.css">
<link rel="stylesheet" href="<?=$this->view->css?>/page.css"> 
<script type="text/javascript" src="<?=$this->view->js_com?>/layer/layer.min.js"></script>
<script type="text/javascript" src="<?=$this->view->js_com?>/plugins/validator/jquery.validator.js" charset="utf-8"></script>
<script type="text/javascript" src="<?=$this->view->js_com?>/plugins/validator/local/zh_CN.js" charset="utf-8"></script>
</head>  
<div class="container">
        <div class="m <?php echo $data['page_color'] ?> frame-<?php echo $data['layout_id'] ?>">
                <div class="mt fn-clear">
                    <div class="title"><?=$data['page_name']?></div>
                    <div class="tit_nav" style="min-width:600px;float:right;height: 36px;border-right:1px solid <?php echo $data['page_color'] ?>;border-left:1px solid <?php echo $data['page_color'] ?>;border-top:1px solid <?php echo $data['page_color'] ?>;  ">
                           <?php if(!empty($data['nav'])){
                                    foreach($data['nav'] as $key=>$value){
                                    if($value['widget_nav_name']){
                           ?>
                            <span style="margin:0 5px;"><?=$value['widget_nav_name']?></span>
                            <?php } }} ?>
                    </div>
                 </div>
                <div class="mc fn-clear">
                    
                       <?php
           
        
              
                  foreach ($data["structure"]["layout_structure"] as $keyss =>$valss)
                {
                     
          ?>
                <div id="<?php echo $keyss ?>" style="    <?php  foreach ($valss['style'] as $keysss =>$valsss)  {     ?><?php echo $keysss ?>:<?php echo $valsss ?><?php if ($keysss == 'height' || $keysss == 'width'){ ?>px;<?php } ?><?php } ?>"  data-type="<?php if(!empty($valss['type'])){ echo $valss['type'];} ?>"   class="i-mc <?php if (empty($valss['child'])){ ?> block <?php } ?>">
                <?php
                    if(!empty($valss["child"])){
                           foreach ($valss["child"] as $keysd =>$valsd)
                         {
                ?>
                      <div id="<?php echo $keysd ?>" style="    <?php  foreach ($valsd['style'] as $keysss =>$valsss)  {     ?><?php echo $keysss ?>:<?php echo $valsss ?><?php if ($keysss == 'height' || $keysss == 'width'){ ?>px;<?php } ?><?php } ?>" data-type="<?php echo $valsd["type"] ?>" class=" block ">
                    <?php  
                    if($valsd['type']=="ad" ||$valsd['type']=="ag" ){
                        if(!empty($valsd['html'])){
                           foreach ($valsd['html'] as $html =>$img)  {  
                        
                ?>
                          <img width="<?php echo $valsd['style']['width']  ?>" height="<?php echo $valsd['style']['height'] ?>" alt="<?php echo $img['item_name'] ?>" title="<?php echo $img['item_name'] ?>" src="<?php echo $img['item_img_url'] ?>" />
                
                    <?php } } }else{  ?>
                          <ul class="fn-clear">
                                <?php  
                                    if(!empty($valsd['html'])){
                                     foreach ($valsd['html'] as $html =>$cat)  {  
                                  ?>
                                        <li><?php echo $cat['item_name']  ?></li>
                                 <?php } }  ?>
                          </ul>
                      
                    <?php }?>
                      </div>
            
                <?php
                         }
                }else{
                     if(!empty($valss['html'])){
                     foreach ($valss['html'] as $html =>$img)  {  
                    
                ?>
                <img width="<?php echo $valss['style']['width']  ?>" height="<?php echo $valss['style']['height'] ?>"   alt="<?php echo $img['item_name'] ?>" title="<?php echo $img['item_name'] ?>" src="<?php echo $img['item_img_url'] ?>">
                
                     <?php } } } ?>
       	 	</div>
            <?php } ?>
                            </div>
        <script type="text/javascript">
$(".m .block").click(function(){
	$(".block").removeClass('cur');
	$(this).addClass('cur');
	var data_type = $(this).attr("data-type");
	var name = $(this).attr("id");
	var height = $(this).css("height");
	var width = $(this).css("width");
         $.dialog({
                title: "模块编辑",
                content: "url:"+ SITE_URL +"?ctl=Floor_Adposition&met="+data_type+"&op=iframe&page_id=<?php echo $data['page_id'] ?>&layout_id=<?php echo $data['layout_id'] ?>&widget_name="+name+"&width="+width+"&height="+height,
                data:{callback: testF},
                width: 700,
                height: 550,
                max: !1,
                min: !1,
                cache: !1,
                lock: !0,
                zIndex:2000
                //ok:true,
                //cancel:true
            })

});
function testF(){ 
    window.location.reload(); 
}
$(".tit_nav").click(function(){
        $.dialog({
                title: "分类设置",
                content: "url:"+ SITE_URL +"?ctl=Floor_Adposition&met=nav&op=iframe&page_id=<?php echo $data['page_id'] ?>",
                data:{callback: testF},
                width: 700,
                height: 550,
                max: !1,
                min: !1,
                cache: !1,
                zIndex:2000,
                lock: !0
            })
})
</script>
</div>

<?php
include $this->view->getTplPath() . '/' . 'footer.php';
?>