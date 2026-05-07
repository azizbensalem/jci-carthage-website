<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFacebookFieldsToEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('events', function (Blueprint $table) {
            $table->string('facebook_event_id')->nullable()->unique()->after('id');
            $table->dateTime('starts_at')->nullable()->after('description');
            $table->dateTime('ends_at')->nullable()->after('starts_at');
            $table->string('location_name')->nullable()->after('ends_at');
            $table->index('starts_at');
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
            $table->dropIndex(['starts_at']);
            $table->dropUnique(['facebook_event_id']);
            $table->dropColumn(['facebook_event_id', 'starts_at', 'ends_at', 'location_name']);
        });
    }
}
