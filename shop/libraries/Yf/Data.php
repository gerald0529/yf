<?php
/**
 * Ajax数据格式化类
 * 
 * 用来生成Ajax需要的JSON数据。
 * 
 * @category   Framework
 * @package	Model
 * @author	 Xinze <xinze@live.cn>
 * @copyright  Copyright (c) 2010, 黄新泽
 * @version	1.0
 * @todo	   
 */
class Yf_Data
{
	/**
	 * 控制器返回的数据,For ajax data format.
	 * 
	 * @access public
	 * @var array
	 */
	//public $dataRows = array('h'=>array('i'=>0, 'e'=>false, 'm'=>''),'data'=>'');
	public $dataRows = array();

	public function __construct()
	{
		if (array_key_exists('id', $_REQUEST))
		{
			//$this->dataRows['h']['i'] = trim($_REQUEST['id']);
		}


	}

	/*
	//废弃
	public function buildXML($id, $data, $rows=1, $errorMessage='发生错误', $type='object', $operateType='object')
	{
		$outString=	
					'<?xml version="1.0" encoding="UTF-8" ?>
						<ajax-response>
							<response type="'.$type.'" id="'.$id.'"	 totalRows="'.$rows.'" errorMessage="'.$errorMessage.'"	operateType="'.$operateType.'">
								'.$data.'
							</response>
						</ajax-response>';
		return $outString ;
	}

	//废弃
	public function printXML($row=false)
	{
		header('Content-type: text/xml');
		
		if (!$row)
		{
			$row = $this->virtualRow;
		}

		$data	= convertArrayToProperties($row,'arytest',false,false);
		$outXML	= $this->buildXML($this->id, $data,$this->totalRows, $this->errorMessage);
		echo $outXML ;
	}

	//废弃
	public function printHTML($row=false)
	{
		header('Content-type: text/xml');
		
		if (!$row)
		{
			$row = $this->virtualRow;
		}

		$outXML	= $this->buildXML($this->id, $row,$this->totalRows,	$this->errorMessage, 'object', 'element');

		echo $outXML ;
	}

	//废弃
	public function buildXMLCDATA($id,	$data, $rows=1,	$errorMessage='发生错误', $type='object')
	{
		$outString=	
					'<?xml version="1.0" encoding="UTF-8" ?>
						<ajax-response>
							<response type="'.$type.'" id="'.$id.'"	 totalRows="'.$rows.'" errorMessage="'.$errorMessage.'">
								<![CDATA['.$data.']]>
							</response>
						</ajax-response>';
		return $outString ;
	}

	//废弃
	public function printXMLCDATA($row=false)
	{
		header('Content-type: text/xml');
		
		if (!$row)
		{
			$row = $this->virtualRow;
		}

		$data	= convertArrayToProperties($row,'arytest',false,false);
		$outXML	= $this->buildXMLCDATA($this->id, $data,$this->totalRows, $this->errorMessage);
		echo $outXML ;
	}
	*/

	/**
	 * 返回请求数据
	 *
	 * @return   string $json   返回数据
	 * @access   public
	 */
	public function getJSON()
	{
		header('Content-type: application/json');

		return encode_json($this->getDataRows()) ;
	}

	/**
	 * 返回请求数据
	 *
	 * @return   string $json   返回数据
	 * @access   public
	 */
	public function getDataRows()
	{
		if (DEBUG)
		{
			if ($d = trim(ob_get_contents()))
			{
				$item = array(-999, $d);
				$this->dataRows['d'] = $item;
			}
			
		}
		
		if ('cli' != SAPI)
		{
			ob_end_clean();
			ob_start();
		}

		return $this->dataRows;
	}

	/**
	 * 返回请求数据
	 *
	 * @return string   $json   返回数据
	 * @access public
	 */
	public function printJSON()
	{
		echo $this->getJSON();
	}

	/**
	 * 设置结果状态
	 *
	 * @return string   $json   返回数据
	 * @access public
	 */
	public function setState($state = true)
	{
	   $this->dataRows['h']['e'] = $state;
	}

	/**
	 * 设置出错信息
	 *$code int 0普通出错提示,1:缺银两,2:缺黄金
	 * @return string   $json   返回数据
	 * @access public
	 */
	public function setError($msg, $data=array(), $status=250)
	{
		$item = array('cmd_id'=>-1, 'status'=>$status,  'msg'=>$msg, 'data'=>$data);
		//$this->dataRows[] = $item;
		$this->dataRows = $item;
	}

    public function setApiError($msg)
    {
        exit(encode_json(array("result"=>0,"msg"=>$msg)));
    }

	/**
	 * 设置出错信息
	 *
	 * @return string   $json   返回数据
	 * @access public
	 */
	public function addMsg($cmd_id=-1,$code=0, $msg='')
	{
		$item = array('cmd_id'=>$cmd_id, 'code'=>$code, 'msg'=>$msg);
		$this->dataRows[] = $item;
	}

	/**
	 * 设置返回数据
	 *
	 * @return string   $json   返回数据
	 * @access public
	 */
	public function addBody($cmd_id, $data_rows=null, $msg='success', $status=200)
	{
		//fb($data_rows);
		$item = array();

		if(is_array($data_rows))
		{
            $body_data_rows = array();
            //$body_data_rows = $data_rows;

            $body_data_rows['status'] = $status;
			$body_data_rows['msg']    = $msg;
			$body_data_rows['data']   = $data_rows;

			//$item = array_merge($item, self::typeConversion($data_rows));
			//$item = array_merge($item, $data_rows);
            $pro_data_rows = array('cmd_id'=>$cmd_id) + $body_data_rows;
			//array_unshift($data_rows, $cmd_id);

			//$this->dataRows[] = $pro_data_rows;
			$this->dataRows = $pro_data_rows;
		}
        else
        {
            throw new Exception("协议内容有误：" . $cmd_id);
        }
	}
	
	/**
	 * 类型转换
	 *
	 * @param	
	 * @return	
	 * @access	public
	 * @see	
	 */
	public static function typeConversion($data)
	{
		$new_data = '';

		if(is_numeric($data))
		{
            $new_data = intval($data);
            /*
			if (is_true_int($data))
			{
				$new_data = intval($data);
			}
			else
			{
				//if(is_true_float($data))
				$new_data = floatval($data);
			}
            */
		}
		elseif (is_string($data))
		{
			$new_data = $data;
		}
		elseif (is_bool($data))
		{
			$new_data = $data;
		}
		else
		{
			$new_data = $data;
		}

		return $new_data;
	}

	/**
	 * convert dic to array
	 *
	 * @param	
	 * @return	
	 * @access	public
	 * @see	
	 */
	public static function array2ValuesRecursive(array $data)
	{
		$new_data = array();

		if(is_array($data))
		{
			foreach($data as $key=>$data_child)
			{
				if(is_array($data_child))
				{
					$data_temp = self::array2ValuesRecursive($data_child);

					array_push($new_data, $data_temp);
				}
				else
				{
					if('user_name' === $key)
					{
						array_push($new_data, $data);
					}
					else
					{
						array_push($new_data, self::typeConversion($data_child));
					}
				}
			}
		}
		else
		{
			$new_data = self::typeConversion($data);
		}

		return $new_data;
	}

	//msgpack封装下，便于其他地方调用
	public static function msgEncode($item)
	{
		if (function_exists('msgpack_pack'))
		{
			$item_data = msgpack_pack($item);
		}
		else
		{
			$item_data = MsgPack_Coder::encode($item);
		}

		return $item_data;
	}

	public static function msgDecode($item) 
	{
		if (function_exists('msgpack_unpack')) 
		{
			$item_data = msgpack_unpack($item);
		}
		else
		{
			$item_data = MsgPack_Coder::decode($item);
		}

		return $item_data;
	}


	/**
	 * 输出数据
	 *
	 * @return string   $json   返回数据
	 * @access public
	 */
	public static function encodeProtocolData($d, $typ='json')
	{
		if ('json'==$typ || 'msgpack'==$typ || 'json'==$typ)
		{
			if ('json' == $typ)
			{
				if (isset($_REQUEST['debug']) && 1 == $_REQUEST['debug'])
				{
					return var_dump($d);
				}
				else
				{
					header('Content-type: application/json');
					/*
					dataType: "jsonp",
			    jsonpCallback:"yf",
					*/
					$callback = $_GET['callback'];
					if($callback){
						$start = $callback.'(';
						$end = ')';
					}
					return $start.encode_json(conversion_array_type($d)).$end;
				}
			}
			else
			{
				/*
				$d = array
				(
					array(-500, "abcdefg", "hijklmn"),
					array(-900, "fda", "fdafda")
					array(-900, "abcdefg", "fd")
					array(-800, "ffd", "hijklmn")
					array(-900, "abcdefg", "hijklmn")
					
				);
				$d = array
				(
					array(-900, "abcdefg", "hijklmn"),
					array(-900, "黄新泽", "黄新泽发达双方都"),
					array(-900, "abcdefg", "hijklmn")
				);
				*/
				 
				$protocol_data = "";

				foreach ($d as $key=>$item)
				{
					$item_data = "";

					$item_data = Yf_Data::msgEncode(self::array2ValuesRecursive($item));

					$protocol_item_head = pack("sS", $item['cmd_id'], strlen($item_data));

					$protocol_data .=  $protocol_item_head . $item_data;
				}

				return $protocol_data;
			}
		}
		else
		{

			$config_rewrite = array();

			try
			{
				$config_rewrite = Yf_Registry::get('config_rewrite');

				if ($config_rewrite['open'])
				{
					$mod = $config_rewrite['mod'];
					$d =  preg_replace($config_rewrite[$mod]['search'], $config_rewrite[$mod]['replace'], $d);
				}
			}
			catch(Exception $e)
			{
			}
            
			
			$base_url = Yf_Registry::get('base_url');
			$static_s_row = array();
			$static_r_row = array();
            //return $d;
            
            $html = $d;
			
            global $themes_name;
            
            $themes = '/static/' . $themes_name . '/';
            
            $doc = new \DOMDocument();
            @$doc->loadHTML($html);
            $xpath = new \DOMXPath($doc);

            $doc->normalizeDocument();
            
            $scriptnodes = $xpath->query('//script');
            
            foreach($scriptnodes as $node){
                if(true || $node->hasAttribute("type") && strtolower($node->getAttribute("type"))=='text/javascript' ){
                    if($node->hasAttribute("src")){
                        $path = str_replace($base_url, '.', $node->getAttribute("src"));
                        
                        if (!file_exists($path))
                        {
                            $static_s_row[] = $node->getAttribute("src");
                            $static_r_row[] = str_replace($themes, '/static/default/', $node->getAttribute("src"));
                        }
                        
                        continue;
                    }
                }
            }
            
            $doc->normalizeDocument();
            $cssnodes = $xpath->query('//link');

            foreach($cssnodes as $node){
                if(($node->hasAttribute("rel") && strtolower($node->getAttribute("rel"))=='stylesheet') || ($node->hasAttribute("type") && strtolower($node->getAttribute("type"))=='text/css') ){
                    if($node->hasAttribute("href")){
                        $path = str_replace($base_url, '.', $node->getAttribute("href"));
    
                        if (!file_exists($path))
                        {
                            $static_s_row[] = $node->getAttribute("href");
                            $static_r_row[] = str_replace($themes, '/static/default/', $node->getAttribute("href"));
                        }
                        continue;
                    }
                }
            }
            
            $d = str_replace($static_s_row, $static_r_row, $d);
            
            return $d;

		}
	}
}

function wpjam_minify_html($html) {
	return preg_replace(
		$search = array(
			'/\>[^\S ]+/s',  // 删除标签后面空格
			'/[^\S ]+\</s',  // 删除标签前面的空格
			'/(\s)+/s'       // 将多个空格合并成一个
		),
		array(
			'>',
			'<',
			'\\1'
		),
		$html
	);
}

/**
 * 压缩html : 清除换行符,清除制表符,去掉注释标记
 * @param $string
 * @return 压缩后的$string
 * */
function compress_html($string) {
	$string = str_replace("\r\n", '', $string); //清除换行符
	$string = str_replace("\n", '', $string); //清除换行符
	$string = str_replace("\t", '', $string); //清除制表符
	$pattern = array (
		"/> *([^ ]*) *</", //去掉注释标记
		"/[\s]+/",
		"/<!--[^!]*-->/",
		"/\" /",
		"/ \"/",
		"'/\*[^*]*\*/'"
	);
	$replace = array (
		">\\1<",
		" ",
		"",
		"\"",
		"\"",
		""
	);
	return preg_replace($pattern, $replace, $string);
}
?>