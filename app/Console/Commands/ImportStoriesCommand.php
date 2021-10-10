<?php

namespace App\Console\Commands;

use App\Models\Category;
use App\Models\Story;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ImportStoriesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:stories';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {


        $categoriesMap = Category::query()
            ->selectRaw(DB::raw("meta->'import'->'id' as import_id, id"))
            ->get()
            ->pluck('id', 'import_id');

        $usersMap = User::query()
            ->selectRaw(DB::raw("meta->'import'->'id' as import_id, id"))
            ->get()
            ->pluck('id', 'import_id');

        // $maxStory = Story::query()->selectRaw("max((meta->'import'->'id')::INTEGER) as maxid")->value('maxid');
        $maxStory = 11650 ;
        $this->line( 'get stories');

        Story::withoutSyncingToSearch(function () use ($maxStory, $categoriesMap, $usersMap) {

        DB::connection('import')->table('story')
            ->where('id', '>=', $maxStory )
            ->orderBy('id')
            ->chunk( 100 , function( $rows ) use ($usersMap, $categoriesMap) {

                $this->line('--> Process chunk');
                foreach(  $rows as $row ) {


                    // @todo resolve bad stories
                    if (in_array($row->id, [4488])) {
                        continue;
                    }
                    if (empty($row->headline) || empty($row->rawtext)) {
                        continue;
                    }

                    $this->line("\t{$row->id}: [{$row->created_at}] {$row->headline}");

                    $story = Story::query()
                        ->where('meta->import->id', $row->id)->first();
                    if (empty($story)) {
                        $story = new Story();
                        $story->meta = [
                            'import' => $row,
                        ];
                    }
                    if (empty($row->headline) || empty($row->rawtext)) {
                        continue;
                    }
                    $story->headline = Str::of($row->headline)->trim();
                    $story->subhead = Str::of($row->subhead)->trim();
                    $story->abstract = Str::of($row->abstract)->trim();
                    $story->published_at = $row->created_at;
                    $story->weight = $row->weight;
                    $story->body = $row->body_content ?? '';

                    $story->category_id = $categoriesMap[$row->section_id] ?? null ;

                    $story->user_id = $usersMap[$row->user_id] ?? null;
                    $story->save();

                }

            });
        });
    }
            // Perform model actions...


}
