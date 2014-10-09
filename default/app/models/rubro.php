<?php
/**
*
*/
class rubro extends ActiveRecord
{

    protected function initialize(){
        $this->validates_uniqueness_of('rubro', 'message: Ya existe un rubro con ese nombre');
    }

    public function paginar($page=null, $fields=null)
    {
        $sql = array();
        $criterio = '';
        $conditions = '';
        foreach ($fields as $key => $value) {
            if(!empty($value)){
                if ($key == 'rubro'){
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

        $columns = "columns: id, rubro";
        $conditions = (!empty($criterio))?$criterio.' AND ':'';
        $conditions .= "rubro.id != False";
        $join = "";
        return $this->paginate($conditions, $columns, $join, "page: $page", "per_page: ".PER_PAGE);
    }

    public function guardar(){
        return $this->save();
    }

    public function buscar($id){
        $columns = "columns: id, rubro";
        $join = "";
        return $this->find_first('rubro.id ='.$id, $join, $columns);
    }


}
?>