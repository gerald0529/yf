<?php if (!defined('ROOT_PATH')) exit('No Permission');?>

<?php
include $this->view->getTplPath() . '/' . 'seller_header.php';
?>
<style>

.bd-line td {
	border-bottom: solid 1px #DDD;
}

</style>
<div class="deliverSetting">
    
       <table class="table-list-style table-promotion-list">
  <thead>
    <tr>

      <th class="tl"><?=__('标题')?></th>
      <th class="w200"><?=__('发布时间')?></th>
      <th class="w70"><?=__('操作')?></th>
    </tr>
   
  </thead>
  <?php
	if($data['items'])
	{
   ?>
  <tbody>
	 <?php
		foreach ($data['items'] as $key => $val)
		{
      ?>
    <tr class="bd-line">
      <td class="tl"><?= $val['article_title'] ?></td>
      <td><?= $val['article_add_time'] ?></td>
      <td class="nscs-table-handle"><span><a href="<?= Yf_Registry::get('url') ?>?ctl=Article_Base&article_id=<?= $val['article_id'] ?>"  target="_blank"><i class="iconfont icon-chakan"></i>
        <p><?=__('查看')?></p>
        </a></span></td>
    </tr>
	<?php
	}
   ?>
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

 </div>
  </div>
</div>
   
</div>
<script language="javascript">
$('a[nc_type="dialog"]').bind("click",function(){
        var id = $(this).attr("data-id");
        layer.open({
            type:2,
            title:"<?=__('查看消息')?>",
            maxmin:true,
            shadeClose:false,
            area:["400px","200px"],
            content: SITE_URL + '?ctl=Seller_Message&met=look&typ=e&message_id=' + id
        });

    });
</script>   
<?php
include $this->view->getTplPath() . '/' . 'seller_footer.php';
?>

