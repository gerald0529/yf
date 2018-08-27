<?php
$js_row = glob('LC_MESSAGES/*.js');

include_once('global.js');
echo "\r\n";

if (is_array($js_row))
{
    foreach ($js_row as $k=>$v)
    {
        if (!is_dir($v))
        {
            $path_parts = pathinfo($v);

            if ('js' == $path_parts["extension"])
            {
                include_once($v);
                echo "\r\n";
            }
        }
    }
}
?>