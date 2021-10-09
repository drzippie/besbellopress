<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class CreateDefaultRoles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $roleWriter = Role::create(['name' => 'writer' ]);
        $roleEditor = Role::create(['name' => 'editor' ]);
        $roleAdmin = Role::create(['name' => 'admin' ]);
        $roleRoot = Role::create(['name' => 'root' ]);

        $permAccessDashboard = Permission::create(['name' => 'access dashboard']);


        $roleWriter->givePermissionTo($permAccessDashboard);
        $roleEditor->givePermissionTo($permAccessDashboard);
        $roleAdmin->givePermissionTo($permAccessDashboard);
        $roleRoot->givePermissionTo($permAccessDashboard);

        $permEditOwnArticles = Permission::create(['name' => 'edit own articles']);

        $roleWriter->givePermissionTo($permEditOwnArticles);
        $roleEditor->givePermissionTo($permEditOwnArticles);
        $roleAdmin->givePermissionTo($permEditOwnArticles);
        $roleRoot->givePermissionTo($permEditOwnArticles);

        $permEditOthersArticles = Permission::create(['name' => 'edit others articles']);

        $roleEditor->givePermissionTo($permEditOthersArticles);
        $roleAdmin->givePermissionTo($permEditOthersArticles);
        $roleRoot->givePermissionTo($permEditOthersArticles);



    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

    }
}
