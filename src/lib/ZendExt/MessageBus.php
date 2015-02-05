<?php
/**
 *
 */
class ZendExt_MessageBus implements ZendExt_Aspect_ISinglenton{
    private static $instance;
    protected $observers;
    protected $events;

    /**
     *
     */
    private function __construct() {
        $this->observers = new ZendExt_MessageBus_Catalog();
        $this->events = new ZendExt_MessageBus_EventQueue();
        $this->Inicialize();
    }

    /**
     *
     */
    static public function getInstance() {
        
        if (!isset(self::$instance)){
            $cache = ZendExt_Cache::getInstance();
            try{
                self::$instance = $cache->load('messagebus');
                if(!self::$instance){
                    self::$instance = new self();
                    $cache->save(self::$instance, 'messagebus');
                }
            }
            catch (Exception $ee){
                self::$instance = new self();
                $cache->save(self::$instance, 'messagebus');
            }
        }
        
        return self::$instance;
    }

    /**
     *
     * @param string $subject
     * @param string $events
     */
    public function Register($subject, $events){
        $this->observers->RegisterSubject($subject);
        foreach($events as $event){
            $this->observers->RegisterEvent ($subject, $event);
        }
        $cache = ZendExt_Cache::getInstance();
        $cache->save(self::$instance, 'messagebus');
    }

    /**
     *
     * @param string $subject
     */
    public function UnRegister($subject){
        $this->observers->UnRegisterSubject($subject);
        $cache = ZendExt_Cache::getInstance();
        $cache->save(self::$instance, 'messagebus');
    }

    /**
     *
     * @param string $subject
     * @param string $event
     * @param object $observer
     */
    public function Subscribe($subject, $event, $observer){
        $this->observers->RegisterObserver($subject, $event, $observer);
        $cache = ZendExt_Cache::getInstance();
        $cache->save(self::$instance, 'messagebus');
    }

    /**
     *
     * @param string $subject
     * @param string $event
     * @param object $observer
     */
    public function UnSubscribe($subject, $event, $observer){
        $this->observers->UnRegisterObserver($subject, $event, $observer);
        $cache = ZendExt_Cache::getInstance();
        $cache->save(self::$instance, 'messagebus');
    }

    /**
     *
     * @param string $subject
     * @return array
     */
    public function GetEventsBySubject($subject){
        return $this->observers->GetEventsBySubject($subject);
    }

    /**
     *
     * @param string $subject
     * @param string $event
     * @param array $data
     */
    public function FireEvent($subject, $event, $data){
        if($this->observers->IsRegisteredEvent($subject, $event))
            $this->events->Enqueue ($subject, $event, $data);
        $this->NotifyEvents();
    }

    /**
     * 
     */
    private function NotifyEvents(){
        while (!$this->events->IsEmpty()){
            $item = $this->events->Dequeue();
            $objects = $this->observers->GetObserversByEvent($item->subject, $item->event);
            $ioc = ZendExt_IoC::getInstance();
            foreach($objects as $obj){
                $ioc->__get($obj);
                $ioc->notify($item->subject, $item->event, $item->data);
            }
        }
    }
    
    /**
     * 
     */
    private function Inicialize(){
        $this->Register('holamundo', array('evento1','evento2'));
        $this->Subscribe('holamundo', 'evento1', 'alertasavisos');
    }
}



?>
