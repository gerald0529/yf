<?php if (!defined('ROOT_PATH')) {exit('No Permission');}?>
<?php
include $this->view->getTplPath() . '/'  . 'header.php';
?>
<link href="<?=$this->view->css?>/index.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="<?=$this->view->css?>/page.css">
<script type="text/javascript" src="<?=$this->view->js_com?>/layer/layer.min.js"></script>
<script type="text/javascript" src="<?=$this->view->js_com?>/plugins/validator/jquery.validation.min.js" charset="utf-8"></script>
</head>

</head>
<body>
<div class="dialog_frame">
<form id="form" method="post">
   
     <input type="hidden" name="act" value="category_add" id="act"/>
     <input type="hidden" name="widget_width" value="<?=$data["width"] ?>" id="widget_width"/>
     <input type="hidden" name="widget_height" value="<?=$data["height"]  ?>" id="widget_height"/>

    <input type="hidden" name="layout_id" value="<?=$data["layout_id"]  ?>" id="layout_id"/>
    <input type="hidden" name="widget_cat" value="<?=$data["met"]  ?>" id="widget_cat"/>
    <input type="hidden" name="widget_name" id="widget_name"value="<?=$data["widget_name"]  ?>" />
    <input type="hidden" name="page_id" id="page_id"  value="<?=$data["page_id"]  ?>" />
    <table class="form-table-style frame-table-style">
        <tr>
            <th width="150">分类名称：</th>
            <th>跳转链接：</th>
        </tr>
       <?php    
       
        
                   if(empty($data["category"])){
            
           ?>
            <input type="hidden" name="widget_id" value="" id="widget_id"/>
        <tr>
            <td><input type="text" class="text" name="item_name[]" value="" /></td>
         	<td><input type="text" class="text w250" name="item_url[]" value="" /></td>
        </tr>
          <tr>
            <td><input type="text" class="text" name="item_name[]" value="" /></td>
         	<td><input type="text" class="text w250" name="item_url[]" value="" /></td>
        </tr>
            <tr>
            <td><input type="text" class="text" name="item_name[]" value="" /></td>
         	<td><input type="text" class="text w250" name="item_url[]" value="" /></td>
        </tr>
          <tr>
            <td><input type="text" class="text" name="item_name[]" value="" /></td>
         	<td><input type="text" class="text w250" name="item_url[]" value="" /></td>
        </tr>
           <tr>
            <td><input type="text" class="text" name="item_name[]" value="" /></td>
         	<td><input type="text" class="text w250" name="item_url[]" value="" /></td>
        </tr>
          <tr>
            <td><input type="text" class="text" name="item_name[]" value="" /></td>
         	<td><input type="text" class="text w250" name="item_url[]" value="" /></td>
        </tr>
      
   
               <?php 
                   
            }else{ 
                foreach($data["category"] as $key =>$val){ 
                ?>
            <input type="hidden" name="widget_id" value="<?php echo $val['widget_id'] ?>" id="widget_id"/>
                 <?php 
                  foreach($val["cat"] as $keys =>$vals){ 
                            
                  ?>
                   
         <tr>
            <td><input type="text" class="text" name="item_name[]" value="<?php echo $vals['item_name'] ?>" /></td>
            <td><input type="text" class="text w250" name="item_url[]" value="<?php echo $vals['item_url'] ?>" /></td>
        </tr>
        
                        <?php }  }   } ?>
        
         <tr>    
            <td class="foot" colspan="2"><input type="submit" value="保存" /></td>
        </tr>
	</table>
</form>

<script type="text/javascript">

$('#form').validate({
	submitHandler:function(form){ 

            var  act = $.trim($("#act").val());
            var  widget_width = $.trim($("#widget_width").val());
            var  widget_height = $.trim($("#widget_height").val());
            var  widget_id = $.trim($("#widget_id").val());
            var  page_id = $.trim($("#page_id").val());
            var  widget_name = $.trim($("#widget_name").val());
            var  widget_cat = $.trim($("#widget_cat").val());
            var  layout_id = $.trim($("#layout_id").val());

             var item_name = new Array;
            $("input[name='item_name[]']").each(function(i){
                     item_name[i] = $(this).val();
              });
            var item_url = new Array;
            $("input[name='item_url[]']").each(function(i){
                     item_url[i] = $(this).val();
              });
              
                                params =  {
                                        page_id: page_id, 
                                        item_url: item_url,
                                        item_name: item_name,
                                        widget_id:widget_id,
                                        layout_id:layout_id,
                                        widget_name:widget_name,
                                        widget_cat:widget_cat,
                                        widget_width:widget_width,
                                        widget_height:widget_height,
                                } 
                               
                                Public.ajaxPost(SITE_URL +"?ctl=Floor_Adposition&typ=json&met="+act , params, function (e)
                                {
                                    
                                        if (200 == e.status || true)
                                        {
                                            var callback = frameElement.api.data.callback;
                                            callback();
                                            //parent.parent.Public.tips({content: n + "成功！"});
//                                                callback && "function" == typeof callback && callback(e.data, t, window)
                                        }
                                        else
                                        {
                                             var callback = frameElement.api.data.callback;
                                            callback();
                                           // parent.parent.Public.tips({type: 1, content: n + "失败！" + e.msg})
                                                    
                                        }
                                })
	}
});
</script>

<?php
include $this->view->getTplPath() . '/'  . 'footer.php';
?>