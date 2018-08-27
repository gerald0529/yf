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
            <li><?=__('1、短信、邮件接收方式需要正确设置接收号码才能正常接收。')?></li>
        </ul>
    </div>
    <form method="post" id="form" action="" >
       <table class="table-list-style table-promotion-list">
  <thead>
    <tr>
      <th class="w700 tl"><?=__('模板名称')?></th>
      <th class="w200 tl"><?=__('接收方式')?></th>
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
      <td class="tl"><?=$val['name'];?></td>
      <td class="tl"><?=__('商家消息')?></td>
      <td class="nscs-table-handle ">
        <span><a class="setting" onclick="edit_message(<?= $val['id'] ?>)"><i class="iconfont icon-btnsetting"></i><?=('设置')?></a>
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
 </form>

<script language="javascript">

	window.edit_message = function (e)
    {
        url = SITE_URL + "?ctl=Seller_Message&met=set&id="+e;

        $.dialog({
            title: '设置消息接收',
            content: 'url: ' + url ,
            height: 150,
            width: 450,
            lock: true,
            drag: false,

        })
    }
</script>   
 </div>
  </div>
</div>
   
</div>

<?php
include $this->view->getTplPath() . '/' . 'seller_footer.php';
?>

