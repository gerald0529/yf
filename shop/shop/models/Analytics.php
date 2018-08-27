<?php

/**
 * Description of AnalyticsModel
 *
 * @author tech40@yuanfeng021.com  & tech35@yuanfeng021.com
 */
class Analytics {
    public  $key = null;
    public  $url = null;
    public  $app_id = null;
    
    public function __construct(){
        $this->key = Yf_Registry::get('analytics_api_key');
        $this->url = Yf_Registry::get('analytics_api_url');
        $this->app_id = Yf_Registry::get('analytics_app_id');
    }

    /**
     * 商家中心 --- 店铺概况
     * @param array $formvars
     * @return boolean
     */
    public function getGeneralInfo($formvars = array()){
        if(!$formvars){
            return false;
        }
        $formvars['app_id']    = $this->app_id;
        $init_rs = get_url_with_encrypt($this->key, sprintf('%s?ctl=Api_Shop_Getdata&met=getGeneralInfo&typ=json', $this->url), $formvars);
        return $init_rs;
    }
    
     /**
     * 商家中心 --- 商品详情
     * @param array $formvars
     * @return boolean
     */
    public function getGoodsAnalytics($formvars = array()){
        if(!$formvars){
            return false;
        }
        $formvars['app_id']    = $this->app_id;
        $init_rs = get_url_with_encrypt($this->key, sprintf('%s?ctl=Api_Shop_Getdata&met=getGoodsAnalytics&typ=json', $this->url), $formvars);
        return $init_rs;
    }
    
     /**
     * 商家中心 --- 热卖商品
     * @param array $formvars
     * @return boolean
     */
    public function getGoodsHot($formvars = array()){
        if(!$formvars){
            return false;
        }
        $formvars['app_id']    = $this->app_id;
        $init_rs = get_url_with_encrypt($this->key, sprintf('%s?ctl=Api_Shop_Getdata&met=getGoodsHot&typ=json', $this->url), $formvars);
        return $init_rs;
    }
    
     /**
     * 商家中心 --- 运营报告
     * @param array $formvars
     * @return boolean
     */
    public function getOperationArea($formvars = array()){
        if(!$formvars){
            return false;
        }
        $formvars['app_id']    = $this->app_id;
        $init_rs = get_url_with_encrypt($this->key, sprintf('%s?ctl=Api_Shop_Getdata&met=getOperationArea&typ=json', $this->url), $formvars);

        return $init_rs;
    }
    
    //获取商品详情 2017.3.14 hp
    public function getGoodsDetail($formvars = array())
    {
        if(!$formvars){
            return false;
        }
        $formvars['app_id']    = $this->app_id;
        $init_rs = get_url_with_encrypt($this->key, sprintf('%s?ctl=Api_Shop_Getdata&met=getGoodsDetail&typ=json', $this->url), $formvars);

        return $init_rs;
    }

    //获取商品详情 2017.3.15 hp
    public function goodsAnalysis($formvars = array())
    {
        if(!$formvars){
            return false;
        }
        $formvars['app_id']    = $this->app_id;
        $init_rs = get_url_with_encrypt($this->key, sprintf('%s?ctl=Api_Shop_Getdata&met=getGoodsAnalysis&typ=json', $this->url), $formvars);

        return $init_rs;
    }

    //获取订单地域详情 2017.3.16 hp
    public function getAreaData($formvars = array())
    {
        if(!$formvars){
            return false;
        }

        $formvars['app_id']    = $this->app_id;
        $init_rs = get_url_with_encrypt($this->key, sprintf('%s?ctl=Api_Shop_Getdata&met=getAreaData&typ=json', $this->url), $formvars);

        return $init_rs;
    }
}
