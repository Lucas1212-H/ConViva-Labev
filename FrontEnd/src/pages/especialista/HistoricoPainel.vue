<template>
  <section class="row g-4">
    <article class="col-12">
      <div class="archived-card p-4 shadow-sm">
        <header class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-3 panel-header">
          <div>
            <h4 class="fw-bold m-0 text-dark">Denúncias</h4>
            <small class="text-muted header-hint">Gerencie denúncias pendentes e arquivadas</small>
          </div>
        </header>

        <!-- Sub-tabs -->
        <div class="subtabs-wrapper mb-4">
          <button
            class="subtab-btn"
            :class="{ active: subAba === 'pendentes' }"
            @click="subAba = 'pendentes'"
          >
            <i class="fas fa-clock me-1"></i> Pendentes
            <span v-if="props.pendentes?.length" class="badge-count ms-1">{{ props.pendentes.length }}</span>
          </button>
          <button
            class="subtab-btn"
            :class="{ active: subAba === 'arquivadas' }"
            @click="subAba = 'arquivadas'"
          >
            <i class="fas fa-archive me-1"></i> Arquivadas
            <span v-if="props.arquivadas?.length" class="badge-count ms-1">{{ props.arquivadas.length }}</span>
          </button>
        </div>

        <!-- ===== PENDENTES ===== -->
        <div v-if="subAba === 'pendentes'">
          <div class="d-flex justify-content-end mb-3">
            <div class="input-group search-group">
              <span class="input-group-text bg-white border-0 shadow-sm">
                <i class="fas fa-search text-muted"></i>
              </span>
              <input
                v-model="filtroPendentes"
                type="text"
                class="form-control border-0 shadow-sm"
                placeholder="Filtrar pendentes..."
              >
            </div>
          </div>

          <div class="table-responsive bg-white rounded-4 shadow-sm p-2">
            <table class="table table-hover align-middle mb-0">
              <thead class="table-light">
                <tr>
                  <th scope="col">Número de Registro</th>
                  <th scope="col">Denunciante</th>
                  <th scope="col">Local</th>
                  <th scope="col">Data</th>
                  <th scope="col">Status</th>
                </tr>
              </thead>
              <tbody>
                <tr
                  v-for="item in pendentesFiltrados"
                  :key="item.id"
                  class="clickable-row"
                  @click="$emit('selecionarPendente', item)"
                >
                  <td class="fw-bold">LABEV{{ String(item.id).padStart(2, '0') }}</td>
                  <td>{{ item.denunciante }}</td>
                  <td><small class="text-muted">{{ item.local }}</small></td>
                  <td><small>{{ item.data }}</small></td>
                  <td>
                    <span class="badge rounded-pill bg-warning text-dark">Em Atendimento</span>
                  </td>
                </tr>
                <tr v-if="pendentesFiltrados.length === 0">
                  <td colspan="5" class="text-center py-4 text-muted">Nenhuma denúncia pendente no momento.</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

        <!-- ===== ARQUIVADAS ===== -->
        <div v-else>
          <div class="d-flex justify-content-end gap-2 mb-3 flex-wrap">
            <button class="btn btn-outline-secondary btn-sm shadow-sm" type="button" @click="exportarCsv">
              Exportar CSV
            </button>
            <div class="input-group search-group">
              <span class="input-group-text bg-white border-0 shadow-sm">
                <i class="fas fa-search text-muted"></i>
              </span>
              <input
                v-model="filtro"
                type="text"
                class="form-control border-0 shadow-sm"
                placeholder="Filtrar arquivadas..."
              >
            </div>
          </div>

          <div class="table-responsive bg-white rounded-4 shadow-sm p-2">
            <table class="table table-hover align-middle mb-0">
              <thead class="table-light">
                <tr>
                  <th scope="col">Número de Registro</th>
                  <th scope="col">Denunciante</th>
                  <th scope="col">Processo</th>
                  <th scope="col">Data</th>
                  <th scope="col">Status final</th>
                  <th scope="col" class="text-end">Mapa</th>
                </tr>
              </thead>
              <tbody>
                <tr
                  v-for="item in arquivadasFiltradas"
                  :key="item.id"
                  class="clickable-row"
                  @click="$emit('selecionarHistorico', item)"
                >
                  <td class="fw-bold">LABEV{{ String(item.id).padStart(2, '0') }}</td>
                  <td>{{ item.denunciante }}</td>
                  <td>
                    <span class="badge rounded-pill bg-secondary-subtle text-secondary-emphasis process-badge">
                      {{ item.processoFinal }}
                    </span>
                  </td>
                  <td>{{ item.data }}</td>
                  <td>
                    <span v-if="item.publicadoNoMapa" class="badge rounded-pill bg-success-subtle text-success-emphasis me-1">No mapa</span>
                    <span class="badge rounded-pill" :class="item.publicadoNoMapa ? 'bg-primary-subtle text-primary-emphasis' : 'bg-dark-subtle text-dark'">
                      {{ item.publicadoNoMapa ? 'Publicado' : item.statusFinal }}
                    </span>
                  </td>
                  <td class="text-end">
                    <button
                      class="btn btn-sm shadow-sm action-btn"
                      :class="item.publicadoNoMapa ? 'btn-outline-danger' : 'btn-outline-success'"
                      type="button"
                      @click.stop="$emit('alternarPublicacao', item)"
                    >
                      {{ item.publicadoNoMapa ? 'Tirar do mapa' : 'Publicar no mapa' }}
                    </button>
                  </td>
                </tr>

                <tr v-if="arquivadasFiltradas.length === 0">
                  <td colspan="6" class="text-center py-4 text-muted">Nenhum registro arquivado encontrado.</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

      </div>
    </article>
  </section>
</template>

<script setup>
import { computed, ref } from 'vue'

const props = defineProps({
  arquivadas: {
    type: Array,
    required: true,
    default: () => []
  },
  pendentes: {
    type: Array,
    default: () => []
  }
})

const emit = defineEmits(['selecionarHistorico', 'selecionarPendente', 'alternarPublicacao'])

const subAba = ref('pendentes')
const filtro = ref('')
const filtroPendentes = ref('')

const normalizarTexto = (valor) => String(valor ?? '')
  .normalize('NFD')
  .replace(/[\u0300-\u036f]/g, '')
  .toLowerCase()

// --- Filtro de pendentes ---
const pendentesFiltrados = computed(() => {
  if (!props.pendentes) return []
  if (!filtroPendentes.value.trim()) return props.pendentes
  const termo = normalizarTexto(filtroPendentes.value)
  return props.pendentes.filter((item) => (
    normalizarTexto(item.animal).includes(termo) ||
    normalizarTexto(item.denunciante).includes(termo) ||
    normalizarTexto(item.local).includes(termo) ||
    normalizarTexto(item.data).includes(termo)
  ))
})

// --- Filtro de arquivadas ---
const arquivadasFiltradas = computed(() => {
  if (!props.arquivadas) return []
  if (!filtro.value.trim()) return props.arquivadas

  const termo = normalizarTexto(filtro.value)

  return props.arquivadas.filter((item) => (
    normalizarTexto(item.animal).includes(termo) ||
    normalizarTexto(item.denunciante).includes(termo) ||
    normalizarTexto(item.processoFinal).includes(termo) ||
    normalizarTexto(item.statusFinal).includes(termo) ||
    normalizarTexto(item.publicadoNoMapa ? 'No mapa' : 'Fora do mapa').includes(termo) ||
    normalizarTexto(item.data).includes(termo)
  ))
})

const exportarCsv = () => {
  const linhas = arquivadasFiltradas.value.map((item) => [
    item.animal,
    item.denunciante,
    item.processoFinal,
    item.data,
    item.publicadoNoMapa ? 'Publicado no mapa' : item.statusFinal,
    item.publicadoNoMapa ? 'Sim' : 'Não'
  ])

  const cabecalho = ['Animal', 'Denunciante', 'Processo', 'Data', 'Status', 'No mapa']
  const csv = [cabecalho, ...linhas]
    .map((linha) => linha.map((celula) => `"${String(celula ?? '').replace(/"/g, '""')}"`).join(';'))
    .join('\r\n')

  const blob = new Blob(['\ufeff' + csv], { type: 'text/csv;charset=utf-8;' })
  const url = URL.createObjectURL(blob)
  const link = document.createElement('a')
  link.href = url
  link.download = `denuncias_arquivadas_${new Date().toISOString().slice(0, 10)}.csv`
  document.body.appendChild(link)
  link.click()
  document.body.removeChild(link)
  URL.revokeObjectURL(url)
}
</script>

<style scoped>
.archived-card {
  background: #dfe6df;
  border-radius: 18px;
  color: #2f3a33;
  border: 1px solid #c8d0c9;
}

/* Sub-tabs */
.subtabs-wrapper {
  display: flex;
  gap: 0.5rem;
  border-bottom: 2px solid #c8d0c9;
  padding-bottom: 0;
}

.subtab-btn {
  background: transparent;
  border: none;
  border-bottom: 3px solid transparent;
  padding: 0.5rem 1.2rem;
  font-weight: 600;
  font-size: 0.9rem;
  color: #6e7b6e;
  cursor: pointer;
  transition: all 0.2s;
  margin-bottom: -2px;
  border-radius: 6px 6px 0 0;
}

.subtab-btn:hover {
  color: #2f4d3a;
  background: rgba(255,255,255,0.4);
}

.subtab-btn.active {
  color: #175d36;
  border-bottom-color: #175d36;
  background: rgba(255,255,255,0.6);
}

.badge-count {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  background: #175d36;
  color: white;
  border-radius: 999px;
  font-size: 0.72rem;
  font-weight: 700;
  padding: 0.1rem 0.45rem;
  min-width: 1.4rem;
}

.table {
  --bs-table-bg: transparent;
}

.table-responsive {
  overflow-x: auto;
  -webkit-overflow-scrolling: touch;
}

.rounded-4 {
  border-radius: 0.9rem;
}

.clickable-row {
  cursor: pointer;
}

.clickable-row:hover {
  background: #f4f7f4;
}

.process-badge {
  border-radius: 999px;
}

.header-hint {
  max-width: 420px;
}

.search-group {
  width: auto;
  min-width: 220px;
  max-width: 320px;
  flex-shrink: 0;
}

.action-btn {
  min-width: 140px;
}

@media (min-width: 992px) {
  .table-responsive {
    max-height: min(60vh, 520px);
    overflow-y: auto;
  }
}

@media (max-width: 991.98px) {
  .table-responsive {
    max-height: 400px;
    overflow-y: auto;
  }
}

@media (max-width: 767.98px) {
  .archived-card {
    padding: 1rem !important;
  }

  .header-hint {
    max-width: none;
    text-align: left;
    font-size: 0.75rem;
  }

  .search-group {
    width: 100% !important;
    max-width: none;
  }

  .action-btn {
    width: 100%;
    min-width: 0;
  }

  .subtab-btn {
    padding: 0.4rem 0.8rem;
    font-size: 0.82rem;
  }
}
</style>