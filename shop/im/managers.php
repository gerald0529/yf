<?php

    $arr = [
            ['u_id' => 'hp','u_name' => 'hp','avatar' => './img/avatar.jpg'],
            ['u_id' => 'admin','u_name' => 'admin','avatar' => './img/avatar.jpg'],
     ];    
 
    $admin_list = json_encode(['data'=>$arr]);
    
echo $admin_list;

