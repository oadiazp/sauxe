<?php

class UpdaterController extends ZendExt_Controller_Secure {
    /**
     *
     * @var SimpleXmlElement
     */
    private $_xml;
    
    /**
     *
     * @var Doctrine_Connection
     */
    private $_conn;

    /**
     *
     * @var string
     */
    private $_tmp_dir;

    /**
     *
     * @var array
     */
    private $_updated_apps;

    /**
     *
     * @var array
     */
    private $_updated_scripts;

    /**
     *
     * @var ZendExtModel
     */
    private $_model;

    /**
     *
     * @var string
     */
    private $_apps_path;

    function init ()
    {
        parent::init(false);

        $this->_updated_apps = array ();
        $this->_updated_scripts = array ();

        $this->_temp_dir = sys_get_temp_dir();
        $this->_model = new UpdaterModel();

        $parts = explode('/', __DIR__);
        $parts [count($parts) - 1] = false; $parts [count($parts) - 2] = false;
        $parts = array_filter($parts);

        $this->_apps_path = '/' . implode('/', $parts);
    }

    function updaterAction()
    {
        $this->render();
    }

    function loadAction () {
        $version = $this->getRequest()->getPost ('version');
        $json = array ();

        $this->_xml = simplexml_load_file($this->_temp_dir . '/update.xml');

        if ($this->_xml && $version) {
            foreach ($this->_xml->$version->children () as $child) {
                $item->modulo = (string) $child['name'];
                $item->idmodulo = (string) $child['src'];

                $json[] = $item; $item = null;
            }
        }

        echo json_encode(array ('data' => $json));
    }

    function updateAction () {
        $config = Zend_Registry :: get ('config')->bd->updater;

        $version = $this->getRequest()->getPost ('version');
        $module = $this->getRequest()->getPost ('module');
        $this->_xml = simplexml_load_file($this->_temp_dir . '/update.xml');

        try {
            $dsn =  "pgsql://{$this->getRequest()->getPost ('user')}:{$this->getRequest()->getPost ('passwd')}@{$config->host}:5432/{$config->bd}";
            $this->_model->connect ($dsn);

            $this->_model->updateApp ($module,
                                      $this->_xml->$version->$module,
                                      $this->_apps_path, $version);
        }

        catch (Exception $e) {
            ZendExt_MessageBox::show($e->getMessage(), ZendExt_MessageBox::ERROR);
        }



    }

    function uploadAction () {
        //Descargando el fichero comprimido
        $adapter = new Zend_File_Transfer_Adapter_Http();
        $adapter->setDestination($this->_temp_dir);


        if (! $adapter->receive()) {
            file_get_contents('/tmp/a.', $adapter->getMessages ());
            return;
        }


        //Descomprimiendo en los temporales
        $zip = new ZipArchive();
        $zip->open($adapter->getFileName());
        $zip->extractTo($this->_temp_dir);
    }

    function versionAction () {
        $this->_xml = simplexml_load_file($this->_temp_dir . '/update.xml');

        $xml = simplexml_load_file($this->_apps_path . '/comun/recursos/xml/subsistemasinstalados.xml');

        $data = array ();

        $currentVersion = new Version ((string) $xml ['version']);
        $versiones = array ();

        foreach ($this->_xml->versiones->children () as $child) {
            $version = new Version ((string) $child ['value']);
            $versiones[] = $version;
        }

        $tmp = null;

        for ($i = 0; $i < count($versiones); $i++) {
            for ($j = $i; $j < count($versiones); $j++) {
                if ($versiones [$i]->compareTo ($versiones [$j]) > 0) {
                    $tmp = $versiones [$i];
                    $versiones [$i] = $versiones [$j];
                    $versiones [$j] = $tmp;
                }
            }    
        }    

        $cache = ZendExt_Cache :: getInstance();
        $cache->save ($versiones, 'versiones');

        foreach ($versiones as $child) {
            if ($child->compareTo ($currentVersion) > 0) {
                $data[] = array ('version' => $child->getName ());
            }
        }

        $json->data = $data; echo json_encode($json);
    }

    function versionesAction () {
        $version = $this->getRequest ()->getPost ('last_version');
        $version = new Version ($version);

        $xml = simplexml_load_file($this->_apps_path . '/comun/recursos/xml/subsistemasinstalados.xml');
        $currentVersion = new Version ((string) $xml ['version']);

        $cache = ZendExt_Cache :: getInstance();
        $versiones = $cache->load ('versiones');

        foreach ($versiones as $child) {
            if ($child->compareTo ($currentVersion) > 0 && $child->compareTo ($version) <= 0 ) {
                $data [] = $child->getName ();
            }
        }

        echo json_encode ($data);
    }
    
    function xmlAction () {
        $xml = simplexml_load_file($this->_apps_path . '/comun/recursos/xml/subsistemasinstalados.xml');
        $xml ['version'] = $this->getRequest ()->getPost ('version');
        file_put_contents($this->_apps_path . '/comun/recursos/xml/subsistemasinstalados.xml', $xml->asXml ());
    }
}