<?php if (!defined('ROOT_PATH')){exit('No Permission');}?>
<?php
include $this->view->getTplPath() . '/' . 'seller_header.php';
?>
<link href="<?= $this->view->css ?>/seller_center.css?ver=<?=VER?>" rel="stylesheet">

<div class="ncsc-form-default">
  <form id="form" method="post" action="#">
    <dl>
      <dt><?=__('启用店铺装修：')?></dt>
      <dd>
        <label for="store_decoration_switch_on" class="mr30">
            <input  type="radio"  name="is_renovation" value="1" <?php if($renovation_list['is_renovation'] == "1"){?>checked="checked"<?php }?> >
          <?=__('是')?></label>
        <label for="store_decoration_switch_off">
          <input type="radio" name="is_renovation" value="0" <?php if($renovation_list['is_renovation'] == "0"){?>checked="checked"<?php }?>>
          <?=__('否')?></label>
        <p class="hint"><?=__('选择是否使用店铺装修模板；')?><br/>
          <?=__('如选择“是”，店铺首页背景、头部、导航以及上方区域都将根据店铺装修模板所设置的内容进行显示；')?><br/>
          <?=__('如选择“否”根据')?> <a href="index.php?ctl=Seller_Shop_Setshop&met=theme&typ=e"><?=__('“店铺主题”')?></a> <?=__('所选中的系统预设值风格进行显示。')?></p>
      </dd>
    </dl>
    <dl>
      <dt><?=__('仅显示装修内容：')?></dt>
      <dd>
        <label for="store_decoration_only_on" class="mr30">
            <input  type="radio"  name="is_only_renovation" value="1"  <?php if($renovation_list['is_only_renovation'] == "1"){?>checked="checked"<?php }?>>
          <?=__('是')?></label>
        <label for="store_decoration_only_off">
          <input  type="radio"  name="is_only_renovation" value="0" <?php if($renovation_list['is_only_renovation'] == "0"){?>checked="checked"<?php }?>>
          <?=__('否')?></label>
        <p class="hint"><?=__('该项设置如选择“是”，则店铺首页仅显示店铺装修所设定的内容；')?><br/>
         <?=__('如选择“否”则按标准默认风格模板延续显示页面下放内容，即左侧店铺导航、销售排行，右侧轮换广告、最新商品、推荐商品等相关店铺信息。')?> </p>
      </dd>
    </dl>

    <dl>
      <dt><?=__('店铺装修：')?></dt>
      <dd> <a href="index.php?ctl=Seller_Shop_Decoration&met=decoration&act=set&typ=e&" class="ncbtn ncbtn-aqua mr5 bbc_seller_bg" style="margin-left: 0px;color:#ffffff;" target="_blank"><i class="icon-puzzle-piece"></i><?=__('装修页面')?></a> 
<!--          <a id="btn_build" href="" class="ncbtn ncbtn-bittersweet" target="_blank"><i class="icon-magic"></i>生成页面</a>-->
        <p class="hint"><?=__('点击“装修页面”按钮，在新窗口对店铺首页进行装修设计；')?><br/>
       </p>
      </dd>
    </dl>
    <div class="bottom">
      <label class="submit-border">
          <input  type="submit" class="bbc_seller_submit_btns" value="<?=__('提交')?>" />
      </label>
    </div>
  </form>
</div>

<script type="text/javascript">
    function refreshPage() 
{ 
 parent.location.reload();
} 

     $(document).ready(function(){
         var ajax_url = './index.php?ctl=Seller_Shop_Decoration&met=editRenovation&typ=json';
        $('#form').validator({
            ignore: ':hidden',
            theme: 'yellow_right',
            timely: 1,
            stopOnError: false,
            rules: {
            
            },
            fields: {
             
            },
           valid:function(form){
                //表单验证通过，提交表单
                $.ajax({
                    url: ajax_url,
                    data:$("#form").serialize(),
                    success:function(a){
                        if(a.status == 200)
                        {
                             Public.tips.success("<?=__('操作成功！')?>");
                            // window.setTimeout("refreshPage()",3000);
                        }
                        else
                        {
                            Public.tips.error("<?=__('操作失败！')?>");
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

