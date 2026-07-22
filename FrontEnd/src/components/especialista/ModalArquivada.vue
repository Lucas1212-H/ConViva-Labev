<template>
  <div v-if="denuncia" class="custom-modal-overlay d-flex align-items-center justify-content-center">
    <div class="custom-modal bg-white rounded-4 shadow-lg">
      <div class="modal-header-custom">
        <h4 class="modal-title-custom">
          Registro <span class="text-primary fw-bold">{{ denuncia.codigo_registro || `LABEV${String(denuncia.id).padStart(2, '0')}` }}</span> - Arquivada: {{ denuncia.tipo_animal || 'Sem título' }}
        </h4>
      </div>

      <div class="modal-body-custom">
        <div class="row g-4">
          <div class="col-lg-5">
            <div v-if="denuncia.foto_url || denuncia.video_url" class="media-preview mb-3">
              <img v-if="denuncia.foto_url" :src="denuncia.foto_url" class="img-fluid rounded-3" alt="Foto">
              <video v-if="denuncia.video_url" :src="denuncia.video_url" class="img-fluid rounded-3" controls></video>
            </div>

            <div class="info-grid mb-3">
              <div class="info-item">
                <span class="label">Denunciante</span>
                <span class="value">{{ denuncia.denunciante_nome || 'Anônimo' }}</span>
              </div>
              <div class="info-item">
                <span class="label">Data/Hora</span>
                <span class="value">{{ formatarDataHora(denuncia.created_at) }}</span>
              </div>
              <div class="info-item">
                <span class="label">Tipo Animal</span>
                <span class="value">{{ denuncia.tipo_animal || 'Não informado' }}</span>
              </div>
              <div class="info-item">
                <span class="label">Situação Animal</span>
                <span class="value">{{ denuncia.situacao_animal || 'Não informado' }}</span>
              </div>
              <div class="info-item">
                <span class="label">Status</span>
                <span class="status-badge" :class="getStatusClass(denuncia.status)">{{ denuncia.status }}</span>
              </div>
            </div>

            <div class="info-item info-item--full mb-3">
              <span class="label">Descrição da Denúncia</span>
              <span class="value muted">{{ denuncia.descricao_ocorrencia || denuncia.descricao || 'Sem descrição' }}</span>
            </div>
          </div>

          <div class="col-lg-7">
            <h5 class="fw-bold mb-3">Localização</h5>
            
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
            </div>

            <h5 class="fw-bold mb-3 mt-4">Informações Técnicas do Registro</h5>
            
            <div class="info-grid mb-3">
              <div class="info-item">
                <span class="label">Habitat</span>
                <span class="value">{{ denuncia.habitat || 'Não informado' }}</span>
              </div>
              <div class="info-item">
                <span class="label">Microhabitat</span>
                <span class="value">{{ denuncia.microhabitat || 'Não informado' }}</span>
              </div>
              <div class="info-item">
                <span class="label">Classe</span>
                <span class="value">{{ denuncia.classe || 'Não informado' }}</span>
              </div>
              <div class="info-item">
                <span class="label">Ordem</span>
                <span class="value">{{ denuncia.ordem || 'Não informado' }}</span>
              </div>
              <div class="info-item">
                <span class="label">Família</span>
                <span class="value">{{ denuncia.familia || 'Não informado' }}</span>
              </div>
              <div class="info-item">
                <span class="label">Espécie</span>
                <span class="value">{{ denuncia.especie || 'Não informado' }}</span>
              </div>
              <div class="info-item">
                <span class="label">Quem Coletou</span>
                <span class="value">{{ denuncia.que_coletou || 'Não informado' }}</span>
              </div>
              <div class="info-item">
                <span class="label">Destino</span>
                <span class="value">{{ denuncia.destino || 'Não informado' }}</span>
              </div>
              <!-- Novos campos -->
              <div class="info-item">
                <span class="label">Classe Etária</span>
                <span class="value">{{ denuncia.classe_etaria || 'Não informado' }}</span>
              </div>
              <div class="info-item">
                <span class="label">Tempo (Clima)</span>
                <span class="value">{{ denuncia.tempo || 'Não informado' }}</span>
              </div>
              <div class="info-item info-item--full">
                <span class="label">Campo Encontrado</span>
                <span class="value">{{ denuncia.campo_encontrado || 'Não informado' }}</span>
              </div>
            </div>

            <div class="info-item info-item--full mb-3">
              <span class="label">Observações</span>
              <span class="value muted">{{ denuncia.observacoes || 'Nenhuma observação registrada.' }}</span>
            </div>

            <div class="info-item info-item--full mb-3">
              <span class="label">Registrado Por</span>
              <span class="value fw-bold text-success">{{ denuncia.registrado_por || 'Não informado' }}</span>
            </div>

            <div class="actions-stack mt-4">
              <button class="btn btn-light border w-100 fw-bold shadow-sm" @click="$emit('fechar')">Fechar</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
const props = defineProps({
  denuncia: Object
})
const emit = defineEmits(['fechar'])

const formatarDataHora = (data) => {
  if (!data) return 'Data não informada'
  return new Date(data).toLocaleString('pt-BR')
}

const getStatusClass = (status) => {
  if (status === 'Resolvido') return 'status-success'
  if (status === 'Falso Alarme') return 'status-warning'
  if (status === 'Em Atendimento') return 'status-info'
  return 'status-default'
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

.info-item--full {
  grid-column: 1 / -1;
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

.muted {
  color: #5d6a7a;
  font-weight: 500;
}

.status-badge {
  display: inline-flex;
  align-self: flex-start;
  padding: 0.2rem 0.6rem;
  border-radius: 999px;
  font-size: 0.8rem;
  font-weight: 700;
}

.status-success {
  background: #d4edda;
  color: #155724;
}

.status-warning {
  background: #fff3cd;
  color: #856404;
}

.status-info {
  background: #d1ecf1;
  color: #0c5460;
}

.status-default {
  background: #e9eef4;
  color: #3f5065;
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
