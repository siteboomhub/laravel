<?php

namespace App\Services\League\Repositories;

use Illuminate\Contracts\Cache\Repository;

abstract class AbstractRepository
{
    public function __construct(
        protected readonly Repository $cache
    )
    {
    }
}
