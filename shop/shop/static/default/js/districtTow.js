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
		obj.parent().find('.'+obj.id).nextAll('select').empty();
		obj.parent().find('.'+obj.id).nextAll('select').addClass('hidden');
		obj.parent().parent().find('.t').val('');
		obj.parent().find('.id_'+valarray[1]).nextAll("input'").val('');
		obj.parent().find('.id_'+valarray[1]).val('');
		return false;	
	}
	change_district(obj,obj.value);
}
function change_district(obj,value){
	var valarray = value.split('|');
          obj = $(obj);
	//alert(valarray[0]);
	var url = SITE_URL + '?ctl=Base_District&met=district&typ=json&pid='+valarray[0];

	$.getJSON(url,function(data)
	{
        data = data.data.items;	
        if(data && data.length > 0)
	{       
            var str = '<option value="">--请选择--</option>';
            var class_div_id = parseInt(valarray[1])+1;
          //  alert(class_div_id);
            $.each(data, function(i, district_row)
			{
                str += '<option value="'+district_row.district_id+'|'+class_div_id+'">'+district_row.district_name+'</option>';
            });
		
			obj.parent().find('.select_'+class_div_id).removeClass('hidden');		
			obj.parent().parent().find('.id_'+valarray[1]).val(valarray[0]);
			for (j=class_div_id; j<=5; j++)
			{   
				obj.parent().find('.select_'+(j+1)).addClass('hidden');
				obj.parent().parent().find('.id_'+j).val('');
			}
                        
			obj.parent().find('.select_'+class_div_id).empty();
			obj.parent().find('.select_'+class_div_id).append(str);
			obj.parent().find('.select_'+class_div_id).nextAll('select').empty();

			var strs="";
			$.each(obj.parent().find("option[value='"+value+"']"),function(i){
				$(this).addClass('classClick');
			});
			$.each(obj.parent().find('option[class="classClick"]'),function(i){
				strs +=$(this).text()+" ";
			});
			strs=strs.substring(0,strs.length-1);
                        obj.parent().parent().find('.t').val(strs);
        }
        else
        {
            for(var i= parseInt(valarray[1]); i<5; i++){
				obj.parent().find('.select_'+(i+1)).empty();
				obj.parent().find('.select_'+(i+1)).addClass('hidden');
			}
			var str="";
			obj.parent().parent().find('.id_'+valarray[1]).val(valarray[0]);
			$.each(obj.parent().find("option[value='"+value+"']"),function(i){
				$(this).attr('class','classClick');
			});
			$.each(obj.parent().find('option[class="classClick"]'),function(i){
				str+=$(this).text()+" ";
			});
			str=str.substring(0,str.length-1);
			obj.parent().parent().find('.t').val(str);
        }
    });
}


function district_yingye(obj_yingye){
    $(obj_yingye).children('option').attr('class','');
    if(!obj_yingye.value)
    {
        var valarray = obj_yingye.id.split('_');
        obj_yingye.parent().find('.'+obj_yingye.id).nextAll('select').empty();
        obj_yingye.parent().find('.'+obj_yingye.id).nextAll('select').addClass('hidden');
        obj_yingye.parent().parent().find('.t').val('');
        obj_yingye.parent().find('.id_'+valarray[1]).nextAll("input'").val('');
        obj_yingye.parent().find('.id_'+valarray[1]).val('');
        return false;	
    }
    change_district_yingye(obj_yingye,obj_yingye.value);
}


function change_district_yingye(obj_yingye,value){
        var valarray = value.split('|');
        obj_yingye = $(obj_yingye);
        var url = SITE_URL + '?ctl=Base_District&met=district&typ=json&pid='+valarray[0];

        $.getJSON(url,function(data){
            data = data.data.items;	
            if(data && data.length > 0){       
                var str = '<option value="">--请选择--</option>';
                var class_div_id = parseInt(valarray[1])+1;
                $.each(data, function(i, district_row){
                    str += '<option value="'+district_row.district_id+'|'+class_div_id+'">'+district_row.district_name+'</option>';
                });

                obj_yingye.parent().find('.select_'+class_div_id).removeClass('hidden');	
                for (j=class_div_id; j<=4; j++)
                {   
                    obj_yingye.parent().find('.select_'+(j+1)).addClass('hidden');
                }

                obj_yingye.parent().find('.select_'+class_div_id).empty();
                obj_yingye.parent().find('.select_'+class_div_id).append(str);
                obj_yingye.parent().find('.select_'+class_div_id).nextAll('select').empty();

                var strs="";
                $.each(obj_yingye.parent().find("option[value='"+value+"']"),function(i){
                    $(this).addClass('classClick');
                });
                $.each(obj_yingye.parent().find('option[class="classClick"]'),function(i){
                    strs +=$(this).text()+" ";
                });
                strs=strs.substring(0,strs.length-1);
                obj_yingye.parent().parent().find('.t').val(strs);
            }else{
                for(var i= parseInt(valarray[1]); i<4; i++){
                        obj_yingye.parent().find('.select_'+(i+1)).empty();
                        obj_yingye.parent().find('.select_'+(i+1)).addClass('hidden');
                }
                var str="";
                $.each(obj_yingye.parent().find("option[value='"+value+"']"),function(i){
                    $(this).attr('class','classClick');
                });
                $.each(obj_yingye.parent().find('option[class="classClick"]'),function(i){
                        str+=$(this).text()+" ";
                });
                str=str.substring(0,str.length-1);
                obj_yingye.parent().parent().find('.t').val(str);
            }
    });
}