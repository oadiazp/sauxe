<?php

abstract class BaseDatRespuesta extends Doctrine_Record
{

    public function setTableDefinition()
    {
        $this->setTableName('public.dat_respuesta');
        $this->hasColumn('idrespuesta', 'numeric', null, array ('notnull' => false,'primary' => true, 'sequence' => 'mod_analisis.sec_respuesta'));
        $this->hasColumn('idtraza', 'numeric', null, array ('notnull' => true,'primary' => false));
        $this->hasColumn('idcaso', 'numeric', null, array ('notnull' => true,'primary' => false));
        $this->hasColumn('codigo', 'character varying', null, array ('notnull' => true,'primary' => false));
        $this->hasColumn('mensaje', 'character varying', null, array ('notnull' => true,'primary' => false));
        $this->hasColumn('idestructuracomun', 'numeric', null, array ('notnull' => true,'primary' => false));
        $this->hasColumn('respuesta', 'character varying', null, array ('notnull' => true,'primary' => false));
        $this->hasColumn('fecha', 'timestamp without time zone', null, array ('notnull' => true,'primary' => false));
    }


}

