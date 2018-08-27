<?php 
 
include __DIR__.'/BaiduBce.phar';

use BaiduBce\BceClientConfigOptions;
use BaiduBce\Util\Time;
use BaiduBce\Util\MimeTypes;
use BaiduBce\Http\HttpHeaders;
use BaiduBce\Services\Bos\BosClient;
use BaiduBce\Services\Bos\BosOptions;
class BaiduBce_BaiduBce{
	public $config;
	protected $client;
	protected $url ;
	protected $accessKeyId ;
	protected $secretAccessKey;
	protected $bucketName;
	 
	protected function getGuid() {
		 $charid = strtoupper(md5(uniqid(mt_rand(), true))); 
		 
		 $hyphen = chr(45);// "-" 
		 $uuid = substr($charid, 0, 8).$hyphen 
		 .substr($charid, 8, 4).$hyphen 
		 .substr($charid,12, 4).$hyphen 
		 .substr($charid,16, 4).$hyphen 
		 .substr($charid,20,12);
		 return $uuid; 
	}

	public function __construct(){
		include APP_PATH.'/configs/bos.ini.php';
		if($bos_config['open'] == 'bos'){
			$this->accessKeyId = $bos_config['bos']['accessKeyId'];
			$this->secretAccessKey = $bos_config['bos']['secretAccessKey'];
			$this->url = $bos_config['bos']['endpoint'];
			$this->bucketName = $bos_config['bos']['bucketName']; 
		}else{
			return;
		}
		 

		$this->config =
		    array(
		        'credentials' => array(
		            'accessKeyId' => $this->accessKeyId,
		            'secretAccessKey' =>$this->secretAccessKey ,
		        ),
		    	'endpoint' => $this->url,
		    );
	    $this->connect();
	}


	public function get_url(){
		return $this->url;
	}

	public function get_bucket($bucketName){
		$exist = $this->client->doesBucketExist($bucketName);
		if(!$exist){
		    $this->client->createBucket($bucketName);
		}
		return $bucketName;
	}

	public function set_bucket($bucketName){
		$this->bucketName = $bucketName;
	}


	public function connect(){
		//新建BosClient
   		 $this->client = new BosClient($this->config);
	}

	public function upload($objectKey,$fileName ,$bucketName= null){
		if(!$bucketName){
			$bucketName = $this->bucketName;
		}
		$name = substr($objectKey,strrpos($objectKey,'/')+1);
		$ext = substr($name,strrpos($name,'.'));
		$name = str_replace('.','-',$name);
		$name = $name.'-'.strtolower($this->getGuid());
		$objectKey = substr($objectKey,0,strrpos($objectKey,'/')+1).$name.$ext;

		 


		$this->get_bucket($bucketName);
	 	try { 
			$res = $this->client->putObjectFromFile($bucketName, $objectKey, $fileName);
 			return $this->link($bucketName,$objectKey);
		} catch (\BaiduBce\Exception\BceBaseException $e) {
		     
		}
	}

	public function link($bucketName,$objectKey){
		return str_replace('://','://'.$bucketName.'.',$this->url).'/'.$objectKey;
	}

	public function write($objectKey,$data , $bucketName=null){ 
		if(!$bucketName){
			$bucketName = $this->bucketName;
		}
		$this->get_bucket($bucketName);
		try {
		   	$res = $this->client->putObjectFromString($bucketName, $objectKey, $data); 
		 	return $this->link($bucketName,$objectKey);

		} catch (\BaiduBce\Exception\BceBaseException $e) {
		     
		}

		
		 
	}
}