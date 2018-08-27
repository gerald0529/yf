<?php if (!defined('ROOT_PATH')) exit('No Permission');?>

<?php
include $this->view->getTplPath() . '/' . 'seller_header.php';
?>
</head>
<body>

<div>

        <form id="add_form" action="<?=Yf_Registry::get('url')?>?ctl=Seller_Goods_Evaluation&met=addEvaluationExplain&typ=json"  method="post" onsubmit="return submitBtn();">
            <input type="hidden" name="evaluation_goods_id" value="<?=($data['evaluation_goods_id'])?>" id="evaluation_goods_id" />
            <div class="form-style">
                <dl>
                    <dt><i>*</i><?=__('评价内容：')?></dt>
                    <?php Text_Filter::filterWords($data['content']);?>
                    <dd style="width:50%;" id="content"><?=($data['content'])?></dd>
                </dl>
				<?php if(!empty($data['image'])){?>
				<dl>
                    <dt><i><?=__('*')?></i><?=__('评价图片：')?></dt>
                    <dd>
						<?php foreach($data['image_row'] as $imgkey => $imgval){?>
                        <?php if($imgval){?><img src="<?=image_thumb($imgval,60,60)?>"><?php }?>
						<?php }?>
                    </dd>
                </dl>
				<?php }?>
                <dl>
                    <dt><i><?=__('*')?></i><?=__('解释内容：')?></dt>
                    <dd>
                        <?php Text_Filter::filterWords($data['explain_content']);?>
                        <textarea name="con" class="text" style="width:60%;height:100px;" ><?=($data['explain_content'])?></textarea>
                    </dd>
                </dl>
				<?php if($type != 'again'){?>
                <dl>
                    <dt><i><?=__('*')?></i><?=__('置顶：')?></dt>
                    <dd>
                        <label ><input type="radio" <?=($data['status'] == '2'? 'checked="checked"' : '')?> name="status" value="2" /><?=__('是')?></label>
                        <label><input type="radio" <?=($data['status'] != '2'? 'checked="checked"' : '')?> name="status" value="1" /><?=__('否')?></label>
                    </dd>
                </dl>
				<?php }?>
                <dl>
                    <dt></dt>
                    <dd><input type="submit" class="button button_red bbc_seller_btns bbc_seller_pad" value="<?=__('确认提交')?>" /></dd>
                </dl>
            </div>
    </form>
    </div>

<script src="http://malsup.github.io/jquery.form.js"></script>
<script>
    function submitBtn()
    {
        var exp = $("textarea[name='con']").val();
        if(exp != ""){
            $("#add_form").ajaxSubmit(function(message){
                if(message.status == 200)
                {
                    location.href="<?=Yf_Registry::get('url')?>?ctl=Seller_Goods_Evaluation&met=evaluation";
                }
                else
                {
                    Public.tips.error('<?=__('操作失败！')?>');
                    //alert('操作失败！');
                }
            });
        } else {
            Public.tips.error('<?=__('请输入解释内容！')?>');
        }
        return false;
    }
</script>
<?php
include $this->view->getTplPath() . '/' . 'seller_footer.php';
?>