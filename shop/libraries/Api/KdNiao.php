<?php

/**
 * 快递鸟API
 *
 *
 * @category   Framework
 * @package    API
 * @author     Xinze <xinze@live.cn>
 * @copyright  Copyright (c) 2016, 黄新泽
 * @version    1.0
 * @todo
 */

/*

$e_business_id = 1256046;
$app_key = '0011e81b-2ae7-4fe1-a775-090544e1ded7';

$api = new Api_KdNiao($e_business_id, $app_key);

$request_rows =
	array (
		'OrderCode' => '012657018199',
		'ShipperCode' => 'YTO',
		'PayType' => 1,
		'MonthCode' => '7553045845',
		'ExpType' => 1,
		'Cost' => 1,
		'OtherCost' => 1,
		'Sender' =>
			array (
				'Company' => 'LV',
				'Name' => 'Taylor',
				'Mobile' => '15018442396',
				'ProvinceName' => '上海',
				'CityName' => '上海',
				'ExpAreaName' => '青浦区',
				'Address' => '明珠路',
			),
		'Receiver' =>
			array (
				'Company' => 'GCCUI',
				'Name' => 'Yann',
				'Mobile' => '15018442396',
				'ProvinceName' => '北京',
				'CityName' => '北京',
				'ExpAreaName' => '朝阳区',
				'Address' => '三里屯街道',
			),
		'Commodity' =>
			array (
				0 =>
					array (
						'GoodsName' => '鞋子',
						'Goodsquantity' => 1,
						'GoodsWeight' => 1,
					),
			),
		'AddService' =>
			array (
				0 =>
					array (
						'Name' => 'COD',
						'Value' => '1020',
					),
			),
		'Weight' => 1,
		'Quantity' => 1,
		'Volume' => 0,
		'Remark' => '小心轻放',
	);

echo $api->createOrderOnlineByJson($request_rows);




*/

class Api_KdNiao
{
	private $eBusinessId; //电商ID
	private $appKey; //电商加密私钥，快递鸟提供，注意保管，不要泄漏
	private $reqURL; //请求url

	/**
	 * 构造函数
	 *
	 * @access    private
	 */
//	 public function __construct($e_business_id='1310531', $app_key='8364c7b3-3ebf-4ae3-a8e5-f2735d875614', $req_url = 'http://testapi.kdniao.cc:8081/api/EOrderService')
    public function __construct($e_business_id='1256046', $app_key='0011e81b-2ae7-4fe1-a775-090544e1ded7', $req_url = 'http://120.25.97.33:8081/api/apiservice')
	{
		$this->eBusinessId = $e_business_id;
		$this->appKey      = $app_key;
		$this->reqURL      = $req_url;
	}

	/**
     * Json方式 调用电子面单接口
    */
    public function submitEOrder($eorder){
        $requestData = json_encode($eorder, JSON_UNESCAPED_UNICODE);
//        echo '<pre>';
//        print_r($requestData);
        $datas = array(
            'EBusinessID' => $this->eBusinessId,
            'RequestType' => '1007',
            'RequestData' => urlencode($requestData) ,
            'DataType' => '2',
        );
        $datas['DataSign'] = $this->encrypt($requestData, $this->appKey);
//        echo '<pre>';
//        print_r($datas);
        $result = $this->sendPost($this->reqURL, $datas);
//        echo '<pre>';
////        print_r($this->reqURL);
//        var_dump($this->appKey);
//        var_dump($this->eBusinessId);
//        echo '<hr>';
//        print_r($result);
//        die;
        //根据公司业务处理返回的信息......

        return $result;
    }
	
	/**
	 * 电商Sign签名生成
	 * @param data 内容
	 * @param appkey Appkey
	 * @return DataSign签名
	 */
	public function encrypt($data, $appkey)
	{
		return urlencode(base64_encode(md5($data . $appkey)));
	}

	/**
	 * Json方式 在线下单
	 *
	 * @param array $request_data 请求的数据
	 *
	 * @return
	 *
	 * @access public
	 */
	public function createOrderOnlineByJson($request_data = array())
	{
		$request_data = json_encode($request_data, JSON_UNESCAPED_UNICODE);

		$datas             = array(
			'EBusinessID' => $this->eBusinessId,
			'RequestType' => '1001',
			'RequestData' => urlencode($request_data),
			'DataType' => '2',
		);

		$datas['DataSign'] = $this->encrypt($request_data, $this->appKey);
		$result            = $this->sendPost($this->reqURL, $datas);

		//根据公司业务处理返回的信息......

		return $result;
	}

	/**
	 * XML方式 在线下单
	 *
	 * @param array $request_data 请求的数据
	 */
	public function createOrderOnlineByXml($request_data = '')
	{
		$request_data = "<?xml version=\"1.0\" encoding=\"utf-8\" ?>".
			"<Content>".
			"<LogisticsWeight>1.5</LogisticsWeight>".
			"<OrderCode>test_123456</OrderCode>".
			"<LogisticsVol>0.5</LogisticsVol>".
			"<HQPOrderDesc>测试在线下单接口 20150510</HQPOrderDesc>".
			"<HQPPayType>1</HQPPayType>".
			"<IsNeedPay>1</IsNeedPay>".
			"<Payment>1000</Payment>".
			"<OrderType>1</OrderType>".
			"<StartDate>2015-05-10 19:36:50</StartDate>".
			"<EndDate>2015-05-11 19:36:50</EndDate>".
			"<ShipperCode>LB</ShipperCode>".
			"<LogisticCode></LogisticCode>".
			"<ToName>张三</ToName>".
			"<ToAddressArea>深圳市南山区南新路2055号</ToAddressArea>".
			"<ToTel></ToTel>".
			"<ToMobile>13800000000</ToMobile>".
			"<ToPostCode></ToPostCode>".
			"<ToProvinceID>广东省</ToProvinceID>".
			"<ToCityID>深圳市</ToCityID>".
			"<ToExpAreaID>南山区</ToExpAreaID>".
			"<FromCompany>快递鸟科技</FromCompany>".
			"<FromName>李四</FromName>".
			"<FromAddressArea>深圳市福田区华强北路211号</FromAddressArea>".
			"<FromTel></FromTel>".
			"<FromMobile>13888888888</FromMobile>".
			"<FromPostCode></FromPostCode>".
			"<FromProvinceID>广东省</FromProvinceID>".
			"<FromCityID>深圳市</FromCityID>".
			"<FromExpAreaID>福田区</FromExpAreaID>".
			"<Cost>12</Cost>".
			"<OtherCost>1</OtherCost>".
			"<Commoditys>".
			"<Commodity>".
			"<GoodsName>惠普显示器</GoodsName>".
			"<GoodsCode>ABCD_123456789</GoodsCode>".
			"<Goodsquantity>2</Goodsquantity>".
			"<GoodsPrice>850</GoodsPrice>".
			"</Commodity>".
			"<Commodity>".
			"<GoodsName>神州笔记本</GoodsName>".
			"<GoodsCode>QWERT_456456</GoodsCode>".
			"<Goodsquantity>2</Goodsquantity>".
			"<GoodsPrice>4200</GoodsPrice>".
			"</Commodity>".
			"</Commoditys>".
			"</Content>";

		$datas             = array(
			'EBusinessID' => $this->eBusinessId,
			'RequestType' => '1001',
			'RequestData' => urlencode($request_data),
			'DataType' => '1',
		);
		$datas['DataSign'] = $this->encrypt($request_data, $this->appKey);
		$result            = $this->sendPost($this->reqURL, $datas);

		//根据公司业务处理返回的信息......

		return $result;
	}

	/**
	 * Json方式 取消订单
	 *
	 * @param array $request_data 请求的数据
	 *
	 * @return
	 *
	 * @access public
	 */
	public function cancelOrderOnlineByJson($request_data = array())
	{
		$request_data = json_encode($request_data);


		$datas             = array(
			'EBusinessID' => $this->eBusinessId,
			'RequestType' => '1004',
			'RequestData' => urlencode($request_data),
			'DataType' => '2',
		);

		$datas['DataSign'] = $this->encrypt($request_data, $this->appKey);
		$result            = $this->sendPost($this->reqURL, $datas);

		//根据公司业务处理返回的信息......

		return $result;
	}

	/**
	 * Json方式 查询订单物流轨迹
	 */
	public function getOrderTracesByJson($request_data)
	{
		$request_data = json_encode($request_data);
		$datas             = array(
			'EBusinessID' => $this->eBusinessId,
			'RequestType' => '1002',
			'RequestData' => urlencode($request_data),
			'DataType' => '2',
		);
		$datas['DataSign'] = $this->encrypt($request_data, $this->appKey);
		$result            = $this->sendPost($this->reqURL, $datas);

		//根据公司业务处理返回的信息......

		return $result;
	}

	/**
	 * XML方式 查询订单物流轨迹
	 */
	public function getOrderTracesByXml($request_data = '')
	{
		$request_data= "<?xml version=\"1.0\" encoding=\"utf-8\" ?>".
			"<Content>".
			"<OrderCode></OrderCode>".
			"<ShipperCode>SF</ShipperCode>".
			"<LogisticCode>589707398027</LogisticCode>".
			"</Content>";

		$datas             = array(
			'EBusinessID' => $this->eBusinessId,
			'RequestType' => '1002',
			'RequestData' => urlencode($request_data),
			'DataType' => '1',
		);
		$datas['DataSign'] = $this->encrypt($request_data, $this->appKey);
		$result            = $this->sendPost($this->reqURL, $datas);

		//根据公司业务处理返回的信息......

		return $result;
	}


	/**
	 * Json方式  物流信息订阅
	 */
	public function subOrderTracesByJson($request_data = array())
	{
		$request_data="{'Code': 'SF','Item': [".
			"{'No': '909261024507','Bk': 'test'},".
			"{'No': '589554393102','Bk': 'test'},".
			"{'No': '589522101958','Bk': 'test'},".
			"{'No': '909198822942', 'Bk': 'test'}".
			"]}";

		$datas             = array(
			'EBusinessID' => $this->eBusinessId,
			'RequestType' => '1005',
			'RequestData' => urlencode($request_data),
			'DataType' => '2',
		);
		$datas['DataSign'] = $this->encrypt($request_data, $this->appKey);
		$result            = $this->sendPost($this->reqURL, $datas);

		//根据公司业务处理返回的信息......

		return $result;
	}

	/**
	 * XML方式  物流信息订阅
	 */
	public function subOrderTracesByXml($request_data = '')
	{
		$request_data="<?xml version=\"1.0\" encoding=\"utf-8\" ?>".
			"<Content>".
			"<Code>SF</Code>".
			"<Items>".
			"<Item>".
			"<No>909261024507</No>".
			"<Bk>test</Bk>".
			"</Item>".
			"<Item>".
			"<No>909261024507</No>".
			"<Bk>test</Bk>".
			"</Item>".
			"</Items>".
			"</Content>";

		$datas             = array(
			'EBusinessID' => $this->eBusinessId,
			'RequestType' => '1005',
			'RequestData' => urlencode($request_data),
			'DataType' => '1',
		);
		$datas['DataSign'] = $this->encrypt($request_data, $this->appKey);
		$result            = $this->sendPost($this->reqURL, $datas);

		//根据公司业务处理返回的信息......

		return $result;
	}


	/**
	 * Json方式 电子面单
	 *
	 * @param array $request_data 请求的数据
	 *
	 * @return
	 *
	 * @access public
	 */
	public function createWayBillOnlineByJson($request_data = array())
	{
		$request_data = json_encode($request_data);

		$datas             = array(
			'EBusinessID' => $this->eBusinessId,
			'RequestType' => '1007',
			'RequestData' => urlencode($request_data),
			'DataType' => '2',
		);

		$datas['DataSign'] = $this->encrypt($request_data, $this->appKey);
		$result            = $this->sendPost($this->reqURL, $datas);

		//根据公司业务处理返回的信息......

		return $result;
	}


	/**
	 *  post提交数据
	 * @param  string $url 请求Url
	 * @param  array $datas 提交的数据
	 * @return url响应返回的html
	 */
	function sendPost($url, $datas)
	{
		/*
		$temps = array();
		foreach ($datas as $key => $value)
		{
			$temps[] = sprintf('%s=%s', $key, $value);
		}
		$post_data  = implode('&', $temps);
		$url_info   = parse_url($url);
		$httpheader = "POST " . $url_info['path'] . " HTTP/1.0\r\n";
		$httpheader .= "Host:" . $url_info['host'] . "\r\n";
		$httpheader .= "Content-Type:application/x-www-form-urlencoded\r\n";
		$httpheader .= "Content-Length:" . strlen($post_data) . "\r\n";
		$httpheader .= "Connection:close\r\n\r\n";
		$httpheader .= $post_data;
		$fd = fsockopen($url_info['host'], 80);
		fwrite($fd, $httpheader);
		$gets = "";
		while (!feof($fd))
		{
			$gets .= fread($fd, 128);
		}
		
		fclose($fd);
		*/
		$gets = get_url($url, $datas);
		return $gets;
	}
}

?>
