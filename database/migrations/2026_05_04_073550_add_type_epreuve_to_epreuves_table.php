<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('epreuves', function (Blueprint $table) {
            $table->string('type_epreuve')->nullable()->after('semestre');
        });
    }

    public function down()
    {
        Schema::table('epreuves', function (Blueprint $table) {
            $table->dropColumn('type_epreuve');
        });
    }
};