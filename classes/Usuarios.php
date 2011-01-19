<?php
/**
 * Table Definition for usuarios
 */
require_once 'DB/DataObject.php';

class DataObjects_Usuarios extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'usuarios';            // table name
    public $us_id;                           // int(4)  primary_key not_null
    public $us_nombre;                       // text   not_null
    public $us_pass;                         // varchar(20)   not_null
    public $us_sec_id;                       // int(4)   not_null

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DataObjects_Usuarios',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
