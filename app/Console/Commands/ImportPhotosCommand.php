<?php

namespace App\Console\Commands;

use App\Models\Story;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class ImportPhotosCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:photos';

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
        $basePath = env('IMPORT_PHOTOS_PATH');

        $this->line('get stories');

        // Story::withoutSyncingToSearch(function () use ($maxStory, $categoriesMap, $usersMap) {

        DB::connection('import')->table('photo')
            ->orderBy('id', 'desc')
            ->chunk(100, function (Collection $rows) use ($basePath) {
                $this->line('--> Process chunk');

                /** @var stdClass $row */
                foreach ($rows as $row) {
                    $file = $basePath.DIRECTORY_SEPARATOR."{$row->filename}-original.{$row->file_extension}";
                    if (!file_exists($file)) {
                        $this->error("\tNOT_FOUND: {$file}");
                        continue;
                    }
                    $importRelated = DB::connection('import')->table('photo_story')
                        ->select(['story_id', 'id'])
                        ->where('photo_id', $row->id)
                        ->get()->toArray();
                    if (empty($importRelated)) {
                        $this->error("\tIMAGE_NOT_LINKED: {$file}");
                        continue;
                    }

                    foreach ($importRelated as $related) {

                        $story = Story::query()
                            ->where('meta->import->id', $related->story_id)->first();
                        if (!$story) {
                            continue;
                        }
                        $image = $story->getMedia('images', ['photo_id' => $related->id]);
                        if ($image->count() > 0) {
                            $this->info("\t\tIMPORTED: {$file}");

                            continue;
                        }

                        $story
                            ->addMedia($file)
                            ->preservingOriginal()
                            ->withCustomProperties(['photo_id' => $related->id, 'imported' => true])
                            ->toMediaCollection('images');

                        $this->line("\tDONE: {$file}");

                    }


                }

            });
        return 0;
    }
}
