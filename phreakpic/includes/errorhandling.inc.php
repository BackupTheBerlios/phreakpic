<?php

function message_die($msg_code, $msg_location = '', $msg_describtion = '',$msg_error, $err_line = '', $err_file = '', $sql = '')
{
	echo ("$msg_code $msg_text $msg_titel in line $err_line in file $err_file at sql querry $sql<br>");
}
?>