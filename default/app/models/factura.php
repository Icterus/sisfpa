<?php

/**
*
*/
class Factura extends ActiveRecord
{
    protected $logger = True;
    public function cierre($id, $fecha){
        $fecha = date("Y-m-d", strtotime(str_replace('/', '-',$fecha)));

        $columns = "columns: factura.monto, prefijo, factura.factura, cliente, tipo, iva, retencion";
        $joins = "join: LEFT JOIN clientes ON clientes_id = clientes.id LEFT JOIN pagos ON factura_id = factura.id INNER JOIN ubicacion ON ubicacion_id = ubicacion.id";
        $conditions = "factura.created_at LIKE '".$fecha." %' AND factura.usuario_id = ".$id." and factura.estatus != 2";
        $group = "group: factura.id";

        return $this->find($conditions, $columns, $joins, $group);
    }

    public function credito($id, $inicio, $final){
        $inicio = date("Y-m-d", strtotime(str_replace('/', '-',$inicio))).' 00:00:00';
        $final = date("Y-m-d", strtotime(str_replace('/', '-',$final))).' 23:59:59';

        $columns = "columns: factura.monto, prefijo, factura.factura, factura.created_at";
        $joins = "join: LEFT JOIN pagos ON factura_id = factura.id INNER JOIN ubicacion ON ubicacion_id = ubicacion.id";
        $conditions = "(factura.created_at BETWEEN '".$inicio."' AND '".$final."') AND clientes_id = ".$id." AND tipo is NULL";

        return $this->find($conditions, $columns, $joins);
    }

    public function credito_fbo($id, $inicio, $final){
        $inicio = date("Y-m-d", strtotime(str_replace('/', '-',$inicio))).' 00:00:00';
        $final = date("Y-m-d", strtotime(str_replace('/', '-',$final))).' 23:59:59';

        $columns = "columns: factura.monto, prefijo, factura.factura, factura.created_at";
        $joins = "join: LEFT JOIN pagos ON factura_id = factura.id INNER JOIN ubicacion ON ubicacion_id = ubicacion.id";
        $conditions = "(factura.created_at BETWEEN '".$inicio."' AND '".$final."') AND prestadores_id = ".$id." AND tipo is NULL";

        return $this->find($conditions, $columns, $joins);
    }

    public function cuentas($id, $inicio, $final){
        $inicio = date("Y-m-d", strtotime(str_replace('/', '-',$inicio))).' 00:00:00';
        $final = date("Y-m-d", strtotime(str_replace('/', '-',$final))).' 23:59:59';

        $columns = "columns: factura.monto, prefijo, factura.factura, factura.created_at";
        $joins = "join: LEFT JOIN pagos ON factura_id = factura.id INNER JOIN ubicacion ON ubicacion_id = ubicacion.id";
        $conditions = "(factura.created_at BETWEEN '".$inicio."' AND '".$final."') AND clientes_id = ".$id." AND factura is NULL";

        return $this->find($conditions, $columns, $joins);
    }

    public function buscar($prefijo, $numero) {
        $columns = "columns: factura.id, prefijo, factura.factura, nombres, apellidos, cliente, factura.created_at, iva, exento, monto, factura.estatus";
        $joins = "join: INNER JOIN usuario ON usuario.id = usuario_id INNER JOIN clientes ON clientes.id = clientes_id INNER JOIN ubicacion ON factura.ubicacion_id = ubicacion.id";
        $conditions = "factura.ubicacion_id = ".$prefijo." AND factura.factura = ".$numero;
        return $this->find_first($conditions, $columns, $joins);
    }

    public function reimprimir($id) {
        $columns = "columns: prefijo, factura.factura, nombres, apellidos, tipo_rif, rif, cliente, clientes.direccion, clientes.telefono, aerolinea, matricula, peso, capacidad, procedencia, destino, piloto.nombre AS piloto, prestadores, fecha_llegada, fecha_salida, hora_llegada, hora_salida, pasajeros, factura.created_at, iva, exento, monto";
        $joins = "join:
        LEFT JOIN aerolinea ON aerolinea.id = aerolinea_id
        LEFT JOIN avion ON avion.id = avion_id
        LEFT JOIN prestadores ON prestadores.id = prestadores_id
        LEFT JOIN rutas ON rutas.id = rutas_id
        LEFT JOIN piloto ON piloto.id = piloto_id


        INNER JOIN usuario ON usuario.id = usuario_id
        INNER JOIN clientes ON clientes.id = clientes_id
        INNER JOIN ubicacion ON factura.ubicacion_id = ubicacion.id";
        #return $this->find_first($id, $columns, $joins);
        $sql = "SELECT prefijo, factura.factura, nombres, apellidos, clientes.tipo_rif, clientes.rif, cliente, clientes.direccion, clientes.telefono, aerolinea, matricula, peso, capacidad, procedencia, destino, piloto.nombre AS piloto, prestadores, fecha_llegada, fecha_salida, hora_llegada, hora_salida, pasajeros, factura.created_at, iva, exento, monto, factor_iva

        FROM factura

        LEFT JOIN aerolinea ON aerolinea.id = aerolinea_id
        LEFT JOIN avion ON avion.id = avion_id
        LEFT JOIN prestadores ON prestadores.id = prestadores_id
        LEFT JOIN rutas ON rutas.id = rutas_id
        LEFT JOIN piloto ON piloto.id = piloto_id


        INNER JOIN usuario ON usuario.id = usuario_id
        INNER JOIN clientes ON clientes.id = clientes_id
        INNER JOIN ubicacion ON factura.ubicacion_id = ubicacion.id
        WHERE factura.id = ".$id;
        return $this->find_by_sql($sql);
    }

    public function anular($id) {
        $rs = $this->find_first($id);
        $rs->estatus = 2;  // Anulado
        return $rs->save();
    }

}

?>
