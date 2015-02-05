<?php
/**
 *
 */
class ZendExt_MessageBus_Catalog {

    protected $catalog;

    /**
     *
     */
    public function __construct() {
        $this->catalog = array();
    }

    /**
     *
     * @param string $subject
     */
    public function RegisterSubject($subject){
        if(!isset($this->catalog[$subject]))
            $this->catalog[$subject] = array();
    }

    /**
     *
     * @param string $subject
     */
    public function UnRegisterSubject($subject){
        if(isset($this->catalog[$subject]))
            unset($this->catalog[$subject]);
    }

    /**
     *
     * @param string $subject
     * @param string $event
     */
    public function RegisterEvent($subject, $event){
        if(isset($this->catalog[$subject]) && !isset($this->catalog[$subject][$event]))
            $this->catalog[$subject][$event]= array();
    }

    /**
     *
     * @param string $subject
     * @param string $event
     */
    public function UnRegisterEvent($subject, $event){
        if(isset($this->catalog[$subject][$event]))
            unset($this->catalog[$subject][$event]);
    }

    /**
     *
     * @param string $subject
     * @param string $event
     * @param object $observer
     */
    public function RegisterObserver($subject, $event, $observer){
        if(isset($this->catalog[$subject][$event]) && !$this->IsRegisteredObserver($subject, $event, $observer)){
            $this->catalog[$subject][$event][] = $observer;
        }
    }

    /**
     *
     * @param string $subject
     * @param string $event
     * @param object $observer
     */
    public function UnRegisterObserver($subject, $event, $observer){
        if(isset($this->catalog[$subject][$event])){
            $key = array_search($observer, $this->catalog[$subject][$event], TRUE);
            if($key){
                unset($this->catalog[$subject][$event][$key]);
                $this->catalog[$subject][$event] = array_values($this->catalog[$subject][$event]);
            }
        }
    }

    /**
     *
     * @param string $subject
     * @return bool
     */
    public function IsRegisteredSubject($subject){
        if(isset($this->catalog[$subject]))
            return true;
        return false;
    }

    /**
     *
     * @param string $subject
     * @param string $event
     * @return bool
     */
    public function IsRegisteredEvent($subject, $event){
        if(isset($this->catalog[$subject][$event]))
            return true;
        return false;
    }

    /**
     *
     * @param string $subject
     * @param string $event
     * @param object $observer
     * @return bool
     */
    public function IsRegisteredObserver($subject, $event, $observer){
        if(isset($this->catalog[$subject][$event])){
            foreach ($this->catalog[$subject][$event] as $item){
                if($item === $observer)
                    return true;
            }
        }
        return false;
    }

    /**
     *
     * @return array
     */
    public function GetSubjects(){
        $subjects = array();
        foreach($this->catalog as $subjet=>$events){
            $subjects[]=$subjet;
        }
        return $subjects;
    }

    /**
     *
     * @param string $subject
     * @return array
     */
    public function GetEventsBySubject($subject){
        $events = array();
        if($this->IsRegisteredSubject($subject))
            foreach($this->catalog[$subject] as $event=>$observers){
                $events[] = $event;
            }
        return $events;
    }

    /**
     *
     * @param string $subject
     * @param string $event
     * @return array
     */
    public function GetObserversByEvent($subject,$event){
        $observers = array();
        if($this->IsRegisteredEvent($subject, $event))
            foreach($this->catalog[$subject][$event] as $observer){
                $observers[]=$observer;
            }
        return $observers;
    }
}
?>
