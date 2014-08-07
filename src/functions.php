<?php

namespace Heystack\Core;

/**
 * @param mixed $val
 * @return string
 */
function serialize($val) {
    if (function_exists('igbinary_serialize')) {
        return \igbinary_serialize($val);
    } else {
        return \serialize($val);
    }
}

/**
 * @param $val
 * @return mixed
 */
function unserialize($val) {
    if (function_exists('igbinary_unserialize')) {
        return \igbinary_unserialize($val);
    } else {
        return \unserialize($val);
    }
}