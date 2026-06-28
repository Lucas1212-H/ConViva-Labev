export interface OcorrenciaResumo {
  id: number
  tipo_animal: string
  distincao_biologica: string
  situacao_animal: string
  status: string
  ponto_referencia: string
  created_at: string
}

export function tempoRelativo(dataIso: string): string {
  const diff = Date.now() - new Date(dataIso).getTime()
  const minutos = Math.floor(diff / 60000)

  if (minutos < 1) return 'agora mesmo'
  if (minutos < 60) return `há ${minutos} min`

  const horas = Math.floor(minutos / 60)
  if (horas < 24) return `há ${horas}h`

  const dias = Math.floor(horas / 24)
  return `há ${dias}d`
}

export function labelStatusWorkflow(status: string): string {
  const mapa: Record<string, string> = {
    Pendente: 'Não resolvido',
    'Em Atendimento': 'Em atendimento',
    Resolvido: 'Resolvido',
    Publicado: 'Publicado',
    'Falso Alarme': 'Arquivado',
  }
  return mapa[status] ?? status
}

export function isUrgente(ocorrencia: Pick<OcorrenciaResumo, 'situacao_animal' | 'distincao_biologica'>): boolean {
  const situacao = ocorrencia.situacao_animal?.toLowerCase() ?? ''
  const distincao = ocorrencia.distincao_biologica?.toLowerCase() ?? ''

  return (
    situacao === 'morto' ||
    situacao === 'ferido' ||
    distincao.includes('peconhento') ||
    distincao.includes('peçonhento')
  )
}

export function iconeDistincao(distincao: string): string {
  const valor = distincao?.toLowerCase() ?? ''
  if (valor.includes('peconhento') || valor.includes('peçonhento')) return '⚠️'
  return '🐾'
}
