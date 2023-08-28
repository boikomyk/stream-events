<?php

namespace App\Models\Traits;

trait ReadableTrait
{

    protected function initializeReadableTrait()
    {
        $this->fillable[] = 'is_read';
    }
}
