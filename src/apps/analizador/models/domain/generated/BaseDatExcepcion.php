<?php

abstract class BaseDatExcepcion extends DatCaso
{

    public function setTableDefinition()
    {
        $this->setTableName('mod_analisis.dat_excepcion');
        $this->hasColumn('idcaso', 'numeric', null, array ('notnull' => false,'primary' => false));
        $this->hasColumn('mensaje', 'character varying', null, array ('notnull' => true,'primary' => false));
        $this->hasColumn('codigo', 'character varying', null, array ('notnull' => true,'primary' => false));
    }


}

