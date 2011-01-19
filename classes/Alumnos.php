<?php
/**
 * Table Definition for alumnos
 */
require_once 'DB/DataObject.php';

class DataObjects_Alumnos extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'alumnos';             // table name
    public $alu_id;                          // int(4)  primary_key not_null
    public $alu_zona;                        // text   not_null
    public $alu_nombre;                      // text   not_null
    public $alu_identificador;               // varchar(16)   not_null

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DataObjects_Alumnos',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
