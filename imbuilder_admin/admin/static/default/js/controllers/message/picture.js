$(function() {
    var a = {fileList: {},api: frameElement.api,page: {$container: $(".container"),$upfile: $("#upfile"),$content: $(".content"),$progress: $("#progress"),$fileinputButton: $("#fileinput-button"),uploadLock: !1},init: function() {
        try {
            document.domain = thisDomain
        } catch (a) {
        }
        this.initUpload(), this.initPopBtns(), this.initEvent(), this.initDom()
    },initDom: function() {
        var b = a.api.data || {};
                                 // SITE_URL +'?ctl=User_Info&met=getImagesById&typ=json'
                                 // "./admin.php?ctl=Message_Record&met=getImagesById&isDlete=2&typ=json"
        b.id && Public.ajaxPost("./admin.php?ctl=Message_Record&met=getImagesById&isDlete=2&typ=json", {id: b.id}, function(b) {
            200 == b.status ? a.addImgDiv(b.files) : parent.parent.Public.tips({type: 1,content: "获取商品图片失败！"})
        })
    },initPopBtns: function() {
        var b = ["保存", "关闭"];
        this.api.button({id: "ok",name: b[0],hidden:1,focus: !0,callback: function() {
            return a.postData(), !1
        }}, {id: "cancel",name: b[1],hidden:1})
    },postData: function() {
        var b = a.api.data || {}, c = (b.callback, a.getCurrentFiles()), d = [];
        for (var e in c)
            d.push(c[e]);
        if (b.id) {
            var f = {id: b.id,files: d};
            Public.ajaxPost("./index.php?ctl=Goods_Gallery&met=addImagesToInv", {postData: JSON.stringify(f)}, function(a) {
                200 == a.status ? parent.parent.Public.tips({content: "保存商品图片成功！"}) : parent.parent.Public.tips({type: 1,content: a.msg})
            })
        }
    },initEvent: function() {
        var b = this.page;
        b.$container.on("click", ".uploading", function(a) {
            parent.parent.Public.tips({type: 2,content: "正在上传，请稍等！"})
        }), b.$container.on("mouseenter", ".imgDiv", function(a) {
            $(this).addClass("hover")
        }), b.$container.on("mouseleave", ".imgDiv", function(a) {
            $(this).removeClass("hover")
        }), b.$container.on("click", ".del", function(b) {
            b.stopPropagation();
            var c = $(this).closest(".imgDiv").hide().data("pid");
            a.fileList[c].status = 0
        }), b.$container.on("click", "img", function(a) {
            var b = $(this), c = b.prop("src");
            window.open(c)
        })
    },initUpload: function() {
        var b = this.page;
        b.liList = b.$content.find("li");
        b.$upfile.fileupload({url: "./index.php?ctl=Goods_Gallery&met=uploadImages",maxFileSize: 5e6,sequentialUploads: !0,acceptFileTypes: /(\.|\/)(gif|jpe?g|png|bmp)$/i,dataType: "json",done: function(b, c) {
            200 != c.result.status ? parent.parent.Public.tips({type: 2,content: c.result.msg || "上传失败！"}) : a.addImgDiv(c.result.files)
        },add: function(a, c) {
            $.each(c.files, function(a, d) {
                b.$fileinputButton.addClass("uploading"), c.submit()
            })
        },beforeSend: function(a, b) {
            "775px" === $("#progress .bar").css("width") && $("#progress .bar").css("width", "0")
        },progressall: function(a, c) {
            var d = parseInt(c.loaded / c.total * 100, 10);
            $("#progress .bar").stop().animate({width: d + "%"}, 1e3), 100 == d && b.$fileinputButton.removeClass("uploading")
        }})
    },addImgDiv: function(b) {
        $.each(b, function(b, c) {
            a.fileList[c.pid] = c;
            //console.info(c);
            var d = a.getHeightMinLi();
            //console.info(d);
            if (d) {
                var e = $('<div class="imgDiv" data-pid="' + c.pid + '"><p class="imgControl"><span class="del">X</span></p><img src="' + c.url + '" alt="' + c.name + '"/></div>');
                d.append(e)
            }
        }), $(".img-warp").animate({scrollTop: $("body")[0].scrollHeight}, 500)
    },getHeightMinLi: function() {
        var a, b = this.page;
        return b.liList.each(function(b) {
            var c = $(this);
            a ? c.height() < a.height() && (a = c) : a = c
        }), a
    },getCurrentFiles: function() {
        return this.fileList
    }};
    a.init()
});
