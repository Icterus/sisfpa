<?php
/**
*
*/
class rutas extends ActiveRecord
{
    public function paginar($page=null, $fields=null)
    {
        $sql = array();
        $criterio = '';
        $conditions = '';
        foreach ($fields as $key => $value) {
            if(!empty($value)){
                $sql[] = ActiveRecord::sql_item_sanitize($key)." LIKE '%".$value."%'";
            }
        }
        if (count($sql)){
            $criterio = implode(' AND ', $sql);
        } else {
            $criterio = NULL;
        }

        $columns = "columns: id, destino, procedencia, tipo";
        $conditions = (!empty($criterio))?$criterio:NULL;
        return $this->paginate($conditions, $columns, "page: $page", "per_page: ".PER_PAGE);
    }

    public function guardar(){
        return $this->save();
    }
}
?>