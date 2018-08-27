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
                    <li class="active"><a><?=__('投诉管理')?></a></li>
                </ul>
            </div>
            <div class="order_content_title clearfix">
                <div style="margin-top: 10px;" class="clearfix">
                    <form id="search_form" method="get">
                        <input type="hidden" name="ctl" value="Buyer_Service_Complain"/>
                        <input type="hidden" name="met" value="index"/>
                        <p class="pright">

                            <select name="status">
                                <option value=""><?=__('选择状态')?></option>
                                <option value="1" <?php if (!empty($data['state']) && $data['state'] == 1)
                                {
                                    echo "selected='selected'";
                                } ?>><?=__('新投诉')?>
                                </option>
                                <option value="2" <?php if (!empty($data['state']) && $data['state'] == 2)
                                {
                                    echo "selected='selected'";
                                } ?>><?=__('待申诉')?>
                                </option>
                                <option value="3" <?php if (!empty($data['state']) && $data['state'] == 3)
                                {
                                    echo "selected='selected'";
                                } ?>><?=__('对话中')?>
                                </option>
                                <option value="4" <?php if (!empty($data['state']) && $data['state'] == 4)
                                {
                                    echo "selected='selected'";
                                } ?>><?=__('待仲裁')?>
                                </option>
                                <option value="5" <?php if (!empty($data['state']) && $data['state'] == 5)
                                {
                                    echo "selected='selected'";
                                } ?>><?=__('已关闭')?>
                                </option>
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


            <table class="complaint icos">
                <tr>
                    <td colspan="2" class="w420"><p><?=__('投诉商品')?></p></td>
                    <td width="143"><p><?=__('投诉主题')?></p></td>
                    <td width="237"><p><?=__('投诉时间')?></p></td>
                    <td width="96"><p><?=__('投诉状态')?></p></td>
                    <td width="146"><p><?=__('操作')?></p></td>
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
                            <td width="65" style="padding-right: 9px;"><img width="60" src="<?= $value['good']['goods_image'] ?>"></td>
                            <td width="345" style="text-align: left;"><a target="_blank" href="<?= Yf_Registry::get('url') ?>?ctl=Goods_Goods&met=goods&type=goods&gid=<?= $value['good']['goods_id'] ?>"><?= $value['good']['goods_name'] ?></a></td>
                            <td><p><?= $value['complain_subject_content'] ?></p></td>
                            <td><p><?= $value['complain_datetime'] ?></p></td>
                            <td><p><?= $value['complain_state_text'] ?></p></td>
                            <td><p><span class="edit"><a href="./index.php?ctl=Buyer_Service_Complain&met=index&act=detail&id=<?= $value['complain_id'] ?>"><i class="iconfont icon-chakan"></i><?=__('查看')?></a></span><span class="cancel del_line"><a data-param="{'ctl':'Buyer_Service_Complain','met':'cancelComplain','id':'<?= $value['complain_id'] ?>'}" href="javascript:void(0)" <?php if($value['complain_state_etext']=="finish"){ ?> style="opacity: 0.3;" data-dis="1"<?php } ?>><i class="iconfont icon-lajitong"></i><?=__('取消')?></a></span></p></td>
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



