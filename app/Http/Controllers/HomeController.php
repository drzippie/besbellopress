<?php

namespace App\Http\Controllers;

use App\Models\Story;
use Illuminate\Http\Request;
use Illuminate\View\View;
use MeiliSearch\Endpoints\Indexes;

class HomeController extends Controller
{
    /**
     * Provision a new web server.
     */
    public function __invoke(Request $request): View
    {
        $results = Story::search($request->input('search', ''),
            function (Indexes $meilisearch, $query, $options) {
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

                $options['sort'] = ['id:desc'];
                // dd( $meilisearch->getSortableAttributes());

                return $meilisearch->search($query, $options);
            })->paginate(10);


        return view('home', ['results' => $results]);
    }

}
