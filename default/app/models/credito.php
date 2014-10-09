<?php
/**
*
*/
class credito extends ActiveRecord
{

    protected function initialize(){
        $this->validates_uniqueness_of('credito', 'message: Ya existe un credito con esa descripción');
    }

    public function paginar($page=null, $fields=null)
    {
        $sql = array();
        $criterio = '';
        $conditions = '';
        foreach ($fields as $key => $value) {
            if(!empty($value)){
                if ($key == 'credito'){
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

        $columns = "columns: id, credito";
        $conditions = (!empty($criterio))?$criterio.' AND ':'';
        $conditions .= "credito.estatus = 1";
        $join = "";
        return $this->paginate($conditions, $columns, $join, "page: $page", "per_page: ".PER_PAGE);
    }

    public function guardar(){
        return $this->save();
    }

    public function buscar($id){
        $columns = "columns: id, credito";
        $join = "";
        return $this->find_first('credito.id ='.$id, $join, $columns);
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