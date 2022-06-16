<?php

namespace App\Controller;

use App\Repository\PlantsRepository;
use Symfony\Component\HttpFoundation\Request;

class PlantsCountController
{
    public function __construct(private PlantsRepository $plantsRepository)
    {
        
    }

    public function __invoke(Request $request): int
    {
        $onlineQuery = $request->get('online');
        if ($onlineQuery != NULL) {
            $conditions = ['online' => $onlineQuery == '1' ? true : false];
        }
        return $this->plantsRepository->count($conditions);
    }
}