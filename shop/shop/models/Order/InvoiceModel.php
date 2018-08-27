<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * @author     Xinze <xinze@live.cn>
 */
class Order_InvoiceModel extends Order_Invoice
{
	const INVOICE_NORMAL   = 1;     //普通发票
	const INVOICE_ELECTRON = 2;     //电子发票
	const INVOICE_ADDTAX   = 3;     //增值税发票

	public function __construct()
	{
		parent::__construct();

		$this->invoiceState = array(
			'1' => __("普通发票"),
			'2' => __("电子发票"),
			'3' => __("增值税发票"),
		);
	}

	public function addInvoiceByInviceId($invoice_id = null,$invoice_title = null,$invoice_content = null)
	{
		$InvoiceModel = new InvoiceModel();
		$invoice = $InvoiceModel->getOne($invoice_id);

		unset($invoice['invoice_id']);
		unset($invoice['id']);
		unset($invoice['invoice_title']);
		$invoice['invoice_content'] = $invoice_content;
		$invoice['invoice_title'] = $invoice_title;
		$order_invoice_id = $this->addInvoice($invoice,true);

		return $order_invoice_id;
	}

	public function addInvoiceByInvice($invoice_title = null,$invoice_content = null)
	{
		$cond_row = array();
		$cond_row['user_id'] = Perm::$userId;
		$cond_row['invoice_state'] = 1;
		$cond_row['invoice_title'] = $invoice_title;
		$cond_row['invoice_content'] = $invoice_content;

		$order_invoice_id = $this->addInvoice($cond_row,true);
		return $order_invoice_id;
	}
    
    /**
     * 订单发票信息
     * @param type $invoice_id
     * @param type $invoice_title
     * @param type $invoice_content
     * @return int
     */
    public function getOrderInvoiceId($invoice_id,$invoice_title,$invoice_content,$order_id){
        
        if(!$invoice_id || !$order_id){
            return 0;
        }
        $InvoiceModel = new InvoiceModel();
		$invoice = $InvoiceModel->getOne($invoice_id);
        if(!$invoice || $invoice['user_id'] != Perm::$userId){
            return 0;
        }
        $invoice['invoice_title'] = $invoice_title == '个人' ? $invoice_title : $invoice['invoice_title'];
        $invoice['invoice_content'] = $invoice_content;
        $invoice['order_id'] = $order_id;
        $data = array();
        $field = array('order_id','user_id','invoice_state','invoice_title','invoice_content','invoice_company','invoice_code','invoice_reg_addr','invoice_reg_phone','invoice_reg_bname','invoice_reg_baccount','invoice_rec_name','invoice_rec_phone','invoice_rec_email','invoice_rec_province','invoice_goto_addr','invoice_province_id','invoice_city_id','invoice_area_id');
        foreach ($invoice as $key => $value){
            if(in_array($key, $field)){
                $data[$key] = $value;
            }
        }
		$order_invoice_id = $this->addInvoice($data,true);
       
        return $order_invoice_id;
    } 
}

?>