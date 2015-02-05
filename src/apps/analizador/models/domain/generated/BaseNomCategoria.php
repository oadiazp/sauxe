<?php

abstract class BaseNomCategoria extends Doctrine_Record
{

    public function setTableDefinition()
    {
        $this->setTableName('mod_analisis.nom_categoria');
        $this->hasColumn('idcategoria', 'numeric', null, array ('notnull' => false,'primary' => true, 'sequence' => 'mod_analisis.seq_nom_categoria'));
        $this->hasColumn('categoria', 'character varying', null, array ('notnull' => true,'primary' => false));
    }


}

