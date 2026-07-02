<template>
  <div>
    <NavBar />

    <main class="container py-5 d-flex justify-content-center">
      <div class="w-100" style="max-width: 600px;">
        
        <div class="card shadow-sm border-0 rounded-3 p-4 mb-4">
          <header class="mb-4 text-start border-bottom pb-2">
            <h1 class="h4 fw-bold text-dark m-0">Minha Conta</h1>
            <p class="text-muted small mb-0">Atualize suas informações de perfil organizacional e credenciais de acesso.</p>
          </header>

          <div v-if="sucessoMensagem" class="alert alert-success small py-2 text-start" role="alert">
            {{ sucessoMensagem }}
          </div>
          <div v-if="erroMensagem" class="alert alert-danger small py-2 text-start" role="alert">
            {{ erroMensagem }}
          </div>

          <form @submit.prevent="salvarPerfil">
            <div class="mb-3 text-start">
              <label for="name" class="form-label small fw-semibold mb-1">Nome Completo</label>
              <input 
                type="text" 
                id="name" 
                v-model="perfil.name" 
                class="form-control" 
                :disabled="carregando"
                required
              >
            </div>

            <div class="mb-3 text-start">
              <label for="email" class="form-label small fw-semibold mb-1">E-mail Organizacional</label>
              <input 
                type="email" 
                id="email" 
                v-model="perfil.email" 
                class="form-control" 
                :disabled="carregando"
                required
              >
            </div>

            <div class="mb-4 text-start">
              <label class="form-label small fw-semibold mb-1 d-block">Nível de Permissão</label>
              <span 
                class="badge rounded-pill px-3 py-2 fs-7"
                :class="tipoConta === 'Administrador' ? 'bg-danger-subtle text-danger' : 'bg-primary-subtle text-primary'"
              >
                Perfil {{ tipoConta }}
              </span>
            </div>

            <hr class="text-muted my-4">
            <h5 class="h6 fw-bold text-secondary text-start mb-3">Segurança e Nova Senha</h5>

            <div class="mb-3 text-start">
              <label for="password" class="form-label small fw-semibold mb-1">Nova Senha</label>
              <input 
                type="password" 
                id="password" 
                v-model="perfil.password" 
                class="form-control" 
                placeholder="Deixe em branco para não alterar"
                :disabled="carregando"
              >
            </div>

            <div class="mb-4 text-start">
              <label for="password_confirmation" class="form-label small fw-semibold mb-1">Confirmar Nova Senha</label>
              <input 
                type="password" 
                id="password_confirmation" 
                v-model="perfil.password_confirmation" 
                class="form-control" 
                placeholder="Repita a nova senha se digitou acima"
                :disabled="carregando"
              >
            </div>

            <div class="d-flex justify-content-end gap-2">
              <button type="button" class="btn btn-light border px-4" @click="$router.push('/')" :disabled="carregando">
                Cancelar
              </button>
              <button type="submit" class="btn btn-dark px-4" :disabled="carregando">
                <span v-if="carregando" class="spinner-border spinner-border-sm me-2"></span>
                {{ carregando ? 'Salvando...' : 'Salvar Alterações' }}
              </button>
            </div>
          </form>
        </div>

        <div class="card shadow-sm border-1 border-danger-subtle rounded-3 p-4 bg-danger-subtle bg-opacity-10">
          <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 text-start">
            <div>
              <h5 class="h6 fw-bold text-danger mb-1">Zona de Perigo</h5>
              <p class="text-secondary small m-0">Ao excluir sua conta, todos os seus dados organizacionais e privilégios de acesso serão permanentemente removidos.</p>
            </div>
            <button type="button" class="btn btn-danger fw-medium px-4" @click="modalConfirmarExclusao = true">
              Excluir Minha Conta
            </button>
          </div>
        </div>

      </div>
    </main>

    <div class="modal" :class="{ show: modalConfirmarExclusao }" :style="{ display: modalConfirmarExclusao ? 'block' : 'none' }" tabindex="-1">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
          <div class="modal-header bg-danger text-white">
            <h5 class="modal-title fw-bold">⚠️ Ação Irreversível!</h5>
            <button type="button" class="btn-close btn-close-white" @click="fecharModalExclusao"></button>
          </div>
          <div class="modal-body text-dark text-start">
            <p>Você está prestes a apagar definitivamente a sua conta do sistema <strong>FaunaAqui</strong>.</p>
            <p class="small text-muted">Para confirmar que deseja prosseguir, digite a palavra <strong class="text-danger">EXCLUIR</strong> no campo abaixo:</p>
            
            <input 
              type="text" 
              class="form-control border-danger mt-2 text-center fw-bold" 
              v-model="confirmacaoTexto" 
              placeholder="Digite EXCLUIR para confirmar"
            >
          </div>
          <div class="modal-footer bg-light">
            <button type="button" class="btn btn-secondary" @click="fecharModalExclusao" :disabled="deletando">Cancelar</button>
            <button 
              type="button" 
              class="btn btn-danger fw-bold" 
              :disabled="confirmacaoTexto !== 'EXCLUIR' || deletando"
              @click="executarExclusaoConta"
            >
              <span v-if="deletando" class="spinner-border spinner-border-sm me-1"></span>
              Confirmar Exclusão
            </button>
          </div>
        </div>
      </div>
    </div>

    <div class="modal-backdrop fade" :class="{ show: modalConfirmarExclusao }" v-if="modalConfirmarExclusao"></div>
  </div>
</template>

<script>
import NavBar from '@/components/NavBar.vue';
import axios from 'axios';

const isLocal = window.location.hostname === 'localhost' || window.location.hostname === '127.0.0.1';
const API_BASE_URL = isLocal ? 'http://localhost:8000' : 'https://conviva-labev.onrender.com';

export default {
  components: { NavBar },
  data() {
    return {
      carregando: false,
      deletando: false,
      modalConfirmarExclusao: false,
      confirmacaoTexto: '',
      sucessoMensagem: '',
      erroMensagem: '',
      tipoConta: '',
      perfil: {
        name: '',
        email: '',
        password: '',
        password_confirmation: ''
      }
    }
  },
  methods: {
    carregarDadosLocais() {
      const userDataStr = localStorage.getItem('usuario_logado_dados');
      const userData = userDataStr ? JSON.parse(userDataStr) : {};
      
      this.perfil.name = localStorage.getItem('user_name') || userData.name || '';
      this.perfil.email = localStorage.getItem('user_email') || userData.email || ''; 
      this.tipoConta = localStorage.getItem('user_tipo') || userData.tipo_conta || 'Colaborador';
    },
    async salvarPerfil() {
      if (this.perfil.password && this.perfil.password !== this.perfil.password_confirmation) {
        this.erroMensagem = 'As senhas digitadas não coincidem!';
        return;
      }

      try {
        this.carregando = true;
        this.erroMensagem = '';
        this.sucessoMensagem = '';

        const token = localStorage.getItem('fauna_token');
        const response = await axios.put(`${API_BASE_URL}/api/perfil`, this.perfil, {
          headers: { Authorization: `Bearer ${token}` }
        });

        localStorage.setItem('user_name', response.data.user.name);
        this.sucessoMensagem = 'Seu perfil foi atualizado com sucesso!';
        
        this.perfil.password = '';
        this.perfil.password_confirmation = '';
      } catch (error) {
        console.error('Erro ao atualizar perfil:', error);
        this.erroMensagem = error.response?.data?.message || 'Não foi possível salvar as alterações.';
      } finally {
        this.carregando = false;
      }
    },
    fecharModalExclusao() {
      this.modalConfirmarExclusao = false;
      this.confirmacaoTexto = '';
    },
    async executarExclusaoConta() {
      if (this.confirmacaoTexto !== 'EXCLUIR') return;

      try {
        this.deletando = true;
        const token = localStorage.getItem('fauna_token');

        await axios.delete(`${API_BASE_URL}/api/perfil`, {
          headers: { Authorization: `Bearer ${token}` }
        });

        alert('Sua conta foi excluída com sucesso.');

        // 🧼 Limpa todas as credenciais guardadas no navegador para deslogar completamente
        localStorage.clear();

        // Remove o cabeçalho global do Axios
        delete axios.defaults.headers.common['Authorization'];

        // Redireciona o usuário expulso de volta para a tela de início
        this.$router.push({ name: 'home' });

      } catch (error) {
        console.error('Erro ao excluir conta própria:', error);
        alert(error.response?.data?.message || 'Ocorreu um erro ao tentar excluir sua conta.');
        this.fecharModalExclusao();
      } finally {
        this.deletando = false;
      }
    }
  },
  mounted() {
    this.carregarDadosLocais();
  }
}
</script>

<style scoped>
.fs-7 { font-size: 0.85rem; }
.modal.show { display: block !important; background-color: rgba(0, 0, 0, 0.6); }
.modal-backdrop { position: fixed; top: 0; left: 0; z-index: 1040; width: 100vw; height: 100vh; background-color: #000; }
.modal-backdrop.show { opacity: 0.6; }
</style>