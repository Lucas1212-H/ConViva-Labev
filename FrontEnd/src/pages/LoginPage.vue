<template>
  <div class="container-fluid min-vh-100 d-flex justify-content-center align-items-center py-4 painel-autenticacao">
    <div class="card card-custom border-0 p-4 p-sm-5 shadow w-100 text-dark">
      <div class="card-body">
        
        <h2 class="display-6 fw-normal mb-4 text-start">Login Organizacional</h2>

        <div v-if="erroMensagem" class="alert alert-danger small py-2 rounded-3 text-start" role="alert">
          {{ erroMensagem }}
        </div>

        <form @submit.prevent="lidarComLogin">
          <div class="mb-3 text-start">
            <label for="email" class="form-label small fw-semibold mb-1">Email</label>
            <input 
              type="email" 
              id="email" 
              v-model="credenciais.email" 
              class="form-control input-custom py-2 shadow-sm" 
              :disabled="carregando"
              required
            />
          </div>

          <div class="mb-4 text-start">
            <label for="senha" class="form-label small fw-semibold mb-1">Senha</label>
            <input 
              type="password" 
              id="senha" 
              v-model="credenciais.password" class="form-control input-custom py-2 shadow-sm" 
              :disabled="carregando"
              required
            />
          </div>

          <div class="d-grid mb-3">
            <button type="submit" class="btn btn-black py-2.5 rounded-pill fw-medium shadow-sm" :disabled="carregando">
              <span v-if="carregando" class="spinner-border spinner-border-sm me-2"></span>
              {{ carregando ? 'Autenticando...' : 'Entrar' }}
            </button>
          </div>
        </form>

        <div class="d-flex justify-content-between align-items-start mt-3 small">
          <div class="text-start">
            <span class="d-block text-secondary">Não possui conta?</span>
            <a href="#" @click.prevent="irParaCadastro" class="link-custom fw-semibold text-decoration-underline">
              Cadastre Aqui
            </a>
          </div>
          <div>
            <a href="#" @click.prevent="esqueciSenha" class="link-custom fw-semibold text-decoration-none">
              Esqueci minha senha
            </a>
          </div>
        </div>

      </div>
    </div>
  </div>
</template>

<script setup>
import { reactive, ref } from 'vue'
import axios from 'axios'

const emit = defineEmits(['mudarTela', 'loginSucesso'])

const carregando = ref(false)
const erroMensagem = ref('')
const credenciais = reactive({
  email: '',
  password: ''
})

const lidarComLogin = async () => {
  try {
    carregando.value = true
    erroMensagem.value = ''

    const resultado = await login({
      email: credenciais.email,
      password: credenciais.password
    })

    if (resultado.sucesso) {
      console.log('Login bem-sucedido:', resultado.user.tipo_conta)
      emit('loginSucesso', resultado.user)
      router.push({ name: 'specialist-area '})
    } else {
      erroMensagem.value = resultado.mensagem
    }

  } catch (error) {
    erroMensagem.value = 'Erro de credenciais ou conexão com servidor.'
  } finally {
    carregando.value = false
  }
}

const irParaCadastro = () => {
  emit('mudarTela', 'cadastro')
}

const esqueciSenha = () => {
  alert('Funcionalidade de recuperação de senha será implementada a seguir!')
}
</script>

<style scoped>
/* Fundo externo suave para dar contraste */
.painel-autenticacao {
  background-color: #f4f6f0;
  font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Arial, sans-serif;
}

/* Cor verde de fundo do container conforme imagem enviada */
.card-custom {
  background-color: #93e27a !important; 
  border-radius: 2.5rem !important; /* Cantos bem arredondados */
  max-width: 500px;
}

/* Estilização sutil dos inputs para combinar com o mockup */
.input-custom {
  background-color: rgba(255, 255, 255, 0.4) !important;
  border: 1px solid #5fa64a !important;
  border-radius: 8px !important;
  color: #111111 !important;
}
.input-custom:focus {
  background-color: #ffffff !important;
  border-color: #335d25 !important;
  box-shadow: 0 0 0 0.25rem rgba(51, 93, 37, 0.25) !important;
}

/* Botão inteiramente preto e oval */
.btn-black {
  background-color: #000000 !important;
  color: #ffffff !important;
  border: none;
  transition: opacity 0.2s ease;
}
.btn-black:hover {
  opacity: 0.85;
}

/* Cor dos links roxo/azulados conforme o layout original */
.link-custom {
  color: #4b39ef !important;
}
.link-custom:hover {
  color: #3225b8 !important;
}
</style>