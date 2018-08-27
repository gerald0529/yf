var system = parent.SYSTEM,
    urlParam = Public.urlParam();
system.taxRequiredInput = system.taxRequiredInput ? system.taxRequiredInput : 0;
var siType = system.siType,
    THISPAGE = {
        locaData: {
            funtParams: [
                {
                    id: 'billRequiredCheck',
                    PID: '',
                    name: '审核',
                    value: 1 === system.billRequiredCheck,
                    type: 'checkbox',
                    describe: '（启用后单据只有在审核后才会生效，报表数据才会变化）'
                },
                {
                    id: 'taxRequiredCheck',
                    PID: '',
                    name: '税金',
                    value: 1 === system.taxRequiredCheck,
                    type: 'checkbox',
                    describe: ' 增值税税率'
                },
                {
                    id: 'taxRequiredInput',
                    PID: 'taxRequiredCheck',
                    name: '',
                    value: system.taxRequiredInput,
                    type: 'text',
                    describe: '%'
                },
                {
                    id: 'hasOnlineStore',
                    PID: '',
                    name: '网店',
                    value: 1 === system.hasOnlineStore,
                    type: 'checkbox',
                    describe: '（启用后将新增网店菜单模块）'
                },
                {
                    id: 'enableStorage',
                    PID: '',
                    name: '京东仓储',
                    value: 1 === system.enableStorage,
                    type: 'checkbox',
                    describe: '（启用后将新增京东仓储相关功能）'
                },
                {
                    id: 'skuRequired',
                    PID: '',
                    name: '辅助属性',
                    value: 1 === system.enableAssistingProp,
                    type: 'checkbox',
                    describe: '（启用后允许商品新增服装、尺码等自定义属性）'
                },
                {
                    id: 'ISSERNUM',
                    PID: '',
                    name: '序列号',
                    value: 1 === system.ISSERNUM,
                    type: 'checkbox',
                    describe: ' （启用后将新增商品序列号管理功能） '
                },
                {
                    id: 'ISWARRANTY',
                    PID: '',
                    name: '批次保质期管理',
                    value: 1 === system.ISWARRANTY,
                    type: 'checkbox',
                    describe: ' （启用后将新增商品保质期管理功能）'
                }
            ]
        },
        init: function (a) {
            this.loadData(),
            this.initDom(),
                this.addEvent(),
                this.$_sign_time.datepicker(),
            this.$_effective_date_start.datepicker(),
            this.$_effective_date_end.datepicker()
        },
        initDom: function () {
            var a = [
                    {
                        value: 'movingAverage',
                        text: '移动平均法'
                    }
                ],
                b = !0;

            if (this.$_company_name = $('#company_name'), this.$_company_phone = $('#company_phone'), this.$_contacter = $('#contacter'), this.$_sign_time = $('#sign_time'),this.$_plantform_url = $('#plantform_url'), this.$_account_num = $('#account_num'),this.$_upload_path = $('#upload_path'), this.$_business_agent = $('#business_agent'), this.$_price = $('#price'), this.$_effective_date_start = $('#effective_date_start'), this.$_effective_date_end = $('#effective_date_end'),this.$_user_name = $('#user_name'), b = !1, this.locaData.funtParams.length > 0) {
                for (var c = '', d = [
                ], e = 0; e < this.locaData.funtParams.length; e++) {
                    var f = this.locaData.funtParams[e];
                    if ('' != f.PID) switch (f.type) {
                        case 'text':
                            var g = '<input style=\'margin-left:10px;width:40px;text-align:right\' class=\'ui-input\' type=\'text\' id=\'' + f.id + '\' defaultValue = \'' + f.value + '\' value = \'' + f.value + '\'/><span class=\'tips\'>' + f.describe + '</span>';
                            d.push({
                                id: f.id,
                                html: g,
                                PID: f.PID,
                                callback: function (a, b) {
                                    a[0].checked ? (b.val(b.attr('defaultvalue')), b.attr('disabled', !1).removeClass('ui-input-dis'))  : (b.val(b.attr('defaultvalue')), b.attr('disabled', !0).addClass('ui-input-dis')),
                                        a.click(function () {
                                            this.checked ? (b.val(b.attr('defaultvalue')), b.attr('disabled', !1).removeClass('ui-input-dis'))  : (b.val(b.attr('defaultvalue')), b.attr('disabled', !0).addClass('ui-input-dis'))
                                        })
                                }
                            })
                    } else {
                        f.describe = '' == f.describe ? '' : f.describe;
                        var h = 'checkbox' == f.type && f.value ? 'checked' : '';
                        c += '<li class="row-item"><div class="label-wrap"><label>是否启用' + f.name + '：</label></div><div class="ctn-wrap"><input type="' + f.type + '" id="' + f.id + '" ' + h + '><label for="' + f.id + '" class="tips">' + f.describe + '</label></div></li>'
                    }
                }
                /*this.$_establishForm.append(c).parent('div').show(),
                    this.$_establishForm.closest('.para-item').show();
                for (var e = 0, i = d.length; i > e; e++) {
                    var j = $('#' + d[e].PID);
                    j.closest('div').append(d[e].html),
                        d[e].callback(j, $('#' + d[e].id))
                }*/
            }
            /*this.valMethodsCombo = this.$_valMethods.combo({
                data: a,
                text: 'text',
                value: 'value',
                width: 230,
                disabled: b,
                defaultSelected: [
                    'value',
                    system.valMethods
                ]
            }).getCombo()*/
        },
        addEvent: function () {
            var a = this;
            $('#save').click(function () {
                if (Business.verifyRight('SYSTEM_UPDATE')) {
                    var b = $.trim(a.$_company_name.val());
                    var r = $.trim(a.$_user_name.val());
                    if (!b) return Public.tips({
                        type: 2,
                        content: '公司名称不能为空！'
                    });
                    if (!r) return Public.tips({
                        type: 2,
                        content: '用户名称不能为空！'
                    });
                    console.info(a);
                    var g = {
                        company_name:b,
                        company_phone: a.$_company_phone.val(),
                        contacter: a.$_contacter.val(),
                        sign_time: a.$_sign_time.val(),
                        plantform_url: a.$_plantform_url.val(),
                        account_num: a.$_account_num.val(),
                        /*db_name: a.$_db_name.val(),
                        db_host: a.$_db_host.val(),
                        db_passwd: a.$_db_passwd.val(),*/
                        upload_path: a.$_upload_path.val(),
                        business_agent: a.$_business_agent.val(),
                        price: a.$_price.val(),
                        effective_date_start: a.$_effective_date_start.val(),
                        effective_date_end: a.$_effective_date_end.val(),
                        user_name: a.$_user_name.val(),
                        service_id: urlParam.id
                    };
                    $.dialog.confirm('所有信息填写正确么，确认保存？', function () {
                        Public. ajaxPost('./admin.php?ctl=Purchase_Information&met=save&typ=json',g,function (b) {
                            200 === b.status ? (Public.tips({content: "保存成功！"}))  : (Public.tips({content: "保存失败！",type:1}), a.close())
                        }, function (b) {
                            a.close()
                        })
                    })
                }
            })
        },
        loadData: function(){
            var a = urlParam.id;
            if(a){
                Public.ajaxPost("./admin.php?ctl=Purchase_Information&met=list1",{id:a},function(b){
                    $('#service_id').val(a);
                    $('#company_name').val(b.data.company_name);
                    $('#company_phone').val(b.data.company_phone);
                    $('#contacter').val(b.data.contacter);
                    $('#sign_time').val(b.data.sign_time);
                    $('#account_num').val(b.data.account_num);
                    $('#user_name').val(b.data.user_name);
                    $('#upload_path').val(b.data.upload_path);
                    $('#business_agent').val(b.data.business_agent);
                    $('#price').val(b.data.price);
                    $('#effective_date_start').val(b.data.effective_date_start);
                    $('#effective_date_end').val(b.data.effective_date_end);
                    $('#company_name').val(b.data.company_name);
                    $('#plantform_url').val(b.data.plantform_url);
                })
            }
        }
    };
THISPAGE.init();
