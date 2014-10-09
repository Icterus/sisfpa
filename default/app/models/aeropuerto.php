<?php

/**
*
*/
class aeropuerto extends ActiveRecord
{

    public function paises()
    {
        $rs = $this->distinct('pais', 'order: pais ASC');
        array_unshift($rs, 'Seleccione País');
        return $rs;
    }

    public function aeropuertos($pais)
    {
        $columns = "columns: id, codigo, aeropuerto, pais";
        $order = "order: aeropuerto ASC";
        return $this->find("pais = '$pais'", $columns, $order);
    }
}

?>