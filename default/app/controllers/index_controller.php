<?php

/**
 * Controller por defecto si no se usa el routes
 *
 */
class IndexController extends AppController
{

    public function index()
    {
        View::template('login');
        if (Input::hasPost('login')) {
            $usuario = Input::post('usuario');
            $clave = md5(Input::post('clave'));
            $this->auth = new Auth('model', 'class: usuario', "login: $usuario", "password: $clave", "estatus: 1");
            if ( !$this->auth->authenticate() ) {
                Flash::error("Usuario o Contraseña es Inválida");
            } else {
                Session::set('id', $this->auth->get('id'));
                Session::set('login', $this->auth->get('login'));
                Session::set('nivel', $this->auth->get('nivel'));
                Session::set('nombre', $this->auth->get('nombres') . ' ' . $this->auth->get('apellidos'));
                Router::redirect('dashboard/');
            }
        }  elseif ( Auth::is_valid() ) {
            Router::redirect('dashboard/');
        }
        //  else {
        //     Flash::error("Error de Autenticación");
        // }
    }

    public function dashboard() {

    }

    public function salir() {
        Auth::destroy_identity();
        Session::delete('id');
        Session::delete('login');
        Session::delete('nivel');
        Session::delete('nombre');
        Flash::info('Sesión Cerrada');
        Router::redirect('/');
    }
}
