<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ficheros', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('extension', 10);
            $table->unsignedTinyInteger('eliminado')->default(0)->comment('0: no | 1: sí');
            $table->timestamps();
        });

        DB::statement("alter table ficheros add contenido mediumblob");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ficheros');
    }
};
