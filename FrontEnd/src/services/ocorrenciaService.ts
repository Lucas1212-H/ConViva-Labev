import axios from 'axios'

const isLocal = window.location.hostname === 'localhost' || window.location.hostname === '127.0.0.1';

// URL base dinâmica CORRIGIDA (Adicionado /api no final de ambas)
const API_BASE_URL = isLocal 
  ? 'http://localhost:8000/api' 
  : 'https://conviva-labev.onrender.com/api';

const api = axios.create({
  baseURL: API_BASE_URL,
  headers: {
    'Content-Type': 'application/json'
  }
})

export interface DadosDenuncia {
  denunciante_nome: string
  denunciante_contato_tipo: string
  denunciante_contato_valor: string
  distincao_biologica: string
  tipo_animal: string
  situacao_animal: string
  descricao: string
  latitude: number
  longitude: number
  ponto_referencia: string
  descricao_local_exato?: string
  media?: File
  media_type?: string
}

export const ocorrenciaService = {
  /**
   * Cria uma nova ocorrência (denuncia) no backend
   * @param dados Dados do formulário da denuncia
    * @returns Response com confirmação da ocorrência
   */
  async criarDenuncia(dados: DadosDenuncia) {
    const formData = new FormData()
    
    // Adiciona todos os campos de texto
    formData.append('denunciante_nome', dados.denunciante_nome)
    formData.append('denunciante_contato_tipo', dados.denunciante_contato_tipo)
    formData.append('denunciante_contato_valor', dados.denunciante_contato_valor)
    formData.append('distincao_biologica', dados.distincao_biologica)
    formData.append('tipo_animal', dados.tipo_animal)
    formData.append('situacao_animal', dados.situacao_animal)
    formData.append('descricao', dados.descricao)
    formData.append('latitude', dados.latitude.toString())
    formData.append('longitude', dados.longitude.toString())
    formData.append('ponto_referencia', dados.ponto_referencia)
    formData.append('descricao_local_exato', dados.descricao_local_exato || '')
    
    // Adiciona a mídia (foto ou vídeo) se fornecida
    if (dados.media) {
      formData.append('media', dados.media)
      formData.append('media_type', dados.media_type || 'image')
    }

    try {
      // Como o baseURL já termina em /api, isso vai para /api/ocorrencias
      const response = await api.post('/ocorrencias', formData, {
        headers: {
          'Content-Type': 'multipart/form-data'
        }
      })
      return response.data
    } catch (error) {
      if (axios.isAxiosError(error)) {
        throw new Error(error.response?.data?.message || 'Erro ao criar denuncia')
      }
      throw error
    }
  },

  /**
   * Busca os dados de uma ocorrência pelo id
   * @param id Identificador da ocorrência
   * @returns Dados da ocorrência
   */
  async obterDenunciaPorId(id: number | string) {
    try {
      // Isso vai para /api/ocorrencias/{id}
      const response = await api.get(`/ocorrencias/${id}`)
      return response.data
    } catch (error) {
      if (axios.isAxiosError(error)) {
        throw new Error(error.response?.data?.message || 'Denuncia não encontrada')
      }
      throw error
    }
  },

  /**
   * Lista as ocorrências mais recentes para exibição pública (sem dados de contato)
   */
  async listarRecentes() {
    try {
      const response = await api.get('/ocorrencias/recentes')
      return Array.isArray(response.data) ? response.data : []
    } catch (error) {
      if (axios.isAxiosError(error)) {
        throw new Error(error.response?.data?.message || 'Erro ao buscar ocorrências recentes')
      }
      throw error
    }
  }
}