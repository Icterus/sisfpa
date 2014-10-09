<?php

/**
* Maestro de los clientes ingresados al Sistema
*/

class ClientesController extends AppController
{


    public function index()
    {

    }

    public function paginar($pagina)
    {
        $campos = Input::request('clientes');
        $this->data = Load::model('clientes')->paginar($pagina, $campos);
    }

    public function nuevo()
    {
        if (Input::hasPost('clientes')){
            Load::model('clientes');

            $data = Input::post('clientes');
            if ( empty($data['documento']) ){
                $data['rif'] = $data['rif'].'-'.$data['terminal_rif'];
            } else {
                $data['tipo_rif'] = $data['tipo_documento'];
                $data['rif'] = $data['documento'];
            }
            $operacion = new Clientes($data);
            if($operacion->guardar()){
                Flash::valid('Cliente creado con éxito');
                Router::redirect();
            }
        }
    }

    public function modificar($id)
    {
        View::select('nuevo');
        $clientes = Load::model('clientes');
        if (Input::hasPost('clientes')) {
            $data = Input::post('clientes');
            if ( empty($data['documento']) ){
                $data['rif'] = $data['rif'].'-'.$data['terminal_rif'];
            } else {
                $data['tipo_rif'] = $data['tipo_documento'];
                $data['rif'] = $data['documento'];
            }
            $operacion = new Clientes($data);
            if($operacion->guardar()){
                Flash::valid('Cliente modificado con éxito');
                Router::redirect();
            }
        } else{
            $this->clientes = $clientes->buscar($id);

            if (stripos($this->clientes->rif, "-") !== false) {
                $this->clientes->tipo_documento_rif = "rif";
                $rif = explode('-', $this->clientes->rif);
                $this->clientes->rif = $rif[0];
                $this->clientes->terminal_rif = $rif[1];
            } else {
                $this->clientes->tipo_documento_rif = "documento";
                $this->clientes->tipo_documento = $this->clientes->tipo_rif;
                $this->clientes->documento = $this->clientes->rif;
                $this->clientes->tipo_rif = '';
                $this->clientes->rif = '';
            }

        }
    }

    public function borrar($id) {
        if(Load::model('clientes')->borrar($id))
            Flash::valid('El cliente ha sido borrado exitosamente!!!');
    }


    }