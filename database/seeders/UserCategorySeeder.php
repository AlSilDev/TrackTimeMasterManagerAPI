<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info("User categories seeder - Start");

        $categories = [['name' => 'Administrador', 'sigla' => 'A','description' => 'Tem todas as permissões','created_at' => Carbon::now()->toDateTimeString(), 'updated_at' => Carbon::now()->toDateTimeString()],
                        ['name' => 'Secretariado', 'sigla' => 'S', 'description' => 'Permissões de secretariado', 'created_at' => Carbon::now()->toDateTimeString(), 'updated_at' => Carbon::now()->toDateTimeString()],
                        ['name' => 'Verificações Técnicas', 'sigla' => 'VT', 'description' => 'Verifica as viaturas inscritas', 'created_at' => Carbon::now()->toDateTimeString(), 'updated_at' => Carbon::now()->toDateTimeString()],
                        ['name' => 'Controlo Horário', 'sigla' => 'CH', 'description' => 'Controlo horario', 'created_at' => Carbon::now()->toDateTimeString(), 'updated_at' => Carbon::now()->toDateTimeString()],
                        ['name' => 'Partida', 'sigla' => 'P', 'description' => 'Posicionado na partida da corrida', 'created_at' => Carbon::now()->toDateTimeString(), 'updated_at' => Carbon::now()->toDateTimeString()],
                        ['name' => 'Tomada de tempo', 'sigla' => 'TT', 'description' => 'Controla a tomada de tempo final da corrida', 'created_at' => Carbon::now()->toDateTimeString(), 'updated_at' => Carbon::now()->toDateTimeString()]];

        DB::table('user_categories')->insert($categories);

        $this->command->info("User categories seeder - End");
    }
}
