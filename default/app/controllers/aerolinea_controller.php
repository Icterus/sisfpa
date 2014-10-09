<?php

/**
*
*/

class AerolineaController extends AppController
{

    public function index()
    {
        $this->here = Router::get('controller');
    }

    public function paginar($pagina)
    {
        $campos = Input::request('aerolinea');
        $this->data = Load::model('aerolinea')->paginar($pagina, $campos);
    }

    public function nuevo()
    {
        if (Input::hasPost('aerolinea')){
            Load::model('aerolinea');

            $data = Input::post('aerolinea');
            $data['rif'] = $data['rif'].'-'.$data['terminal_rif'];
            $operacion = new Aerolinea($data);
            if($operacion->guardar()){
                Flash::valid('Aerolinea creada con éxito');
                Router::redirect();
            }
        }
    }

    public function modificar($id)
    {
        View::select('nuevo');
        $aerolinea = Load::model('aerolinea');
        if (Input::hasPost('aerolinea')) {
            $data = Input::post('aerolinea');
            $data['rif'] = $data['rif'].'-'.$data['terminal_rif'];
            $operacion = new Aerolinea($data);
            if($operacion->guardar()){
                Flash::valid('Aerolinea modificada con éxito');
                Router::redirect();
            }
        } else{
            $this->aerolinea = $aerolinea->buscar($id);

            $rif = explode('-', $this->aerolinea->rif);
            $this->aerolinea->rif = $rif[0];
            $this->aerolinea->terminal_rif = $rif[1];

        }
    }

    public function borrar($id) {
        if(Load::model('aerolinea')->borrar($id))
            Flash::valid('Aerolinea borrada exitosamente!!!');
    }

    }