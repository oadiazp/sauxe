<?php

class NomCategoriaModel extends ZendExt_Model
{
    static function cargarCategorias () {
        return Doctrine::getTable('NomCategoria')->findAll();
    }
}

