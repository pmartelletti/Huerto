<?php
/**
 * Table Definition for horas_extras_seq
 */
require_once 'DB/DataObject.php';

class DataObjects_Horas_extras_seq extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'horas_extras_seq';    // table name
    public $sequence;                        // int(4)  primary_key not_null

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DataObjects_Horas_extras_seq',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
