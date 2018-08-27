<?php if (!defined('ROOT_PATH')){exit('No Permission');}?>
<?php
include $this->view->getTplPath() . '/' . 'seller_header.php';
?>
<!-- 配置文件 -->
<script type="text/javascript" src="<?= $this->view->js_com ?>/ueditor/ueditor.config.js"></script>
<!-- 编辑器源码文件 -->
<script type="text/javascript" src="<?= $this->view->js_com ?>/ueditor/ueditor.all.js"></script>
<script type="text/javascript" src="<?= $this->view->js_com ?>/ueditor/ueditor.parse.js"></script>
<script type="text/javascript" src="<?= $this->view->js_com ?>/upload/addCustomizeButton.js"></script>
<div class="freight">

    <?php if($act == 'edit' && $data){?>
        <form id="form" action="#" method="post" >

            <div class="form-style">
                <dl class="dl" style="display: none;">
                    <dt><i>*</i><?=__('版式名称')?>:</dt>
                    <dd ><input type="text" class="text w120" name="id" id="title" value="<?=$data['id']; ?>" /></dd>
                </dl>
                <dl class="dl">
                    <dt><i>*</i><?=__('版式名称')?>:</dt>
                    <dd ><input type="text" class="text w120" name="name" id="title" value="<?=$data['name']; ?>" /></dd>
                </dl>
                <dl class="row">
                    <dt class="tit">
                        <span style="margin-right:5px;"><i>*</i><?=__('版式位置')?>:</span>
                    </dt>
                    <select name="position" class="selected_edit">
                        <option value="<?=Goods_FormatModel::FORMAT_POSITION_TOP?>" <?=Goods_FormatModel::FORMAT_POSITION_TOP==@$data['position']?'selected':''?>><?=__('顶部')?></option>
                        <option value="<?=Goods_FormatModel::FORMAT_POSITION_BOTTOM?>" <?=Goods_FormatModel::FORMAT_POSITION_BOTTOM==@$data['position']?'selected':''?>><?=__('底部')?></option>
                    </select>
                </dl>
                <dl class="row">
                    <dt class="tit">
                        <label><i>*</i><?=__('版式内容')?>:</label>
                    </dt>
                    <dd class="opt">
                        <!-- 加载编辑器的容器 -->
                        <textarea id="content" style="width:700px;height:300px;" name="content" type="text/plain">

                        </textarea>
                    </dd>
                </dl>
                <dl>
                    <dt></dt>
                    <dd><input type="submit" class="button button_red bbc_seller_submit_btns" value="<?=__('确认提交')?>" /></dd>
                </dl>
            </div>
        </form>
    <?php }
    else {?>
        <form id="form" action="#" method="post" >
            <div class="form-style">
                <dl class="dl">
                    <dt><i>*</i><?=__('版式名称')?>:</dt>
                    <dd ><input type="text" class="text w120" name="name" id="title" value="" /></dd>
                </dl>
                <dl class="row">
                    <dt class="tit">
                        <span style="margin-right:5px;"><i>*</i><?=__('版式位置')?>:</span>
                    </dt>
                    <select name="position" class="selected_edit">
                        <option value="<?=Goods_FormatModel::FORMAT_POSITION_TOP?>" <?=Goods_FormatModel::FORMAT_POSITION_TOP==@$_GET['position']?'selected':''?>><?=__('顶部')?></option>
                        <option value="<?=Goods_FormatModel::FORMAT_POSITION_BOTTOM?>" <?=Goods_FormatModel::FORMAT_POSITION_BOTTOM==@$_GET['position']?'selected':''?>><?=__('底部')?></option>
                    </select>
                </dl>
                <dl class="row">
                    <dt class="tit">
                        <label><i>*</i><?=__('版式内容')?>:</label>
                    </dt>
                    <dd class="opt">
                        <!-- 加载编辑器的容器 -->
                        <textarea id="content" style="width:700px;height:300px;" name="content" type="text/plain">

                        </textarea>
                    </dd>
                </dl>
                <dl>
                    <dt></dt>
                    <dd><input type="submit" class="button button_red bbc_seller_submit_btns" value="<?=__('确认提交')?>" /></dd>
                </dl>
            </div>
        </form>
    <?php }?>

</div>
<!-- 实例化编辑器 -->
<script type="text/javascript">
    var ue = UE.getEditor('content', {
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
</script>
<script>
    $(document).ready(function(){
        ue.ready(function() {
            <?php if(isset($data['content'])): ?>
                ue.setContent("<?= trim($data['content']);?>");
            <?php endif; ?>
        });
        var ajax_url = SITE_URL + '?ctl=Seller_Goods&met=<?=$act?>FormatRow&typ=json';
        $('#form').validator({
            ignore: ':hidden',
            theme: 'yellow_right',
            timely: 1,
            stopOnError: false,
            rules: {

            },

            fields: {
                'name': 'required;length[~10]',
            },
            valid:function(form){
                if(!ue.hasContents())
                {
                    Public.tips({ content: "<?=__('请输入版式内容'); ?>"});
                    return false;
                }
                //表单验证通过，提交表单
                $.ajax({
                    url: ajax_url,
                    data:$("#form").serialize(),
                    type:"POST",
                    success:function(a){
                        if(a.status == 200)
                        {
                            Public.tips({type:3, content: "<?=__('操作成功'); ?>"});
                            // if (opener) {
                            //     opener.addLayout({
                            //                         id: a.data.id,
                            //                         name: $("#title").val(),
                            //                         position: $("select[name=\"position\"]").val()
                            //                     });
                            //     window.close();
                            // } else {
                                location.href=SITE_URL + "?ctl=Seller_Goods&met=format&typ=e";
                            // }
                        }
                        else
                        {
                            Public.tips({type:1, content: "<?=__('操作失败'); ?>"});
                        }
                    }
                });
            }

        });
    });



</script>
<?php
include $this->view->getTplPath() . '/' . 'seller_footer.php';
?>