<?php


class RubroController extends AppController
{
    public function index()
    {
        $this->here = Router::get('controller');
    }


    public function paginar($pagina)
    {
        $campos = Input::request('rubro');
        $this->data = Load::model('rubro')->paginar($pagina, $campos);
    }
    
    public function nuevo()
    {
        if (Input::hasPost('rubro')){
            Load::model('rubro');
            $operacion = new Rubro(Input::post('rubro'));
            if($operacion->guardar()){
                Flash::valid('Rubro creado con éxito');
                Router::redirect();
            }
        }
    }

    public function modificar($id)
    {
        View::select('nuevo');
        $rubro = Load::model('rubro');
        if (Input::hasPost('rubro')) {
            $data = Input::post('rubro');
            $operacion = new Rubro($data);
            if($operacion->guardar()){
                Flash::valid('Rubro modificado con éxito');
                Router::redirect();
            }
        } else{
            $this->rubro = $rubro->buscar($id);
        }
    }

}