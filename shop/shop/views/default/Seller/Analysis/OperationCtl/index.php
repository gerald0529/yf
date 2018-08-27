<?php if (!defined('ROOT_PATH'))
{
    exit('No Permission');
} ?>
<?php
include $this->view->getTplPath() . '/' . 'seller_header.php';
?>
<link rel="stylesheet" type="text/css" href="<?= $this->view->css ?>/seller_center.css"/>

<div class="tabmenu">
    <ul class="">
        <?= $tabmenu_html; ?>
    </ul>
</div>
<div class="search fn-clear">
    <form id="search_form" method="get">
        <input type="hidden" name="ctl" value="Seller_Analysis_Operation"/>
        <input type="hidden" name="met" value="index"/>
        <input type="hidden" name="kinds" value="<?= $kinds; ?>" class="kinds"/>
<!--        <a class="button refresh" href="index.php?ctl=Seller_Analysis_Operation&met=index&typ=e"><i class="iconfont">
                &#xe649;</i></a>-->
        <a class="button btn_search_goods ml10" href="javascript:void(0);"><i class="iconfont icon-btnsearch"></i><?=__('搜索')?></a>
        <select name="stype" id="stype">
            <?= $stype_html;?>
        </select>
        <select name="year" id="year">
            <?= $option['year'] ?>
        </select>
        <select name="month" id="month">
            <?= $option['month'] ?>
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
        $("#month").change(function ()
        {
//            var month = $("#month").val();
//            var year = $("#year").val();
//            var stype = $("#stype").val();
//            var week = $("#stype option:checked").val();
//            if(week === 'week')
//            {
//                $.post(SITE_URL + "?ctl=Seller_Analysis_Goods&met=getWeek", {year: year, month: month}, function (e)
//                {
//                    $("#week").empty();
//                    $("#week").html(e);
//                })
//            }
            var stype = $("#stype").val();
            if (stype == "week")
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
        $("#stype").change(function ()
        {
            var stype = $(this).val();
            if (stype == "month")
            {
                $("#week").empty();
                $("#week").css("display", "none");
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
<div class="row">
    <div class="right_nav">
        <div class="right_content">

            <script type="text/javascript" src="<?=$this->view->js_com?>/plugins/echarts/echarts-all.js"></script>

            <div class="right_content">
                <div class="right_content_title">
                    <span class="icon_right_content_tille"></span>
                    <span class="font_title"><?=__('地域分布')?></span>
                </div>

                <div id="mapCountry" style="width: 96%; height: 400px; padding: 10px;margin: auto;"></div>

                <div id="main" style="height: 400px;width: 98%;"></div>

            </div>

            <script type="text/javascript">
                var tabname = '<?php echo $tabname?>';
                country(tabname);
                function country(){

                    var myChart = echarts.init(document.getElementById('mapCountry'));

                    option = {
                        title : {
                            text: "<?=__('全国')?>" + tabname,
                            x:'center'
                        },
                        tooltip : {
                            trigger: 'item'
                        },
                        dataRange: {
                            min: 0,
                            max: <?php echo max($data_country)?>,
                            x: 'left',
                            y: 'bottom',
                            text:["<?=__('高')?>","<?=__('低')?>"],           // 文本，默认为数值文本
                            calculable : true
                        },
                        toolbox: {
                            show: true,
                            orient : 'vertical',
                            x: 'right',
                            y: 'center',
                            feature : {
                                dataView : {show: false, readOnly: true},
                                saveAsImage : {show: true}
                            }
                        },
                        series : [{
                            name: tabname,
                            type: 'map',
                            mapType: 'china',
                            roam: true,
                            itemStyle:{
                                normal:{label:{show:true}},
                                emphasis:{label:{show:true}}
                            },
                            selectedMode : 'single',
                            data:[
                                <?php
                                foreach($data_country as $k => $v)
                                {
                                    echo "{name: '$k',value:'$v'},";
                                }
                                ?>
                            ]
                        }],
                    };
                    myChart.setOption(option, true); //显示国家地图

                    myChart.on(echarts.config.EVENT.MAP_SELECTED, function (param){//单击省份事件
                        var selected = param.selected;
                        var selectedProvince;
                        var name;

                        for (var i = 0, l = option.series[0].data.length; i < l; i++) {
                            name = option.series[0].data[i].name;
                            option.series[0].data[i].selected = selected[name];
                            if (selected[name]) {
                                selectedProvince = name;
                            }
                        }

                        if (typeof selectedProvince == 'undefined') {
                            alert("<?=__('南海诸岛暂无数据,请重新选择')?>");
                            //如果选择省份不存在则显示国家
                            return;
                        }
                        mapProvince(selectedProvince);
                        specific(selectedProvince);
                    });

                }
            </script>

            <script>
                function mapProvince(province){
                    var myChart2 = echarts.init(document.getElementById('mapCountry'));

                    option2 = {
                        title : {
                            text: province + tabname,
                            x:'center'
                        },
                        tooltip : {
                            trigger: 'item'
                        },
                        dataRange: {
                            min: 0,
                            max: <?php echo max($data_country)?>,
                            x: 'left',
                            y: 'bottom',
                            text:["<?=__('高')?>","<?=__('低')?>"],            // 文本，默认为数值文本
                            calculable : true
                        },
                        toolbox: {
                            show: true,
                            orient : 'vertical',
                            x: 'right',
                            y: 'center',
                            feature : {
                                dataView : {show: false, readOnly: false},
                                saveAsImage : {show: true}
                            }
                        },
                        series : [{
                            name: province + tabname,
                            type: 'map',
                            mapType: province,
                            roam: true,
                            itemStyle:{
                                normal:{label:{show:true}},
                                emphasis:{label:{show:true}}
                            },
                            selectedMode : 'single',
                            data: [
                                <?php
                                foreach($data_provices as $kk => $vv)
                                {
                                    foreach($vv as $k => $v)
                                    {
                                        echo "{name: '$k',value:'$v'},";
                                    }
                                }
                                ?>
                            ]
                        }],
                    };
                    myChart2.setOption(option2, true); //显示省份地图

                    myChart2.on(echarts.config.EVENT.CLICK, function (param){//单击城市返回全国
                        country(tabname);
                        specific('全国');
                        //alert(param.name);  城市名
                    });

                }
            </script>

            <script type="text/javascript">
                specific('全国');
                function specific(province){
                    bpro = province;

                    if(bpro == '全国')	 {<?php $x=array();$y=array();foreach($data_country 		   as $k=>$v){$x[]=$k;$y[]=$v;}$xa=json_encode($x);$ya=json_encode($y);?>xa=<?php echo $xa?>;ya=<?php echo $ya?>;}
                    if(bpro == '北京')	 {<?php $x=array();$y=array();foreach($data_provices['北京']   as $k=>$v){$x[]=$k;$y[]=$v;}$xa=json_encode($x);$ya=json_encode($y);?>xa=<?php echo $xa?>;ya=<?php echo $ya?>;}
                    if(bpro == '天津')	 {<?php $x=array();$y=array();foreach($data_provices['天津']   as $k=>$v){$x[]=$k;$y[]=$v;}$xa=json_encode($x);$ya=json_encode($y);?>xa=<?php echo $xa?>;ya=<?php echo $ya?>;}
                    if(bpro == '上海')	 {<?php $x=array();$y=array();foreach($data_provices['上海']   as $k=>$v){$x[]=$k;$y[]=$v;}$xa=json_encode($x);$ya=json_encode($y);?>xa=<?php echo $xa?>;ya=<?php echo $ya?>;}
                    if(bpro == '重庆')	 {<?php $x=array();$y=array();foreach($data_provices['重庆']   as $k=>$v){$x[]=$k;$y[]=$v;}$xa=json_encode($x);$ya=json_encode($y);?>xa=<?php echo $xa?>;ya=<?php echo $ya?>;}
                    if(bpro == '河北')	 {<?php $x=array();$y=array();foreach($data_provices['河北']   as $k=>$v){$x[]=$k;$y[]=$v;}$xa=json_encode($x);$ya=json_encode($y);?>xa=<?php echo $xa?>;ya=<?php echo $ya?>;}
                    if(bpro == '河南')	 {<?php $x=array();$y=array();foreach($data_provices['河南']   as $k=>$v){$x[]=$k;$y[]=$v;}$xa=json_encode($x);$ya=json_encode($y);?>xa=<?php echo $xa?>;ya=<?php echo $ya?>;}
                    if(bpro == '云南')	 {<?php $x=array();$y=array();foreach($data_provices['云南']   as $k=>$v){$x[]=$k;$y[]=$v;}$xa=json_encode($x);$ya=json_encode($y);?>xa=<?php echo $xa?>;ya=<?php echo $ya?>;}
                    if(bpro == '辽宁')	 {<?php $x=array();$y=array();foreach($data_provices['辽宁']   as $k=>$v){$x[]=$k;$y[]=$v;}$xa=json_encode($x);$ya=json_encode($y);?>xa=<?php echo $xa?>;ya=<?php echo $ya?>;}
                    if(bpro == '黑龙江') {<?php $x=array();$y=array();foreach($data_provices['黑龙江'] as $k=>$v){$x[]=$k;$y[]=$v;}$xa=json_encode($x);$ya=json_encode($y);?>xa=<?php echo $xa?>;ya=<?php echo $ya?>;}
                    if(bpro == '湖南')	 {<?php $x=array();$y=array();foreach($data_provices['湖南']   as $k=>$v){$x[]=$k;$y[]=$v;}$xa=json_encode($x);$ya=json_encode($y);?>xa=<?php echo $xa?>;ya=<?php echo $ya?>;}
                    if(bpro == '安徽')	 {<?php $x=array();$y=array();foreach($data_provices['安徽']   as $k=>$v){$x[]=$k;$y[]=$v;}$xa=json_encode($x);$ya=json_encode($y);?>xa=<?php echo $xa?>;ya=<?php echo $ya?>;}
                    if(bpro == '山东') 	 {<?php $x=array();$y=array();foreach($data_provices['山东']   as $k=>$v){$x[]=$k;$y[]=$v;}$xa=json_encode($x);$ya=json_encode($y);?>xa=<?php echo $xa?>;ya=<?php echo $ya?>;}
                    if(bpro == '江苏') 	 {<?php $x=array();$y=array();foreach($data_provices['江苏']   as $k=>$v){$x[]=$k;$y[]=$v;}$xa=json_encode($x);$ya=json_encode($y);?>xa=<?php echo $xa?>;ya=<?php echo $ya?>;}
                    if(bpro == '浙江') 	 {<?php $x=array();$y=array();foreach($data_provices['浙江']   as $k=>$v){$x[]=$k;$y[]=$v;}$xa=json_encode($x);$ya=json_encode($y);?>xa=<?php echo $xa?>;ya=<?php echo $ya?>;}
                    if(bpro == '江西') 	 {<?php $x=array();$y=array();foreach($data_provices['江西']   as $k=>$v){$x[]=$k;$y[]=$v;}$xa=json_encode($x);$ya=json_encode($y);?>xa=<?php echo $xa?>;ya=<?php echo $ya?>;}
                    if(bpro == '湖北')	 {<?php $x=array();$y=array();foreach($data_provices['湖北']   as $k=>$v){$x[]=$k;$y[]=$v;}$xa=json_encode($x);$ya=json_encode($y);?>xa=<?php echo $xa?>;ya=<?php echo $ya?>;}
                    if(bpro == '甘肃')	 {<?php $x=array();$y=array();foreach($data_provices['甘肃']   as $k=>$v){$x[]=$k;$y[]=$v;}$xa=json_encode($x);$ya=json_encode($y);?>xa=<?php echo $xa?>;ya=<?php echo $ya?>;}
                    if(bpro == '山西')	 {<?php $x=array();$y=array();foreach($data_provices['山西']   as $k=>$v){$x[]=$k;$y[]=$v;}$xa=json_encode($x);$ya=json_encode($y);?>xa=<?php echo $xa?>;ya=<?php echo $ya?>;}
                    if(bpro == '陕西') 	 {<?php $x=array();$y=array();foreach($data_provices['陕西']   as $k=>$v){$x[]=$k;$y[]=$v;}$xa=json_encode($x);$ya=json_encode($y);?>xa=<?php echo $xa?>;ya=<?php echo $ya?>;}
                    if(bpro == '吉林') 	 {<?php $x=array();$y=array();foreach($data_provices['吉林']   as $k=>$v){$x[]=$k;$y[]=$v;}$xa=json_encode($x);$ya=json_encode($y);?>xa=<?php echo $xa?>;ya=<?php echo $ya?>;}
                    if(bpro == '福建')	 {<?php $x=array();$y=array();foreach($data_provices['福建']   as $k=>$v){$x[]=$k;$y[]=$v;}$xa=json_encode($x);$ya=json_encode($y);?>xa=<?php echo $xa?>;ya=<?php echo $ya?>;}
                    if(bpro == '贵州')	 {<?php $x=array();$y=array();foreach($data_provices['贵州']   as $k=>$v){$x[]=$k;$y[]=$v;}$xa=json_encode($x);$ya=json_encode($y);?>xa=<?php echo $xa?>;ya=<?php echo $ya?>;}
                    if(bpro == '广东')	 {<?php $x=array();$y=array();foreach($data_provices['广东']   as $k=>$v){$x[]=$k;$y[]=$v;}$xa=json_encode($x);$ya=json_encode($y);?>xa=<?php echo $xa?>;ya=<?php echo $ya?>;}
                    if(bpro == '青海') 	 {<?php $x=array();$y=array();foreach($data_provices['青海']   as $k=>$v){$x[]=$k;$y[]=$v;}$xa=json_encode($x);$ya=json_encode($y);?>xa=<?php echo $xa?>;ya=<?php echo $ya?>;}
                    if(bpro == '四川')	 {<?php $x=array();$y=array();foreach($data_provices['四川']   as $k=>$v){$x[]=$k;$y[]=$v;}$xa=json_encode($x);$ya=json_encode($y);?>xa=<?php echo $xa?>;ya=<?php echo $ya?>;}
                    if(bpro == '海南')   {<?php $x=array();$y=array();foreach($data_provices['海南']   as $k=>$v){$x[]=$k;$y[]=$v;}$xa=json_encode($x);$ya=json_encode($y);?>xa=<?php echo $xa?>;ya=<?php echo $ya?>;}
                    if(bpro == '台湾')   {<?php $x=array();$y=array();foreach($data_provices['台湾']   as $k=>$v){$x[]=$k;$y[]=$v;}$xa=json_encode($x);$ya=json_encode($y);?>xa=<?php echo $xa?>;ya=<?php echo $ya?>;}
                    if(bpro == '新疆')   {<?php $x=array();$y=array();foreach($data_provices['新疆']   as $k=>$v){$x[]=$k;$y[]=$v;}$xa=json_encode($x);$ya=json_encode($y);?>xa=<?php echo $xa?>;ya=<?php echo $ya?>;}
                    if(bpro == '西藏')   {<?php $x=array();$y=array();foreach($data_provices['西藏']   as $k=>$v){$x[]=$k;$y[]=$v;}$xa=json_encode($x);$ya=json_encode($y);?>xa=<?php echo $xa?>;ya=<?php echo $ya?>;}
                    if(bpro == '宁夏')   {<?php $x=array();$y=array();foreach($data_provices['宁夏']   as $k=>$v){$x[]=$k;$y[]=$v;}$xa=json_encode($x);$ya=json_encode($y);?>xa=<?php echo $xa?>;ya=<?php echo $ya?>;}
                    if(bpro == '广西')   {<?php $x=array();$y=array();foreach($data_provices['广西']   as $k=>$v){$x[]=$k;$y[]=$v;}$xa=json_encode($x);$ya=json_encode($y);?>xa=<?php echo $xa?>;ya=<?php echo $ya?>;}
                    if(bpro == '内蒙古') {<?php $x=array();$y=array();foreach($data_provices['内蒙古'] as $k=>$v){$x[]=$k;$y[]=$v;}$xa=json_encode($x);$ya=json_encode($y);?>xa=<?php echo $xa?>;ya=<?php echo $ya?>;}
                    if(bpro == '香港')   {<?php $x=array();$y=array();foreach($data_provices['香港']   as $k=>$v){$x[]=$k;$y[]=$v;}$xa=json_encode($x);$ya=json_encode($y);?>xa=<?php echo $xa?>;ya=<?php echo $ya?>;}
                    if(bpro == '澳门')   {<?php $x=array();$y=array();foreach($data_provices['澳门']   as $k=>$v){$x[]=$k;$y[]=$v;}$xa=json_encode($x);$ya=json_encode($y);?>xa=<?php echo $xa?>;ya=<?php echo $ya?>;}

                    specific_data(xa,ya);
                }

                function specific_data(xarray, yarray){

                    var myChart3 = echarts.init(document.getElementById('main'));
                    tabname3  = bpro+'<?php echo $tabname?>';
                    option3  = {
                        tooltip : {
                            trigger: 'axis'
                        },
                        toolbox: {
                            orient: 'vertical',
                            y: 'center',
                            show: true,
                            feature: {
                                dataView: {show: false,readOnly: false},
                                magicType: {show: true,type: ['line', 'bar']},
                                restore: {show: false},
                                saveAsImage: {show: true}
                            }
                        },
                        grid: {
                            x: 80,
                            y: 80,
                            x2: 80,
                            y2: 80
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
                            data: xarray
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
                            type: 'bar',
                            data: yarray
                        }]
                    };

                    myChart3.setOption(option3);
                }
            </script>

        </div>
    </div>
</div>


<!--<div class="tabmenu choose_bar">-->
<!--    <ul class="choose_bar">-->
<!--        <li class="active bbc_seller_bg"><a href="javascript:void(0);" data-id="1" onclick="getAjax('orderpeople')">下单会员数</a></li>-->
<!--        <li><a href="javascript:void(0);" data-id="2" onclick="getAjax('orderprice')">下单金额</a></li>-->
<!--        <li><a href="javascript:void(0);" data-id="3">下单量</a></li>-->
<!--    </ul>-->
<!--</div>-->

<script>
    $(".tabmenu li a").click(function () {
        $("#week").css("display", "none");
        $(".tabmenu li").removeClass("active bbc_seller_bg");
        $(this).parent("li").addClass("active bbc_seller_bg");
        $("#stype option:checked").removeAttr('selected');
        $("#year option:checked").removeAttr('selected');
        $("#month option:checked").removeAttr('selected');
        var year = <?= $tyear;?>;
        var month = <?= $tmonth;?>;
        $("#year option[value="+ year +"]").attr('selected', true);
        $("#month option[value="+ month +"]").attr('selected', true);

        var id = $(this).attr("data-id");
        if(id == 1)
        {
            $('.kinds').val(1);
            getAjax('orderpeople');
        }
        else if(id == 2)
        {
            $('.kinds').val(2);
            getAjax('orderprice');
        }
        else if(id == 3)
        {
            $('.kinds').val(3);
            getAjax('ordernum');
        }
    })

    function getAjax(a)
    {
        $('.right_content').html('<div class="loading"></div>');
        var url = "?ctl=Seller_Analysis_Operation&met=" + a + "&typ=e";
        var pars = {};
        $.post(url,pars,showResponse);
        function showResponse(originalRequest)
        {
            $(".right_content").html(originalRequest);
        }
    }

</script>
<?php
include $this->view->getTplPath() . '/' . 'seller_footer.php';
?>



