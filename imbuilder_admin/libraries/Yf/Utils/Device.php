<?php

class Yf_Utils_Device
{
	public static function isMobile()
	{
		// 脑残法，判断手机发送的客户端标志,兼容性有待提高
		if (isset($_SERVER['HTTP_USER_AGENT']))
		{
			$client_keywords = Array(
				'240x320',
				'acer',
				'acoon',
				'acs-',
				'abacho',
				'ahong',
				'airness',
				'alcatel',
				'amoi',
				'android',
				'anywhereyougo.com',
				'applewebkit/525',
				'applewebkit/532',
				'asus',
				'audio',
				'au-mic',
				'avantogo',
				'becker',
				'benq',
				'bilbo',
				'bird',
				'blackberry',
				'blazer',
				'bleu',
				'cdm-',
				'compal',
				'coolpad',
				'danger',
				'dbtel',
				'dopod',
				'elaine',
				'eric',
				'etouch',
				'fly ',
				'fly_',
				'fly-',
				'go.web',
				'goodaccess',
				'gradiente',
				'grundig',
				'haier',
				'hedy',
				'hitachi',
				'htc',
				'huawei',
				'hutchison',
				'inno',
				'ipad',
				'ipaq',
				'ipod',
				'jbrowser',
				'kddi',
				'kgt',
				'kwc',
				'lenovo',
				'lg ',
				'lg2',
				'lg3',
				'lg4',
				'lg5',
				'lg7',
				'lg8',
				'lg9',
				'lg-',
				'lge-',
				'lge9',
				'longcos',
				'maemo',
				'mercator',
				'meridian',
				'micromax',
				'midp',
				'mini',
				'mitsu',
				'mmm',
				'mmp',
				'mobi',
				'mot-',
				'moto',
				'nec-',
				'netfront',
				'newgen',
				'nexian',
				'nf-browser',
				'nintendo',
				'nitro',
				'nokia',
				'nook',
				'novarra',
				'obigo',
				'palm',
				'panasonic',
				'pantech',
				'philips',
				'phone',
				'pg-',
				'playstation',
				'pocket',
				'pt-',
				'qc-',
				'qtek',
				'rover',
				'sagem',
				'sama',
				'samu',
				'sanyo',
				'samsung',
				'sch-',
				'scooter',
				'sec-',
				'sendo',
				'sgh-',
				'sharp',
				'siemens',
				'sie-',
				'softbank',
				'sony',
				'spice',
				'sprint',
				'spv',
				'symbian',
				'tablet',
				'talkabout',
				'tcl-',
				'teleca',
				'telit',
				'tianyu',
				'tim-',
				'toshiba',
				'tsm',
				'up.browser',
				'utec',
				'utstar',
				'verykool',
				'virgin',
				'vk-',
				'voda',
				'voxtel',
				'vx',
				'wap',
				'wellco',
				'wig browser',
				'wii',
				'windows ce',
				'wireless',
				'xda',
				'xde',
				'zte'
			);

			// 从HTTP_USER_AGENT中查找手机浏览器的关键字
			if (preg_match("/(" . implode('|', $client_keywords) . ")/i", strtolower($_SERVER['HTTP_USER_AGENT'])))
			{
				return true;
			}
		}

		// 协议法，因为有可能不准确，放到最后判断
		if (isset($_SERVER['HTTP_ACCEPT']))
		{
			// 如果只支持wml并且不支持html那一定是移动设备
			// 如果支持wml和html但是wml在html之前则是移动设备
			if ((strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') !== false) && (strpos($_SERVER['HTTP_ACCEPT'], 'text/html') === false || (strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') < strpos($_SERVER['HTTP_ACCEPT'], 'text/html'))))
			{
				return true;
			}
		}
		
		return false;
	}
}

?>