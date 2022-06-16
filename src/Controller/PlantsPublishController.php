<?php

namespace App\Controller;

use App\Entity\Plants;

class PlantsPublishController
{
    public function __invoke(Plants $data) : Plants
    {
        $data->setOnline(true);
        return $data;
    }
}