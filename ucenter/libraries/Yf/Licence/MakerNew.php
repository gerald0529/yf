<?php
class Yf_Licence_MakerNew
{
	private $keydir = null;
	private $data   = array();
	private $output = null;
	public function aa(){}
	/**
	 *  生成licence
	 *
	 * @access public
	 *
	 * @return bool  res;
	 */
	public function createLicence($data=array('expires' => '2014-04-14 00:00:00', 'ip' => '202.107.0.1'), $private_key_path, $licence_path=null)
	{
		$data_str    = serialize($data);

		//$salt = rand(1000, 9999);
		//$sdata = $salt.$sdata;

		$pri = file_get_contents($private_key_path);
		openssl_private_encrypt($data_str, $out, $pri);
		$b = base64_encode($out);
		file_put_contents($licence_path, $b);

		return $b;
	}


	public function check($licence_data, $public_key_data, $evn_row=array())
	{
		$licence = base64_decode($licence_data);
		$ret     = openssl_public_decrypt($licence, $data, $public_key_data);
		$data    = unserialize($data);

		return $this->checkDate($data, $evn_row);
	}

	public function checkDate($data, $evn_row=array())
	{
		$expires = $data['expires'];
		if ($expires < time())
		{
			return false;
		}
		return true;
	}


	//client
	public function checkLicence()
	{
		$url = '';
		$arr_param = array();

		$data = get_url($url, $arr_param=array());

		if (200 == $data['status'])
		{
			return true;
		}
		else
		{
			return false;
		}
	}


}

?>