<?php if (!defined('ROOT_PATH'))
{
    exit('No Permission');
} ?>
<?php
include $this->view->getTplPath() . '/' . 'header.php';
?>
<link href="<?=$this->view->css?>/goods-detail.css" rel="stylesheet">
<div class="chain-detail wrap">
    <div class="clearfix chain-detail-content">
        <div class="chain-img"><div class="jqthumb" style="width: 350px; height: 350px; opacity: 1;"><div style="width: 100%; height: 100%; background-image: url('<?=$chan_base['chain_img']?>'); background-repeat: no-repeat; background-position: 50% 50%; background-size: cover;"></div></div><img src="<?=$chan_base['chain_img']?>" style="display: none;"></div>
        <div class="chain-info">
            <div class="chain-name">
                <h1><?=$chan_base['chain_name']?></h1>
                <a href="javascript:;" onclick="show_map();"><i></i><?=__('查看地图')?></a></div>
            <dl>
                <dt><?=__('门店地址：')?></dt>
                <dd><?=$chan_base['chain_province']?> <?=$chan_base['chain_city']?> <?=$chan_base['chain_county']?> <?=$chan_base['chain_address']?></dd>
            </dl>
            <dl>
                <dt><?=__('联系电话：')?></dt>
                <dd><?=$chan_base['chain_mobile']?></dd>
            </dl>
            <dl>
                <dt><?=__('营业时间：')?></dt>
                <dd><?=$chan_base['chain_opening_hours']?></dd>
            </dl>
            <dl>
                <dt><?=__('交通线路：')?></dt>
                <dd><?=$chan_base['chain_traffic_line']?></dd>
            </dl>
            <div class="delivery-map"></div>
        </div>
    </div>
</div>
<script>
    function show_map() {
        $('.delivery-map').html('<img width="740" height="320" src="http://api.map.baidu.com/staticimage?center=&width=740&height=320&zoom=18&markers=<?=$chan_base['chain_province']?><?=$chan_base['chain_city']?><?=$chan_base['chain_county']?><?=$chan_base['chain_address']?>">');
    }
</script>
<?php
include $this->view->getTplPath() . '/' . 'footer.php';
?>