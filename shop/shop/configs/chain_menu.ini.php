<?php
$ctl       = request_string('ctl');
$met       = request_string('met');
$act       = request_string("act");
$level_row = array();
$chain_menu = array(
	10000 => array(
		'menu_id' => '10000',
		'menu_parent_id' => '-1',
		'menu_name' => __('门店商品库存'),
		'menu_icon' => '',
		'menu_url_ctl' => 'Chain_Goods',
		'menu_url_met' => 'goods',
		'menu_url_parem' => '',
	),
    11000 => array(
        'menu_id' => '11000',
        'menu_parent_id' => '-1',
        'menu_name' => __('门店取货订单'),
        'menu_icon' => '',
        'menu_url_ctl' => 'Chain_Order',
        'menu_url_met' => 'index',
        'menu_url_parem' => '',
    ),
    12000 => array(
        'menu_id' => '12000',
        'menu_parent_id' => '-1',
        'menu_name' => __('安全退出'),
        'menu_icon' => '',
        'menu_url_ctl' => 'Login',
        'menu_url_met' => 'loginout',
        'menu_url_parem' => '',
    ),

);

//行
global $chain_menu_rows;
$chain_menu_rows = array();


function get_menu_rows($chain_menu, &$chain_menu_rows)
{
	foreach ($chain_menu as $id=>$item)
	{
		if (isset($item['sub']) && $item['sub'])
		{
			get_menu_rows($item['sub'], $chain_menu_rows);

			unset($item['sub']);
			$chain_menu_rows[$id] = $item;
		}
		else
		{
			$chain_menu_rows[$id] = $item;
		}

	}
}

get_menu_rows($chain_menu, $chain_menu_rows);


//$ctl       = request_string('ctl');
//$met       = request_string('met');
//$level_row = array();

//echo $ctl, "\n",	$met;
//echo "\n";

function get_menu_id($chain_menu, $level = 0, &$level_row, $ctl, $met)
{
	global $chain_menu_rows;

	$level++;

	foreach ($chain_menu as $menu_row)
	{
		if ($menu_row['menu_url_ctl'] == $ctl && $menu_row['menu_url_met'] == $met)
		{
			$level_row[$ctl][$met][$level]     = $menu_row['menu_id'];
			$level_row[$ctl][$met][$level - 1] = $menu_row['menu_parent_id'];

			//向上查找一次
			if (isset($chain_menu_rows[$menu_row['menu_parent_id']]))
			{
				$level_row[$ctl][$met][$level - 2] = $chain_menu_rows[$menu_row['menu_parent_id']]['menu_parent_id'];
			}
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

function get_menu_url_map($chain_menu, &$level_row, $chain_menu_ori)
{
	foreach ($chain_menu as $menu_row)
	{
		get_menu_id($chain_menu, 0, $level_row, $menu_row['menu_url_ctl'], $menu_row['menu_url_met']);

		if (isset($menu_row['sub']))
		{
			get_menu_url_map($menu_row['sub'], $level_row, $chain_menu_ori);
		}
	}
}

//缓存点亮规则
//get_menu_url_map($chain_menu, $level_row, $chain_menu);

//计算当前高亮
get_menu_id($chain_menu, 0, $level_row, $ctl, $met);
$level_row = $level_row[$ctl][$met];

return $chain_menu;
?>