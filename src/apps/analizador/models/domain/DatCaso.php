<?php

class DatCaso extends BaseDatCaso
{

    public function setUp()
    {
        parent :: setUp ();
        $this->hasOne ('NomCategoria', array ('local' => 'idcategoria', 'foreign' => 'idcategoria'));
    }


}

