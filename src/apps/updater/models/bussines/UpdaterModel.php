<?php
    class UpdaterModel extends ZendExt_Model {
        private $_conn;
        private $_scripts;
        private $_apps;
        private $_tmp;

        function  __construct() {
            parent::ZendExt_Model();
            $this->_apps = array ();
            $this->_scripts = array ();
            $this->_tmp = sys_get_temp_dir();
        }

        function getNewerVersions ($pXml, $pVersion) {
            $result = array ();

            foreach ($pXml->children () as $tag) {
                $str = (string)$tag['value'];
                $result[] = array ('version' => $str);
            }
                
            return $result;
        }

        function updateApp ($pIdApp, $pXml, $pPath, $pVersion) {
            if (! $this->_apps [$pIdApp]) {
                if ($pXml->scripts)
                    foreach ($pXml->scripts->children () as $script) {
                        $file = (string) $script ['src'];
                        $path = "{$this->_tmp}/sql/$pVersion/$pIdApp/$file";
                        $this->_conn->exec (file_get_contents($path));
                    }

                ZendExt_File :: mv ("code/$pVersion/$pPath", __DIR__);

                if ($pXml->apps)
                    foreach ($pXml->apps->children () as $app) {
                        $this->updateApp((string) $app ['app'], $pXml->$app, $pPath, $pVersion);
                    }

                $this->_apps [$pIdApp] = true;
            }
        }

        function connect ($pDSN) {
            try {
                $this->_conn = Doctrine_Manager::getInstance()->connection($pDSN);
                $this->_conn->connect();
            }
            
            catch (Exception $e) {
                throw $e;
            }
        }
    }
?>
