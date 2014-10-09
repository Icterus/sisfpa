<?php

/**
*
*/
class AvionesController extends AppController
{

    public function index()
    {
        $this->here = Router::get('controller');
    }

    public function paginar($pagina)
    {
        $campos = Input::request('avion');
        $this->data = Load::model('avion')->paginar($pagina, $campos);
    }

    public function nuevo()
    {
        if (Input::hasPost('avion')){
            Load::model('avion');

            $data = Input::post('avion');
            $data['aerolinea_id'] = 1;  # FIXME: este campo ya no va
            $prefijo = Load::model('prefijo')->GetPrefijo($data['prefijo_id']);
            $data['matricula'] = $prefijo.$data['matricula'];
            $data['estatus'] = 1;
            $operacion = new Avion($data);
            if($operacion->guardar()){
                Flash::valid('Avión creado con éxito');
                Router::redirect();
            }
        }
    }

    public function modificar($id)
    {
        View::select('nuevo');
        $avion = Load::model('avion');
        if (Input::hasPost('avion')) {
            $data = Input::post('avion');
            $data['aerolinea_id'] = 1;  # FIXME: este campo ya no va
            $prefijo = Load::model('prefijo')->GetPrefijo($data['prefijo_id']);
            $data['matricula'] = $prefijo.$data['matricula'];
            $operacion = new Avion($data);
            if($operacion->guardar()){
                Flash::valid('Avión modificado con éxito');
                Router::redirect();
            }
        } else{
            $this->avion = $avion->buscar($id);

            $matricula = explode('-', $this->avion->matricula);
            $this->avion->prefijo = $matricula[0];
            $this->avion->matricula = $matricula[1];

        }
    }

    public function borrar($id) {
        if(Load::model('avion')->borrar($id))
            Flash::valid('El avión a sido borrado exitosamente!!!');
    }
}

?>