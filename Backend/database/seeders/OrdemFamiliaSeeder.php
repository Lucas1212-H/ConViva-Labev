<?php

namespace Database\Seeders;

use App\Models\Classe;
use App\Models\Ordem;
use App\Models\Familia;
use Illuminate\Database\Seeder;

class OrdemFamiliaSeeder extends Seeder
{
    public function run()
    {
        // Busca a classe existente (Mamíferos)
        $classeMamiferos = Classe::where('nome_popular', 'like', '%mamífero%')->first();
        
        if (!$classeMamiferos) {
            $classeMamiferos = Classe::first();
        }

        if ($classeMamiferos) {
            // Cria ordens para mamíferos
            $ordemCarnivoros = Ordem::create([
                'nome_cientifico' => 'Carnivora',
                'nome_popular' => 'Carnívoros',
                'id_classe' => $classeMamiferos->id_classe,
            ]);

            $ordemPrimates = Ordem::create([
                'nome_cientifico' => 'Primates',
                'nome_popular' => 'Primatas',
                'id_classe' => $classeMamiferos->id_classe,
            ]);

            $ordemRoedores = Ordem::create([
                'nome_cientifico' => 'Rodentia',
                'nome_popular' => 'Roedores',
                'id_classe' => $classeMamiferos->id_classe,
            ]);

            // Cria famílias para carnívoros
            Familia::create([
                'nome_cientifico' => 'Felidae',
                'nome_popular' => 'Felídeos',
                'id_ordem' => $ordemCarnivoros->id_ordem,
            ]);

            Familia::create([
                'nome_cientifico' => 'Canidae',
                'nome_popular' => 'Canídeos',
                'id_ordem' => $ordemCarnivoros->id_ordem,
            ]);

            // Cria famílias para primatas
            Familia::create([
                'nome_cientifico' => 'Cebidae',
                'nome_popular' => 'Cebídeos',
                'id_ordem' => $ordemPrimates->id_ordem,
            ]);

            Familia::create([
                'nome_cientifico' => 'Atelidae',
                'nome_popular' => 'Atelídeos',
                'id_ordem' => $ordemPrimates->id_ordem,
            ]);

            // Cria famílias para roedores
            Familia::create([
                'nome_cientifico' => 'Cricetidae',
                'nome_popular' => 'Cricetídeos',
                'id_ordem' => $ordemRoedores->id_ordem,
            ]);

            $this->command->info('Ordens e famílias criadas com sucesso!');
        } else {
            $this->command->warn('Nenhuma classe encontrada. Crie uma classe primeiro.');
        }
    }
}
