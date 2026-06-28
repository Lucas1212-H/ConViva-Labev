export interface DadosLaudo {
  id?: number | string
  tipoAnimal?: string
  animal?: string
  tipo_animal?: string
  distincaoBiologica?: string
  tipo?: string
  distincao_biologica?: string
  situacaoAnimal?: string
  situacao_animal?: string
  status?: string
  statusWorkflow?: string
  denunciante?: string
  denunciante_nome?: string
  local?: string
  ponto_referencia?: string
  descricao?: string
  dataRegistro?: string
  created_at?: string
  data?: string
  parecerTecnico?: string
  parecer_tecnico?: string
  latitude?: number | string | null
  longitude?: number | string | null
  coordenadas?: { lat?: number | null; lng?: number | null }
  imagem?: string
}

function escaparHtml(valor: unknown): string {
  return String(valor ?? '—')
    .replace(/&/g, '&amp;')
    .replace(/</g, '&lt;')
    .replace(/>/g, '&gt;')
    .replace(/"/g, '&quot;')
}

function normalizar(dados: DadosLaudo) {
  const lat = dados.coordenadas?.lat ?? dados.latitude
  const lng = dados.coordenadas?.lng ?? dados.longitude

  return {
    id: dados.id ?? '—',
    tipoAnimal: dados.tipoAnimal ?? dados.animal ?? dados.tipo_animal ?? '—',
    distincao: dados.distincaoBiologica ?? dados.tipo ?? dados.distincao_biologica ?? '—',
    situacao: dados.situacaoAnimal ?? dados.situacao_animal ?? dados.status ?? '—',
    statusWorkflow: dados.statusWorkflow ?? 'Pendente',
    denunciante: dados.denunciante ?? dados.denunciante_nome ?? '—',
    local: dados.local ?? dados.ponto_referencia ?? '—',
    descricao: dados.descricao ?? 'Sem descrição registrada.',
    dataRegistro: dados.dataRegistro ?? dados.created_at ?? dados.data ?? new Date().toISOString(),
    parecer: dados.parecerTecnico ?? dados.parecer_tecnico ?? 'Parecer pendente de avaliação técnica.',
    coordenadas: lat != null && lng != null ? `${lat}, ${lng}` : 'Não informado',
    imagem: dados.imagem ?? '',
  }
}

function formatarData(dataIso: string): string {
  try {
    return new Date(dataIso).toLocaleString('pt-BR')
  } catch {
    return dataIso
  }
}

function montarHtmlLaudo(dados: DadosLaudo): string {
  const o = normalizar(dados)
  const dataEmissao = new Date().toLocaleString('pt-BR')

  return `<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <title>Laudo Técnico — Ocorrência #${escaparHtml(o.id)}</title>
  <style>
    * { box-sizing: border-box; }
    body { font-family: Arial, sans-serif; color: #1e293b; margin: 32px; line-height: 1.5; }
    h1 { font-size: 22px; margin: 0 0 4px; color: #14532d; }
    .subtitulo { color: #64748b; font-size: 13px; margin-bottom: 24px; }
    .grid { display: grid; grid-template-columns: 1fr 1fr; gap: 12px 24px; margin-bottom: 20px; }
    .campo label { display: block; font-size: 11px; text-transform: uppercase; color: #64748b; margin-bottom: 2px; }
    .campo p { margin: 0; font-weight: 600; }
    .bloco { border: 1px solid #e2e8f0; border-radius: 8px; padding: 16px; margin-bottom: 16px; }
    .bloco h2 { font-size: 14px; margin: 0 0 8px; color: #14532d; }
    .bloco p { margin: 0; white-space: pre-wrap; }
    .imagem { max-width: 100%; max-height: 280px; border-radius: 8px; margin-top: 8px; }
    .rodape { margin-top: 32px; padding-top: 16px; border-top: 1px solid #e2e8f0; font-size: 12px; color: #64748b; }
    @media print { body { margin: 16px; } }
  </style>
</head>
<body>
  <h1>Laudo Técnico de Ocorrência Faunística</h1>
  <p class="subtitulo">ConViva Labev — UFPA · Emitido em ${escaparHtml(dataEmissao)}</p>

  <div class="grid">
    <div class="campo"><label>ID da Ocorrência</label><p>#${escaparHtml(o.id)}</p></div>
    <div class="campo"><label>Data do Registro</label><p>${escaparHtml(formatarData(o.dataRegistro))}</p></div>
    <div class="campo"><label>Tipo de Animal</label><p>${escaparHtml(o.tipoAnimal)}</p></div>
    <div class="campo"><label>Distinção Biológica</label><p>${escaparHtml(o.distincao)}</p></div>
    <div class="campo"><label>Situação do Animal</label><p>${escaparHtml(o.situacao)}</p></div>
    <div class="campo"><label>Status do Atendimento</label><p>${escaparHtml(o.statusWorkflow)}</p></div>
    <div class="campo"><label>Denunciante</label><p>${escaparHtml(o.denunciante)}</p></div>
    <div class="campo"><label>Local / Referência</label><p>${escaparHtml(o.local)}</p></div>
    <div class="campo" style="grid-column: 1 / -1;"><label>Coordenadas</label><p>${escaparHtml(o.coordenadas)}</p></div>
  </div>

  <div class="bloco">
    <h2>Descrição da Ocorrência</h2>
    <p>${escaparHtml(o.descricao)}</p>
  </div>

  <div class="bloco">
    <h2>Parecer Técnico</h2>
    <p>${escaparHtml(o.parecer)}</p>
  </div>

  ${o.imagem ? `<div class="bloco"><h2>Evidência Fotográfica</h2><img class="imagem" src="${escaparHtml(o.imagem)}" alt="Foto da ocorrência" /></div>` : ''}

  <div class="rodape">
    Documento gerado automaticamente pelo sistema ConViva Labev para fins de registro e acompanhamento técnico.
  </div>
</body>
</html>`
}

export function exportarLaudoOcorrencia(dados: DadosLaudo): boolean {
  const html = montarHtmlLaudo(dados)
  const janela = window.open('', '_blank')

  if (!janela) {
    return false
  }

  janela.document.open()
  janela.document.write(html)
  janela.document.close()

  setTimeout(() => {
    janela.focus()
    janela.print()
  }, 300)

  return true
}

export function exportarLaudoCsv(dados: DadosLaudo): void {
  const o = normalizar(dados)
  const linhas = [
    ['Campo', 'Valor'],
    ['ID', o.id],
    ['Data do Registro', formatarData(o.dataRegistro)],
    ['Tipo de Animal', o.tipoAnimal],
    ['Distinção Biológica', o.distincao],
    ['Situação do Animal', o.situacao],
    ['Status do Atendimento', o.statusWorkflow],
    ['Denunciante', o.denunciante],
    ['Local', o.local],
    ['Coordenadas', o.coordenadas],
    ['Descrição', o.descricao],
    ['Parecer Técnico', o.parecer],
  ]

  const csv = linhas
    .map((linha) => linha.map((celula) => `"${String(celula).replace(/"/g, '""')}"`).join(';'))
    .join('\n')

  const blob = new Blob(['\uFEFF' + csv], { type: 'text/csv;charset=utf-8;' })
  const url = URL.createObjectURL(blob)
  const link = document.createElement('a')
  link.href = url
  link.download = `laudo-ocorrencia-${o.id}.csv`
  link.click()
  URL.revokeObjectURL(url)
}
