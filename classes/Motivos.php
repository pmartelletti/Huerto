<?php
/**
 * Table Definition for motivos
 */
require_once 'DB/DataObject.php';

class DataObjects_Motivos extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'motivos';             // table name
    public $mot_id;                          // varchar(11)  primary_key not_null
    public $mot_descripcion;                 // varchar(256)   not_null

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DataObjects_Motivos',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
