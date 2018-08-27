<?php if (!defined('ROOT_PATH')) exit('No Permission');?>

<?php
include $this->view->getTplPath() . '/' . 'seller_header.php';
?>

    <div class="form-style cs">
    <form action="" method="post" id="form">
    <input type="hidden" value="save" name="op">
    <dl data-type="pre">
        <dt>售前客服：</dt>
        <dd>
            <div class="mt">
                <span class="name">客服名称</span>
                <span class="tool">客服工具</span>
                <span class="number">客服账号</span>
            </div>
            <?php if(!empty($data['pre'])){?>
            <?php foreach($data['pre'] as $key=>$val){?>
               <div class="mc clearfix">
                <span class="name"><input type="text" maxlength="10" name="prename[<?=$key?>]" value="<?=$val['name']?>" class="text w60"></span>
                <span class="tool">
                <select name="pretool[<?=$key?>]">
                <option <?php if($val['tool'] == 1){?>selected="selected"<?php }?> value="1">QQ</option>
                <option <?php if($val['tool'] == 2){?>selected="selected"<?php }?> value="2">旺旺</option>
                <option <?php if($val['tool'] == 3){?>selected="selected"<?php }?> value="3">IM</option>
                </select>
                </span>
                <span class="number"><input type="text" name="prenum[<?=$key?>]" class="text" value="<?=$val['number']?>"></span>
                <span class="op"><a data-param="{'id':'<?=$val['id']?>'}" href="javascript:void(0);" data-type="del"><i class="iconfont icon-lajitong"></i>删除</a></span>
            </div>
			<?php }?>
			<?php }else{ ?>
			<div class="mc clearfix">
                <span class="name">
                    <input type="text" maxlength="10" name="prename[0]" value="售前1" class="text w60" >
                </span>
                <span class="tool">
                <select name="pretool[0]">
                    <option value="1">QQ</option>
                    <option value="2">旺旺</option>
                    <?php if(Yf_Registry::get('im_statu') ==1){?>
                    <option value="3">IM</option>
                    <?php }?>
                </select>
                </span>
                <span class="number">
                    <input type="text" name="prenum[0]" class="text" value="">
                </span>
                <span class="op"><a href="javascript:void(0);" data-type="del"><i class="iconfont icon-lajitong"></i>删除</a></span>
            </div>
           <?php }?>
           <p><a class="service_reset bbc_seller_btns" href="javascript:void(0);" onclick="add_service('pre');"><i class="iconfont icon-jia"></i>添加客服</a></p>
        </dd>
    </dl>
    <dl data-type="after">
        <dt>售后客服：</dt>
        <dd>
            <div class="mt">
                <span class="name">客服名称</span>
                <span class="tool">客服工具</span>
                <span class="number">客服账号</span>
            </div>
			<?php if(!empty($data['after'])){?>
            <?php foreach($data['after'] as $key=>$val){ ?>
            <div class="mc clearfix">
                <span class="name">
                <input type="text" maxlength="10" name="aftername[<?=$key?>]" value="<?=$val['name']?>" class="text w60"></span>
                <span class="tool">
                <select name="aftertool[<?=$key?>]">
                    <option <?php if($val['tool'] == 1){?>selected="selected"<?php }?>  value="1">QQ</option>
                    <option <?php if($val['tool'] == 2){?>selected="selected"<?php }?> value="2">旺旺</option>
                    <option <?php if($val['tool'] == 3){?>selected="selected"<?php }?> value="3">IM</option>
                </select>
                </span>
                <span class="number">
                    <input type="text" name="afternum[<?=$key?>]" value="<?=$val['number']?>" class="text">
                </span>
                <span class="op"><a data-param="{'id':'<?=$val['id']?>'}" href="javascript:void(0);" data-type="del"><i class="iconfont icon-lajitong"></i>删除</a></span>
            </div>
			<?php }?>
			<?php }else{ ?>
			<div class="mc clearfix">
                <span class="name">
                    <input type="text" maxlength="10" name="aftername[0]" value="售后1" class="text w60">
                </span>
                <span class="tool">
                <select name="aftertool[0]">
                    <option value="1">QQ</option>
                    <option value="2">旺旺</option>
                    <?php if(Yf_Registry::get('im_statu') ==1){?>
                        <option value="3">IM</option>
                    <?php }?>
                </select>
                </span>
                <span class="number">
                    <input type="text" name="afternum[0]" class="text" value="">
                </span>
                <span class="op"><a href="javascript:void(0);" data-type="del"><i class="iconfont icon-lajitong"></i>删除</a></span>
            </div>	
			<?php }?>
            <p><a class="service_reset bbc_seller_btns" href="javascript:void(0);" onclick="add_service('after');"><i class="iconfont icon-jia"></i>添加客服</a></p>
        </dd>
    </dl>
    <dl>
    	<dt>工作时间</dt>
        <dd>
        <p><font color="#777">例：（AM 10:00 - PM 18:00）</font></p>
        <textarea name="workingtime" class="text textarea w450"><?=$data['shop_workingtime']?></textarea>
        </dd>
    </dl>
    <dl class="foot">
        <dt>&nbsp;</dt>
        <dd><input type="submit" class="button bbc_seller_submit_btns" value="确认提交"></dd>
    </dl>
	</form>
    </div>
</div>
<script type="text/javascript">
$('#form').find('a[data-type="del"]').live('click', function(){
	$(this).parents('div:first').remove();
});
function add_service(param){
	if(param == 'pre'){
		var text = '售前';
	}else if(param == 'after'){
		var text = '售后';
	}
	obj = $('dl[data-type="'+param+'"]').children('dd').find('p');
	len = $('dl[data-type="'+param+'"]').children('dd').find('div').length;
	key = 'k'+len+Math.floor(Math.random()*100);
	$('<div class="mc clearfix"></div>').append('<span class="name"><input type="text" class="text w60" value="'+text+len+'" name="'+param+'name['+key+']" /></span>').append('<span class="tool">&nbsp;<select name="'+param+'tool['+key+']">><option value="1">QQ</option><option value="2">旺旺</option><?php if(Yf_Registry::get('im_statu') ==1){?><option value="3">IM</option><?php }?></select></span>').append('<span class="number">&nbsp;&nbsp;<input class="text" type="text" name="'+param+'num['+key+']"  /></span>').append('<span class="op">&nbsp;<a data-type="del" href="javascript:void(0);" ><i class="iconfont icon-lajitong"></i>删除</a></span>').insertBefore(obj);
}

//表单提交
	$(document).ready(function(){ 

        var ajax_url = SITE_URL +'?ctl=Seller_Message&met=editService&typ=json';
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
                            location.href= SITE_URL +"?ctl=Seller_Message&met=index";
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
</script></div>	
</div>

<?php
include $this->view->getTplPath() . '/' . 'seller_footer.php';
?>
