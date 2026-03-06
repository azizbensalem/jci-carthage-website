<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameProjectsToEventsAndAddFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::rename('projects', 'events');
        
        Schema::table('events', function (Blueprint $table) {
            $table->string('type')->default('project')->after('id');
            $table->boolean('show_on_website')->default(true)->after('is_active');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn(['type', 'show_on_website']);
        });
        
        Schema::rename('events', 'projects');
    }
}
