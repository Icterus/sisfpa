<?php



class RutasController extends AppController
{

    public function index()
    {
        $this->here = Router::get('controller');
    }

    public function paginar($pagina)
    {
        $campos = Input::request('rutas');
        $this->data = Load::model('rutas')->paginar($pagina, $campos);
    }

    public function nuevo()
    {
        if (Input::hasPost('ruta')){
            Load::model('rutas');
            $paises = Load::model('aeropuerto')->paises();
            $data = Input::post('ruta');
            $idppais = $data['ppais'];
            $iddpais = $data['dpais'];
            $data['tipo'] = ( $paises[$idppais] == 'VENEZUELA' && $paises[$iddpais] == 'VENEZUELA' )?'N':'I';
            $operacion1 = new Rutas($data); // Primera Ruta
            $d = $data['destino'];
            $p = $data['procedencia'];
            if ( $d == $p ){
                if($operacion1->guardar()){
                    Flash::valid('Ruta creado con éxito');
                    Router::redirect();
                }
            } else {
                $o = array('destino' => $p, 'procedencia' => $d, 'tipo' => $data['tipo']);
                $operacion2 = new Rutas($o); // Segunda Ruta si son diferentes
                if($operacion1->guardar() && $operacion2->guardar()){
                    Flash::valid('Ruta creado con éxito');
                    Router::redirect();
                }
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

    public function lista($pais)
    {
        $this->data = Load::model('aeropuerto')->aeropuertos($pais);
    }

    public function aeropuertos()
    {
        if ( Input::hasPost('ruta') ) {
            Load::model('aeropuerto');
            $aero = new Aeropuerto(Input::post('ruta'));
            if ($aero->create()) {
                Flash::valid('Aeropuerto creado con éxito');
                Input::delete();
            }
        }
    }

}
