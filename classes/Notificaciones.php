<?php
/**
 * Table Definition for notificaciones
 */
require_once 'DB/DataObject.php';

class DataObjects_Notificaciones extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'notificaciones';      // table name
    public $not_id;                          // int(4)  primary_key not_null
    public $not_sec_id;                      // int(4)  
    public $not_tipo;                        // varchar(11)  
    public $not_fecha_creacion;              // date  
    public $not_motivo;                      // varchar(256)  
    public $not_mostrar;                     // tinyint(1)   default_1
    public $not_fecha_modificacion;          // date  

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DataObjects_Notificaciones',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
