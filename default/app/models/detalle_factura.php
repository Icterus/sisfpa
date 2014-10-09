<?php

/**
*
*/
class DetalleFactura extends ActiveRecord
{
    protected $logger = True;
    public function detalles($factura_id) {
        $columns = "columns: descripcion, cantidad, precio";
        $conditions = "factura_id =".$factura_id;
        return $this->find($conditions, $columns);
    }

    public function rubros($inicio, $final)
    {
        $inicio = date("Y-m-d", strtotime(str_replace('/', '-',$inicio))).' 00:00:00';
        $final = date("Y-m-d", strtotime(str_replace('/', '-',$final))).' 23:59:59';

        $columns = "columns: factura.created_at, descripcion, precio, sum(cantidad) as cantidad, monto, iva";
        $joins = "join: INNER JOIN factura ON factura.id = factura_id";
        $group = "group: factura.created_at, descripcion, precio";
        $conditions = "factura.created_at BETWEEN '".$inicio."' AND '".$final."'";
        return $this->find($conditions, $columns, $joins, $group);
    }
}

?>