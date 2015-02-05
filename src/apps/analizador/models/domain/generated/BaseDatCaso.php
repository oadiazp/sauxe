<?php

abstract class BaseDatCaso extends Doctrine_Record
{

    public function setTableDefinition()
    {
        $this->setTableName('mod_analisis.dat_caso');
        $this->hasColumn('idcaso', 'numeric', null, array ('notnull' => false,'primary' => true, 'sequence' => 'mod_analisis.seq_dat_caso'));
        $this->hasColumn('denominacion', 'character varying', null, array ('notnull' => true,'primary' => false));
        $this->hasColumn('respuesta', 'character varying', null, array ('notnull' => true,'primary' => false));
        $this->hasColumn('idestructuracomun', 'numeric', null, array ('notnull' => true,'primary' => false));
        $this->hasColumn('idcategoria', 'numeric', null, array ('notnull' => false,'primary' => false));
    }


}

