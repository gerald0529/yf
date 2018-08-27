<?php if (!defined('ROOT_PATH'))
{
    exit('No Permission');
}

/**
 * @author     Xinze <xinze@live.cn>
 */
class Seller_Goods_TBImportCtl extends Seller_Controller
{
    public $goodsSpecValueModel = null;

    /**
     * Constructor
     *
     * @param  string $ctl 控制器目录
     * @param  string $met 控制器方法
     * @param  string $typ 返回数据类型
     * @access public
     */
    public function __construct(&$ctl, $met, $typ)
    {
        parent::__construct($ctl, $met, $typ);

    }

    public function importFile()
    {
        $shopGoodsCatModel = new Shop_GoodsCatModel();
        $shop_goods_cat_rows = $shopGoodsCatModel->getByWhere( array('shop_id'=> Perm::$shopId) );
        include $this->view->getView();
    }

    public function importImage()
    {
        include $this->view->getView();
    }

    /**
     * 有用的信息：宝贝名称、宝贝价格、宝贝数量、运费承担、平邮、EMS、快递、橱窗推荐、宝贝描述、新图片
     */
    public function addGoods()
    {
        $file_path = request_string("file_path");
        $file_path = "./shop/data/upload$file_path";

        $csv_string = $this->unicodeToUtf8(file_get_contents($file_path));

        $handle = fopen($file_path, "w");
        fwrite($handle, $csv_string);
        fclose($handle);

        $reader_csv = new PHPExcel_Reader_CSV();
        $reader_csv->setDelimiter("\t")->setEnclosure("");
        $php_excel = $reader_csv->load($file_path);

        $sheet_data = $php_excel->getActiveSheet()->toArray(null,true,true,true);

        if ( !empty($sheet_data) ) {

            $KName_VLetter = array();

            $important_data = array("宝贝名称", "宝贝价格", "宝贝数量", "橱窗推荐", "宝贝描述", "新图片");
            $unimportant_data = array("运费承担", "平邮", "EMS", "快递");

            foreach ( $sheet_data as $column => $row_data )
            {
                //获取真实数据在哪
                $success = 0;
                foreach ( $important_data as $column_name )
                {
                    if (in_array( $column_name, $row_data ))
                    {
                        $success++;
                    }
                    else
                    {
                        array_shift($sheet_data);
                        continue 2;
                    }
                }

                if ( $success == 6 )
                {

                    foreach ( $row_data as $col_letter => $col_name)
                    {
                        foreach ( $important_data as $column_name )
                        {
                            if ( $col_name == $column_name )
                            {
                                $KName_VLetter[$col_name] = $col_letter;
                            }
                            if ( count($KName_VLetter) == 6 )
                            {
                                array_shift($sheet_data);
                                break 3;
                            }
                        }
                    }
                }
            }
        } else {
            return $this->data->addBody(-140, array(), "没有数据导入", 250);
        }

        if ( !empty($sheet_data) )
        {
            $shopBaseModel          = new Shop_BaseModel();
            $goodsCommonModel       = new Goods_CommonModel();
            $GoodsBaseModel         = new Goods_BaseModel();
            $goodsImagesModel       = new Goods_ImagesModel();
            $goodsCommonDetailModel = new Goods_CommonDetailModel();

            $shop_data = $shopBaseModel->getBase(Perm::$shopId);
            $shop_data = current($shop_data);

            $result_error = array();
            $result_success = array();

            //读取公共数据
            $cat_id             = request_string("goods_category_id");      //商品分类
            $cat_name           = request_string("goods_category_name");    //商品分类
            $province_id        = request_string("province_id");            //商品所在地
            $city_id            = request_string("city_id");                //商品所在地
            $shop_goods_cat_id  = request_row("store_goods_category");      //店铺商品分类

            $common_location = array();
            $common_location[] = $province_id;
            if ( $city_id != 0 )
            {
                $common_location[] = $city_id;
            }

            $common_data = array();
            $common_data['cat_id']              = $cat_id;
            $common_data['cat_name']            = $cat_name;
            $common_data['common_location']     = $common_location;
            $common_data['shop_goods_cat_id']   = $shop_goods_cat_id;

            $common_data['shop_id']             = Perm::$shopId;
            $common_data['shop_name']           = $shop_data['shop_name'];
            $common_data['common_state']        = Goods_CommonModel::GOODS_STATE_OFFLINE; //默认状态下架
            $common_data['common_goods_from']   = Goods_CommonModel::GOODS_FROM_OUTSIDEIMPORT;//外部导入商品

            //common_base shop_self_support 冗余字段（冗余字段太多，不利于维护）判断商品是否为非自营店铺
            $common_data['shop_self_support']   = $shop_data['shop_self_support'] == 'true' ? 1 : 0;


            //判断发布的的商品是否需要审核
            if (Web_ConfigModel::value('goods_verify_flag') == 0)    //商品是否需要审核 0 不需要
            {
                $common_data['common_verify'] = Goods_CommonModel::GOODS_VERIFY_ALLOW;
            }
            else
            {
                $common_data['common_verify'] = Goods_CommonModel::GOODS_VERIFY_WAITING;
            }

            set_time_limit(180);

            foreach ( $sheet_data as $row_data)
            {
                //处理图片
                $goods_image = $row_data[$KName_VLetter["新图片"]];
                $goods_image = str_replace('"', "", $goods_image);
                $goods_image = explode(';', $goods_image);

                array_walk($goods_image, function (&$val){
                    $val = array_shift( explode(":", $val) );
                });

                $common_name = str_replace('"', "", $row_data[$KName_VLetter["宝贝名称"]]);

                $common_data['common_image']            = $goods_image[0];

                $common_data['common_name']             = $common_name;
                $common_data['common_price']            = $row_data[$KName_VLetter["宝贝价格"]];
                $common_data['common_cost_price']       = $row_data[$KName_VLetter["宝贝价格"]];
                $common_data['common_market_price']     = $row_data[$KName_VLetter["宝贝价格"]];
                $common_data['common_stock']            = $row_data[$KName_VLetter["宝贝数量"]];
                $common_data['common_is_recommend']     = $row_data[$KName_VLetter["橱窗推荐"]] == 1 ? Goods_CommonModel::RECOMMEND_TRUE : Goods_CommonModel::RECOMMEND_FALSE;

                //淘宝导入没有时间
                $common_data['common_add_time'] = date('Y-m-d H:i:s');

                $common_id = $goodsCommonModel->addCommon($common_data, true);

                if ($common_id)
                {
                    $result_success[] = $common_data['common_name'];

                    //goods_base
                    $goods_data = array();

                    $goods_data['common_id']            = $common_id;
                    $goods_data['shop_id']              = $common_data['shop_id'];
                    $goods_data['shop_name']            = $common_data['shop_name'];
                    $goods_data['goods_name']           = $common_data['common_name'];
                    $goods_data['cat_id']               = $common_data['cat_id'];
                    $goods_data['goods_price']          = $common_data['common_price'];
                    $goods_data['goods_market_price']   = $common_data['common_market_price'];
                    $goods_data['goods_stock']          = $common_data['common_stock'];
                    $goods_data['goods_is_recommend']   = $common_data['common_is_recommend'];
                    $goods_data['goods_image']          = $common_data['common_image'];
                    $goods_data['goods_is_shelves']     = Goods_BaseModel::GOODS_UP;

                    $goods_id = $GoodsBaseModel->addBase($goods_data, true);

                    if ( $goods_id )
                    {
                        $common_update_data['goods_id'] = array( array('goods_id' => $goods_id, 'color_id' => Goods_ImagesModel::IMAGE_NOT_COLOR) );
                        $goods_common_flag = $goodsCommonModel->editCommon($common_id, $common_update_data);
                    }



                    //goods_image
                    $image_data = array();

                    foreach ( $goods_image as $key => $image )
                    {
                        if ( $key > 4 ) break;
                        $image_data['common_id']            = $common_id;
                        $image_data['shop_id']              = Perm::$shopId;
                        $image_data['images_color_id']      = Goods_ImagesModel::IMAGE_NOT_COLOR;
                        $image_data['images_image']         = $image;
                        $image_data['images_displayorder']  = $key == 0 ? Goods_ImagesModel::IMAGE_DEFAULT : Goods_ImagesModel::IMAGE_NOT_DEFAULT;

                        $goods_image_id = $goodsImagesModel->addImages($image_data, true);
                    }


                    //goods_detail
                    $common_detail_data = array();
                    $common_detail_data['common_id']   = $common_id;
                    $common_detail_data['common_body'] = $row_data[$KName_VLetter["宝贝描述"]];
                    $common_detail_id = $goodsCommonDetailModel->addCommonDetail($common_detail_data, true);
                }
                else
                {
                    $result_error[] = $common_data['common_name'];
                }
            }
            $result_success_str = '';
            $result_error_str = '';
            foreach ($result_success as $v) {
                $result_success_str .= "<p>$v</p>";
            }
            foreach ($result_error as $v) {
                $result_error_str .= "<p>$v</p>";
            }

            $msg = '';
            if ($result_success_str) {
                $msg .= '导入成功:'.$result_success_str.';';
            }
            if ($result_error_str) {
                $msg .= '导入失败:'.$result_error_str.';';
            }
            $this->data->addBody(-140, array(), $msg, 200);
        }
        else
        {
            return $this->data->addBody(-140, array(), "无效数据", 250);
        }
    }

    function unicodeToUtf8($str, $order = "little")
    {
        $utf8string ="";
        $n=strlen($str);
        for ($i=0;$i<$n ;$i++ )
        {
            if ($order=="little")
            {
                $val = str_pad(dechex(ord($str[$i+1])), 2, 0, 0) .
                    str_pad(dechex(ord($str[$i])),      2, 0, 0);
            }
            else
            {
                $val = str_pad(dechex(ord($str[$i])),      2, 0, 0) .
                    str_pad(dechex(ord($str[$i+1])), 2, 0, 0);
            }
            $val = intval($val,16); // 由于上次的.连接，导致$val变为字符串，这里得转回来。
            $i++; // 两个字节表示一个unicode字符。
            $c = "";
            if($val < 0x7F)
            { // 0000-007F
                $c .= chr($val);
            }
            elseif($val < 0x800)
            { // 0080-07F0
                $c .= chr(0xC0 | ($val / 64));
                $c .= chr(0x80 | ($val % 64));
            }
            else
            { // 0800-FFFF
                $c .= chr(0xE0 | (($val / 64) / 64));
                $c .= chr(0x80 | (($val / 64) % 64));
                $c .= chr(0x80 | ($val % 64));
            }
            $utf8string .= $c;
        }
        /* 去除bom标记 才能使内置的iconv函数正确转换 */
        if (ord(substr($utf8string,0,1)) == 0xEF && ord(substr($utf8string,1,2)) == 0xBB && ord(substr($utf8string,2,1)) == 0xBF)
        {
            $utf8string = substr($utf8string,3);
        }
        return $utf8string;
    }
}

?>