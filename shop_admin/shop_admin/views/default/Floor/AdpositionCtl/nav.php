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
   

    <input type="hidden" name="page_id" id="page_id"  value="<?=$data["page_id"]  ?>" />
    <table class="form-table-style frame-table-style">
        <tr>
            <th width="150">分类名称：</th>
            <th>跳转链接：</th>
        </tr>
       <?php    
       
        
                   if(empty($data["nav"])){
            
           ?>
            <input type="hidden" name="act" value="addNav" id="act"/>
            <input type="hidden" name="widget_nav_id[]" value="" id="widget_nav_id"/>
        <tr>
            <td><input type="text" class="text" name="widget_nav_name[]" value="" /></td>
         	<td><input type="text" class="text w250" name="widget_nav_url[]" value="" /></td>
        </tr>
          <tr>
            <td><input type="text" class="text" name="widget_nav_name[]" value="" /></td>
         	<td><input type="text" class="text w250" name="widget_nav_url[]" value="" /></td>
        </tr>
            <tr>
            <td><input type="text" class="text" name="widget_nav_name[]" value="" /></td>
         	<td><input type="text" class="text w250" name="widget_nav_url[]" value="" /></td>
        </tr>
          <tr>
            <td><input type="text" class="text" name="widget_nav_name[]" value="" /></td>
         	<td><input type="text" class="text w250" name="widget_nav_url[]" value="" /></td>
        </tr>
           <tr>
            <td><input type="text" class="text" name="widget_nav_name[]" value="" /></td>
         	<td><input type="text" class="text w250" name="widget_nav_url[]" value="" /></td>
        </tr>
          <tr>
            <td><input type="text" class="text" name="widget_nav_name[]" value="" /></td>
         	<td><input type="text" class="text w250" name="widget_nav_url[]" value="" /></td>
        </tr>
          <tr>
            <td><input type="text" class="text" name="widget_nav_name[]" value="" /></td>
         	<td><input type="text" class="text w250" name="widget_nav_url[]" value="" /></td>
        </tr>
          <tr>
            <td><input type="text" class="text" name="widget_nav_name[]" value="" /></td>
         	<td><input type="text" class="text w250" name="widget_nav_url[]" value="" /></td>
        </tr>
          <tr>
            <td><input type="text" class="text" name="widget_nav_name[]" value="" /></td>
         	<td><input type="text" class="text w250" name="widget_nav_url[]" value="" /></td>
        </tr>
          <tr>
            <td><input type="text" class="text" name="widget_nav_name[]" value="" /></td>
         	<td><input type="text" class="text w250" name="widget_nav_url[]" value="" /></td>
        </tr>
      
   
               <?php 
                   
            }else{ 
                foreach($data["nav"] as $key =>$val){
                ?>
            <input type="hidden" name="act" value="editNav" id="act"/>
            <input type="hidden" name="widget_nav_id[]" value="<?php echo $val['widget_nav_id'] ?>" id="widget_nav_id"/>

         <tr>
            <td><input type="text" class="text" name="widget_nav_name[]" value="<?php echo $val['widget_nav_name'] ?>" /></td>
            <td><input type="text" class="text w250" name="widget_nav_url[]" value="<?php echo $val['widget_nav_url'] ?>" /></td>
        </tr>
        
                        <?php }   } ?>
        
         <tr>    
            <td class="foot" colspan="2"><input type="submit" value="保存" /></td>
        </tr>
	</table>
</form>

<script type="text/javascript">

$('#form').validate({
	submitHandler:function(form){ 

            var  act = $.trim($("#act").val());
               var widget_nav_id = new Array;
            $("input[name='widget_nav_id[]']").each(function(i){
                     widget_nav_id[i] = $(this).val();
              });
            var  page_id = $.trim($("#page_id").val());
            var widget_nav_name = new Array;
            $("input[name='widget_nav_name[]']").each(function(i){
                     widget_nav_name[i] = $(this).val();
              });
            var widget_nav_url = new Array;
            $("input[name='widget_nav_url[]']").each(function(i){
                     widget_nav_url[i] = $(this).val();
              });
              
                                params =  {
                                        page_id: page_id, 
                                        widget_nav_url: widget_nav_url,
                                        widget_nav_name: widget_nav_name,
                                        widget_nav_id:widget_nav_id,
                                }
                               
                                Public.ajaxPost(SITE_URL +"?ctl=Floor_Adposition&typ=json&met="+act , params, function (e)
                                {
                                    
                                        if (200 == e.status)
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