<template>
  <div class="home-page">
    <NavBarPublic />

    <header class="hero-section d-flex align-items-center">
      <div class="overlay"></div>
      <div class="container position-relative text-white">
        <div class="row align-items-center">
          <div class="col-lg-6 px-10">
            <div class="hero-card shadow-lg">
              <h1 class="display-10 fw-bold">Encontrou um animal silvestre na região?</h1>
              <p class="lead">Ajude a ciência e a preservação local com apenas alguns cliques.</p>
              <button @click="irParaDenuncia" class="btn btn-warning btn-lg fw-bold mt-3 px-5 py-3">Denuncie Aqui</button>
            </div>
          </div>
          <div class="col-lg-6">
            <div id="mapa-fauna" class="rounded-4 shadow-sm mb-4" style="height: 400px; width: 100%;"></div>
          </div>
        </div>
      </div>
    </header>

    <section class="py-5 bg-light">
      <div class="container">
        <h2 class="text-center fw-bold mb-5 section-title">Animais Catalogados</h2>
        <div class="row g-4">
          <AnimalCard 
            v-for="animal in animais" 
            :key="animal.id"
            v-bind="animal"
          />
        </div>
      </div>
    </section>

    <Footer />
  </div>
</template>

<script setup>
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import NavBarPublic from '@/components/NavBarPublic.vue';
import Footer from '@/components/Footer.vue';
import AnimalCard from '@/components/AnimalCard.vue';

const router = useRouter();

const irParaDenuncia = () => {
  router.push('/denuncia');
};


// Dados que virão futuramente da sua API/Firebase
const animais = [
  { id: 1, nome: 'Lobo-guará', especie: 'Mamífero', local: 'Área rural', data: '12/05/2024', imagem: 'https://picsum.photos/500/300?1' },
  { id: 2, nome: 'Tucano', especie: 'Ave', local: 'Região central', data: '10/05/2024', imagem: 'https://picsum.photos/500/300?2' },
  { id: 3, nome: 'Jaguatirica', especie: 'Mamífero', local: 'Reserva ambiental', data: '09/05/2024', imagem: 'https://picsum.photos/500/300?3' }
];

import { onMounted, watch } from 'vue';
import L from 'leaflet';
import 'leaflet/dist/leaflet.css';

const props = defineProps({
  publicados: { type: Array, required: true, default: () => [] }
});

let map = null;
const markerGroup = L.layerGroup();

// Configuração das coordenadas centrais da UFPA - Campus Guamá
const LAT_UFPA = -1.4748;
const LNG_UFPA = -48.4456;

const inicializarMapa = () => {
  // Inicializa o mapa focado na UFPA
  map = L.map('mapa-fauna').setView([LAT_UFPA, LNG_UFPA], 15);

  // Adiciona a camada visual gratuita do OpenStreetMap
  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; OpenStreetMap contributors'
  }).addTo(map);

  markerGroup.addTo(map);
  renderizarMarcadores();
};

const renderizarMarcadores = () => {
  if (!map) return;
  markerGroup.clearLayers(); // Limpa os pins antigos antes de redesenhar

  props.publicados.forEach(animal => {
    if (animal.coordenadas && animal.coordenadas.lat && animal.coordenadas.lng) {
      
      // Cria o Pin no mapa
      const marker = L.marker([animal.coordenadas.lat, animal.coordenadas.lng]);
      
      // Cria a janelinha (Popup) que abre ao clicar no Pin
      const popupContent = `
        <div style="font-family: sans-serif; padding: 5px;">
          <h6 style="margin: 0 0 5px 0; font-weight: bold; color: #198754;">${animal.animal}</h6>
          <p style="margin: 0 0 5px 0; font-size: 12px;"><strong>Local:</strong> ${animal.local}</p>
          <p style="margin: 0; font-size: 11px; color: #6c757d;">Registrado em: ${animal.data}</p>
        </div>
      `;
      
      marker.bindPopup(popupContent);
      markerGroup.addLayer(marker);
    }
  });
};

// Permite que ao clicar na linha da tabela, o mapa dê um "zoom" suave direto no animal selecionado
const focarNoAnimal = (animal) => {
  if (map && animal.coordenadas) {
    map.setView([animal.coordenadas.lat, animal.coordenadas.lng], 18);
  }
};

// Monitora se a lista mudou (caso chegue dado novo da API) para atualizar os pins automaticamente
watch(() => props.publicados, () => {
  renderizarMarcadores();
}, { deep: true });

onMounted(() => {
  inicializarMapa();
});
</script>

<style scoped>
.hero-section {
  min-height: 60vh;
  background: url('@/assets/images/faunaaqui.png') center/cover no-repeat;
  position: relative;
}
.overlay { position: absolute; inset: 0; background: rgba(0,0,0,0.4); }
.hero-card { background: rgba(0, 0, 0, 0.5); padding: 3rem; border-radius: 25px; backdrop-filter: blur(8px); }
.blur-btn { backdrop-filter: blur(5px); border-radius: 15px; }
.section-title { color: #14532d; font-size: 2.5rem; }
</style>