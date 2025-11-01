<?php

use App\Models\Faculty;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('faculty', function (Blueprint $table) {
            $table->id();
            $table->string('direction', 255)->nullable();
            $table->string('description', 255)->nullable();
            $table->string('code', 50)->nullable();
            $table->timestamps();
        });

        Faculty::insert([
            ['direction' => 'Ciudad Universitaria, San Salvador', 'description' => 'Facultad de Ingeniería y Arquitectura', 'code' => 'FIA', 'created_at' => now(), 'updated_at' => now()],
            ['direction' => 'Ciudad Universitaria, San Salvador', 'description' => 'Facultad de Ciencias y Humanidades', 'code' => 'FCH', 'created_at' => now(), 'updated_at' => now()],
            ['direction' => 'Ciudad Universitaria, San Salvador', 'description' => 'Facultad de Jurisprudencia y Ciencias Sociales', 'code' => 'FJCS', 'created_at' => now(), 'updated_at' => now()],
            ['direction' => 'Ciudad Universitaria, San Salvador', 'description' => 'Facultad de Ciencias Naturales y Matemática', 'code' => 'FCNM', 'created_at' => now(), 'updated_at' => now()],
            ['direction' => 'Ciudad Universitaria, San Salvador', 'description' => 'Facultad de Medicina', 'code' => 'FMED', 'created_at' => now(), 'updated_at' => now()],
            ['direction' => 'Ciudad Universitaria, San Salvador', 'description' => 'Facultad de Odontología', 'code' => 'FOD', 'created_at' => now(), 'updated_at' => now()],
            ['direction' => 'Ciudad Universitaria, San Salvador', 'description' => 'Facultad de Química y Farmacia', 'code' => 'FQF', 'created_at' => now(), 'updated_at' => now()],
            ['direction' => 'Ciudad Universitaria, San Salvador', 'description' => 'Facultad de Ciencias Económicas', 'code' => 'FCE', 'created_at' => now(), 'updated_at' => now()],
            ['direction' => 'Ciudad Universitaria, San Salvador', 'description' => 'Facultad de Agronomía', 'code' => 'FAG', 'created_at' => now(), 'updated_at' => now()],
            ['direction' => 'Santa Ana', 'description' => 'Facultad Multidisciplinaria de Occidente', 'code' => 'FMOcc', 'created_at' => now(), 'updated_at' => now()],
            ['direction' => 'San Miguel', 'description' => 'Facultad Multidisciplinaria Oriental', 'code' => 'FMOr', 'created_at' => now(), 'updated_at' => now()],
            ['direction' => 'San Vicente', 'description' => 'Facultad Multidisciplinaria Paracentral', 'code' => 'FMPar', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }


    public function down(): void
    {
        Schema::dropIfExists('faculty');
    }
};
