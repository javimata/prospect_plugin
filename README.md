# Plugin para Wordpress
__contact-form-7-prospectsuite__
_V 1.0 (Beta)_

Mi "Hola mundo" de plugins en Wordpress
Intenta conectar los formularios Contacto Form 7 de Wordpress con la suite Prospect Suite

- [x] Instalacion en Wordpress
- [x] Crea pagina de configuración general
- [x] Crea menu lateral en el administrador de wordpress
- [x] Crea Tab en Contact Form con campo para la Key del formulario
- [x] Guarda la Key ingresada
- [ ] Genera el envio de los datos via jQuery o cURL

El envío a Prospect Suite debe ser mediante metodo POST

Ejemplo con jQuery:
```javascript
$.post(url_prospect, { nombre: fnombre, email: femail, key:"tgT5s4D7Ns25" });
```

Los campos deben tomarse automaticamente de los campos del formulario Contact Form 7, el valor de **key** es el valor que se guarda en la tab del plugin con el campo **$prospectkey** que se guarda en un registro de postmeta, quisiera poder seleccionar el metodo de envio, usando jQuery o cURL.

Lo que me falta configurar es la funcion before_send_mail:

````php
function prospect_cf7_before_send( $contact_form ){
	$contact_form_id = $contact_form->id();
	$prospectkey = get_post_meta( $contact_form->id(), 'prospect_cf7_prospectkey', true );
}
add_action( 'wpcf7_before_send_mail', 'prospect_cf7_before_send' );
```
