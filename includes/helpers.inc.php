<?php 
function print1($text)
{
	return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
}
function printhtml($text)
{
	echo print1($text);
}
?>