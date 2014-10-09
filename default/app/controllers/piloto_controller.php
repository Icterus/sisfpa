<?php

/**
*
*/

class PilotoController extends AppController
{

    public function index()
    {
        $this->here = Router::get('controller');
    }

    public function paginar($pagina)
    {
        $campos = Input::request('piloto');
        $this->data = Load::model('piloto')->paginar($pagina, $campos);
    }

    public function nuevo()
    {
        if (Input::hasPost('piloto')){
            Load::model('piloto');
            $operacion = new Piloto(Input::post('piloto'));
            if($operacion->guardar()){
                Flash::valid('Piloto creado con éxito');
                Router::redirect();
            }
        }
    }
 
     public function modificar($id)
    {
        View::select('nuevo');
        $piloto = Load::model('piloto');
        if (Input::hasPost('piloto')) {
            $data = Input::post('piloto');
            $operacion = new Piloto($data);
            if($operacion->guardar()){
                Flash::valid('Piloto modificado con éxito');
                Router::redirect();
            }
        } else{
            $this->piloto = $piloto->buscar($id);
        }
    }

    public function borrar($id) {
        if(Load::model('piloto')->borrar($id))
            Flash::valid('El piloto ha sido borrado exitosamente!!!');
    }

}