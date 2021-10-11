<?php

namespace App\Http\Controllers;

use App\Models\Story;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Provision a new web server.
     *
     * @return \Illuminate\Http\Response
     */
    public function __invoke( Request  $request)
    {
        $results = Story::search($request->input('search', 'sevilla'), function (\MeiliSearch\Endpoints\Indexes $meilisearch, $query, $options) {
            //   $meilisearch->delete();

            /*

             $meilisearch->updateRankingRules([
                    'words',
                    'typo',
                    'proximity',
                    'attribute',
                'sort',
                 'exactness',
                 'published_at:desc',
                 'id:desc',


            ]);


          //  dd( ['y' =>  $meilisearch->getRankingRules()  ]);

             dd( $meilisearch->updateSortableAttributes(['id', 'published_at', 'headline']));
             */
            //  ( $meilisearch->updateSortableAttributes(['id', 'published_at', 'headline']));

             $options['sort'] = [ 'id:desc'];
            // dd( $meilisearch->getSortableAttributes());

            return $meilisearch->search($query, $options);
        })->paginate(10);


        return view('home', ['results' => $results]);
    }

}
