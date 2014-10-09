<?php
/**
*
*/

Load::models('aerolinea_contacto');
class AerolineaContactoController extends AppController {

    public function ver($id)
	{
            $aecontacto = new aerolinea_contacto();
            return $this->contactos_aerolinea = $aecontacto->contactos($id);
    }

}
?>