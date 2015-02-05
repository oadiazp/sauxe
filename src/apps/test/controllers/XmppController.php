<?php
class XmppController extends ZendExt_Controller_Secure {
	
	function init ()
	{
		parent::init(false);
	}
	
	function xmppAction()
	{
		$this->render();
	}

	function dataAction () {
		echo json_encode(array ('data' => array (array ('name' => 'Omar',
											     'age'  => 25,
											     'sex'  => 'M'))));							
	}

	function connectionAction () {
		echo json_encode(array ('url' => '/xmpp-httpbind',
								'username' => 'a@zco',
								'password' => 'a'));
	}
        
	
	
}
