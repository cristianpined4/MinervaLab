<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SceneSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('scene_category')->insert([
            [
                'description' => 'Laboratorio Virtual',
                'color' => '#3B82F6', // Azul principal
                'icon' => 'fa-flask',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'description' => 'Simulación Educativa',
                'color' => '#10B981', // Verde esmeralda
                'icon' => 'fa-graduation-cap',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'description' => 'Experiencia Inmersiva',
                'color' => '#8B5CF6', // Púrpura
                'icon' => 'fa-vr-cardboard',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'description' => 'Práctica de Laboratorio',
                'color' => '#F59E0B', // Amarillo
                'icon' => 'fa-microscope',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'description' => 'Entorno 3D',
                'color' => '#EF4444', // Rojo
                'icon' => 'fa-cube',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'description' => 'Zona de Prueba',
                'color' => '#6B7280', // Gris neutro
                'icon' => 'fa-tools',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
