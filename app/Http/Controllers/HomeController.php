<?php

namespace App\Http\Controllers;

use App\Models\ServiceReview;
use App\Models\Therapist;
use Inertia\Inertia;

class HomeController extends Controller
{
    //
    public function index()
    {
        $guest_ratings = ServiceReview::published()->avg('rating');
        $therapists = Therapist::active()->count();

        return Inertia::render('Home', [
            'guest_ratings' => number_format((float) $guest_ratings, 1),
            'therapists' => $therapists,
        ]);
    }
}
