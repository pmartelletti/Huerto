/**
 * @author pablo
 */

function showMessage(selector, msj, clase, icon){
	var div = $(selector);
	
	div.hide();
	
	var mensaje = '<p style="padding:5px; margin:0 "><span class="ui-icon ' + icon + '" style="float: left; margin-right: .3em;"></span>' + msj + '</p>';
	div.html(mensaje).removeClass("ui-state-error").addClass(clase).fadeIn();
	
}
