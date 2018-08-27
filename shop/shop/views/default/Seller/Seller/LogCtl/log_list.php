<?php if (!defined('ROOT_PATH')){exit('No Permission');}?>
<?php
    include $this->view->getTplPath() . '/' . 'seller_header.php';
?>
<style>
    #search_form #date-box{float:right;margin:0 10px;}
    #date-box{float:left;display:inline-block;}
    #date-box input,#date-box span{float:left !important;margin-left:0px;}
    #date-box span input{margin-right: 0px;}

</style>

<div class="tabmenu">
    <ul>
        <li class="active bbc_seller_bg"><a href="<?=Yf_Registry::get('url')?>?ctl=Seller_Seller_Log&met=logList&typ=e"><?=__('日志列表')?></a></li>
    </ul>
</div>

<div class="search fn-clear">
    <div id="search_form">
        <form id="form" action="index.php" method="get">
            <input type="hidden" name="ctl" value="<?=request_string('ctl')?>">
            <input type="hidden" name="met" value="<?=request_string('met')?>">
            <input type="hidden" name="typ" value="e">
            <a class="button refresh" href="<?=Yf_Registry::get('url')?>?ctl=Seller_Seller_Log&met=logList&typ=e&"><i class="iconfont icon-huanyipi"></i></a>
            <a href="javascript:void(0);" class="button btn_search_goods"><i class="iconfont icon-btnsearch"></i><?=__('搜索')?></a>
            <div id="date-box">
                <span><input type="text" autocomplete="off" name="start_date" id="start_date" class="text w120" value="<?=request_string('start_date')?>" placeholder="开始时间"/><em class="add-on add-on2"><i class="iconfont icon-rili"></i></em></span>
                <span style="height: 30px;line-height: 30px;">&nbsp; – &nbsp;</span>
                <span><input type="text" autocomplete="off" name="end_date" id="end_date" class="text w120" value="<?=request_string('end_date')?>" placeholder="结束时间"/><em class="add-on add-on2"><i class="iconfont icon-rili"></i></em></span>
            </div>
            <input type="text" value="" placeholder="<?=__('日志内容')?>" class="text w200" name="log_content">
            <input type="text" value="" placeholder="<?=__('账号')?>" class="text w100" name="seller_name">
        </form>
        <script type="text/javascript">
            $(".search").on("click","a.button",function(){
                $("#form").submit();
            });

            $(document).ready(function(){
                $('#start_date').datetimepicker({
                    controlType: 'select',
                    timepicker:false
                });

                $('#end_date').datetimepicker({
                    controlType: 'select',
                    timepicker:false
                });
            });
        </script>
    </div>
</div>

<table class="table-list-style" width="100%" cellpadding="0" cellspacing="0">
    <tr>
        <th width="50"></td>
        <th class="tl" width="300"><?=__('账号')?></th>
        <th class="tl"><?=__('日志内容')?></th>
        <th width="80"><?=__('状态')?></th>
        <th width="110"><?=__('ip')?></th>
        <th width="130"><?=__('时间')?></th>
    </tr>
    <?php
    if($data['items'])
    {
        foreach($data['items'] as $key=>$value)
        {
    ?>
    <tr>
        <td width="50"></td>
        <td class="tl"><?=$value['log_seller_name']?></td>
        <td><?=$value['log_content']?></td>
        <td><?=$value['log_state']?></td>
        <td><?=$value['log_seller_ip']?></td>
        <td><?=$value['log_time']?></td>
    </tr>
    <?php } }else{ ?>
        <tr class="row_line">
            <td colspan="99">
                <div class="no_account">
                    <img src="<?=$this->view->img?>/ico_none.png">
                    <p>暂无符合条件的数据记录</p>
                </div>
            </td>
        </tr>
    <?php } ?>
</table>

<?php if($page_nav){ ?>
    <div class="mm">
        <div class="page"><?=$page_nav?></div>
    </div>
<?php }?>

<?php
    include $this->view->getTplPath() . '/' . 'seller_footer.php';
?>



