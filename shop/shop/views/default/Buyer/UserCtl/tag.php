<?php if (!defined('ROOT_PATH')){exit('No Permission');}?>
<?php
include $this->view->getTplPath() . '/' . 'buyer_header.php';
?>
</div>
       <form action="" enctype="multipart/form-data" id="form" name="form" method="post">
         <input type="hidden" name="form_submit" value="ok">
         <div nctype="list" class="user-tag-optional ui-droppable">
         	<div class="label-box">可选标签</div>
				<?php if(!empty($re['items'])){?>
				<?php foreach($re['items'] as $key=>$val){?>
				<?php if(!empty($val['user_tag_name'])){?>
                <span class="ui-draggable" nctype="able" data-param="{&quot;id&quot;:&quot;<?=$val['user_tag_id']?>&quot;}"><?=$val['user_tag_name']?></span>
				<?php }?>
				<?php }?>
				<?php }?>
              </div>
      
      <div nctype="choose" class="user-tag-selected ui-droppable">
      	<div class="label-box bor1-color">我的标签</div>
				<?php if(!empty($data['items'])){?>
				<?php foreach($data['items'] as $key=>$val){?>
				<?php if(!empty($val['user_tag_name'])){?>
                <span class="ui-draggable" nctype="able" data-param="{id:<?=$val['user_tag_id']?>;}"><?=$val['user_tag_name']?><a href="javascript:void(0)" nctype="delTag">
				<input name="mid[]" value="<?=$val['user_tag_id']?>" type="hidden">
				X</a></span>
               <?php }?>
               <?php }?>
				<?php }?>
                <span nctype="ep" class="ep" style="display:none" >&nbsp;</span> </div>
      <div class="bottom">
        <label class="submit-border">
          <input class="submit bbc_btns" value="<?=__('保存修改')?>" type="submit">
        </label>
      </div>    
       </form>
        </div>
      </div>
    </div>
  
</div>
</div>
</div>
</div>
  </div>
</div>

<script>
   //注册表单验证
$(function(){

	var $list = $('div[nctype="list"]');
	var $choose = $('div[nctype="choose"]');
	var $ep = $('span[nctype="ep"]');
	$('span[nctype="able"]', $list).draggable({ 
		cancel: "a.ui-icon",
        revert: "invalid",
        containment: "document",
       	helper: "clone",
        cursor: "move"
	});
	$('span[nctype="able"]', $choose).draggable({ 
		cancel: "a.ui-icon",
        revert: "invalid",
        containment: "document",
       	helper: "clone",
        cursor: "move"
	});
	$choose.droppable({
		accept: 'div[nctype="list"] span',
		activeClass: "ui-state-highlight",
		drop: function( event, ui ) {
            chooseTeg(ui.draggable);
        }
    });
	$list.droppable({
		accept: 'div[nctype="choose"] span',
		activeClass: "custom-state-active",
        drop: function( event, ui ) {
        	recycleIeg(ui.draggable);
        }
    });

    function chooseTeg($item){
    	$item.fadeOut('fast',function(){
        	eval("data_param = "+($item.attr('data-param')));
    		$item.append('<a href="javascript:void(0)" nctype="delTag"><input type="hidden" name="mid[]" value="'+data_param.id+'" />X</a>')
    		.insertBefore($ep).fadeIn('fast').removeAttr('style');
        });
		
    }
    function recycleIeg($item){
    	$item.fadeOut('fast',function(){
    		$item.find('a').remove().end()
    		.appendTo($list).fadeIn('fast').removeAttr('style');
        });
    }

	$('a[nctype="delTag"]').live('click', function(){
		recycleIeg($(this).parent());
	});

	$('div[nctype="list"]').find('span').live('click', function(){
		chooseTeg($(this));
	});

	$('#profile_more').submit(function(){
		ajaxpost('profile_more', '', '', 'onerror');
		return false;
	});
	
});
	//表单提交
	$(document).ready(function(){ 

        var ajax_url = SITE_URL +'?ctl=Buyer_User&met=editTagRec&typ=json';
        $('#form').validator({
            ignore: ':hidden',
            theme: 'yellow_right',
            timely: 1,
            stopOnError: false,
            fields: {               
            },
            valid:function(form){
                //表单验证通过，提交表单
                $.ajax({
                    url: ajax_url,
                    data:$("#form").serialize(),
                    success:function(a){
                        if(a.status == 200)
                        {
							Public.tips.success("<?=__('操作成功')?>");
                            location.href= SITE_URL +"?ctl=Buyer_User&met=tag";
                        }
                        else
                        {
                            Public.tips.error("<?=__('操作失败！')?>");
                        }
                    }
                });
            }

        });

    });
</script>
<?php
include $this->view->getTplPath() . '/' . 'buyer_footer.php';
?>