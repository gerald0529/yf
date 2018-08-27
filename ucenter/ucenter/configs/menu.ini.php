<?php
$buyer_menu = array(
	
	10000 => array(
		'menu_id' => '10000',
		'menu_parent_id' => '-1',
		'menu_name' => '首页',
		'menu_icon' => '',
		'menu_url_ctl' => 'Buyer_Index',
		'menu_url_met' => 'index',
		'menu_url_parem' => '',
		'sub' => array(
					100004 => array(
						'menu_id' => '100004',
						'menu_parent_id' => '10000',
						'menu_name' => '会员中心',
						'menu_icon' => '',
						'menu_url_ctl' => 'User',
						'menu_url_met' => 'getUserInfo',
						'menu_url_parem' => '',
						'sub' => array(
									1000041 => array(
										'menu_id' => '1000041',
										'menu_parent_id' => '100004',
										'menu_name' => '账号信息',
										'menu_icon' => '',
										'menu_url_ctl' => 'User',
										'menu_url_met' => 'getUserInfo',
										'menu_url_parem' => '',
										'sub' => array(
													10000411 => array(
														'menu_id' => '10000411',
														'menu_parent_id' => '1000041',
														'menu_name' => '基本信息',
														'menu_icon' => '',
														'menu_url_ctl' => 'User',
														'menu_url_met' => 'getUserInfo',
														'menu_url_parem' => '',
														
													)
										),
									),
									1000042 => array(
										'menu_id' => '1000042',
										'menu_parent_id' => '100004',
										'menu_name' => '头像照片',
										'menu_icon' => '',
										'menu_url_ctl' => 'User',
										'menu_url_met' => 'getUserImg',
										'menu_url_parem' => '',
										'sub' => array(
											10000411 => array(
												'menu_id' => '10000411',
												'menu_parent_id' => '1000042',
												'menu_name' => '头像照片',
												'menu_icon' => '',
												'menu_url_ctl' => 'User',
												'menu_url_met' => 'getUserImg',
												'menu_url_parem' => '',
											),
										),
									),
									1000043 => array(
										'menu_id' => '1000043',
										'menu_parent_id' => '100004',
										'menu_name' => '账户安全',
										'menu_icon' => '',
										'menu_url_ctl' => 'User',
										'menu_url_met' => 'security',
										'menu_url_parem' => '',
										'sub' => array(
											10000411 => array(
												'menu_id' => '10000431',
												'menu_parent_id' => '1000043',
												'menu_name' => '账户安全',
												'menu_icon' => '',
												'menu_url_ctl' => 'User',
												'menu_url_met' => 'security',
												'menu_url_parem' => '',
											),
										),
									),
									1000044 => array(
										'menu_id' => '1000044',
										'menu_parent_id' => '100004',
										'menu_name' => '修改登录密码',
										'menu_icon' => '',
										'menu_url_ctl' => 'User',
										'menu_url_met' => 'passwd',
										'menu_url_parem' => '',
										'sub' => array(
													10000411 => array(
														'menu_id' => '10000441',
														'menu_parent_id' => '1000044',
														'menu_name' => '修改登录密码',
														'menu_icon' => '',
														'menu_url_ctl' => 'User',
														'menu_url_met' => 'passwd',
														'menu_url_parem' => '',				
													),
										),
									),
									1000045 => array(
										'menu_id' => '1000045',
										'menu_parent_id' => '100004',
										'menu_name' => '账号绑定',
										'menu_icon' => '',
										'menu_url_ctl' => 'User',
										'menu_url_met' => 'bindAccount',
										'menu_url_parem' => '',
										'sub' => array(
													10000411 => array(
														'menu_id' => '10000451',
														'menu_parent_id' => '1000045',
														'menu_name' => '账号绑定',
														'menu_icon' => '',
														'menu_url_ctl' => 'User',
														'menu_url_met' => 'bindAccount',
														'menu_url_parem' => '',
													),
										),
									)
						),
					)
				),
	),
		
);


//行
global $buyer_menu_rows;
$buyer_menu_rows = array();


function get_menu_rows($buyer_menu, &$buyer_menu_rows)
{
	foreach ($buyer_menu as $id=>$item)
	{
		if (isset($item['sub']) && $item['sub'])
		{
			get_menu_rows($item['sub'], $buyer_menu_rows);

			unset($item['sub']);
			$buyer_menu_rows[$id] = $item;
		}
		else
		{
			$buyer_menu_rows[$id] = $item;
		}

	}
}

 get_menu_rows($buyer_menu, $buyer_menu_rows);


$ctl       = request_string('ctl');
$met       = request_string('met');
$level_row = array();

//echo $ctl, "\n",	$met;
//echo "\n";
function get_menu_id($buyer_menu, $level = 0, &$level_row, $ctl, $met)
{
	global $buyer_menu_rows;

	$level++;

	foreach ($buyer_menu as $menu_row)
	{
		if ($menu_row['menu_url_ctl'] == $ctl && $menu_row['menu_url_met'] == $met)
		{
			$level_row[$ctl][$met][$level]     = $menu_row['menu_id'];
			$level_row[$ctl][$met][$level - 1] = $menu_row['menu_parent_id'];

			//向上查找一次
			if (isset($buyer_menu_rows[$menu_row['menu_parent_id']]))
			{
				$level_row[$ctl][$met][$level - 2] = $buyer_menu_rows[$menu_row['menu_parent_id']]['menu_parent_id'];
			}
			//向上查再找一次
			 // if (isset($buyer_menu_rows[$buyer_menu_rows[$menu_row['menu_parent_id']]['menu_parent_id']]['menu_parent_id']))
			// {
				// $level_row[$ctl][$met][$level - 3] = $buyer_menu_rows[$buyer_menu_rows[$menu_row['menu_parent_id']]['menu_parent_id']]['menu_parent_id'];
			// } 
			
			
		}
		else
		{
		}

		if (isset($menu_row['sub']))
		{
			get_menu_id($menu_row['sub'], $level, $level_row, $ctl, $met);
		}
	}
}

function get_menu_url_map($buyer_menu, &$level_row, $buyer_menu_ori)
{
	foreach ($buyer_menu as $menu_row)
	{
		get_menu_id($buyer_menu, 0, $level_row, $menu_row['menu_url_ctl'], $buyer_row['menu_url_met']);

		if (isset($menu_row['sub']))
		{
			get_menu_url_map($menu_row['sub'], $level_row, $buyer_menu_ori);
		}
	}
}

//缓存点亮规则
//get_menu_url_map($user_menu, $level_row, $user_menu);

//计算当前高亮
get_menu_id($buyer_menu, 0, $level_row, $ctl, $met);
$level_row = $level_row[$ctl][$met];
$level_row[1] = 10000;
return $buyer_menu;
?>