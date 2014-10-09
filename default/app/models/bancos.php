<?php
/**
*
*/
class bancos extends ActiveRecord
{

    protected function initialize(){
        $this->validates_uniqueness_of('bancos', 'message: Ya existe un banco con ese nombre');
    }

    public function paginar($page=null, $fields=null)
    {
        $sql = array();
        $criterio = '';
        $conditions = '';
        foreach ($fields as $key => $value) {
            if(!empty($value)){
                if ($key == 'bancos'){
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

        $columns = "columns: id, bancos";
        $conditions = (!empty($criterio))?$criterio.' AND ':'';
        $conditions .= "bancos.estatus = 1";
        $join = "";
        return $this->paginate($conditions, $columns, $join, "page: $page", "per_page: ".PER_PAGE);
    }

    public function guardar(){
        return $this->save();
    }

    public function buscar($id){
        $columns = "columns: id, bancos";
        $join = "";
        return $this->find_first('bancos.id ='.$id, $join, $columns);
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