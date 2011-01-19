<?php
/**
 * Table Definition for informe_errores
 */
require_once 'DB/DataObject.php';

class DataObjects_Informe_errores extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'informe_errores';     // table name
    public $ie_id;                           // int(4)  primary_key not_null
    public $ie_fecha;                        // date  
    public $ie_sec_id;                       // int(4)  
    public $ie_motivo;                       // varchar(90)  
    public $ie_detalle;                      // text  
    public $ie_observaciones;                // text  

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DataObjects_Informe_errores',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
