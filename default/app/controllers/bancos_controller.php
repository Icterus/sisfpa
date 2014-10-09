<?php

class BancosController extends AppController
{
    public function index()
    {
        $this->here = Router::get('controller');
    }


    public function paginar($pagina)
    {
        $campos = Input::request('bancos');
        $this->data = Load::model('bancos')->paginar($pagina, $campos);
    }
    
    public function nuevo()
    {
        if (Input::hasPost('bancos')){
            Load::model('bancos');
            $operacion = new Bancos(Input::post('bancos'));
            if($operacion->guardar()){
                Flash::valid('Banco creado con éxito');
                Router::redirect();
            }
        }
    }

    public function modificar($id)
    {
        View::select('nuevo');
        $bancos = Load::model('bancos');
        if (Input::hasPost('bancos')) {
            $data = Input::post('bancos');
            $operacion = new Bancos($data);
            if($operacion->guardar()){
                Flash::valid('Banco modificado con éxito');
                Router::redirect();
            }
        } else{
            $this->bancos = $bancos->buscar($id);
        }
    }

    public function borrar($id) {
        if(Load::model('bancos')->borrar($id))
            Flash::valid('Banco borrado exitosamente!!!');
    }
}