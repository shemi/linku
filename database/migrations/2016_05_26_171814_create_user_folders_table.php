<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Kalnoy\Nestedset\NestedSet;

class CreateUserFoldersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
//        Schema::create('user_folders', function (Blueprint $table) {
//
//            $table->increments('id');
//
//            $table->unsignedInteger('user_id');
//            $table->unsignedInteger('by_user_id')->nullable();
//            $table->unsignedInteger('folder_id');
//
//            $table->timestamps();
//
//            $table
//                ->foreign('user_id')
//                ->references('id')
//                ->on('users');
//
//            $table
//                ->foreign('by_user_id')
//                ->references('id')
//                ->on('users')
//                ->onDelete('cascade');
//
//            $table->index(['user_id', 'by_user_id', 'folder_id']);
//        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
//        Schema::drop('user_folders');
    }
}
