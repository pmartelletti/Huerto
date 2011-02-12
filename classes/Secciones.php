<?php
/**
 * Table Definition for secciones
 */
require_once 'DB/DataObject.php';

class DataObjects_Secciones extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'secciones';           // table name
    public $sec_id;                          // int(4)  primary_key not_null
    public $sec_nombre;                      // varchar(100)  unique_key not_null
    public $sec_login;                       // varchar(15)   not_null
    public $sec_email;                       // varchar(90)   not_null

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DataObjects_Secciones',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
