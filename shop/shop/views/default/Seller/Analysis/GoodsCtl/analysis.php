
<script type="text/javascript" src="<?=$this->view->js_com?>/plugins/echarts/echarts.min.js"></script>
<script <script type="text/javascript" src="<?=$this->view->js_com?>/plugins/dateRange/dateRange.js"></script>
<link rel="stylesheet" type="text/css" media="all" href="<?=$this->view->js_com?>/plugins/dateRange/dateRange.css" />

<script type="text/javascript">
    /*   图表样式  */
    function echartstyle(tabid, tabname, yarray){
//        alert(yarray);
        var myChart = echarts.init(document.getElementById(tabid));
        option = {
            toolbox: {
                feature: {
                    dataView: {show: false, readOnly: true},
                    magicType: {type: ['line', 'bar']},
                    restore: {show: false},
                    saveAsImage: {}
                }
            },
            legend: {
                data: [tabname],
                bottom: 20
            },
            grid: {
                top: 50,
                right: 10,
                bottom: 80
            },
            tooltip: {
                trigger: 'axis'
            },
            xAxis: {
                axisLabel:{
                    rotate: 45,
                    interval: 0
                },
                axisLine:{
                    show: false
                },
                axisTick:{
                    interval: 0
                },
                splitLine: {
                    show: false
                },
                data: <?php echo $x_data?>
            },
            yAxis: {
                axisLine:{
                    show: false
                },
                axisTick:{
                    show: false
                }
            },
            series: [{
                name: tabname,
                type: 'line',
                data: yarray
            }]
        };
        myChart.setOption(option);
    }

    echartstyle('data_order_total', '<?=__('销售量')?>', <?php echo $y_data_order?>);
    echartstyle('data_sales_total', '<?=__('销售额')?>', <?php echo $y_data_sales?>);
    echartstyle('data_followr_total', '<?=__('商品关注')?>', <?php echo $y_data_followr?>);
    echartstyle('data_conversion_total', '<?=__('转化率')?>', <?php echo $y_data_conversion?>);
    echartstyle('data_access_total', '<?=__('商品浏览')?>', <?php echo $y_data_pv_num?>);
    echartstyle('data_score_total', '<?=__('商品评分')?>', <?php echo $y_data_score?>);
</script>

<div class="right_content">
    <div class="right_content_title">
        <span class="icon_right_content_tille"></span>

        <div class="ta_date" id="div_date1" style="float:right;margin-right:20px;">
            <span class="date_title" id="date1"></span>
            <a class="opt_sel" id="input_trigger1" href="#" title="<?=__('时间范围请选择一个月以内')?>"><i class="i_orderd" style="margin-top: 10px;"></i></a>
        </div>
        <script type="text/javascript">
            var dateRange1 = new pickerDateRange('date1', {
                isTodayValid : true,
                startDate : '<?php echo $stime?>',
                endDate : '<?php echo $etime?>',
                needCompare : false,
                defaultText : ' <?=__('至')?> ',
                autoSubmit : true,
                inputTrigger : 'input_trigger1',
                theme : 'ta',
                success : function(obj) {
                    $('.right_content').html('<div class="loading"></div>');
                    var url = "?ctl=Seller_Analysis_Goods&met=analysis&plat_id=" + <?php echo $plat_id?> + "&shop_id=" + <?php echo $shop_id?> + "&product_id=" + <?php echo $product_id?>;
                    var pars = {'sdate':obj.startDate,'edate':obj.endDate};
                    $.post(url,pars,showResponse);
                    function showResponse(originalRequest)
                    {
                        $(".right_content").html(originalRequest);
                    }
                }
            });
        </script>
    </div>

    <div class="charts">
        <ul>
            <li>
                <div class="charts_title"><?=__('销售量')?>
                    <div id="sh_tab_1" class="icon_retract" onclick="showORhideTab('1')"></div>
                </div>
                <div id="hs_tab_1">
                    <div class="charts_subhead">
                        <p><?=__('你选择的是')?><strong><?php echo $day+1?></strong><?=__('天')?>，<?=__('累计销售量')?><strong><?php echo array_sum($data_order)?></strong><?=__('笔')?></p>
                        <p><?=__('销售量最高时间是')?><strong><?php $key = array_search(max($data_order),$data_order);echo $categories[$key]?></strong>，<?=__('成交了')?><strong><?php echo max($data_order)?></strong><?=__('笔')?></p>
                    </div>
                    <div id="data_order_total" style="height:300px;width:900px;"></div>
                </div>
            </li>

            <li>
                <div class="charts_title"><?=__('销售额')?>
                    <div id="sh_tab_2" class="icon_retract" onclick="showORhideTab('2')"></div>
                </div>
                <div id="hs_tab_2">
                    <div class="charts_subhead">
                        <p><?=__('你选择的是')?><strong><?php echo $day+1?></strong><?=__('天')?>，<?=__('累计销售额')?><strong><?php echo array_sum($data_sales)?></strong><?=__('元')?></p>
                        <p><?=__('销售额最高时间是')?><strong><?php $key = array_search(max($data_sales),$data_sales);echo $categories[$key]?></strong>，<?=__('成交了')?><strong><?php echo max($data_sales)?></strong><?=__('元')?></p>
                    </div>
                    <div id="data_sales_total" style="height:300px;width:900px;"></div>
                </div>
            </li>
        </ul>
        <ul>
            <li>
                <div class="charts_title"><?=__('商品关注')?>
                    <div id="sh_tab_3" class="icon_retract" onclick="showORhideTab('3')"></div>
                </div>
                <div id="hs_tab_3">
                    <div class="charts_subhead">
                        <p><?=__('你选择的是')?><strong><?php echo $day+1?></strong><?=__('天')?>，<?=__('累计商品关注')?><strong><?php echo array_sum($data_followr)?></strong><?=__('件')?></p>
                        <p><?=__('商品关注最高时间是')?><strong><?php $key = array_search(max($data_followr),$data_followr);echo $categories[$key]?></strong>，<?=__('关注了')?><strong><?php echo max($data_followr)?></strong><?=__('件')?></p>
                    </div>
                    <div id="data_followr_total" style="height:300px;width:900px;"></div>
                </div>
            </li>

            <li>
                <div class="charts_title"><?=__('转化率')?>
                    <div id="sh_tab_4" class="icon_retract" onclick="showORhideTab('4')"></div>
                </div>
                <div id="hs_tab_4">
                    <div class="charts_subhead">
                        <p><?=__('你选择的是')?><strong><?php echo $day+1?></strong><?=__('天')?>，<?=__('平均转化率')?><strong><?php echo number_format(array_sum($data_conversion)/($day+1),2,'.','')?>%</strong></p>
                        <p><?=__('转化率最高时间是')?><strong><?php $key = array_search(max($data_conversion),$data_conversion);echo $categories[$key]?></strong>，<?=__('达到了')?><strong><?php echo max($data_conversion)?>%</strong></p>
                    </div>
                    <div id="data_conversion_total" style="height:300px;width:900px;"></div>
                </div>
            </li>
        </ul>
        <ul>
            <li>
                <div class="charts_title"><?=__('商品浏览')?>
                    <div id="sh_tab_5" class="icon_retract" onclick="showORhideTab('5')"></div>
                </div>
                <div id="hs_tab_5">
                    <div class="charts_subhead">
                        <p><?=__('你选择的是')?><strong><?php echo $day+1?></strong><?=__('天')?>，<?=__('累计商品浏览')?><strong><?php echo array_sum($data_pv_num)?></strong><?=__('件')?></p>
                        <p><?=__('商品浏览最高时间是')?><strong><?php $key = array_search(max($data_pv_num),$data_pv_num);echo $categories[$key]?></strong>，<?=__('浏览了')?><strong><?php echo max($data_pv_num)?></strong><?=__('件')?></p>
                    </div>
                    <div id="data_access_total" style="height:300px;width:900px;"></div>
                </div>
            </li>

            <li>
                <div class="charts_title"><?=__('商品评分')?>
                    <div id="sh_tab_6" class="icon_retract" onclick="showORhideTab('6')"></div>
                </div>
                <div id="hs_tab_6">
                    <div class="charts_subhead">
                        <p><?=__('你选择的是')?><strong><?php echo $day+1?></strong><?=__('天')?>，<?=__('累计商品评分')?><strong><?php echo array_sum($data_score)?>次</strong></p>
                        <p><?=__('商品评分最高时间是')?><strong><?php $key = array_search(max($data_score),$data_score);echo $categories[$key]?></strong>，<?=__('达到了')?><strong><?php echo max($data_score)?>次</strong></p>
                    </div>
                    <div id="data_score_total" style="height:300px;width:900px;"></div>
                </div>
            </li>
        </ul>
    </div>

</div>




