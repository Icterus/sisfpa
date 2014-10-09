<?php
/**
*
*/
class aerolinea extends ActiveRecord
{

    protected function initialize(){
        $this->validates_uniqueness_of('aerolinea', 'message: Ya existe esa Aerolinea');
        $this->validates_uniqueness_of('rif', 'message: Ya existe una aerolinea con este rif');
    }

    public function paginar($page=null, $fields=null)
    {
        $sql = array();
        $criterio = '';
        $conditions = '';
        foreach ($fields as $key => $value) {
            if(!empty($value)){
                if ($key == 'aerolinea'){
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

        $columns = "columns: id, aerolinea, tipo_rif, rif, telefono, email";
        $conditions = (!empty($criterio))?$criterio.' AND ':'';
        $conditions .= "aerolinea.estatus = 1";
        return $this->paginate($conditions, $columns, $join, "page: $page", "per_page: ".PER_PAGE);
    }



    public function guardar(){
        return $this->save();
    }

    public function buscar($id){
        $columns = "columns: id, aerolinea, tipo_rif, rif, telefono, telefono_opc, fax, email, direccion_fiscal";
        $join = '';
        return $this->find_first('aerolinea.id ='.$id, $join, $columns);
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

    public function listar() {
        $columns = "columns: id, aerolinea";
        $conditions = "estatus = 1";
        return $this->find($columns, $conditions);
    }

}
?>
