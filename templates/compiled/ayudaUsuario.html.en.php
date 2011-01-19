<div class="span-23 last" style="margin-bottom: 10px">

    <h3>Ayuda para el usuario</h3>
    <p>Esta sección está pensada para ayudar a los usuarios a comprender de una manera eficiente
    el nuevo sistema de partes diarios.</p>
    <p>Si bien se ha tratado de llevar a un minimo las dificultades que el usuario puediera tener a la hora
    de interactuar con el sistema, es de esperar que, ante lo novedoso, surjan dudas sobre su funcionamiento.</p>
    <p>Para eso es que ponemos a disposicion de todos los usuarios un <b>Manual del usuario</b>, en donde se podran despejar
    todas las dudas respecto al funcionamiento del sistema. El mismo deberia poder solucionar cualquier incoveniente / duda
    que tenga a la hora de manejar el sistema.</p>
    <p><img src="images/guia_pdf.jpg" /><br>
        <b>Descargar Manual de Usuario</b></p>
    <p>Asi mismo, contamos con un sistema de reporte de fallos. Mediante el mismo, el usuario podra informar al administrador del
        sitio que ha tenido problemas al insertar un parte, y el administrador hara lo posible para encontrar el fallo y continuar
        normalmente con el trabajo.</p>

    <form action="index.php" id="contact_form">
        <fieldset>

            <p>
                <label>Motivo de contacto: </label><?php echo $this->elements['ie_motivo']->toHtml();?>
            </p>

            <p>
                <label>Detalles del error: </label><br>
                <?php echo $this->elements['ie_detalle']->toHtml();?>
            </p>

            <p>
                <label>Observaciones adicionales:</label><br>
                <?php echo $this->elements['ie_observaciones']->toHtml();?>
            </p>

        </fieldset>
        
		<?php echo $this->elements['send']->toHtml();?>
    </form>
    <div id="response"></div>

    <script type="text/javascript">
        $(document).ready(function(){
            // preparo el formulario para mandarlo.
			$("#send").button();
			
			$("#contact_form").validate({
			errorClass: "errorValidate",
			submitHandler: function(form) {
				$.post("index.php?action=sendMail", $(form).serialize(), function(response){
                    $("#response").html( response.statusMsg );
                    if( response.statusCode == "100" ){
                        $("#response").addClass("success");
                    } else{
                        $("#response").addClass("error");
                    }
                    $("#contact_form").slideUp();
                }, "json");
				
				return false;
			}
		});
		
		$.validator.addMethod("cRequired", $.validator.methods.required, "*");
		$.validator.addClassRules("customRequired", {cRequired: true});
			
        })
    </script>
   
</div>