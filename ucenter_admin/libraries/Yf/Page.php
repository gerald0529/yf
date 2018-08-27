<?php
/**
 * 分页
 * 
 * 此类为PHP分页，如果是用ajax，可参考JS中的Page类
 * 
 * @category   Framework
 * @package    Page
 * @author     Xinze <xinze@live.cn>
 * @copyright  Copyright (c) 2010, 黄新泽
 * @version    1.0
 * @todo       
 */
class Yf_Page
{
    private    $firstRow        = 0;    // 起始行
    private    $listRows        = 10;    // 每页显示列表行数
    private    $parameter       = '';    // 页数跳转时要带的参数
    private    $totalPages      = 0;    // 总页数
    private    $totalRows       = 0;    // 总行数
    private    $nowPage         = 0;    // 当前页数
    private    $showPageJump    = true; // 是否显示跳到第几页
    private    $coolPages       = 0;    // 分页的栏的总页数
    private    $rollPage        = 5;    // 分页栏每页显示的页数
    
    /**
     * constract function
     *
     * @access public
     *
     * @author 黄新泽
     */
    public function __construct() 
    {
        $a_url = array_merge($_GET,    $_POST);

        //这样做,新增后如果连totalRows一起跳转的话,会导致新加数据不够.
        if (!empty($a_url['totalRows'])) 
        {
            $this->totalRows = $a_url['totalRows'];
			unset($a_url['totalRows']);
        }
        
        if (!empty($a_url['firstRow']))    
        {
            $this->firstRow    = $a_url['firstRow'];
			unset($a_url['firstRow']);
        }
        else
        {
            $this->firstRow    = 0;
        }


        $this->convert($a_url);
        
        if ($a_url)    
        {
            $this->parameter = implode('&',    $a_url);
        }
        
        if ($this->parameter) 
        {
            $this->parameter = '&' . $this->parameter;
        }
        
        if ($this->totalRows < $this->firstRow+1 &&    $this->firstRow) 
        {
            $this->firstRow    = $this->totalRows-1;
        }
    }

    public function set($name, $value) 
    {
        $this->$name = $value;
    }
    
    public function get($name) 
    {
        return $this->$name;
    }

    public function convert(& $array) 
    {
        if (is_array($array)) 
        {
            return @array_walk($array, create_function('&$value, $key', '$value = $key ."=". $value;'));
        }
    }

    /*--------------------------------------------------------------------------
    功能：显示分页信息第 x 页 共 x 页 << < 6 7 8 9 10 >    >>
    -----------------------------------------------------------------------------*/
    public function getTotalSql($query)
    {
        $TotalRows = new TotalRows();

        $sql = $TotalRows->getTotalRowCount($query);
        
        return $sql    ;
    }


    public function prompt() 
    {
        if (0 == $this->totalRows) 
        {
            return;
        }

        $this->totalPages =    ceil($this->totalRows / $this->listRows); //总页数
        $this->coolPages = ceil($this->totalPages / $this->rollPage);
        
        if ($this->firstRow    >= $this->totalRows) 
        {
            $this->nowPage = $this->totalPages;
            $this->firstRow    = ($this->totalPages-1) * $this->listRows;
        }
        else
        {
            $this->nowPage = floor($this->firstRow / $this->listRows + 1); //当前页号
            
        }

        $nowCoolPage = ceil($this->nowPage / $this->rollPage);
        // << <    > >>
        
        if ($nowCoolPage ==    1) 
        {
            $theFirst =    '';
            $prePage = '';
        }
        else
        {
            $preRow    = ($this->rollPage*($nowCoolPage-1)    -1)    * $this->listRows;
            $prePage = "<a href='?firstRow=$preRow&totalRows=$this->totalRows$this->parameter'><font face='Webdings'>&#55;</font></a>";
            $theFirst =    "<a    href='?firstRow=0&totalRows=$this->totalRows$this->parameter'><font    face='Webdings'>&#57;</font></a>";
        }

        if ($nowCoolPage ==    $this->coolPages) 
        {
            $nextPage =    '';
            $theEnd    = '';
        }
        else
        {
            $nextRow = ($nowCoolPage*$this->rollPage) *    $this->listRows;
            $theEndRow = ($this->totalPages-1) * $this->listRows;
            $nextPage =    "<a    href='?firstRow=$nextRow&totalRows=$this->totalRows$this->parameter'><font face='Webdings'>&#56;</font></a>";
            $theEnd    = "<a href='?firstRow=$theEndRow&totalRows=$this->totalRows$this->parameter'><font face='Webdings'>&#58;</font></a>";
        }

        // 1 2 3 4 5
        $linkPage =    '';

        for    ($i    = 1; $i    <= $this->rollPage;    $i++) 
        {
            $page =    ($nowCoolPage-1) *$this->rollPage+$i;
            $rows =    ($page-1) *$this->listRows;
            
            if ($page != $this->nowPage) 
            {
                
                if ($page <= $this->totalPages)    
                {
                    $linkPage.=    " <a href='?firstRow=$rows&totalRows=$this->totalRows$this->parameter'>" . $page . "</a>";
                }
                else
                {
                    break;
                }
            }
            else
            {
                
                if ($this->totalPages != 1)    
                {
                    $linkPage.=    " [<b>"    . $page    . "</b>]";
                }
            }
        }

        $pageStr = '共 ' . $this->totalPages . ' 页     每页显示 '    . $this->listRows .    ' 条记录    当前第 <font color="red">' .    $this->nowPage . '</font> 页  '    . $theFirst    . '    ' .    $prePage . ' ' . $linkPage . ' ' . $nextPage .    ' '    . $theEnd;
        
        return $pageStr;
    }
}
?>