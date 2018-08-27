<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
} ?>
<?php
include $this->view->getTplPath() . '/' . 'header.php';
?>

<link rel="stylesheet" type="text/css" href="<?=$this->view->css?>/ui.min.css">
<link href="<?= $this->view->css_com ?>/jquery/plugins/datepicker/dateTimePicker.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="<?=$this->view->js_com?>/plugins/jquery.datetimepicker.js" charset="utf-8"></script>
    <div class="pc_user_about">
        <div class="recharge-content-top content-public clearfix">
            <ul class="tab">
                <li><a href="<?=Yf_Registry::get('url')?>?ctl=Info&met=btinfo">白条概览</a></li>
                <li class="active"><a href="<?=Yf_Registry::get('url')?>?ctl=Info&met=btbill">白条账单</a></li>
            </ul>
        </div>
        
        <div class="wrap">
            
                        <div class="mod-search cf clearfix">
                            <div class="fl">
                                
                                    
                                <ul class="ul-inline">
                                    <li>
                                            <select  class="input-txt" style="width:120px; height: 30px;" id="order_status">
                                                <option value="0" <?php if(!$order_status){ echo 'selected="selected"';}?>>全部</option>
                                                <option value="1" <?php if($order_status == 1){ echo 'selected="selected"';}?>>待还款</option>
                                                <option value="2" <?php if($order_status == 2){ echo 'selected="selected"';}?>>已还款</option>
                                                <option value="3" <?php if($order_status == 3){ echo 'selected="selected"';}?>>已延期</option>
                                            </select>
                                    </li>
                                    
                                    <li>
                                        <input type="text" id="order_id" class="ui-input ui-input-ph matchCon" placeholder="请输入订单号" value="<?=$search_order_id?>" style="width:300px;">
                                    </li>
                                    <li>
                                        <label>付款日期:</label>
                                        <input id="start_time" class="ui-input ui-datepicker-input" type="text" value="<?=$start_time?>" readonly placeholder="开始时间"/>
                                        至
                                        <input id="end_time" class="ui-input ui-datepicker-input" type="text" value="<?=$end_time?>" readonly placeholder="结束时间"/>
                                    </li>
<!--                                    <li>
                                        
                                        <input type="text" value="" class="ui-input ui-datepicker-input" name="filter-fromDate" id="filter-fromDate" readonly=""> - <input type="text" value="" class="ui-input ui-datepicker-input" name="filter-toDate" id="filter-toDate" readonly="">
                                    </li>-->
                                <li> <input type="submit" class="ui-btn iconfont1 icon-btn021" id="search11" value="查询" style="color:white; padding: 0px 10px;" onclick="getbtbill();" /></a> </li>
                                </ul>
                            </div>
                        </div>
                        <?php if(isset($baitiao_order) && $baitiao_order){ ?>
                        <div class="grid-wrap">
                           <div class="ui-jqgrid ui-widget ui-widget-content ui-corner-all" style="width: 1200px;">
                               <div class="ui-jqgrid-view" style="width: 100%;">
                                   <div class="ui-state-default ui-jqgrid-hdiv ui-corner-top" style="width: 100%;">
                                       <div class="ui-jqgrid-bdiv">
                                            <table class="ui-jqgrid-htable" style="width:100%" role="grid"  cellspacing="0" cellpadding="0" border="0">
                                                <thead>
                                                    <tr class="ui-jqgrid-labels" role="rowheader">
                                                        <th class="ui-state-default ui-th-column ui-th-ltr" style="width: 60px;">
                                                            <div id="jqgh_grid_operate" class="ui-jqgrid-sortable">序号</div>
                                                        </th>
                                                        <th class="ui-state-default ui-th-column ui-th-ltr" style="width: 200px;">
                                                            <div class="ui-jqgrid-sortable">订单编号</div>
                                                        </th>
                                                        <th class="ui-state-default ui-th-column ui-th-ltr" style="width: 140px;">
                                                            <div id="jqgh_grid_operate" class="ui-jqgrid-sortable">订单日期</div>
                                                        </th>
                                                        <th class="ui-state-default ui-th-column ui-th-ltr" style="width:120px;">
                                                            <div class="ui-jqgrid-sortable">订单总金额（￥）</div>
                                                        </th>
                                                        <th class="ui-state-default ui-th-column ui-th-ltr" style="width: 120px;">
                                                            <div class="ui-jqgrid-sortable">实际还款金额（￥）</div>
                                                        </th>
                                                        <th class="ui-state-default ui-th-column ui-th-ltr" style="width: 120px;">
                                                            <div class="ui-jqgrid-sortable">剩余还款金额</div>
                                                        </th>
                                                        <th class="ui-state-default ui-th-column ui-th-ltr" style="width: 80px;">
                                                            <div class="ui-jqgrid-sortable">还款状态</div>
                                                        </th>
                                                        <th class="ui-state-default ui-th-column ui-th-ltr" style="width: 140px;">
                                                            <div class="ui-jqgrid-sortable">最迟还款日期</div>
                                                        </th>
                                                    </tr>
                                                </thead>
                                               <tbody>
                                                    <?php 
                                                    
                                                        foreach ($baitiao_order as $key => $value){
                                                       
                                                    ?>
                                                    <tr role="row" class="ui-widget-content jqgrow ui-row-ltr">
                                                       <td role="gridcell" style="text-align:center;" class="ui-ellipsis" width=55><?=($key+1)?></td>
                                                       <td role="gridcell" style="text-align:center;" class="ui-ellipsis" width=146><?=$value['order_id']?></td>
                                                        <td role="gridcell" style="text-align:center;" class="ui-ellipsis" width=146><?=$value['trade_create_time']?></td>
                                                        <td role="gridcell" style="text-align:center;" class="ui-ellipsis" width=146><?=$value['order_payment_amount']?></td>
                                                        <td role="gridcell" style="text-align:center;" class="ui-ellipsis" width=116><?=$value['trade_payment_amount']?></td>
                                                        <td role="gridcell" style="text-align:center;" class="ui-ellipsis" width=116><?php echo sprintf("%.2f",($value['order_payment_amount']-$value['remain_payment_amount']));?></td>
                                                        <td role="gridcell" style="text-align:center;" class="ui-ellipsis" width=94><?=$value['order_status_text']?></td>
                                                        <td role="gridcell" style="text-align:center;" class="ui-ellipsis" width=116><?=$value['bt_limit_time']?></td>
                                                    </tr>
                                                        <?php  }?>
                                               </tbody>
                                           </table>
                                       </div>
                                   </div>
                               </div>
                           </div>
                        </div>
                        <?php }else{ ?>
                        <div class="no_account">
                            <p class="no_account"><img src="<?= $this->view->img ?>/ico_none.png"/></p>
                            <p>您还没有白条账单记录！</p>
                        </div>
                        <?php }?>
                    </div>
                </li>
            </ul>

        </div>
    </div>
<script type="text/javascript">
    $('#start_time').datetimepicker({
        controlType: 'select',
        format:"Y-m-d",
        timepicker:false
    });

    $('#end_time').datetimepicker({
        controlType: 'select',
        format:"Y-m-d",
        timepicker:false
    });
    function getbtbill(){
        var start_time = $('#start_time').val();
        var end_time = $('#end_time').val();
        var order_status = $("#order_status option:selected").val(); 
        var order_id = $('#order_id').val();
        window.location.href = "<?=Yf_Registry::get('base_url').'/index.php?ctl=Info&met=btbill&typ=e'?>"+"&start_time="+start_time+"&end_time="+end_time+"&order_status="+order_status+"&order_id="+order_id;
    }
    
</script>
<?php
include $this->view->getTplPath() . '/' . 'footer.php';
?>