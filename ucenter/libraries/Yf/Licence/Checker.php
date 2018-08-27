<?php
class Yf_Licence_Checker
{
	public function checkDate($data)
	{
		$expires = strtotime($data['expires']);
		if ($expires < time())
		{
			return false;
		}
		return true;
	}

	private $lfile   = null;
	private $kfile   = null;

	public function __construct($kfile, $lfile)
	{
		$this->kfile   = $kfile;
		$this->lfile   = $lfile;
	}

	public function check()
	{
		$licence = base64_decode($this->lfile);
		$ret     = openssl_public_decrypt($licence, $data, $this->kfile);
		$data    = unserialize($data);
		return $this->checkDate($data);
	}
}

?>