<?php

namespace App\Http\Controllers;

use App\Models\Ocorrencia;
use App\Services\CloudinaryService;
use App\Support\StorageUrl;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OcorrenciaController extends Controller
{
    public function __construct(private CloudinaryService $cloudinary) {}

    public function store(Request $request)
    {
        $request->validate([
            'denunciante_nome' => 'required|string|max:255',
            'denunciante_contato_tipo' => 'required|string',
            'denunciante_contato_valor' => 'required|string',
            'distincao_biologica' => 'required|string',
            'tipo_animal' => 'required|string',
            'situacao_animal' => 'required|in:Preso,Morto,Ferido,Avistado,Área de Risco,Outro',
            'descricao' => 'required|string',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'ponto_referencia' => 'required|string',
            'descricao_local_exato' => 'nullable|string|max:120',
            'media' => 'nullable|file|mimes:jpeg,png,jpg,webp,mp4,mov,avi,webm|max:51200',
            'media_type' => 'nullable|in:image,video',
        ]);

        $fotoPath = null;
        $videoPath = null;

        if ($request->hasFile('media')) {
            $mediaFile = $request->file('media');
            $mediaType = $request->media_type ?? 'image';

            try {
                if ($mediaType === 'video') {
                    $videoPath = $this->cloudinary->upload($mediaFile, 'ocorrencias');
                } else {
                    $fotoPath = $this->cloudinary->upload($mediaFile, 'ocorrencias');
                }
            } catch (\Exception $e) {
                // Log do erro mas não falha a criação da ocorrência
                \Log::error('Erro no upload de mídia (ocorrência criada sem mídia): ' . $e->getMessage(), [
                    'file' => $mediaFile->getClientOriginalName(),
                    'type' => $mediaType,
                    'error' => $e->getMessage()
                ]);
                // Continua sem mídia - a ocorrência será salva mesmo assim
            }
        }

        $ocorrencia = Ocorrencia::create([
            'denunciante_nome' => $request->denunciante_nome,
            'denunciante_contato_tipo' => $request->denunciante_contato_tipo,
            'denunciante_contato_valor' => $request->denunciante_contato_valor,
            'distincao_biologica' => $request->distincao_biologica,
            'tipo_animal' => $request->tipo_animal,
            'situacao_animal' => $request->situacao_animal,
            'descricao_ocorrencia' => $request->descricao,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'ponto_referencia' => $request->ponto_referencia,
            'descricao_local_exato' => $request->descricao_local_exato,
            'foto_path' => $fotoPath,
            'video_path' => $videoPath,
            'status' => 'Pendente',
        ]);

        return response()->json([
            'message' => 'Ocorrência registrada com sucesso!',
            'data' => $this->mapearOcorrencia($ocorrencia),
        ], 201);
    }

    public function indexPendentes()
    {
        try {
            $ocorrencias = Ocorrencia::where('status', 'Pendente')
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(fn ($item) => $this->mapearOcorrencia($item));

            return response()->json($ocorrencias, 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erro ao buscar ocorrências: '.$e->getMessage(),
            ], 500);
        }
    }

    public function indexEmAnalise()
    {
        try {
            $ocorrencias = Ocorrencia::where('status', 'Em Análise')
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(fn ($item) => $this->mapearOcorrencia($item));

            return response()->json($ocorrencias, 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erro ao buscar ocorrências em análise: '.$e->getMessage(),
            ], 500);
        }
    }

    public function indexArquivadas()
    {
        try {
            $ocorrencias = Ocorrencia::whereIn('status', ['Resolvido', 'Falso Alarme', 'Em Atendimento', 'Publicado'])
                ->orderBy('updated_at', 'desc')
                ->get()
                ->map(fn ($item) => $this->mapearOcorrencia($item));

            return response()->json($ocorrencias, 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erro ao buscar arquivadas: '.$e->getMessage(),
            ], 500);
        }
    }

    public function alocar(Request $request, $id)
    {
        try {
            $ocorrencia = Ocorrencia::where('id', $id)->first();

            if (! $ocorrencia) {
                return response()->json([
                    'error' => 'Ocorrência não encontrada',
                ], 404);
            }

            $request->validate([
                'usuario' => 'required|string',
            ]);

            $ocorrencia->update([
                'status' => 'Em Análise',
                'assigned_to' => $request->usuario,
            ]);

            return response()->json([
                'message' => 'Ocorrência alocada com sucesso!',
                'data' => $this->mapearOcorrencia($ocorrencia),
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erro ao alocar: '.$e->getMessage(),
            ], 500);
        }
    }

    public function arquivar(Request $request, $id)
    {
        try {
            $ocorrencia = Ocorrencia::where('id', $id)->first();

            if (! $ocorrencia) {
                return response()->json([
                    'error' => 'Ocorrência não encontrada',
                ], 404);
            }

            $request->validate([
                'habitat' => 'nullable|string',
                'microhabitat' => 'nullable|string',
                'classe' => 'nullable|string',
                'ordem' => 'nullable|string',
                'familia' => 'nullable|string',
                'especie' => 'nullable|string',
                'que_coletou' => 'nullable|string',
                'destino' => 'nullable|string',
                'registrado_por' => 'required|string',
                'classe_etaria' => 'nullable|string',
                'tempo' => 'nullable|string',
                'campo_encontrado' => 'nullable|string',
                'observacoes' => 'nullable|string',
                'codigo_registro' => 'nullable|string',
            ]);

            $ocorrencia->update([
                'habitat' => $request->habitat,
                'microhabitat' => $request->microhabitat,
                'classe' => $request->classe,
                'ordem' => $request->ordem,
                'familia' => $request->familia,
                'especie' => $request->especie,
                'que_coletou' => $request->que_coletou,
                'destino' => $request->destino,
                'registrado_por' => $request->registrado_por,
                'classe_etaria' => $request->classe_etaria,
                'tempo' => $request->tempo,
                'campo_encontrado' => $request->campo_encontrado,
                'observacoes' => $request->observacoes,
                'codigo_registro' => $request->codigo_registro,
                'status' => 'Resolvido',
            ]);

            return response()->json([
                'message' => 'Ocorrência arquivada com sucesso!',
                'data' => $this->mapearOcorrencia($ocorrencia),
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erro ao arquivar: '.$e->getMessage(),
            ], 500);
        }
    }

    public function avaliar(Request $request, $id)
    {
        try {
            $ocorrencia = Ocorrencia::where('id', $id)->first();

            if (! $ocorrencia) {
                return response()->json([
                    'error' => 'Ocorrência não encontrada',
                ], 404);
            }

            $request->validate([
                'status' => 'required|in:Pendente,Em Atendimento,Resolvido,Falso Alarme,Publicado',
                'parecer_tecnico' => 'nullable|string',
            ]);

            $ocorrencia->update([
                'status' => $request->status,
                'parecer_tecnico' => $request->parecer_tecnico,
            ]);

            return response()->json([
                'message' => 'Ocorrência atualizada com sucesso!',
                'data' => $this->mapearOcorrencia($ocorrencia),
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erro ao atualizar: '.$e->getMessage(),
            ], 500);
        }
    }

    private function mapearOcorrencia($ocorrencia)
    {
        return [
            'id' => $ocorrencia->id,
            'tipo_animal' => $ocorrencia->tipo_animal,
            'denunciante_nome' => $ocorrencia->denunciante_nome,
            'distincao_biologica' => $ocorrencia->distincao_biologica,
            'descricao' => $ocorrencia->descricao_ocorrencia,
            'descricao_ocorrencia' => $ocorrencia->descricao_ocorrencia,
            'foto_path' => $ocorrencia->foto_path,
            'foto_url' => StorageUrl::publicUrl($ocorrencia->foto_path),
            'video_path' => $ocorrencia->video_path,
            'video_url' => StorageUrl::publicUrl($ocorrencia->video_path),
            'ponto_referencia' => $ocorrencia->ponto_referencia,
            'descricao_local_exato' => $ocorrencia->descricao_local_exato,
            'created_at' => $ocorrencia->created_at,
            'updated_at' => $ocorrencia->updated_at,
            'status' => $ocorrencia->status,
            'publicado_no_mapa' => (bool) $ocorrencia->publicado_no_mapa,
            'situacao_animal' => $ocorrencia->situacao_animal,
            'parecer_tecnico' => $ocorrencia->parecer_tecnico,
            'latitude' => $ocorrencia->latitude,
            'longitude' => $ocorrencia->longitude,
            'assigned_to' => $ocorrencia->assigned_to,
            'habitat' => $ocorrencia->habitat,
            'microhabitat' => $ocorrencia->microhabitat,
            'classe' => $ocorrencia->classe,
            'ordem' => $ocorrencia->ordem,
            'familia' => $ocorrencia->familia,
            'especie' => $ocorrencia->especie,
            'que_coletou' => $ocorrencia->que_coletou,
            'destino' => $ocorrencia->destino,
            'registrado_por' => $ocorrencia->registrado_por,
            'classe_etaria' => $ocorrencia->classe_etaria,
            'tempo' => $ocorrencia->tempo,
            'campo_encontrado' => $ocorrencia->campo_encontrado,
            'observacoes' => $ocorrencia->observacoes,
            'codigo_registro' => $ocorrencia->codigo_registro,
        ];
    }

    public function indexPublicados()
    {
        try {
            $publicados = Ocorrencia::where('status', 'Publicado')
                ->where('publicado_no_mapa', true)
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(fn ($item) => $this->mapearOcorrencia($item));

            return response()->json($publicados, 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erro ao buscar publicados: '.$e->getMessage(),
            ], 500);
        }
    }

    public function indexRecentes()
    {
        try {
            $recentes = Ocorrencia::whereIn('status', ['Pendente', 'Em Atendimento', 'Publicado'])
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get()
                ->map(fn ($item) => [
                    'id' => $item->id,
                    'tipo_animal' => $item->tipo_animal,
                    'distincao_biologica' => $item->distincao_biologica,
                    'situacao_animal' => $item->situacao_animal,
                    'status' => $item->status,
                    'ponto_referencia' => $item->ponto_referencia,
                    'created_at' => $item->created_at,
                ]);

            return response()->json($recentes, 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erro ao buscar ocorrências recentes: '.$e->getMessage(),
            ], 500);
        }
    }

    public function showPublicada($id)
    {
        try {
            $ocorrencia = Ocorrencia::where('id', $id)
                ->where('status', 'Publicado')
                ->first();

            if (! $ocorrencia) {
                return response()->json([
                    'error' => 'Ocorrência não encontrada',
                ], 404);
            }

            return response()->json([
                'data' => $this->mapearOcorrencia($ocorrencia),
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erro ao buscar ocorrência: '.$e->getMessage(),
            ], 500);
        }
    }

    public function publicar($id)
    {
        try {
            $ocorrencia = Ocorrencia::where('id', $id)->first();

            if (! $ocorrencia) {
                return response()->json([
                    'error' => 'Ocorrência não encontrada',
                ], 404);
            }

            $ocorrencia->update([
                'status' => 'Publicado',
                'publicado_no_mapa' => true,
            ]);

            return response()->json([
                'message' => 'Ocorrência publicada com sucesso!',
                'data' => $this->mapearOcorrencia($ocorrencia),
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erro ao publicar: '.$e->getMessage(),
            ], 500);
        }
    }

    public function despublicar($id)
    {
        try {
            $ocorrencia = Ocorrencia::where('id', $id)->first();

            if (! $ocorrencia) {
                return response()->json([
                    'error' => 'Ocorrência não encontrada',
                ], 404);
            }

            $ocorrencia->update([
                'publicado_no_mapa' => false,
            ]);

            return response()->json([
                'message' => 'Ocorrência removida do mapa com sucesso!',
                'data' => $this->mapearOcorrencia($ocorrencia),
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erro ao remover do mapa: '.$e->getMessage(),
            ], 500);
        }
    }

    public function pendentes()
    {
        return $this->indexPendentes();
    }

    public function dadosParaAnalise()
    {
        try {
            $dados = Ocorrencia::where('status', 'Publicado')
                ->select('tipo_animal', 'distincao_biologica', 'situacao_animal', 'latitude', 'longitude', 'created_at')
                ->get()
                ->map(fn ($item) => [
                    'especie' => $item->tipo_animal,
                    'distincao_biologica' => $item->distincao_biologica,
                    'situacao' => $item->situacao_animal,
                    'situacao_animal' => $item->situacao_animal,
                    'lat' => $item->latitude !== null ? (float) $item->latitude : null,
                    'lng' => $item->longitude !== null ? (float) $item->longitude : null,
                    'latitude' => $item->latitude,
                    'longitude' => $item->longitude,
                    'ano' => $item->created_at->format('Y'),
                    'mes' => $item->created_at->format('m'),
                    'hora' => $item->created_at->format('H'),
                ]);

            return response()->json($dados, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao processar dados: ' . $e->getMessage()], 500);
        }
    }
}