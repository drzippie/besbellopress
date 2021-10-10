<?php

namespace App\Console\Commands;

use App\Models\Category;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ImportCategoriesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:categories';

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
        $parent_id = null;


        $this->getChildren($parent_id);

        return 0;
    }

    private function getChildren( $parentId = null, $node = null ) {
        $rows =  DB::connection('import')
            ->table('section')->where('parent_id', $parentId )
            ->orderBy('sortorder')
            ->get();
        foreach( $rows as $row ) {
            $this->line( $row->id );
            $category = Category::query()
                ->where('meta->import->id', $row->id)->first();
            if ( empty( $category)) {
                print_R($category);
                $category = new Category();
                $category->name = $row->fullname;
                $category->meta = [
                    'import' => $row,
                ];

                if ( !empty($node)) {
                    $category->appendToNode( $node)
                        ->save();

                }
                $category->save();

            }
            $this->getChildren($row->id, $category);



        }
    }
}
