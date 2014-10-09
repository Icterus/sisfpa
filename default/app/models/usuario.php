<?php
/**
*
*/
class usuario extends ActiveRecord
{

    public static $niveles = array(1=>'Sistema', 2=>'Recaudación', 3=>'Contabilidad', 4=>'Caja', 5=>'Tasa');

    protected function initialize(){
        $this->validates_uniqueness_of('login', 'message: Ya existe alguien con ese login');
        $this->validates_uniqueness_of('cedula', 'message: Esta persona posee una cuenta');
        $this->validates_uniqueness_of('correo', 'message: Esta persona posee una cuenta');
        $this->validates_email_in('correo', 'message: Error en el formato de Correo Electrónico');
        $this->validates_length_of('login', 12, 6, 'message: El nombre de usuario debe tener de 6 a 12 carateres');
    }

    public function paginar($page=null, $fields=null)
    {
        $sql = array();
        $criterio = '';
        $conditions = '';
        foreach ($fields as $key => $value) {
            if(!empty($value)){
                if ($key == 'cedula'){
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

        $columns = "columns: id, login, nombres, apellidos, correo, nivel, estatus, cedula";
        $conditions = (!empty($criterio))?$criterio.' AND ':'';
        $conditions .= "estatus >= 0";
        $join = "";
        return $this->paginate($conditions, $columns, $join, "page: $page", "per_page: ".PER_PAGE);
    }

    public function buscar($id){
        $columns = "columns: id, login, nombres, apellidos, correo, nivel, estatus, cedula, ubicacion_id";
        $join = "";
        return $this->find_first('usuario.id ='.$id, $join, $columns);
    }

    public function consultar($campo, $dato) {
        $conditions = ActiveRecord::sql_item_sanitize($campo)." = '".$dato."'";
        return $this->exists($conditions);
    }

    public function crear(){
        $this->password = md5($this->password);
        if ($this->save()) return True;
    }

    public function borrar($id) {
        $conditions = "id = " . ActiveRecord::sql_item_sanitize($id) . " AND estatus != -1";
        $item = $this->find_first($conditions);
        if (!$item) {
            Flash::error('El usuario no existe');
            return False;
        } elseif ( $item->login == 'admin') {
            Flash::error('El Administrador del sistema no puede ser eliminado');
            return False;
        } else {
            $item->estatus = -1;
            if ($item->save()) return True;
        }
    }

    public function cambiar_clave($id, $password) {
        $conditions = "id = " . ActiveRecord::sql_item_sanitize($id) . " AND estatus = 1";
        $item = $this->find_first($conditions);
        if (!$item) {
            Flash::error('El usuario no existe');
            return False;
        } else {
            $item->password = $password;
            if ($item->crear()) return True;
        }
    }

    public function recaudadores(){
        $sql = "SELECT id, CONCAT(nombres,' ', apellidos) AS nombre FROM usuario WHERE nivel = 4 AND estatus = 1";
        return $this->find_all_by_sql($sql);
    }

    public function recaudador($id){
        $sql = "SELECT usuario.id, CONCAT(nombres,' ', apellidos) AS nombre, prefijo
        FROM usuario
        INNER JOIN ubicacion ON ubicacion_id = ubicacion.id
        WHERE usuario.id = ".$id;
        return $this->find_by_sql($sql);
    }

}
?>