<?php

/**
*  Maestro  de Los Modelos Disponibles de Aviones
*/
class ModeloController extends AppController
{
    public function index()
    {
        $this->here = Router::get('controller');
    }


    public function paginar($pagina)
    {
        $campos = Input::request('modelo');
        $this->data = Load::model('modelo')->paginar($pagina, $campos);
    }
    
    public function nuevo()
    {
        if (Input::hasPost('modelo')){
            Load::model('modelo');
            $operacion = new Modelo(Input::post('modelo'));
            if($operacion->guardar()){
                Flash::valid('Modelo creado con éxito');
                Router::redirect();
            }
        }
    }

    public function modificar($id)
    {
        View::select('nuevo');
        $modelo = Load::model('modelo');
        if (Input::hasPost('modelo')) {
            $data = Input::post('modelo');
            $operacion = new modelo($data);
            if($operacion->guardar()){
                Flash::valid('Modelo modificado con éxito');
                Router::redirect();
            }
        } else{
            $this->modelo = $modelo->buscar($id);
        }
    }

    public function borrar($id) {
        if(Load::model('modelo')->borrar($id))
            Flash::valid('Modelo borrado exitosamente!!!');
    }

}