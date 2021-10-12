<?php

namespace App\Http\Controllers\News;

use App\Http\Controllers\Controller;
use App\Models\Story;
use Illuminate\View\View;

class ViewController extends Controller
{

    public function __invoke(Story $story): View
    {
        return view('story.view', ['story' => $story]);
    }
}
