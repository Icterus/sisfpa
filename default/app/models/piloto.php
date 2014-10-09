<?php
/**
*
*/
class piloto extends ActiveRecord
{

    protected function initialize(){
        $this->validates_uniqueness_of('documento', 'message: Ya existe un piloto con este documento');
        $this->validates_uniqueness_of('permiso', 'message: Ya existe un piloto con este permiso');
    }

    public function paginar($page=null, $fields=null)
    {
        $sql = array();
        $criterio = '';
        $conditions = '';
        foreach ($fields as $key => $value) {
            if(!empty($value)){
                if ($key == 'documento'){
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

        $columns = "columns: id, nombre, tipo, documento, permiso, telefono, celular, correo, direccion";
        $conditions = (!empty($criterio))?$criterio.' AND ':'';
        $conditions .= "piloto.estatus = 1";
        $join = "";
        return $this->paginate($conditions, $columns, $join, "page: $page", "per_page: ".PER_PAGE);
    }

    public function guardar(){
        return $this->save();
    }

    public function buscar($id){
        $columns = "columns: id, nombre, tipo, documento, permiso, telefono, celular, correo, direccion, estatus";
        $join = "";
        return $this->find_first('piloto.id ='.$id, $join, $columns);
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