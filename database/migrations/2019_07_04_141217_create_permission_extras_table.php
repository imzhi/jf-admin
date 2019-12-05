<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePermissionExtrasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $tableNames = config('permission.table_names');

        Schema::disableForeignKeyConstraints();

        Schema::create('permission_extras', function (Blueprint $table) use ($tableNames) {
            if ($this->laravelPermissionVersion()) {
                $table->unsignedInteger('permission_id');
            } else {
                $table->unsignedBigInteger('permission_id');
            }

            $table->string('extra_cate');
            $table->string('extra_name');

            $table->foreign('permission_id')
                ->references('id')
                ->on($tableNames['permissions'])
                ->onDelete('cascade');

            $table->primary('permission_id');
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('permission_extras');
    }

    /**
     * 检查 spatie/laravel-permission 扩展包版本是否小于 3.1.0
     * （大于等于 3.1.0 时 permissions 表 ID 字段是 bigint 类型）
     *
     * @return bool
     */
    protected function laravelPermissionVersion()
    {
        $installed = json_decode(file_get_contents(base_path().'/vendor/composer/installed.json'), true);

        $installed_name = collect($installed)->keyBy('name');

        return version_compare($installed_name['spatie/laravel-permission']['version'], '3.1.0', '<');
    }
}
