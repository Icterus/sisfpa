<?php
/**
* Maestro de los Clientes Ingresados al Sistema
*/
class clientes extends ActiveRecord
{
    protected $logger = True;
    protected function initialize(){
        $this->validates_uniqueness_of('cliente', 'message: Ya existe este cliente');
        $this->validates_uniqueness_of('rif', 'message: Ya existe un cliente con este rif');
    }

    public function paginar($page=null, $fields=null)
    {
        $sql = array();
        $criterio = '';
        $conditions = '';
        /* foreach ($fields as $key => $value) {
            if(!empty($value)){
                if ($key == 'cliente'){
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
        }*/

        $columns = "columns: id, cliente, tipo_rif, rif, telefono, direccion";
        #$conditions = (!empty($criterio))?$criterio.' AND ':'';
        $conditions = " cliente LIKE '".$fields['cliente']."%' AND ";
        $conditions .= "clientes.estatus = 1";
        return $this->paginate($conditions, $columns, $join, "page: $page", "per_page: ".PER_PAGE);
    }

    public function guardar(){
        return $this->save();
    }

    public function buscar($id){
        $columns = "columns: id, cliente, tipo_rif, rif, telefono, fax, correo, direccion, credito_id";
        $join = '';
        return $this->find_first('clientes.id ='.$id, $join, $columns);
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

    public function cliente() {
        return $this->find('columns: id, cliente', 'order: cliente ASC');
    }
}
?>