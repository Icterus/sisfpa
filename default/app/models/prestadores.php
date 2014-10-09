<?php
/**
* Maestro de los Clientes Ingresados al Sistema
*/
class prestadores extends ActiveRecord
{

    protected function initialize(){
        $this->validates_uniqueness_of('prestadores', 'message: Ya existe este prestador de servicio');
        $this->validates_uniqueness_of('rif', 'message: Ya existe un cliente con este rif');
    }

    public function paginar($page=null, $fields=null)
    {
        $sql = array();
        $criterio = '';
        $conditions = '';
        foreach ($fields as $key => $value) {
            if(!empty($value)){
                if ($key == 'prestadores'){
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

        $columns = "columns: id, prestadores, tipo_rif, rif, telefono, direccion";
        $conditions = (!empty($criterio))?$criterio.' AND ':'';
        $conditions .= "estatus = 1";
        return $this->paginate($conditions, $columns, $join, "page: $page", "per_page: ".PER_PAGE);
    }

    public function guardar(){
        return $this->save();
    }

    public function buscar($id){
        $columns = "columns: id, prestadores, tipo_rif, rif, telefono, fax, email, direccion";
        return $this->find_first($id, $columns);
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

    public function prestador(){
        return $this->find('columns: id, prestadores');
    }
}
?>