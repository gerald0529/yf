<?php if (!defined('ROOT_PATH')){exit('No Permission');}

include $this->view->getTplPath() . '/' . 'buyer_header.php';
?>
      <div class="aright">
      <div class="member_infor_content">
        <div class="tabmenu">
          <ul class="tab pngFix">
            <li  class="active"><a href="<?= Yf_Registry::get('url') ?>?ctl=Buyer_User&met=getSubUser"><?=__('子账号设置')?></a></li>
            <li  style="float:right;"><a onclick="addSubUser()" class="bbc_seller_btns" style="padding:5px 19px;margin-top:4px;"><?=__('添加子账号')?></a></li>
			</ul>
        </div>
	<div class="ncm-friend-find">  
	<?php if(!empty($data['items'])){?>
	<div style=" background:#FFF; padding:2px 20px">
        <div style=" background:#FFF; padding:0px 0" class="order_content">
        <table class="icos">
            <tbody class="tbpad">
            <tr class="order_tit">
                <th width=250><?= __('配置编号') ?></th>
                <th width=250><?= __('子账号名') ?></th>
                <th><?= __('是否启用') ?></th>
                <th><?= __('操作') ?></th>
            </tr>
            </tbody>
			<tbody class="" id="<?= $value['sub_user_id'] ?>">
                <?php
                foreach ($data['items'] as $key => $value)
                {
                ?>
                    
                    <tr class="tr_con" style="text-align:center;" id="<?= $value['sub_user_id']?>">
                        <td class="td_color"><?= $value['sub_user_id'] ?></td>
                        <td class="td_color"><?= $value['user_name'] ?></td>
                        <td class="td_color"><?= $value['active_state'] ?></td>
                        <td>
                            <span class="edit"><i class="iconfont icon-chakan"></i><a onclick="addSubUser(<?= $value['sub_user_id']?>)" ><?=__('编辑')?></a></span>
                            <span class="unbund del_line"><i class="iconfont icon-unbundling"></i><a onclick="delSubUser(<?= $value['sub_user_id']?>)" ><?=__('解绑')?></a></span>
                        </td>

                    </tr>
                   
                <?php } ?>
 			</tbody>
        </table>

        <?php if ($page_nav)
        { ?>
            <div class="page"><?= $page_nav ?></div>
        <?php } ?>

        </div>
     </div>

	<?php }else{?>
	 <div class="no_account">
		<img src="<?= $this->view->img ?>/ico_none.png"/>
		<p><?=__('暂无符合条件的数据记录')?></p>
	</div>  
	<?php }?>
		
    </div>
</div>
</div>
</div>
</div>
<script>
window.addSubUser = function (e)
	{
		if(e)
		{
		    url = SITE_URL + "?ctl=Buyer_User&met=subUser&sub_user_id="+e;
		    title = "<?=__('编辑子账号')?>";
		}
		else
		{
		    url = SITE_URL + "?ctl=Buyer_User&met=subUser";
		    title = "<?=__('新增子账号')?>";
		}

		$.dialog({
			title: title,
			content: 'url: ' + url ,
			height: 340,
			width: 580,
			lock: true,
			drag: true
		})

	}

window.delSubUser = function (e)
	{
	$.dialog({
			title: "<?=__('解除绑定')?>",
			content: "<?=__('您确定要解除绑定吗？')?>",
			icon: 'alert.gif',
			height: 96,
			width: 200,
			lock: true,
			drag: true,
			ok: function () {
				$.post(SITE_URL  + '?ctl=Buyer_User&met=delSubUser&typ=json',{sub_user_id:e},function(data)
					{
						console.info(data);
						if(data && 200 == data.status) {
							Public.tips.success("<?=__('解绑成功!')?>");
							$("#"+e).hide();
						} else {
							Public.tips.error("<?=__('解绑失败!')?>");
						}
					}
				);
			}
		})
	}
</script>
 <?php
include $this->view->getTplPath() . '/' . 'buyer_footer.php';
?>