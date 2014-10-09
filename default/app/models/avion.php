<?php

/**
*
*/
class avion extends ActiveRecord
{
    protected function initialize(){
        $this->validates_uniqueness_of('matricula', 'message: Ya existe un avión con esa matricula');
    }

    public function paginar($page=null, $fields=null)
    {
        $sql = array();
        $criterio = '';
        $conditions = '';
        foreach ($fields as $key => $value) {
            if(!empty($value)){
                if ($key == 'matricula'){
                    $sql[] = ActiveRecord::sql_item_sanitize($key)." LIKE '".$value."%'";
                } else {
                    $sql[] = ActiveRecord::sql_item_sanitize($key)." = '".ActiveRecord::sql_item_sanitize($value)."'";
                }
            }
        }
        if (count($sql)){
            $criterio = implode(' AND ', $sql);
        } else {
            $criterio = NULL;
        }

        $columns = "columns: avion.id AS id, matricula, modelo, aerolinea, marca, basamento";
        $conditions = (!empty($criterio))?$criterio.' AND ':'';
        $conditions .= "avion.estatus = 1";
        $join = "join: INNER JOIN aerolinea ON aerolinea.id = aerolinea_id
            INNER JOIN modelo ON modelo.id = modelo_id
            INNER JOIN marca ON marca.id = marca_id";
        return $this->paginate($conditions, $columns, $join, "page: $page", "per_page: ".PER_PAGE);
    }

    public function guardar(){
        return $this->save();
    }

    public function buscar($id){
        $columns = "columns: avion.id AS id, prefijo_id, matricula, modelo_id, aerolinea_id, tipo, peso, capacidad, basamento, avion.estatus";
        $join = "join: INNER JOIN aerolinea ON aerolinea.id = aerolinea_id
            INNER JOIN modelo ON modelo.id = modelo_id
            INNER JOIN marca ON marca.id = marca_id";
        return $this->find_first('avion.id ='.$id, $join, $columns);
    }

    public function borrar($id){
        if (!empty($id)) {
            $id = ActiveRecord::sql_item_sanitize($id);
            $item = $this->find_first($id);
            if (!$item) {
                Flash::error('ID vacío');
                return False;
            }
            $item->estatus = -1;
            if ($item->save()) return True;
        } else {
            Flash::error('ID vacío');
            return False;
        }
    }


}

?>