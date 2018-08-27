<?php if (!defined('ROOT_PATH'))
{
    exit('No Permission');
} ?>
<?php
include $this->view->getTplPath() . '/' . 'seller_header.php';
?>
<link rel="stylesheet" type="text/css" href="<?= $this->view->css ?>/seller_center.css"/>

<div class="search fn-clear">
    <form id="search_form" method="get">
        <input type="hidden" name="ctl" value="Seller_Analysis_Goods"/>
        <input type="hidden" name="met" value="hot"/>
<!--        <a class="button refresh" href="index.php?ctl=Seller_Analysis_Goods&met=hot&typ=e"><i class="iconfont">
                &#xe649;</i></a>-->
        <a class="button btn_search_goods ml10" href="javascript:void(0);"><i class="iconfont icon-btnsearch"></i><?=__('搜索')?></a>
        <select name="stype" id="stype">
            <?= $stype_html;?>
        </select>
        <select name="year" id="year">
            <?=$option['year']?>
        </select>
        <select name="month" id="month">
            <?=$option['month']?>
        </select>
        <select name="week" id="week" style="display: none;">
        </select>
    </form>
    <script type="text/javascript">
        (function($){
            $.getUrlParam
                = function(name)
            {
                var reg
                    = new RegExp("(^|&)"+
                    name +"=([^&]*)(&|$)");
                var r
                    = window.location.search.substr(1).match(reg);
                if (r!=null) return unescape(r[2]); return null;
            }
        })(jQuery);
        $(document).ready(function () {
            var year = $("#year").val();
            var month = $("#month").val();
            var week = $("#stype option:checked").val();
            if(week === 'week')
            {
                var week_val = $.getUrlParam('week');
                $.post(SITE_URL + "?ctl=Seller_Analysis_Goods&met=getWeek", {year: year, month: month}, function (e)
                {
                    $("#week").html(e);
                    $("#week").css("display", "inline");
                    $('#week option[value="'+ week_val + '"]').attr('selected', true);
                })
            }
        })
        $("#month").change(function(){
            var stype = $("#stype").val();
            if(stype=="week"){
                var year = $("#year").val();
                var month = $("#month").val();
                $.post(SITE_URL+"?ctl=Seller_Analysis_Goods&met=getWeek",{year:year,month:month},function(e){
                    $("#week").html(e);
                    $("#week").css("display","inline");
                })
            }
        });
        $("#stype").change(function(){
            var stype = $(this).val();
            if(stype=="month"){
                $("#week").empty();
                $("#week").css("display","none");
            }
            else
            {
                var year = $("#year").val();
                var month = $("#month").val();
                $.post(SITE_URL + "?ctl=Seller_Analysis_Goods&met=getWeek", {year: year, month: month}, function (e)
                {
                    $("#week").html(e);
                    $("#week").css("display", "inline");
                })
            }
        });
        $(".search").on("click", "a.button", function ()
        {
            $("#search_form").submit();
        });
    </script>
</div>

<div class="tabmenu">
    <ul class="tab clearfix">
        <li class="ui-tabs-selected bbc_seller_bg"><a href="javascript:void(0);" data-id="1" class="mar0"><?=__('下单金额')?></a></li>
        <li><a href="javascript:void(0);" data-id="2" class="mar0"><?=__('下单商品数')?></a></li>
    </ul>
</div>

<div class="main-content" id="mainContent">
    <div id="container" style="height: 400px;"></div>
    <div class="fl mr50 tb" style="width: 100%;" id="tb1">
        <table class="table-list-style table-promotion-list">
            <thead>
            <tr class="sortbar-array">
                <th class="align-center w100"><?=__('序号')?></th>
                <th class="align-center"><?=__('商品名称')?></th>
                <th class="align-center w200"><?=__('下单金额')?></th>
            </tr>
            </thead>
            <tbody id="datatable">
            <?php if (empty($cash_list))
            { ?>
                <tr>
                    <td colspan="20" class="norecord">
                        <div class="no_account"> <img src="<?=$this->view->img?>/ico_none.png"><p><?=__('暂无符合条件的数据记录')?></p></div>
                    </td>
                </tr>
            <?php }
            else
            {
                foreach ($cash_list as $k => $v)
                {
                    ?>
                    <tr>
                        <td class="align-center"><?= ($k+1) ?></td>
                        <td class="align-center"><?= $v['goods_name'] ?></td>
                        <td class="align-center"><?= $v['cashes'] ?></td>
                    </tr>
                <?php }
            }
            ?>
            </tbody>
        </table>
    </div>
    <div class="fl mr50 tb" style="width: 100%;display: none;" id="tb2">
        <table class="table-list-style table-promotion-list">
            <thead>
            <tr class="sortbar-array">
                <th class="align-center w100"><?=__('序号')?></th>
                <th class="align-center"><?=__('商品名称')?></th>
                <th class="align-center w200"><?=__('下单商品数量')?></th>
            </tr>
            </thead>
            <tbody id="datatable">
            <?php if (empty($num_list))
            { ?>
                <tr>
                    <td colspan="20" class="norecord">
                        <div class="warning-option"><i class="icon-warning-sign"></i><span><?=__('暂无符合条件的数据记录')?></span></div>
                    </td>
                </tr>
            <?php }
            else
            {
                foreach ($num_list as $k => $v)
                {
                    ?>
                    <tr>
                        <td class="align-center"><?=($k+1) ?></td>
                        <td class="align-center"><?= $v['goods_name'] ?></td>
                        <td class="align-center"><?= $v['nums'] ?></td>
                    </tr>
                <?php }
            }
            ?>
            </tbody>
        </table>
    </div>
    <div class="h30 cb">&nbsp;</div>
</div>
<script type="text/javascript" src="<?=$this->view->js_com?>/plugins/echarts/echarts.js"></script>
<script>
    $(function ()
    {
        require.config({
            paths: {
                echarts: '<?=$this->view->js_com?>/plugins/echarts'
            }
        });

        option =
        {
            "1": {
                tooltip: {
                    show: true
                },
                legend: {
                    data:["<?=__('下单金额')?>"]
                },
                xAxis : [
                    {
                        type : 'category',
                        data : <?=$data_cash['line']?>
                    }
                ],
                yAxis : [
                    {
                        type : 'value'
                    }
                ],
                series : [
                    {
                        "name":"<?=__('下单金额')?>下单金额",
                        "type":"line",
                        "data":<?=$data_cash['num']?>
                    }
                ]
            }
            ,
            "2": {
                tooltip: {
                    show: true
                },
                legend: {
                    data:["<?=__('下单数量')?>"]
                },
                xAxis : [
                    {
                        type : 'category',
                        data : <?=$data_num['line']?>
                    }
                ],
                yAxis : [
                    {
                        type : 'value'
                    }
                ],
                series : [
                    {
                        "name":"<?=__('下单数量')?>",
                        "type":"line",
                        "data":<?=$data_num['num']?>
                    }
                ]
            }
        }
        require(
            [
                'echarts',
                'echarts/chart/line' // 使用柱状图就加载bar模块，按需加载
            ],
            function (ec)
            {
                // 基于准备好的dom，初始化echarts图表
                var myChart = ec.init(document.getElementById('container'));
                myChart.setOption(option[1]);
            }
        );

        $(".tab li a").click(function ()
        {
            $(".tab li").removeClass("ui-tabs-selected bbc_seller_bg");
            $(this).parent("li").addClass("ui-tabs-selected bbc_seller_bg");
            var id = $(this).attr("data-id");
            $(".tb").css("display","none");
            $("#tb"+id).css("display","block");
            require(
                [
                    'echarts',
                    'echarts/chart/line' // 使用柱状图就加载bar模块，按需加载
                ],
                function (ec)
                {
                    // 基于准备好的dom，初始化echarts图表
                    var myChart = ec.init(document.getElementById('container'));
                    myChart.setOption(option[id]);
                }
            );
        })
    });
</script>
<?php
include $this->view->getTplPath() . '/' . 'seller_footer.php';
?>



