<?php if (!defined('ROOT_PATH')) exit('No Permission');?>

<?php
include $this->view->getTplPath() . '/' . 'seller_header.php';
?>
<style>
.ncbtn-mini, .ncbtn {
    background-color: #ccd0d9;
    border-radius: 3px;
    color: #fff;
    cursor: pointer;
    display: inline-block;
    font: 12px/20px "microsoft yahei",arial;
    height: 20px;
    padding: 5px 10px;
    text-align: center;
    vertical-align: middle;
}
.ncbtn-mini {
    border-radius: 2px;
    height: 16px;
    line-height: 16px;
    padding: 3px 7px;
}

.ncsc-default-table tbody tr td {
	color: #999;
	background-color: #FFF;
	text-align: center;
	padding: 10px 0;
	line-height:20px;
}
.bd-line td {
	border-bottom: solid 1px #DDD;
}
.dark {
    color: #333333 !important;
}

.tc {
    text-align: center !important;
}
</style>
<div class="deliverSetting">
    <div class="alert">
        <h4><?=__('操作提示：')?></h4>
        <ul>
            <li><?=__('1、可以对消息进行查看和删除。')?></li>
            <li><?=__('2、删除消息，删除后其他账户的该条消息也将被删除。')?></li>
        </ul>
    </div>
    <form method="post" id="form"  >
       <table class="table-list-style table-promotion-list">
  <tbody>
    <tr>
      <th class="tl"><label class="checkbox"><input class="checkall" type="checkbox"/></label> <?=__('消息内容')?></th>
      <th class="w200" width="150"><?=__('发送时间')?></th>
    </tr>
  <?php
	if($data['items'])
	{
   ?>
	 <?php
		foreach ($data['items'] as $key => $val)
		{
      ?>
    <tr class="bd-line">
      <td class="tl <?php if($val['message_islook']==0){;?>dark<?php }?>"><label  class="checkbox"><input class="checkitem" name="chk[]" value="<?= $val['message_id'] ?>" type="checkbox"></label><?=$val['message_content'];?></td>
      <td><?=$val['message_create_time'];?></td>
    </tr>
	<?php
	}
   ?>
 
  <tr>
      <td class="toolBar" colspan="2"><label  class="checkbox"><input class="checkall" type="checkbox"></label><?=__('全选')?>
	  <span>|</span>
		<a name="smids" nc_type="batchbutton" href="javascript:void(0);">
		<i class="iconfont icon-biaozhi"></i>
		标记为已读
		</a>
		<span>|</span>
         <label class="del" ><i class="iconfont icon-lajitong"></i><a data-param="{'ctl':'Seller_Message','met':'delAllMessage'}" ><?=__('删除')?></a></label>
       </td>
    </tr>
	 </tbody>
  <?php
	}else{
   ?>
   <tbody>
            <tr>
              <td colspan="20" class="norecord"><div class="no_account">
            <img src="<?= $this->view->img ?>/ico_none.png"/>
            <p><?=__('暂无符合条件的数据记录')?></p>
			</div> </td>
            </tr>
          </tbody>
	<?php }?>
  <tbody>
  <?php if($page_nav){?>
    <tr class="bd-line">
      <td colspan="20"><div class="pagination page page_front"><?=$page_nav?></div></td>
    </tr>
  <?php }?>
  </tbody>
</table>
 </form>
<script language="javascript">
    $('a[nc_type="batchbutton"]').click(function(){

	   var length = $('.checkitem:checked').length;
       if(length > 0){
			var chk_value =[];//定义一个数组
				$("input[name='chk[]']:checked").each(function(){
                chk_value.push($(this).val());//将选中的值添加到数组chk_value中
            });

	    var ajax_url = SITE_URL+'?ctl=Seller_Message&met=look&typ=json';
	   
	  $.ajax({
			url: ajax_url,
			data:{id:chk_value},
			success:function(a){
				
				if(a.status == 200)
				{
					Public.tips.success("<?=__('标记成功！')?>");
					//obj.parents('tr:first').children('.tl').removeClass('dark');
					 location.reload();
				}
				else
				{
					Public.tips.error("<?=__('标记失败！')?>");
				}
			}
		});
		 }
        else
        {
            $.dialog.alert('请选择需要操作的记录');
        }
        
    });
</script>   
 </div>
  </div>
</div>
   
</div>

<?php
include $this->view->getTplPath() . '/' . 'seller_footer.php';
?>

