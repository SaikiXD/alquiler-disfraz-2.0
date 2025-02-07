<?php

namespace Database\Seeders;

use App\Models\Disfraz;
use App\Models\DisfrazPieza;
use App\Models\Pieza;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DisfrazSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Lista de disfraces con sus respectivas piezas
        $disfraces = [
            [
                'name' => 'Naruto',
                'description' => 'Disfraz del personaje Naruto Uzumaki',
                'gender' => 'masculino',
                'image_path' => 'images/naruto.jpg',
                'price' => 20.99,
                'status' => 'disponible',
                'piezas' => [
                    ['name' => 'Banda Ninja', 'color' => 'Negro', 'size' => 'M', 'material' => 'Metal', 'stock' => 15],
                    [
                        'name' => 'Túnica Naranja',
                        'color' => 'Naranja',
                        'size' => 'L',
                        'material' => 'Tela',
                        'stock' => 10,
                    ],
                    ['name' => 'Shuriken', 'color' => 'Negro', 'size' => 'S', 'material' => 'Plástico', 'stock' => 20],
                ],
            ],
            [
                'name' => 'Sakura',
                'description' => 'Disfraz de Sakura Haruno',
                'gender' => 'femenino',
                'image_path' => 'images/sakura.jpg',
                'price' => 18.5,
                'status' => 'disponible',
                'piezas' => [
                    ['name' => 'Vestido Rosa', 'color' => 'Rosa', 'size' => 'M', 'material' => 'Tela', 'stock' => 12],
                    [
                        'name' => 'Cinta para el cabello',
                        'color' => 'Rojo',
                        'size' => 'Única',
                        'material' => 'Tela',
                        'stock' => 10,
                    ],
                ],
            ],
            [
                'name' => 'Batman',
                'description' => 'Disfraz del superhéroe Batman',
                'gender' => 'masculino',
                'image_path' => 'images/batman.jpg',
                'price' => 30.0,
                'status' => 'disponible',
                'piezas' => [
                    [
                        'name' => 'Máscara de Batman',
                        'color' => 'Negro',
                        'size' => 'Única',
                        'material' => 'Plástico',
                        'stock' => 8,
                    ],
                    ['name' => 'Capa', 'color' => 'Negro', 'size' => 'L', 'material' => 'Tela', 'stock' => 5],
                ],
            ],
            [
                'name' => 'Spiderman',
                'description' => 'Disfraz de Spiderman clásico',
                'gender' => 'masculino',
                'image_path' => 'images/spiderman.jpg',
                'price' => 25.0,
                'status' => 'disponible',
                'piezas' => [
                    [
                        'name' => 'Máscara de Spiderman',
                        'color' => 'Rojo',
                        'size' => 'Única',
                        'material' => 'Tela',
                        'stock' => 10,
                    ],
                    [
                        'name' => 'Guantes de Spiderman',
                        'color' => 'Rojo',
                        'size' => 'M',
                        'material' => 'Tela',
                        'stock' => 12,
                    ],
                ],
            ],
            [
                'name' => 'Harry Potter',
                'description' => 'Disfraz de estudiante de Hogwarts',
                'gender' => 'unisex',
                'image_path' => 'images/harry_potter.jpg',
                'price' => 22.5,
                'status' => 'disponible',
                'piezas' => [
                    [
                        'name' => 'Capa de Hogwarts',
                        'color' => 'Negro',
                        'size' => 'L',
                        'material' => 'Tela',
                        'stock' => 10,
                    ],
                    [
                        'name' => 'Gafas Redondas',
                        'color' => 'Negro',
                        'size' => 'Única',
                        'material' => 'Plástico',
                        'stock' => 15,
                    ],
                    [
                        'name' => 'Varita Mágica',
                        'color' => 'Madera',
                        'size' => 'Única',
                        'material' => 'Madera',
                        'stock' => 8,
                    ],
                ],
            ],
        ];
        foreach ($disfraces as $data) {
            // Crear el disfraz
            $disfraz = Disfraz::create([
                'name' => $data['name'],
                'description' => $data['description'],
                'gender' => $data['gender'],
                'image_path' => $data['image_path'],
                'price' => $data['price'],
                'status' => $data['status'],
            ]);

            foreach ($data['piezas'] as $piezaData) {
                // Buscar o crear la pieza
                $pieza = Pieza::firstOrCreate(
                    ['name' => $piezaData['name']],
                    ['tipo_id' => 1, 'status' => 'disponible'] // `tipo_id` genérico, cámbialo si es necesario
                );

                // Asignar la pieza al disfraz en la tabla pivote disfraz_pieza
                DisfrazPieza::create([
                    'disfraz_id' => $disfraz->id,
                    'pieza_id' => $pieza->id,
                    'stock' => $piezaData['stock'],
                    'color' => $piezaData['color'],
                    'size' => $piezaData['size'],
                    'material' => $piezaData['material'],
                ]);
            }
        }
    }
}
