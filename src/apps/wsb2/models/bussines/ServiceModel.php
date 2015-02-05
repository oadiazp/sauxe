<?php

/**
 * 
 */
class ServiceModel extends ZendExt_Model{

    function  __construct() {
        parent::ZendExt_Model();
    }

    function GetServicesXML(){
        $dir = $_SERVER['SCRIPT_FILENAME'];
        $dir = str_replace('web', 'apps', $dir);
        $dir = explode('index.php', $dir);
        $dir = $dir[0].'comun/recursos/xml/web_services.xml';
        $webservices = new SimpleXMLElement($dir, null, true);
        return $webservices;
    }
    function GetServicesDOM(){
        $dir = $_SERVER['SCRIPT_FILENAME'];
        $dir = str_replace('web', 'apps', $dir);
        $dir = explode('index.php', $dir);
        $dir = $dir[0].'comun/recursos/xml/web_services.xml';
        $doc = new DOMDocument;
        $doc->load($dir);
        $a = $doc->documentElement->getElementsByTagName("package")->item(0);
        return $doc;
    }
    function SaveServicesXML($xml){
        $dir = $_SERVER['SCRIPT_FILENAME'];
        $dir = str_replace('web', 'apps', $dir);
        $dir = explode('index.php', $dir);
        $dir = $dir[0].'comun/recursos/xml/web_services.xml';
        file_put_contents($dir,$xml);
    }
    function LoadAllServices(){
        $webservices = $this->GetServicesXML();
        $result = array();
        $packages = $webservices->children();
        foreach($packages as $package){
            $services = $package->children();
            foreach ($services as $service){
                $item =array();
                $item['nombrepaquete'] = (string)$package['name'];
                $item['nombreservicio'] = (string)$service['name'];
                $item['clas'] = (string)$service['class'];
                $item['method'] = (string)$service['method'];
                $item['subsystem'] = (string)$service['subsystem'];
                $item['descripcion']="joder";
                $item['uri'] = "http://".$_SERVER['HTTP_HOST'].(string)$package['uri'];
                $item['estado'] = (string)$service['state'];
                $result[] = $item;
            }
        }
        return $result;
    }
    function LoadPackage($package){
        $webservices = $this->GetServicesXML();
        $packages = $webservices->children();
        $response = new stdClass();
        foreach($packages as $item){
            if($item['name'] == $package){
                $response->name = (string)$item['name'];
                $response->author = (string)$item['author'];
                $response->description = (string)$item['description'];
                $response->uri = (string)$item['uri'];
                $response->services = array();
                foreach($item->children() as $serv){
                    $servicio = new stdClass();
                    $servicio->name = (string)$serv['name'];
                    $servicio->subsystem = (string)$serv['subsystem'];
                    $servicio->class = (string)$serv['class'];
                    $servicio->method = (string)$serv['method'];
                    $servicio->src = (string)$serv['src'];
                    $parameters = array();
                    foreach ($serv->param as $param){
                        $parameters[(string)$param['name']] = (string)$param['type'];
                    }
                    $servicio->params = $parameters;
                    $servicio->result = (string)$serv->return['type'];
                    $response->services[] = $servicio;
                }
                break;
            }
        }
        return $response;
    }
    function LoadAllPackage(){
        $webservices = $this->GetServicesXML();
        $result = array();
        $packages = $webservices->children();
        foreach($packages as $package){
            $item =array();
            $item['nombrepaquete'] = (string)$package['name'];
            $result[] = $item;
        }
        return $result;
    }

    function AddPackage($package){
        $webservices = $this->GetServicesXML();
        $newchild = $webservices->addChild("package");
        $newchild->addAttribute("name", $package->name);
        $newchild->addAttribute("author", $package->author);
        $newchild->addAttribute("description", $package->description);
        $newchild->addAttribute("uri", $package->uri);
        $this->SaveServicesXML($webservices->asXML());
        foreach($package->services as $service){
            $this->AddService($service, $package->name);
        }
    }

    function RemovePackage($package){
        $webservices = $this->GetServicesDOM();
        $packages = $webservices->documentElement->getElementsByTagName("package");
        foreach ($packages as $item){
            if($item->getAttribute('name') == $package){
                $webservices->documentElement->removeChild($item);
                break;
            }
        }
        $this->SaveServicesXML($webservices->saveXML());
    }

    function AddService($service, $package){
        $webservices = $this->GetServicesXML();
        $packages = $webservices->children();
        foreach($packages as $item){
            if($item['name'] == $package){
                $newchild = $item->addChild("service");
                $newchild->addAttribute("name", $service->name);
                $newchild->addAttribute("subsystem", $service->subsystem);
                $newchild->addAttribute("class", $service->class);
                $newchild->addAttribute("method", $service->method);
                $newchild->addAttribute("src", $service->src);
                foreach ($service->params as $param=>$type){
                    $newparam = $newchild->addChild('param');
                    $newparam->addAttribute("name", $param);
                    $newparam->addAttribute("type", $type);
                }
                $return = $newchild->addChild('return');
                $return->addAttribute("type", $service->result);
//                if($service->state)
//                    $newchild->addAttribute("state", $service->state);
//                else
//                    $newchild->addAttribute("state", "no probado");
                break;
            }
        }
        $this->SaveServicesXML($webservices->asXML());
    }

    function RemoveService($service, $package){
        $webservices = $this->GetServicesDOM();
        $packages = $webservices->documentElement->getElementsByTagName("package");
        foreach ($packages as $item){
            if($item->getAttribute('name') == $package){
                $services = $item->getElementsByTagName("service");
                foreach ($services as $elem){
                    if($elem->getAttribute('name') == $service){
                        $item->removeChild($elem);
                        break;
                    }
                }
                break;
            }
        }
        $this->SaveServicesXML($webservices->saveXML());
    }
}
?>
