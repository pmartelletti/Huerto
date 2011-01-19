<?php
/**
 * Table Definition for horas_extras
 */
require_once 'DB/DataObject.php';

class DataObjects_Horas_extras extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'horas_extras';        // table name
    public $he_id;                           // int(4)  primary_key not_null
    public $he_par_id;                       // int(4)   not_null
    public $he_doc_id;                       // int(4)   not_null
    public $he_cantidad_horas;               // int(4)   not_null
    public $he_descripcion;                  // text  
    public $he_observacion;                  // text  

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DataObjects_Horas_extras',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
