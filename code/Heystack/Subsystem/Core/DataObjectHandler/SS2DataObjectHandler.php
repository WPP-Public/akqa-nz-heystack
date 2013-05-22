<?php
/**
 * Created by JetBrains PhpStorm.
 * User: glenn
 * Date: 21/05/13
 * Time: 10:44 AM
 * To change this template use File | Settings | File Templates.
 */

namespace Heystack\Subsystem\Core\DataObjectHandler;


class SS2DataObjectHandler implements DataObjectHandlerInterface {

    public function getDataObject($callerClass, $filter = "", $cache = true, $orderby = "")
    {
        return \DataObject::get_one($callerClass, $filter, $cache, $orderby);
    }

    public function getDataObjects($callerClass, $filter = "", $sort = "", $join = "", $limit = "", $containerClass = "DataObjectSet")
    {
        return \DataObject::get($callerClass, $filter, $sort, $join, $limit, $containerClass);
    }

    public function getDataObjectById($callerClass, $id, $cache = true)
    {
        return \DataObject::get_by_id($callerClass, $id, $cache);
    }

    public function deleteDataObjectById($className, $id)
    {
        \DataObject::delete_by_id($className, $id);
    }

}