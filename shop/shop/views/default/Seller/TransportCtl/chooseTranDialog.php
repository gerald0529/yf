<?php if (!defined('ROOT_PATH')) exit('No Permission');?>
<!DOCTYPE HTML>
<html>
<head>
    <link href="<?= $this->view->css ?>/seller.css?ver=<?= VER ?>" rel="stylesheet">
    <link href="<?= $this->view->css ?>/iconfont/iconfont.css?ver=<?= VER ?>" rel="stylesheet" type="text/css">
    <link href="<?= $this->view->css ?>/seller_center.css?ver=<?= VER ?>" rel="stylesheet">
    <link href="<?= $this->view->css ?>/base.css?ver=<?= VER ?>" rel="stylesheet">
    <script type="text/javascript" src="<?=$this->view->js_com?>/jquery.js" charset="utf-8"></script>
</head>
<body>

<div class="dialog_content" style="margin: 0px; padding: 0px;">
    <div class="eject_con">
        <div class="adds" style=" min-height:240px;">
            <table class="ncsc-default-table">
                <thead>
                <tr>
                    <th class="w200"><?=__('模板名称')?></th>
                    <th class="w400"><?=__('售卖区域')?></th>
                    <th class="w200"></th>
                </tr>
                </thead>
                
                <tbody>
                <?php if ( !empty($transport_list) ) { ?>
                    <?php foreach ( $transport_list as $key => $val ) { ?>
                        <tr class="bd-line">
                            <td class="tc"><?= $val['name']; ?></td>
                            <td class="tc"><?= $val['area_name']; ?></td>
                            <td class="tc">
                                <a href="javascript:void(0);" nc_type="select" class="ncbtn bbc_seller_btns" data-transport_area_name="<?= $val['name']; ?>" data-transport_area_id="<?= $val['id']; ?>"><?=__('选择')?></a>
                            </td>
                        </tr>
                    <?php } ?>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

</div>

</body>
</html>

<script>

    api = frameElement.api;
    callback = api.data.callback;

    $(function () {

        $('a[nc_type="select"]').on('click', function () {

            if ( typeof callback == 'function' ) {

                var data = { transport_area_name: $(this).data('transport_area_name'), transport_area_id: $(this).data('transport_area_id') };

                callback(data, api);
            }
        })
    })
</script>