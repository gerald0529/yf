<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * Api接口, 让App等调用
 *
 *
 * @category   Game
 * @package    User
 * @author     Xinze <xinze@live.cn>
 * @copyright  Copyright (c) 2015, 黄新泽
 * @version    1.0
 * @todo
 */
class Api_Goods_BrandCtl extends Yf_AppController
{
	/*
	 * @mars
	 * 品牌列表
	 */
	public function listBrand($return = false)
	{
		if (request_int('page'))
		{
			$page = request_int('page');
		}
		else
		{
			$page = 0;
		}
		if (request_int('rows'))
		{
			$rows = request_int('rows');
		}
		else
		{
			$rows = 99999;
		}
		$skey                 = request_string('skey');
		$cat_id				  = request_int('cat_id');
		$Goods_BrandModel     = new Goods_BrandModel();
		$Goods_TypeBrandModel = new Goods_TypeBrandModel();
		$cond_row             = array();
		if (request_int('uncheck'))
		{
			$cond_row['brand_enable'] = 0;
		}
		else
		{
			$cond_row['brand_enable'] = 1;
		}
		if ($skey)
		{
			$cond_row['brand_name:like'] = '%' . $skey . '%';
		}

		if($cat_id&&$cat_id!=-1)
		{
			$cond_row['cat_id'] = $cat_id;
		}

		$data_brand = $Goods_BrandModel->getBrandList($cond_row, array(), $page, $rows);
		$rows       = $data_brand['items'];
		unset($data_brand['items']);

		if (!empty($rows))
		{
			foreach ($rows as $key => $value)
			{
				$brand_id                      = $value['brand_id'];
				$rows[$key]['id']              = $brand_id;
				$data_type                     = $Goods_TypeBrandModel->getByWhere(array('brand_id' => $brand_id));
			}
			$msg    = __('success');
			$status = 200;
		}
		else
		{
			$msg    = __('没有数据');
			$status = 250;
		}
		if ($return)
		{
			return $rows;
		}
		else
		{
			$data_brand['rows'] = $rows;
			$this->data->addBody(-140, $data_brand, $msg, $status);
		}
	}

	/*
	 * 删除品牌
	 */
	public function remove()
	{
		$Goods_BrandModel     = new Goods_BrandModel();
		$Goods_TypeBrandModel = new Goods_TypeBrandModel();

		$brand_id = request_int('brand_id');
		if ($brand_id)
		{
			$flag = $Goods_BrandModel->removeBrand($brand_id);

			if ($flag)
			{
				$data_type = $Goods_TypeBrandModel->getByWhere(array('brand_id' => $brand_id));
				if ($data_type)
				{
					foreach ($data_type as $key => $value)
					{
						$type_brand_id = $value['type_brand_id'];
						$flags         = $Goods_TypeBrandModel->removeTypeBrand($type_brand_id);
					}
				}
			}
		}
		if ($flag)
		{
			$msg    = __('success');
			$status = 200;
		}
		else
		{
			$msg    = __('failure');
			$status = 250;
		}
		$data['id'] = $brand_id;
		$this->data->addBody(-140, $data, $msg, $status);
	}

	/**
	 * 获取品牌名首字母 hp
	 * @param $str
	 * @return string
	 */
	function getFirstCharter($str)
	{
		$pattern = '/[a-zA-Z0-9]/';//匹配品牌名字符串的首字母
		$a = $str[0];
		$status = preg_match($pattern, $a);
		if($status)
		{
			$data = $str[0];
		}
		else
		{
			if(empty($str)){return '';}
			$fchar=ord($str{0});
			if($fchar>=ord('A')&&$fchar<=ord('z')) return strtoupper($str{0});
			$s1=iconv('UTF-8','GBK//IGNORE',$str);
			$s2=iconv('GBK','UTF-8//IGNORE',$s1);
			$s=$s2==$str?$s1:$str;
			$asc=ord($s{0})*256+ord($s{1})-65536;
			if($asc>=-20319&&$asc<=-20284) $data = 'A';
			if($asc>=-20283&&$asc<=-19776) $data = 'B';
			if($asc>=-19775&&$asc<=-19219) $data = 'C';
			if($asc>=-19218&&$asc<=-18711) $data = 'D';
			if($asc>=-18710&&$asc<=-18527) $data = 'E';
			if($asc>=-18526&&$asc<=-18240) $data = 'F';
			if($asc>=-18239&&$asc<=-17923) $data = 'G';
			if($asc>=-17922&&$asc<=-17418) $data = 'H';
			if($asc>=-17417&&$asc<=-16475) $data = 'J';
			if($asc>=-16474&&$asc<=-16213) $data = 'K';
			if($asc>=-16212&&$asc<=-15641) $data = 'L';
			if($asc>=-15640&&$asc<=-15166) $data = 'M';
			if($asc>=-15165&&$asc<=-14923) $data = 'N';
			if($asc>=-14922&&$asc<=-14915) $data = 'O';
			if($asc>=-14914&&$asc<=-14631) $data = 'P';
			if($asc>=-14630&&$asc<=-14150) $data = 'Q';
			if($asc>=-14149&&$asc<=-14091) $data = 'R';
			if($asc>=-14090&&$asc<=-13319) $data = 'S';
			if($asc>=-13318&&$asc<=-12839) $data = 'T';
			if($asc>=-12838&&$asc<=-12557) $data = 'W';
			if($asc>=-12556&&$asc<=-11848) $data = 'X';
			if($asc>=-11847&&$asc<=-11056) $data = 'Y';
			if($asc>=-11055&&$asc<=-10247) $data = 'Z';
		}
		return $data;
	}

	/*
	 * 新增品牌
	 */
	public function add()
	{
	    $brand_name                 = request_string('brand_name');
		$Goods_BrandModel           = new Goods_BrandModel();
		$goodsTypeBrandModel        = new Goods_TypeBrandModel();
		$data                       = array();
		$data['brand_name']         = $brand_name;
		$data['brand_initial']      = $this->getFirstCharter($brand_name); // 首字母
		$data['brand_pic']          = request_string('brand_pic');
		$data['brand_show_type']    = request_int('brand_show_type');
		$data['brand_recommend']    = request_int('brand_recommend');
		$data['brand_enable']       = request_int('brand_enable');
		$data['cat_id']             = request_int('cat_id');
		$data['brand_displayorder'] = request_int('brand_displayorder');
		$data['brand_pic']          = request_string('brand_pic');
		$flag                       = $Goods_BrandModel->addBrand($data, true);
		if ($flag)
		{
			$msg    = 'success';
			$status = 200;
		}
		else
		{
			$msg    = 'failure';
			$status = 250;
		}

		$data['id']       = $flag;
		$data['brand_id'] = $flag;
		$this->data->addBody(-140, $data, $data['brand_initial'], $status);
	}

	public function getBrand()
	{
		$Goods_BrandModel = new Goods_BrandModel();
		$brand_id         = request_int('brand_id');
		$data_brand       = $Goods_BrandModel->getByWhere(array('brand_id' => $brand_id));
		$data             = $data_brand[$brand_id];
		if ($data)
		{
			$msg    = 'success';
			$status = 200;
		}
		else
		{
			$msg    = 'failure';
			$status = 250;
		}
		$this->data->addBody(-140, $data, $msg, $status);
	}

	public function edit()
	{
		$Goods_BrandModel = new Goods_BrandModel();
		$id               = request_int('id');

		$data                    = array();
		$data['brand_name']      = request_string('brand_name');
        $data['brand_initial']   = $this->getFirstCharter(request_string('brand_name')); // 首字母
		$data['brand_pic']       = request_string('brand_pic');
		$data['brand_show_type'] = request_int('brand_show_type');
		$data['brand_recommend'] = request_int('brand_recommend');
		$data['brand_enable']    = request_int('brand_enable');
		if (request_int('cat_id') != -1)
		{
			$data['cat_id'] = request_int('cat_id');
		}
		$data['brand_displayorder'] = request_int('brand_displayorder');
		$data['brand_pic']          = request_string('brand_pic');

		$flag = $Goods_BrandModel->editBrand($id, $data, false);

		if ($flag === false)
		{
			$msg    = __('failure');
			$status = 250;
		}
		else
		{
			$msg    = __('success');
			$status = 200;
		}
		$data['id']              = $id;
		$data['brand_id']        = $id;
		$data['brand_recommend'] = Goods_BrandModel::$recommend_content[request_int('brand_recommend')];
		$data['brand_show_type'] = Goods_BrandModel::$show_content[request_int('brand_show_type')];
		$this->data->addBody(-140, $data, $msg, $status);
	}

	/*
	 * 取得所有品牌
	 */
	public function getBrands()
	{
		$Goods_BrandModel = new Goods_BrandModel();
		$data             = $Goods_BrandModel->getBrandAll();
		if ($data)
		{
			$msg    = __('success');
			$status = 200;
		}
		else
		{
			$msg    = __('failure');
			$status = 250;
		}
		$data = array_values($data);
		$this->data->addBody(-140, $data, $msg, $status);
	}


	//商品品牌导出
	public function getBrandListExcel()
	{
		$con = $this->listBrand(true);
		$tit = array(
			'品牌id',
			'品牌名称',
			'首字母',
			'品牌排序',
			'品牌推荐',
			'展现形式'
		);
		$key = array(
			'brand_id',
			'brand_name',
			'brand_initial',
			'brand_displayorder',
			'brand_recommend',
			'brand_show_type'
		);
		$this->excel("品牌列表", $tit, $con, $key);
	}

	function excel($title, $tit, $con, $key)
	{
		ob_end_clean();
		$objPHPExcel = new PHPExcel();
		$objPHPExcel->getProperties()->setCreator("mall_new");
		$objPHPExcel->getProperties()->setLastModifiedBy("mall_new");
		$objPHPExcel->getProperties()->setTitle($title);
		$objPHPExcel->getProperties()->setSubject($title);
		$objPHPExcel->getProperties()->setDescription($title);
		$objPHPExcel->setActiveSheetIndex(0);
		$objPHPExcel->getActiveSheet()->setTitle($title);

		$letter = array(
			'A',
			'B',
			'C',
			'D',
			'E',
			'F'
		);
		foreach ($tit as $k1 => $v1)
		{
			$objPHPExcel->getActiveSheet()->setCellValue($letter[$k1] . "1", $v1);
		}
		foreach ($con as $k => $v)
		{
			foreach ($key as $k2 => $v2)
			{
				$objPHPExcel->getActiveSheet()->setCellValue($letter[$k2] . ($k + 2), $v[$v2]);
			}
		}
		ob_end_clean();  //清出缓冲区内容,避免出现乱码
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header("Content-Disposition: attachment; filename=\"$title.xls\"");
		header('Cache-Control: max-age=0');
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save('php://output');
		die();
	}

	function check(){

		$Goods_BrandModel = new Goods_BrandModel();

		$ids = request_string('id');
		$id_rows = explode(',',$ids);
		if(!empty($id_rows))
		{
			foreach($id_rows as $key => $value)
			{
				$brand_id = $value;
				$edit_row = array();
				$edit_row['brand_enable'] = $Goods_BrandModel::ENABLE_TRUE;
				$flag = $Goods_BrandModel->editBrand($brand_id, $edit_row);
			}
		}
		$this->data->addBody(-140, $id_rows);
	}
}

?>