<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Ocorrencia;

class AjustarOcorrenciasParaProducao extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Iniciando ajuste de ocorrências para produção...');

        // Ajustar campos que podem estar nulos ou com valores inconsistentes
        $ocorrencias = Ocorrencia::all();

        foreach ($ocorrencias as $ocorrencia) {
            $atualizacoes = [];

            // Garantir que codigo_registro tenha valor se estiver null
            if (empty($ocorrencia->codigo_registro)) {
                $atualizacoes['codigo_registro'] = 'LABEV' . str_pad($ocorrencia->id, 4, '0', STR_PAD_LEFT);
            }

            // Garantir que status tenha valores válidos
            $statusValidos = ['Pendente', 'Em Análise', 'Resolvido', 'Arquivado', 'Publicado'];
            if (!in_array($ocorrencia->status, $statusValidos)) {
                // Mapear status antigos para novos
                $statusMap = [
                    'Em Atendimento' => 'Em Análise',
                    'Falso Alarme' => 'Arquivado',
                ];
                $atualizacoes['status'] = $statusMap[$ocorrencia->status] ?? 'Pendente';
            }

            // Ajustar campos de especialista se estiverem vazios
            if (empty($ocorrencia->habitat)) {
                $atualizacoes['habitat'] = null;
            }
            if (empty($ocorrencia->microhabitat)) {
                $atualizacoes['microhabitat'] = null;
            }
            if (empty($ocorrencia->classe)) {
                $atualizacoes['classe'] = null;
            }
            if (empty($ocorrencia->ordem)) {
                $atualizacoes['ordem'] = null;
            }
            if (empty($ocorrencia->familia)) {
                $atualizacoes['familia'] = null;
            }
            if (empty($ocorrencia->especie)) {
                $atualizacoes['especie'] = null;
            }
            if (empty($ocorrencia->que_coletou)) {
                $atualizacoes['que_coletou'] = null;
            }
            if (empty($ocorrencia->destino)) {
                $atualizacoes['destino'] = null;
            }
            if (empty($ocorrencia->registrado_por)) {
                $atualizacoes['registrado_por'] = null;
            }

            // Ajustar campos adicionais
            if (empty($ocorrencia->classe_etaria)) {
                $atualizacoes['classe_etaria'] = null;
            }
            if (empty($ocorrencia->tempo)) {
                $atualizacoes['tempo'] = null;
            }
            if (empty($ocorrencia->campo_encontrado)) {
                $atualizacoes['campo_encontrado'] = null;
            }
            if (empty($ocorrencia->observacoes)) {
                $atualizacoes['observacoes'] = null;
            }

            // Atualizar se houver mudanças
            if (!empty($atualizacoes)) {
                $ocorrencia->update($atualizacoes);
                $this->command->info("Ocorrência ID {$ocorrencia->id} ajustada.");
            }
        }

        $this->command->info('Ajuste de ocorrências concluído com sucesso!');
    }
}
