<?php if (!defined('ROOT_PATH')) {
    exit('No Permission');
}
    
    /**
     * @author     Xinze <xinze@live.cn>
     */
    class IndexCtl extends AdminController
    {
        public function __construct(&$ctl, $met, $typ)
        {
            parent ::__construct($ctl, $met, $typ);
        }
        
        public function index()
        {
            $admin_rights = parent ::getAdminRights();
            // 全部菜单
            $Menu_Base = new Menu_Base();
            $menus = $Menu_Base -> getMenus();

            // 过滤掉没有权限的数据和没有二级菜单的数据
            foreach ($menus as $key => $val) {
                if (in_array($val['rights_id'], $admin_rights) || $val['rights_id'] == 0) {
                    // 去除没有权限的二级菜单的数据
                    foreach ($val['next_menus'] as $k => $v) {
                        if (!in_array($v['rights_id'], $admin_rights) && $v['rights_id'] != 0) {
                            unset($menus[$key]['next_menus'][$k]);
                        } else {
                            if (is_array($v['third_menus']) && $v['third_menus']) {
                                $result = $this -> getMenuUrlPara($v['third_menus'], $admin_rights);
                                $menus[$key]['next_menus'][$k]['menu_url_ctl'] = $result['ctl'];
                                $menus[$key]['next_menus'][$k]['menu_url_met'] = $result['met'];
                                $menus[$key]['next_menus'][$k]['menu_url_parem'] = $result['parem'];
                            }
                        }
                    }
                } else {
                    // 去除没有权限的一级菜单的数据
                    unset($menus[$key]);
                }
                // 去除没有二级菜单的数据
                if (!$val['next_menus']) {
                    unset($menus[$key]);
                } else {
                }
            }
    
            // 去除 物流设置中的  物流查询选用设置
            // 打开注释 可以解决 禅道bug 3722的问题，还需要修改  kuaidi.php  kuaidiniao.php
            /*$menus['11000']['next_menus']['11015']['menu_url_met'] = $menus['11000']['next_menus']['11015']['third_menus']['11064']['menu_url_met'];
            $menus['11000']['next_menus']['11015']['menu_url_parem'] = $menus['11000']['next_menus']['11015']['third_menus']['11064']['menu_url_parem'];
            unset($menus['11000']['next_menus']['11015']['third_menus']['11063']);*/
            // echo '<pre>';
            // print_r($menus);
            // die;
            
//		 echo '<pre>';print_r($menus);exit;
            /*
            $Page_LayoutModel = new Page_LayoutModel();
    
            $id = 1;
            $data = $Page_LayoutModel->getOne($id);
    
            echo '<pre>';
            print_r($data);
            //$d['layout_structure'] =  $data['layout_structure'];
            //$Page_LayoutModel->edit($id, $d);
            */
            include $this -> view -> getView();
        }
        
        public function main()
        {
            include $this -> view -> getView();
        }
        
        public function upload()
        {
            include $this -> view -> getView();
        }
        
        public function cropperImage()
        {
            include $this -> view -> getView();
        }
        
        public function image()
        {
            include $this -> view -> getView();
        }
        
        public function mainIndex()
        {
            //判断首页权限
            //获取首页协议来判断权限
            $where = array('ctl' => 'Index', 'met' => 'index');
            $Base_Protocol = new Base_Protocol();
            $right_info = $Base_Protocol -> getProtocolByWhere($where);
            $admin_rights = parent ::getAdminRights();
            if (in_array($right_info['rights_id'], $admin_rights)) {
                include $this -> view -> getView();
            } else {
                exit('没有首页显示权限');
            }
        }
        
        /**
         * 根据权限获取菜单url所需要的控制器和方法，参数
         */
        private function getMenuUrlPara($menu = array(), $admin_rights = array())
        {
            $result = array();
            foreach ($menu as $k => $v) {
                if (in_array($v['rights_id'], $admin_rights) || $v['rights_id'] == 0) {
                    $result['ctl'] = $v['menu_url_ctl'];
                    $result['met'] = $v['menu_url_met'];
                    $result['parem'] = $v['menu_url_parem'];
                    break;
                }
            }
            if (!$result) {
                $result['ctl'] = 'Index';
                $result['met'] = 'blankpage';
                $result['parem'] = '';
            }
            return $result;
        }
        
        /**
         * 没有权限时显示的默认页面
         */
        public function blankpage()
        {
            exit();
        }
    }

?>
