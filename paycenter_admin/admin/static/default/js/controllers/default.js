function setTabHeight() {
	var a = $(window).height(),
		b = $("#main-bd"),
		c = $("#col-side"),
		d = a - b.offset().top;
	b.height(d);
	c.height(d);
}
function initDate() {
	var a = new Date,
		b = a.getFullYear(),
		c = ("0" + (a.getMonth() + 1)).slice(-2),
		d = ("0" + a.getDate()).slice(-2);
	SYSTEM.beginDate = b + "-" + c + "-01", SYSTEM.endDate = b + "-" + c + "-" + d
}
function addUrlParam() {
	var a = "beginDate=" + SYSTEM.beginDate + "&endDate=" + SYSTEM.endDate;
	$("#nav").find("li.item-report .nav-item a").each(function() {
		var b = this.href;
		b += -1 === this.href.lastIndexOf("?") ? "?" : "&", this.href = "商品库存余额表" === $(this).html() ? b + "beginDate=" + SYSTEM.startDate + "&endDate=" + SYSTEM.endDate : b + a
	})
}
function BBSPop() {
	var a = $("#yswb-tab"),
		b = ['<ul id="yswbPop">', '<li class="yswbPop-title">请选择您要进入的论坛</li>', '<li><strong>产品服务论坛</strong><p>在线会计，在线进销存操作问题咨询</p><a href="http://kdweibo.com/yuanfeng021.com/042633" target="_blank">进入论坛>></a></li>', '<li><strong>会计交流论坛</strong><p>会计实操，会计学习，会计资讯</p><a href="http://kdweibo.com/yuanfeng021.com/026606" target="_blank">进入论坛>></a></li>', "</ul>"].join("");
	a.find("a").click(function(a) {
		a.preventDefault();
		var c = $.cookie("yswbPop_scm");
		c ? window.open(c) : $.dialog({
			id: "",
			title: "",
			lock: !0,
			padding: 0,
			content: b,
			max: !1,
			min: !1,
			init: function() {
				var a = $("#yswbPop"),
					b = this;
				a.find("a").each(function() {
					$(this).on("click", function() {
						$.cookie("yswbPop_scm", this.href, {
							expires: 7
						}), b.close()
					})
				})
			}
		})
	})
}
function getStores() {
	SYSTEM.isAdmin || SYSTEM.rights.CLOUDSTORE_QUERY ? Public.ajaxGet("http://wd.yuanfeng021.com/bs/cloudStore.do?action=list", {}, function(a) {
		200 === a.status ? SYSTEM.storeInfo = a.data.items : 250 === a.status ? SYSTEM.storeInfo = [] : Public.tips({
			type: 1,
			content: a.msg
		})
	}) : SYSTEM.storeInfo = []
}
function getLogistics() {
	SYSTEM.isAdmin || SYSTEM.rights.EXPRESS_QUERY ? Public.ajaxGet("http://wd.yuanfeng021.com/bs/express.do?action=list", {}, function(a) {
		200 === a.status ? SYSTEM.logisticInfo = a.data.items : 250 === a.status ? SYSTEM.logisticInfo = [] : Public.tips({
			type: 1,
			content: a.msg
		})
	}) : SYSTEM.logisticInfo = []
}
function setCurrentNav(a) {
	if (a) {
		var b = a.match(/([a-zA-Z]+)[-]?/)[1];
		$("#nav > li").removeClass("current"), $("#nav > li.item-" + b).addClass("current")
	}
}
var dataReflush, list = {
	onlineStoreMap: {
		name: "新手导航",
		href: WDURL + "/online-store/map.jsp?language=zh-CHS&site=SITE_MAIN&siId=" + SYSTEM.DBID + "&scheme=" + SCHEME + "&logonName=" + SYSTEM.userName,
		dataRight: "",
		target: "vip-onlineStore"
	},
	onlineStoreList: {
		name: "网店记录",
		href: WDURL + "/online-store/onlineStoreList.jsp?language=zh-CHS&site=SITE_MAIN&siId=" + SYSTEM.DBID + "&scheme=" + SCHEME + "&logonName=" + SYSTEM.userName,
		dataRight: "CLOUDSTORE_QUERY",
		target: "vip-onlineStore"
	},
	onlineStoreRelation: {
		name: "商品对应关系",
		href: WDURL + "/online-store/onlineStoreRelation.jsp?language=zh-CHS&site=SITE_MAIN&siId=" + SYSTEM.DBID + "&scheme=" + SCHEME + "&logonName=" + SYSTEM.userName,
		dataRight: "INVENTORYCLOUD_QUERY",
		target: "vip-onlineStore"
	},
	logisticsList: {
		name: "物流公司记录",
		href: WDURL + "/online-store/logisticsList.jsp?language=zh-CHS&site=SITE_MAIN&siId=" + SYSTEM.DBID + "&scheme=" + SCHEME + "&logonName=" + SYSTEM.userName,
		dataRight: "EXPRESS_QUERY",
		target: "vip-onlineStore"
	},
	orderHandle1: {
		name: "订单审核",
		href: WDURL + "/online-store/onlineOrderList.jsp?handle=1&language=zh-CHS&site=SITE_MAIN&siId=" + SYSTEM.DBID + "&scheme=" + SCHEME + "&logonName=" + SYSTEM.userName,
		dataRight: "ORDERCLOUD_QUERY",
		target: "vip-onlineStore"
	},
	orderHandle2: {
		name: "打单发货",
		href: WDURL + "/online-store/onlineOrderList.jsp?handle=2&language=zh-CHS&site=SITE_MAIN&siId=" + SYSTEM.DBID + "&scheme=" + SCHEME + "&logonName=" + SYSTEM.userName,
		dataRight: "ORDERCLOUD_QUERY",
		target: "vip-onlineStore"
	},
	orderHandle3: {
		name: "已发货",
		href: WDURL + "/online-store/onlineOrderList.jsp?handle=3&language=zh-CHS&site=SITE_MAIN&siId=" + SYSTEM.DBID + "&scheme=" + SCHEME + "&logonName=" + SYSTEM.userName,
		dataRight: "ORDERCLOUD_QUERY",
		target: "vip-onlineStore"
	},
	orderList: {
		name: "订单查询",
		href: WDURL + "/online-store/onlineOrderList.jsp?language=zh-CHS&site=SITE_MAIN&siId=" + SYSTEM.DBID + "&scheme=" + SCHEME + "&logonName=" + SYSTEM.userName,
		dataRight: "ORDERCLOUD_QUERY",
		target: "vip-onlineStore"
	},
	onlineSalesList: {
		name: "销货记录",
		href: "/scm/invSa.do?action=initSaleList",
		dataRight: "SA_QUERY",
		target: "vip-onlineStore"
	},
	JDStorageList: {
		name: "授权管理",
		href: "/JDStorage/JDStorageList.jsp",
		dataRight: "",
		target: "vip-JDStorage"
	},
	JDStorageGoodsList: {
		name: "商品上传管理",
		href: "/JDStorage/JDStorageGoodsList.jsp",
		dataRight: "",
		target: "vip-JDStorage"
	},
	JDStoragePurchaseOrderList: {
		name: "购货订单上传",
		href: "/JDStorage/JDStoragePurchaseOrderList.jsp",
		dataRight: "",
		target: "vip-JDStorage"
	},
	JDStorageSaleOrderList: {
		name: "销货订单上传",
		href: "/JDStorage/JDStorageSaleOrderList.jsp",
		dataRight: "",
		target: "vip-JDStorage"
	},
	JDStorageInvManage: {
		name: "京东库存",
		href: "/JDStorage/JDStorageInvManage.jsp",
		dataRight: "",
		target: "vip-JDStorage"
	},
	purchaseOrder: {
		name: "购货订单",
		href: "/scm/invPo.do?action=initPo",
		dataRight: "PO_ADD",
		target: "purchase",
		list: "/scm/invPo.do?action=initPoList"
	},
	purchase: {
		name: "购货单",
		href: "/scm/invPu.do?action=initPur",
		dataRight: "PU_ADD",
		target: "purchase",
		list: "/scm/invPu.do?action=initPurList"
	},
	salesOrder: {
		name: "销货订单",
		href: "/scm/invSo.do?action=initSo",
		dataRight: "SO_ADD",
		target: "sales",
		list: "/scm/invSo.do?action=initSoList"
	},
	sales: {
		name: "销货单",
		href: "/scm/invSa.do?action=initSale",
		dataRight: "SA_ADD",
		target: "sales",
		list: "/scm/invSa.do?action=initSaleList"
	},
	transfers: {
		name: "调拨单",
		href: "/scm/invTf.do?action=initTf",
		dataRight: "TF_ADD",
		target: "storage",
		list: "/scm/invTf.do?action=initTfList"
	},
	inventory: {
		name: "盘点",
		href: "/storage/inventory.jsp",
		dataRight: "PD_GENPD",
		target: "storage"
	},
	otherWarehouse: {
		name: "其他入库单",
		href: "/scm/invOi.do?action=initOi&type=in",
		dataRight: "IO_ADD",
		target: "storage",
		list: "/scm/invOi.do?action=initOiList&type=in"
	},
	otherOutbound: {
		name: "其他出库单",
		href: "/scm/invOi.do?action=initOi&type=out",
		dataRight: "OO_ADD",
		target: "storage",
		list: "/scm/invOi.do?action=initOiList&type=out"
	},
	adjustment: {
		name: "成本调整单",
		href: "/scm/invOi.do?action=initOi&type=cbtz",
		dataRight: "CADJ_ADD",
		target: "storage",
		list: "/scm/invOi.do?action=initOiList&type=cbtz"
	},
	assemble: {
		name: "组装单",
		href: "/scm/invOi.do?action=initOi&type=zz",
		dataRight: "ZZD_ADD",
		target: "storage",
		list: "/scm/invOi.do?action=initOiList&type=zz"
	},
	disassemble: {
		name: "拆卸单",
		href: "/scm/invOi.do?action=initOi&type=cx",
		dataRight: "CXD_ADD",
		target: "storage",
		list: "/scm/invOi.do?action=initOiList&type=cx"
	},
	receipt: {
		name: "收款单",
		href: "/scm/receipt.do?action=initReceipt",
		dataRight: "RECEIPT_ADD",
		target: "money",
		list: "/scm/receipt.do?action=initReceiptList"
	},
	payment: {
		name: "付款单",
		href: "/scm/payment.do?action=initPay",
		dataRight: "PAYMENT_ADD",
		target: "money",
		list: "/scm/payment.do?action=initPayList"
	},
	verification: {
		name: "核销单",
		href: "/scm/verifica.do?action=initVerifica",
		dataRight: "VERIFICA_ADD",
		target: "money",
		list: "/money/verification-list.jsp"
	},
	otherIncome: {
		name: "其他收入单",
		href: "/scm/ori.do?action=initInc",
		dataRight: "QTSR_ADD",
		target: "money",
		list: "/scm/ori.do?action=initIncList"
	},
	otherExpense: {
		name: "其他支出单",
		href: "/scm/ori.do?action=initExp",
		dataRight: "QTZC_ADD",
		target: "money",
		list: "/scm/ori.do?action=initExpList"
	},
	puOrderTracking: {
		name: "采购订单跟踪表",
		href: "/report/pu-order-tracking.jsp",
		dataRight: "PURCHASEORDER_QUERY",
		target: "report-purchase"
	},
	puDetail: {
		name: "采购明细表",
		href: "/report/puDetail.do?action=detail",
		dataRight: "PUREOORTDETAIL_QUERY",
		target: "report-purchase"
	},
	puSummary: {
		name: "采购汇总表（按商品）",
		href: "/report/puDetail.do?action=inv",
		dataRight: "PUREPORTINV_QUERY",
		target: "report-purchase"
	},
	puSummarySupply: {
		name: "采购汇总表（按供应商）",
		href: "/report/puDetail.do?action=supply",
		dataRight: "PUREPORTPUR_QUERY",
		target: "report-purchase"
	},
	salesOrderTracking: {
		name: "销售订单跟踪表",
		href: "/report/sales-order-tracking.jsp",
		dataRight: "SALESORDER_QUERY",
		target: "report-sales"
	},
	salesDetail: {
		name: "销售明细表",
		href: "/report/sales-detail.jsp",
		dataRight: "SAREPORTDETAIL_QUERY",
		target: "report-sales"
	},
	salesSummary: {
		name: "销售汇总表（按商品）",
		href: "/report/sales-summary.jsp",
		dataRight: "SAREPORTINV_QUERY",
		target: "report-sales"
	},
	salesSummaryCustomer: {
		name: "销售汇总表（按客户）",
		href: "/report/salesDetail.do?action=customer",
		dataRight: "SAREPORTBU_QUERY",
		target: "report-sales"
	},
	contactDebt: {
		name: "往来单位欠款表",
		href: "/report/contactDebt.do?action=detail",
		dataRight: "ContactDebtReport_QUERY",
		target: "report-sales"
	},
	initialBalance: {
		name: "商品库存余额表",
		href: "",
		dataRight: "InvBalanceReport_QUERY",
		target: "report-storage"
	},
	goodsFlowDetail: {
		name: "商品收发明细表",
		href: "/report/goods-flow-detail.jsp",
		dataRight: "DeliverDetailReport_QUERY",
		target: "report-storage"
	},
	goodsFlowSummary: {
		name: "商品收发汇总表",
		href: "/report/goods-flow-summary.jsp",
		dataRight: "DeliverSummaryReport_QUERY",
		target: "report-storage"
	},
	cashBankJournal: {
		name: "现金银行报表",
		href: "/report/bankBalance.do?action=detail",
		dataRight: "SettAcctReport_QUERY",
		target: "report-money"
	},
	accountPayDetail: {
		name: "应付账款明细表",
		href: "/report/fundBalance.do?action=detailSupplier&type=10",
		dataRight: "PAYMENTDETAIL_QUERY",
		target: "report-money"
	},
	accountProceedsDetail: {
		name: "应收账款明细表",
		href: "/report/fundBalance.do?action=detail",
		dataRight: "RECEIPTDETAIL_QUERY",
		target: "report-money"
	},
	customersReconciliation: {
		name: "客户对账单",
		href: "/report/customerBalance.do?action=detail",
		dataRight: "CUSTOMERBALANCE_QUERY",
		target: "report-money"
	},
	suppliersReconciliation: {
		name: "供应商对账单",
		href: "/report/supplierBalance.do?action=detail",
		dataRight: "SUPPLIERBALANCE_QUERY",
		target: "report-money"
	},
	customerList: {
		name: "客户管理",
		href: "",
		dataRight: "BU_QUERY",
		target: "setting-base"
	},
	vendorList: {
		name: "供应商管理",
		href: "",
		dataRight: "PUR_QUERY",
		target: "setting-base"
	},
	goodsList: {
		name: "商品管理",
		href: "",
		dataRight: "INVENTORY_QUERY",
		target: "setting-base"
	},
	storageList: {
		name: "仓库管理",
		href: "",
		dataRight: "INVLOCTION_QUERY",
		target: "setting-base"
	},
	staffList: {
		name: "职员管理",
		href: "",
		dataRight: "",
		target: "setting-base"
	},
	settlementaccount: {
		name: "账户管理",
		href: "",
		dataRight: "SettAcct_QUERY",
		target: "setting-base"
	},
	shippingAddress: {
		name: "发货地址管理",
		href: "",
		dataRight: "DELIVERYADDR_QUERY",
		target: "setting-base"
	},
	customerCategoryList: {
		name: "客户类别",
		href: "",
		dataRight: "BUTYPE_QUERY",
		target: "setting-auxiliary"
	},
	vendorCategoryList: {
		name: "供应商类别",
		href: "",
		dataRight: "SUPPLYTYPE_QUERY",
		target: "setting-auxiliary"
	},
	goodsCategoryList: {
		name: "商品类别",
		href: "",
		dataRight: "TRADETYPE_QUERY",
		target: "setting-auxiliary"
	},
	unitList: {
		name: "计量单位",
		href: "",
		dataRight: "UNIT_QUERY",
		target: "setting-auxiliary"
	},
	settlementCL: {
		name: "结算方式",
		href: "",
		dataRight: "Assist_QUERY",
		target: "setting-auxiliary"
	},
	assistingProp: {
		name: "辅助属性",
		href: "",
		dataRight: "FZSX_QUERY",
		target: "setting-auxiliary"
	},
	parameter: {
		name: "系统参数",
		href: "",
		dataRight: "",
		target: "setting-advancedSetting"
	},
	authority: {
		name: "权限设置",
		href: "",
		dataRight: "",
		target: "setting-advancedSetting"
	},
	operationLog: {
		name: "操作日志",
		href: "",
		dataRight: "OPERATE_QUERY",
		target: "setting-advancedSetting"
	},
	printTemplates: {
		name: "套打模板",
		href: "/settings/print-templates.jsp",
		dataRight: "",
		target: "setting-advancedSetting"
	},
	backup: {
		name: "备份与恢复",
		href: "/settings/backup.jsp",
		dataRight: "",
		target: "setting-advancedSetting"
	},
	reInitial: {
		name: "重新初始化",
		href: "",
		dataRight: "",
		id: "reInitial",
		target: "setting-advancedSetting-right"
	},
	addedServiceList: {
		name: "增值服务",
		href: "/settings/addedServiceList.jsp",
		dataRight: "",
		target: "setting-advancedSetting-right"
	}
},
	menu = {
		init: function(a, b) {
			var c = {
				callback: {}
			};
			this.obj = a, this.opts = $.extend(!0, {}, c, b), this.sublist = this.opts.sublist, this.sublist || this._getMenuData(), this._menuControl(), this._initDom(), $(".vip").length || $(".main-nav").css("margin", "5px 0")
		},
		_display: function(a, b) {
			for (var c = a.length - 1; c >= 0; c--) this.sublist[a[c]] && (this.sublist[a[c]].disable = !b);
			return this
		},
		_show: function(a) {
			return this._display(a, !0)
		},
		_hide: function(a) {
			return this._display(a, !1)
		},
		_getMenuData: function() {
			this.sublist = list
		},
		_menuControl: function() {
			var a = SYSTEM.siType,
				b = SYSTEM.isAdmin,
				c = SYSTEM.siVersion;
			switch (this._hide(["authority", "reInitial", "backup", "onlineStoreMap", "onlineStoreList", "onlineStoreRelation", "logisticsList", "orderHandle1", "orderHandle2", "orderHandle3", "orderList", "onlineSalesList", "JDStorageList", "JDStorageGoodsList", "JDStoragePurchaseOrderList", "JDStorageSaleOrderList", "JDStorageInvManage", "assistingProp"]), a) {
			case 1:
				this._hide(["purchaseOrder", "purchaseOrderList", "salesOrder", "salesOrderList", "verification", "verificationList", "shippingAddress", "puOrderTracking", "salesOrderTracking", "assemble", "disassemble"]);
				break;
			case 2:
			}
			switch (c) {
			case 1:
				break;
			case 2:
				break;
			case 3:
				break;
			case 4:
				this._hide(["backup"])
			}
			b && (3 == c && this._show(["reInitial"]), this._show(["authority"]), this._show(["backup"])), 2 == a && (1 == SYSTEM.hasOnlineStore ? this._show(["onlineStoreMap", "onlineStoreList", "onlineStoreRelation", "logisticsList", "orderHandle1", "orderHandle2", "orderHandle3", "orderList", "onlineSalesList"]) : 1 == SYSTEM.enableStorage && $(".vip-nav").width(125), 1 == SYSTEM.enableStorage ? this._show(["JDStorageList", "JDStorageGoodsList", "JDStoragePurchaseOrderList", "JDStorageSaleOrderList", "JDStorageInvManage"]) : 1 == SYSTEM.hasOnlineStore && $(".vip-nav").width(120), 1 == SYSTEM.enableAssistingProp && this._show(["assistingProp"]))
		},
		_getDom: function() {
			this.objCopy = this.obj.clone(!0), this.container = this.obj.closest("div")
		},
		_setDom: function() {
			this.obj.remove(), this.container.append(this.objCopy)
		},
		_initDom: function() {
			if (this.sublist && this.obj) {
				this.obj.find("li:not(.item)").remove(), this._getDom();
				var a = this.sublist,
					b = {};
				b.target = {};
				for (var c in a) if (!a[c].disable) {
					var d = a[c],
						e = b.target[d.target],
						f = d.id ? "id=" + d.id : "",
						g = d.id ? "" : "rel=pageTab",
						h = "";
					if (d.list) {
						var i = d.name + "记录";
						h = "<i " + f + ' tabTxt="' + i + '" tabid="' + d.target.split("-")[0] + "-" + c + 'List" ' + g + ' href="' + d.list + '" data-right="' + d.dataRight.split("_")[0] + '_QUERY">查询</i>'
					}
					var j = "<li><a " + f + ' tabTxt="' + d.name + '" tabid="' + d.target.split("-")[0] + "-" + c + '" ' + g + ' href="' + d.href + '" data-right="' + d.dataRight + '">' + d.name + h + "</a></li>";
					e ? e.append(j) : (b.target[d.target] = this.objCopy.find("#" + d.target), b.target[d.target] && b.target[d.target].append(j))
				}
				this.objCopy.find("li.item").each(function() {
					var a = $(this);
					a.find("li").length || a.remove(), a.find(".nav-item").each(function() {
						var a = $(this);
						a.find("li").length || (a.hasClass("last") && a.prev().addClass("last"), a.remove())
					})
				}), this._setDom()
			}
		}
	};
$(function() {
	$("#companyName").text(SYSTEM.companyName).prop("title", SYSTEM.companyName)
    $("#fast").click();

}), setTabHeight(), $(window).bind("resize", function() {
	setTabHeight()
}), function(a) {
	//menu.init(a("#nav")), initDate(), addUrlParam(), BBSPop();
	var b = a("#nav"),
		c = a("#nav > li");
	a.each(c, function() {
		//var c = a(this).find("#sub-nav");
		a(this).on("click", function() {
			// b.removeClass("static"),
			a(this).addClass("cur").siblings().removeClass("cur")//,
		 	a(this).parent().next("#sub-nav").find("ul").eq(a(this).index()).fadeIn(250).show().siblings().hide();
			 //c.find("i:eq(0)").closest("li").addClass("on"),
			 //c.stop(!0, !0).fadeIn(250)
		})
	})/*, a(".sub-nav-wrap a").bind("click", function() {
		a(this).parents(".sub-nav-wrap").hide()
	}), a(".sub-nav").each(function() {
		a(this).on("mouseover", "li", function() {
			var b = a(this);
			b.siblings().removeClass("on"), b.addClass("on")
		}).on("mouseleave", "li", function() {
			var b = a(this);
			b.removeClass("on")
		})
	})*/
}(jQuery), $("#page-tab").ligerTab({
	height: "100%",
	changeHeightOnResize: !0,
	onBeforeAddTabItem: function(a) {
		setCurrentNav(a)
	},
	onAfterAddTabItem: function() {},
	onAfterSelectTabItem: function(a) {
		setCurrentNav(a)
	},
	onBeforeRemoveTabItem: function() {},
	onAfterLeaveTabItem: function(a) {
		switch (a) {
		case "setting-vendorList":
			getSupplier();
			break;
		case "setting-customerList":
			getCustomer();
			break;
		case "setting-storageList":
			getStorage();
			break;
		case "setting-goodsList":
			getGoods();
			break;
		case "setting-settlementaccount":
			getAccounts();
			break;
		case "setting-settlementCL":
			getPayments();
			break;
		case "onlineStore-onlineStoreList":
			break;
		case "onlineStore-logisticsList":
			break;
		case "setting-staffList":
			getStaff()
		}
	},
	onAfterSelectTabItem: function(a) {
		switch (a) {
		case "index":
			dataReflush && dataReflush()
		}
	}
});
var tab = $("#page-tab").ligerGetTabManager();

$(".nav").on("click", "li", function(a) {
	a.preventDefault();
	$(this).addClass("cur").siblings().removeClass("cur");
	$("#col-side").find(".nav-wrap").eq($(this).index()).show().siblings().hide();
});
$("#col-side").on("click", "[rel=pageTab]", function(a) {
	$("#sub-nav").find("li").removeClass("cur");
	a.preventDefault();
	var b = $(this).data("right");
	if (b && !Business.verifyRight(b)) return !1;
	var c = $(this).attr("tabid"),
		d = $(this).attr("href"),
		e = $(this).attr("showClose"),
		f = $(this).attr("tabTxt") || $(this).text().replace(">", ""),
		g = $(this).attr("parentOpen");
		$(this).parent("li").addClass("cur").siblings("li").removeClass("cur");
        if ($(this).parent("li").attr("class")=='cur')
        {
            $(this).parent("li").find(".list_tab").css("display","inline")
        }
        else
        {
            $(this).parent("li").find(".list_tab").css("display","none")
        }
	return g ? parent.tab.addTabItem({
		tabid: c,
		text: f,
		url: d,
		showClose: e
	}) : tab.addTabItem({
		tabid: c,
		text: f,
		url: d,
		showClose: e
	}), !1
}), tab.addTabItem({
	tabid: "site-setting",
	text: "基础设置",
	url: "index.php?ctl=Config&met=site&typ=e&config_type%5B%5D=site",
	showClose: !1
}), function(a) {
	if (2 === SYSTEM.siVersion && SYSTEM.isOpen) {
		var b, c = location.protocol + "//" + location.host + "/update_info.jsp",
			d = '您的单据分录已经录入达到300条，继续使用选择<a href="http://www.yuanfeng021.com/buy/invoicing/" target="_blank">购买产品</a>或者完善个人信息赠送1000条免费容量。';
		SYSTEM.isshortUser ? SYSTEM.isshortUser && (b = "http://service.yuanfeng021.com/user/set_password.jsp?updateUrl=" + encodeURIComponent(c) + "&warning=" + encodeURIComponent(d) + "&loginPage=http://www.yuanfeng021.com/buy/invoicing/", a.dialog({
			min: !1,
			max: !1,
			cancle: !1,
			lock: !0,
			width: 450,
			height: 490,
			title: "完善个人信息",
			content: "url:" + b
		})) : (b = "http://service.yuanfeng021.com/user/phone_validate.jsp?updateUrl=" + encodeURIComponent(c) + "&warning=" + encodeURIComponent(d), a.dialog({
			min: !1,
			max: !1,
			cancle: !1,
			lock: !0,
			width: 400,
			height: 280,
			title: "完善个人信息",
			content: "url:" + b
		}))
	}
}(jQuery), $(window).load(function() {
	function a() {
		var a;
		switch (SYSTEM.siVersion) {
		case 3:
			a = "1";
			break;
		case 4:
			a = "3";
			break;
		default:
			a = "2"
		}
		
	}
	markupVension(), a(), $("#skin-" + SYSTEM.skin).addClass("select").append("<i></i>"),
        $("#sysSkin").powerFloat({
		reverseSharp: !0,
		target: function() {
			return $("#selectSkin")
		},
		showCall: function() {
			$(this).css("background","#456DA3")
		},
		hideCall: function() {
			$(this).css("background","")
		},
		position: "5-7"
	}),
        $("#selectSkin li a").click(function() {
		var a = this.id.split("-")[1];
		Public.ajaxPost("/basedata/systemProfile.do?action=changeSysSkin", {
			skin: a
		}, function(a) {
			200 === a.status && window.location.reload()
		})
	});
	var b = $("#nav .item");
	if ($("#scollUp").click(function() {
		var a = b.filter(":visible");
		a.first().prev().length > 0 && (a.first().prev().show(500), a.last().hide())
	}), $("#scollDown").click(function() {
		var a = b.filter(":visible");
		a.last().next().length > 0 && (a.first().hide(), a.last().next().show(500))
	}), $(".service-tab").click(function() {
		var a = $(this).data("tab");
		tab.addTabItem({
			tabid: "myService",
			text: "服务支持",
			url: "/service/service.jsp",
			callback: function() {
				document.getElementById("myService").contentWindow.openTab(a)
			}
		})
	}), $.cookie("ReloadTips") && (Public.tips({
		content: $.cookie("ReloadTips")
	}), $.cookie("ReloadTips", null)), $("#nav").on("click", "#reInitial", function(a) {
		a.preventDefault(), $.dialog({
			lock: !0,
			width: 430,
			height: 180,
			title: "系统提示",
			content: '<div class="re-initialize"><h3>重新初始化系统将会清空你录入的所有数据，请慎重！</h3><ul><li>系统将删除您新增的所有商品、供应商、客户</li><li>系统将删除您录入的所有单据</li><li>系统将删除您录入的所有初始化数据</li></ul><p><input type="checkbox" id="understand" /><label for="understand">我已清楚了解将产生的后果</label></p><p class="check-confirm">（请先确认并勾选“我已清楚了解将产生的后果”）</p></div>',
			icon: "alert.gif",
			okVal: "重新初始化",
			ok: function() {
				if ($("#understand").is(":checked")) {
					this.close();
					var a = $.dialog.tips("正在重新初始化，请稍候...", 1e3, "loading.gif", !0).show();
					$.ajax({
						type: "GET",
						url: "/user/recover?siId=" + SYSTEM.DBID + "&userName=" + SYSTEM.userName,
						cache: !1,
						async: !0,
						dataType: "json",
						success: function(b) {
							200 === b.status && ($("#container").html(""), a.close(), window.location.href = "start.jsp?re-initial=true&serviceType=" + SYSTEM.serviceType)
						},
						error: function(a) {
							Public.tips({
								type: 1,
								content: "操作失败了哦！" + a
							})
						}
					})
				} else $(".check-confirm").css("visibility", "visible");
				return !1
			},
			cancelVal: "放弃",
			cancel: !0
		})
	}), SYSTEM.siExpired) {
		var c = [{
			name: "立即续费",
			focus: !0,
			callback: function() {
				window.open("http://service.yuanfeng021.com/fee/renew.do?langOption=zh-CHS&accIds=" + SYSTEM.DBID)
			}
		}, {
			name: "下次再说"
		}],
			d = ['<div class="ui-dialog-tips">', "<p>谢谢您使用本产品，您的当前服务已经到期，到期3个月后数据将被自动清除，如需继续使用请购买/续费！</p>", '<p style="color:#AAA; font-size:12px;">(续费后请刷新页面或重新登录。)</p>', "</div>"].join("");
		$.dialog({
			width: 400,
			min: !1,
			max: !1,
			title: "系统提示",
			fixed: !0,
			lock: !0,
			button: c,
			resize: !1,
			content: d
		})
	}
});