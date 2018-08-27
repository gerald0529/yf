var api = frameElement.api;
var oper = api.data.oper;
var rowData = api.data.rowData || {};
var callback = api.data.callback;

initPopBtns();
initField();
initEvent();

function initField() {
    if (api.data.parent_id) {
        $.get(SITE_URL + "?ctl=Goods_Cat&met=getGoodsCatName&typ=json&id=" + api.data.parent_id, function (a) {
            if (a.status == 200) {
                $("#parent_cat").val(a.data.cat_name);
                $("#parent_id").val(a.data.id);
            }
        });
    }
    
    if (rowData.id) {
        $("#cat_name").val(rowData.cat_name);
        if (rowData.cat_is_virtual == 1) {
            $("[name=\"cat_is_virtual\"]:checkbox").attr("checked", true);
        }
        
        Public.ajaxGet(SITE_URL + "?ctl=Fenxiao&met=getCatValues&typ=json", {id: rowData.id}, function (data) {
            if (data.status == 200) {
                var values = data.data.values;
                $("input[name=\"c_first\"]").val(values[0]);
                $("input[name=\"c_second\"]").val(values[1]);
                $("input[name=\"c_third\"]").val(values[2]);
            }
        });
        
        $("#cat_commission").val(rowData.cat_commission);
        $("#first_cat_commission").val(rowData.cat_commission);
        $("#second_cat_commission").val(rowData.cat_commission);
        $("#third_cat_commission").val(rowData.cat_commission);
        $("#low_cat_commission").val(rowData.cat_commission);
        
        $("#cat_displayorder").val(rowData.cat_displayorder);
        $("#cat_image").attr("src", rowData.cat_pic);
        $("#cat_logo").val(rowData.cat_pic);
        if (rowData.cat_parent_id) {
            $.get(SITE_URL + "?ctl=Goods_Cat&met=getGoodsCatName&typ=json&id=" + rowData.cat_parent_id, function (a) {
                if (a.status == 200) {
                    $("#parent_cat").val(a.data.cat_name);
                    $("#parent_id").val(a.data.id);
                }
            });
        }
    }
    
    
    var typeCombo = Business.categoryCombo($("#goods_type_combo"), {
        editable: true,
        extraListHtml: "",
        addOptions: {
            value: -1,
            text: "选择类别"
        },
        defaultSelected: 0,
        trigger: true,
        width: 120,
        callback: {
            onChange: function (data) {
                $("#type_id").val(this.getValue());
            }
        }
    }, "goods_type");
    typeCombo.selectByValue(rowData.type_id);
}

function initEvent() {
    var $number = $("#number");
    var $cat_name = $("#cat_name");
    
    Public.limitInput($number, /^[a-zA-Z0-9\-_]*$/);
    // Public.limitLength($cat_name, 17);
    Public.bindEnterSkip($("#manage-wrap"), postData, oper, rowData.id);
    initValidator();
    $number.focus().select();
}

function initPopBtns() {
    var operName = oper == "add" ? ["保存", "关闭"] : ["确定", "取消"];
    api.button({
        id: "confirm",
        name: operName[0],
        focus: true,
        callback: function () {
            postData(oper, rowData.id);
            return false;
        }
    }, {
        id: "cancel",
        name: operName[1]
    });
}

function initValidator() {
    $.validator.addMethod("number", function (value) {
        return /^[a-zA-Z0-9\-_]*$/.test(value);
    });
    
    $("#manage-form").validate({
        rules: {
            common_verify_remark: {
                required: true
            }
        },
        messages: {
            common_verify_remark: {
                required: "违规审核理由不能为空"
            }
        },
        errorClass: "valid-error"
    });
}

/*
    判断佣金比例是否是0-100之间的两位小数
 */
function initValidator2(val) {
    var reg = /^\d+(\.\d{1,2})?$/;
    if (reg.test(val) && val > 0 && val < 100 || val == 0 || val == 100) {
        return true;
    } else {
        return false;
    }
}

function postData(oper, id) {
    /*
    if(!$('#manage-form').validate().form()){
        $('#manage-form').find('textarea.valid-error').eq(0).focus();
        return ;
    }
    */
    //限制输入的字符长度
    if ($("#cat_name").val().length < 2) {
        Public.tips({type: 1, content: "分类名称为2~17个字符"});
        return false;
    }
    var cat_name = $.trim($("#cat_name").val());
    var cat_commission = $.trim($("#cat_commission").val());
    var cat_displayorder = $.trim($("#cat_displayorder").val());
    var parent_id = $.trim($("#parent_id").val());
    var cat_pic = $.trim($("#cat_logo").val());
    var type_id = $.trim($("#type_id").val());
    var c_first = $("input[name=\"c_first\"]").val();
    var c_second = $("input[name=\"c_second\"]").val();
    var c_third = $("input[name=\"c_third\"]").val();
    
    if (initValidator2(c_first) && initValidator2(c_second) && initValidator2(c_third)) {
    
    } else {
        Public.tips({type: 1, content: "分佣比例必须为0-100两位小数"});
        return false;
    }
    
    if ($("input[name='cat_is_virtual']").is(":checked")) {
        var cat_is_virtual = 1;
    }
    else {
        var cat_is_virtual = 0;
    }
    var msg = oper == "add" ? "新增分类" : "编辑分类";
    
    params = {
        cat_name: cat_name,
        cat_is_virtual: cat_is_virtual,
        t_gc_virtual: $("input[name='t_gc_virtual']:checked").val(),
        t_show_type: $("input[name='t_show_type']:checked").val(),
        cat_commission: cat_commission,
        t_commis_rate: $("input[name='t_commis_rate']:checked").val(),
        cat_displayorder: cat_displayorder,
        cat_parent_id: parent_id,
        cat_pic: cat_pic,
        type_id: type_id,
        c_first: $("input[name=\"c_first\"]").val(),
        c_second: $("input[name=\"c_second\"]").val(),
        c_third: $("input[name=\"c_third\"]").val()
    };
    rowData.id ? params["cat_id"] = id : "";
    
    Public.ajaxPost(SITE_URL + "?ctl=Goods_Cat&typ=json&met=" + (oper == "add" ? "add" : "editGoodsCat"), params, function (data) {
        if (data.status == 200) {
            rowData = data.data;
            rowData.operate = oper;
            rowData.ex_commission = [$("input[name=\"c_first\"]").val(), $("input[name=\"c_second\"]").val(), $("input[name=\"c_third\"]").val()];
            updateGoodsCate();
        } else {
            parent.parent.Public.tips({type: 1, content: msg + "失败！" + data.msg});
        }
    });
}

function resetForm(data) {
    $("#manage-form").validate().resetForm();
    $("#name").val("");
    $("#number").val(Public.getSuggestNum(data.locationNo)).focus().select();
}

//更新缓存数据并关闭窗口
function updateGoodsCate() {
    $.get(SITE_URL + "?ctl=Category&met=lists&typ=json&type_number=goods_cat&is_delete=2", function (a) {
        if (a.status == 200) {
            parent.SYSTEM.goodsCatInfo = a.data.items;
            
        }
        parent.parent.Public.tips({content: "操作成功！"});
        if (callback && typeof callback == "function") {
            callback(rowData, oper, window);
        }
    });
}
