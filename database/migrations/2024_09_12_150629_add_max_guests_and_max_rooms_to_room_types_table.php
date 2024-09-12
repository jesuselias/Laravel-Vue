<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMaxGuestsAndMaxRoomsToRoomTypesTable extends Migration
{
    public function up()
    {
        Schema::table('room_types', function (Blueprint $table) {
            $table->integer('max_guests')->nullable()->after('number');
            $table->integer('max_rooms')->nullable()->after('max_guests');
        });
    }

    public function down()
    {
        Schema::table('room_types', function (Blueprint $table) {
            $table->dropColumn('max_guests');
            $table->dropColumn('max_rooms');
        });
    }
}
