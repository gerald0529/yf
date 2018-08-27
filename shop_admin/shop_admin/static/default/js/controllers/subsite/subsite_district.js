// JavaScript Document
function district(obj){
	if(obj.value==''){
		obj.focus();
		return false;
	}

	// 请求全部被添加过的地区id
	var out_div_id = $(obj).parents('.area_in_sub').attr('id');
	$.get(SITE_URL + '?ctl=Subsite_Config&met=getLimitDistrictAll&typ=json',function(limit){
        if(limit.status==200)
        {
            limit_ids = limit.data.limit_ids;
            // 请求某一地区的下级地区并过滤掉已添加过的地区
            change_district(obj.value,out_div_id,limit_ids);
        }
    });
	
}
function change_district(value,mark,limit_district){
	var valarray = value.split('|');
	var area_mark = mark.split('_')[1];
	// alert(area_mark);
	//alert(valarray[0]);
	var url = SITE_URL + '?ctl=Base_District&met=district&typ=json&nodeid='+valarray[0];

	$.getJSON(url,function(data)
	{
        data = data.data.items;	
        if(data && data.length > 0)
		{       
			var str = '<option value="all">--请选择--</option>';

            var class_div_id = parseInt(valarray[1])+1;
            $.each(data, function(i, district_row)
			{	
				if(jQuery.inArray(district_row.district_id,limit_district)==-1){
                	str += '<option value="'+district_row.district_id+'|'+class_div_id+'">'+district_row.district_name+'</option>';
        		}
            });
			
			$('#select_'+area_mark+'_'+class_div_id).removeClass('hidden');
			for (j=class_div_id; j<=4; j++)
			{
				$('#select_'+area_mark+'_'+(j+1)).addClass('hidden');
			}
		
			$('#select_'+area_mark+'_'+class_div_id).empty();
			$('#select_'+area_mark+'_'+class_div_id).append(str);
			$('#select_'+area_mark+'_'+class_div_id).nextAll('select').empty();
        }
        else
        {
            for(var i= parseInt(valarray[1]); i<4; i++){
				$('#select_'+area_mark+'_'+(i+1)).empty();
				$('#select_'+area_mark+'_'+(i+1)).addClass('hidden');
			}
        }
    });
}
