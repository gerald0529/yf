<?php if (!defined('ROOT_PATH')) {
    exit('No Permission');
}
    
    include $this->view->getTplPath() . '/' . 'buyer_header.php';
?>
    
    </div>
    <div class="order_content_title clearfix">
        <div style="margin-top: 10px;" class="clearfix">
            <form id="search_form" method="get">
                <input type="hidden" name="ctl" value="Buyer_Voucher" />
                <input type="hidden" name="met" value="voucher" />
                <p class="pright">
                    <select name="state">
                        <option value="" <?php if ($state == '') {
                            echo "selected";
                        } ?>><?= __('选择状态') ?></option>
                        <option value="1" <?php if ($state == 1) {
                            echo "selected";
                        } ?>><?= __('未用') ?></option>
                        <option value="2" <?php if ($state == 2) {
                            echo "selected";
                        } ?>><?= __('已用') ?></option>
                        <option value="3" <?php if ($state == 3) {
                            echo "selected";
                        } ?>><?= __('过期') ?></option>
                    </select>
                    <a class="btn_search_goods sous" href="javascript:void(0);">
                        <i class="iconfont icon-btnsearch  icon_size18"></i><?= __('搜索') ?></a></p>
            </form>
        </div>
    </div>
    <script type="text/javascript">
        $(".sous").on("click", function () {
            $("#search_form").submit();
        });
    </script>
    <table class="ncm-default-table annoc_con">
        <thead>
        <tr class="bortop">
            
            <th class="w150"><?= __('面额') ?></th>
            <th class="tl opti"><?= __('有效期') ?></th>
            <th class="tl opti"><?= __('使用条件') ?></th>
            <th class="w120"><?= __('状态') ?></th>
            <th class="w110"><?= __('操作') ?></th>
        </tr>
        </thead>
        <tbody>
        <?php if (!empty($data['items'])) { ?>
            <?php foreach ($data['items'] as $key => $val) { ?>
                <tr class="bd-line">
                    
                    <td><?= format_money($val['voucher_price']) ?></td>
                    <td class="tl opti"><?= $val['voucher_start_date'] ?>--<?= $val['voucher_end_date'] ?></td>
                    <td class="tl opti"><?= __('满') ?>&nbsp;<?= format_money($val['voucher_limit']) ?>&nbsp;<?= __('可用') ?></td>
                    <td><?= $val['voucher_state_label'] ?></td>
                    <td class="ncm-table-handle">
                        <?php if ($val['voucher_state_label'] == '未用') { ?>
                            <span>
                                <a class="btn-grapefruit" href="<?= Yf_Registry::get('url') ?>?ctl=Shop&met=index&id=<?= $val['voucher_shop_id'] ?>"><?= __('去使用') ?></a>
                            </span>
                        <?php } else { ?>
                            <span>
                                <a onclick="delVoucher('<?= $val['voucher_id'] ?>')" href="javascript:void(0)"><i class="iconfont icon-lajitong"></i>
                                    <?= __('删除') ?>
                                </a>
                            </span>
                        <?php } ?>
                    </td>
                </tr>
            <?php } ?>
        <?php } else { ?>
            <tr id="list_norecord">
                <td colspan="20" class="norecord">
                    <div class="no_account">
                        <img src="<?= $this->view->img ?>/ico_none.png" />
                        <p><?= __('暂无符合条件的数据记录') ?></p>
                    </div>
                </td>
            </tr>
        <?php } ?>
        
        </tbody>
    </table>
<?php if ($page_nav) { ?>
    <div style="clear:both"></div>
    <div class="page page_front"><?= $page_nav ?></div>
    <div style="clear:both"></div>
<?php } ?>
    </div>
    </div>
    </div>
    </div>
    <script>
        window.delVoucher = function (e) {
            $.dialog({
                title: '系统提示',
                content: '您确定要删除吗？',
                icon: 'confirm.gif',
                height: 100,
                width: 210,
                lock: true,
                drag: false,
                ok: function () {
                    $.post(SITE_URL + '?ctl=Buyer_Voucher&met=delVoucher&typ=json', {id: e}, function (data) {
                        if (data && 200 == data.status) {
                            Public.tips.success('删除成功！');
                            setTimeout(function () {
                                window.location.reload();
                            }, 1500)
                        } else {
                            Public.tips.error('删除失败！');
                            setTimeout(function () {
                                window.location.reload();
                            }, 1500)
                        }
                    })
                }
            })
        }
    </script>
<?php
    include $this->view->getTplPath() . '/' . 'buyer_footer.php';
?>