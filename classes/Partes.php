<?php
/**
 * Table Definition for partes
 */
require_once 'DB/DataObject.php';

class DataObjects_Partes extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'partes';              // table name
    public $par_id;                          // int(4)  primary_key not_null
    public $par_fecha;                       // date   not_null
    public $par_aprobado;                    // tinyint(1)   not_null
    public $par_observaciones;               // text   not_null
    public $par_sec_id;                      // int(4)   not_null
    public $par_us_id;                       // int(4)   not_null
    public $par_us_id_aprobacion;            // int(4)  
    public $par_fecha_aprobacion;            // varchar(45)  

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DataObjects_Partes',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
