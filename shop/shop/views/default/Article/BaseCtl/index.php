<?php 
if (!defined('ROOT_PATH')) {
    exit('No Permission');
} 
include $this->view->getTplPath() . '/' . 'header.php';
?>
<div class="hr">
</div>
<div class="Colr">
    <div class="wrapper ">
        <div class="nch-container clearfix">
            <div class="left">
                <div class="nch-module nch-module-style01">
                    <div class="title">
                        <h3><?=__('文章分类')?></h3>
                    </div>
                    <div class="content">
                        <div class="nch-sidebar-article-class">
                            <ul>
                                <?php foreach($data_all_group as $k1=>$v1): ?>
                                <li><a href="index.php?ctl=Article_Base&met=index&article_group_id=<?=$k1 ?>"><?=$v1['article_group_title']; ?></a></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="nch-module nch-module-style03">
                    <div class="title">
                        <h3><?=__('最新文章')?></h3>
                    </div>
                    <div class="content">
                        <ul class="nch-sidebar-article-list">
                            <?php foreach($data_recent_article as $k2=>$v2): ?>
                            <li><i></i><a href="index.php?ctl=Article_Base&met=index&article_id=<?=$k2 ?>"><?=$v2['article_title'] ?></a></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="right">
                <div class="nch-article-con">
                    <?php if(!empty($data_article)){ ?>
                    <h1><?=$data_article['article_title'] ?></h1>
                    <h2><?=$data_article['article_add_time'] ?></h2>
                    <div class="default">
                        <p><?=$data_article['article_desc'] ?></p>
                    </div>
                    <?php }elseif(!empty($data_article_list)){ ?>
                        <h3><?=$data_article_list['group_name']?></h3>
                        <ul class="nch-article-list">
                            <?php if(!empty($data_article_list['article'])): foreach($data_article_list['article'] as $k3=>$v3): ?>
                            <li><i></i><a href="index.php?ctl=Article_Base&met=index&article_id=<?=$v3['article_id'] ?>"><?=$v3['article_title']?></a><time><?=$v3['article_add_time']?></time></li>
                            <?php endforeach; endif;?>
                        </ul>
                    <?php }?>
                    <div class="more_article">
                        <span class="fl"><?=__('上一篇')?>：
                            <?php if(!empty($data_near_article['front'])){ ?>
                            <a href="index.php?ctl=Article_Base&met=index&article_id=<?=$data_near_article['front']['article_id'] ?>"><?=$data_near_article['front']['article_title']?></a> <time><?=$data_near_article['front']['article_add_time']?></time>
                            <?php }else{ ?>
                                <span style="color: #006bcd;"><?=__('没有了')?></span>
                            <?php } ?>
                        </span>
                        <span class="fr"><?=__('下一篇')?>：
                            <?php if(!empty($data_near_article['behind'])){ ?>
                                <a href="index.php?ctl=Article_Base&met=index&article_id=<?=$data_near_article['behind']['article_id'] ?>"><?=$data_near_article['behind']['article_title']?></a> <time><?=$data_near_article['behind']['article_add_time']?></time>
                            <?php }else{ ?>
                                <span style="color: #006bcd;"><?=__('没有了')?></span>
                            <?php } ?>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
</div>
</div>
</div>
</div>
<?php
include $this->view->getTplPath() . '/' . 'footer.php';
?>