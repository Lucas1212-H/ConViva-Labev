<template>
  <div>
    <NavBar />

    <main class="container py-5">
      <div v-if="!eAdmin" class="alert alert-danger text-center shadow-sm">
        <h4>Acesso Negado</h4>
        <p>Esta página é restrita apenas para Administradores do sistema FaunaAqui.</p>
        <button class="btn btn-dark btn-sm mt-2" @click="$router.push('/')">Voltar ao Início</button>
      </div>

      <section v-else>
        <header class="mb-4">
          <h1 class="h3 fw-bold text-dark m-0">Gerenciamento de Contas</h1>
          <p class="text-muted small">Aprove novas solicitações de acesso ou gerencie os colaboradores ativos na plataforma.</p>
        </header>

        <ul class="nav nav-tabs mb-4" id="adminTabs" role="tablist">
          <li class="nav-item" role="presentation">
            <button 
              class="nav-link position-relative" 
              :class="{ active: abaAtiva === 'pendentes' }" 
              @click="abaAtiva = 'pendentes'"
              type="button"
            >
               Solicitações Pendentes
              <span v-if="usuariosPendentes.length > 0" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                {{ usuariosPendentes.length }}
              </span>
            </button>
          </li>
          <li class="nav-item" role="presentation">
            <button 
              class="nav-link" 
              :class="{ active: abaAtiva === 'ativos' }" 
              @click="abaAtiva = 'ativos'"
              type="button"
            >
              Colaboradores Ativos
            </button>
          </li>
        </ul>

        <div v-if="abaAtiva === 'pendentes'">
          <div v-if="carregando" class="text-center py-5">
            <div class="spinner-border text-secondary" role="status"></div>
            <p class="text-muted small mt-2">Buscando solicitações...</p>
          </div>

          <div v-else-if="usuariosPendentes.length === 0" class="alert alert-light text-center border py-4">
            <span class="fs-3 d-block mb-2">🎈</span>
            <p class="text-muted m-0">Nenhuma solicitação de cadastro aguardando aprovação no momento.</p>
          </div>

          <div v-else class="card border-0 shadow-sm rounded-3 overflow-hidden">
            <div class="table-responsive">
              <table class="table table-hover align-middle mb-0">
                <thead class="table-light text-secondary small text-uppercase">
                  <tr>
                    <th class="ps-4">Nome</th>
                    <th>E-mail</th>
                    <th>Solicitado em</th>
                    <th class="text-end pe-4">Ação</th>
                  </tr>
                </thead>
                <tbody class="text-dark">
                  <tr v-for="user in usuariosPendentes" :key="user.id">
                    <td class="ps-4 fw-medium">{{ user.name }}</td>
                    <td>{{ user.email }}</td>
                    <td class="text-muted small">
                      {{ formatarData(user.created_at) }}
                    </td>
                    <td class="text-end pe-4">
                      <button 
                        class="btn btn-sm btn-success fw-semibold px-3" 
                        @click="aprovarColaborador(user)"
                        :disabled="processandoId === user.id"
                      >
                        <span v-if="processandoId === user.id" class="spinner-border spinner-border-sm me-1"></span>
                        Aprovar Cadastro
                      </button>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <div v-if="abaAtiva === 'ativos'">
          <div v-if="carregando" class="text-center py-5">
            <div class="spinner-border text-secondary" role="status"></div>
            <p class="text-muted small mt-2">Buscando colaboradores...</p>
          </div>

          <div v-else class="card border-0 shadow-sm rounded-3 overflow-hidden">
            <div class="table-responsive">
              <table class="table table-hover align-middle mb-0">
                <thead class="table-light text-secondary small text-uppercase">
                  <tr>
                    <th class="ps-4">Nome</th>
                    <th>E-mail</th>
                    <th>Nível de Acesso</th>
                    <th class="text-end pe-4"></th>
                  </tr>
                </thead>
                <tbody class="text-dark">
                  <tr v-for="user in usuariosAtivos" :key="user.id">
                    <td class="ps-4 fw-medium">{{ user.name }}</td>
                    <td>{{ user.email }}</td>
                    <td>
                      <span 
                        class="badge rounded-pill px-3 py-1.5 fs-7"
                        :class="user.tipo_conta === 'Administrador' ? 'bg-danger-subtle text-danger' : 'bg-primary-subtle text-primary'"
                      >
                        {{ user.tipo_conta }}
                      </span>
                    </td>
                    <td class="text-end pe-4">
                      <button class="btn btn-sm btn-outline-primary me-2" @click="abrirEdicao(user)">
                        Editar
                      </button>
                      <button class="btn btn-sm btn-outline-danger" @click="deletarUsuario(user.id)">
                        Excluir
                      </button>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </section>
    </main>

    <div class="modal" :class="{ show: modalEditar }" :style="{ display: modalEditar ? 'block' : 'none' }" tabindex="-1">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header bg-dark text-white">
            <h5 class="modal-title">Alterar Permissões de Usuário</h5>
            <button type="button" class="btn-close btn-close-white" @click="modalEditar = false"></button>
          </div>
          <div class="modal-body text-dark">
            <div class="mb-3 text-start">
              <label class="form-label fw-semibold small">Nome Completo</label>
              <input type="text" class="form-control" v-model="formUser.name" required>
            </div>
            <div class="mb-3 text-start">
              <label class="form-label fw-semibold small">E-mail Organizacional</label>
              <input type="email" class="form-control" v-model="formUser.email" required>
            </div>
            <div class="mb-3 text-start">
              <label class="form-label fw-semibold small">Tipo de Conta (Nível de Acesso)</label>
              <select class="form-select" v-model="formUser.tipo_conta">
                <option value="Colaborador">Colaborador (Acesso padrão)</option>
                <option value="Administrador">Administrador (Controle Total)</option>
              </select>
            </div>
            <div class="mb-3 text-start">
              <label class="form-label fw-semibold small">Alterar Senha (Opcional)</label>
              <input type="password" class="form-control" v-model="formUser.password" placeholder="Deixe vazio para manter a atual">
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" @click="modalEditar = false">Cancelar</button>
            <button type="button" class="btn btn-dark" @click="salvarAlteracoes" :disabled="salvando">
              {{ salvando ? 'Salvando...' : 'Salvar Alterações' }}
            </button>
          </div>
        </div>
      </div>
    </div>

    <div class="modal-backdrop fade" :class="{ show: modalEditar }" v-if="modalEditar"></div>
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
      abaAtiva: 'pendentes', // Controla qual aba inicia aberta
      carregando: false,
      salvando: false,
      processandoId: null, // Controla o spinner de aprovação individual
      modalEditar: false,
      usuariosPendentes: [],
      usuariosAtivos: [],
      formUser: {
        id: null,
        name: '',
        email: '',
        tipo_conta: '',
        password: ''
      }
    }
  },
  computed: {
    eAdmin() {
      return localStorage.getItem('user_tipo') === 'Administrador';
    }
  },
  methods: {
    async buscarTodosUsuarios() {
      try {
        this.carregando = true;
        const token = localStorage.getItem('fauna_token');
        
        const response = await axios.get(`${API_BASE_URL}/api/usuarios`, {
          headers: { Authorization: `Bearer ${token}` }
        });

        // 🧠 Separação inteligente dos dados vindos do banco
        this.usuariosPendentes = response.data.filter(u => u.status === 'Pendente');
        this.usuariosAtivos = response.data.filter(u => u.status !== 'Pendente');

      } catch (error) {
        console.error('Erro ao buscar lista de usuários:', error);
      } finally {
        this.carregando = false;
      }
    },

    async aprovarColaborador(user) {
      if (!confirm(`Confirmar o registro de ${user.name} e ativar acesso ao sistema?`)) return;

      try {
        this.processandoId = user.id;
        const token = localStorage.getItem('fauna_token');

        const response = await axios.post(`${API_BASE_URL}/api/usuarios/${user.id}/aprovar`, {}, {
          headers: { Authorization: `Bearer ${token}` }
        });

        alert(response.data.message || 'Colaborador aprovado com sucesso!');
        this.buscarTodosUsuarios(); // Recarrega as duas listas automaticamente
      } catch (error) {
        console.error(error);
        alert('Erro ao aprovar colaborador.');
      } finally {
        this.processandoId = null;
      }
    },

    abrirEdicao(user) {
      this.formUser = {
        id: user.id,
        name: user.name,
        email: user.email,
        tipo_conta: user.tipo_conta,
        password: ''
      };
      this.modalEditar = true;
    },

    async salvarAlteracoes() {
      try {
        this.salvando = true;
        const token = localStorage.getItem('fauna_token');
        
        await axios.put(`${API_BASE_URL}/api/usuarios/${this.formUser.id}`, this.formUser, {
          headers: { Authorization: `Bearer ${token}` }
        });

        alert('Conta atualizada com sucesso!');
        this.modalEditar = false;
        this.buscarTodosUsuarios();
      } catch (error) {
        console.error(error);
        alert('Erro ao salvar alterações.');
      } finally {
        this.salvando = false;
      }
    },

    async deletarUsuario(id) {
      if (!confirm('Deseja remover permanentemente este usuário do sistema?')) return;

      try {
        const token = localStorage.getItem('fauna_token');
        await axios.delete(`${API_BASE_URL}/api/usuarios/${id}`, {
          headers: { Authorization: `Bearer ${token}` }
        });

        alert('Usuário removido!');
        this.buscarTodosUsuarios();
      } catch (error) {
        console.error(error);
        alert('Erro ao remover usuário.');
      }
    },

    formatarData(dataString) {
      if (!dataString) return '';
      return new Date(dataString).toLocaleDateString('pt-BR');
    }
  },
  mounted() {
    if (this.eAdmin) {
      this.buscarTodosUsuarios();
    }
  }
}
</script>

<style scoped>
.modal.show { display: block !important; background-color: rgba(0, 0, 0, 0.5); }
.modal-backdrop { position: fixed; top: 0; left: 0; z-index: 1040; width: 100vw; height: 100vh; background-color: #000; }
.modal-backdrop.show { opacity: 0.5; }
.nav-tabs .nav-link { color: #495057; cursor: pointer; font-weight: 500; }
.nav-tabs .nav-link.active { color: #000; font-weight: 600; border-bottom-color: #000; }
.fs-7 { font-size: 0.85rem; }
</style>