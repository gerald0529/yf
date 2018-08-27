var curRow,
    curCol,
    loading,
    urlParam = Public.urlParam(),
    SYSTEM = system = parent.SYSTEM;

var handle = {
    operate: function (t, e)
    {
        var type_id = urlParam.type_id;
        if ("add" == t)
        {
            var i = "新增属性", a = {oper: t, type_id:type_id, callback: this.callback};
        }
        else
        {
            var i = "修改属性", a = {oper: t, type_id:type_id, rowData: $("#grid").data("gridData")[e], callback: this.callback};
        }
        $.dialog({
            title: i,
            content: "url:./index.php?ctl=Goods_Property&met=property",
            data: a,
            width: 550,
            height: 450,
            max: !1,
            min: !1,
            cache: !1,
            lock: !0
        })
    }, callback: function (t, e, i)
    {
        var a = $("#grid").data("gridData");
        if (!a)
        {
            a = {};
            $("#grid").data("gridData", a)
        }
        a[t.property_id] = t;
        if ("edit" == e)
        {
            $("#grid").jqGrid("setRowData", t.property_id, t);
            i && i.api.close();
        }
        else
        {
            $("#grid").jqGrid("addRowData", t.property_id, t, "last");
            i && i.api.close();
        }
    }, del: function (t)
    {
        $.dialog.confirm("删除的属性将不能恢复，请确认是否删除？", function ()
        {
            Public.ajaxPost("./index.php?ctl=Goods_Property&met=remove&typ=json", {id: t}, function (e)
            {
                if (e && 200 == e.status)
                {
                    parent.Public.tips({content: "属性删除成功！"});
                    $("#grid").jqGrid("delRowData", t);
                }
                else
                {
                    parent.Public.tips({type: 1, content: "属性删除失败！" + e.msg})
                }
            })
        })
    }
};
function init()
{
    urlParam.type_id ? Public.ajaxPost('./index.php?ctl=Goods_Type&met=getType&typ=json', {type_id: urlParam.type_id,property_is_search:0},
        function (a)
        {
            200 === a.status ? (rowData = a.data, initField(), initGrid(rowData.property)) : parent.Public.tips({
                type: 1,
                content: a.msg
            })
        }) : (initField(), initGrid(rowData = ''))
}

function initField()
{
    if (urlParam.type_id)
    {
        $('#type_name').val(rowData.type_name);
        $('#type_displayorder').val(rowData.type_displayorder);
    }
    initSpec(), initBrand();
}
//商品规格
function initSpec()
{
    $.get('./index.php?ctl=Goods_Spec&met=getSpec&typ=json', function (a)
    {
        if (a.status == 200)
        {
            var a_str = "", b_str = '', c_str = '';
            for (var i = 0; i < a.data.length; i++)
            {
                var s_id = a.data[i]['spec_id'];
                if (urlParam.type_id)
                {
                    if (rowData.spec[s_id])
                    {
                        b_str = "checked='checked'";
                        c_str = "";
                    }
                    else
                    {
                        b_str = "";
                        c_str = "";
                    }
                }
                else
                {
                    b_str = "";
                    c_str = "";
                }
                c_str = 'ipt-dis';
                a_str += "<div style='width:150px;float: left;'><input type='checkbox' " + b_str + " value='" + a.data[i]['spec_id'] + "' name='spec_id'><span class='mg-right vm iblock one-overflow w130 " + c_str + "'>" + a.data[i]['spec_name'] + '</span>' + '</div>';
            }

            $('#type_spec').html(a_str + '</select>');
        }
    });
}
//商品品牌
function initBrand()
{
    $.get('./index.php?ctl=Goods_Brand&met=getBrands&typ=json', function (b)
        {
            if (b.status == 200)
            {
                var a_str = "", b_str = '', c_str = '';
                for (var i = 0; i < b.data.length; i++)
                {
                    var b_id = b.data[i]['brand_id'];
                    if (urlParam.type_id)
                    {
                        if (rowData.brand[b_id])
                        {
                            b_str = "checked='checked'";
                            c_str = "";
                        }
                        else
                        {
                            b_str = "";
                            c_str = "";
                        }
                    }
                    else
                    {
                        b_str = "";
                        c_str = "";
                    }
                    c_str = 'ipt-dis';
                    a_str += "<div style='width:150px;float: left;'><input type='checkbox' " + b_str + " value='" + b.data[i]['brand_id'] + "' name='brand_id'><span class='mg-right one-overflow vm iblock w130 " + c_str + "'>" + b.data[i]['brand_name'] + '</span>' + '</div>';
                }
                $('#type_brand').html(a_str + '</select>');
            }
        }
    );
}
//Business.billsEvent(this, 'property');

var grid_row = Public.setGrid();
function initGrid()
{
    var t = ["操作", "排序", "属性名称", '属性内容', '显示'], e = [{
        name: "operate",
        width: 100,
        fixed: !0,
        align: "center",
        formatter: Public.operFmatter
    },
        {name: "property_displayorder", index: "property_displayorder", align: "center", width: 100},
        {name: "property_name", index: "property_name", width: 200},
        {name: "property_item", index: "property_item", width: 480},
        {name: "property_format", index: "property_format", width: 60},
    ];

    $("#grid").jqGrid({
        data: rowData.property,
        datatype: "local",
        autowidth: true,
        shrinkToFit: true,
        forceFit: true,
        width: grid_row.w,
        height: grid_row.h,
        altRows: !0,
        gridview: !0,
        colNames: t,
        colModel: e,
        cmTemplate: {
            sortable: !1,
            title: !1
        },
        localReader: {
            id: "property_id"
        },
        loadComplete: function ()
        {
            var e = {};

            if (urlParam.type_id)
            {
                t = rowData.property;
            }
            else
            {
                t = '';
            }

            for (var i = 0; i < t.length; i++)
            {
                var a = t[i];
                e[a.id] = a;
            }
            $("#grid").data("gridData", e);
        }
    });

    //新增
    $('#btn-add').on('click', function(e) {
        e.preventDefault();
        handle.operate('add');
    });
}

$('#submit_data').click(function ()
{
    if(rowData.id)
    {
        postData('editType', rowData.id);
    }
    else
    {
        postData('addType');
    }

    //$("#type_form").trigger("validate");
});

var lock = false;
function postData(oper, id)
{
    if (lock === true) {
        return false;
    }
    lock = true;
    $('#type_form').validator({
        ignore: ':hidden',
        theme: 'yellow_bottom',
        timely: 1,
        stopOnError: true,
        fields: {
            'type_name': 'required;',
            'type_displayorder': 'required;'
        },
        valid: function (form)
        {


            var msg = oper == 'addType' ? '新增分类' : '编辑分类';

            var type_name = $.trim($('#type_name').val());
            var type_displayorder = $.trim($('#type_displayorder').val());
            var spec = [];
            var brand = [];
            var property = rowData.property;

            for (var i = 0; i < $("input[name = 'spec_id']:checked").length; i++)
            {
                spec[i] = $("input[name = 'spec_id']:checked").eq(i).val();
            }

            for (var i = 0; i < $("input[name = 'brand_id']:checked").length; i++)
            {
                brand[i] = $("input[name = 'brand_id']:checked").eq(i).val();
            }
            //var type_spec           = $("input[name = 'spec_id']:checked").val();
            //var type_brand          = $("input[name = 'brand_id']:checked").val();
            var params = {
                type_name: type_name,
                type_displayorder: type_displayorder,
                type_spec: spec,
                type_brand: brand,
                type_property: property
            };
            id ? params.id = id : '';

            Public.ajaxPost('./index.php?ctl=Goods_Type&typ=json&met=' + (oper == 'addType' ? 'addType' : 'editType'), params, function (data)
            {
                if (data.status == 200)
                {
                    rowData = data.data;
                    rowData.operate = oper;
                    updateType();


                }
                else
                {
                    lock = false;
                    parent.parent.Public.tips({type: 1, content: msg + '失败！' + data.msg});
                }
            }, function () { //error
                lock = false;
            });

        },
    }).on("click", "a.submit-btn", function (e)
    {
        $(e.delegateTarget).trigger("validate");
    });
}

function initEvent()
{
    $("#grid").on("click", ".operating .ui-icon-pencil", function (t)
    {
        t.preventDefault();
        if (Business.verifyRight("INVLOCTION_UPDATE"))
        {
            var e = $(this).parent().data("id");
            handle.operate("edit", e)
        }
    });
    $('#grid').on("click", ".operating .ui-icon-trash", function (t)
    {
        t.preventDefault();
        if (Business.verifyRight("INVLOCTION_UPDATE"))
        {
            var e = $(this).parent().data("id");

            handle.del(e)
        }
    });
}

$(function ()
{
    init(), initEvent(); 
    
});

//更新缓存数据并关闭窗口
function updateType(){
    $.get(SITE_URL + '?ctl=Goods_Type&met=lists&typ=json', function(a){
        if(a.status==200)
        {
            var items = a.data.items;
            var goods_type = new Array();
            if(items != 'undefined' && items){
                for(var i in items){
                    goods_type[i] = new Object();
                    goods_type[i].id = items[i]['type_id'];
                    goods_type[i].name = items[i]['type_name'];
                }
            }
            SYSTEM.categoryInfo.goods_type = goods_type;
        }
        parent.parent.Public.tips({content: '操作成功！'});
        parent.tab.removeTabItem('goods_type_manage');
    });
}
