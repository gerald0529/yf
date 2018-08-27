<?php if (!defined('ROOT_PATH'))
{
    exit('No Permission');
} ?>
<?php
include $this->view->getTplPath() . '/' . 'buyer_header.php';
?>
<link type="text/css" rel="stylesheet" href="<?= $this->view->css ?>/headfoot.css">
<link type="text/css" rel="stylesheet" href="<?= $this->view->css ?>/iconfont/iconfont.css">
<link type="text/css" rel="stylesheet" href="<?= $this->view->css ?>/index.css">
<div class="aright">
    <div class="member_infor_content">
<div class="order_content">
    <div class="div_head tabmenu clearfix">
        <ul class="tab pngFix clearfix">
            <li <?php if (empty($data['state']))
            {
                echo 'class="active"';
            } ?>><a href="<?= Yf_Registry::get('url') ?>?ctl=Buyer_Service_Consult&met=index"><?=__('全部咨询')?></a></li>
            <li <?php if ($data['state'] == 1)
            {
                echo 'class="active"';
            } ?>><a href="<?= Yf_Registry::get('url') ?>?ctl=Buyer_Service_Consult&met=index&state=1"><?=__('未回复咨询')?></a></li>
            <li <?php if ($data['state'] == 2)
            {
                echo 'class="active"';
            } ?>><a href="<?= Yf_Registry::get('url') ?>?ctl=Buyer_Service_Consult&met=index&state=2"><?=__('已回复咨询')?></a></li>
        </ul>
    </div>
    <div class="order_content_title clearfix">
        <div style="margin-top: 10px; border-bottom:1px solid #e1e1e1; padding:0px 0px 10px;" class="clearfix">
            <p style=" text-align:center; color:#999999"><?=__('咨询/回复')?></p>
        </div>
    </div>
    <?php
    if (!empty($data['items']))
    {
    ?>
    <div class="div_bt clearfix">
        <ul class="clearfix">
            <?php foreach ($data['items'] as $key => $value){ ?>
                <li class="clearfix">
                	 <a style="float:left;margin-top:3px" target="_blank" href="<?= Yf_Registry::get('url') ?>?ctl=Shop&met=index&id=<?= $value['shop_id'] ?>"><i class="iconfont icon-icoshop"></i></a>
                     <p class="p_Font_1 "><a href="<?= Yf_Registry::get('url') ?>?ctl=Goods_Goods&met=goods&type=goods&gid=<?= $value['goods_id'] ?>"><?= $value['goods_name'] ?></a></p>
                	 <p class="p_Font_1 " style="padding-left:3px"><a href="<?= Yf_Registry::get('url') ?>?ctl=Shop&met=index&id=<?= $value['shop_id'] ?>">[<?= $value['shop_name'] ?>]</a></p>
                	<p class="p_Font_2" style="font-size: 12px;  line-height: 23px;float: right;"><?=__('咨询时间：')?><span><?= $value['question_time'] ?></span></p>
                </li>
                <li>
                    <p class="p_Font_repeat"><span><?=__('咨询内容：')?></span><span><?= $value['consult_question'] ?></span></p>
                </li>
            <?php if($value['consult_answer']){ ?>
                <li>
                <p class="p_Font_repeat">
                    <span><?=__('回复：')?></span><span><?= $value['consult_answer'] ?>(<?= $value['answer_time'] ?>)</span></p>

                </li>
            <?php }?>
            <?php } ?>
        </ul>
    </div>
    <?php if($page_nav){ ?>
        <div class="flip clearfix page">
            <?=$page_nav?>
        </div>
    <?php }?>
    <?php }
    else
    {
        ?>
                <div class="no_account">
                    <img src="<?= $this->view->img ?>/ico_none.png"/>
                    <p><?= __('暂无符合条件的数据记录') ?></p>
                </div>
    <?php } ?>

</div>
    </div>
</div>
<?php
include $this->view->getTplPath() . '/' . 'buyer_footer.php';
?>



