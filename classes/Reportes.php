<?php
/**
 * Table Definition for reportes
 */
require_once 'DB/DataObject.php';

class DataObjects_Reportes extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'reportes';            // table name
    public $re_id;                           // int(4)  primary_key not_null
    public $re_par_id;                       // int(4)   not_null
    public $re_doc_id;                       // int(4)   not_null
    public $re_tipo;                         // varchar(20)   not_null
    public $re_horas;                        // float   not_null
    public $re_motivo;                       // varchar(20)   not_null
    public $re_observacion;                  // text  

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DataObjects_Reportes',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
