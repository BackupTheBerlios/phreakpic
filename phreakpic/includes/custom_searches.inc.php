<?php
class custom_sql
{
	var $entities;
	
	function create_from_xml($element)
	{
	
		$childs = $element->children();
		foreach ($childs as $value)
		{
			if ($value->type ==XML_TEXT_NODE)
			{
				$s=new sql_string;
				$s->sql=$value->content;
				$this->entities[]=$s;
			}
			if ($value->type == XML_ELEMENT_NODE)
			{	
				if ($value->tagname=='field')
				{
					$f=new sql_field();
					$f->create_from_xml($value);
					$this->entities[]=$f;
				}
				if ($value->tagname=='loop')
				{
					$s=new custom_sql;
					$s->create_from_xml($value);
					$this->entities[]=$s;
				}
				
			}
		}

	
	}
}
class sql_field
{
	var $name;
	var $type;
	var $value;
	var $descr;
	
	function get_vars($element,$var)
	{
		
		$xml=$element->get_elements_by_tagname($var);
		if (isset($xml[0]))
		{
			$content=$xml[0]->children();
			$this->$var=$content[0]->content;
			
		}
	}
	
	function create_from_xml($element)
	{
		$this->get_vars($element,'type');
		$this->get_vars($element,'value');
		$this->get_vars($element,'descr');
		$this->name=$element->get_attribute('name');
	}
}

class sql_string
{
	var $sql;
}




function parse_sql($query)
{
	// replaces the placeholders in $query with the values form $HTTP_POST_VARS['returns']
	
	global $HTTP_POST_VARS;
	// get param fields
	
	
	for ($i=0;$i<sizeof($HTTP_POST_VARS['returns']);$i++)
	{
		$query=preg_replace('/\[\$'.$i.'\]/',$HTTP_POST_VARS['returns'][$i],$query);
	}
	
	return $query;	
}

function parse_xml($xml)
{
	if(!$dom = domxml_open_mem($xml)) {
		echo "Error while parsing the document\n";
		exit;
	}

	$root = $dom->document_element();
	$childs = $root->children();


	$query_sql=new custom_sql;

	$query_sql->create_from_xml($root);
	return $query_sql;
}

function make_sql($returns)
{
	global $query_sql,$HTTP_POST_VARS,$HTTP_SESSION_VARS;
	$loop=0;
	foreach ($query_sql->entities as $value)
	{
		
		if (get_class($value) == 'sql_string')
		{
		
			$sql = $sql . $value->sql;
		}
		if (get_class($value) == 'sql_field')
		{	
			$sql = $sql . $returns[$value->name][0];
		}
		
		if (get_class($value) == 'custom_sql')
		{
			$loop++;
			for ($i=0;$i<=$HTTP_SESSION_VARS['lines'][$loop];$i++)
				
			{
				foreach($value->entities as $lfields)
				{
					
					if (get_class($lfields) == 'sql_string')
					{
						
						$sql = $sql . $lfields->sql;
					}

					if (get_class($lfields) == 'sql_field')
					{	
						
						$sql = $sql . "{$returns[$lfields->name][$i]}";
					}
				}
				
					
				
			}
			
			
		}
	}

	return $sql;
		
}

function field_param($value)
{
	global $x,$y,$fields,$loops,$HTTP_SESSION_VARS,$HTTP_POST_VARS;
	
	$field['name']=$value->name;
	$field['type']=$value->type;
	$field['descr']=$value->descr;
	$field['value']=generate_values($value->value);
		
	$field['loop']=$loops;
	for ($x=0;$x<=$HTTP_SESSION_VARS['lines'][$field['loop']];$x++)
	{
		if (isset($HTTP_POST_VARS['returns']))
		{
			if ($field['type']=='INPUT')
			{
				
				
				$field['value']=$HTTP_POST_VARS['returns'][$value->name][$x];	
			}
			if ($field['type']=='DROPDOWN')
			{
				foreach ($field['value'] as $key=>$val)
				{
					if ($val == $HTTP_POST_VARS['returns'][$value->name][$x])
					{
						$field['selected']=$key;
						
					}
				}
			}
			
		}

		$fields[$x][$y]=$field;
	}
					

}

function generate_values($values)
{
	//Either key word or list seperated with ,
	
	
	if ($values == 'OPERATOR')
	{
		return Array('=','<','>');
	}
	if (strpos($values,','))
	{
		return explode(',',$values);
	}
	return $values;
	
	

}



?>
