<?php
        $connect_config['imbuilder_url'] = 'http://api.im-builder.com/';
        $connect_config['ucenter_url'] = 'http://ucenter.yuanfeng021.com/index.php';

        //$connect_config['ucenter_url'] = 'http://localhost/pcenter/index.php';
        //$connect_config['imbuilder_url'] = 'http://localhost/imbuilder/';

        $host = './';


        if (isset($_SERVER['HTTP_HOST']))
        {
            $host = 'http://' . $_SERVER['HTTP_HOST'];
        }

        $config['weburl'] = $host . trim($_SERVER["REQUEST_URI"]);

        $login_url = $connect_config['ucenter_url'] . '?ctl=Login&met=index&typ=';
        $callback = $config['weburl'] . '&redirect=' . urlencode($config['weburl']);
        $login_url = $login_url . '&from=mall&callback=' . urlencode($callback);
        //header('location:' . $login_url);
        $userid = '';
        $k = ''; //解码
        if(isset($_GET['us']) && isset($_GET['ks']))
        {
            $userid = intval($_GET['us']);
            $k = $_GET['ks']; 
        }
        session_start();
        $_SESSION['login_url'] = $login_url;  
?>