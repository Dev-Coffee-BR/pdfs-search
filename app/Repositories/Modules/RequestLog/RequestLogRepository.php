<?php

namespace App\Repositories\Modules\RequestLog;

use App\Models\RequestLog;

class RequestLogRepository
{
    /**
     * Create a new class instance.
     */
    public function __construct(protected RequestLog $model)
    {
    }

    public function create(array $data): void
    {
        $this->model->create($data);
    }
}
