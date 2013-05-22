<?php
namespace Heystack\Subsystem\Core\DataObjectHandler;


interface DataObjectHandlerInterface {

    public function getDataObject($callerClass, $filter = "", $cache = true, $orderby = "");

    public function getDataObjects($callerClass, $filter = "", $sort = "", $join = "", $limit = "", $containerClass = "DataObjectSet");

    public function getDataObjectById($callerClass, $id, $cache = true);

    public function deleteDataObjectById($className, $id);

}