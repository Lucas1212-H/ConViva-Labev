<template>
  <!-- Indicador compacto para mobile/tablet -->
  <div class="col-12 d-md-none mobile-step-bar px-0">
    <div class="bg-success text-white px-3 py-3 rounded-top-4">
      <div class="d-flex justify-content-between align-items-center mb-2">
        <span class="small fw-bold">Passo {{ passoAtual }} de 5</span>
        <span class="small opacity-75">{{ labelPassoAtual }}</span>
      </div>
      <div class="progress bg-white bg-opacity-25" style="height: 6px;">
        <div
          class="progress-bar bg-white"
          role="progressbar"
          :style="{ width: `${(passoAtual / 5) * 100}%` }"
        ></div>
      </div>
    </div>
  </div>

  <!-- Painel lateral para desktop -->
  <div class="col-md-5 col-lg-4 bg-success text-white p-4 d-none d-md-flex flex-column justify-content-between painel-lateral">
    <div>
      <div class="d-flex align-items-center gap-2 mb-3">
        <img src="@/assets/images/logo_sf.png" alt="ConViva" style="width: 40px; height: 40px;">
        <h2 class="h5 m-0 fw-bold tracking-wider">ConViva</h2>
      </div>
      <p class="small text-white-50 lh-sm">Sistema de Monitoramento e Registro de Ocorrências Animais no Campus.</p>
    </div>

    <div class="my-auto py-3">
      <div
        v-for="passo in passos"
        :key="passo.numero"
        :class="['d-flex align-items-center gap-3 mb-3 transition-all', passoAtual === passo.numero ? 'opacity-100 fw-bold' : 'opacity-50']"
      >
        <div class="rounded-circle bg-white text-success d-flex align-items-center justify-content-center fw-bold step-number" style="width: 32px; height: 32px;">
          {{ passo.numero }}
        </div>
        <div class="small lh-1">
          {{ passo.titulo }}
          <small class="d-block text-white-50 fw-normal mt-1">{{ passo.subtitulo }}</small>
        </div>
      </div>
    </div>

    <div class="small text-white-50 border-top border-white-10 pt-3">
      © 2026 UFPA — Segurança Ambiental
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
  passoAtual: {
    type: Number,
    required: true
  }
})

const passos = [
  { numero: 1, titulo: 'Contato', subtitulo: 'Nome e telefone' },
  { numero: 2, titulo: 'Identificação', subtitulo: 'O que você observou?' },
  { numero: 3, titulo: 'Detalhes', subtitulo: 'Tipo e situação' },
  { numero: 4, titulo: 'Localização', subtitulo: 'Onde aconteceu?' },
  { numero: 5, titulo: 'Foto', subtitulo: 'Evidência visual' }
]

const labelPassoAtual = computed(() => {
  return passos.find((p) => p.numero === props.passoAtual)?.titulo ?? ''
})
</script>

<style scoped>
.mobile-step-bar .progress-bar {
  transition: width 0.3s ease;
}

.rounded-top-4 {
  border-top-left-radius: 1.5rem;
  border-top-right-radius: 1.5rem;
}
</style>
