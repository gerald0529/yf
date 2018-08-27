<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Document</title>
    <link href="<?= $this->view->css ?>/goods-detail.css" rel="stylesheet">
    <link href="<?= $this->view->css ?>/base.css" rel="stylesheet" type="text/css">
    <script type="text/javascript" src="<?= $this->view->js_com ?>/jquery.js" charset="utf-8"></script>
    <script>
        $(document).ready(function () {
            $(".dialog_close_button").click(function () {
                $(".store-mention-alert").css("display", "none");
            });
            var inde;
            $(".tabs-nav li").click(function () {
                inde = $(this).index();
                $(".tabs-nav li").removeClass("tabs-selected");
                $(".order-info .tabs-panel").removeClass("tabs-active");
                $(".order-info .tabs-panel").eq(inde).addClass("tabs-active");
                $(this).addClass("tabs-selected");
            });
            
            //获取定位地址
            var current_area = "<?= $current_province ? '\"$current_province\"' : ''; ?>";
            if (current_area) {
                var option = $("#select_1 > option[position='" + current_area + "']");
                if (option[0]) {
                    $("#select_1").val(option.val()).trigger("change");
                }
            }
            
            getchain('getAll');
        });
        var SITE_URL = "<?=Yf_Registry::get('url')?>";
        var api = frameElement.api;
        var callback = api.data.callback;
        
        function getchain(selectValue) {
            
            var chainProvinces = <?= $chain_provinces ?>,
                chainCities = <?= $chain_cities ?>,
                arr = selectValue.split('|'),
                level = arr[1],
                provinceOrCity = arr[0],
                rows = [];
            
            if (selectValue == 'getAll') {
                for (var f in chainCities) {
                    for (var s in chainCities[f]) {
                        rows.push(chainCities[f][s]);
                    }
                }
            } else {
                if (!provinceOrCity) {
                    return false; //请选择
                }
                if (level == 1) { //省
                    var province_id = provinceOrCity;
                    if (chainProvinces[province_id]) {
                        var city_ids = chainProvinces[province_id];
                        for (var i = 0, l = city_ids.length; i < l; i++) {
                            for (var m = 0, n = chainCities[city_ids[i]].length; m < n; m++) {
                                rows.push(chainCities[city_ids[i]][m]);
                            }
                        }
                    }
                    console.info(rows);
                } else { //市
                    var city_id = provinceOrCity;
                    if (chainCities[city_id]) {
                        rows = chainCities[city_id];
                    }
                }
                
                $('ul[nctype="chain_see"]').html('');
                $('.ncs-chain-no-date').remove();
                if (rows.length == 0) {
                    $('<div class="ncs-chain-no-date"><?=__("很抱歉，该区域暂无门店有货，正努力补货中")?>•••</div>').insertAfter('ul[nctype="chain_see"]');
                    return false;
                }
            }
            
            var chainIds = [];
            for (var i = 0; i < rows.length; i++) {
                _chain = rows[i];
                if ($.inArray(_chain.chain_id, chainIds) > -1) {
                    return false;
                }
                chainIds.push(_chain.chain_id);
                var callback_url = SITE_URL + '?ctl=Buyer_Cart&met=confirmChain&isGetBestOffer=1&goods_id=<?=$goods_id?>&chain_id=' + _chain.chain_id;
                $('<li><div class="handle"><a href="javascript:;" onclick="callback(\'' + callback_url + '\',' + _chain.chain_id +')"><?=__('马上自提')?>></a></div><h5><i></i><a target="_blank" href="' + SITE_URL + '?ctl=Goods_Goods&met=getChain&chain_id=' + _chain.chain_id + '">' + _chain.chain_name + '</a></h5>' +
                    '<p>联系电话：' + _chain.chain_mobile +
                    '<p class="wp80">' + _chain.chain_province + ' ' + _chain.chain_city + ' ' + _chain.chain_county + ' ' + _chain.chain_address + '</p></li>').appendTo('ul[nctype="chain_see"]');
            }
            return false;
        }
    
    </script>
</head>
<body>
<div class="store-mention-alert">
    <div class="store-mention">
        <div class="dialog_body">
            <div class="dialog_content">
                <div class="chain-show">
                    <dl>
                        <dt><?= __('门店所在地区：') ?></dt>
                        <dd>
                            <input type="hidden" name="address_area" id="t" value="" />
                            <input type="hidden" name="province_id" id="id_1" value="" />
                            <input type="hidden" name="city_id" id="id_2" value="" />
                            <input type="hidden" name="area_id" id="id_3" value="" />
                            <div id="d_2">
                                <select id="select_1" name="select_1" onChange="district(this);getchain(this.value);">
                                    <option value="">--<?= __('请选择') ?>--</option>
                                    <?php foreach ($district['items'] as $key => $val) { ?>
                                        <option value="<?= $val['district_id'] ?>|1" position="<?= $val['district_name'] ?>"><?= $val['district_name'] ?></option>
                                    <?php } ?>
                                </select>
                                <select id="select_2" name="select_2" onChange="district(this);getchain(this.value);" class="hidden"></select>
                                <!--<select id="select_3" name="select_3" onChange="district(this);getchain(this);" class="hidden"></select>-->
                            </div>
                        </dd>
                    </dl>
                    <div class="chain-list">
                        <ul nctype="chain_see"></ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>
<script type="text/javascript" src="<?= $this->view->js ?>/district.js"></script>
