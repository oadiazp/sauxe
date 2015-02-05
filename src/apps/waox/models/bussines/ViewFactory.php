<?php
	
class ViewFactory
{
	
	static function factory ($pChart, $pView, $pXml) {
		$xml = $pXml->$pChart;		
		$result = new $pView ($xml, $pView);
		
		return $result;
	}
}