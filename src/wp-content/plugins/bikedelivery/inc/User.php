<?php

/**
 * Created by PhpStorm.
 * User: Beto
 * Date: 11/7/2015
 * Time: 2:56 PM
 */
class User
{

    protected $view_update_user = null;


    function __construct()
    {
        $this->set_hooks();
    }

    function set_hooks()
    {
        add_filter('wp_nav_menu_items', array($this,'add_login_logout_link'), 10, 2);
        add_action('user_register', array($this, 'create_session_after_register'));
        add_action('login_form', array($this, 'show_register_message_on_login'));
        add_filter('login_redirect', array($this, 'redirect_user_by_role') ,10, 3);


        $this->view_update_user = new UpdateUserData();
    }

    /***
     * Crea la sesión después de que un usuario se ha registrado
     * @param $user_id Usuario que se autentica
     */
    function create_session_after_register($user_id)
    {
        wp_set_auth_cookie($user_id, true);
    }

    /***
     * En el login muestra un mensaje de registro
     */
    function show_register_message_on_login()
    {
        echo '<div>Si no tienes cuenta <a href="'.get_permalink(ConstantBD::BD_POST_SIGNUP).'">registrate</a> en un solo paso. </div>';
    }


    /***
     * Redirecciona al usuario después de autenticarse dependiendo del rol que tenga
     * @param $redirect_to
     * @param $request
     * @param $user
     * @return false|string
     */
    function redirect_user_by_role($redirect_to, $request, $user)
    {
        global $user;


        if(isset($user->roles) && is_array($user->roles))
        {
            //Si el usuario tiene rol usuario lo envia a "Mis envios"
            if(in_array('usuario', $user->roles))
            {
                $redirect_to = get_permalink(ConstantBD::BD_POST_MY_DELIVERIES_ID);
            }
            else if(in_array('mensajero', $user->roles))
            {
                $redirect_to = get_permalink(ConstantBD::BD_POST_MY_SERVICES_ID);
            }
        }


        return $redirect_to;
    }



    function add_login_logout_link($items, $args)
    {
        if(is_user_logged_in())
        {
            $items .= '<li><a href="'.esc_url( wp_logout_url('/')).'">Salir</a></li>';
        }

        return $items;
    }

}