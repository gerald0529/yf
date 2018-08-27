<?php
/**
 * Created by PhpStorm.
 * User: rd07
 * Date: 2017/4/17
 * Time: 9:28
 */
class Api_UcenterCtl extends Yf_AppController
{
    /**
     * /?ctl=Api_Ucenter&met=getWeixinConfig&typ=json
     *
     * return
     *
     *
     * {
    cmd_id: -140,
    status: 200,
    msg: "success",
    data: {
    city: "",
    country: "",
    headimgurl: "",
    language: "",
    nickname: "",
    privilege: "",
    openid : "",
    province: "",
    sex: 0,
    unionid: "",
    app_id: 104,
    rtime: 1492585436,
    token: "25e388878c6d32140c4cfa964393142f"
    }
    }
     *
     */
    public function getWeixinConfig()
    {
        $data['city'] = request_string('city');
        $data['country'] = request_string('country');
        $data['headimgurl'] = request_string('headimgurl');
        $data['language'] = request_string('language');
        $data['nickname'] = request_string('nickname');
        $data['privilege'] = request_string('privilege');
        $data['openid'] = request_string('openid');
        $data['province'] = request_string('province');
        $data['sex'] = request_int('sex');
        $data['unionid'] = request_string('unionid');
        $data['app_id'] = Yf_Registry::get('ucenter_app_id');
        $key = Yf_Registry::get('ucenter_api_key');
        $data['rtime'] = get_time();
        $hash_row = $data;
        array_multiksort($hash_row, SORT_STRING);
        $hash_row['key'] = $key;
        $tmp_str = http_build_query($hash_row);
        $data["token"] = md5($tmp_str);

        $this->data->addBody(-140, $data, $tmp_str);
        if ($jsonp_callback = request_string('jsonp_callback'))
        {
            exit($jsonp_callback . '(' . json_encode($this->data->getDataRows()) . ')');
        }
    }

    /**
     * /?ctl=Api_Ucenter&met=getQqConfig&typ=json
     *
     *
     * return
     *
     *
     * {
    cmd_id: -140,
    status: 200,
    msg: "success",
    data: {
    is_lost: 0,
    figureurl: "",
    vip: "",
    is_yellow_year_vip: "",
    ret: 0,
    is_yellow_vip: "",
    figureurl_qq_1: "",
    province: "",
    yellow_vip_level: "",
    level: "",
    figureurl_1: "",
    city: "",
    figureurl_2: "",
    nickname: "",
    msg: "",
    gender: 2,
    figureurl_qq_2: "",
    app_id: 104,
    rtime: 1492587336,
    token: "e3136389b4587c2efd84556586a7025e"
    }
    }
     */
    public function getQqConfig()
    {
        $data['is_lost'] = request_int('is_lost');
        $data['figureurl'] = request_string('figureurl');
        $data['vip'] = request_string('vip');
        $data['is_yellow_year_vip'] = request_string('is_yellow_year_vip');
        $data['ret'] = request_int('ret');
        $data['is_yellow_vip'] = request_string('is_yellow_vip');
        $data['figureurl_qq_1'] = request_string('figureurl_qq_1');
        $data['province'] = request_string('province');
        $data['yellow_vip_level'] = request_string('yellow_vip_level');
        $data['level'] = request_string('level');
        $data['figureurl_1'] = request_string('figureurl_1');
        $data['city'] = request_string('city');
        $data['figureurl_2'] = request_string('figureurl_2');
        $data['nickname'] = request_string('nickname');
        $data['msg'] = request_string('msg');
        $data['gender'] = $this->sextonum(request_string('gender'));
        $data['figureurl_qq_2'] = request_string('figureurl_qq_2');
        $data['app_id'] = Yf_Registry::get('ucenter_app_id');
        $key = Yf_Registry::get('ucenter_api_key');
        $data['rtime'] = get_time();
        $hash_row = $data;
        array_multiksort($hash_row, SORT_STRING);
        $hash_row['key'] = $key;
        $tmp_str = http_build_query($hash_row);
        $data["token"] = md5($tmp_str);

        $this->data->addBody(-140, $data);
        if ($jsonp_callback = request_string('jsonp_callback'))
        {
            exit($jsonp_callback . '(' . json_encode($this->data->getDataRows()) . ')');
        }
    }

    //性别汉字转数字
    function sextonum($sex)
    {
        if($sex === '男') {
            $sex = 1;
        }
        elseif($sex === '女'){
            $sex = 0;
        }
        else{
            $sex = 2;
        }
        return $sex;
    }

}