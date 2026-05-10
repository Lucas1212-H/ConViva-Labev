<template>
  <div class="dashboard-page bg-light min-vh-100 d-flex flex-column">
    <NavBar />

    <div class="container-fluid px-4 flex-grow-1 py-4">
      
      <div class="row g-3 mb-4 text-white text-center">
        <div class="col-6 col-md-3">
          <div class="p-3 shadow-sm rounded-4 bg-success-gradient">
            <small class="opacity-75">Total Registros</small>
            <h2 class="fw-bold m-0">142</h2>
          </div>
        </div>
        <div class="col-6 col-md-3">
          <div class="p-3 shadow-sm rounded-4 bg-warning-gradient">
            <small class="opacity-75">Pendentes</small>
            <h2 class="fw-bold m-0">12</h2>
          </div>
        </div>
        <div class="col-6 col-md-3">
          <div class="p-3 shadow-sm rounded-4 bg-danger-gradient">
            <small class="opacity-75">Urgentes</small>
            <h2 class="fw-bold m-0">05</h2>
          </div>
        </div>
        <div class="col-6 col-md-3">
          <div class="p-3 shadow-sm rounded-4 bg-purple-gradient">
            <small class="opacity-75">Espécies</small>
            <h2 class="fw-bold m-0">38</h2>
          </div>
        </div>
      </div>

      <div class="row g-4">
        <div class="col-lg-8">
          <div class="data-card p-4 h-100 shadow-sm">
            <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
              <h4 class="fw-bold m-0 text-dark">Fila de Triagem</h4>
              <div class="input-group w-auto">
                <span class="input-group-text bg-white border-0 shadow-sm"><i class="fas fa-search text-muted"></i></span>
                <input type="text" class="form-control border-0 shadow-sm" placeholder="Buscar ocorrência...">
              </div>
            </div>
            
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
                  <tr v-for="d in denuncias" :key="d.id">
                    <td><span class="fw-bold">{{ d.animal }}</span></td>
                    <td><small class="text-muted">{{ d.local }}</small></td>
                    <td><small>{{ d.data }}</small></td>
                    <td>
                      <span :class="['badge rounded-pill', getStatusClass(d.status)]">
                        {{ d.status }}
                      </span>
                    </td>
                    <td class="text-center">
                      <button class="btn btn-sm btn-light text-success me-1 shadow-sm" @click="handleValidar(d)">
                        <i class="fas fa-eye"></i>
                      </button>
                      <button class="btn btn-sm btn-light text-primary shadow-sm" @click="gerarLaudo(d)">
                        <i class="fas fa-file-pdf"></i>
                      </button>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>

            <button class="btn btn-primary-light w-100 mt-4 py-3 fw-bold" @click="handleVerArquivados">
              Ver Histórico Completo
            </button>
          </div>
        </div>

        <div class="col-lg-4">
          <div class="data-card p-4 h-100 text-center d-flex flex-column shadow-sm">
            <h4 class="mb-4 fw-bold">Análise de Campo</h4>
            
            <div class="pie-chart-sim mx-auto mb-4"></div>
            
            <div class="status-legend d-flex justify-content-center text-start small mb-4 flex-wrap gap-3">
              <span><i class="fas fa-circle text-danger me-1"></i> Mortos</span>
              <span><i class="fas fa-circle text-primary me-1"></i> Feridos</span>
              <span><i class="fas fa-circle text-warning me-1"></i> Avistados</span>
            </div>

            <div class="alert-box text-start mb-4">
              <h6 class="fw-bold mb-2 small text-uppercase">Comunicados Equipe</h6>
              <div class="alert alert-light border-0 shadow-sm py-2 mb-2">
                <i class="fas fa-exclamation-triangle text-warning me-2"></i>
                <small>Alta incidência de Saruês no Setor Norte.</small>
              </div>
            </div>

            <button class="btn btn-purple w-100 py-3 fw-bold text-white mt-auto" @click="handleGerenciarEspecies">
              Base de Dados Taxonômica
            </button>
          </div>
        </div>
      </div>
    </div>

    <div v-if="selectedDenuncia" class="custom-modal-overlay d-flex align-items-center justify-content-center">
      <div class="custom-modal bg-white p-4 rounded-4 shadow-lg w-50">
        <h4 class="fw-bold text-success mb-3">Validar Ocorrência: {{ selectedDenuncia.animal }}</h4>
        <div class="row">
          <div class="col-md-6">
            <img src="https://picsum.photos/400/300" class="img-fluid rounded-3 mb-3" alt="Foto Animal">
          </div>
          <div class="col-md-6">
            <p><strong>Local:</strong> {{ selectedDenuncia.local }}</p>
            <p><strong>Status:</strong> {{ selectedDenuncia.status }}</p>
            <textarea class="form-control mb-3" placeholder="Escreva o parecer técnico..."></textarea>
            <div class="d-flex gap-2">
              <button class="btn btn-success flex-grow-1" @click="selectedDenuncia = null">Aprovar</button>
              <button class="btn btn-light flex-grow-1" @click="selectedDenuncia = null">Fechar</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import NavBar from '@/components/NavBar.vue';


const router = useRouter();
const selectedDenuncia = ref(null);

const denuncias = ref([
  { id: 1, animal: 'Serpente', local: 'Reserva Ambiental', data: '29/04/2025', status: 'Morto' },
  { id: 2, animal: 'Capivara', local: 'Parque do Lago', data: '27/04/2025', status: 'Ferido' },
  { id: 3, animal: 'Gavião', local: 'Área Urbana Central', data: '25/04/2025', status: 'Avistado' },
  { id: 4, animal: 'Lobo-Guará', local: 'Estrada da Ceasa', data: '24/04/2025', status: 'Morto' }
]);

const getStatusClass = (status) => {
  if (status === 'Morto') return 'bg-danger';
  if (status === 'Ferido') return 'bg-primary';
  return 'bg-warning text-dark';
};

const handleValidar = (d) => {
  selectedDenuncia.value = d;
};

const gerarLaudo = (d) => {
  alert(`Gerando Laudo Técnico em PDF para: ${d.animal}`);
};

const handleVerArquivados = () => console.log('Ver Arquivados');
const handleGerenciarEspecies = () => console.log('Gerenciar Espécies');

const handleLogout = () => {
  localStorage.removeItem('usuarioLogado');
  router.push('/');
};
</script>

<style scoped>
/* CORES GRADIENTES PARA OS CARDS (Destaque Profissional) */
.bg-success-gradient { background: linear-gradient(135deg, #2ecc71, #27ae60); }
.bg-warning-gradient { background: linear-gradient(135deg, #f1c40f, #f39c12); }
.bg-danger-gradient { background: linear-gradient(135deg, #e74c3c, #c0392b); }
.bg-purple-gradient { background: linear-gradient(135deg, #a569bd, #8e44ad); }

.rounded-4 { border-radius: 1.25rem; }

.data-card {
  background: #58d68d; /* Mantendo sua cor base do anexo */
  border-radius: 25px;
  color: #1e293b;
}

.table { --bs-table-bg: transparent; }
.table-responsive { max-height: 400px; }

.pie-chart-sim {
  width: 180px;
  height: 180px;
  border-radius: 50%;
  background: conic-gradient(#e74c3c 0% 57%, #3498db 57% 83%, #f1c40f 83% 100%);
  border: 10px solid white;
}

.user-avatar {
  width: 40px; height: 40px;
  background: #fff; color: #2ecc71;
  border-radius: 50%; display: flex;
  align-items: center; justify-content: center;
  font-weight: bold; border: 2px solid #2ecc71;
}

.bg-success-soft { background-color: #e8f5e9; }

/* MODAL SIMULADO */
.custom-modal-overlay {
  position: fixed; top: 0; left: 0; width: 100%; height: 100%;
  background: rgba(0,0,0,0.5); z-index: 1000;
}
.custom-modal { max-width: 90%; }

.btn-purple { background-color: #a569bd; border: none; border-radius: 15px; }
.btn-primary-light { background-color: #27ae60; color: white; border: none; border-radius: 15px; }

@media (max-width: 768px) {
  .custom-modal { width: 95%; }
}
</style>