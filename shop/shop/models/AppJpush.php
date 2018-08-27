<?php
require_once("Jpush/JPush.php");
class AppJpush
{
    //bbcweb推送
    public function bbcwebJpush($uid,$content,$device=array('ios', 'android'),$type=array('type'=>'1')){
        $appkey = Yf_Registry::get('jpush_bbcweb_appkey');
        $app_secret = Yf_Registry::get('jpush_bbcweb_app_secret');
        $app_push = new JPush($appkey,$app_secret);
        try{
            $app_push->push()
                    ->setPlatform($device)
                    ->addAlias($uid)
                    ->addIosNotification($content,'', null, null, null, $type)
                    ->addAndroidNotification($content,null,null,$type)
                    ->setOptions(100000, 3600, null, false)
                    ->send();
        } catch(\Exception $e){
         
        }
        return ;
    }

}

?>