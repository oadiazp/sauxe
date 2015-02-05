<?php
   /**
   * ZendExt_XMPP
   * 
   * @author: Juan Jose Rosales
   * @copyright DESPROD-ERP Cuba
   * @package: ZendExt
   * @version 1.0-0
   */ 
  
   require_once dirname(__FILE__) . "/Xmpp/XMPP.php";

   class ZendExt_Xmpp extends XMPPHP_XMPP 
   {   
    /**
     * Instancia para la implementación del patrón Sigleton
     *
     * @var ZendExt_Xmpp
     */
    private static $_instance;
    /**
     * Instancia para la implementación del patrón Sigleton
     *
     * @var String
     */
    public $_serv;


    private $onlineUsers;

    public function getOnlineUsers ()
    {
        return array_keys($this->onlineUsers);
    }

    public function addOnlineUser ($pUsername)
    {
        $this->onlineUsers[$pUsername] = true;
        $this->cache->save ($this->onlineUsers, 'onlineUsers');
    }


    /**
     * Constructor
     */ 
    public function __construct($pUsername, $pPassword, $pPort, $pServer)
    {      
      $user         = $pUsername;
      $port         = $pPort;
      $server       = $pServer;
      $password     = $pPassword;

      parent::__construct($server, $port ,  $user, $password, 'xmpphp', 'gmail.com', false, XMPPHP_Log::LEVEL_VERBOSE); 	
      $this->_serv  =  $server;   
      $cache =  ZendExt_Cache :: getInstance ();
      $this->onlineUsers =  $cache->load ('onlineUsers');       
    }

    /**
    * Retorna una instancia de ZendExt_Xmpp para el singleton
    *
    * @return ZendExt_Xmpp
    */
    static function getInstance ()
    {
      if (self :: $_instance == null)
        self :: $_instance = new self ();        
      return self :: $_instance;
    }
  
   /**
   *  Funcion que registra un nuevo usuario al servidor de chat
   *
   * @return void
   */
   public function registerNewUser($username, $password = NULL)
   {
      if (!isset($password))
       $password = $this->genRandomString(15);
       $id = 'reg_' . $this->getID();
        $xml = "<iq type='set' id='$id'>
                  <query xmlns='jabber:iq:register'>
                    <username>" . $username . "</username>
                    <password>" . $password . "</password>
                    <email></email>
                    <name></name>
                   </query>
                </iq>";

      $this->addIdHandler($id, 'register_new_user_handler');
      $this->send($xml);
   }

   /**
   *  Funcion que subcribe
   *
   * @return void
   */
   public function subscribe($jid)
   {
     $jid = $jid .'@'.$this->_serv;
     parent::subscribe($jid);
   }
   
   /**
   * Funcion que envia un mensaje
   *
   * @return void
   */
   public function sendMsg($destino,$msg)
   {
      try{
            $this->message($destino.'@'.$this->_serv,$msg);
       }
       catch (XMPPHP_Exception $e) {
            die($e->getMessage());
       }
   }            

   public function presence_handler ($pXml)
   {
     parent :: presence_handler ($pXml);
     $parts = explode('/', $pXml->attrs ['from']);
     $user = $parts [0];

     $cache = ZendExt_Cache :: getInstance ();
     $users = $cache->load ('onlineUsers');
     $users [$user] = true;
     $cache->save ($users, 'onlineUsers');
   }

   /**
   * 
   *
   * @return void
   */    

   protected function register_new_user_handler($xml)
   {
    switch ($xml->attrs['type']) {
        case 'error':
                $this->event('new_user_registered', 'error');
                break;
        case 'result':
            $query = $xml->sub('query');
            $username='';
            $password='';
              if(!is_array($query->subs)) {
                foreach ($query->sub as $key => $value) {
                    switch ($value->name) {
                        case 'username':
                            $username = $value->data;
                            break;
                        case 'password':
                            $password = $value->data;
                            break;
                    }
                }
            }
         $this->event('new_user_registered', array('jid' => $username . "@{$this->server}", 'password' => $password));
        default:
         $this->event('new_user_registered', 'default');
      }
    }
  }
?>