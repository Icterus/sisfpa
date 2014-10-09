<?php

/**
*
*/
class MarcaController extends AppController
{

    public function index()
    {
        $this->here = Router::get('controller');
    }

    public function paginar($pagina)
    {
        $campos = Input::request('marca');
        $this->data = Load::model('marca')->paginar($pagina, $campos);
    }

    public function nuevo()
    {
        if (Input::hasPost('marca')){
            Load::model('marca');
            $operacion = new Marca(Input::post('marca'));
            if($operacion->guardar()){
                Flash::valid('Marca creada con éxito');
                Router::redirect();
            }
        }
    }

    public function modificar($id)
    {
        View::select('nuevo');
        $marca = Load::model('marca');
        if (Input::hasPost('marca')) {
            $data = Input::post('marca');
            $operacion = new Marca($data);
            if($operacion->guardar()){
                Flash::valid('Marca modificada con éxito');
                Router::redirect();
            }
        } else{
            $this->marca = $marca->buscar($id);
        }
    }

    public function borrar($id) {
        if(Load::model('marca')->borrar($id))
            Flash::valid('La marca ha sido borrada exitosamente!!!');
    }
}