<?php

class NomCategoria extends BaseNomCategoria
{

    public function setUp()
    {
        parent :: setUp ();
        $this->hasMany ('DatCaso', array ('local' => 'idcategoria', 'foreign' => 'idcategoria'));
    }


}

