<?php if (!defined('ROOT_PATH'))
{
    exit('No Permission');
} ?>
<?php
include $this->view->getTplPath() . '/' . 'header.php';
?>
<style>
	.define_detail{text-align:left;padding:15px;}
</style>
<link rel="stylesheet" type="text/css" href="<?= $this->view->css ?>/personalstores.css">
<link rel="stylesheet" type="text/css" href="<?= $this->view->css ?>/goods-detail.css"/>
<link rel="stylesheet" type="text/css" href="<?= $this->view->css ?>/Group-integral.css" />
<link rel="stylesheet" type="text/css" href="<?= $this->view->css ?>/base.css">
<script type="text/javascript" src="<?=$this->view->js?>/tuangou-index.js"></script>
<div class="wrap clearfix">
          <div class="bbc-store-info">
                <div class="basic">
                    <div class="displayed"><a href=""><?=$shop_base['shop_name']?></a>
                        <span class="all-rate">
                     <div class="rating"><span style="width: <?=$shop_scores_percentage?>%"></span></div>
                       <em><?=$shop_scores_count?></em><em>分</em></span>
                </div>
                <div class="sub">
                    <div class="store-logo"><img src="<?=$shop_base['shop_logo']?>" alt="<?=$shop_base['shop_name']?>" title="<?=$shop_base['shop_name']?>"></div>
                    <!--店铺基本信息 S-->
                    <div class="bbc-info_reset">
                        <div class="title">
                            <h4><?=$shop_base['shop_name']?></h4>
                        </div>
                        <div class="content_reset">
                            <div class="bbc-detail-rate">
                                <ul>
                                    <li>
                                        <h5><?=__('描述')?></h5>
                                                   <div class="low" ><?=$shop_detail['shop_desc_scores']?><i></i></div>
                                    </li>
                                    <li>
                                        <h5><?=__('服务')?></h5>
                                        <div class="low" ><?=$shop_detail['shop_service_scores']?><i></i></div>
                                    </li>
                                    <li>
                                        <h5><?=__('物流')?></h5>
                                          <div class="low" ><?=$shop_detail['shop_send_scores']?><i></i></div>
                                    </li>
                                </ul>
                            </div>
                            <div class="btns"><a href="index.php?ctl=Shop&met=goodsList&id=<?=$shop_id?>" class="goto"><?=__('进店逛逛')?></a><a href="#"><?=__('收藏店铺')?></a></div>
                            <?php if(!empty($shop_all_base)){?>
                            <dl class="no-border">
                                <dt><?=__('公司名称')?>：</dt>
                                <dd><?=$shop_all_base['shop_company_name']?></dd>
                            </dl>
                            <dl>
                                <dt><?=__('电话')?>：</dt>
                                <dd><?=$shop_all_base['company_phone']?></dd>
                            </dl>
                            <dl>
                                <dt><?=__('所在地')?>：</dt>
                                <dd><?=$shop_all_base['shop_company_address']?></dd>
                            </dl>
                            <?php }?>
                            <dl class="messenger">
                                <dt><?=__('联系方式')?>：</dt>
                                <dd><span member_id="9"></span>
                                    <a target="_blank" href='http://wpa.qq.com/msgrd?v=3&uin=<?=$shop_base['shop_qq']?>&site=qq&menu=yes'><img border="0" src="http://wpa.qq.com/pa?p=2:<?=$shop_base['shop_qq']?>:52&amp;r=0.22914223582483828" style=" vertical-align: middle;"></a>
                                    <a target="_blank" href='http://www.taobao.com/webww/ww.php?ver=3&touid=<?=$shop_base['shop_ww']?>&siteid=cntaobao&status=2&charset=utf-8'><img border="0" src='http://amos.alicdn.com/realonline.aw?v=2&uid=<?=$shop_base['shop_ww']?>&site=cntaobao&s=2&charset=utf-8' alt="<?=__('点击这里给我发消息')?>" style=" vertical-align: middle;"></a>
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
          </div>
   <div class="clearfix">
  
      <div class="div_shop_Carouselfigure1" style="width:1200px;height: 150px;overflow: hidden;">
      <?php if(!empty($shop_base['shop_banner'])){ ?>
      <img src="<?=$shop_base['shop_banner']?>" width="1200px" height="150px;"/></a>
      <?php }else{ ?>
      <img src="<?= $this->view->img ?>/shop_img.png" width="1200px" /></a>
      <?php } ?>

    </div>
  </div>
     <div id="nav" class="bbc-nav">
      <ul>
       
          <li class="active9"><a href="index.php?ctl=Shop&met=index&id=<?=$shop_id?>"><span><?=__('店铺首页')?><i></i></span></a></li>
         <?php if($shop_nav['items']){
                foreach ($shop_nav['items'] as $key => $value) {

               
         ?>
           <li><a href="<?php if(!empty($value['url'])) echo $value['url'];else echo 'index.php?ctl=Shop&met=info&id='.$shop_id.'&nav_id='.$value['id']; ?>" <?php if($value['target']){?>target="_blank" <?php } ?>><span><?=$value['title']?><i></i></span></a></li>
         <?php }} ?>
              </ul>
    </div>
    <div class="clearfix">
        <div class="t_goods_bot clearfix">
            <div class="wrap clearfix">
                <div class="bbc-main-container">
                    <div class="title">
                        <h4><?=@$data['title']?></h4>
                    </div>
					<div class="define_detail">
						<?=@$data['detail']?>
					</div>
                </div>
            </div>
        </div>
    </div>
</div>
 
<div class="bbuilder_code">
    <span class="bbc_codeArea"><img src="<?=Yf_Registry::get('base_url')?>/shop/api/qrcode.php?data=<?= urlencode(Yf_Registry::get('url')."?ctl=Shop&met=index&id=".$shop_base['shop_id'])?>"></span>
    <span class="bbc_arrow"></span>
    <div class="bbc_guide_con">
      <span>
          <div class="service-list1" store_id="8" store_name="12312312<?=__('发发')?>">
		  
            <dl>
              <dt><?=__('售前客服')?>：</dt>
			  <?php if(!empty($service['pre'])){?>
			   <?php foreach($service['pre'] as $key=>$val){ ?>
			   <?php if(!empty($val['number'])){?>
               <dd><span>
                  <span c_name="<?=$val['name']?>" member_id="9"><?=$val['tool']?></span>
                  </span></dd>
				<?php }?>
				<?php }?> 
				<?php }?> 				
            </dl> 
		
			
            <dl>
              <dt><?=__('售后客服')?>：</dt>
			  <?php if(!empty($service['after'])){?> 
			  <?php foreach($service['after'] as $key=>$val){ ?>
			  <?php if(!empty($val['number'])){?>
                <dd><span>
                  <span c_name="<?=$val['name']?>" member_id="9"><?=$val['tool']?></span>
                  </span></dd>  
				<?php }?>
				<?php }?>
				<?php }?> 
            </dl>
			
			
            <dl class="workingtime">
              <dt><?=__('工作时间')?>：</dt>
			  <?php if($shop_base['shop_workingtime']){?>
              <dd>
              <p><?=($shop_base['shop_workingtime'])?></p>
              </dd><?php }?>
            </dl>
			
        </div>
      </span>
    </div>
  </div>
<?php
include $this->view->getTplPath() . '/' . 'footer.php';
?>