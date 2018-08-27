<?php
if (! defined('ROOT_PATH'))
    exit('No Permission');

class Api_Database_UpdateCtl extends Api_Controller
{

    public $databaseUpdateModel = null;

    public function __construct(&$ctl, $met, $typ)
    {
        parent::__construct($ctl, $met, $typ);

    }
    public function manage()
    {
        @set_time_limit(0);
        $this->databaseUpdateModel = new Database_Update();
        $sql = str_replace("\\", "", $_POST["sql_content"]);
        $ar = explode(";", $sql);
        foreach ($ar as $ke => $ve) {
            $ve = trim($ve);
            if (! empty($ve)) {
                $return[$ke] = $this->databaseUpdateModel->sql->exec($ve);
                if ($return[$ke] === false) {
                    $msg = $ve;
                    $status = 250;
                    $data = array();
                    break;
                }
            }
            $msg = '';
            $status = 200;
            $data[] = $ve;
        }
        
        $this->data->addBody(- 140, $data, $msg, $status);
    }
}