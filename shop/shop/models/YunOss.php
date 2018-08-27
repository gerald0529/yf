<?php 
class YunOss{
	protected $config;
	protected $info;
	public function __construct(){
		include APP_PATH.'/configs/bos.ini.php';
		$this->config = $bos_config; 
	}

	public function run(&$info){
		$this->info = $info;
		$class = $this->config['open'];
		if(!$class){
			return;
		}
		return $this->$class(); 
	}
	
	public function bos(){ 
		$info = $this->info;
		$bos_config = $this->config; 
		if($this->config['open'] == 'bos'){
			//BOS上传
			$bos_url = 'upload'.$info['url_path'];
			$path = __DIR__.'/../data/'.$bos_url; 
			include __DIR__.'/BaiduBce/BaiduBce.php';
			$bos = new BaiduBce_BaiduBce;
			$new_url = $bos->upload($bos_url,$path); 
			if($new_url){
				$info['url'] = $new_url;
				$info['url_prefix'] = ""; 
			}
			spl_autoload_register("__autoload");
			return $new_url;
		}
	}
	public function ali(){
		$info = $this->info;
		$bos_config = $this->config; 
		if($this->config['open'] == 'ali'){
			include __DIR__.'/OSS/OssClient.php'; 
			$bos_url = 'upload'.$info['url_path'];
			$path = __DIR__.'/../data/'.$bos_url;
			$accessKeyId = $bos_config['ali']['accessKeyId'];
			$accessKeySecret = $bos_config['ali']['secretAccessKey'];
			$endpoint = $bos_config['ali']['endpoint'];
			$bucket = $bos_config['ali']['bucketName'];  
	        try {
	            $ossClient = new \OSS\OSSClient($accessKeyId, $accessKeySecret, $endpoint);
	        } catch (OssException $e) {
	            return;
	        }  
			try {
				$link = self::rename($bos_url);
			    $ossClient->uploadFile($bucket, $link, $path);
			    $new_url = 'https://'.$bucket.'.'.$endpoint.'/'.$link;
			    if($new_url){
					$info['url'] = $new_url;
					$info['url_prefix'] = "";
					 
				}
			} catch (OssException $e) {
			    return;
			}
			return $new_url;
		}
	}
	static function rename($objectKey){
		$name = substr($objectKey,strrpos($objectKey,'/')+1);
		$ext = substr($name,strrpos($name,'.'));
		$name = str_replace('.','-',$name);
		$name = $name.'-'.strtolower(self::getGuid());
		return substr($objectKey,0,strrpos($objectKey,'/')+1).$name.$ext;
	}


	static function getGuid() {
		 $charid = strtoupper(md5(uniqid(mt_rand(), true))); 
		 
		 $hyphen = chr(45);// "-" 
		 $uuid = substr($charid, 0, 8).$hyphen 
		 .substr($charid, 8, 4).$hyphen 
		 .substr($charid,12, 4).$hyphen 
		 .substr($charid,16, 4).$hyphen 
		 .substr($charid,20,12);
		 return $uuid; 
	}

}