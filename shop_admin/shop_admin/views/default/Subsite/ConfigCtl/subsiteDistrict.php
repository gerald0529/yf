<?php if (!defined('ROOT_PATH')) {exit('No Permission');}?>
<?php
include $this->view->getTplPath() . '/'  . 'header.php';
$district = $data['district'];
?>
<link href="<?=$this->view->css?>/index.css" rel="stylesheet" type="text/css">
<style>
.page{width:963px !important;min-width:900px;}
.hidden{display:none !important;}
.area_in_sub{height:30px;line-height: 30px;margin-bottom: 10px;}
.area_in_sub div span{margin-right: 10px;} 
.area_in_sub .second{width:95%;display:inline-block;}
.area_in_sub .second select{width:130px;height:30px;}
.area_in_sub .delete_area{width:3%;display:inline-block;cursor:pointer;text-align:center;}
.add_row{display:block;color:blue;}
</style>
</head>
<body>

<div class="wrapper">
	<form id="manage-form" action="#">
		<div class="ncap-form-default">
            <dl class="row diqu_row">
                <dt class="tit">
                    <label for="sub_site_district_ids">地区选择</label>
                    <a href="javascript:add_district()" class="add_row">添加地区</a>
                </dt>
                <dd class="opt">
                	<div class="area_in_sub" id="area_1">
                		<input type="hidden">
						<div id="d_1_1" class="hidden old">
							<span class="district_name"></span>
						</div>
						<div id="d_1_2" class="second">
							<select id="select_1_1" name="select_1" onChange="district(this);">
								<option value=""><?=_('--请选择--')?></option>
								<?php foreach($district['items'] as $key=>$val){ ?>
								<option value="<?=$val['district_id']?>|1"><?=$val['district_name']?></option>
								<?php } ?>
							</select>
							<select id="select_1_2" name="select_2" onChange="district(this);" class="hidden"></select>
							<select id="select_1_3" name="select_3" onChange="district(this);" class="hidden"></select>
							<select id="select_1_4" name="select_4" onChange="district(this);" class="hidden"></select>
							<!-- 若要减少地区联动的等级，只需从后向前以此注释掉页面中的select标签,同时增加地区联动结构中的对应select框也要去除 -->

						</div>
						<div class="delete_area">X</div>
					</div>
                </dd>
               
            </dl>
            
        </div>
        
	</form>

</div>

<script type="text/javascript">
// 重新载入的时候 

    // 在页面上增加一个地区结构
    function add_district(){
        var mark = $('.area_in_sub:last').attr('id');
        var area_num = mark.split('_')[1];
        var this_area_mark = parseInt(area_num)+1;
        var str = '';
        str = '<div class="area_in_sub" id="area_'+this_area_mark+'"><input type="hidden"><div id="d_'+this_area_mark+'_1" class="hidden old"><span class="district_name"></span></div><div id="d_'+this_area_mark+'_2" class="second"><select id="select_'+this_area_mark+'_1" name="select_1" onChange="district(this);"><option value=""><?=_("--请选择--")?></option><?php foreach($district['items'] as $key=>$val){ ?><option value="<?=$val['district_id']?>|1"><?=$val['district_name']?></option><?php } ?></select> <select id="select_'+this_area_mark+'_2" name="select_2" onChange="district(this);" class="hidden"></select> <select id="select_'+this_area_mark+'_3" name="select_3" onChange="district(this);" class="hidden"></select> <select id="select_'+this_area_mark+'_4" name="select_4" onChange="district(this);" class="hidden"></select> </div><div class="delete_area">X</div></div>';
        $('.diqu_row .opt').append(str);

    }

    // 删除一个地区
    $('div').delegate('.delete_area','click',function(){
        if($('.area_in_sub').length > 1){
            $(this).parents('.area_in_sub').remove();
        }else{
            add_district();
            $(this).parents('.area_in_sub').remove();
        }
    });


    function initField(){ 
        // 编辑
        $("#subsite_id").val(rowData.subsite_id);
        var district_ids = rowData.sub_site_district_ids;
        if(district_ids){
            // 根据分站地区id请求上级地区并显示
            $.get(SITE_URL+'?ctl=Subsite_Config&met=getSubsiteDistrictTree&typ=json&district_id='+district_ids,function(b){
                if(b.status==200){
                    $('#subsite_district_ids').val(b.data.subsite_district_ids);
                    var district_list = b.data.subsite_district_name;
                    for(var num=0,len=district_list.length;num<len;num++){
                        var district_mark = num+1;
                        if(district_mark==1){ 
                            $('#d_'+district_mark+'_1').removeClass('hidden');
                            $('#d_'+district_mark+'_1').addClass('second');
                            $('#d_'+district_mark+'_1 .district_name').text(district_list[num]);    
                            $('#d_'+district_mark+'_2').hide();
                            $('#area_'+district_mark+' input[type="hidden"]').val(b.data.subsite_district_ids[num]);
                        }else{
                            add_district();
                            $('#d_'+district_mark+'_1').removeClass('hidden');
                            $('#d_'+district_mark+'_1').addClass('second');
                            $('#d_'+district_mark+'_1  .district_name').text(district_list[num]);
                            $('#d_'+district_mark+'_2').hide();
                            $('#area_'+district_mark+' input[type="hidden"]').val(b.data.subsite_district_ids[num]);
                        }
                    }
                }
            });
        }
                

        // 如果分站存在上级分站 请求上级分站id级名字并分配到页面
        if(rowData.sub_site_parent_id)
        {
            $.get(SITE_URL + '?ctl=Subsite_Config&met=getSubsiteName&typ=json&id=' + rowData.sub_site_parent_id, function(a){
                if(a.status==200)
                {
                    $("#parent_subsite").val(a.data.sub_site_name);
                    $("#parent_id").val(a.data.id);
                }
            });
        }
    }
    
    function initPopBtns()
    {
        var t = "add" == oper ? ["保存", "关闭"] : ["确定", "取消"];
        api.button({
            id: "confirm", name: t[0], focus: !0, callback: function ()
            {
                postData(oper, rowData.subsite_id);
                return cancleGridEdit(), $("#manage-form").trigger("validate"), !1;
            }
        }, {id: "cancel", name: t[1]})
    }
    function postData(t, e){   
        // 准备分站选中地区id
        var sub_site_district_ids = '';
        var select_yes = $('select:visible').length;
        // 页面中存在可见的下拉框
        if(select_yes){
            $('.area_in_sub').each(function(e,ele){
                // 下拉框中的值
                var district = $(ele).find('select:visible:last');
                if(district.val()){
                    if(district.val()=='all'){
                        district = district.prev().val();
                        district = district.split('|')[0];
                    }else{
                        district = district.val();
                        district = district.split('|')[0];
                    }

                    sub_site_district_ids += district+',';
                }

                // 如果编辑分站时存在没有改变过的地区id  隐藏域中的值
                var orig_district = $(ele).find('.old');
                if(orig_district.hasClass('second')){
                    orig_district_ids=orig_district.prev().val();
                    sub_site_district_ids += orig_district_ids+',' ;
                }

            });

            sub_site_district_ids = sub_site_district_ids.substring(0,sub_site_district_ids.length-1);

        }else{
            // 获取到编辑分站页面中已存在的地区id 没有对已存在的地区进行删新增的
            $('.area_in_sub input[type="hidden"]').each(function(e,ele){
                sub_site_district_ids += ele.value+',';
            });
            sub_site_district_ids = sub_site_district_ids.substring(0,sub_site_district_ids.length-1);
        }

        var params = {sub_site_district_ids:sub_site_district_ids,subsite_id:rowData.subsite_id};
        Public.ajaxPost(SITE_URL +"?ctl=Subsite_Config&typ=json&met=addSubsiteDistrict", params, function (e)
        {
            if (200 == e.status)
            {
                parent.parent.Public.tips({content: "设置分站地区成功！"});
                callback && "function" == typeof callback && callback(e.data, t, window)
            }
            else
            {
                parent.parent.Public.tips({type: 1, content: "设置分站地区失败！" + e.msg})
            }
        })
    }
    function cancleGridEdit()
    {
        null !== curRow && null !== curCol && ($grid.jqGrid("saveCell", curRow, curCol), curRow = null, curCol = null)
    }

var curRow, curCol, curArrears, $grid = $("#grid"), $_form = $("#manage-form"), api = frameElement.api, oper = api.data.oper, rowData = api.data.rowData || {}, callback = api.data.callback;
//alert(rowData.sub_site_district_ids);
initPopBtns();
initField();
</script>
<script type="text/javascript" src="<?=$this->view->js?>/controllers/subsite/subsite_district.js" charset="utf-8"></script>
<?php
include $this->view->getTplPath() . '/' . 'footer.php';
?>