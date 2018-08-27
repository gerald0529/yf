<?php if (!defined('ROOT_PATH')) exit('No Permission');?>
<?php
include TPL_PATH . '/'  . 'header.php';
?>
<style>
#para-wrapper{font-size:14px; }
#para-wrapper .para-item{margin-bottom:30px;}
#para-wrapper .para-item h3{font-size:14px;font-weight:bold;margin-bottom:10px;}

.mod-form-rows .label-wrap { width:180px; }
.para-item .ui-input{width:220px;font-size:14px;}

.subject-para .ui-input{width:40px;}

.code-length .ui-spinbox-wrap{margin-right:0;}

.books-para input{margin-top:-3px;}

#currency{width: 68px;}
.ui-droplist-wrap .list-item {font-size:14px;}
</style>
</head>
<body>
<div class="wrapper">
  <div id="para-wrapper">
    <div class="para-item">
      <h3>录入信息:</h3>
      <ul class="mod-form-rows" id="establish-form">
        <li class="row-item">
          <div class="label-wrap">
            <label for="service_id" style="display: none"></label>
          </div>
          <div class="ctn-wrap">
            <input type="text" name="service_id" class="ui-input" id="service_id " hidden="true" />
          </div>
          <div class="label-wrap">
            <label for="company_name">公司名称：</label>
          </div>
          <div class="ctn-wrap">
            <input type="text" name="company_name" class="ui-input" id="company_name" />
          </div>
        </li>
        <li class="row-item">
          <div class="label-wrap">
            <label for="company_phone">公司电话：</label>
          </div>
          <div class="ctn-wrap">
            <input type="text" name="company_phone" class="ui-input" id="company_phone" />
          </div>
        </li>
        <li class="row-item">
          <div class="label-wrap">
            <label for="contacter">联系人：</label>
          </div>
          <div class="ctn-wrap">
            <input type="text" name="contacter" class="ui-input" id="contacter" />
          </div>
        </li>
        <li class="row-item">
          <div class="label-wrap">
            <label for="plantform_url">Mall URL：</label>
          </div>
          <div class="ctn-wrap">
            <input type="text" name="plantform_url" class="ui-input" id="plantform_url" value="http://119.90.133.156/mallbuilder-api/api/" />
          </div>
        </li>
        <li class="row-item">
          <div class="label-wrap">
            <label for="sign_time">签约时间：</label>
          </div>
          <div class="ctn-wrap">
            <input type="text" name="sign_time" class="ui-input" id="sign_time" />
          </div>
        </li>
        <li class="row-item">
          <div class="label-wrap">
            <label for="account_num">账号个数：</label>
          </div>
          <div class="ctn-wrap">
            <input type="text" name="account_num" class="ui-input" id="account_num" />
          </div>
        </li>
        <li class="row-item">
          <div class="label-wrap">
            <label for="user_name">用户帐号：</label>
          </div>
          <div class="ctn-wrap">
            <input type="text" name="user_name" class="ui-input" id="user_name" />
          </div>
        </li>
        <!-- <li class="row-item">
          <div class="label-wrap">
            <label for="upload_path">附件存放地址：</label>
          </div>
          <div class="ctn-wrap">
            <input type="text" name="upload_path" class="ui-input" id="upload_path" />
          </div>
        </li> -->
        <li class="row-item">
          <div class="label-wrap">
            <label for="business_agent">业务代表：</label>
          </div>
          <div class="ctn-wrap">
            <input type="text" name="business_agent" class="ui-input" id="business_agent" />
          </div>
        </li>
        <li class="row-item">
          <div class="label-wrap">
            <label for="price">费用：</label>
          </div>
          <div class="ctn-wrap">
            <input type="text" name="price" class="ui-input" id="price" />
          </div>
        </li>
        <li class="row-item">
          <div class="label-wrap">
            <label for="effective_date_start">开始有效时间：</label>
          </div>
          <div class="ctn-wrap">
            <input type="text" name="effective_date_start" class="ui-input" id="effective_date_start" />
          </div>
        </li>
        <li class="row-item">
          <div class="label-wrap">
            <label for="effective_date_end">结束有效时间：</label>
          </div>
          <div class="ctn-wrap">
            <input type="text" name="effective_date_end" class="ui-input" id="effective_date_end" />
          </div>
        </li>
      </ul>
    </div>

    <div class="para-item dn">
      <h3>功能参数</h3>
      <ul class="mod-form-rows" id="establish-form2">

      </ul>
    </div>

    <div class="btn-wrap"> <a id="save" class="ui-btn ui-btn-sp">保存</a> </div>
  </div>
</div>
<script src="./admin/static/default/js/controllers/purchase/purchase_information.js"></script>
<?php
include TPL_PATH . '/'  . 'footer.php';
?>