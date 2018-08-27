<?php if (!defined('ROOT_PATH')){exit('No Permission');}?>
<?php
include $this->view->getTplPath() . '/' . 'seller_header.php';
?>
          

        <div class="tabmenu">
            <ul>
                    <li class="active bbc_seller_bg"><a href="./index.php?ctl=Seller_Shop_Entityshop&met=entityShop&typ=e"><?=__('地图显示')?></a></li>
                   
                    <li ><a href="./index.php?ctl=Seller_Shop_Entityshop&met=entityShop&typ=e&act=list"><?=__('列表显示')?></a></li>
               
                 
            </ul>
                <a class="button add button_blue bbc_seller_btns"  id="add_map" ><i class="iconfont icon-jia"></i><?=__('添加实体店铺')?></a>
           </div>    
                <div class="alert">
                    <ul>
                        <li><?=__('1、系统借助“百度地图”进行定位，使用时要确保网络能正常访问。')?></li>
                        <li><?=__('2、由于地图的窗口大小限制，最多可添加20个地址。可在“列表显示”中修改和删除已添加的地址。')?></li>
                    </ul>
                </div>
<?php
?>
<div id="baidu_map" style="height:600px;border:1px solid gray"></div>
<link href="<?= $this->view->css_com ?>/jquery/plugins/dialog/green.css" rel="stylesheet">
<script type="text/javascript" src="<?= $this->view->js_com ?>/plugins/jquery.dialog.js"></script>
<script type="text/javascript" src="https://api.map.baidu.com/api?v=2.0&ak=5At3anZe83x8oOpFap42Gt8eHYpy3wm9"></script>
<script type="text/javascript">
    var map = new BMap.Map("baidu_map", {enableMapClick:false});
    var geo = new BMap.Geocoder();
	var city = new BMap.LocalCity();
	var top_left_navigation = new BMap.NavigationControl();
	var overView = new BMap.OverviewMapControl();
	var currentArea = '';//当前地图中心点的区域对象
	var currentCity = '';//当前地图中心点的所在城市
	var idArray = new Array();

	map.addControl(top_left_navigation);
	map.addControl(overView);
	map.enableScrollWheelZoom(true);
	city.get(local_city);
	function local_city(cityResult){
	    map.centerAndZoom(cityResult.center, 15);
	    currentCity = cityResult.name;
	    	var pointArray = new Array();
	        var point = '';
	        var marker = '';
	        var label = '';
	        var k = 0;
                <?php if($data['items']){
                    
                    foreach ($data['items'] as $key => $value) {

                        if($value['lng']&&$value['lat']){
               ?>
                     point = new BMap.Point(<?=$value['lng']?>, <?=$value['lat']?>);
	            pointArray[k++] = point;
	            label = new BMap.Label("<?=$value['entity_name']?>",{offset:new BMap.Size(20,-10)});
	            marker = new BMap.Marker(point);
	            marker.setTitle('地址-'+k);
	            marker.setLabel(label);
	            marker.enableDragging();
	            marker.addEventListener("dragend",getMarkerPoint);
	            map.addOverlay(marker);
	            idArray['地址-'+k] = <?=$value['entity_id']?>;
                    
                <?php } } }?>
	        map.setViewport(pointArray);
	    	}
	function getMarkerPoint(e){//拖拽结束时通过点找到地区
	    var marker = e.target;
	    var point = marker.getPosition();
	    var title = marker.getTitle();
	    var map_id = idArray[title];

	    getPointArea(point,function(pointArea){
	        var obj = {
	            'entity_id': map_id,
	            'entity[province]': pointArea.province,
	            'entity[city]': pointArea.city,
	            'entity[district]': pointArea.district,
	            'entity[street]': pointArea.street,
	            'entity[lng]': point.lng,
	            'entity[lat]': point.lat
	            };

    		$.ajax({
    			type: "POST",
    			url: './index.php?ctl=Seller_Shop_Entityshop&met=editEntity&typ=e&',
    			data: obj,
    			async: false,
    		    success: function(rs){
    		    }
    		});
	    });
	}
        
        function getCity(func)
        {
            //当前地图中心点所在城市
	    var point = map.getCenter();//当前地图中心点
	    getPointArea(point,function(pointArea)
            {
                currentArea = pointArea;
                currentCity = ''+pointArea.city;
                currentlng = point.lng;
                currentlat = point.lat;
                func(currentArea.province,currentArea.district,currentArea.street,currentArea.city,currentlng,currentlat);
                //setPoint(point);
	    });
	}


	function getPointArea(point,callback){//通过点找到地区
	    geo.getLocation(point, function(rs){
	        var addComp = rs.addressComponents;
	        if(addComp.province != '') callback(addComp);
	    }, {numPois:1});
	}
        
        function getArea(func)
        {
            getCity(func); 
        }
        
         $('#add_map').click(function ()
        {
            var data = {
                callback: getArea
            };
            
            $.dialog({
                title: "<?=__('添加实体店铺')?>",
                content: 'url: ' + SITE_URL + '?ctl=Seller_Shop_Entityshop&met=addEntityInfo&typ=e',
                data: data,
                width: 600,
                height: 450,
                max: !1,
                min: !1,
                cache: !1,
                lock: !0
            });

        });
        
        
        
        
        
</script>

<?php
include $this->view->getTplPath() . '/' . 'seller_footer.php';
?>
