<?php if (!defined('ROOT_PATH')) {
    exit('No Permission');
}
    
    /**
     * Api接口
     *
     *
     * @category   Game
     * @package    User
     * @author     Xinze <xinze@live.cn>
     * @copyright  Copyright (c) 2015, 黄新泽
     * @version    1.0
     * @todo
     */
    class Connect_BindCtl extends Yf_AppController implements Connect_Interface
    {
        public $appid = null;
        public $appsecret = null;
        public $redirect_url = null;
        public $callback = null;
        
        /**
         * Constructor
         *
         * @param  string $ctl 控制器目录
         * @param  string $met 控制器方法
         * @param  string $typ 返回数据类型
         *
         * @access public
         */
        public function __construct(&$ctl, $met, $typ)
        {
            parent ::__construct($ctl, $met, $typ);
            $this -> type = request_string('type');
            $this -> op = request_string('op');
            $connect_config = Yf_Registry ::get('connect_rows');
            if (strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false) {
                $this -> appid = $connect_config['wechat']['app_id'];
                $this -> appsecret = $connect_config['wechat']['app_key'];
            } else {
                $this -> appid = $connect_config[$this -> type]['app_id'];
                $this -> appsecret = $connect_config[$this -> type]['app_key'];
            }
            if ($this -> type == 'alipay') {
                //AppId
                $this -> appid = $connect_config[$this -> type]['app_id'];
                //支付宝公钥
                $this -> alipayrsaPublicKey = $connect_config[$this -> type]['alipayrsaPublicKey'];
                //商户私钥
                $this -> rsaPrivateKey = $connect_config[$this -> type]['alipayrsaPrivateKey'];
            }
            $this -> callback = request_string('callback');
            if ($this -> type == 'qq') {
                $this -> redirect_url = Yf_Registry ::get('base_url') . '/login.php';
                $this -> bindtype = User_BindConnectModel::QQ;
            }
            if ($this -> type == 'weixin') {
                if (strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false) {
                    $this -> redirect_url = sprintf('%s?ctl=WeiXin&met=callback&from=%s&callback=%sctl=Index&type=%s&op=%s', Yf_Registry ::get('paycenter_api_url'), request_string('from'), urlencode(request_string('callback')), 'weixin', $this -> op);
                } else {
                    $this -> redirect_url = sprintf('%s?ctl=Connect_Bind&met=callback&from=%s&callback=%sctl=Index&type=%s&op=%s', Yf_Registry ::get('url'), request_string('from'), urlencode(request_string('callback')), 'weixin', $this -> op);
                }
                $this -> bindtype = User_BindConnectModel::WEIXIN;
            }
            if ($this -> type == 'weibo') {
                $this -> redirect_url = sprintf('%s?ctl=Connect_Bind&met=login&type=%s&op=%s', Yf_Registry ::get('url'), 'weibo', $this -> op);
                $this -> bindtype = User_BindConnectModel::SINA_WEIBO;
            }
            if ($this -> type == 'alipay') {
                $this -> redirect_url = sprintf('%s?ctl=Connect_Bind&met=callback&type=%s&callback=%s&op=%s', Yf_Registry ::get('url'), 'alipay', urlencode(request_string('callback')), $this -> op);
                $this -> bindtype = User_BindConnectModel::ALIPAY;
            }
        }
        
        public function select()
        {
            include $this -> view -> getView();
        }
        
        public function login()
        {
            $url = '';
            //1.QQ授权
            if ($this -> type == 'qq') {
                $redirect_url = $this -> redirect_url;
                $url = "https://graph.qq.com/oauth2.0/authorize?response_type=code&client_id=" . $this -> appid . "&redirect_uri=" . urlencode($redirect_url) . "&client_secret=" . $this -> appsecret . "&state=" . urlencode($this -> callback) . "&cope=get_user_info,get_info&callback=" . urlencode($this -> callback);
            }
            //2.微信授权
            if ($this -> type == 'weixin') {
                $redirect_url = urlencode($this -> redirect_url);
                if (strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false) {
                    $url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=$this->appid&redirect_uri=$redirect_url&response_type=code&scope=snsapi_userinfo&state=123&connect_redirect=1#wechat_redirect";
                } else {
                    $url = "https://open.weixin.qq.com/connect/qrconnect?appid=$this->appid&redirect_uri=$redirect_url&response_type=code&scope=snsapi_login&state=STATE#wechat_redirect";
                }
            }
            //3.微博授权
            if ($this -> type == 'weibo') {
                $redirect_url = $this -> redirect_url;
                if ($_GET['code']) {
                    $_REQUEST['callback'] = $_GET['state'];
                    $this -> callback();
                    exit;
                }
                $url = "https://api.weibo.com/oauth2/authorize?client_id=" . $this -> appid . "&redirect_uri=" . urlencode($redirect_url) . "&state=" . urlencode($this -> callback);
            }
            //4.支付宝
            if ($this -> type == 'alipay') {
                $url = 'https://openauth.alipay.com/oauth2/publicAppAuthorize.htm?app_id=' . $this -> appid . '&scope=auth_user&redirect_uri=' . urlencode($this -> redirect_url);
            }
            location_to($url);
        }
        
        /**
         * callback 回调函数
         *
         * @access public
         */
        public function callback()
        {
            $callbackurl = request_string('callback');
            // $callbackurl = https://ucenter.local.yuanfeng021.com/index.php?
            
            // 查询绑定类型  1:sinaweibo  2:qq   3:weixin  11:email  12:mobile
            $type = $this -> bindtype;
            
            $User_InfoModel = new User_InfoModel();
            $code = request_string('code', null);
            if (!$code) {
                $code = request_string('auth_code');
            }
            // $code = 011xmaL40ZwZ8K12I8M40rf7L40xmaLG;
            
            $redirect_url = $this -> redirect_url;
            
            $openid = '';
            $login_flag = false;
            
            //判断当前登录账户
            if (Perm ::checkUserPerm()) {
                $user_id = Perm ::$userId;
            } else {
                $user_id = 0;
            }
            
            if ($code) {
                switch ($this -> type) {
                    case 'qq':
                        $data = $this -> getQQuser($code);
                        break;
                    case 'weixin':
                        $data = array();
                        if (request_string('from') == 'paycenter') {
                            $data['status'] = request_int('status');
                            $data['bind_id'] = request_string('bind_id');
                            $data['access_token'] = request_string('access_token');
                            $data['openid'] = request_string('openid');
                            $data['user_info']['bind_avator'] = request_string('bind_avator');
                            $data['user_info']['bind_nickname'] = request_string('bind_nickname');
                            $data['user_info']['bind_gender'] = request_int('bind_gender');
                        } else {
                            $data = $this -> getWXuser($code);
                        }
                        break;
                    case "weibo":
                        $data = $this -> getWBuser($code);
                        break;
                    case "alipay":
                        $data = $this -> getAliPayUser($code);
                        break;
                    default:
                        # code...
                        break;
                }
                
                if ($data['status'] == 200) {
                    $connect_rows = array();
                    $User_BindConnectModel = new User_BindConnectModel();
                    $bind_id = $data['bind_id'];
                    $connect_rows = $User_BindConnectModel -> getBindConnect($bind_id);
                    if ($connect_rows) {
                        $connect_row = array_pop($connect_rows);
                    }
                    //已经绑定,并且用户正确
                    if (isset($connect_row['user_id']) && $connect_row['user_id']) {
                        //验证通过, 登录成功.，跳转到 用户中心页面
                        if ($user_id && $user_id == $connect_row['user_id']) {
                            $url = sprintf('%s?ctl=User&met=getUserInfo', Yf_Registry ::get('url'));
                            location_to($url);
                            // echo '非法请求,已经登录用户不应该访问到此页面';
                            // die();
                        }
                        $login_flag = true;
                    } else {
                        // 下面可以需要封装
                        $bind_rows = $User_BindConnectModel -> getBindConnect($bind_id);
                        if ($bind_rows && $bind_row = array_pop($bind_rows)) {
                            if ($bind_row['user_id']) {
                                //该账号已经绑定
                                echo '非法请求,该账号已经绑定';
                                die();
                            }
                            
                            if ($user_id != 0) {
                                $bind_id_row = $User_BindConnectModel -> getBindConnectByuserid($user_id, $type);
                                if ($bind_id_row) {
                                    echo '非法请求,该账号已经绑定';
                                    die();
                                }
                            }
                            
                            $data_row = array();
                            $data_row['user_id'] = $user_id;
                            $data_row['bind_token'] = $data['access_token'];
                            $connect_flag = true;
                            $User_BindConnectModel -> editBindConnect($bind_id, $data_row);
                        } else {
                            if ($user_id != 0) {
                                $bind_id_row = $User_BindConnectModel -> getBindConnectByuserid($user_id, $type);
                                if ($bind_id_row) {
                                    echo '非法请求,该账号已经绑定';
                                    die();
                                }
                            }
                            $user_info = $data['user_info'];
                            //插入绑定表
                            $bind_array = array();
                            //互联登录中的用户名为8位（数字，大小写字母）的字符
                            $bind_nickname = $User_InfoModel -> random_str(8);
                            $bind_array = array(
                                'bind_id' => $bind_id,
                                'bind_type' => $this -> bindtype,
                                'user_id' => $user_id,
                                //'bind_nickname'=>$user_info['bind_nickname'],
                                'bind_nickname' => $bind_nickname,
                                'bind_avator' => $user_info['bind_avator'],
                                'bind_gender' => $user_info['bind_gender'],
                                'bind_openid' => $data['openid'],
                                'bind_token' => $data['access_token'],
                            );
                            $connect_flag = $User_BindConnectModel -> addBindConnect($bind_array);
                        }
                        //取得open id, 需要封装
                        if ($connect_flag) {
                            //选择,登录绑定还是新创建账号 $user_id == 0
                            if (!Perm ::checkUserPerm()) {
                                if ($this -> op) {
                                    //扫码进入的用户不需要绑定手机就注册用户
                                    $url = sprintf('%s?ctl=Login&met=bindRegist&token=%s&op=%s&typ=json&callback=%s', Yf_Registry ::get('url'), $data['access_token'], $this -> op, urlencode(request_string('callback')));
                                    location_to($url);
                                } else {
                                    $url = sprintf('%s?ctl=Login&met=select&t=%s&type=%s&from=%s&callback=%s', Yf_Registry ::get('url'), $data['access_token'], $type, request_string('fr'), urlencode(request_string('callback') ? : $_GET['callbak']));
                                    location_to($url);
                                }
                            } else {
                                $login_flag = true;
                            }
                        } else {
                            //
                        }
                    }
                }
                
                if ($login_flag) {
                    //验证通过, 登录成功.
                    if ($user_id && $user_id == $connect_row['user_id']) {
                        echo json_encode(['test' => 1]);
                        exit;
                        echo '非法请求,已经登录用户不应该访问到此页面';
                        die();
                    } else {
                        $result = $User_InfoModel -> userlogin($connect_row['user_id']);
                        if ($result) {
                            $msg = 'success';
                            $status = 200;
                        } else {
                            $msg = '登录失败';
                            $status = 250;
                            $this -> data -> addBody(-140, array(), $msg, $status);
                        }
                    }
                    $login_flag = true;
                    if (request_string('callback')) {
                        $us = $result['user_id'];
                        $ks = $result['k'];
                        $url = sprintf('%s&us=%s&ks=%s', request_string('callback'), $us, $ks);
                        location_to($url);
                    } else {
                        $url = sprintf('%s?ctl=Login', Yf_Registry ::get('url'));
                        location_to($url);
                    }
                    echo '登录系统';
                    die();
                } else {
                    //失败
                }
            } else {
                
                $this -> data -> setError('code 获取失败');
                
            }
        }
        
        /**
         * getQQuser qq互联登录 - 获取用户信息
         *
         * @access public
         */
        public function getQQuser($code)
        {
            $data = array();
            $token_url = 'https://graph.qq.com/oauth2.0/token?grant_type=authorization_code&client_id=' . $this -> appid . '&client_secret=' . $this -> appsecret . '&code=' . $code . '&redirect_uri=' . urlencode($this -> redirect_url);
            $curl = curl_init($token_url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_FAILONERROR, false);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            $response = curl_exec($curl);
            curl_close($curl);
            $error = strpos($response, 'error');
            if ($error) {
                $error_info = preg_match_all("|{(.*)}|U", $response, $out, PREG_PATTERN_ORDER);
                $error_info = json_decode($out[0][0]);
                $error_id = $error_info -> client_id;
                $error_des = $error_info -> error_description;
                $data['status'] = 250;
                $this -> data -> addBody($error_des);
                die();
            } else {
                $access_token_row = explode('&', $response);
                //取出token
                $access_token = substr($access_token_row[0], strpos($access_token_row[0], "=") + 1);
                //获取用户unionid
                $user_openid_url = 'https://graph.qq.com/oauth2.0/me?' . $access_token_row[0] . '&unionid=1';
                $curl = curl_init($user_openid_url);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($curl, CURLOPT_FAILONERROR, false);
                curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
                $user_unionid = curl_exec($curl);
                if ($user_unionid) {
                    $user_unionid_info = preg_match_all("|{(.*)}|U", $user_unionid, $out, PREG_PATTERN_ORDER);
                    $user_unionid_info_row = json_decode($out[0][0]);
                    $unionid = $user_unionid_info_row -> unionid;
                }
                //获取用户openid
                $user_openid_url = 'https://graph.qq.com/oauth2.0/me?' . $access_token_row[0];
                $curl = curl_init($user_openid_url);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($curl, CURLOPT_FAILONERROR, false);
                curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
                $user_openid = curl_exec($curl);
                curl_close($curl);
                $user_openid_info_row = array();
                $client_id = "";
                if ($user_openid) {
                    $user_openid_info = preg_match_all("|{(.*)}|U", $user_openid, $out, PREG_PATTERN_ORDER);
                    $user_openid_info_row = json_decode($out[0][0]);
                    $client_id = $user_openid_info_row -> client_id;
                    $openid = $user_openid_info_row -> openid;
                }
                if ($openid) {
                    //获取用户信息
                    $user_info_url = 'https://graph.qq.com/user/get_user_info?' . $access_token_row[0] . '&oauth_consumer_key=' . $client_id . '&openid=' . $openid;
                    $curl = curl_init($user_info_url);
                    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($curl, CURLOPT_FAILONERROR, false);
                    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
                    $user_info = curl_exec($curl);
                    curl_close($curl);
                    $user_info = json_decode($user_info);
                    if ($user_info -> gender == '女') {
                        $user_gender = 2;
                    } else {
                        $user_gender = 1;
                    }
                    $data['status'] = 200;
                    $data['bind_id'] = sprintf('%s_%s', 'qq', $unionid);
                    $data['access_token'] = $access_token;
                    $data['openid'] = $openid;
                    $data['user_info']['bind_avator'] = $user_info -> figureurl_qq_2;
                    $data['user_info']['bind_nickname'] = $user_info -> nickname;
                    $data['user_info']['bind_gender'] = $user_gender;
                }
            }
            return $data;
        }
        
        /**
         * getWXuser 微信互联登录 - 获取用户信息
         *
         * @access public
         */
        public function getWXuser($code)
        {
            $data = array();
            $token_url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid=' . $this -> appid . '&secret=' . $this -> appsecret . '&code=' . $code . '&grant_type=authorization_code';
            $access_token_row = json_decode(file_get_contents($token_url), true);
            if (!$access_token_row || !empty($access_token_row['errcode'])) {
                if ($access_token_row['errcode'] == 40163) {
                    $re_url = '';
                    $re_url = Yf_Registry ::get('re_url');
                    $from = '';
                    $wx_url = sprintf('%s?ctl=Connect_Bind&met=login&callback=%s&from=%s&type=%s', Yf_Registry ::get('url'), urlencode($re_url), $from, 'weixin');
                    header('Location:' . $wx_url);
                } else {
                    throw new Yf_ProtocalException($access_token_row['errmsg']);
                    $data['status'] = 250;
                }
            } else {
                $user_info_url = 'https://api.weixin.qq.com/sns/userinfo?access_token=' . $access_token_row['access_token'] . '&openid=' . $access_token_row['openid'] . '&lang=zh_CN';
                $user_info_row = json_decode(@file_get_contents($user_info_url), true);
                $data['status'] = 200;
                $data['bind_id'] = sprintf('%s_%s', 'weixin', $user_info_row['unionid']);
                $data['access_token'] = $access_token_row['access_token'];
                $data['openid'] = $user_info_row['openid'];
                $data['user_info']['bind_avator'] = $user_info_row['headimgurl'];
                $data['user_info']['bind_nickname'] = preg_replace('/\xEE[\x80-\xBF][\x80-\xBF]|\xEF[\x81-\x83][\x80-\xBF]/', '', $user_info_row['nickname']);
                $data['user_info']['bind_gender'] = $user_info_row['sex'];
            }
            return $data;
        }
        
        /**
         * getWBuser 微博互联登录 - 获取用户信息
         *
         * @access public
         */
        public function getWBuser($code)
        {
            $data = array();
            $token_url = 'https://api.weibo.com/oauth2/access_token?grant_type=authorization_code&client_id=' . $this -> appid . '&client_secret=' . $this -> appsecret . '&code=' . $code . '&redirect_uri=' . urlencode($this -> redirect_url);
            $curl = curl_init($token_url);
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_FAILONERROR, false);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            $response = curl_exec($curl);
            curl_close($curl);
            $error = strpos($response, 'error');
            if ($error) {
                $error_info = json_decode($response);
                $error_code = $error_info -> error_code;
                $error_description = $error_info -> error_description;
                $data['status'] = 250;
                $this -> data -> addBody($error_description);
                die;
            } else {
                $token = json_decode($response);
                $access_token = $token -> access_token;
                $expires_in = $token -> expires_in;
                $remind_in = $token -> remind_in;
                $uid = $token -> uid;
                //获取用户信息
                $user_openid_url = 'https://api.weibo.com/2/users/show.json?access_token=' . $access_token . '&uid=' . $uid;
                $curl = curl_init($user_openid_url);
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET");
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($curl, CURLOPT_FAILONERROR, false);
                curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
                $user_info = curl_exec($curl);
                curl_close($curl);
                $user_info = json_decode($user_info);
                if ($user_info -> gender == 'f') {
                    $user_gender = 2;
                } else {
                    $user_gender = 1;
                }
                $data['status'] = 200;
                $data['bind_id'] = sprintf('%s_%s', 'wb', $access_token);
                $data['access_token'] = $access_token;
                $data['openid'] = $access_token;
                $data['user_info']['bind_avator'] = $user_info -> avatar_large;
                $data['user_info']['bind_nickname'] = $user_info -> figureurl_qq_2;
                $data['user_info']['bind_gender'] = $user_gender;
            }
            return $data;
        }
        
        public function getAliPayUser($auth_code)
        {
            include(APP_PATH . '/Alipay/aop/AopClient.php');
            include(APP_PATH . '/Alipay/aop/request/AlipaySystemOauthTokenRequest.php');
            include(APP_PATH . '/Alipay/aop/request/AlipayUserInfoShareRequest.php');
            $aop = new AopClient();
            $aop -> gatewayUrl = 'https://openapi.alipay.com/gateway.do';
            $aop -> appId = $this -> appid;
            $aop -> rsaPrivateKey = $this -> rsaPrivateKey;
            $aop -> alipayrsaPublicKey = $this -> alipayrsaPublicKey;
            $aop -> apiVersion = '1.0';
            $aop -> signType = 'RSA2';
            $aop -> postCharset = 'UTF-8';
            $aop -> format = 'json';
            //根据返回的auth_code换取access_token
            $request = new AlipaySystemOauthTokenRequest ();
            $request -> setGrantType("authorization_code");
            $request -> setCode($auth_code);
            //$request->setRefreshToken("201208134b203fe6c11548bcabd8da5bb087a83b");
            $result = $aop -> execute($request);
            $access_token = $result -> alipay_system_oauth_token_response -> access_token;
            $request = new AlipayUserInfoShareRequest ();
            $user_info = $aop -> execute($request, $access_token);
            $responseNode = str_replace(".", "_", $request -> getApiMethodName()) . "_response";
            $resultCode = $user_info -> $responseNode -> code;
            if (!empty($resultCode) && $resultCode == 10000) {
                if ($user_info -> alipay_user_info_share_response -> gender == 'f') {
                    $user_gender = 2;
                } else {
                    $user_gender = 1;
                }
                //user_id的简称，用户身份标示。用于表示支付宝用户的唯一标示。
                $data = array();
                $data['status'] = 200;
                $data['bind_id'] = sprintf('%s_%s', 'alipay', $user_info -> alipay_user_info_share_response -> user_id);
                $data['access_token'] = $access_token;
                $data['openid'] = $user_info -> alipay_user_info_share_response -> user_id;
                $data['user_info']['bind_avator'] = $user_info -> alipay_user_info_share_response -> avatar;
                $data['user_info']['bind_nickname'] = $user_info -> alipay_user_info_share_response -> nick_name;
                $data['user_info']['bind_gender'] = $user_gender;
            } else {
                $error_description = $user_info -> $responseNode -> sub_msg;
                $data['status'] = 250;
                $this -> data -> addBody($error_description);
                die;
            }
            return $data;
        }
    }

?>
