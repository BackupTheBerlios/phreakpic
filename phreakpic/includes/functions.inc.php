<?php
function generate_where($field,$array)
{
	for ($i=0;$i<sizeof($array);$i++)
	{
		$where=$where." ".$field." like ".$array[$i];
		if ($i<sizeof($array)-1)
			$where=$where." or";
	}
	return $where;
}

function getext($in_file)
{
	return(end(explode('.',$in_file)));
}



?>
