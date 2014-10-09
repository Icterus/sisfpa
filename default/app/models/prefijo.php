<?php

/**
*
*/
class prefijo extends ActiveRecord
{

    protected function initialize(){
        $this->validates_uniqueness_of('prefijo', 'message: Ya existe el prefijo');
    }

    public function guardar(){
        return $this->save();
    }

    public function paginar($pagina=null){
        $pagina = (!empty($pagina))?1:ActiveRecord::sql_item_sanitize($pagina);
        return $this->paginate("page: $pagina", 'per_page: '.PER_PAGE);
    }

    public function buscar($id){
        $id = ActiveRecord::sql_item_sanitize($id);
        return $this->find_first($id);
    }

    public function GetPrefijo($id){
        $rs = $this->buscar($id);
        return $rs->prefijo.'-';
    }
}

?>