<?php
/**
 * Table Definition for docente
 */
require_once 'DB/DataObject.php';

class DataObjects_Docente extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'docente';             // table name
    public $doc_id;                          // int(4)  primary_key not_null
    public $doc_nombre;                      // text   not_null
    public $doc_cargo;                       // varchar(4)   not_null
    public $doc_cuil;                        // varchar(13)   not_null
    public $doc_hs;                          // int(4)  
    public $doc_revista;                     // varchar(100)  
    public $doc_seccion;                     // varchar(10)   not_null

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DataObjects_Docente',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
