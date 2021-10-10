<?php

namespace App\Console\Commands;

use App\Models\Category;
use App\Models\Story;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

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

        foreach(  DB::connection('import')->table('story')->get( ) as $row )  {


            $this->line($row->headline);

            $story = Story::query()
                ->where('meta->import->id', $row->id)->first();
            if ( empty( $story )) {
                $story = new Story();
                $story->meta =[
                'import' => $row,
                ];
            }
            $story->headline = $row->headline;
            $story->subhead = $row->subhead;
            $story->abstract = $row->abstract;
            $story->published_at = $row->created_at;
            $story->body = $row->body_content ?? '';
            $story->save();





        }


    }

}
