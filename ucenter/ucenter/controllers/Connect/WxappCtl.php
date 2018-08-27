<?php
if (!defined('ROOT_PATH'))
{
    exit('No Permission');
}

/**
 * @author
 */
class Connect_WxappCtl extends Yf_AppController
{

	/**
	 * Constructor
	 *
	 * @param  string $ctl 控制器目录
	 * @param  string $met 控制器方法
	 * @param  string $typ 返回数据类型
	 * @access public
	 */
	public function __construct(&$ctl, $met, $typ)
	{
		parent::__construct($ctl, $met, $typ);

		$this->userBindConnectModel  = new User_BindConnectModel();

	}
    //检测当前用户是否存在
    public function wx_app(){
        $openid = request_string('openid');
        $user_info = $_GET['userInfo'];
        $bind_id     = sprintf('%s_%s', 'weixin', $openid);
        $info = json_decode($user_info);

        //根据openid查找绑定表中的数据
        $connect_rows = $this->userBindConnectModel->getBindConnect($bind_id);
        $bind_rows     = $connect_rows;

        if ($connect_rows)
        {
            $connect_row = array_pop($connect_rows);
        }
        if (isset($connect_row['user_id']) && $connect_row['user_id'])
        {
            $login_flag = true;

        }else{

            if($bind_rows  && $bind_row = array_pop($bind_rows)){
                if ($bind_row['user_id'])
                {
                    //该账号已经绑定
                    echo '非法请求,该账号已经绑定';
                    die();
                }
                $data_row                      = array();
                $data_row['user_id']           = 0;
                $data_row['bind_token'] = $_GET['token'];

                $connect_flag = $this->userBindConnectModel->editBindConnect($bind_id, $data_row);
            }else{
                //排除所有与非法情况后，在绑定表中添加绑定信息
                $User_BindConnectModel = new User_BindConnectModel();
                $data = array();

                $data['bind_id']           = $bind_id;
                $data['bind_type']         = $User_BindConnectModel::WEIXIN;
                $data['user_id']           = 0;
                $data['bind_nickname']     = $info->nickName; // 名称
                $data['bind_avator']         = $info->avatarUrl; //
                $data['bind_gender']       = $info->gender; // 性别 1:男  2:女
                $data['bind_openid']       = $openid; // 访问
                $data['bind_token'] = $_GET['token'];

                $connect_flag = $User_BindConnectModel->addBindConnect($data);
            }
        }

        if ($connect_flag)
        {
            echo $bind_id;die;
        }
        //用户生成成功，并且与微信绑定成功后用户登录
        if ($login_flag)
        {

            $User_InfoModel  = new User_InfoModel();

            $user_info_row   = $User_InfoModel->getInfo($connect_row['user_id']);

            $user_info_row = array_values($user_info_row);
            $user_info_row = $user_info_row[0];
            $session_id = $user_info_row['session_id'];

            $arr_field               = array();
            $arr_field['session_id'] = $session_id;

            if ($user_info_row)
            {
                $arr_body           = $user_info_row;
                $arr_body['result'] = 1;

                $data            = array();
                $data['user_id'] = $user_info_row['user_id'];

                $encrypt_str = Perm::encryptUserInfo($data, $session_id);

                $arr_body['k'] = $encrypt_str;
                $arr_body['u'] = $data['user_id'];
                $this->data->addBody(100, $arr_body);

            }
            else
            {
                $this->data->setError('登录失败');
            }

        }

    }

    /*获取用户openid和session_key*/
    public function getopenid(){
        $appid = request_string('appid');
        $secret = request_string('secret');
        $js_code = request_string('code');

        $url = "https://api.weixin.qq.com/sns/jscode2session?appid=".$appid."&secret=".$secret."&js_code=".$js_code."&grant_type=authorization_code";

        $ch  = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);

        $data = curl_exec($ch);

        curl_close($ch);

        echo $data;exit;

    }


    /*获取用户openid和session_key*/
    public function getUnionid(){
        $appid = request_string('appid');
        $sessionKey = request_string('session_key');
        $encryptedData = request_string('encryptedData');
        $iv = request_string('iv');


        $errCode = $this->decryptData($appid,$sessionKey,$encryptedData, $iv, $data );

        if ($errCode == 0) {
            print($data . "\n");
        } else {
            print($errCode . "\n");
        }

    }



    /**
     * 检验数据的真实性，并且获取解密后的明文.
     * @param $encryptedData string 加密的用户数据
     * @param $iv string 与用户数据一同返回的初始向量
     * @param $data string 解密后的原文
     *
     * @return int 成功0，失败返回对应的错误码
     */
    public function decryptData($appid,$sessionKey,$encryptedData, $iv, &$data )
    {
        if (strlen($sessionKey) != 24) {
            return -41001;
        }
        $aesKey=base64_decode($sessionKey);


        if (strlen($iv) != 24) {
            return -41002;
        }
        $aesIV=base64_decode($iv);

        $aesCipher=base64_decode($encryptedData);

        $result=openssl_decrypt( $aesCipher, "AES-128-CBC", $aesKey, 1, $aesIV);

        $dataObj=json_decode( $result );
        if( $dataObj  == NULL )
        {
            return -41003;
        }
        if( $dataObj->watermark->appid != $appid )
        {
            return -41003;
        }
        $data = $result;
        return 0;
    }


}

?>