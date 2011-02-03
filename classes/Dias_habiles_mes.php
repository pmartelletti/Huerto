<?php
/**
 * Table Definition for dias_habiles_mes
 */
require_once 'DB/DataObject.php';

class DataObjects_Dias_habiles_mes extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'dias_habiles_mes';    // table name
    public $dhm_id;                          // int(4)  primary_key not_null
    public $dhm_mes;                         // int(4)   not_null
    public $dhm_cantidad_dias;               // int(4)   not_null

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DataObjects_Dias_habiles_mes',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
