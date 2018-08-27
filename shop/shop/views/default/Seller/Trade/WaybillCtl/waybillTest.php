
<html>

<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8">
    <title><?=__('快递单据打印测试')?></title>
    <style>
        body { margin: 0; text-align:center;}
        .waybill_area { position: relative; width: 820.8px; height: 528.2px; }
        .waybill_back { position: relative; width: 820.8px; margin:auto;padding-top:30px;}
        /*.waybill_back img { width: <?= $waybill_data['waybill_tpl_width'] * 3.8; ?>px; height: <?= $waybill_data['waybill_tpl_height'] * 3.8; ?>px; }*/
        .waybill_design { position: absolute; left: 0; top:30px; width: 820.8px; height: 528.2px; }
        .waybill_item { position: absolute; left: 0; top: 0; max-width:100px; height: 20px; border: 1px solid #CCCCCC; }
        .print-btn { background:#FFF; border: solid 1px #ccc; line-height:32px; display: inline-block; padding:5px 10px;border-radius: 5px; box-shadow: 2px 2px 0 rgba(153,153,153,0.2); cursor: pointer;}
        .print-btn:hover {  background: #555; box-shadow: none; border-color: #555;}
        .print-btn a { font-family:"microsoft yahei"; font-size: 20px; text-decoration: none; padding: 0 0 0 10px; color: #555; font-weight:600; display:inline-block; vertical-align: middle;}
        .print-btn:hover a, .print-layout .print-btn a:hover { color: #FFF;  text-decoration:none;}
    </style>
</head>

<body>
    <div class="waybill_back"> <img src="<?php echo $waybill_data['waybill_tpl_image']; ?>" alt="" style="max-height:100%;max-width:800px;"> <div>
        <?php if ( $waybill_data['waybill_tpl_item'] ) { ?>
        <?php foreach ( $waybill_data['waybill_tpl_item'] as $key => $val ) { ?>
        <div class="waybill_item" style="left:<?= $val['left']; ?>px; top:<?= $val['top']; ?>px; width:<?= $val['width']; ?>px; height:<?= $val['height']; ?>px;"><?php if ( empty($val['value']) ) { echo $val['name']; } else { echo $val['value']; } ?></div>
        <?php } ?>
        <?php } ?>
    </div></div>
    
    <?php if ( !empty($data) ) { ?>
    <div class="control"><?=__('其它模板')?>：
         <select id="waybill_list">
         <?php foreach ( $data as $key => $val ) { ?>
             <option value="<?= $val['way_bill']['waybill_tpl_id']; ?>"><?= $val['way_bill']['waybill_tpl_name']; ?></option>
         <?php } ?>
        </select>
    </div>
    <?php } ?>
    <div class="print-btn"><i></i><a id="btn" class="" href="javascript:;"><?=__('打印运单')?></a> </div>
</body>

</html>

<script type="text/javascript" src="<?=$this->view->js_com?>/jquery.js" charset="utf-8"></script>
<script>
    $(document).ready(function() {
        $('#btn').on('click', function() {
            pos();

            $('.waybill_back').hide();
            $('.control').hide();

            window.print();
        });

        var pos = function () {
            var top = <?= $waybill_data['waybill_tpl_top']; ?>;
            var left = <?= $waybill_data['waybill_tpl_left']; ?>;

            $(".waybill_design").each(function(index) {
                var offset = $(this).offset();
                var offset_top = offset.top + top;
                var offset_left = offset.left + left;
                $(this).offset({ top: offset_top, left: offset_left})
            });
        };

        $(document).on('change', 'select', function () {
            var waybill_tpl_id = $('select').val();
            if ( window.location.href.indexOf('waybill_tpl_id') > -1 ) {
                window.location.href = window.location.href.replace(/waybill_tpl_id=\w+/, 'waybill_tpl_id=' + waybill_tpl_id );
            } else {
                window.location.href += '&waybill_tpl_id=' + waybill_tpl_id;
            }
        });

        <?php if ( !empty($waybill_tpl_id) ) { ?>
        $('select').children('option[value="<?= $waybill_tpl_id ?>"]').prop('selected', 'selected');
        <?php } ?>
    });

</script>
