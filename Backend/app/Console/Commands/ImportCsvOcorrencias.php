<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Ocorrencia;
use Carbon\Carbon;

class ImportCsvOcorrencias extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ocorrencias:import {files?*}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Importa as ocorrências dos arquivos CSV (2025 e 2026)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $files = $this->argument('files');
        
        if (empty($files)) {
            $files = [
                database_path('data/ocorrencias2025.csv'),
                database_path('data/ocorrencias2026.csv')
            ];
        }

        foreach ($files as $file) {
            if (!file_exists($file)) {
                $this->error("Arquivo não encontrado: $file");
                continue;
            }

            $this->info("Importando: $file");
            $this->importarArquivo($file);
        }

        $this->info("Importação concluída com sucesso.");
    }

    private function importarArquivo($filePath)
    {
        $handle = fopen($filePath, "r");
        if ($handle !== false) {
            // Ler cabeçalho
            $header = fgetcsv($handle, 0, ",");
            
            $importedCount = 0;

            while (($data = fgetcsv($handle, 0, ",")) !== false) {
                // Pular linhas vazias
                if (empty(array_filter($data))) {
                    continue;
                }
                
                // Mapear dados
                $codigoRegistro = $data[0] ?? null;
                $classe = $data[1] ?? null;
                $ordem = $data[2] ?? null;
                $familia = $data[3] ?? null;
                $especie = $data[4] ?? null;
                $nomePopular = $data[5] ?? null;
                $dataEncontro = $data[6] ?? null;
                $horario = $data[7] ?? null;
                $tempo = $data[8] ?? null;
                $campus = $data[9] ?? null;
                $localEncontro = $data[10] ?? null;
                // $pontoGeo = $data[11] ?? null;
                $latitude = $data[12] ?? null;
                $longitude = $data[13] ?? null;
                $habitat = $data[14] ?? null;
                $microhabitat = $data[15] ?? null;
                $statusAnimal = $data[16] ?? null; // Vivo, Morto
                $classeEtaria = $data[17] ?? null;
                $colaborador = $data[18] ?? null;
                $instagram = $data[19] ?? null;
                $coletor = $data[20] ?? null;
                $destino = $data[21] ?? null;
                $comoContatou = $data[22] ?? null;
                $registradoPor = $data[23] ?? null;
                $observacoes = $data[26] ?? null; // 24 = Foto, 25 = Filme

                if (empty($codigoRegistro)) {
                    continue;
                }

                // Tratar data e hora
                $createdAt = now();
                if (!empty($dataEncontro) && $dataEncontro !== '-') {
                    try {
                        $horarioStr = (!empty($horario) && $horario !== '-') ? $horario : '00:00';
                        $createdAt = Carbon::createFromFormat('d/m/Y H:i', trim($dataEncontro) . ' ' . trim($horarioStr));
                    } catch (\Exception $e) {
                        try {
                            $createdAt = Carbon::createFromFormat('d/m/Y', trim($dataEncontro));
                        } catch (\Exception $e2) {
                            // default to now
                        }
                    }
                }

                // Normalizar situação
                $situacaoAnimal = 'Não informado';
                if (stripos($statusAnimal, 'vivo') !== false) {
                    $situacaoAnimal = 'Avistado'; // Ou Vivo
                } elseif (stripos($statusAnimal, 'morto') !== false) {
                    $situacaoAnimal = 'Morto';
                }

                Ocorrencia::updateOrCreate(
                    ['codigo_registro' => trim($codigoRegistro)],
                    [
                        'denunciante_nome' => substr(trim($colaborador), 0, 255) ?: 'Não informado',
                        'denunciante_contato_tipo' => substr(trim($comoContatou), 0, 255) ?: 'Não informado',
                        'denunciante_contato_valor' => substr(trim($instagram), 0, 255) ?: 'Não informado',
                        'distincao_biologica' => 'Animal',
                        'tipo_animal' => substr(trim($nomePopular) ?: trim($especie), 0, 255) ?: 'Desconhecido',
                        'situacao_animal' => $situacaoAnimal,
                        'descricao_ocorrencia' => substr(trim($observacoes), 0, 255) ?: 'Importado via CSV',
                        
                        // --- ALTERAÇÃO AQUI: Se não for numérico/estiver nulo, envia '0' para não quebrar o MySQL ---
                        'latitude' => is_numeric(trim($latitude)) ? trim($latitude) : '0',
                        'longitude' => is_numeric(trim($longitude)) ? trim($longitude) : '0',
                        
                        'ponto_referencia' => substr(trim($localEncontro), 0, 255) ?: 'Não informado',
                        'descricao_local_exato' => substr(trim($localEncontro), 0, 255),
                        'status' => 'Resolvido',
                        'publicado_no_mapa' => false,
                        'parecer_tecnico' => 'Caso importado do histórico.',
                        'habitat' => substr(trim($habitat), 0, 255),
                        'microhabitat' => substr(trim($microhabitat), 0, 255),
                        'classe' => substr(trim($classe), 0, 255),
                        'ordem' => substr(trim($ordem), 0, 255),
                        'familia' => substr(trim($familia), 0, 255),
                        'especie' => substr(trim($especie), 0, 255),
                        'que_coletou' => substr(trim($coletor), 0, 255),
                        'destino' => substr(trim($destino), 0, 255),
                        'registrado_por' => substr(trim($registradoPor), 0, 255),
                        'classe_etaria' => substr(trim($classeEtaria), 0, 255),
                        'tempo' => substr(trim($tempo), 0, 255),
                        'campo_encontrado' => substr(trim($campus), 0, 255),
                        'observacoes' => trim($observacoes),
                        'created_at' => $createdAt,
                        'updated_at' => $createdAt,
                    ]
                );
                
                $importedCount++;
            }
            fclose($handle);
            $this->info(" -> Foram importados/atualizados $importedCount registros do arquivo.");
        }
    }
}