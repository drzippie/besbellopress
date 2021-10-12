<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ImportUsersCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:users';

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
        foreach (DB::connection('import')->table('user')->get() as $row) {

            $this->line("{$row->id} - $row->login");

            $user = User::query()
                ->where('meta->import->id', $row->id)->first();
            if (empty($user)) {
                $user = new User();
                $user->meta = [
                    'import' => $row,
                ];
            }
            $user->email = $row->email ? "{$row->id}.{$row->email}" : $row->login.'@besbellopress.org';
            $user->name = $row->fullname;
            $user->username = $row->login;
            $user->password = Str::random();
            $user->active = true;
            $user->save();


        }


    }

}
