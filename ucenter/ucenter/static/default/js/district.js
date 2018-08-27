// JavaScript Document
function sd()
{
	$("#d_1").attr('class','hidden')
	$("#d_2").removeClass('hidden')
}
function district(obj){
	
	$(obj).children('option').attr('class','');
	if(!obj.value)
	{
		var valarray = obj.id.split('_');
		$('#'+obj.id).nextAll('select').empty();
		$('#'+obj.id).nextAll('select').addClass('hidden');
		$('#t').val('');
		$('#id_'+valarray[1]).nextAll("input'").val('');
		$('#id_'+valarray[1]).val('');
		return false;	
	}
	change_district(obj.value);
}
function change_district(value){
	var valarray = value.split('|');
	//alert(valarray[0]);
	var url = SITE_URL + '?ctl=Base_District&met=district&typ=json&pid='+valarray[0];

	$.getJSON(url,function(data)
	{
        data = data.data.items;	
        if(data && data.length > 0)
		{       
			var str = '<option value="">--请选择--</option>';
            var class_div_id = parseInt(valarray[1])+1;
            $.each(data, function(i, district_row)
			{
                str += '<option value="'+district_row.district_id+'|'+class_div_id+'">'+district_row.district_name+'</option>';
            });
		
			$('#select_'+class_div_id).removeClass('hidden');		
			$('#id_'+valarray[1]).val(valarray[0]);
			for (j=class_div_id; j<=4; j++)
			{
				$('#select_'+(j+1)).addClass('hidden');
				$('#id_'+j).val('');
			}
		
			$('#select_'+class_div_id).empty();
			$('#select_'+class_div_id).append(str);
			$('#select_'+class_div_id).nextAll('select').empty();
			
			var strs="";
			$.each($("option[value='"+value+"']"),function(i){
				$(this).addClass('classClick');
			});
			$.each($('option[class="classClick"]'),function(i){
				strs+=$(this).text()+" ";
			});
			strs=strs.substring(0,strs.length-1);
			$('#t').val(strs);
        }
        else
        {
            for(var i= parseInt(valarray[1]); i<4; i++){
				$('#select_'+(i+1)).empty();
				$('#select_'+(i+1)).addClass('hidden');
			}
			var str="";
			$('#id_'+valarray[1]).val(valarray[0]);
			$.each($("option[value='"+value+"']"),function(i){
				$(this).attr('class','classClick');
			});
			$.each($('option[class="classClick"]'),function(i){
				str+=$(this).text()+" ";
			});
			str=str.substring(0,str.length-1);
			$('#t').val(str);
        }
    });
}