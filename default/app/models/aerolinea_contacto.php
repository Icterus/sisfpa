<?php
/**
*
*/


class aerolinea_contacto extends ActiveRecord
{




	public function contactos($id)
		{
		
    	    $columns = "columns: id ,  nombres ,  apellidos , telefono , celular ,  fax , correo , cargo";
    	    $conditions=" aerolinea_contacto_id = $id";
	    return $this->find($columns, $conditions);
		}

}
?>