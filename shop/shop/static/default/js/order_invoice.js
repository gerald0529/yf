/**
 * @author     朱羽婷
 */
$(document).ready(function(){
	var api = frameElement.api;
	//编辑抬头
	if(2 != 2 || false){
		$('#click_3').addClass('disabled');
		$('#click_3').attr('title', '您的订单中部分商品不支持此发票类型');
	}

	if(3 != 3){
		$('#click_2').addClass('disabled');
		$('#click_2').attr('title', '您的订单中部分商品不支持此发票类型');
	}
	//新增单位发票
	window.add_save=function()
	{
		$('#invoice-tit-list .invoice-item-selected').removeClass('invoice-item-selected');
		//$('#save-invoice').addClass('invoice-item-selected').removeClass('hide').show().find('input').removeAttr('readonly').val('').focus();
		$('#invoice-tit-list').scrollTop($('#invoice-tit-list')[0].scrollHeight);
		$('#add-invoice').hide();

		var str = '<div id="save-invoice" class="invoice-item invoice-item-selected" onclick="selected(this)"><div class="add-invoice-tit"> <input type="text" name="invoice_title" class="itxt itxt04" placeholder="新增单位发票抬头"><div class="btns"><a class="ftx-05 save-tit" onclick="add_btn(this)">保存</a></div></div></div>';

		$("#invoice-tit-list").append(str);


	}

	window.selected = function(e){
		$(e).siblings(".invoice-item-selected").removeClass('invoice-item-selected');
		$(e).addClass("invoice-item-selected");
	};

	//显示保存 编辑 删除
	window.show_op = function(e)
	{
		$(e).find(".show").removeClass("hide");
		$(e).find(".del-tit").removeClass("hide");
	}

	window.hide_op = function(e)
	{
		$(e).find(".show").addClass("hide");
		$(e).find(".del-tit").addClass("hide");
	}

	//编辑发票抬头
	window.edit_invoice = function(e)
	{
		$(e).parent().find(".itxt").removeAttr("readonly");
		$(e).removeClass("show");
		$(e).addClass("hide");
		$(e).parent().find(".save-tit").addClass("show");
	}

	//保存发票抬头
	window.save_invoice = function(e)
	{
		//获取隐藏域中的invoice_id
		invoice_id = $(e).parent().find("#invoice_id").val();
		title = $(e).parent().find(".itxt").val();
		
		$.ajax({
			url: SITE_URL+"?ctl=Buyer_Invoice&met=editInvoice&typ=json",
			data:{invoice_id:invoice_id,invoice_title:title},
			dataType: "json",
			contentType: "application/json;charset=utf-8",
			async:false,
			success:function(a){
				 if(a.status == 200)
				 {
				 	$(e).parent().find(".itxt").attr("readonly");
					$(e).removeClass("show");
					$(e).addClass("hide");
					$(e).parent().find(".edit-tit").addClass("show");
				 }
				 else
				 {
					 Public.tips.error('操作失败！');
				 	//$.dialog.alert("操作失败！");
				 }
			}
		});
	}

	//删除发票抬头
	window.del_invoice = function(e)
	{
		//获取隐藏域中的invoice_id
		invoice_id = $(e).parent().find("#invoice_id").val();

		$.dialog({
			title: '删除发票抬头',
			content: '您确定要删除吗？',
			height: 100,
			width: 190,
			lock: true,
			drag: false,
			ok: function () {
				$.post(SITE_URL+"?ctl=Buyer_Invoice&met=delInvoice&typ=json",{invoice_id:invoice_id},function(data)
					{
						console.info(data);
						if(data && 200 == data.status) {
							Public.tips.success('删除成功！');
							//$.dialog.alert('删除成功');
							$(e).parents(".invoice-item").hide('slow');
						} else {
							Public.tips.error('删除失败！');
							//$.dialog.alert('删除失败');
						}
					}
				);
			}
		})
	}

	//普通发票的抬头保存按钮
	window.add_btn = function(e)
	{
		title = $(e).parent().parent().find("input").val();
		state = $(".tab-item-selected").val();

		var data = {invoice_state:state,
					invoice_title:title,};


		flag = add_invoice(data);

		console.info(flag);
		if(flag.status == 200)
		{
			$('#invoice-tit-list .invoice-item-selected').removeClass('invoice-item-selected');

			var str = '<div class="js-company invoice-item invoice-item-selected" style="cursor:pointer" onclick="selected(this)" onmouseover="show_op(this)" onmouseout="hide_op(this)"><div id="invoice-1" style="cursor:pointer"> <span class="hide"><input type="hidden" value="4"></span> <span class="fore2" id="invoice-r1-58325" name="usualInvoiceList" value="58325"><input type="text" style="cursor:pointer" class="itxt"  value=" ' + title + ' " readonly><input type="hidden" id="invoice_id"  value=" ' + flag.data.invoice_id + ' " name="invoice_id"><a  class="ftx-05 save-tit hide" onclick="save_invoice(this)">保存</a> <a  class="ftx-05 edit-tit hide" onclick="edit_invoice(this)">编辑</a> <a  class="ftx-05 del-tit hide" onclick="del_invoice(this)">删除</a><b></b></span></div></div>';

			$('#save-invoice').detach();
			$('#add-invoice').show();
			$("#invoice-tit-list").append(str);

            showCompanyTaxNum('#noraml');
		}
		else
		{
			Public.tips.error('操作失败！');
			//$.dialog.alert('操作失败');
		}
	}
	//新增单位发票
	window.add_invoice = function(e)
	{
		var result = "";
		$.ajax({
			url: SITE_URL+"?ctl=Buyer_Invoice&met=addInvoice&typ=json",
			data:e,
			dataType: "json",
			contentType: "application/json;charset=utf-8",
			async:false,
			success:function(a){
				 result = a;
			}
		});
		return result;
	}

	//保存发票信息
	window.save_Invoice = function(e)
	{
        var companyTaxNum = "";
		//$(e).parents(".tab-con").css('border', '1px solid red');

		index = $(e).parents(".tab-con");
		index1 =  $(e).parents("#invoice-tab");
		state =  index1.find(".tab-item-selected").find("p").html();
		title = index.find("#invoice-tit-list").find(".invoice-item-selected").find(".itxt").val();
		con   = index.find(".content_radio").find(".invoice-item-selected").find("p").html();

		state_num = index1.find(".tab-item-selected").val();



		if(state_num == 2) //电子发票
		{
			if(!title)
			{
				Public.tips.warning('发票抬头不能为空！');
				return;
			}

			//判断选择的是否是企业
			title_type = index.find("#invoice-tit-list").find(".invoice-item-selected").find(".itxt").attr('title_type');
            //将电子发票的信息保存到数据库
            phone = index.find("#e_consignee_mobile").val();
            email = index.find("#e_consignee_email").val();
            companyTaxNum = $("#Electronics").find("input.js-i-company-tax-num").val();
			if(!companyTaxNum && title_type == 2)
			{
				Public.tips.warning('企业税号不能为空！');
				return;
			}
            var reg_companyNum = /^([a-zA-Z0-9]){15,20}$/;
            if(companyTaxNum && title_type == 2)
            {
				if (!reg_companyNum.test(companyTaxNum)) {
					Public.tips.warning('企业税号错误，请检查！');
					return;
				}
            }

            var data = {
                invoice_state:state_num,
                invoice_title:title,
                invoice_rec_phone:phone,
                invoice_rec_email:email,
                invoice_code:companyTaxNum
            };

            $re = check_electroInvoicePhone();
            var check_email = check_electroInvoiceEmail();
            if($re && check_email)
            {
                flag = add_invoice(data);
            }
            else
            {
                return false;
            }
                    
		}

		if(state_num == 3)	 //增值税发票
		{
			title = index.find("#vat_companyName").val();
			company = index.find("#vat_companyName").val();
			code	= index.find("#vat_code").val();
			addr = index.find("#vat_address").val();
			phone = index.find("#vat_phone").val();
			bname = index.find("#vat_bankName").val();
			bcount = index.find("#vat_bankAccount").val();
			cname = index.find("#consignee_name").val();
			cphone = index.find("#consignee_mobile").val();
			province = index.find("#t").val();
			caddr = index.find("#consignee_address").val();

			province_id = index.find("#id_1").val;
			city_id = index.find("#id_2").val;
			area_id = index.find("#id_3").val;

			var reg_companyNum = /^([a-zA-Z0-9]){15,20}$/;
			if (!reg_companyNum.test(code)) 
			{
				Public.tips.warning('识别号错误，请检查！');
				return false;
			}

			var cphone_reg = /^1[34578]\d{9}$/;
			if (!cphone_reg.test(cphone)) 
			{
				Public.tips.warning('手机号码格式不正确！');
				return false;
			}


			var data = {
                            invoice_state:state_num,
                            invoice_title:title,
                            invoice_company:company,
                            invoice_code:code,
                            invoice_reg_addr:addr,
                            invoice_reg_phone:phone,
                            invoice_reg_bname:bname,
                            invoice_reg_baccount:bcount,
                            invoice_rec_name:cname,
                            invoice_rec_phone:cphone,
                            invoice_rec_province:province,
                            invoice_province_id:province_id,
                            invoice_city_id:city_id,
                            invoice_area_id:area_id,
                            invoice_goto_addr:caddr
                        };

			var check_ok = false;
			$(".vat-step-2").each(function(){
				if(check_InvoiceConsignee(this.id, this.value)) {
					check_ok = true;
				}else{
					check_ok = false;
				}
			});
			if(check_ok) {
				flag = add_invoice(data);
			}
			else
			{
				return false;
			}
		}

		if(state_num == 1)	 //普通发票
		{
			if(!title)
			{
				Public.tips.warning('发票抬头不能为空！');
				return;
			}

			id = index.find("#invoice-tit-list").find(".invoice-item-selected").find("input[name='invoice_id']").val();
            companyTaxNum = $("#noraml").find("input.js-i-company-tax-num").val();
			//判断选择的是否是企业
			title_type = index.find("#invoice-tit-list").find(".invoice-item-selected").find(".itxt").attr('title_type');

			if(!companyTaxNum && title_type == 2)
			{
				Public.tips.warning('企业税号不能为空！');
				return;
			}

            var reg_companyTaxNum = /^([a-zA-Z0-9]){15,20}$/;
            if(companyTaxNum && title_type == 2)
            {
				if (!reg_companyTaxNum.test(companyTaxNum)) {
					Public.tips.warning('企业税号错误，请检查！');
					return;
				}
            }

			var data = {
				invoice_state:state_num,
				invoice_title:title,
				invoice_code:companyTaxNum
			};
			flag = add_invoice(data);
                        
		}

		if(flag.status == 200)
		{
			invoice_id = flag.data.invoice_id;
                        $('#order_invoice_id').val(invoice_id);
                        $('#order_invoice_title').val(title);
                        $('#order_invoice_content').val(con);
			parent.addInvoice(state,title,con,invoice_id, companyTaxNum);

			api.close();
		}
		else
		{
			Public.tips.error('操作失败！');
			//$.dialog.alert('操作失败');
		}

	}

	//验证手机号
	window.check_electroInvoicePhone = function(e)
	{
		var value = $("#e_consignee_mobile").val();
		var errorFlag = false;
		var errorMessage = "";
		var reg=/^1[34578]\d{9}$/;
		if (value != '') {
			if (!reg.test(value)) {
				errorFlag = true;
				errorMessage = "手机号码格式不正确";
			}
		} else {
			errorFlag = true;
			errorMessage = "请输入手机号码";
		}
		if (errorFlag) {
			$("#e_consignee_mobile_error").html(errorMessage);
			$("#e_consignee_mobile_error").addClass("error-msg");
			return false;
		} else {
			$("#e_consignee_mobile_error").removeClass("error-msg");
			$("#e_consignee_mobile_error").html("");
			return true;
		}

	}
	//验证邮箱
	window.check_electroInvoiceEmail = function(e)
	{
		var value = $("#e_consignee_email").val();
		var errorFlag = false;
		var errorMessage = "";
		var reg=/^\w{3,}@\w+(\.\w+)+$/;
		if (value != '') {
			if (!reg.test(value)) {
				errorFlag = true;
				errorMessage = "邮箱格式不正确";
			}
			if (value.length > 50) {
				errorFlag = true;
				errorMessage = "邮箱长度不能大于50位";
			}
		}
		if (errorFlag) {
			$("#e_consignee_email_error").html(errorMessage);
			$("#e_consignee_email_error").addClass("error-msg");
			return false;
		} else {
			$("#e_consignee_email_error").removeClass("error-msg");
			$("#e_consignee_email_error").html("");
			return true;
		}

	}

	/**
	 * 验证增值税发票消息提示
	 *
	 * @param divId
	 * @param value
	 */
	window.check_Invoice =function(type, value) {
		var errorFlag = false;
		var errorMessage = null;
		var regx=/[`~!@#$%^&*()_+<>?:"{},.\/;'[\]]/;
        var reg=/^1[345789]\d{9}$/;
		// 验证发票单位名称
		if (type == "vat_companyName") {
			if (value == '') {
				errorFlag = true;
				errorMessage = "单位名称不能为空！";
			} else {
				if (value.length < 2) {
					errorFlag = true;
					errorMessage = "请填写完整单位名称！";
				}
				if (value.length > 100) {
					errorFlag = true;
					errorMessage = "单位名称过长！";
				}
				if (regx.test(value)) {
					errorFlag = true;
					errorMessage = "含有非法字符！";
				}
			}
		} else if (type == "vat_code") { // 验证纳税人识别号
			if (value == '') {
				errorFlag = true;
				errorMessage = "识别号不能为空！";
			} else {
				var reg_number = /^([a-zA-Z0-9]){15,20}$/;
				if (!reg_number.test(value)) {
					errorFlag = true;
					errorMessage = "识别号错误，请检查！";
				}
				if (regx.test(value)) {
					errorFlag = true;
					errorMessage = "识别号含有非法字符！";
				}
			}
		} else if (type == "vat_address") { // 验证发票注册地址
			if (value == '') {
				errorFlag = true;
				errorMessage = "注册地址不能为空！";
			} else {
				if (value.replace(/[^\x00-\xff]/g, "**").length < 2) {
					errorFlag = true;
					errorMessage = "注册地址错误！";
				}
				if (value.length > 250) {
					errorFlag = true;
					errorMessage = "注册地址过长！";
				}
				if (regx.test(value)) {
					errorFlag = true;
					errorMessage = "含有非法字符！";
				}
			}
		} else if (type == "vat_phone") { // 验证增值税发票电话
			if (value == "") {
				errorFlag = true;
				errorMessage = "注册电话不能为空！";
			} else {
				if (!reg.test(value)) {
					errorFlag = true;
					errorMessage = "注册电话不正确！";
				}
				if (regx.test(value)) {
					errorFlag = true;
					errorMessage = "含有非法字符！";
				}
			}
		} else if (type == "vat_bankName") { // 验证增值税发票开户银行
			if (value == '') {
				errorFlag = true;
				errorMessage = "开户银行不能为空！";
			} else {
				if (value.replace(/[^\x00-\xff]/g, "**").length < 2) {
					errorFlag = true;
					errorMessage = "开户银行错误！";
				}
				if (value.length > 100) {
					errorFlag = true;
					errorMessage = "开户银行过长！";
				}
				if (regx.test(value)) {
					errorFlag = true;
					errorMessage = "含有非法字符！";
				}
			}
		} else if (type == "vat_bankAccount") { // 验证增值税发票银行账户
			if (value == '') {
				errorFlag = true;
				errorMessage = "银行帐户不能为空！";
			} else {
				var reg_bank = /^(\d{15,20})$/;
				if (!reg_bank.test(value)) {
					errorFlag = true;
					errorMessage = "银行账户错误,请检查！";
				}
			}
		}
		if (errorFlag) {
			$("#" + type + "_error").html(errorMessage);
			$("#" + type + "_error").addClass("error-msg");

			return false;
		} else {
			$("#" + type + "_error").removeClass("error-msg");
			$("#" + type + "_error").html("");
			return true;
		}

	}

	window.nextAvt = function () {
		var check_ok = false;
		$(".vat-step-1").each(function(){
			if(check_Invoice(this.id, this.value)) {
				check_ok = true;
			}else{
				check_ok = false;
				return false;
			}
		});
		if(check_ok) {
			$('#invoice-box-03 .steps .step2').removeClass('hide').siblings().addClass('hide');
			$('#invoice-box-03 .invoice-status .fore2').addClass('curr').siblings().removeClass('curr');
			$('#consignee_name').focus();
		}
		console.log(check_ok);
	}

	 window.prev = function() {
		$('#invoice-box-03 .steps .step1').removeClass('hide').siblings().addClass('hide');
		$('#invoice-box-03 .invoice-status .fore1').addClass('curr').siblings().removeClass('curr');
	}

	/**
	 * 验证发票地址消息提示，单列出方法是因为文案不同
	 *
	 * @param divId
	 * @param value
	 */
	 window.check_InvoiceConsignee = function(divId,isGeneral) {
		var errorFlag = false;
		var errorMessage = null;
		var value = null;
		var generalTag = isGeneral?"generalInvoice_":"";
		 var regx=/[`~!@#$%^&*()_+<>?:"{},.\/;'[\]]/;
		 var mobile_reg=/^1[34578]\d{9}$/;
		// 验证收货人名称
		if (divId == generalTag+"name_div") {
			value = $("#"+generalTag+"consignee_name").val();
			if (value == '') {
				errorFlag = true;
				errorMessage = "请您填写收票人姓名";
			}
			if (regx.test(value)) {
				errorFlag = true;
				errorMessage = "收票人姓名中含有非法字符";
			}
		}
		// 验证地区是否完整
		else if (divId == generalTag+"area_div") {
			var provinceId = $("#select_1").find("option:selected").val();
			var cityId = $("#select_2").find("option:selected").val();
			var countyId = $("#select_3").find("option:selected").val();

			// 验证地区是否正确
			if (!((provinceId && cityId) || ($(".dress_box").html()) ) )
			{
				errorFlag = true;
				errorMessage = "请您填写完整的地区信息";
			}
		}
		// 验证收货人地址
		else if (divId == generalTag+"address_div") {
			value = $("#"+generalTag+"consignee_address").val();
			if (value == '') {
				errorFlag = true;
				errorMessage = "请您填写收票人详细地址";
			}
			if (regx.test(value)) {
				errorFlag = true;
				errorMessage = "收票人详细地址中含有非法字符";
			}
		}
		// 验证手机号码
		else if (divId == generalTag+"call_phone_div") {
			value = $("#"+generalTag+"consignee_phone").val();
			divId = generalTag+"call_div";
			if (value != '') {
				if (regx.test(value)) {
					errorFlag = true;
					errorMessage = "固定电话号码中含有非法字符";
				}
			}
			if (true) {
				value = $("#"+generalTag+"consignee_mobile").val();
				if (value == '') {
					errorFlag = true;
					errorMessage = "请您填写收票人手机号码";
				} else {
					if (!mobile_reg.test(value)) {
						errorFlag = true;
						errorMessage = "手机号码格式不正确";
					}
				}
			}
		}
		if (errorFlag) {
			$("#"+divId + "_error").html(errorMessage);
			$("#"+divId + "_error").addClass("error-msg");
			return false;
		} else {
			$("#"+divId + "_error").removeClass("error-msg");
			$("#"+divId + "_error").html("");
			return true;
		}

	}

	window.quxiao = function()
	{
		//var index = parent.layer.getFrameIndex(window.name); //获取窗口索引
		//parent.layer.close(index);
		api.close();
	}

});

function showCompanyTaxNum (type) {
	var $type = $(type);
	$type.find("div.js-company-tax-num").show();
}

function hideCompanyTaxNum(type) {
    var $type = $(type);
    $type.find("div.js-company-tax-num").hide();
}