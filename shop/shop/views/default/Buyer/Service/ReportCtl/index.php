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
    <div class="div_head  tabmenu clearfix">
        <ul class="tab pngFix clearfix">
            <li class="active"><a><?=__('违规举报')?></a></li>

        </ul>
    </div>

    <div class="order_content_title clearfix">
        <div style="margin-top: 10px;" class="clearfix">
            <form id="search_form" method="get">
                <input type="hidden" name="ctl" value="Buyer_Service_Report"/>
                <input type="hidden" name="met" value="index"/>
                <p class="pright">
                    <select name="report_state">
                        <option value="1" <?php if($data['state']==1){echo "selected";}?>><?=__('未处理')?></option>
                        <option value="2" <?php if($data['state']==2){echo "selected";}?>><?=__('已处理')?></option>
                    </select>
                    <a href="javascript:void(0);" class="sous"><i class="iconfont icon-btnsearch"></i><?=__('搜索')?></a></p>
            </form>
        </div>
    </div>
    <script type="text/javascript">
        $(".sous").on("click", function ()
        {
            $("#search_form").submit();
        });
        </script>

        <table style="width: 100%;" class="icos bort">
            <tr>
                <td class="td_1" width="415" colspan="2"><p><?=__('相关商品')?></p></td>
                <td class="td_2" width="273"><p><?=__('举报时间')?></p></td>
                <td class="td_3" width="166"><p><?=__('状态处理结果')?></p></td>
                <td class="td_4" width="178"><p><?=__('操作')?></p></td>
            </tr>
            <?php
            if (!empty($data['items']))
            {
                ?>
            <?php
            foreach ($data['items'] as $key => $value)
            {
                ?>
                <tr>
                    <td width="65" style="padding-right: 9px;"><img width="60" src="<?= $value['goods_pic'] ?>"></td>
                    <td class="td_1" width="350"><p style="text-align: left;margin-left: 0px;"><a target="_blank" href="<?= Yf_Registry::get('url') ?>?ctl=Goods_Goods&met=goods&type=goods&gid=<?= $value['goods_id'] ?>"><?= $value['goods_name'] ?></a></p></td>
                    <td class="td_2"><p><?= $value['report_date'] ?></p></td>
                    <td class="td_3"><p><?= $value['state'] ?></p></td>
                    <td class="td_4"><p><span class="edit"><a href="<?= Yf_Registry::get('url') ?>?ctl=Buyer_Service_Report&met=index&act=detail&id=<?= $value['report_id'] ?>"><i class="iconfont icon-chakan"></i><?=__('查看')?></a></span>
                            <span class="del del_line"><a
                                    data-param="{'ctl':'Buyer_Service_Report','met':'delReport','id':'<?= $value['report_id'] ?>'}" href="javascript:void(0)"  <?php if($value['state_etext']!="do"){ ?>style="opacity: 0.3;" data-dis="1"<?php } ?>><i class="iconfont icon-lajitong"></i><?=__('删除')?></a></span></p></td>
                </tr>
            <?php } ?>
            <?php }
            else
            {
                ?>
                <tr>
                    <td colspan="99">
                        <div class="no_account">
                            <img src="<?= $this->view->img ?>/ico_none.png"/>
                            <p><?= __('暂无符合条件的数据记录') ?></p>
                        </div>
                    </td>
                </tr>
            <?php } ?>
        </table>


    <?php if($page_nav){ ?>
        <div class="page"><?=$page_nav?></div>
    <?php } ?>

</div>
    </div>
</div>

<?php
include $this->view->getTplPath() . '/' . 'buyer_footer.php';
?>



