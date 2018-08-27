<?php if (!defined('ROOT_PATH')) exit('No Permission');?>

<?php
include $this->view->getTplPath() . '/' . 'seller_header.php';
?>
</head>
<body>

	<!-- <div class="tabmenu">
		<ul>
                    <li class=""><a href="./index.php?ctl=Seller_Shop_Nav&met=nav&typ=e"><?=__('店铺导航')?></a></li>

            <?php if($act == 'add') {?>
            <li  class="active bbc_seller_bg"><a href="javascript:void(0);"><?=__('添加店铺导航')?></a></li>
            <?php }
            if($act == 'edit'){?>
            <li class="active bbc_seller_bg"><a href="javascript:void(0);"><?=__('编辑店铺导航')?></a></li>
            <?php }?>
        </ul>

    </div>
 -->
    <?php if($act == 'add') {?>
    <form id="form" action="#" method="post" >
            <div class="form-style">



        <dl class="dl">
            <dt><i>*</i><?=__('导航名称：')?></dt>
            <dd ><input type="text" class="text w120" name="nav[title]" id="title" value="" /></dd>
        </dl>
         <dl class="dl">
            <dt><?=__('是否显示：')?></dt>
            <dd >  <label class="radio status"><input type="radio"  name="nav[status]" id="status" checked="checked" value="1" /><?=__('是')?></label> <label class="radio status"><input type="radio"  name="nav[status]" id="status" value="0" /><?=__('否')?></label></dd>
        </dl>
        <dl class="dl">
            <dt><i></i><?=__('排序：')?></dt>
            <dd ><input type="text" class="text w50" name="nav[displayorder]" id="displayorder" value="" />
             <p class="hint"><?=__('排序为0-255之间')?></p></dd>
        </dl>
        <dl class="dl">
            <dt><i></i><?=__('内容：')?></dt>
            <dd>

            <!-- 加载编辑器的容器 -->
            <script id="container" style="width:800px;height:300px;" name="nav[detail]" type="text/plain">

            </script>

            
            </dd>
        </dl>
    
        <dl class="dl">
            <dt><i></i><?=__('导航外链URL：')?></dt>
            <dd style="width:50%;">
                <input type="text" class="text w200" name="nav[url]" id="url" value="" />
                <p class="hint"><?=__('请填写包含http://的完整URL地址,如果填写此项则点击该导航会跳转到外链')?></p>
            </dd>
        </dl>
        
           <dl class="dl">
           <dt><?=__('新窗口打开：')?></dt>
           <dd ><label class="radio target"><input type="radio"  name="nav[target]" id="target" checked="checked" value="1" /><?=__('是')?></label> <label class="radio target"><input type="radio"  name="nav[target]" id="target" value="0" /><?=__('否')?></label></dd>
        </dl>
        <dl>
            <dt></dt>
            <dd><input type="submit" class="button bbc_seller_submit_btns" value="<?=__('确认提交')?>" /></dd>
        </dl>
    </div>
    </form>
    <?php }
    if($act == 'edit' && $data){?>
    <form id="form" action="#" method="post" >

    <div class="form-style">

  
        <input type="hidden" name="id" id="id" value="<?=$data['id']?>" />

        <dl class="dl">
            <dt><i>*</i><?=__('导航名称：')?></dt>
            <dd ><input type="text" class="text w120" name="nav[title]" id="title" value="<?=$data['title']?>" /></dd>
        </dl>
         <dl class="dl">
            <dt><?=__('是否显示：')?></dt>
            <dd >  <label class="radio status"><input type="radio" <?=($data['status'] ? 'checked' : '') ?>  name="nav[status]" id="status" value="1" /><?=__('是')?></label> <label class="radio status"><input type="radio"   <?=(!$data['status'] ? 'checked' : '') ?>  name="nav[status]" id="status" value="0" /><?=__('否')?></label></dd>
        </dl>
        <dl class="dl">
            <dt><i></i><?=__('排序：')?></dt>
            <dd ><input type="text" class="text w50" name="nav[displayorder]" id="displayorder" value="<?=$data['displayorder']?>" />
             <p class="hint"><?=__('排序为0-255之间')?></p></dd>
        </dl>
        <dl class="dl">
            <dt><i></i><?=__('内容：')?></dt>
            <dd>
            
            <!-- 加载编辑器的容器 -->
            <script id="container" style="width:800px;height:300px;" name="nav[detail]" type="text/plain">
                <?=$data['detail']?>
            </script>

            </dd>
        </dl>
    
        <dl class="dl">
            <dt><i></i><?=__('导航外链URL：')?></dt>
            <dd style="width:50%;">
                <input type="text" class="text w200" name="nav[url]" id="url" value="<?=$data['url']?>" />
                <p class="hint"><?=__('请填写包含http://的完整URL地址,如果填写此项则点击该导航会跳转到外链')?></p>
            </dd>
        </dl>
        
           <dl class="dl">
           <dt><?=__('新窗口打开：')?></dt>
           <dd ><label class="radio target"><input type="radio" <?=($data['target'] ? 'checked' : '') ?>  name="nav[target]" id="target" value="1" /><?=__('是')?></label> <label class="radio target"><input type="radio"  <?=(!$data['target'] ? 'checked' : '') ?> name="nav[target]" id="target" value="0" /><?=__('否')?></label></dd>
        </dl>
        <dl>
            <dt></dt>
            <dd><input type="submit" class="button bbc_seller_submit_btns" value="<?=__('确认提交')?>" /></dd>
        </dl>
    </div>
    </form>
    <?php }?>
<script>
       $(document).ready(function(){
    
        var ajax_url = './index.php?ctl=Seller_Shop_Nav&met=<?=$act?>Nav&typ=json';
         
        $('#form').validator({
            ignore: ':hidden',
            theme: 'yellow_right',
            timely: 1,
            stopOnError: true,
            rules: {
              
            },

            fields: {
                'nav[title]': 'required;length[1~10]',
                'nav[displayorder]':'range[0~255];integer',
            },
           valid:function(form){
                 var me = this;
                // 提交表单之前，hold住表单，防止重复提交
                me.holdSubmit();
                //表单验证通过，提交表单
                $.ajax({
                    url: ajax_url,
                    data:$("#form").serialize(),
                    success:function(a){
                        if(a.status == 200)
                        {
                           parent.Public.tips.success("<?=__('操作成功')?>");
                           setTimeout('location.href="index.php?ctl=Seller_Shop_Nav&met=nav&typ=e"',3000); //成功后跳转
                        }
                        else
                        {
                            parent.Public.tips.error("<?=__('操作失败！')?>");
                        }
                    }
                });
            }

        });
    });



//    function submitBtn()
//    {
//        $("#form").ajaxSubmit(function(message){
//            if(message.status == 200)
//            {
//                location.href="index.php?ctl=Seller_Shop_Nav&met=nav";
//            }
//            else
//            {
//                alert('操作失败！');
//            }
//        });
//        return false;
//    }
</script>
    <!-- 配置文件 -->
    <script type="text/javascript" src="<?= $this->view->js_com ?>/ueditor/ueditor.config.js"></script>
    <!-- 编辑器源码文件 -->
    <script type="text/javascript" src="<?= $this->view->js_com ?>/ueditor/ueditor.all.js"></script>

    <script type="text/javascript" src="<?= $this->view->js_com ?>/upload/addCustomizeButton.js"></script>

    <!-- 实例化编辑器 -->
    <script type="text/javascript">
        var ue = UE.getEditor('container', {
            toolbars: [
                [
                 'bold', 'italic', 'underline', 'forecolor', 'backcolor', 'justifyleft', 'justifycenter', 'justifyright', 'insertunorderedlist', 'insertorderedlist', 'blockquote',
                 'emotion', 'insertvideo', 'link', 'removeformat', 'rowspacingtop', 'rowspacingbottom', 'lineheight', 'paragraph', 'fontsize', 'inserttable', 'deletetable', 'insertparagraphbeforetable',
                 'insertrow', 'deleterow', 'insertcol', 'deletecol', 'mergecells', 'mergeright', 'mergedown', 'splittocells', 'splittorows', 'splittocols'
                ]
            ],
            autoClearinitialContent: true,
            //关闭字数统计
            wordCount: false,
            //关闭elementPath
            elementPathEnabled: false
        });
            ue.ready(function() {
            ue.setContent('<?=(@$data['detail'])?>');
        });
    </script>
<?php
include $this->view->getTplPath() . '/' . 'seller_footer.php';
?>