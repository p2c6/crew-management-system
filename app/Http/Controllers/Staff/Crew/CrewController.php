<?php

namespace App\Http\Controllers\Staff\Crew;

use App\Http\Controllers\Controller;
use App\Services\Rank\RankService;
use Inertia\Inertia;
use Inertia\Response;

class CrewController extends Controller
{
    /** 
     * @return Inertia\Response
     */
    public function index(): Response
    {

        return Inertia::render('Staff/Crews/Index');
    }
}
