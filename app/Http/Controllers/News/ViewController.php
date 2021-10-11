<?php

namespace App\Http\Controllers\News;

use App\Http\Controllers\Controller;
use App\Models\Story;
use Illuminate\Http\Request;

class ViewController extends Controller
{
    /**
     * Provision a new web server.
     *
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Story $story)
    {
        return view('story.view', ['story' => $story]);
    }
}
