UE.registerUI('uploadimage',function(editor,uiName){
    //注册按钮执行时的command命令，使用命令默认就会带有回退操作


    //创建dialog
    var dialog = new UE.ui.Dialog({
        //指定弹出层中页面的路径，这里只能支持页面,因为跟addCustomizeDialog.js相同目录，所以无需加路径
        iframeUrl: SITE_URL + '?ctl=Upload&met=image&typ=e',
        //需要指定当前的编辑器实例
        editor:editor,
        //指定dialog的名字
        name:uiName,
        //dialog的标题
        title:"<span id='first-title'>我的图片</span><span class='unselected-title' id='second-title'>图标库</span>",

        //指定dialog的外围样式
        cssRules:"width:900px;height:593px;",

        //如果给出了buttons就代表dialog有确定和取消
//        buttons:[
///*            {
////                className:'edui-okbutton',
//                label:'确定',
//                onclick:function () {
//                    dialog.close(true);
//                }
//            },
//            {
////                className:'edui-cancelbutton',
//                label:'取消',
//                onclick:function () {
//                    dialog.close(false);
//                }
//            }*/
//        ]
    	});
    
    
	    var btn = new UE.ui.Button({
	        name:'dialogbutton' + uiName,
	        title: '图片',
	        //需要添加的额外样式，指定icon图标，这里默认使用一个重复的icon
	        cssRules :'background-position: -380px 0;',
	        onclick:function () {
	            //渲染dialog
	            dialog.render();
	            dialog.open();
	        }
	    });



    //因为你是添加button,所以需要返回这个button
    return btn;
},12/*index 指定添加到工具栏上的那个位置，默认时追加到最后,editorId 指定这个UI是那个编辑器实例上的，默认是页面上所有的编辑器都会添加这个按钮*/);