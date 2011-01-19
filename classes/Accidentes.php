<?php
/**
 * Table Definition for accidentes
 */
require_once 'DB/DataObject.php';

class DataObjects_Accidentes extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'accidentes';          // table name
    public $acc_id;                          // int(4)  primary_key not_null
    public $acc_par_id;                      // int(4)   not_null
    public $acc_doc_id;                      // int(4)  
    public $acc_tipo;                        // varchar(20)   not_null
    public $acc_reporte_art;                 // tinyint(1)   not_null
    public $acc_descripcion;                 // text   not_null
    public $acc_observacion;                 // text  
    public $acc_alu_id;                      // int(4)  

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DataObjects_Accidentes',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
