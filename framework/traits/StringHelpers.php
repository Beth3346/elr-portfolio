<?php

namespace Framework\Traits;

trait StringHelpers {
    public function deslugify($str)
    {
        return ucwords(str_replace('_', ' ', $str));
    }

    public function slugify($str)
    {
        return str_replace('_', '-', $str);
    }
}