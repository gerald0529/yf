<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * @author     Xinze <xinze@live.cn>
 */
class InvoiceModel extends Invoice
{
	const INVOICE_NORMAL   = 1;     //普通发票
	const INVOICE_ELECTRON = 2;     //电子发票
	const INVOICE_ADDTAX   = 3;     //增值税发票
	public static $invoiceState = array(
		"1" => "normal",
		"2" => "electron",
		"3" => "addtax"
	);

	public function getInvoiceByUser($user_id = null)
	{
		$cond_row     = array('user_id' => $user_id);
		$invoice_list = $this->getByWhere($cond_row);


		if ($invoice_list)
		{
			foreach ($invoice_list as $key => $val)
			{
				$data[InvoiceModel::$invoiceState[$val['invoice_state']]][] = $val;
			}
		}
		else
		{
			$data = array();
		}

		return $data;
	}
}

?>