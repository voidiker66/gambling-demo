<?php

namespace App\Http\Utils;

trait IterableTrait {
    public function map(callable $callback): void
    {
        foreach($this->data as $iterable) {
            $callback($iterable);
        }
    }
}