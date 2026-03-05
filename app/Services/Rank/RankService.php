<?php
namespace App\Services\Rank;

use App\Models\Rank;

class RankService
{
    public function getAllRanks()
    {
        return Rank::query()->get();
    }
}