<?php
//[StyleGroup][RelationshipDefaultsGroup][DefaultConnectionStyle][0][@attributes][ConnectionShape] => urn:mindjet:NoArrow
//[StyleGroup][RelationshipDefaultsGroup][DefaultConnectionStyle][1][@attributes][ConnectionShape] => urn:mindjet:Arrow
class File_MindManage
{
	public $relationshipStyleRows  = array();    //Relationship
	public $itemOidNameRows  = array();    //Oid->Name Id
	public $itemIdOidRows  = array();    //Id->Oid
	public $returnRows  = array();      //临时，继续需要返回的数据长度
	public $formatTopicRows  = array(); //Topic
	public $formatRelationshipRows  = array(); //Relationship
	public $xmlString   = '';
	public $xmlObj      = null;
	public $json       = null;

	public function __construct($path, $flag=0)
	{
        if (0 == $flag)
        {
            $this->xmlString = file_get_contents($path);
        }
        else
        {
            $this->xmlString = $path;
        }

        $this->xmlString = str_replace('ap:', '', $this->xmlString);
        $this->xmlObj    = simplexml_load_string($this->xmlString);
        $this->json      = json_decode(encode_json($this->xmlObj), true);

        $this->relationshipStyleRows['start'] = $this->json['StyleGroup']['RelationshipDefaultsGroup']['DefaultConnectionStyle']['0']['@attributes']['ConnectionShape'];
        $this->relationshipStyleRows['end'] = $this->json['StyleGroup']['RelationshipDefaultsGroup']['DefaultConnectionStyle']['1']['@attributes']['ConnectionShape'];


        $this->formatTopicRows = $this->formatTopic($this->json['OneTopic']);
        $this->formatRelationshipRows = $this->formatRelationship($this->json['Relationships']['Relationship']);
	}

	/**
	 * $floating_flag =0, topic  , 1=floating topic
	 *
	 * @param array		$data_all_rows		
	 * @param int		$parent_id		
	 * @param int		$deep		
	 * @param int		$floating_flag	是否为浮动标题
	 *
	 * @return array	$this->returnRows		
	 * @access	public	
	 */
    function formatTopic($data_all_rows, $parent_id=0, $deep=0, $floating_flag=0)
    {
        $format_return_rows = array();

        $data_rows = array();
        $data_temp_rows = array();

        $data_floating_rows = array();
        $data_floating_tmp_rows = array();


        if (isset($data_all_rows['Topic']))
        {
            $data_temp_rows = $data_all_rows['Topic'];
        }
        elseif (isset($data_all_rows['OneTopic']))
        {
            $data_temp_rows = $data_all_rows['OneTopic'];
        }
        
        if (isset($data_all_rows['FloatingTopics']))
        {
            $data_floating_tmp_rows = $data_all_rows['FloatingTopics'];
        }

        //单个子节点，其直接放入数据，多个子节点，以数组存储，做个调整，程序统一逻辑
        if (isset($data_temp_rows['@attributes']))
        {
            $data_rows[0] = $data_temp_rows;
        }
        else
        {
            $data_rows = $data_temp_rows;
        }

        if (isset($data_floating_tmp_rows['@attributes']))
        {
            $data_floating_rows[0] = $data_floating_tmp_rows;
        }
        else
        {
            $data_floating_rows = $data_floating_tmp_rows;
        }


        foreach ($data_rows as $key=>$data_row)
        {
            $tmp_rows = array();
            $format_tmp_rows = array();

            $parent_id = $parent_id;
            $id = count($this->returnRows);

            $tmp_rows['id'] = $id;
            $tmp_rows['parent_id'] = $parent_id;
            $tmp_rows['level'] = $deep;
            $tmp_rows['PlainText'] = $data_row['Text']['@attributes']['PlainText'];

            $this->itemOidNameRows[$data_row['@attributes']['OId']]['name'] = $tmp_rows['PlainText'];
            $this->itemOidNameRows[$data_row['@attributes']['OId']]['id']   = $id;
            $this->itemIdOidRows[$id] = $data_row['@attributes']['OId'];

            if (isset($data_row['Task']['@attributes']['TaskPriority']))
            {
                $tmp_rows['TaskPriority'] = trim($data_row['Task']['@attributes']['TaskPriority'], 'urn:mindjet:Prio');
            }
            else
            {
                $tmp_rows['TaskPriority'] = '';
            }

            if (isset($data_row['NotesGroup']['NotesXhtmlData']['@attributes']['PreviewPlainText']))
            {
                $tmp_rows['Notes'] = html_entity_decode($data_row['NotesGroup']['NotesXhtmlData']['@attributes']['PreviewPlainText']);
            }
            else
            {
                $tmp_rows['Notes'] = '';
            }

            //floating data
            if (isset($data_row['FloatingTopics']))
            {
                $format_floating_temp_rows = $this->formatTopic($data_row['FloatingTopics'], $id, $deep+1, 1);
                $tmp_rows['floating'] = $format_floating_temp_rows;
            }
            else
            {
                
            }

            if ($floating_flag)
            {
                $sub_tmp_rows = array();

                if (isset($data_row['SubTopics']))
                {
                    $sub_tmp_rows = $this->formatTopic($data_row['SubTopics'], $id, $deep+1, $floating_flag);
                    $tmp_rows['SubTopics'] = $sub_tmp_rows;
                }
                
                
                $format_return_rows[$key] = $tmp_rows;
            }
            else
            {
                $this->returnRows[$id]    = $tmp_rows;
                $format_tmp_rows[]   = $tmp_rows;

                $sub_tmp_rows = array();

                if (isset($data_row['SubTopics']))
                {
                    $sub_tmp_rows = $this->formatTopic($data_row['SubTopics'], $id, $deep+1, $floating_flag);
                }
                
                $format_return_rows = array_merge($format_return_rows, $format_tmp_rows, $sub_tmp_rows);
            }

        }   


        return  $format_return_rows;
    }

	/**
	 * 返回格式化后的数组
	 *
	 *
	 * @return array		$formatTopicRows		格式化后的数组
	 * @access	public	
	 */
	function formatRelationship($data_all_rows)
	{
        
        //单个子节点，其直接放入数据，多个子节点，以数组存储，做个调整，程序统一逻辑
        if (isset($data_all_rows['@attributes']))
        {
            $data_rows[0] = $data_all_rows;
        }
        else
        {
            $data_rows = $data_all_rows;
        }


        foreach ($data_rows as $key=>$data_row)
        {
            $tmp_rows = array();

            $tmp_rows['start'] = $data_row['ConnectionGroup'][0]['Connection']['ObjectReference']['@attributes']['OIdRef'];
            $tmp_rows['end']   = $data_row['ConnectionGroup'][1]['Connection']['ObjectReference']['@attributes']['OIdRef'];
            
            $id = count($this->formatRelationshipRows);

            $this->formatRelationshipRows[$id]['start'] = $this->itemOidNameRows[$tmp_rows['start']]['id'];
            $this->formatRelationshipRows[$id]['end'] = $this->itemOidNameRows[$tmp_rows['end']]['id'];

            $id = count($this->formatRelationshipRows);
            $this->formatRelationshipRows[$id]['start'] = $this->itemOidNameRows[$tmp_rows['end']]['id'];
            $this->formatRelationshipRows[$id]['end'] = $this->itemOidNameRows[$tmp_rows['start']]['id'];

            //现在设置为所有路都是双向的，顾对方向不做判断
            if (isset($data_row['ConnectionGroup'][0]['ConnectionStyle']))
            {
                
            }
        }   


        return  $this->formatRelationshipRows;

	}

	/**
	 * 返回格式化后的数组
	 *
	 *
	 * @return array		$formatTopicRows		格式化后的数组
	 * @access	public	
	 */
	function getFormatRelationship()
	{
		return $this->formatRelationshipRows;
	}

	/**
	 * 返回格式化后的数组
	 *
	 *
	 * @return array		$formatTopicRows		格式化后的数组
	 * @access	public	
	 */
	function getFormatData()
	{
		return $this->formatTopicRows;
	}
}

?>