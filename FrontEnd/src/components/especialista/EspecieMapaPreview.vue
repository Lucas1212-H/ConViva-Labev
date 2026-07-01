<template>
  <div class="species-map-preview">
    <div v-if="hasPoints" ref="mapElement" class="species-map"></div>
    <div v-else class="species-map-empty">
      <span class="text-muted small">Sem ocorrências vinculadas com coordenadas.</span>
    </div>
  </div>
</template>

<script setup>
import { computed, nextTick, onBeforeUnmount, onMounted, ref, watch } from 'vue'
import L from 'leaflet'

const props = defineProps({
  ocorrencias: {
    type: Array,
    default: () => []
  }
})

const mapElement = ref(null)
let mapInstance = null
const markerLayer = L.layerGroup()

const hasPoints = computed(() => (props.ocorrencias || []).some((ocorrencia) => {
  const latitude = Number(ocorrencia?.latitude)
  const longitude = Number(ocorrencia?.longitude)
  return Number.isFinite(latitude) && Number.isFinite(longitude)
}))

const montarMarcadores = () => {
  if (!mapInstance) return

  markerLayer.clearLayers()

  const pontosValidos = (props.ocorrencias || []).filter((ocorrencia) => {
    const latitude = Number(ocorrencia?.latitude)
    const longitude = Number(ocorrencia?.longitude)
    return Number.isFinite(latitude) && Number.isFinite(longitude)
  })

  if (pontosValidos.length === 0) return

  const marcadores = pontosValidos.map((ocorrencia) => {
    const latitude = Number(ocorrencia.latitude)
    const longitude = Number(ocorrencia.longitude)
    const marcador = L.marker([latitude, longitude])
    marcador.bindPopup(`
      <div style="font-family: sans-serif; min-width: 180px;">
        <strong>${ocorrencia.situacao_animal || 'Ocorrência'}</strong><br/>
        <small>${ocorrencia.ponto_referencia || 'Sem referência'}</small><br/>
        <small>${new Date(ocorrencia.created_at).toLocaleDateString('pt-BR')}</small>
      </div>
    `)
    return marcador
  })

  marcadores.forEach((marcador) => markerLayer.addLayer(marcador))
  markerLayer.addTo(mapInstance)

  const grupo = L.featureGroup(marcadores)
  mapInstance.fitBounds(grupo.getBounds().pad(0.25))
}

const inicializarMapa = async () => {
  if (!mapElement.value || mapInstance) return

  mapInstance = L.map(mapElement.value, {
    zoomControl: false,
    attributionControl: false,
    scrollWheelZoom: false,
    dragging: true,
    doubleClickZoom: false,
    boxZoom: false,
    keyboard: false,
    tap: false
  }).setView([-1.4748, -48.4456], 12)

  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; OpenStreetMap contributors'
  }).addTo(mapInstance)

  montarMarcadores()
  await nextTick()
  mapInstance.invalidateSize()
}

watch(
  () => props.ocorrencias,
  () => {
    montarMarcadores()
    if (mapInstance) {
      nextTick(() => mapInstance?.invalidateSize())
    }
  },
  { deep: true }
)

onMounted(async () => {
  await inicializarMapa()
})

onBeforeUnmount(() => {
  if (mapInstance) {
    mapInstance.remove()
    mapInstance = null
  }
})
</script>

<style scoped>
.species-map-preview {
  width: 100%;
}

.species-map {
  width: 100%;
  height: 180px;
  border-radius: 16px;
  overflow: hidden;
  border: 1px solid rgba(15, 23, 42, 0.08);
}

.species-map-empty {
  min-height: 180px;
  border-radius: 16px;
  border: 1px dashed rgba(100, 116, 139, 0.35);
  background: linear-gradient(180deg, rgba(248, 250, 252, 0.95), rgba(241, 245, 249, 0.95));
  display: flex;
  align-items: center;
  justify-content: center;
  text-align: center;
  padding: 1rem;
}
</style>
