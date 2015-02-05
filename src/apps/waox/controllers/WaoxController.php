<?php

class WaoxController extends ZendExt_Controller_Secure {
	
	function init ()
	{
		parent::init(false);
	}
	
	function waoxAction()
	{
		$this->render();
	}

	function chartsAction ()
	{
		$xml = $this->getXml ();

		$result = array ();
		foreach ($xml->children () as $child) {
			$result[] = array ('className' => (string) $child->getName (),
							   'name' => (string) $child['name']);
		}

		echo json_encode(array ('data' => $result));
	}

	function getXml ()
	{
		$parts = explode(DIRECTORY_SEPARATOR, __FILE__);
		$parts[count ($parts) - 2] = 'comun';
		$parts[count ($parts) - 1] = 'xml';
		$parts[] = 'main.xml';		
		$path = implode(DIRECTORY_SEPARATOR, $parts);

		return simplexml_load_file($path);
	}
        
	function viewsAction ()
	{
		$className = $this->getRequest ()->getParam ('chart');
		$xml = $this->getXml ()->$className; 

		$result = array ();
		foreach ($xml->children () as $child) {
			if ((string) $child ['enabled'] === 'true')
				$result[] = array ('className' => (string) $child->getName (),
								   'name'	   => (string) $child ['name']);
		}

		echo json_encode(array ('data' => $result));
	}

	public function onlineAction ()
	{
		$cache = ZendExt_Cache :: getInstance ();
		$online = $cache->load ('onlineUsers');
		$online[$this->getRequest ()->getParam ('user')] = true;
		$cache->save ($online, 'onlineUsers');
	}

	function configureAction () 
	{
		$chartsStr = $this->getRequest ()->getParam ('charts');

		$charts = array ();
		if (version_compare(PHP_VERSION, '5.3.0', '<'))
			$charts = json_decode(stripcslashes($chartsStr));
		else
			$charts = json_decode($chartsStr);

	  	$result = array (); $stores = array ();

	  	foreach ($charts as $chart) {
	  		$result[] = ViewFactory :: factory (
	  												$chart->chart,
		  											$chart->view,
		  											$this->getXml ()	
		  									   );
	  	}

	  	echo json_encode($result);
	}
}
