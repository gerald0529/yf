<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * @author     Xinze <xinze@live.cn>
 */
class Adv_PageSettingsModel extends Adv_PageSettings
{
	public static $page_color = array(
		"red" => "红色",
		"skyblue" => "天蓝",
		"green" => "绿色",
		"gray" => "蓝色",
		"blue" => "褐色",
		"paleblue" => "黑色",
                "orange"  => "橘色",
	);
	public static $page_statu = array(
		"0" => "不显示",
		"1" => "显示"
	);


	public function listPageSettingsWhere($cond_row = array(), $order_row = array(), $page = 1, $rows = 100)
	{
            
		$page_list = $this->listByWhere($cond_row, $order_row);
		foreach ($page_list['items'] as $key => $value)
		{
			$page_list['items'][$key]['page_colorcha']  = __(self::$page_color[$value["page_color"]]);
			$page_list['items'][$key]['page_statuscha'] = __(self::$page_statu[$value["page_status"]]);
		}
		return $page_list;
	}

	//查询全部模板，以及颜色
	public function layoutColor()
	{
		$data['color']      = self::$page_color;
		$AdvPagelayoutModel = new Adv_PageLayoutModel();
		$data['layout']     = $AdvPagelayoutModel->getByWhere();

		return $data;
	}


	/**
	 * 获取广告页里面的模块内容
	 * @param string 模块id 广告页id
	 * @param $structure 模板内容
	 * @return array
	 */
	public static function getAdpositionlist($page_id, $layout_id, $structure)
	{

		$AdvWidgetBaseModel = new Adv_WidgetBaseModel();
		$AdvWidgetItemModel = new Adv_WidgetItemModel();


		foreach ($structure as $skeys => $structure_block)
		{
			if (is_array($structure_block))
			{
				foreach ($structure_block as $cskeys => $structure_block_child)
				{
					if (empty($structure_block_child["child"]))
					{

						$cond_row = array(
							"page_id" => $page_id,
							"layout_id" => $layout_id,
							"widget_name" => $cskeys
						);
						$selt_con = $AdvWidgetBaseModel->getByWhere($cond_row);

						foreach ($selt_con as $widget_id => $val)
						{
							$array    = array("widget_id" => $widget_id);
							$item_con = $AdvWidgetItemModel->getByWhere($array);
							//var_dump($item_con);exit;
							$structure["layout_structure"][$cskeys]["html"] = $item_con;

						}


					}
					else
					{
						foreach ($structure_block_child["child"] as $childkey => $childhtml)
						{

							$cond_row = array(
								"page_id" => $page_id,
								"layout_id" => $layout_id,
								"widget_name" => $childkey
							);
							$selt_con = $AdvWidgetBaseModel->getByWhere($cond_row);
							foreach ($selt_con as $widget_id => $val)
							{
								$array                                                              = array("widget_id" => $widget_id);
								$item_con                                                           = $AdvWidgetItemModel->getByWhere($array);
								$structure["layout_structure"][$cskeys]["child"][$childkey]["html"] = $item_con;

							}

						}
					}


				}
			}
		}

		return $structure;

	}

	/* 设置页面显示表的html内容
* @param $id 广告页的id
*
*           */

	function set_page_settings_html($id, $type = "index")
	{


		$re       = $this->getOne($id);
		$layoutid = $re['layout_id'];

		$LayoutModel = new Adv_PageLayoutModel();
		// $LayoutModel->sql->setWhere("layout_id",$layoutid);
		//  $layout_row = array("layout_id"=>$layoutid);
		$structure       = $LayoutModel->getOne($layoutid);
		$re['structure'] = $this->getAdpositionlist($id, $layoutid, $structure);
		$AdvWidgetNavModel = new Adv_WidgetNavModel();
		$cond_row         = array(
				"page_id" =>$id,
		);
		$nav = $AdvWidgetNavModel->getByWhere($cond_row);

		$str = "<div class='m frame" . $re['layout_id'] . " " . $re['page_color'] . " '>";
		$str .= ($type == 'wap') ? "" : "<div class='mt fn-clear'><div class='title'><span></span>$re[page_name]</div><div class='tit_nav'>";
		if(!empty($nav)) {
			foreach ($nav as $keys => $vals) {
				$str .= "<a  target='_blank' href='" .$vals['widget_nav_url'] ."'>$vals[widget_nav_name]</a>";
			}
		}
		$str.="</div></div>";
		$str .= "<div class='mc fn-clear'>";

		foreach ($re['structure']['layout_structure'] as $keys => $vals)
		{
			$css = $type == 'index' ? "style='width:" . $vals['style']['width'] . "px;height:" . $vals['style']['height'] . "px'" : "";
			$str .= "<div class='block $keys' $css>";

			if (!empty($vals['child']))
			{

				foreach ($vals['child'] as $k => $v)
				{
					$css = $type == 'index' ? "style='width:" . $v['style']['width'] . "px;height:" . $v['style']['height'] . "px'" : "";
					$str .= "<div class='$k' $css>";
					if ($v['type'] == "ag")
					{

						if (!empty($v['html']))
						{
							foreach ($v['html'] as $ke => $va)
							{


								$str .= "<a target='_blank' href='" . $va['item_url'] . "'>";
								$str .= "<img width='" . $v['style']['width'] . "' height='" . $v['style']['height'] . "' title ='" . $va['item_name'] . "'  src='" . $va['item_img_url'] . "'>";
								$str .= "</a>";
							}
						}
					}
					elseif ($v['type'] == "ad")
					{
						if (!empty($v['html']))
						{
							$str .= "<div class='blueberry'>";
							$str .= "<ul class='slides'>";
							foreach ($v['html'] as $ke => $va)
							{

								$str .= "<li>";
								$str .= "<a target='_blank' href='" . $va['item_url'] . "'>";
								$str .= "<img width='" . $v['style']['width'] . "' height='" . $v['style']['height'] . "' title ='" . $va['item_name'] . "'  src='" . $va['item_img_url'] . "'>";
								$str .= "</a>";
								$str .= "</li>";
							}
							$str .= "</ul>";
							$str .= "</div>";
						}

					}
					else
					{
						$str .= "<ul class='fn-clear'>";
						if (!empty($v['html']))
						{
							foreach ($v['html'] as $ke => $va)
							{
								$str .= "<li>";
								$str .= "<a target='_blank' href='" . $va['item_url'] . "'>$va[item_name]</a>";
								$str .= "</li>";
							}
						}
						$str .= "</ul>";
					}
					$str .= "</div>";
				}

			}
			else
			{
				if ($vals['type'] == "ad")
				{
					if (!empty($vals['html']))
					{
						$str .= "<div class='blueberry'>";
						$str .= "<ul class='slides'>";
						foreach ($vals['html'] as $ke => $va)
						{
							// $click_url = Yf_Registry::get('base_url') . '/advertisement.php?ctl=Message_Adhtml&met=click&item_id='.$va['item_id'].'&url=http://' . urlencode($va['item_url']);
							$str .= "<li>";
							$str .= "<a target='_blank' href='" . $va['item_url'] . "'>";
							$str .= "<img width='" . $vals['style']['width'] . "' height='" . $vals['style']['height'] . "' title ='" . $va['item_name'] . "'  src='" . $va['item_img_url'] . "'>";
							$str .= "</a>";
							$str .= "</li>";
						}
						$str .= "</ul>";
						$str .= "</div>";
					}
				}
				else
				{
					if (!empty($vals['html']))
					{
						foreach ($vals['html'] as $ke => $va)
						{
							// $click_url = Yf_Registry::get('base_url') . '/advertisement.php?ctl=Message_Adhtml&met=click&item_id='.$va['item_id'].'&url=http://' . urlencode($va['item_url']);
							$str .= "<a target='_blank' href='" . $va['item_url'] . "'>";
							$str .= "<img width='" . $vals['style']['width'] . "' height='" . $vals['style']['height'] . "' title ='" . $va['item_name'] . "'  src='" . $va['item_img_url'] . "'>";
							$str .= "</a>";
						}
					}
				}

			}
			$str .= "</div>";
		}

		$str .= "</div></div>";

		$data["page_html"] = $str;
		$editpage          = $this->editPageSettings($id, $data);


	}


}

?>