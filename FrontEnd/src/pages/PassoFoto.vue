<template>
  <div>
    <div class="d-flex gap-1 mb-3" style="height: 4px;">
      <div class="flex-grow-1 bg-success rounded"></div>
      <div class="flex-grow-1 bg-success rounded"></div>
      <div class="flex-grow-1 bg-success rounded"></div>
    </div>

    <h2 class="h4 fw-bold mb-1 text-dark">Evidência visual</h2>
    <p class="small text-muted mb-4">Passo 3 de 3 — foto ou vídeo (opcional)</p>

    <div 
      class="border border-2 border-dashed rounded-4 bg-light d-flex align-items-center justify-content-center mb-4 position-relative overflow-hidden"
      style="height: 220px; border-style: dashed !important;"
    >
      <div v-if="!mediaPreview" class="text-center text-secondary">
        <div class="display-6 mb-2"></div>
        <p class="small m-0">Nenhuma foto ou vídeo selecionado</p>
      </div>
      
      <div v-else class="w-100 h-100">
        <img v-if="mediaType === 'image'" :src="mediaPreview" class="w-100 h-100 object-fit-cover" />
        <video v-else :src="mediaPreview" class="w-100 h-100 object-fit-cover" controls />
        <button 
          class="btn btn-dark btn-sm position-absolute top-0 end-0 m-2 rounded-pill opacity-75"
          @click="removerMedia"
        >
          ✕ Remover
        </button>
      </div>
    </div>

    <input type="file" accept="image/*" capture="environment" ref="inputCamera" class="d-none" @change="manipularArquivo" />
    <input type="file" accept="image/*" ref="inputGaleria" class="d-none" @change="manipularArquivo" />
    <input type="file" accept="video/*" capture="environment" ref="inputVideoCamera" class="d-none" @change="manipularArquivo" />
    <input type="file" accept="video/*" ref="inputVideoGaleria" class="d-none" @change="manipularArquivo" />

    <div class="d-grid gap-2 mb-4" v-if="!mediaPreview">
      <button class="btn btn-outline-success py-3 fw-bold d-flex align-items-center justify-content-center gap-2" @click="abrirCamera">
         Tirar foto agora
      </button>
      
      <button class="btn btn-outline-secondary py-3 fw-bold d-flex align-items-center justify-content-center gap-2" @click="abrirGaleria">
         Escolher foto da galeria
      </button>
    </div>

    <button 
      class="btn btn-success btn-lg w-100 py-3 rounded-3 shadow-sm d-flex align-items-center justify-content-center gap-2"
      @click="enviar"
    >
      Enviar Ocorrência <span class="fs-5">➔</span>
    </button>
  </div>
</template>

<script setup>
import { ref } from 'vue'

const emit = defineEmits(['proximo'])

const inputCamera = ref(null)
const inputGaleria = ref(null)
const inputVideoCamera = ref(null)
const inputVideoGaleria = ref(null)
const mediaArquivo = ref(null)
const mediaPreview = ref(null)
const mediaType = ref(null)

const abrirCamera = () => inputCamera.value.click()
const abrirGaleria = () => inputGaleria.value.click()

const manipularArquivo = (event) => {
  const arquivo = event.target.files[0]
  if (arquivo) {
    // Validate video duration (max 30 seconds)
    if (arquivo.type.startsWith('video/')) {
      const video = document.createElement('video')
      video.preload = 'metadata'
      video.onloadedmetadata = () => {
        window.URL.revokeObjectURL(video.src)
        if (video.duration > 30) {
          alert('O vídeo deve ter no máximo 30 segundos.')
          return
        }
        mediaArquivo.value = arquivo
        mediaType.value = 'video'
        mediaPreview.value = URL.createObjectURL(arquivo)
      }
      video.src = URL.createObjectURL(arquivo)
    } else {
      mediaArquivo.value = arquivo
      mediaType.value = 'image'
      mediaPreview.value = URL.createObjectURL(arquivo)
    }
  }
}

const removerMedia = () => {
  mediaArquivo.value = null
  mediaPreview.value = null
  mediaType.value = null
}

const enviar = () => {
  // Media is now optional - can send without photo/video
  emit('proximo', { 
    media: mediaArquivo.value,
    mediaType: mediaType.value
  })
}
</script>

<style scoped>
/* Classe específica para garantir o comportamento de "cobrir" a div com a imagem no Bootstrap */
.object-fit-cover {
  object-fit: cover;
}

/* Estilo para borda tracejada que não existe por padrão no Bootstrap */
.border-dashed {
  border-style: dashed !important;
}

/* Efeito de hover nos botões */
.btn {
  transition: transform 0.1s ease;
}
.btn:active {
  transform: scale(0.98);
}
</style>