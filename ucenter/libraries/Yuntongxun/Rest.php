<?php

/*
 *  Copyright (c) 2014 The CCP project authors. All Rights Reserved.
 *
 *  Use of this source code is governed by a Beijing Speedtong Information Technology Co.,Ltd license
 *  that can be found in the LICENSE file in the root of the web site.
 *
 *   http://www.yuntongxun.com
 *
 *  An additional intellectual property rights grant can be found
 *  in the file PATENTS.  All contributing project authors may
 *  be found in the AUTHORS file in the root of the source tree.
 */


class Yuntongxun_Rest
{
	private $AccountSid;
	private $AccountToken;
	private $AppId;
	private $SubAccountSid;
	private $SubAccountToken;
	private $VoIPAccount;
	private $VoIPPassword;
	private $ServerIP;
	private $ServerPort;
	private $SoftVersion;
	private $Batch;  //时间sh
	private $BodyType = "xml";//包体格式，可填值：json 、xml
	private $enabeLog = true; //日志开关。可填值：true、
	private $Filename = "./log.txt"; //日志文件
	private $Handle;

	function __construct($ServerIP, $ServerPort, $SoftVersion)
	{
		$this->Batch       = date("YmdHis");
		$this->ServerIP    = $ServerIP;
		$this->ServerPort  = $ServerPort;
		$this->SoftVersion = $SoftVersion;
		$this->Handle      = fopen($this->Filename, 'a');
	}

	/**
	 * 设置主帐号
	 *
	 * @param AccountSid 主帐号
	 * @param AccountToken 主帐号Token
	 */
	function setAccount($AccountSid, $AccountToken)
	{
		$this->AccountSid   = $AccountSid;
		$this->AccountToken = $AccountToken;
	}

	/**
	 * 设置子帐号
	 *
	 * @param SubAccountSid 子帐号
	 * @param SubAccountToken 子帐号Token
	 * @param VoIPAccount VoIP帐号
	 * @param VoIPPassword VoIP密码
	 */
	function setSubAccount($SubAccountSid, $SubAccountToken, $VoIPAccount, $VoIPPassword)
	{
		$this->SubAccountSid   = $SubAccountSid;
		$this->SubAccountToken = $SubAccountToken;
		$this->VoIPAccount     = $VoIPAccount;
		$this->VoIPPassword    = $VoIPPassword;
	}

	/**
	 * 设置应用ID
	 *
	 * @param AppId 应用ID
	 */
	function setAppId($AppId)
	{
		$this->AppId = $AppId;
	}

	/**
	 * 打印日志
	 *
	 * @param log 日志内容
	 */
	function showlog($log)
	{
		if ($this->enabeLog)
		{
			fwrite($this->Handle, $log . "\n");
		}
	}

	/**
	 * 发起HTTPS请求
	 */
	function curl_post($url, $data, $header, $post = 1)
	{
		//初始化curl
		$ch = curl_init();
		//参数设置
		$res = curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_POST, $post);
		if ($post)
		{
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		}
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
		$result = curl_exec($ch);
		//连接失败
		if ($result == FALSE)
		{
			if ($this->BodyType == 'json')
			{
				$result = "{\"statusCode\":\"172001\",\"statusMsg\":\"网络错误\"}";
			}
			else
			{
				$result = "<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"yes\"?><Response><statusCode>172001</statusCode><statusMsg>网络错误</statusMsg></Response>";
			}
		}

		curl_close($ch);
		return $result;
	}

	/**
	 * 创建子帐号
	 * @param friendlyName 子帐号名称
	 */
	function createSubAccount($friendlyName)
	{
		//主帐号鉴权信息验证，对必选参数进行判空。
		$auth = $this->accAuth();
		if ($auth != "")
		{
			return $auth;
		}
		// 拼接请求包体
		if ($this->BodyType == "json")
		{
			$body = "{'appId':'$this->AppId','friendlyName':'$friendlyName'}";
		}
		else
		{
			$body = "<SubAccount>
                    <appId>$this->AppId</appId>
                    <friendlyName>$friendlyName</friendlyName>
                  </SubAccount>";
		}
		$this->showlog("request body = " . $body);
		// 大写的sig参数
		$sig = strtoupper(md5($this->AccountSid . $this->AccountToken . $this->Batch));
		// 生成请求URL
		$url = "https://$this->ServerIP:$this->ServerPort/$this->SoftVersion/Accounts/$this->AccountSid/SubAccounts?sig=$sig";
		$this->showlog("request url = " . $url);
		// 生成授权：主帐号Id + 英文冒号 + 时间戳
		$authen = base64_encode($this->AccountSid . ":" . $this->Batch);
		// 生成包头
		$header = array(
			"Accept:application/$this->BodyType",
			"Content-Type:application/$this->BodyType;charset=utf-8",
			"Authorization:$authen"
		);
		// 发请求
		$result = $this->curl_post($url, $body, $header);
		$this->showlog("response body = " . $result);
		if ($this->BodyType == "json")
		{//JSON格式
			$datas = json_decode($result);
		}
		else
		{ //xml格式
			$datas = simplexml_load_string(trim($result, " \t\n\r"));
		}
		//  if($datas == FALSE){
//            $datas = new stdClass();
//            $datas->statusCode = '172003';
//            $datas->statusMsg = '返回包体错误'; 
//        }
		return $datas;
	}

	/**
	 * 获取子帐号
	 * @param startNo 开始的序号，默认从0开始
	 * @param offset 一次查询的最大条数，最小是1条，最大是100条
	 */
	function getSubAccounts($startNo, $offset)
	{
		//主帐号鉴权信息验证，对必选参数进行判空。
		$auth = $this->accAuth();
		if ($auth != "")
		{
			return $auth;
		}
		// 拼接请求包体
		$body = "
            <SubAccount>
              <appId>$this->AppId</appId>
              <startNo>$startNo</startNo>  
              <offset>$offset</offset>
            </SubAccount>";
		if ($this->BodyType == "json")
		{
			$body = "{'appId':'$this->AppId','startNo':'$startNo','offset':'$offset'}";
		}
		else
		{
			$body = "
            <SubAccount>
              <appId>$this->AppId</appId>
              <startNo>$startNo</startNo>  
              <offset>$offset</offset>
            </SubAccount>";
		}
		$this->showlog("request body = " . $body);
		// 大写的sig参数
		$sig = strtoupper(md5($this->AccountSid . $this->AccountToken . $this->Batch));
		// 生成请求URL
		$url = "https://$this->ServerIP:$this->ServerPort/$this->SoftVersion/Accounts/$this->AccountSid/GetSubAccounts?sig=$sig";
		$this->showlog("request url = " . $url);
		// 生成授权：主帐户Id + 英文冒号 + 时间戳。
		$authen = base64_encode($this->AccountSid . ":" . $this->Batch);
		// 生成包头
		$header = array(
			"Accept:application/$this->BodyType",
			"Content-Type:application/$this->BodyType;charset=utf-8",
			"Authorization:$authen"
		);
		// 发送请求
		$result = $this->curl_post($url, $body, $header);
		$this->showlog("response body = " . $result);
		if ($this->BodyType == "json")
		{//JSON格式
			$datas = json_decode($result);
		}
		else
		{ //xml格式
			$datas = simplexml_load_string(trim($result, " \t\n\r"));
		}
		//  if($datas == FALSE){
//            $datas = new stdClass();
//            $datas->statusCode = '172003';
//            $datas->statusMsg = '返回包体错误'; 
//        }
		return $datas;
	}

	/**
	 * 子帐号信息查询
	 * @param friendlyName 子帐号名称
	 */
	function querySubAccount($friendlyName)
	{
		//主帐号鉴权信息验证，对必选参数进行判空。
		$auth = $this->accAuth();
		if ($auth != "")
		{
			return $auth;
		}
		// 拼接请求包体

		if ($this->BodyType == "json")
		{
			$body = "{'appId':'$this->AppId','friendlyName':'$friendlyName'}";
		}
		else
		{
			$body = "
            <SubAccount>
              <appId>$this->AppId</appId>
              <friendlyName>$friendlyName</friendlyName>
            </SubAccount>";
		}
		$this->showlog("request body = " . $body);
		// 大写的sig参数
		$sig = strtoupper(md5($this->AccountSid . $this->AccountToken . $this->Batch));
		// 生成请求URL
		$url = "https://$this->ServerIP:$this->ServerPort/$this->SoftVersion/Accounts/$this->AccountSid/QuerySubAccountByName?sig=$sig";
		$this->showlog("request url = " . $url);
		// 生成授权：主帐户Id + 英文冒号 + 时间戳。
		$authen = base64_encode($this->AccountSid . ":" . $this->Batch);
		// 生成包头
		$header = array(
			"Accept:application/$this->BodyType",
			"Content-Type:application/$this->BodyType;charset=utf-8",
			"Authorization:$authen"
		);
		// 发送请求
		$result = $this->curl_post($url, $body, $header);
		$this->showlog("response body = " . $result);
		if ($this->BodyType == "json")
		{//JSON格式
			$datas = json_decode($result);
		}
		else
		{ //xml格式
			$datas = simplexml_load_string(trim($result, " \t\n\r"));
		}
		//  if($datas == FALSE){
//            $datas = new stdClass();
//            $datas->statusCode = '172003';
//            $datas->statusMsg = '返回包体错误'; 
//        }
		return $datas;
	}

	/**
	 * 发送模板短信
	 * @param to 短信接收彿手机号码集合,用英文逗号分开
	 * @param datas 内容数据
	 * @param $tempId 模板Id
	 */
	function sendTemplateSMS($to, $datas, $tempId)
	{
		//主帐号鉴权信息验证，对必选参数进行判空。
		$auth = $this->accAuth();
		if ($auth != "")
		{
			return $auth;
		}
		// 拼接请求包体
		if ($this->BodyType == "json")
		{
			$data = "";
			for ($i = 0; $i < count($datas); $i++)
			{
				$data = $data . "'" . $datas[$i] . "',";
			}
			$body = "{'to':'$to','templateId':'$tempId','appId':'$this->AppId','datas':[" . $data . "]}";
		}
		else
		{
			$data = "";
			for ($i = 0; $i < count($datas); $i++)
			{
				$data = $data . "<data>" . $datas[$i] . "</data>";
			}
			$body = "<TemplateSMS>
                    <to>$to</to> 
                    <appId>$this->AppId</appId>
                    <templateId>$tempId</templateId>
                    <datas>" . $data . "</datas>
                  </TemplateSMS>";
		}
		$this->showlog("request body = " . $body);
		// 大写的sig参数
		$sig = strtoupper(md5($this->AccountSid . $this->AccountToken . $this->Batch));
		// 生成请求URL
		$url = "https://$this->ServerIP:$this->ServerPort/$this->SoftVersion/Accounts/$this->AccountSid/SMS/TemplateSMS?sig=$sig";
		$this->showlog("request url = " . $url);
		// 生成授权：主帐户Id + 英文冒号 + 时间戳。
		$authen = base64_encode($this->AccountSid . ":" . $this->Batch);
		// 生成包头
		$header = array(
			"Accept:application/$this->BodyType",
			"Content-Type:application/$this->BodyType;charset=utf-8",
			"Authorization:$authen"
		);
		// 发送请求
		$result = $this->curl_post($url, $body, $header);
		$this->showlog("response body = " . $result);
		if ($this->BodyType == "json")
		{//JSON格式
			$datas = json_decode($result);
		}
		else
		{ //xml格式
			$datas = simplexml_load_string(trim($result, " \t\n\r"));
		}
		//  if($datas == FALSE){
//            $datas = new stdClass();
//            $datas->statusCode = '172003';
//            $datas->statusMsg = '返回包体错误'; 
//        }
		//重新装填数据
		if ($datas->statusCode == 0)
		{
			if ($this->BodyType == "json")
			{
				$datas->TemplateSMS = $datas->templateSMS;
				unset($datas->templateSMS);
			}
		}

		return $datas;
	}

	/**
	 * 双向回呼
	 * @param from 主叫电话号码
	 * @param to 被叫电话号码
	 * @param customerSerNum 被叫侧显示的客服号码
	 * @param fromSerNum 主叫侧显示的号码
	 * @param promptTone 自定义回拨提示音
	 * @param userData 第三方私有数据
	 * @param maxCallTime 最大通话时长
	 * @param hangupCdrUrl 实时话单通知地址
	 * @param alwaysPlay 是否一直播放提示音
	 * @param terminalDtmf 用于终止播放promptTone参数定义的提示音
	 * @param needBothCdr 是否给主被叫发送话单
	 * @param needRecord 是否录音
	 * @param countDownTime 设置倒计时时间
	 * @param countDownPrompt 倒计时时间到后播放的提示音
	 */
	function callBack($from, $to, $customerSerNum, $fromSerNum, $promptTone, $alwaysPlay, $terminalDtmf, $userData, $maxCallTime, $hangupCdrUrl, $needBothCdr, $needRecord, $countDownTime, $countDownPrompt)
	{
		//子帐号鉴权信息验证，对必选参数进行判空。
		$auth = $this->subAuth();
		if ($auth != "")
		{
			return $auth;
		}
		// 拼接请求包体
		if ($this->BodyType == "json")
		{
			$body = "{'from':'$from','to':'$to','customerSerNum':'$customerSerNum','fromSerNum':'$fromSerNum','promptTone':'$promptTone','userData':'$userData','maxCallTime':'$maxCallTime','hangupCdrUrl':'$hangupCdrUrl',
           'alwaysPlay':'$alwaysPlay','terminalDtmf':'$terminalDtmf','needBothCdr':'$needBothCdr',
           'needRecord':'$needRecord','countDownTime':'$$countDownTime','countDownPrompt':'$countDownPrompt'}";
		}
		else
		{
			$body = "<CallBack>
                     <from>$from</from>
                     <to>$to</to>
                     <customerSerNum>$customerSerNum</customerSerNum>
                     <fromSerNum>$fromSerNum</fromSerNum>
                     <promptTone>$promptTone</promptTone>
					           <userData>$userData</userData>
					           <maxCallTime>$maxCallTime</maxCallTime>
					           <hangupCdrUrl>$hangupCdrUrl</hangupCdrUrl>
                     <alwaysPlay>$alwaysPlay</alwaysPlay>
                     <terminalDtmf>$terminalDtmf</terminalDtmf>
                     <needBothCdr>$needBothCdr</needBothCdr>
                     <needRecord>$needRecord</needRecord>
                     <countDownTime>$countDownTime</countDownTime>
                     <countDownPrompt>$countDownPrompt</countDownPrompt>
                   </CallBack>";
		}
		$this->showlog("request body = " . $body);
		// 大写的sig参数
		$sig = strtoupper(md5($this->SubAccountSid . $this->SubAccountToken . $this->Batch));
		// 生成请求URL
		$url = "https://$this->ServerIP:$this->ServerPort/$this->SoftVersion/SubAccounts/$this->SubAccountSid/Calls/Callback?sig=$sig";
		$this->showlog("request url = " . $url);
		// 生成授权：子帐号Id + 英文冒号 + 时间戳
		$authen = base64_encode($this->SubAccountSid . ":" . $this->Batch);
		// 生成包头
		$header = array(
			"Accept:application/$this->BodyType",
			"Content-Type:application/$this->BodyType;charset=utf-8",
			"Authorization:$authen"
		);
		// 发请求
		$result = $this->curl_post($url, $body, $header);
		$this->showlog("response body = " . $result);
		if ($this->BodyType == "json")
		{//JSON格式
			$datas = json_decode($result);
		}
		else
		{ //xml格式
			$datas = simplexml_load_string(trim($result, " \t\n\r"));
		}
		//  if($datas == FALSE){
//            $datas = new stdClass();
//            $datas->statusCode = '172003';
//            $datas->statusMsg = '返回包体错误'; 
//        }
		return $datas;
	}


	/**
	 * 外呼通知
	 * @param to 被叫号码
	 * @param mediaName 语音文件名称，格式 wav。与mediaTxt不能同时为空。当不为空时mediaTxt属性失效。
	 * @param mediaTxt 文本内容
	 * @param displayNum 显示的主叫号码
	 * @param playTimes 循环播放次数，1－3次，默认播放1次。
	 * @param respUrl 外呼通知状态通知回调地址，云通讯平台将向该Url地址发送呼叫结果通知。
	 * @param userData 用户私有数据
	 * @param maxCallTime 最大通话时长
	 * @param speed 发音速度
	 * @param volume 音量
	 * @param pitch 音调
	 * @param bgsound 背景音编号
	 */
	function landingCall($to, $mediaName, $mediaTxt, $displayNum, $playTimes, $respUrl, $userData, $maxCallTime, $speed, $volume, $pitch, $bgsound)
	{
		//主帐号鉴权信息验证，对必选参数进行判空。
		$auth = $this->accAuth();
		if ($auth != "")
		{
			return $auth;
		}
		// 拼接请求包体
		if ($this->BodyType == "json")
		{
			$body = "{'playTimes':'$playTimes','mediaTxt':'$mediaTxt','mediaName':'$mediaName','to':'$to','appId':'$this->AppId','displayNum':'$displayNum','respUrl':'$respUrl',
           'userData':'$userData','maxCallTime':'$maxCallTime','speed':'$speed','volume':'$volume','pitch':'$pitch','bgsound':'$bgsound'}";
		}
		else
		{
			$body = "<LandingCall>
                    <to>$to</to>
                    <mediaName>$mediaName</mediaName>
                    <mediaTxt>$mediaTxt</mediaTxt> 
                    <appId>$this->AppId</appId>
                    <displayNum>$displayNum</displayNum>
                    <playTimes>$playTimes</playTimes>
                    <respUrl>$respUrl</respUrl>
                    <userData>$userData</userData>
                    <maxCallTime>$maxCallTime</maxCallTime>
                    <speed>$speed</speed>
                    <volume>$volume</volume>
                    <pitch>$pitch</pitch>
                    <bgsound>$bgsound</bgsound>
                  </LandingCall>";
		}
		$this->showlog("request body = " . $body);
		// 大写的sig参数
		$sig = strtoupper(md5($this->AccountSid . $this->AccountToken . $this->Batch));
		// 生成请求URL
		$url = "https://$this->ServerIP:$this->ServerPort/$this->SoftVersion/Accounts/$this->AccountSid/Calls/LandingCalls?sig=$sig";
		$this->showlog("request url = " . $url);
		// 生成授权：主帐户Id + 英文冒号 + 时间戳。
		$authen = base64_encode($this->AccountSid . ":" . $this->Batch);
		// 生成包头
		$header = array(
			"Accept:application/$this->BodyType",
			"Content-Type:application/$this->BodyType;charset=utf-8",
			"Authorization:$authen"
		);
		// 发送请求
		$result = $this->curl_post($url, $body, $header);
		$this->showlog("response body = " . $result);
		if ($this->BodyType == "json")
		{//JSON格式
			$datas = json_decode($result);
		}
		else
		{ //xml格式
			$datas = simplexml_load_string(trim($result, " \t\n\r"));
		}
		//  if($datas == FALSE){
//            $datas = new stdClass();
//            $datas->statusCode = '172003';
//            $datas->statusMsg = '返回包体错误'; 
//        }
		return $datas;
	}

	/**
	 * 语音验证码
	 * @param verifyCode 验证码内容，为数字和英文字母，不区分大小写，长度4-8位
	 * @param playTimes 播放次数，1－3次
	 * @param to 接收号码
	 * @param displayNum 显示的主叫号码
	 * @param respUrl 语音验证码状态通知回调地址，云通讯平台将向该Url地址发送呼叫结果通知
	 * @param lang 语言类型
	 * @param userData 第三方私有数据
	 */
	function voiceVerify($verifyCode, $playTimes, $to, $displayNum, $respUrl, $lang, $userData)
	{
		//主帐号鉴权信息验证，对必选参数进行判空。
		$auth = $this->accAuth();
		if ($auth != "")
		{
			return $auth;
		}
		// 拼接请求包体
		if ($this->BodyType == "json")
		{
			$body = "{'appId':'$this->AppId','verifyCode':'$verifyCode','playTimes':'$playTimes','to':'$to','respUrl':'$respUrl','displayNum':'$displayNum',
           'lang':'$lang','userData':'$userData'}";
		}
		else
		{
			$body = "<VoiceVerify>
                    <appId>$this->AppId</appId>
                    <verifyCode>$verifyCode</verifyCode>
                    <playTimes>$playTimes</playTimes>
                    <to>$to</to>
                    <respUrl>$respUrl</respUrl>
                    <displayNum>$displayNum</displayNum>
                    <lang>$lang</lang>
                    <userData>$userData</userData>
                  </VoiceVerify>";
		}
		$this->showlog("request body = " . $body);
		// 大写的sig参数
		$sig = strtoupper(md5($this->AccountSid . $this->AccountToken . $this->Batch));
		// 生成请求URL
		$url = "https://$this->ServerIP:$this->ServerPort/$this->SoftVersion/Accounts/$this->AccountSid/Calls/VoiceVerify?sig=$sig";
		$this->showlog("request url = " . $url);
		// 生成授权：主帐户Id + 英文冒号 + 时间戳。
		$authen = base64_encode($this->AccountSid . ":" . $this->Batch);
		// 生成包头
		$header = array(
			"Accept:application/$this->BodyType",
			"Content-Type:application/$this->BodyType;charset=utf-8",
			"Authorization:$authen"
		);
		// 发送请求
		$result = $this->curl_post($url, $body, $header);
		$this->showlog("response body = " . $result);
		if ($this->BodyType == "json")
		{//JSON格式
			$datas = json_decode($result);
		}
		else
		{ //xml格式
			$datas = simplexml_load_string(trim($result, " \t\n\r"));
		}
		//  if($datas == FALSE){
//            $datas = new stdClass();
//            $datas->statusCode = '172003';
//            $datas->statusMsg = '返回包体错误'; 
//        }
		return $datas;
	}

	/**
	 * IVR外呼
	 * @param number   待呼叫号码，为Dial节点的属性
	 * @param userdata 用户数据，在<startservice>通知中返回，只允许填写数字字符，为Dial节点的属性
	 * @param record   是否录音，可填项为true和false，默认值为false不录音，为Dial节点的属性
	 */
	function ivrDial($number, $userdata, $record)
	{
		//主帐号鉴权信息验证，对必选参数进行判空。
		$auth = $this->accAuth();
		if ($auth != "")
		{
			return $auth;
		}
		// 拼接请求包体
		$body = " <Request>
                  <Appid>$this->AppId</Appid>
                  <Dial number='$number'  userdata='$userdata' record='$record'></Dial>
                </Request>";
		$this->showlog("request body = " . $body);
		// 大写的sig参数
		$sig = strtoupper(md5($this->AccountSid . $this->AccountToken . $this->Batch));
		// 生成请求URL
		$url = "https://$this->ServerIP:$this->ServerPort/$this->SoftVersion/Accounts/$this->AccountSid/ivr/dial?sig=$sig";
		$this->showlog("request url = " . $url);
		// 生成授权：主帐户Id + 英文冒号 + 时间戳。
		$authen = base64_encode($this->AccountSid . ":" . $this->Batch);
		// 生成包头
		$header = array(
			"Accept:application/xml",
			"Content-Type:application/xml;charset=utf-8",
			"Authorization:$authen"
		);
		// 发送请求
		$result = $this->curl_post($url, $body, $header);
		$this->showlog("response body = " . $result);
		$datas = simplexml_load_string(trim($result, " \t\n\r"));
		//  if($datas == FALSE){
//            $datas = new stdClass();
//            $datas->statusCode = '172003';
//            $datas->statusMsg = '返回包体错误'; 
//        }
		return $datas;
	}

	/**
	 * 话单下载
	 * @param date     day 代表前一天的数据（从00:00 – 23:59）
	 * @param keywords   客户的查询条件，由客户自行定义并提供给云通讯平台。默认不填忽略此参数
	 */
	function billRecords($date, $keywords)
	{
		//主帐号鉴权信息验证，对必选参数进行判空。
		$auth = $this->accAuth();
		if ($auth != "")
		{
			return $auth;
		}
		// 拼接请求包体
		if ($this->BodyType == "json")
		{
			$body = "{'appId':'$this->AppId','date':'$date','keywords':'$keywords'}";
		}
		else
		{
			$body = "<BillRecords>
                    <appId>$this->AppId</appId>
                    <date>$date</date>
                    <keywords>$keywords</keywords>
                  </BillRecords>";
		}
		$this->showlog("request body = " . $body);
		// 大写的sig参数
		$sig = strtoupper(md5($this->AccountSid . $this->AccountToken . $this->Batch));
		// 生成请求URL
		$url = "https://$this->ServerIP:$this->ServerPort/$this->SoftVersion/Accounts/$this->AccountSid/BillRecords?sig=$sig";
		$this->showlog("request url = " . $url);
		// 生成授权：主帐户Id + 英文冒号 + 时间戳。
		$authen = base64_encode($this->AccountSid . ":" . $this->Batch);
		// 生成包头
		$header = array(
			"Accept:application/$this->BodyType",
			"Content-Type:application/$this->BodyType;charset=utf-8",
			"Authorization:$authen"
		);
		// 发送请求
		$result = $this->curl_post($url, $body, $header);
		$this->showlog("response body = " . $result);
		if ($this->BodyType == "json")
		{//JSON格式
			$datas = json_decode($result);
		}
		else
		{ //xml格式
			$datas = simplexml_load_string(trim($result, " \t\n\r"));
		}
		//  if($datas == FALSE){
//            $datas = new stdClass();
//            $datas->statusCode = '172003';
//            $datas->statusMsg = '返回包体错误'; 
//        }
		return $datas;
	}

	/**
	 * 主帐号信息查询
	 */
	function queryAccountInfo()
	{
		//主帐号鉴权信息验证，对必选参数进行判空。
		$auth = $this->accAuth();
		if ($auth != "")
		{
			return $auth;
		}
		// 大写的sig参数
		$sig = strtoupper(md5($this->AccountSid . $this->AccountToken . $this->Batch));
		// 生成请求URL
		$url = "https://$this->ServerIP:$this->ServerPort/$this->SoftVersion/Accounts/$this->AccountSid/AccountInfo?sig=$sig";
		$this->showlog("request url = " . $url);
		// 生成授权：主帐户Id + 英文冒号 + 时间戳。
		$authen = base64_encode($this->AccountSid . ":" . $this->Batch);
		// 生成包头
		$header = array(
			"Accept:application/$this->BodyType",
			"Content-Type:application/$this->BodyType;charset=utf-8",
			"Authorization:$authen"
		);
		// 发送请求
		$result = $this->curl_post($url, "", $header, 0);
		$this->showlog("response body = " . $result);
		if ($this->BodyType == "json")
		{//JSON格式
			$datas = json_decode($result);
		}
		else
		{ //xml格式
			$datas = simplexml_load_string(trim($result, " \t\n\r"));
		}
		//  if($datas == FALSE){
//            $datas = new stdClass();
//            $datas->statusCode = '172003';
//            $datas->statusMsg = '返回包体错误'; 
//        }
		return $datas;
	}

	/**
	 * 短信模板查询
	 * @param date     templateId 模板ID
	 */
	function QuerySMSTemplate($templateId)
	{
		//主帐号鉴权信息验证，对必选参数进行判空。
		$auth = $this->accAuth();
		if ($auth != "")
		{
			return $auth;
		}
		// 拼接请求包体
		if ($this->BodyType == "json")
		{
			$body = "{'appId':'$this->AppId','templateId':'$templateId'}";
		}
		else
		{
			$body = "<Request>
                    <appId>$this->AppId</appId>
                    <templateId>$templateId</templateId>  
                  </Request>";
		}
		$this->showlog("request body = " . $body);
		// 大写的sig参数
		$sig = strtoupper(md5($this->AccountSid . $this->AccountToken . $this->Batch));
		// 生成请求URL
		$url = "https://$this->ServerIP:$this->ServerPort/$this->SoftVersion/Accounts/$this->AccountSid/SMS/QuerySMSTemplate?sig=$sig";
		$this->showlog("request url = " . $url);
		// 生成授权：主帐户Id + 英文冒号 + 时间戳。
		$authen = base64_encode($this->AccountSid . ":" . $this->Batch);
		// 生成包头
		$header = array(
			"Accept:application/$this->BodyType",
			"Content-Type:application/$this->BodyType;charset=utf-8",
			"Authorization:$authen"
		);
		// 发送请求
		$result = $this->curl_post($url, $body, $header);
		$this->showlog("response body = " . $result);
		if ($this->BodyType == "json")
		{//JSON格式
			$datas = json_decode($result);
		}
		else
		{ //xml格式
			$datas = simplexml_load_string(trim($result, " \t\n\r"));
		}
		//  if($datas == FALSE){
//            $datas = new stdClass();
//            $datas->statusCode = '172003';
//            $datas->statusMsg = '返回包体错误'; 
//        }
		return $datas;
	}


	/**
	 * 取消回拨
	 * @param callSid          一个由32个字符组成的电话唯一标识符
	 * @param type 0： 任意时间都可以挂断电话；1 ：被叫应答前可以挂断电话，其他时段返回错误代码；2： 主叫应答前可以挂断电话，其他时段返回错误代码；默认值为0。
	 */
	function CallCancel($callSid, $type)
	{
		//主帐号鉴权信息验证，对必选参数进行判空。
		$auth = $this->subAuth();
		if ($auth != "")
		{
			return $auth;
		}
		// 拼接请求包体
		if ($this->BodyType == "json")
		{
			$body = "{'appId':'$this->AppId','callSid':'$callSid','type':'$type'}";
		}
		else
		{
			$body = "<CallCancel>
                    <appId>$this->AppId</appId>
                    <callSid>$callSid</callSid>
                    <type>$type</type>
                  </CallCancel>";
		}
		$this->showlog("request body = " . $body);
		// 大写的sig参数
		$sig = strtoupper(md5($this->SubAccountSid . $this->SubAccountToken . $this->Batch));
		// 生成请求URL
		$url = "https://$this->ServerIP:$this->ServerPort/$this->SoftVersion/SubAccounts/$this->SubAccountSid/Calls/CallCancel?sig=$sig";
		$this->showlog("request url = " . $url);
		// 生成授权：主帐户Id + 英文冒号 + 时间戳。
		$authen = base64_encode($this->SubAccountSid . ":" . $this->Batch);
		// 生成包头
		$header = array(
			"Accept:application/$this->BodyType",
			"Content-Type:application/$this->BodyType;charset=utf-8",
			"Authorization:$authen"
		);
		// 发送请求
		$result = $this->curl_post($url, $body, $header);
		$this->showlog("response body = " . $result);
		if ($this->BodyType == "json")
		{//JSON格式
			$datas = json_decode($result);
		}
		else
		{ //xml格式
			$datas = simplexml_load_string(trim($result, " \t\n\r"));
		}
		//  if($datas == FALSE){
//            $datas = new stdClass();
//            $datas->statusCode = '172003';
//            $datas->statusMsg = '返回包体错误'; 
//        }
		return $datas;
	}

	/**
	 * 呼叫状态查询
	 * @param callid     呼叫Id
	 * @param action   查询结果通知的回调url地址
	 */
	function QueryCallState($callid, $action)
	{
		//主帐号鉴权信息验证，对必选参数进行判空。
		$auth = $this->accAuth();
		if ($auth != "")
		{
			return $auth;
		}
		// 拼接请求包体
		if ($this->BodyType == "json")
		{
			$body = "{'Appid':'$this->AppId','QueryCallState':{'callid':'$callid','action':'$action'}}";
		}
		else
		{
			$body = "<Request>
                    <Appid>$this->AppId</Appid>
                    <QueryCallState callid ='$callid' action='$action'/>
                  </Request>";
		}
		$this->showlog("request body = " . $body);
		// 大写的sig参数
		$sig = strtoupper(md5($this->AccountSid . $this->AccountToken . $this->Batch));
		// 生成请求URL
		$url = "https://$this->ServerIP:$this->ServerPort/$this->SoftVersion/Accounts/$this->AccountSid/ivr/call?sig=$sig&callid=$callid";
		$this->showlog("request url = " . $url);
		// 生成授权：主帐户Id + 英文冒号 + 时间戳。
		$authen = base64_encode($this->AccountSid . ":" . $this->Batch);
		// 生成包头
		$header = array(
			"Accept:application/$this->BodyType",
			"Content-Type:application/$this->BodyType;charset=utf-8",
			"Authorization:$authen"
		);
		// 发送请求
		$result = $this->curl_post($url, $body, $header);
		$this->showlog("response body = " . $result);
		if ($this->BodyType == "json")
		{//JSON格式
			$datas = json_decode($result);
		}
		else
		{ //xml格式
			$datas = simplexml_load_string(trim($result, " \t\n\r"));
		}
		//  if($datas == FALSE){
//            $datas = new stdClass();
//            $datas->statusCode = '172003';
//            $datas->statusMsg = '返回包体错误'; 
//        }
		return $datas;
	}

	/**
	 * 呼叫结果查询
	 * @param callSid     呼叫Id
	 */
	function CallResult($callSid)
	{
		//主帐号鉴权信息验证，对必选参数进行判空。
		$auth = $this->accAuth();
		if ($auth != "")
		{
			return $auth;
		}
		// 大写的sig参数
		$sig = strtoupper(md5($this->AccountSid . $this->AccountToken . $this->Batch));
		// 生成请求URL
		$url = "https://$this->ServerIP:$this->ServerPort/$this->SoftVersion/Accounts/$this->AccountSid/CallResult?sig=$sig&callsid=$callSid";
		$this->showlog("request url = " . $url);
		// 生成授权：主帐户Id + 英文冒号 + 时间戳。
		$authen = base64_encode($this->AccountSid . ":" . $this->Batch);
		// 生成包头
		$header = array(
			"Accept:application/$this->BodyType",
			"Content-Type:application/$this->BodyType;charset=utf-8",
			"Authorization:$authen"
		);
		// 发送请求
		$result = $this->curl_post($url, "", $header, 0);
		$this->showlog("response body = " . $result);
		if ($this->BodyType == "json")
		{//JSON格式
			$datas = json_decode($result);
		}
		else
		{ //xml格式
			$datas = simplexml_load_string(trim($result, " \t\n\r"));
		}
		//  if($datas == FALSE){
//            $datas = new stdClass();
//            $datas->statusCode = '172003';
//            $datas->statusMsg = '返回包体错误'; 
//        }
		return $datas;
	}

	/**
	 * 语音文件上传
	 * @param filename     文件名
	 * @param body   二进制串
	 */
	function MediaFileUpload($filename, $body)
	{
		//主帐号鉴权信息验证，对必选参数进行判空。
		$auth = $this->accAuth();
		if ($auth != "")
		{
			return $auth;
		}
		// 拼接请求包体

		$this->showlog("request body = " . $body);
		// 大写的sig参数
		$sig = strtoupper(md5($this->AccountSid . $this->AccountToken . $this->Batch));
		// 生成请求URL
		$url = "https://$this->ServerIP:$this->ServerPort/$this->SoftVersion/Accounts/$this->AccountSid/Calls/MediaFileUpload?sig=$sig&appid=$this->AppId&filename=$filename";
		$this->showlog("request url = " . $url);
		// 生成授权：主帐户Id + 英文冒号 + 时间戳。
		$authen = base64_encode($this->AccountSid . ":" . $this->Batch);
		// 生成包头
		$header = array(
			"Accept:application/$this->BodyType",
			"Content-Type:application/octet-stream",
			"Authorization:$authen"
		);
		// 发送请求
		$result = $this->curl_post($url, $body, $header);
		$this->showlog("response body = " . $result);
		if ($this->BodyType == "json")
		{//JSON格式
			$datas = json_decode($result);
		}
		else
		{ //xml格式
			$datas = simplexml_load_string(trim($result, " \t\n\r"));
		}
		//  if($datas == FALSE){
//            $datas = new stdClass();
//            $datas->statusCode = '172003';
//            $datas->statusMsg = '返回包体错误'; 
//        }
		return $datas;
	}

	/**
	 * 子帐号鉴权
	 */
	function subAuth()
	{
		if ($this->ServerIP == "")
		{
			$data             = new stdClass();
			$data->statusCode = '172004';
			$data->statusMsg  = 'IP为空';
			return $data;
		}
		if ($this->ServerPort <= 0)
		{
			$data             = new stdClass();
			$data->statusCode = '172005';
			$data->statusMsg  = '端口错误（小于等于0）';
			return $data;
		}
		if ($this->SoftVersion == "")
		{
			$data             = new stdClass();
			$data->statusCode = '172013';
			$data->statusMsg  = '版本号为空';
			return $data;
		}
		if ($this->SubAccountSid == "")
		{
			$data             = new stdClass();
			$data->statusCode = '172008';
			$data->statusMsg  = '子帐号为空';
			return $data;
		}
		if ($this->SubAccountToken == "")
		{
			$data             = new stdClass();
			$data->statusCode = '172009';
			$data->statusMsg  = '子帐号令牌为空';
			return $data;
		}
		if ($this->AppId == "")
		{
			$data             = new stdClass();
			$data->statusCode = '172012';
			$data->statusMsg  = '应用ID为空';
			return $data;
		}
	}

	/**
	 * 主帐号鉴权
	 */
	function accAuth()
	{
		if ($this->ServerIP == "")
		{
			$data             = new stdClass();
			$data->statusCode = '172004';
			$data->statusMsg  = 'IP为空';
			return $data;
		}
		if ($this->ServerPort <= 0)
		{
			$data             = new stdClass();
			$data->statusCode = '172005';
			$data->statusMsg  = '端口错误（小于等于0）';
			return $data;
		}
		if ($this->SoftVersion == "")
		{
			$data             = new stdClass();
			$data->statusCode = '172013';
			$data->statusMsg  = '版本号为空';
			return $data;
		}
		if ($this->AccountSid == "")
		{
			$data             = new stdClass();
			$data->statusCode = '172006';
			$data->statusMsg  = '主帐号为空';
			return $data;
		}
		if ($this->AccountToken == "")
		{
			$data             = new stdClass();
			$data->statusCode = '172007';
			$data->statusMsg  = '主帐号令牌为空';
			return $data;
		}
		if ($this->AppId == "")
		{
			$data             = new stdClass();
			$data->statusCode = '172012';
			$data->statusMsg  = '应用ID为空';
			return $data;
		}
	}


	/**
	 * 获取所有公用群组
	 *
	 * @param  string $last_update_time 上一次更新的时间戳 ms（用于分页，最大返回50条数据）
	 * @access public
	 */
	public function getPublicGroups($last_update_time = null)
	{
		if ($this->BodyType == "json")
		{
		}
		else
		{
			$body = "<?xml version=\"1.0\" encoding=\"UTF-8\"?><Request>";

			if ($last_update_time)
			{
				$body = $body . "<lastUpdateTime>" . $last_update_time . "</lastUpdateTime>";
			}

			$body = $body . "</Request>";
		}


		$this->showlog("request body = " . $body);

		// 大写的sig参数
		$sig = strtoupper(md5($this->SubAccountSid . $this->SubAccountToken . $this->Batch));

		// 生成请求URL
		$url = "https://$this->ServerIP:$this->ServerPort/$this->SoftVersion/SubAccounts/$this->SubAccountSid/Group/etPublicGroups?sig=$sig";


		$this->showlog("request url = " . $url);
		// 生成授权：主帐户Id + 英文冒号 + 时间戳。
		$authen = base64_encode($this->SubAccountSid . ":" . $this->Batch);

		// 生成包头
		$header = array(
			"Accept:application/$this->BodyType",
			"Content-Type:application/$this->BodyType;charset=utf-8",
			"Authorization:$authen"
		);

		// 发送请求
		$result = $this->curl_post($url, $body, $header);
		$this->showlog("response body = " . $result);
		if ($this->BodyType == "json")
		{
			//JSON格式
			$datas = json_decode($result);
		}
		else
		{ //xml格式
			$datas = simplexml_load_string(trim($result, " \t\n\r"));
		}

		//重新装填数据
		if ($datas->statusCode == 0)
		{
		}

		return $datas;
	}


	/**
	 * 群组搜索
	 *
	 * @param  string $group_id 根据群组ID查找（同时具备两个条件，查询以此为先）
	 * @param  string $name 根据群组名查找（模糊查询，结果集中不包含私有群组）
	 * @access public
	 */
	public function searchPublicGroups($group_id = null, $name = null)
	{
		/*
		//主帐号鉴权信息验证，对必选参数进行判空。
		$auth = $this->accAuth();
		if ($auth != "")
		{
			return $auth;
		}
		*/

		if ($this->BodyType == "json")
		{
		}
		else
		{
			$body = "<?xml version=\"1.0\" encoding=\"UTF-8\"?><Request>";

			if ($group_id)
			{
				$body = $body . "<groupId>" . $group_id . "</groupId>";
			}


			if ($name)
			{
				$body = $body . "<name>" . $name . "</name>";
			}


			$body = $body . "</Request>";
		}


		$this->showlog("request body = " . $body);

		// 大写的sig参数
		$sig = strtoupper(md5($this->SubAccountSid . $this->SubAccountToken . $this->Batch));

		// 生成请求URL
		$url = "https://$this->ServerIP:$this->ServerPort/$this->SoftVersion/SubAccounts/$this->SubAccountSid/Group/SearchPublicGroups?sig=$sig";


		$this->showlog("request url = " . $url);
		// 生成授权：主帐户Id + 英文冒号 + 时间戳。
		$authen = base64_encode($this->SubAccountSid . ":" . $this->Batch);

		// 生成包头
		$header = array(
			"Accept:application/$this->BodyType",
			"Content-Type:application/$this->BodyType;charset=utf-8",
			"Authorization:$authen"
		);

		// 发送请求
		$result = $this->curl_post($url, $body, $header);
		$this->showlog("response body = " . $result);
		if ($this->BodyType == "json")
		{
			//JSON格式
			$datas = json_decode($result);
		}
		else
		{ //xml格式
			$datas = simplexml_load_string(trim($result, " \t\n\r"));
		}
		//  if($datas == FALSE){
//            $datas = new stdClass();
//            $datas->statusCode = '172003';
//            $datas->statusMsg = '返回包体错误';
//        }

		//重新装填数据
		if ($datas->statusCode == 0)
		{
		}

		return $datas;
	}


	/**
	 * 群组搜索
	 *
	 * @param  string $group_id 根据群组ID查找（同时具备两个条件，查询以此为先）
	 * @param  string $name 根据群组名查找（模糊查询，结果集中不包含私有群组）
	 * @access public
	 */
	public function queryMember($group_id = null)
	{
		/*
		//主帐号鉴权信息验证，对必选参数进行判空。
		$auth = $this->accAuth();
		if ($auth != "")
		{
			return $auth;
		}
		*/

		if ($this->BodyType == "json")
		{
		}
		else
		{
			$body = "<?xml version=\"1.0\" encoding=\"UTF-8\"?><Request>";

			if ($group_id)
			{
				$body = $body . "<groupId>" . $group_id . "</groupId>";
			}

			$body = $body . "</Request>";
		}


		$this->showlog("request body = " . $body);

		// 大写的sig参数
		$sig = strtoupper(md5($this->SubAccountSid . $this->SubAccountToken . $this->Batch));

		// 生成请求URL
		$url = "https://$this->ServerIP:$this->ServerPort/$this->SoftVersion/SubAccounts/$this->SubAccountSid/Member/QueryMember?sig=$sig";


		$this->showlog("request url = " . $url);
		// 生成授权：主帐户Id + 英文冒号 + 时间戳。
		$authen = base64_encode($this->SubAccountSid . ":" . $this->Batch);

		// 生成包头
		$header = array(
			"Accept:application/$this->BodyType",
			"Content-Type:application/$this->BodyType;charset=utf-8",
			"Authorization:$authen"
		);

		// 发送请求
		$result = $this->curl_post($url, $body, $header);
		$this->showlog("response body = " . $result);

		if ($this->BodyType == "json")
		{
			//JSON格式
			$datas = json_decode($result);
		}
		else
		{
			//xml格式
			$datas = simplexml_load_string(trim($result, " \t\n\r"));
		}


		//重新装填数据
		if ($datas->statusCode == 0)
		{
		}

		return $datas;
	}
	/**
	 * 消息推送
	 * pushType	int	必选	推送类型，1：个人，2：群组，默认为1
	appId	String	必选	应用Id
	sender	String	必选	发送者帐号
	receiver	String	必选	接收者帐号，如果是个人，最大上限100人/次，如果是群组，仅支持1个；如果需要跨应用给个人发送信息，需要在接收者帐号前加appid和#。例如：appid=1，接收者帐号=a，则需要拼为1#a。由于群组ID为唯一ID，因此跨应用给群组发送消息无需增加appid和#。
	msgType	int	必选	消息类型，1：文本消息，2：语音消息，3：视频消息，4：图片消息，5：位置消息，6：文件
	msgContent	String	可选	文本内容，最大长度2048字节，文本和附件二选一，不能都为空
	msgDomain	String	可选	扩展字段
	msgFileName	String	可选	文件名，最大长度128字节
	msgFileUrl	String	可选	文件绝对路径
	 */
	function pushMsg($body_row)
	{
		//主帐号鉴权信息验证，对必选参数进行判空。
		$auth = $this->accAuth();
		if ($auth != "")
		{
			return $auth;
		}
		// 拼接请求包体
		if (true || $this->BodyType == "json")
		{
			/*
			$body_row = array();
			$body_row["pushType"] = "1";
			$body_row["appId"] = "2aabdefff0";
			$body_row["sender"] = "13291217102";
			$body_row["receiver"] = ["18201370642","13121353225"];
			$body_row["msgType"] = "1";
			$body_row["msgContent"] = "你好";
			$body_row["msgDomain"] = "yuntongxun";
			$body_row["msgFileName"] = "";
			$body_row["msgFileUrl"] = "";
			*/

			$body = encode_json($body_row);
		}
		else
		{
			/*
			$data = "";
			for ($i = 0; $i < count($datas); $i++)
			{
				$data = $data . "<data>" . $datas[$i] . "</data>";
			}
			$body = "<TemplateSMS>
                    <to>$to</to>
                    <appId>$this->AppId</appId>
                    <templateId>$tempId</templateId>
                    <datas>" . $data . "</datas>
                  </TemplateSMS>";
			*/
		}


		$this->showlog("request body = " . $body);
		// 大写的sig参数
		$sig = strtoupper(md5($this->AccountSid . $this->AccountToken . $this->Batch));

		// 生成请求URL
		$url = "https://$this->ServerIP:$this->ServerPort/$this->SoftVersion/Accounts/$this->AccountSid/IM/PushMsg?sig=$sig";


		$this->showlog("request url = " . $url);
		// 生成授权：主帐户Id + 英文冒号 + 时间戳。
		$authen = base64_encode($this->AccountSid . ":" . $this->Batch);

		// 生成包头
		$header = array(
			"Accept:application/$this->BodyType",
			"Content-Type:application/$this->BodyType;charset=utf-8",
			"Authorization:$authen"
		);

		// 发送请求
		$result = $this->curl_post($url, $body, $header);
		$this->showlog("response body = " . $result);
		if ($this->BodyType == "json")
		{
			//JSON格式
			$datas = json_decode($result);
		}
		else
		{ //xml格式
			$datas = simplexml_load_string(trim($result, " \t\n\r"));
		}
		//  if($datas == FALSE){
//            $datas = new stdClass();
//            $datas->statusCode = '172003';
//            $datas->statusMsg = '返回包体错误';
//        }

		//重新装填数据
		if ($datas->statusCode == 0)
		{
		}

		return $datas;
	}


	/**
	 * 获取聊天记录
	 *
	 * @param appId    String    必选    应用Id
	 * @param String    可选    时间（暂时无效，预留）
	 * @param String    可选    时间类型，天：day，周：weekly
	 */
	function msgRecordsNew($app_id, $date = null, $time_type = 'weekly')
	{
		//主帐号鉴权信息验证，对必选参数进行判空。
		$auth = $this->accAuth();
		if ($auth != "")
		{
			return $auth;
		}

		// 拼接请求包体
		if ($this->BodyType == "json")
		{
		}
		else
		{
			$body = "<?xml version=\"1.0\" encoding=\"UTF-8\"?><Request>";
			$body = $body . "<appId>" . $app_id . "</appId>";

			if ($time_type)
			{
				$body = $body . "<timeType>" . $time_type . "</timeType>";
			}

			$body = $body . "</Request>";
		}

		$this->showlog("request body = " . $body);
		// 大写的sig参数
		$sig = strtoupper(md5($this->AccountSid . $this->AccountToken . $this->Batch));

		// 生成请求URL
		$url = "https://$this->ServerIP:$this->ServerPort/$this->SoftVersion/Accounts/$this->AccountSid/MsgRecordsNew?sig=$sig";

		$this->showlog("request url = " . $url);
		// 生成授权：主帐户Id + 英文冒号 + 时间戳。
		$authen = base64_encode($this->AccountSid . ":" . $this->Batch);

		// 生成包头
		$header = array(
			"Accept:application/$this->BodyType",
			"Content-Type:application/$this->BodyType;charset=utf-8",
			"Authorization:$authen"
		);

		// 发送请求
		$result = $this->curl_post($url, $body, $header);
		$this->showlog("response body = " . $result);
		if ($this->BodyType == "json")
		{
			//JSON格式
			$datas = json_decode($result);
		}
		else
		{ //xml格式
			$datas = simplexml_load_string(trim($result, " \t\n\r"));
		}

		fb($result);


		$data_rows = array();

		//重新装填数据
		if ($datas->statusCode == 0)
		{
			// 获取返回信息
			//下载文件
			$down_url = $datas->downUrl;

			$msg_data = APP_PATH . '/data/msg_data/';
			if (!file_exists($msg_data))
			{
				mkdir($msg_data);
			}

			$ext = pathinfo($down_url);
			$date_str = date('Y-m-d', strtotime('-1 day')) . '_' . $time_type;

			//$ext['basename']
			//$ext['extension']

			$data_file_name = $msg_data . DIRECTORY_SEPARATOR . $date_str . '.' . $ext['extension'];

			if (!is_file($data_file_name))
			{
				$down_data = file_get_contents($down_url);

				if ($down_data)
				{

					file_put_contents($data_file_name, $down_data);
				}
			}

			//判断是否已经导入?

			//解压文件
			$Archive_Zip = new Archive_Zip($data_file_name);
			$contents    = $Archive_Zip->listContent();
			fb($contents);

			$csv_file_name = $contents[0]['filename'];

			$Archive_Zip->extract(array('add_path' => $msg_data));

			//导入数据入库,读取csv
			$csv_file_name = $msg_data . DIRECTORY_SEPARATOR . $contents[0]['filename'];

			$handle = fopen($csv_file_name, 'r');

			/*
			$data['msg_log_id']             = $_REQUEST['msg_log_id']         ; // ID
			$data['app_id_sender']          = $_REQUEST['app_id_sender']      ; // 发送者id
			$data['msg_sender']             = $_REQUEST['msg_sender']         ; // 发送者名称
			$data['app_id_receiver']        = $_REQUEST['app_id_receiver']    ; // 接受者id
			$data['msg_receiver']           = $_REQUEST['msg_receiver']       ; // 接收者名称
			$data['device_type']            = $_REQUEST['device_type']        ; //
			$data['msg_len']                = $_REQUEST['msg_len']            ; //
			$data['msg_type']               = $_REQUEST['msg_type']           ; //
			$data['msg_content']            = $_REQUEST['msg_content']        ; //
			$data['msg_file_url']           = $_REQUEST['msg_file_url']       ; //
			$data['msg_file_name']          = $_REQUEST['msg_file_name']      ; //
			$data['group_id']               = $_REQUEST['group_id']           ; //
			$data['msg_id']                 = $_REQUEST['msg_id']             ; //
			$data['msg_file_size']          = $_REQUEST['msg_file_size']      ; //
			$data['date_created']           = $_REQUEST['date_created']       ; // 日期
			$data['msg_domain']             = $_REQUEST['msg_domain']         ; // 说明
			*/

			$msg_table_col = array (
				0 => "app_id_sender",
				1 => "msg_sender",
				2 => "app_id_receiver",
				3 => "msg_receiver",
				4 => "device_type",
				5 => "msg_len",
				6 => "msg_type",
				7 => "msg_content",
				8 => "msg_file_url",
				9 => "msg_file_name",
				10 => "group_id",
				11 => "msg_id",
				12 => "msg_file_size",
				13 => "date_created",
				14 => "msg_domain",
			);

			if ($handle)
			{
				$i= 0;
				while (!feof($handle))
				{
					$buffer = trim(fgets($handle, 4096));
					//echo $buffer;

					if (0 == $i)
					{
					}
					else
					{
						//入库
						if ($buffer)
						{
							$msg_log_row = explode(',', $buffer);
							$data = array();

							foreach ($msg_log_row as $key=>$item)
							{
								$item =  trim($item, " \t\n\r\0\x0B\"'");
								if ($item == '\N')
								{
									$item = '';
								}


								if ($msg_table_col[$key] == 'msg_content')
								{
									$item = base64_decode($item);
								}

								$data[$msg_table_col[$key]] = $item;
							}

							$data_rows[] = $data;
						}
					}

					$i++;
				}

				fclose($handle);
			}
		}
		else
		{
			return false;
		}

		return $data_rows;
	}
}

?>
