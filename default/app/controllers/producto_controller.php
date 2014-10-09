<?php

class ProductoController extends AppController
{

    public function index()
    {
        $this->here = Router::get('controller');
    }

    public function paginar($pagina)
    {
        $campos = Input::request('productos');
        $this->data = Load::model('productos')->paginar($pagina, $campos);
    }

    public function nuevo()
    {
        if (Input::hasPost('productos')){
            Load::model('productos');
            $data = Input::post('productos');
            $data['mora'] = empty($data['mora'])?"0":"1";
            $data['exento'] = empty($data['exento'])?"0":"1";
            $operacion = new Productos($data);
            if($operacion->guardar()){
                Flash::valid('Producto creado con éxito');
                Router::redirect();
            }
        }
    }

    public function modificar($id)
    {
        View::select('nuevo');
        $productos = Load::model('productos');
        if (Input::hasPost('productos')) {
            $data = Input::post('productos');
            $data['mora'] = empty($data['mora'])?"0":"1";
            $data['exento'] = empty($data['exento'])?"0":"1";
            $operacion = new Productos($data);
            if($operacion->guardar()){
                Flash::valid('Producto modificado con éxito');
                Router::redirect();
            }
        } else{
            $this->productos = $productos->buscar($id);
        }
    }

    public function borrar($id) {
        if(Load::model('productos')->borrar($id))
            Flash::valid('Producto borrado exitosamente!!!');
    }

    }
