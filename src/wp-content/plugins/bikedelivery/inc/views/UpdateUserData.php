<?php

/**
 * Vista que muestra la información del usuario
 * Created by PhpStorm.
 * User: Beto
 * Date: 11/7/2015
 * Time: 3:51 PM
 */
class UpdateUserData
{
    function __construct()
    {
        add_shortcode('bd_update_user_form', array($this, 'show_view'));
    }

    /***
     *Muestra la vista
     * $attr
     *      id = id del formulario del plugin de formularios
     */
    function show_view($attr)
    {
        //el usuario debe estar autenticado
        if (is_user_logged_in()) {
            //Debe tener la propiedad id cargada la
            if (isset($attr) && isset($attr['id'])) {




                $form_id = $attr['id'];
                crf_frontend_script();

                ?>
                <div class="crf_contact_form">
                    <form enctype="multipart/form-data" method="post" action="" id="crf_contact_form" name="crf_contact_form">
                        <?php
                        wp_nonce_field('view_crf_form');
                        $this->init_form($form_id);
                        ?>
                    </form>
                </div>

                <?php

            }

        }
    }

    /***
     * Realiza las validaciones si el formulario fue guardado
     */
    function validate_form_saved($fields)
    {
        if(isset($_POST['submit']))
        {
            $current_user = wp_get_current_user();

            $user_email = $_POST['user_email']; // receiving email address
            //Si cambio de usuario y existe es error y no continua
            if($current_user->user_email != $_POST['user_email'] && username_exists($user_email) != false)
            {
                ?>
                <script>
                    jQuery(document).on('ready', function(){
                        jQuery("#emailErr").html("Intenta con otro email, este ya está registrado");
                        jQuery("#emailErr").show();
                    }); </script>
                <?php
                return;
            }

            //Actualiza los daos basicos
            $current_user->user_email = $user_email;
            $current_user->user_login = $user_email;
            $current_user->first_name = $_POST['user_first_name'];
            wp_update_user($current_user);

            //Recorre todos los campos del formulario y valida
            foreach($fields as $field)
            {
                $keyfield = sanitize_key($field->Name)."_".$field->Id;
                update_user_meta($current_user->ID, sanitize_key($field->Name), $_POST[$keyfield],$current_user->get(sanitize_key($field->Name)) );
            }
        }
    }

    /***
     * Muestra los campos del formulario
     * @param $form_id
     */
    function init_form($form_id)
    {
        global $wpdb;

        $crf_forms = new crf_basic_fields();

        $crf_fields = $wpdb->prefix . "crf_fields";

        $qry1 = "select * from $crf_fields where Form_Id = '" . $form_id . "' order by ordering asc";
        $reg1 = $wpdb->get_results($qry1);

        //Actualiza los campos
        $this->validate_form_saved($reg1);


        $current_user = wp_get_current_user();

        //Agrega los campos basicos
        $this->show_basic_fields($current_user);

        //Agrega todos los campos del formulario
        foreach ($reg1 as $row1) {
            $key = sanitize_key($row1->Name);
            $value_key = $current_user->get($key);
            $crf_forms->crf_get_custom_form_fields($row1, $value_key);
        }

        $this->show_form_button();

        include ABSPATH.'/wp-content/plugins/custom-registration-form-builder-with-submission-manager/frontendjs.php';

    }


    function show_form_button()
    {
        ?>
        <div class="customcrferror crf_error_text" style="display:none"></div>
        <div class="UltimatePB-Button-area crf_input crf_input_submit">
            <input type="hidden" class="crf_form_type" id="form_type" name="form_type" value="reg_form">
            <input type="submit" name="submit" id="submit" class="crf_contact_submit" value="Actualizar mis datos">
        </div>

        <?php

    }


    /***
     * Muestra la información básica del usuario
     * @param $user WP_User autenticado
     */
    function show_basic_fields($user)
    {
        ?>

        <div class="formtable">
            <div class="crf_label">
                <label for="user_first_name"><?php _e('First Name');?>
                </label>
            </div>
            <div class="crf_input crf_required" data-msg="<?php _e('First Name');?> es obligatorio">
                <input type="text" size="20"  value="<?php echo $user->user_firstname; ?>" class="input" id="user_first_name" name="user_first_name">
                <div class="reg_frontErr crf_error_text custom_error" style="display:none;" id="nameErr"></div>
            </div>
        </div>
        <div class="formtable">
            <div class="crf_label">
                <label for="user_email"><?php _e('E-Mail');?>
                </label>
            </div>
            <div class="crf_input crf_required" data-msg="<?php _e('E-Mail');?> es obligatorio">
                <input type="text" size="20"  value="<?php echo $user->user_email; ?>" class="input" id="user_email" name="user_email">
                <div class="reg_frontErr crf_error_text custom_error" style="display:none;" id="emailErr"></div>
            </div>
        </div>


        <?php
    }
}
?>