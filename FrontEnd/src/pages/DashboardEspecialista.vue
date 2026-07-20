<template>
  <div class="dashboard-page bg-light min-vh-100 d-flex flex-column">
    <NavBar />

    <div class="container-fluid px-4 flex-grow-1 py-4">
      
      <CardsMetricas :total="142" :pendentes="12" :urgentes="5" :especies="38" />

      <div class="row g-4">
        <div class="col-lg-8">
          <div class="data-card p-4 h-100 shadow-sm">
            <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
              <h4 class="fw-bold m-0 text-dark">Denúncias</h4>
              <div class="input-group w-auto">
                <span class="input-group-text bg-white border-0 shadow-sm"><i class="fas fa-search text-muted"></i></span>
                <input type="text" class="form-control border-0 shadow-sm" placeholder="Buscar ocorrência...">
              </div>
            </div>

            <!-- Sub-tabs for Pendentes, Em Análise and Arquivadas -->
            <ul class="nav nav-tabs mb-3">
              <li class="nav-item">
                <a class="nav-link" :class="{ active: activeTab === 'pendentes' }" href="#" @click.prevent="activeTab = 'pendentes'">
                  Pendentes
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" :class="{ active: activeTab === 'em-analise' }" href="#" @click.prevent="activeTab = 'em-analise'">
                  Em Análise
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" :class="{ active: activeTab === 'arquivadas' }" href="#" @click.prevent="activeTab = 'arquivadas'">
                  Arquivadas
                </a>
              </li>
            </ul>
            
            <div class="table-responsive bg-white rounded-4 shadow-sm p-2">
              <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                  <tr>
                    <th>Animal</th>
                    <th>Localização</th>
                    <th>Data</th>
                    <th>Status</th>
                    <th class="text-center">Ações</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="d in denunciasFiltradas" :key="d.id">
                    <td><span class="fw-bold">{{ d.tipo_animal || d.animal }}</span></td>
                    <td><small class="text-muted">{{ d.ponto_referencia || d.local }}</small></td>
                    <td><small>{{ formatarData(d.created_at) }}</small></td>
                    <td>
                      <span :class="['badge rounded-pill', getSituacaoClass(d.status)]">
                        {{ d.status }}
                      </span>
                    </td>
                    <td class="text-center">
                      <button v-if="activeTab === 'pendentes'" class="btn btn-sm btn-light text-success me-1 shadow-sm" @click="handleValidar(d)">
                        <i class="fas fa-eye"></i>
                      </button>
                      <button v-if="activeTab === 'pendentes'" class="btn btn-sm btn-light text-primary shadow-sm" @click="gerarLaudo(d)">
                        <i class="fas fa-file-pdf"></i>
                      </button>
                      <button v-if="activeTab === 'em-analise'" class="btn btn-sm btn-light text-info shadow-sm" @click="handleEmAnalise(d)">
                        <i class="fas fa-edit"></i>
                      </button>
                      <button v-if="activeTab === 'arquivadas'" class="btn btn-sm btn-light text-secondary shadow-sm" @click="handleVerDetalhes(d)">
                        <i class="fas fa-info-circle"></i>
                      </button>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <div class="col-lg-4">
          <PainelAnalise @gerenciarEspecies="handleGerenciarEspecies" />
        </div>
      </div>
    </div>

    <ModalValidacao 
      :denuncia="selectedDenuncia" 
      @fechar="selectedDenuncia = null" 
      @alocar="processarAlocacao"
      @arquivar="processarArquivamento"
    />

    <ModalEmAnalise 
      v-if="selectedDenuncia && activeTab === 'em-analise'"
      :denuncia="selectedDenuncia"
      @fechar="selectedDenuncia = null"
      @arquivar="processarArquivamentoComDados"
    />

    <ModalArquivada 
      v-if="selectedDenuncia && activeTab === 'arquivadas'"
      :denuncia="selectedDenuncia"
      @fechar="selectedDenuncia = null"
    />
  </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue';
import NavBar from '@/components/NavBar.vue';
import CardsMetricas from '@/components/especialista/CardsMetricas.vue';
import PainelAnalise from '@/components/especialista/PainelAnalise.vue';
import ModalValidacao from '@/components/especialista/ModalValidacao.vue';
import ModalEmAnalise from '@/components/especialista/ModalEmAnalise.vue';
import ModalArquivada from '@/components/especialista/ModalArquivada.vue';
import { exportarLaudoOcorrencia } from '@/utils/exportLaudo';
import { resolveStorageUrl } from '@/utils/mediaUrl';
import axios from 'axios';

// Identifica se está rodando localmente ou no Render
const isLocal = window.location.hostname === 'localhost' || window.location.hostname === '127.0.0.1';

// Configura a URL base da API dinamicamente baseado no ambiente
const API_BASE_URL = isLocal 
  ? 'http://localhost:8000' 
  : 'https://conviva-labev.onrender.com';

const selectedDenuncia = ref(null);
const carregando = ref(true);
const denunciasPendentes = ref([]);
const denunciasEmAnalise = ref([]);
const denunciasArquivadas = ref([]);
const activeTab = ref('pendentes');

// Computed property to filter denuncias based on active tab
const denunciasFiltradas = computed(() => {
  if (activeTab.value === 'pendentes') return denunciasPendentes.value;
  if (activeTab.value === 'em-analise') return denunciasEmAnalise.value;
  return denunciasArquivadas.value;
});

// Função assincrona para buscar as denuncias no banco
const buscarOcorrenciasPendentes = async () => {
  try {
    carregando.value = true;
    const response = await axios.get(`${API_BASE_URL}/api/ocorrencias/pendentes`);
    denunciasPendentes.value = response.data;
  } catch (error) {
    console.error('Error ao conectar com a API:', error);
  } finally {
    carregando.value = false;
  }
}

const buscarOcorrenciasEmAnalise = async () => {
  try {
    const response = await axios.get(`${API_BASE_URL}/api/ocorrencias/em-analise`);
    denunciasEmAnalise.value = response.data;
  } catch (error) {
    console.error('Error ao conectar com a API:', error);
  }
}

const buscarOcorrenciasArquivadas = async () => {
  try {
    const response = await axios.get(`${API_BASE_URL}/api/ocorrencias/arquivadas`);
    denunciasArquivadas.value = response.data;
  } catch (error) {
    console.error('Error ao conectar com a API:', error);
  }
}

const getSituacaoClass = (situacao) => {
  if (situacao === 'Morto') return 'bg-danger text-white';
  if (situacao === 'Ferido') return 'bg-warning text-dark';
  return 'bg-secondary text-white';
};

const formatarData = (data) => {
  if (!data) return 'Data não informada';
  return new Date(data).toLocaleDateString('pt-BR');
};

const handleValidar = (denuncia) => {
  selectedDenuncia.value = denuncia;
};

const handleEmAnalise = (denuncia) => {
  selectedDenuncia.value = denuncia;
};

const handleVerDetalhes = (denuncia) => {
  selectedDenuncia.value = denuncia;
};

const processarAlocacao = async (data) => {
  try {
    await axios.put(`${API_BASE_URL}/api/ocorrencias/${data.denunciaId}/alocar`, { usuario: data.usuario });

    alert('Ocorrência alocada com sucesso!');
    selectedDenuncia.value = null;
    activeTab.value = 'em-analise';

    buscarOcorrenciasPendentes();
    buscarOcorrenciasEmAnalise();
  } catch (error) {
    console.error('Erro ao processar alocação:', error);
    alert('Erro ao processar alocação da ocorrência.');
  }
};

const processarArquivamento = async (data) => {
  try {
    await axios.put(`${API_BASE_URL}/api/ocorrencias/${data.denunciaId}/arquivar`, {
      registrado_por: getCurrentUserName() || 'Usuário'
    });

    alert('Ocorrência arquivada com sucesso!');
    selectedDenuncia.value = null;

    buscarOcorrenciasPendentes();
    buscarOcorrenciasArquivadas();
  } catch (error) {
    console.error('Erro ao processar arquivamento:', error);
    alert('Erro ao processar arquivamento da ocorrência.');
  }
};

const processarArquivamentoComDados = async (data) => {
  try {
    await axios.put(`${API_BASE_URL}/api/ocorrencias/${data.denunciaId}/arquivar`, data);

    alert('Ocorrência arquivada com sucesso!');
    selectedDenuncia.value = null;
    activeTab.value = 'arquivadas';

    buscarOcorrenciasEmAnalise();
    buscarOcorrenciasArquivadas();
  } catch (error) {
    console.error('Erro ao processar arquivamento:', error);
    alert('Erro ao processar arquivamento da ocorrência.');
  }
};

const gerarLaudo = (denuncia) => {
  const exportado = exportarLaudoOcorrencia({
    id: denuncia.id,
    tipo_animal: denuncia.tipo_animal ?? denuncia.animal,
    distincao_biologica: denuncia.distincao_biologica,
    situacao_animal: denuncia.situacao_animal ?? denuncia.status,
    statusWorkflow: denuncia.status,
    denunciante_nome: denuncia.denunciante_nome ?? denuncia.denunciante,
    ponto_referencia: denuncia.ponto_referencia ?? denuncia.local,
    descricao: denuncia.descricao ?? denuncia.descricao_ocorrencia,
    created_at: denuncia.created_at,
    parecer_tecnico: denuncia.parecer_tecnico,
    latitude: denuncia.latitude,
    longitude: denuncia.longitude,
    imagem: resolveStorageUrl(denuncia.foto_url || denuncia.foto_path),
  });

  if (!exportado) {
    alert('Não foi possível abrir a janela de exportação. Verifique se o bloqueador de pop-ups está desativado.');
  }
};

const handleGerenciarEspecies = () => {
  alert('Navegar para gerenciamento de espécies.');
};

const getCurrentUserName = () => {
  try {
    const stored = localStorage.getItem('usuarioLogado');
    if (!stored) return null;
    const u = JSON.parse(stored);
    return u.nome || u.email || null;
  } catch (e) {
    return null;
  }
};

onMounted(() => {
  buscarOcorrenciasPendentes();
  buscarOcorrenciasEmAnalise();
  buscarOcorrenciasArquivadas();
});
</script>

<style scoped>
.data-card {
  background: #58d68d;
  border-radius: 25px;
  color: #1e293b;
}
.table { --bs-table-bg: transparent; }
.table-responsive { max-height: 400px; }
.btn-primary-light { background-color: #27ae60; color: white; border: none; border-radius: 15px; }
.rounded-4 { border-radius: 1.25rem; }
</style>