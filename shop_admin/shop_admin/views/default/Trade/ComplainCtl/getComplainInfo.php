<?php if (!defined('ROOT_PATH')) {exit('No Permission');}?>
<?php
include $this->view->getTplPath() . '/'  . 'header.php';
?>


<link rel="stylesheet" href="<?=$this->view->css_com?>/jquery/plugins/validator/jquery.validator.css">
<script type="text/javascript" src="<?=$this->view->js_com?>/plugins/validator/jquery.validator.js" charset="utf-8"></script>
<script type="text/javascript" src="<?=$this->view->js_com?>/plugins/validator/local/zh_CN.js" charset="utf-8"></script>
<link href="<?=$this->view->css?>/index.css" rel="stylesheet" type="text/css">
<link href="<?=$this->view->css?>/complain.css" rel="stylesheet" type="text/css">
</head>
<body>
<div class="wrapper page">
    <div class="fixed-bar">
        <div class="item-title">
            <div class="subject">
                <h3>投诉管理</h3>
                <h5>商城对商品交易投诉管理及仲裁</h5>
            </div>
        </div>
    </div>

    <div class="ncap-order-style">
        <div class="ncap-order-flow">
            <ol class="num5">
                <li id="state_new" class="current">
                    <h5>新投诉</h5>
                    <i class="fa fa-arrow-circle-right"></i>
                </li>
                <li id="state_appeal" <?=( ('appeal' == $data['complain']['state']) || ('talk' == $data['complain']['state']) || ('handle' == $data['complain']['state']) || ('finish' == $data['complain']['state']) ? 'class="current"' : '')?> >
                    <h5>待申诉</h5>
                    <i class="fa fa-arrow-circle-right"></i>
                </li>
                <li id="state_talk" <?=( ('talk' == $data['complain']['state'])|| ('handle' == $data['complain']['state']) || ('finish' == $data['complain']['state'])? 'class="current"' : '')?> >
                    <h5>对话中</h5>
                    <i class="fa fa-arrow-circle-right"></i>
                </li>
                <li id="state_handle" <?=( ('handle' == $data['complain']['state']) || ('finish' == $data['complain']['state'])? 'class="current"' : '')?>>
                    <h5>待仲裁</h5>
                    <i class="fa fa-arrow-circle-right"></i>
                </li>
                    <li id="state_finish" <?=( 'finish' == $data['complain']['state']? 'class="current"' : '')?>>
                    <h5>已完成</h5>
                </li>
            </ol>
        </div>

        <!-- 订单详情 -->
        <div class="ncap-order-details">
            <ul class="tabs-nav">
                <li class="current">
                    <a href="javascript:void(0);">订单详情</a>
                </li>
            </ul>

            <div class="tabs-panels">
                <div class="misc-info">
                    <dl>
                        <dt>店铺名称：</dt>
                        <dd>
                            <a target="_blank" href="<?= Yf_Registry::get('shop_api_url') ?>?ctl=Shop&met=index&typ=e&id=<?=($data['order_detail']['shop_id'])?>"> <?=($data['order_detail']['shop_name'])?> </a>
                        </dd>

                        <dt>订单状态：</dt><dd><?=($data['order_detail']['status'])?></dd>

                        <dt>订单号：</dt>
                        <dd>
                            <a href="<?= Yf_Registry::get('url') ?>?ctl=Trade_Order&met=getOrderInfo&order_id=<?=($data['order']['order_id'])?>"> <?=($data['order']['order_id'])?></a>
                        </dd>

                        <dt>下单时间：</dt><dd><?=($data['order']['order_create_time'])?> </dd>
                        <dt>订单总额：</dt><dd>￥<?=($data['order']['order_payment_amount'])?> </dd>
                    </dl>
                </div>
                <div class="goods-info">
                    <h4>投诉的商品</h4>
                    <table>
                        <thead>
                            <tr>
                                <th colspan="2">商品名称</th>
                                <th>数量</th>
                                <th>价格</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <a target="_blank" href="<?= Yf_Registry::get('shop_api_url') ?>?ctl=Goods_Goods&met=goods&gid=<?=($data['complain_goods']['goods_id'])?>" style="text-decoration:none;">
                                        <img width="50" src="<?=($data['complain_goods']['goods_image'])?>">
                                    </a>
                                </td>

                                <td>
                                    <p>
                                        <a target="_blank" href="<?= Yf_Registry::get('shop_api_url') ?>?ctl=Goods_Goods&met=goods&gid=<?=($data['complain_goods']['goods_id'])?>"><?=($data['complain_goods']['goods_name'])?></a>
                                    </p>
                                    <p></p>
                                </td>

                                <td><?=($data['complain_goods']['goods_num'])?></td>
                                <td>￥<?=($data['complain_goods']['goods_price'])?></td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="100" style="padding:1px;">
                                  &nbsp;&nbsp;&nbsp;投诉内容
                                <div class="complain-intro"><?=($data['complain']['complain_content'])?></div>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        <!-- 投诉信息 -->
        <div class="ncap-form-default">
            <div class="title">
                <h3>投诉信息</h3>
            </div>
            <dl class="row">
                <dt class="tit">投诉状态：</dt>
                <dd class="opt"><?=($data['complain']['complain_state_content'])?></dd>
            </dl>
            <dl class="row">
                <dt class="tit">投诉主题：</dt>
                <dd class="opt"><?=($data['complain']['complain_subject_content'])?></dd>
            </dl>
            <dl class="row">
                <dt class="tit">投诉人：</dt>
                <dd class="opt"><?=($data['complain']['user_account_accuser'])?></dd>
            </dl>
            <dl class="row">
                <dt class="tit">投诉证据：</dt>
                <dd class="opt"> 
                <?php if($data['complain']['complain_pic_content']){
                    foreach ($data['complain']['complain_pic_content'] as $key => $value) {?>
                     <img width="100" src="<?=($value)?>">
                <?php }}else{?>
                    暂无图片
                <?php }?> 
                </dd>
            </dl>
            <dl class="row">
                <dt class="tit">投诉时间：</dt>
                <dd class="opt"><?=($data['complain']['complain_datetime'])?></dd>
            </dl>
        </div>

        <!-- 申诉详情 （对话中，待仲裁，已关闭） -->
        <div class="ncap-form-default" <?=( ('talk' != $data['complain']['state']) && ('handle' != $data['complain']['state']) && ('finish' != $data['complain']['state']) ? 'id="hidden"' : '')?> >
            <div class="title">
                <h3>申诉详情</h3>
            </div>
            <dl class="row">
                <dt class="tit">被投诉店铺：</dt>
                <dd class="opt"><?=($data['complain']['user_account_accused'])?></dd>
            </dl>
            <dl class="row">
                <dt class="tit">申诉证据：</dt>
                <dd class="opt"> 
                <?php if($data['complain']['appeal_pic_content']){
                    foreach ($data['complain']['appeal_pic_content'] as $key => $value) {?>
                     <img width="100" src="<?=($value)?>">
                <?php }}else{?>
                    暂无图片
                <?php }?> 
                </dd>
            </dl>
            <dl class="row">
                <dt class="tit"> 申诉时间：</dt>
                <dd class="opt"><?=($data['complain']['appeal_datetime'])?></dd>
            </dl>
            <dl class="row">
                <dt class="tit">申诉内容：</dt>
                <dd class="opt"><?=($data['complain']['appeal_message'])?></dd>
            </dl>
        </div>

        <!-- 对话详情（对话中，待仲裁，已关闭-对话记录） -->
        <div class="ncap-form-default" <?=( ('talk' != $data['complain']['state']) && ('handle' != $data['complain']['state']) && ('finish' != $data['complain']['state']) ? 'id="hidden"' : '')?> >
            <div class="title">
                <h3>对话详情</h3>
            </div>
            <dl class="row">
                <dt class="tit">对话记录：</dt>
                <dd class="opt">
                    <div id="div_talk" class="div_talk">
                    </div>
                </dd>
            </dl>
            <dl cass="row" <?=( ('talk' != $data['complain']['state']) && ('handle' != $data['complain']['state']) ? 'id="hidden"' : '')?>>
                <dt class="tit">发布对话：</dt>
                <dd class="opt">
                    <textarea id="complain_talk" class="tarea"></textarea>
                    <div>
                        <a id="btn_refresh" class="ncap-btn" href="JavaScript:void(0);">刷新对话</a>
                        <a id="btn_publish" class="ncap-btn" href="JavaScript:void(0);">发布对话</a>
                    </div>
                </dd>
            </dl>
        </div>

        <!-- 投诉处理  （新投诉，待申诉，对话中，待仲裁） -->
        <div class="ncap-form-default" <?=( 'finish' == $data['complain']['state'] ? 'id="hidden"' : '')?>>
            <div class="title">
                <h3>投诉处理</h3>
            </div>
            <dl id="close_complain" class="row">
                <div class="bot">
                    <form id="verify_form" action="" method="post">
                        <input type="hidden" value="<?=$data['complain']['complain_id']?>" name="complain_id" id="complain_id">
                        <input type="hidden" value="<?=Perm::$row['user_id']?>" name="complain_handle_user_id" id="complain_handle_user_id">
                        <a id="verify_button" class="ncap-btn-big ncap-btn-blue" href="javascript:void(0)" <?=( 'new' != $data['complain']['state'] ? 'style="display:none;"' : '')?>>
                            <span>审核</span>
                        </a>
                        <a id="close_button" class="ncap-btn-big ncap-btn-blue" href="javascript:void(0)" style="margin-left: 5px;">
                            <span>关闭投诉</span>
                        </a>
                        <a id="new_return_button" class="ncap-btn-big ncap-btn-blue" href="JavaScript:void(0);" style="margin-left: 5px;">
                            <span>返回</span>
                        </a>
                    </form>
                </div>
            </dl>
            <form id="close_form" action="" method="post">
                <dl class="row complain_dialog" style="display: none;">
                    <dt class="tit">
                        处理意见
                        <input type="hidden" value="<?=$data['complain']['complain_id']?>" name="complain_id" id="complain_id">
                        <input type="hidden" value="<?=Perm::$row['user_id']?>" name="user_id_final_handle" id="user_id_final_handle">
                        <input type="hidden" value="<?=Perm::$row['user_account']?>" name="user_account_final_handle" id="user_account_final_handle">
                    </dt>
                    <dd class="opt">
                        <textarea id="final_handle_message" class="tarea" name="final_handle_message"></textarea>
                    </dd>
                </dl>
                <div class="bot complain_dialog" style="display: none;">
                    <a id="btn_handle_submit" class="ncap-btn-big ncap-btn-blue" href="javascript:void(0)">确认提交</a>
                    <a id="btn_close_cancel" class="ncap-btn-big ncap-btn-blue" href="javascript:void(0)">取消</a>
                </div>
            </form>
        </div>

        <!-- 处理详情（已关闭） -->
        <div class="ncap-form-default" <?=( 'finish' != $data['complain']['state'] ? 'id="hidden"' : '')?>>
            <input type="hidden" value="<?=$data['complain']['complain_id']?>" name="complain_id" id="complain_id">
            <input type="hidden" value="<?=Perm::$row['user_id']?>" name="complain_handle_user_id" id="complain_handle_user_id">
            <div class="title">
                <h3>处理详情</h3>
            </div>
            <dl class="row">
                <dt class="tit">处理意见：</dt>
                <dd class="opt"><?=($data['complain']['final_handle_message'])?></dd>
            </dl>
            <dl>
                <dt class="tit">处理时间：</dt>
                <dd class="opt"><?=($data['complain']['final_handle_datetime'])?></dd>
            </dl>
            <div class="bot">
                <a id="finish_return_button" class="ncap-btn-big ncap-btn-blue"  href="JavaScript:void(0);">返回</a>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
var complain_id = $('#complain_id').val();
    //屏蔽对话
    function forbit_talk(talk_id) {
        $.ajax({
            type:'POST',
                url:SITE_URL + '?ctl=Trade_Complain&met=forbitTalk&typ=json',
                cache:false,
                data:"talk_id="+talk_id+"&talk_admin="+$("#user_id_final_handle").val(),
                dataType:'json',
                error:function(){
                    parent.Public.tips({type:1,content: '对话屏蔽失败！'});
                    //alert("对话屏蔽失败");
                },
                    success:function(data){
                        if(data.msg == 'success') {
                            get_complain_talk();
                            parent.Public.tips({content: '对话屏蔽成功！'});
                            //alert("对话屏蔽成功");
                            get_complain_talk();
                        }
                        else {
                            parent.Public.tips({type:1,content: '对话屏蔽失败！'});
                            //alert("对话屏蔽失败");
                        }
                    }
        });
    }

    //刷新对话
    function get_complain_talk() {
        $("#div_talk").empty();
        $.ajax({
            type:'POST',
            url:SITE_URL + '?ctl=Trade_Complain&met=getComplainTalk&typ=json',
            cache:false,
            data:"complain_id="+complain_id,
            dataType:'json',
            success:function(data){
                console.info(data);
                console.info(Object.getOwnPropertyNames(data.data).length);
                if(Object.getOwnPropertyNames(data.data).length >= 1) {
                    for(var i = 0; i < Object.getOwnPropertyNames(data.data).length-1; i++)
                    {
                        if(data.data[i].talk_state)
                        {
                            var mask_state = "<a onclick='forbit_talk("+data.data[i].talk_id+")' href='#'>屏蔽</a>"
                        }else
                        {
                            var mask_state = '';
                        }

                        var link = "<p class='"+data.data[i].acc_type+"'>"+data.data[i].talk_datetime+data.data[i].member_type+'('+data.data[i].user_name+')说：'+data.data[i].talk_content+mask_state+"</p>";
                        $("#div_talk").append(link);
                        console.info(i);
                        console.info(data.data[i]);
                        console.info(data.data[i].talk_content);
                        console.info(link);
                    }
                }
                else {
                    $("#div_talk").append("<p class='admin'>"+"目前没有对话"+"</p>");
                }
            },
            error:function(){
                    $("#div_talk").append("<p class='admin'>"+"目前没有对话"+"</p>");
            }
        });
    }
</script>
<script type="text/javascript" src="<?=$this->view->js?>/controllers/trade/complain/progress.js" charset="utf-8"></script>
<?php
include $this->view->getTplPath() . '/' . 'footer.php';
?>