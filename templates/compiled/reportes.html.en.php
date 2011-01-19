<script type="text/javascript" src="js/ingresoReportes.js"></script>
<script type="text/javascript">
$(document).ready(function(){
        $("#sendInfo").button();
        $("#menuBtn").button().click(function(){
                $("#dialog").dialog({
                        resizable: false,
                        height:140,
                        modal: true,
                        buttons: {
                                "Salir del sistema": function() {
                                        $.post("login.php", {action:"logout"}, function(){
                                                window.location = "index.php";
                                        });
                                },
                                "Cancelar": function() {
                                        $( this ).dialog( "close" );
                                }
                        }
                });

                return false;
        })

        $("#par_fecha").datepicker({ dateFormat: 'yy-mm-dd' });

        // dynamic inputs
        var i=0;
        $("#addParte").click(function(){
                agregarTr("#parte", 0, i, "re");
                i++;
        });
        $("#addAcc").click(function(){
                agregarTr("#accidentes",1,i,"acc");
                i++;
        });
        $("#addExtra").click(function(){
                agregarTr("#horasExtras", 2, i, "he");
                i++;
        });


})
</script>
<div class="span-23 last">
    <div id="errores" class="error noShow">Por favor, llene los campos marcados con '*' (asterisco)</div>
    <form id="reportes" action="index.php?action=ingresoReportes" method="post">

            <div style="text-align:right">
                    <label for="par_us_id">Secretaria: </label>
                    <?php echo $this->elements['par_us_id']->toHtml();?>
                    <label for="fecha">Fecha del parte: </label>
                    <?php echo $this->elements['par_fecha']->toHtml();?>
            </div>
    <?php 
if (!isset($this->elements['par_sec_id']->attributes['value'])) {
    $this->elements['par_sec_id']->attributes['value'] = '';
    $this->elements['par_sec_id']->attributes['value'] .=  htmlspecialchars($t->seccion_id);
}
$_attributes_used = array('value');
echo $this->elements['par_sec_id']->toHtml();
if (isset($_attributes_used)) {  foreach($_attributes_used as $_a) {
    unset($this->elements['par_sec_id']->attributes[$_a]);
}}
?>
    <fieldset>
            <legend>Parte diario</legend>

            <table id="parte">
                    <thead>
                            <tr>
                                    <th colspan="2">Tipo</th>
                                    <th>Docente</th>
                                    <th>Motivo</th>
                                    <th>Cargo</th>
                                    <th>Horas</th>
                                    <th>Sit. de revista</th>
                            </tr>
                    </thead>
                    <tbody>

                    </tbody>
            </table>
            <p><a href="#" id="addParte">Agregar un docente</a></p>

    </fieldset>

    <fieldset>
            <legend>Accidentes</legend>

            <table id="accidentes">
                    <thead>
                            <tr>
                                    <th colspan="2">Tipo</th>
                                    <th>Nombre del accidentado</th>
                                    <th>Reporte a ART</th>
                                    <th>Descripcion</th>
                            </tr>
                    </thead>
                    <tbody>

                    </tbody>
            </table>
            <p><a href="#" id="addAcc">Agregar otro accidente</a></p>

    </fieldset>

    <fieldset>
            <legend>Horas Extras</legend>

            <table id="horasExtras">
                    <thead>
                            <tr>
                                    <th colspan="2">Docente</th>
                                    <th>Cantidad de Horas</th>
                                    <th>Descripcion</th>
                            </tr>
                    </thead>
                    <tbody>

                    </tbody>
            </table>
            <p><a href="#" id="addExtra">Agregar otro docente</a></p>

    </fieldset>

    <?php echo $this->elements['sendInfo']->toHtml();?>

    </form>
</div>
<div id="message"></div>