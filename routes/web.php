<?php

use App\Models\Story;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function (Request $request) {
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
     //   ( $meilisearch->updateSortableAttributes(['id', 'published_at', 'headline']));

         $options['sort'] = [ 'id:desc'];
     //   dd( $meilisearch->getSortableAttributes());

        return $meilisearch->search($query, $options);
    })->paginate(10);


    return view('welcome', ['results' => $results]);
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

require __DIR__.'/auth.php';
