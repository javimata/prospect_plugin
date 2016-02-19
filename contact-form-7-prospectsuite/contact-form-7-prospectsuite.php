<?php
/**
 * @package Prospect Suite para Contact Form
 * @version 1.0
 */
/*
Plugin Name: Prospect Suite para CF7
Plugin URI: http://www.prospectsuite.com/plugins
Description: Implementación de Prospect Suite para Contact form 7.
Author: @Javi_Mata
Version: 1.0
Author URI: http://www.javimata.com/
*/


/**
 * Verify CF7 dependencies.
 */
function prospect_cf7_admin_notice() {
    // Verify that CF7 is active and updated to the required version (currently 3.9.0)
    if ( is_plugin_active('contact-form-7/wp-contact-form-7.php') ) {
        $wpcf7_path = plugin_dir_path( dirname(__FILE__) ) . 'contact-form-7/wp-contact-form-7.php';
        $wpcf7_plugin_data = get_plugin_data( $wpcf7_path, false, false);
        $wpcf7_version = (int)preg_replace('/[.]/', '', $wpcf7_plugin_data['Version']);
        // CF7 drops the ending ".0" for new major releases (e.g. Version 4.0 instead of 4.0.0...which would make the above version "40")
        // We need to make sure this value has a digit in the 100s place.
        if ( $wpcf7_version < 100 ) {
            $wpcf7_version = $wpcf7_version * 10;
        }
        // If CF7 version is < 3.9.0
        if ( $wpcf7_version < 390 ) {
            echo '<div class="error"><p><strong>Warning: </strong>Contact Form 7 - Success Page Redirects requires that you have the latest version of Contact Form 7 installed. Please upgrade now.</p></div>';
        }
    }
    // If it's not installed and activated, throw an error
    else {
        echo '<div class="error"><p>Contact Form 7 is not activated. The Contact Form 7 Plugin must be installed and activated before you can use Success Page Redirects.</p></div>';
    }
}
add_action( 'admin_notices', 'prospect_cf7_admin_notice' );




/*
 * Add a panel tab
 * CF7 >= 4.2
 */
function prospect_cf7_editor_panels ( $panels ) {

    $panels['Prospect'] = array(
        'title' => __( 'Prospect Suite', 'contact-form-7' ),
        'callback' => 'wpcf7_editor_panel_prospect'
    );

    return $panels;

}
add_filter( 'wpcf7_editor_panels', 'prospect_cf7_editor_panels' );



/*
 * Agrega contenido del tag con campo
 */
function wpcf7_editor_panel_prospect($post) {
	$prospect_cf7_key = get_post_meta( $post->id(), 'prospectkey', true );
	
	?>
	<h3>Configuración de Prospect Suite</h3>
	<fieldset>
	<legend>Los campos serán enviados con el metodo configurado y el nombre del mismo, solo debe agregar una Key para el formulario, esta Key es proporcionada por la suite Prospect Suite</legend>
	<input type="text" id="wpcf7-prospectkey" name="wpcf7-prospectkey" value="<?php echo $prospect_cf7_key; ?>">
	</fieldset>
	<?php
}



/*
 * Guarda la información del campo
 */
function prospect_cf7_save_contact_form( $contact_form ) {
    $contact_form_id = $contact_form->id();

    if ( !isset( $_POST ) || empty( $_POST ) || !isset( $_POST['wpcf7-prospectkey'] ) ) {
        return;
    }
    else {

        update_post_meta( $contact_form_id, 'prospectkey', $_POST['wpcf7-prospectkey'] );
    }
}
add_action( 'wpcf7_after_save', 'prospect_cf7_save_contact_form' );




// Accion antes del envio del correo
function prospect_cf7_before_send( $contact_form ){
	$contact_form_id = $contact_form->id();
	$prospectkey = get_post_meta( $contact_form->id(), 'prospect_cf7_prospectkey', true );

	echo $contact_form->posted_data();

	// $submission = WPCF7_Submission::get_instance();
	// var_dump($submission);

	// foreach ($submission->posted_data as $campo => $valor) {
	// 	echo  $campo . " - " . $valor . "<br>";
	// }

}
add_action( 'wpcf7_before_send_mail', 'prospect_cf7_before_send' );




// Accion despues del envio
function prospect_cf7_form_submitted( $contact_form ) {
    $contact_form_id = $contact_form->id();

    // Send us to a success page, if there is one
    // $success_page = get_post_meta( $contact_form_id, '_cf7_success_page_key', true );
    // if ( !empty($success_page) ) {
    //     wp_redirect( get_permalink( $success_page ) );
    //     die();
    // }

}
add_action( 'wpcf7_mail_sent', 'prospect_cf7_form_submitted' );





// Crea menu lateral administrador de Wordpress
function prospect_cf7_menu()
{
    add_menu_page('Prospect Suite para CF7', 'Prospect Suite para CF7', 'administrator', __FILE__, 'prospect_settings_page', plugins_url('assets/images/icon.png', __FILE__));

    add_action('admin_init', 'register_prospect_settings');
}
add_action( 'admin_menu', 'prospect_cf7_menu');


// Configuracion de valores de la pagina de configuración
function register_prospect_settings()
{
    //register settings
    register_setting('prospect-settings-group', 'prospect-metodoEnvio');
    register_setting('prospect-settings-group', 'prospect-clienteKey');
}


// Crea pagina de configuración general en el administrador de Wordpress
function prospect_settings_page()
{
    ?>
    <div class="wrap">
        <h2>Prospect Suite para Contact Form 7</h2>

        <p>
            Implementa el envio de datos desde Contact Form 7 a la suite Prospect Suite, puede seleccionar un metodo de envio, por default se utilizará el metodo $.post de jQuery
        </p>

        <form method="post" action="options.php">
            <?php settings_fields('prospect-settings-group'); ?>
            <?php do_settings_sections('prospect-settings-group'); 

            $metodo     = get_option('prospect-metodoEnvio');
            $clienteKey = get_option('prospect-clienteKey');

            ?>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row">Metodo de envio</th>
                    <td><select name="prospect-metodoEnvio">
						<option value="jQuery" <?php if ( $metodo=="jQuery" || $metodo == "" ): ?>selected<?php endif;?>>jQuery (Default)</option>
						<option value="cURL" <?php if ( $metodo=="cURL"): ?>selected<?php endif;?>>cURL</option>
                    </select>
                    </td>
                </tr>

                <tr valign="top">
                    <th scope="row">Key del cliente</th>
                    <td><input type="text" name="prospect-clienteKey" value="<?php echo get_option('prospect-clienteKey'); ?>"/></td>
                </tr>

            </table>

            <?php submit_button();

            ?>

        </form>
    </div>
<?php
}
