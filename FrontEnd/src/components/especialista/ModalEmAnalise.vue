<template>
  <div v-if="denuncia" class="custom-modal-overlay d-flex align-items-center justify-content-center">
    <div class="custom-modal bg-white rounded-4 shadow-lg">
      <div class="modal-header-custom">
        <h4 class="modal-title-custom">
          Registro <span class="text-primary fw-bold">LABEV{{ String(denuncia.id).padStart(2, '0') }}</span> - Análise: {{ denuncia.tipo_animal || 'Sem título' }}
        </h4>
      </div>

      <div class="modal-body-custom">
        <div class="row g-4">
          <div class="col-lg-5">
            <div class="info-grid mb-3">
              <div class="info-item">
                <span class="label">Latitude</span>
                <span class="value">{{ denuncia.latitude || 'Não informada' }}</span>
              </div>
              <div class="info-item">
                <span class="label">Longitude</span>
                <span class="value">{{ denuncia.longitude || 'Não informada' }}</span>
              </div>
              <div class="info-item">
                <span class="label">Local Exato</span>
                <span class="value">{{ denuncia.descricao_local_exato || 'Não informado' }}</span>
              </div>
              <div class="info-item">
                <span class="label">Ponto de Referência</span>
                <span class="value">{{ denuncia.ponto_referencia || 'Não informado' }}</span>
              </div>
              <div class="info-item info-item--full">
                <span class="label">Data/Hora da Denúncia</span>
                <span class="value">{{ formatarDataHora(denuncia.created_at) }}</span>
              </div>
            </div>

            <!-- Map placeholder -->
            <div class="map-placeholder rounded-3 mb-3">
              <div class="text-center text-muted py-4">
                <i class="fas fa-map-marker-alt fa-2x mb-2"></i>
                <p class="small m-0">Mapa da localização</p>
                <p class="small text-muted">{{ denuncia.latitude }}, {{ denuncia.longitude }}</p>
              </div>
            </div>

            <div v-if="denuncia.foto_url || denuncia.video_url" class="media-preview mb-3">
              <img v-if="denuncia.foto_url" :src="denuncia.foto_url" class="img-fluid rounded-3" alt="Foto">
              <video v-if="denuncia.video_url" :src="denuncia.video_url" class="img-fluid rounded-3" controls></video>
            </div>
          </div>

          <div class="col-lg-7">
            <h5 class="fw-bold mb-3">Informações Técnicas do Registro</h5>
            
            <div class="row g-2">
              <div class="col-md-6 mb-2">
                <label class="small fw-semibold text-muted mb-1">Habitat</label>
                <input v-model="formData.habitat" type="text" class="form-control form-control-sm" placeholder="Ex.: Floresta, Urbano...">
              </div>
              <div class="col-md-6 mb-2">
                <label class="small fw-semibold text-muted mb-1">Microhabitat</label>
                <input v-model="formData.microhabitat" type="text" class="form-control form-control-sm" placeholder="Ex.: Árvore, Solo...">
              </div>
              <div class="col-md-6 mb-2">
                <label class="small fw-semibold text-muted mb-1">Classe</label>
                <input v-model="formData.classe" type="text" class="form-control form-control-sm" placeholder="Ex.: Mammalia, Aves...">
              </div>
              <div class="col-md-6 mb-2">
                <label class="small fw-semibold text-muted mb-1">Ordem</label>
                <input v-model="formData.ordem" type="text" class="form-control form-control-sm" placeholder="Ex.: Primates, Carnivora...">
              </div>
              <div class="col-md-6 mb-2">
                <label class="small fw-semibold text-muted mb-1">Família</label>
                <input v-model="formData.familia" type="text" class="form-control form-control-sm" placeholder="Ex.: Cebidae, Felidae...">
              </div>
              <div class="col-md-6 mb-2">
                <label class="small fw-semibold text-muted mb-1">Espécie</label>
                <input v-model="formData.especie" type="text" class="form-control form-control-sm" placeholder="Ex.: Sapajus apella...">
              </div>
              <div class="col-md-6 mb-2">
                <label class="small fw-semibold text-muted mb-1">Quem Coletou</label>
                <input v-model="formData.que_coletou" type="text" class="form-control form-control-sm" placeholder="Nome do coletor">
              </div>
              <div class="col-md-6 mb-2">
                <label class="small fw-semibold text-muted mb-1">Destino</label>
                <input v-model="formData.destino" type="text" class="form-control form-control-sm" placeholder="Ex.: Museu, Laboratório...">
              </div>

              <!-- Novos Campos Solicitados -->
              <div class="col-md-6 mb-2">
                <label class="small fw-semibold text-muted mb-1">Classe Etária</label>
                <input v-model="formData.classe_etaria" type="text" class="form-control form-control-sm" placeholder="Ex.: Filhote, Adulto...">
              </div>
              <div class="col-md-6 mb-2">
                <label class="small fw-semibold text-muted mb-1">Tempo (Clima)</label>
                <input v-model="formData.tempo" type="text" class="form-control form-control-sm" placeholder="Ex.: Ensolarado, Nublado...">
              </div>
              <div class="col-md-12 mb-2">
                <label class="small fw-semibold text-muted mb-1">Campo Encontrado</label>
                <select v-model="formData.campo_encontrado" class="form-select form-select-sm">
                  <option value="" disabled selected>Selecione uma opção</option>
                  <option value="Básico">Básico</option>
                  <option value="Profissional">Profissional</option>
                  <option value="Saúde">Saúde</option>
                  <option value="PCT">PCT</option>
                </select>
              </div>
              <div class="col-md-12 mb-2">
                <label class="small fw-semibold text-muted mb-1">Observações</label>
                <textarea v-model="formData.observacoes" class="form-control form-control-sm" rows="3" placeholder="Observações adicionais..."></textarea>
              </div>
            </div>

            <div class="actions-stack mt-4">
              <button class="btn btn-success w-100 fw-bold shadow-sm" @click="arquivarComDados">Arquivar Registro</button>
              <button class="btn btn-light border w-100" @click="$emit('fechar')">Cancelar</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'

const props = defineProps({
  denuncia: Object
})
const emit = defineEmits(['fechar', 'arquivar'])

const formData = ref({
  habitat: '',
  microhabitat: '',
  classe: '',
  ordem: '',
  familia: '',
  especie: '',
  que_coletou: '',
  destino: '',
  classe_etaria: '',
  tempo: '',
  campo_encontrado: '',
  observacoes: ''
})

const getCurrentUserName = () => {
  try {
    const stored = localStorage.getItem('usuarioLogado')
    if (!stored) return null
    const u = JSON.parse(stored)
    return u.nome || u.email || null
  } catch (e) {
    return null
  }
}

const formatarDataHora = (data) => {
  if (!data) return 'Data não informada'
  return new Date(data).toLocaleString('pt-BR')
}

const arquivarComDados = () => {
  emit('arquivar', {
    denunciaId: props.denuncia.id,
    ...formData.value,
    codigo_registro: `LABEV${String(props.denuncia.id).padStart(2, '0')}`,
    registrado_por: getCurrentUserName() || 'Usuário'
  })
}
</script>

<style scoped>
.custom-modal-overlay {
  position: fixed;
  inset: 0;
  background: rgba(12, 20, 33, 0.58);
  z-index: 1000;
  padding: 1rem;
}

.custom-modal {
  width: min(1200px, 100%);
  max-height: calc(100vh - 2rem);
  overflow: auto;
}

.modal-header-custom {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 0.75rem;
  padding: 1rem 1.25rem;
  border-bottom: 1px solid #e8edf2;
}

.modal-title-custom {
  margin: 0;
  font-size: 1.2rem;
  font-weight: 700;
  color: #175d36;
}

.modal-body-custom {
  padding: 1.25rem;
}

.info-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 0.65rem;
}

.info-item {
  border: 1px solid #e8edf2;
  background: #f9fbfd;
  border-radius: 10px;
  padding: 0.6rem 0.7rem;
  display: flex;
  flex-direction: column;
  gap: 0.15rem;
}

.label {
  font-size: 0.73rem;
  text-transform: uppercase;
  letter-spacing: 0.05em;
  color: #6e7b8d;
  font-weight: 700;
}

.value {
  font-size: 0.96rem;
  color: #253142;
  font-weight: 600;
}

.map-placeholder {
  background: #e9eef4;
  height: 200px;
  display: flex;
  align-items: center;
  justify-content: center;
}

.actions-stack {
  display: flex;
  flex-direction: column;
  gap: 0.6rem;
}

@media (max-width: 768px) {
  .modal-body-custom {
    padding: 1rem;
  }

  .info-grid {
    grid-template-columns: 1fr;
  }
}
</style>
