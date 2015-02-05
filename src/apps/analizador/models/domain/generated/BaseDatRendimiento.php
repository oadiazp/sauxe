<?php

abstract class BaseDatRendimiento extends DatCaso
{

    public function setTableDefinition()
    {
        $this->setTableName('mod_analisis.dat_rendimiento');
        $this->hasColumn('idcaso', 'numeric', null, array ('notnull' => false,'primary' => false));
        $this->hasColumn('tiempomin', 'numeric', null, array ('notnull' => true,'primary' => false));
        $this->hasColumn('memoriamin', 'numeric', null, array ('notnull' => true,'primary' => false));
        $this->hasColumn('tiempomax', 'numeric', null, array ('notnull' => true,'primary' => false));
        $this->hasColumn('memoriamax', 'numeric', null, array ('notnull' => true,'primary' => false));
    }


}

