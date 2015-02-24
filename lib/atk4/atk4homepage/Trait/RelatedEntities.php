<?php
/**
 * Created by PhpStorm.
 * User: konstantin
 * Date: 20.01.15
 * Time: 14:41
 */

namespace atk4\atk4homepage;

trait Trait_RelatedEntities {

    /**
     * This method "cooks" related model.
     *
     * @param SQL_Model $m
     * @param bool $as_array
     * @param bool $limit
     * @param int $offset
     * @param bool $order
     * @param bool $desc
     * @return array|SQL_Model
     */
    function prepareRelated(SQL_Model $m, $as_array=false, $limit=false, $offset=0, $order=false, $desc=true) {
        if ($limit) {
            $m->setLimit($limit,$offset);
        }
        if ($order) {
            $m->setOrder($order,$desc);
        }
        if ($as_array) {
            return $m->getRows();
        }
        return $m;
    }
}