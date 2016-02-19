# Plugin para Wordpress
__contact-form-7-prospectsuite__
_V 1.0 (Beta)_

My "Hello World" in plugins Wordpress
This try connect forms from Contact Form 7 of Wordpress to a service suite *Prospect Suite*

This is the status for this:
- [x] Install in Wordpress
- [x] Create a page from general configuration
- [x] Create the lateral menu in the administrator of Wordpress
- [x] Create a Tab in Contact Form with a field for the *Key*
- [x] Save the *Key* in the Tab in the postmeta table of Wordpress (This is the better?)
- [ ] Generate the send of the info via jQuery and/or cURL

The send info must be via POST method, for example

Example with jQuery:
```javascript
$.post(url_prospect, { name: your_name, email: your_email, key:"tgT5s4D7Ns25" });
```

The fields info must be the same fields in the Contact Form 7, the *key* value is the value saved before. This work fine (i thing), so i need send the info using the method desired.

I can't do it this, i'm die in this part, the function before_send_mail is:

````php
function prospect_cf7_before_send( $contact_form ){
	$contact_form_id = $contact_form->id();
	$prospectkey = get_post_meta( $contact_form->id(), 'prospect_cf7_prospectkey', true );
}
add_action( 'wpcf7_before_send_mail', 'prospect_cf7_before_send' );







-------------- SPANISH -------------------

Mi "Hola mundo" de plugins en Wordpress
Intenta conectar los formularios Contact Form 7 de Wordpress con la suite Prospect Suite

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
