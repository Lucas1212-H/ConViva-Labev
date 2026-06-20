import { ref, computed } from 'vue'
import axios from 'axios'

interface User {
  id?: number | string
  nome: string
  email: string
  tipo_conta: 'Administrador' | 'Colaborador'
  status: 'Ativo' | 'Pendente'
}

const usuarioLogado = ref<User | null>(null)
const carregando = ref(false)

const isLocal = window.location.hostname === 'localhost' || window.location.hostname === '127.0.0.1'
const API_BASE_URL = isLocal ? 'http://localhost:8000/api' : 'https://conviva-labev.onrender.com/api'

// Carregar dados do localStorage ao inicializar
const loadStoredUser = () => {
  const token = localStorage.getItem('auth_token')
  const userStored = localStorage.getItem('usuarioLogado')

  if (token && userStored) {
    usuarioLogado.value = JSON.parse(userStored)
    axios.defaults.headers.common['Authorization'] = `Bearer ${token}`
  }
}

export const useAuth = () => {
  const isAutenticado = computed(() => !!usuarioLogado.value)

  const register = async (nome: string, email: string, password: string) => {
    try {
      carregando.value = true
      const response = await axios.post(`${API_BASE_URL}/registrar`, {
        name: nome,
        email,
        password,
        tipo_conta: 'Colaborador' // Padrão para novos registros
      })
      
      return { sucesso: true, mensagem: response.data.message }
  } catch (erro: any) {
      console.error('Erro ao registrar:', erro)
      const msg = erro.response?.data?.message || 'Erro ao registrar. Tente novamente.'
      return { sucesso: false, mensagem: msg }
    } finally {
      carregando.value = false
    }
  }
  /**
   * 🔑 LOGIN REAL (Bate no AuthController do Laravel)
   */
  const login = async (credenciais: { email: string; password?: string; senha?: string }) => {
    try {
      carregando.value = true
      
      // Captura o e-mail e faz o fallback seguro da senha
      const emailFinal = credenciais.email
      const passwordFinal = credenciais.password || credenciais.senha

      console.log('Enviando payload real para a API:', { email: emailFinal, password: passwordFinal })

      const response = await axios.post(`${API_BASE_URL}/login`, {
        email: emailFinal,
        password: passwordFinal
      })

      // Extrai os dados retornados pelo Laravel
      const { access_token, user } = response.data

      // Grava no LocalStorage para persistência
      localStorage.setItem('fauna_token', access_token)
      localStorage.setItem('user_name', user.name)
      localStorage.setItem('user_email', user.email)
      localStorage.setItem('user_tipo', user.tipo_conta)
      localStorage.setItem('usuario_logado_dados', JSON.stringify(user))

      // Injeta o token nas requisições subsequentes
      axios.defaults.headers.common['Authorization'] = `Bearer ${access_token}`

      // Atualiza o estado global reativo do Vue
      usuarioLogado.value = user

      return { sucesso: true, user }
    } catch (erro: any) {
      console.error('Erro ao fazer login:', erro)
      
      // Captura mensagens amigáveis do validador do Laravel
      const msg = erro.response?.data?.message || 'Erro de validação ou credenciais incorretas.'
      return { sucesso: false, mensagem: msg }
    } finally {
      carregando.value = false
    }
  }

  const logout = () => {
    usuarioLogado.value = null
    localStorage.clear()
    delete axios.defaults.headers.common['Authorization']
  }

  // Carregar dados do localStorage quando o composable é inicializado
  loadStoredUser()

  return {
    usuarioLogado: computed(() => usuarioLogado.value),
    isAutenticado,
    carregando: computed(() => carregando.value),
    register,
    login,
    logout
  }
}
