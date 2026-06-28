<template>
  <div>
    <div class="text-uppercase text-muted fw-bold mt-2 mb-2" style="font-size: 0.7rem; letter-spacing: 0.8px;">
      Últimas ocorrências
    </div>

    <div v-if="carregando" class="text-center py-3">
      <div class="spinner-border spinner-border-sm text-success" role="status"></div>
      <p class="small text-muted mt-2 mb-0">Carregando registros...</p>
    </div>

    <div v-else-if="ocorrencias.length === 0" class="text-muted small py-2">
      Nenhuma ocorrência recente registrada no campus.
    </div>

    <div v-else class="d-flex flex-column">
      <div
        v-for="(item, index) in ocorrencias"
        :key="item.id"
        class="d-flex align-items-center py-3"
        :class="{ 'border-bottom border-light': index < ocorrencias.length - 1 }"
      >
        <div
          class="d-flex align-items-center justify-content-center rounded-circle me-3 flex-shrink-0"
          :class="isUrgente(item) ? 'bg-danger bg-opacity-10' : 'bg-success bg-opacity-10'"
          style="width: 32px; height: 32px; font-size: 14px;"
        >
          {{ iconeDistincao(item.distincao_biologica) }}
        </div>

        <div class="flex-grow-1 min-w-0">
          <h4 class="m-0 small fw-medium text-dark text-truncate" style="font-size: 14px;">
            {{ item.tipo_animal }} — {{ item.ponto_referencia }}
          </h4>
          <p class="m-0 text-muted" style="font-size: 11px;">
            {{ tempoRelativo(item.created_at) }} · {{ labelStatusWorkflow(item.status) }}
          </p>
        </div>

        <span
          v-if="isUrgente(item)"
          class="badge rounded-pill bg-danger bg-opacity-10 text-danger fw-bold flex-shrink-0 ms-2"
          style="font-size: 10px; padding: 4px 8px;"
        >
          Urgente
        </span>
      </div>
    </div>
  </div>
</template>

<script setup>
import { onMounted, ref } from 'vue'
import { ocorrenciaService } from '@/services/ocorrenciaService'
import { tempoRelativo, labelStatusWorkflow, isUrgente, iconeDistincao } from '@/utils/ocorrenciaFormat'

const carregando = ref(true)
const ocorrencias = ref([])

onMounted(async () => {
  try {
    carregando.value = true
    ocorrencias.value = await ocorrenciaService.listarRecentes()
  } catch (error) {
    console.error('Erro ao carregar últimas ocorrências:', error)
    ocorrencias.value = []
  } finally {
    carregando.value = false
  }
})
</script>
