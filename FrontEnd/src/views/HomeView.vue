<template>
  <div class="home-page bg-light text-dark">
    <NavBarPublic />
    
    <header class="hero-banner position-relative d-flex align-items-center justify-content-start text-start">
      <div class="hero-overlay position-absolute top-0 start-0 w-100 h-100"></div>
      <div class="container position-relative z-index-2 text-white px-4 px-md-5">
        <div class="row align-items-center">
          <div class="col-lg-7">
            <span class="text-uppercase tracking-widest fw-bold text-success-light mb-2 d-inline-block small">Universidade Federal do Pará</span>
            <h1 class="display-3 fw-extrabold m-0 text-uppercase lh-tight mb-3">ConViva <span class="fw-light text-lowercase text-capitalize">Labev</span></h1>
            <p class="fs-4 fw-light lh-lg max-w-600 opacity-90">
              Entre macacos, sapos, cobras e lagartos, <strong>salvam-se todos!</strong> Projeto de coexistência e conservação de animais silvestres no campus da UFPA.
            </p>
          </div>
        </div>
      </div>
    </header>

    <main class="container my-5">
      <section class="row g-4 align-items-stretch text-start mb-5">
        <div class="col-lg-5 d-flex">
          <article class="hero-card shadow-sm border p-4 p-md-5 w-100 d-flex flex-column justify-content-center bg-white rounded-0">
            <h2 class="h1 fw-bold text-dark text-uppercase tracking-tight mb-3">Encontrou um animal no campus?</h2>
            <p class="fs-5 text-secondary mb-4">Ajude a ciência cidadã e a preservação local registrando o avistamento em poucos cliques.</p>
            <div>
              <button @click="irParaDenuncia" class="btn btn-dark btn-lg rounded-0 fw-bold text-uppercase tracking-wider px-5 py-3 w-100 w-sm-auto">
                Registrar Ocorrência
              </button>
            </div>
          </article>
        </div>
        <div class="col-lg-7 d-flex">
          <div class="w-100 border bg-white p-2 rounded-0 shadow-sm">
            <div v-if="carregandoMapa" class="d-flex flex-column align-items-center justify-content-center bg-light w-100 h-100" style="min-height: 400px;">
              <div class="spinner-border text-dark rounded-0" role="status"></div>
              <p class="text-muted small text-uppercase tracking-wider mt-2">Carregando pontos no mapa...</p>
            </div>
            <div v-show="!carregandoMapa" id="mapa-fauna" class="w-100 h-100" style="min-height: 400px;"></div>
          </div>
        </div>
      </section>

      <section class="py-5 border-top text-start bg-white p-4 p-md-5 border mb-5 shadow-sm rounded-0">
        <div class="row align-items-center g-4">
          <div class="col-lg-4 border-end-lg">
            <span class="text-success text-uppercase fw-bold small tracking-wider">Protocolo de Segurança</span>
            <h2 class="fw-extrabold text-uppercase tracking-tight text-dark m-0 mt-1">Como agir ao encontrar um animal silvestre?</h2>
            <p class="text-secondary mt-3 mb-0 small lh-lg">
              O campus Guamá possui fragmentos florestais e áreas de várzea conectados a grandes reservas. O encontro com jacarés, sucuris, preguiças ou macacos é natural e esperado.
            </p>
          </div>
          <div class="col-lg-8">
            <div class="row g-4">
              <div class="col-md-6">
                <div class="d-flex gap-3">
                  <div class="bg-danger text-white fw-bold d-flex align-items-center justify-content-center rounded-0" style="width: 45px; height: 45px; min-width: 45px;">!</div>
                  <div>
                    <h3 class="h6 fw-bold text-dark text-uppercase mb-1">1. Nunca toque no animal</h3>
                    <p class="text-secondary small mb-0">Evite qualquer contato físico. Você não conhece o comportamento da espécie e ela pode reagir de forma defensiva ou agressiva.</p>
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="d-flex gap-3">
                  <div class="bg-dark text-white fw-bold d-flex align-items-center justify-content-center rounded-0" style="width: 45px; height: 45px; min-width: 45px;">📷</div>
                  <div>
                    <h3 class="h6 fw-bold text-dark text-uppercase mb-1">2. Fotografe à distância</h3>
                    <p class="text-secondary small mb-0">Mantenha o recuo de segurança e capture uma imagem ou vídeo nítido. A foto é essencial para a identificação biológica da nossa equipe.</p>
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="d-flex gap-3">
                  <div class="bg-success text-white fw-bold d-flex align-items-center justify-content-center rounded-0" style="width: 45px; height: 45px; min-width: 45px;">✓</div>
                  <div>
                    <h3 class="h6 fw-bold text-dark text-uppercase mb-1">3. Denuncie/Registre aqui</h3>
                    <p class="text-secondary small mb-0">Use o botão "Registrar Ocorrência" para enviar a imagem e a localização exata. Isso gera o ponto no mapa para a ciência acadêmica.</p>
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="d-flex gap-3">
                  <div class="bg-secondary text-white fw-bold d-flex align-items-center justify-content-center rounded-0" style="width: 45px; height: 45px; min-width: 45px;">🎧</div>
                  <div>
                    <h3 class="h6 fw-bold text-dark text-uppercase mb-1">4. Aguarde Orientações</h3>
                    <p class="text-secondary small mb-0">Após o envio, nossa equipe analisa o risco e dá as instruções necessárias de como proceder ou aciona o protocolo de resgate técnico.</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>

      <section class="py-5 border-top text-start">
        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-end mb-4 gap-3">
          <div>
            <span class="text-success text-uppercase fw-bold small tracking-wider">Fique por dentro</span>
            <h2 class="fw-extrabold text-uppercase m-0 tracking-tight text-dark">Últimas Notícias</h2>
          </div>
          <button @click="$router.push('/noticias')" class="btn btn-sm btn-outline-dark rounded-0 text-uppercase fw-bold w-100 w-sm-auto">Ver Blog →</button>
        </div>

        <div v-if="carregandoNoticias" class="text-center py-4">
          <div class="spinner-border text-dark rounded-0" role="status"></div>
        </div>

        <div v-else-if="noticias.length === 0" class="alert alert-light border text-center py-4 rounded-0">
          Nenhuma publicação disponível no momento.
        </div>

        <div v-else id="noticiasCarousel" class="carousel slide" data-bs-ride="carousel">
          <div class="carousel-inner">
            <div v-for="(grupo, idx) in noticiasAgrupadas" :key="idx" class="carousel-item" :class="{ active: idx === 0 }">
              <div class="row g-4">
                <article v-for="post in grupo" :key="post.id" class="col-12 col-sm-6 col-lg-4" @click="$router.push(`/noticias/${post.id}`)" style="cursor: pointer;">
                  <div class="card h-100 border rounded-0 shadow-sm card-noticia-hover bg-white">
                    <img :src="post.imagem_url || 'https://images.unsplash.com/photo-1546182990-dffeafbe841d?auto=format&fit=crop&w=600&q=80'" class="card-img-top rounded-0 border-bottom" style="height: 200px; object-fit: cover;" alt="Banner da notícia" />
                    <div class="card-body p-4 d-flex flex-column justify-content-between">
                      <div>
                        <span class="badge rounded-0 text-uppercase mb-2" :class="post.tipo === 'Noticia' ? 'bg-success' : 'bg-secondary'">{{ post.tipo }}</span>
                        <h3 class="h5 fw-bold text-dark lh-base text-limit-2 mb-2">{{ post.titulo }}</h3>
                        <p class="text-secondary small text-limit-3 mb-0">{{ post.conteudo }}</p>
                      </div>
                    </div>
                  </div>
                </article>
              </div>
            </div>
          </div>
          <div class="d-flex justify-content-end gap-2 mt-3" v-if="noticiasAgrupadas.length > 1">
            <button class="btn btn-dark btn-sm rounded-0" type="button" data-bs-target="#noticiasCarousel" data-bs-slide="prev">❮</button>
            <button class="btn btn-dark btn-sm rounded-0" type="button" data-bs-target="#noticiasCarousel" data-bs-slide="next">❯</button>
          </div>
        </div>
      </section>

      <section class="py-5 border-top">
        <div class="text-center mb-4">
          <span class="text-success text-uppercase fw-bold small tracking-wider">Biodiversidade</span>
          <h2 class="fw-extrabold text-uppercase m-0 tracking-tight text-dark">Animais Catalogados</h2>
        </div>
        
        <template v-if="carregandoAnimaisCatalogados">
          <div class="text-center py-5">
            <div class="spinner-border text-dark rounded-0" role="status"></div>
          </div>
        </template>

        <template v-else-if="erroAnimaisCatalogados">
          <div class="alert alert-warning border-0 shadow-sm rounded-0 text-start" role="alert">
            {{ erroAnimaisCatalogados }}
          </div>
        </template>

        <template v-else-if="animaisExibidos.length === 0">
          <div class="alert alert-light border shadow-sm text-center py-4 rounded-0">
            Nenhuma espécie catalogada no sistema.
          </div>
        </template>

        <template v-else>
          <div id="catalogoAnimaisCarousel" class="carousel slide text-start" data-bs-ride="carousel">
            <div class="carousel-inner">
              <div v-for="(grupo, index) in animaisAgrupados" :key="index" class="carousel-item" :class="{ active: index === 0 }">
                <div class="row g-4 justify-content-center">
                  <article v-for="animal in grupo" :key="animal.id" class="col-12 col-md-6 col-lg-4">
                    <div class="card h-100 border bg-white shadow-sm rounded-0 overflow-hidden">
                      <figure class="m-0 border-bottom" style="height: 220px; width: 100%;">
                        <img :src="animal.imagem" :alt="animal.nome" class="w-100 h-100" style="object-fit: cover;" />
                      </figure>
                      <div class="p-4">
                        <span class="badge rounded-0 bg-success text-uppercase mb-2 small">{{ animal.categoria }}</span>
                        <h3 class="h4 fw-bold mb-1 text-dark text-truncate">{{ animal.nome }}</h3>
                        <p class="text-secondary small fst-italic mb-3 text-truncate">{{ animal.nomeCientifico }}</p>
                        <p class="text-body-secondary small mb-0 text-limit-3">{{ animal.descricao }}</p>
                      </div>
                    </div>
                  </article>
                </div>
              </div>
            </div>
            <div class="d-flex justify-content-center gap-2 mt-4" v-if="animaisAgrupados.length > 1">
              <button class="btn btn-outline-dark btn-sm rounded-0" type="button" data-bs-target="#catalogoAnimaisCarousel" data-bs-slide="prev">Anterior</button>
              <button class="btn btn-outline-dark btn-sm rounded-0" type="button" data-bs-target="#catalogoAnimaisCarousel" data-bs-slide="next">Próximo</button>
            </div>
          </div>
        </template>
      </section>

      <section class="py-5 border-top text-start">
        <div class="row g-5 align-items-start">
          <div class="col-lg-6">
            <span class="text-success text-uppercase fw-bold small tracking-wider">Conheça o Laboratório</span>
            <h2 class="fw-extrabold text-uppercase tracking-tight text-dark mb-4">O Projeto ConViva Labev</h2>
            <p class="fs-5 text-secondary lh-lg mb-4">
              O <strong>ConViva Labev</strong> é um projeto inovador de coexistência e conservação dos animais silvestres que habitam o campus universitário da UFPA (bairro do Guamá, Belém).
            </p>
            <p class="text-secondary lh-lg small mb-3">
              O campus da UFPA é cercado por uma rica biodiversidade, contendo áreas de várzea e fragmentos florestais interligados a ecossistemas da UFRA, Museu Paraense Emílio Goeldi, Embrapa e Parque do Utinga. Essa conectividade propicia o aparecimento frequente de jacarés, sucuris, capivaras, preguiças e macacos.
            </p>
            <p class="text-secondary lh-lg small">
              Como define a colaboradora e doutoranda Iara Ramos, este é um projeto global que cria redes sociais ambientais unindo a fauna silvestre e o homem como protagonistas do ambiente, visando evitar acidentes e mitigar conflitos preservando a integridade das espécies.
            </p>
          </div>
          
          <div class="col-lg-6">
            <div class="p-4 p-md-5 bg-white border rounded-0 shadow-sm">
              <h3 class="h4 fw-bold text-dark text-uppercase tracking-wide mb-4 border-bottom pb-2">Vínculo e Coordenação</h3>
              <div class="d-flex align-items-start gap-3 mb-4">
                <div class="bg-light border text-center fw-bold d-flex align-items-center justify-content-center text-secondary rounded-0" style="width: 60px; height: 60px; min-width: 60px;">LAB</div>
                <div>
                  <h4 class="h6 fw-bold m-0 text-dark text-uppercase">Coordenação Geral</h4>
                  <p class="text-secondary small mb-1">Profª. Drª. Maria Cristina dos Santos Costa</p>
                  <span class="text-muted small d-block">Laboratório de Ecologia e Zoologia dos Vertebrados</span>
                </div>
              </div>
              <div class="d-flex align-items-start gap-3 mb-4">
                <div class="bg-light border text-center fw-bold d-flex align-items-center justify-content-center text-secondary rounded-0" style="width: 60px; height: 60px; min-width: 60px;">ICB</div>
                <div>
                  <h4 class="h6 fw-bold m-0 text-dark text-uppercase">Instituto de Vínculo</h4>
                  <p class="text-secondary small mb-1">Instituto de Ciências Biológicas (ICB)</p>
                  <span class="text-muted small d-block">Universidade Federal do Pará — UFPA</span>
                </div>
              </div>
              <div class="bg-light p-3 border-start border-success border-4 rounded-0">
                <span class="fw-bold text-dark small d-block text-uppercase">Foco Estratégico</span>
                <span class="text-secondary small">Mapeamento de riscos, fomento a iluminação/sinalização e redução do acúmulo de entulhos para mitigação de acidentes ofídicos.</span>
              </div>
            </div>
          </div>
        </div>
      </section>
    </main>

    <Footer />
  </div>
</template>

<script setup lang="ts">
import { computed, nextTick, onMounted, ref, watch } from 'vue'
import { useRouter } from 'vue-router'
import axios from 'axios'
import L from 'leaflet'
import 'leaflet/dist/leaflet.css'
import NavBarPublic from '@/components/NavBarPublic.vue'
import Footer from '@/components/Footer.vue'
import { useBreakpoint } from '@/composables/useBreakpoint'

interface PublicadoMapa {
  id: number
  animal: string
  local: string
  data: string
  coordenadas: {
    lat: number | null
    lng: number | null
  }
}

type AnimalCatalogado = { id: number; nome: string; nomeCientifico: string; categoria: string; descricao: string; imagem: string }

const noticias = ref<any[]>([])
const carregandoNoticias = ref(true)
const carregandoMapa = ref(true)

const isLocal = window.location.hostname === 'localhost' || window.location.hostname === '127.0.0.1'
const router = useRouter()

const API_BASE = isLocal ? 'http://localhost:8000/api' : 'https://conviva-labev.onrender.com/api'
const STORAGE_BASE = isLocal ? 'http://localhost:8000/storage' : 'https://conviva-labev.onrender.com/storage'
const FALLBACK_IMAGE = 'https://cdn-icons-png.flaticon.com/512/616/616408.png'
const MAX_ANIMAIS_CARROSSEL = 9

const carregandoAnimaisCatalogados = ref(true)
const erroAnimaisCatalogados = ref('')
const animaisCatalogados = ref<AnimalCatalogado[]>([])
const publicados = ref<PublicadoMapa[]>([])

const animaisExibidos = computed(() => animaisCatalogados.value.slice(0, MAX_ANIMAIS_CARROSSEL))

const { carouselGroupSize } = useBreakpoint()

const agruparItens = <T,>(itens: T[]) => {
  const grupos: T[][] = []
  const tamanhoGrupo = carouselGroupSize.value
  for (let i = 0; i < itens.length; i += tamanhoGrupo) {
    grupos.push(itens.slice(i, i + tamanhoGrupo))
  }
  return grupos
}

const animaisAgrupados = computed(() => agruparItens(animaisExibidos.value))

const noticiasAgrupadas = computed(() => agruparItens(noticias.value))

let map: L.Map | null = null
const markerGroup = L.layerGroup()
const LAT_UFPA = -1.4748;
const LNG_UFPA = -48.4456;

const irParaDenuncia = () => router.push('/denuncia')
const normalizarTexto = (valor: unknown) => (valor ?? '').toString().trim()

const buscarNoticiasAPI = async () => {
  try {
    carregandoNoticias.value = true
    const response = await axios.get(`${API_BASE}/posts`)
    const dados = Array.isArray(response.data) ? response.data : response.data.data || []
    noticias.value = dados.slice(0, 6)
  } catch (error) {
    console.error('Erro ao listar notícias:', error)
  } finally {
    carregandoNoticias.value = false
  }
}



const carregarAnimaisCatalogados = async () => {
  try {
    carregandoAnimaisCatalogados.value = true
    erroAnimaisCatalogados.value = ''
    const response = await axios.get<any[]>(`${API_BASE}/especies`)

    animaisCatalogados.value = response.data.map((especie) => {
      const nomeFoto = normalizarTexto(especie.foto)
      let urlImagem = FALLBACK_IMAGE
      if (nomeFoto) {
        urlImagem = isLocal ? `${STORAGE_BASE}/${nomeFoto}` : `${STORAGE_BASE}/${nomeFoto.replace(/^public\//, '')}`
      }
      return {
        id: especie.id_especie,
        nome: normalizarTexto(especie.nome_popular) || 'Espécie sem nome popular',
        nomeCientifico: normalizarTexto(especie.nome_cientifico) || 'Nome científico indisponível',
        categoria: normalizarTexto(especie.categoria?.nome_popular) || 'Categoria não informada',
        descricao: normalizarTexto(especie.descricao) || 'Sem descrição cadastrada.',
        imagem: urlImagem
      }
    })
  } catch (error) {
    erroAnimaisCatalogados.value = 'Não foi possível carregar os animais catalogados no momento.'
  } finally {
    carregandoAnimaisCatalogados.value = false
  }
}

const buscarPublicadosDoMapa = async () => {
  try {
    carregandoMapa.value = true
    const response = await axios.get(`${API_BASE}/ocorrencias/publicados`)
    const dados = Array.isArray(response.data) ? response.data : []
    publicados.value = dados.map((item: any) => ({
      id: item.id,
      animal: item.tipo_animal,
      local: item.ponto_referencia,
      data: new Date(item.created_at).toLocaleDateString('pt-BR'),
      coordenadas: {
        lat: item.latitude !== null ? parseFloat(item.latitude) : null,
        lng: item.longitude !== null ? parseFloat(item.longitude) : null
      }
    }))
  } catch (error) {
    console.error('Erro ao buscar ocorrências publicadas:', error)
    publicados.value = []
  } finally {
    carregandoMapa.value = false
  }
}

const renderizarMarcadores = () => {
  if (!map) return
  markerGroup.clearLayers()

  publicados.value.forEach((animal) => {
    if (animal.coordenadas && animal.coordenadas.lat && animal.coordenadas.lng) {
      const marker = L.marker([animal.coordenadas.lat, animal.coordenadas.lng])

      const popupContent = `
        <div style="font-family: sans-serif; padding: 5px;">
          <h6 style="margin: 0 0 5px 0; font-weight: bold; color: #198754;">${animal.animal}</h6>
          <p style="margin: 0 0 5px 0; font-size: 12px;"><strong>Local:</strong> ${animal.local}</p>
          <p style="margin: 0; font-size: 11px; color: #6c757d;">Registrado em: ${animal.data}</p>
        </div>
      `

      marker.bindPopup(popupContent)
      markerGroup.addLayer(marker)
    }
  })
}

const inicializarMapa = () => {
  map = L.map('mapa-fauna').setView([LAT_UFPA, LNG_UFPA], 15)

  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; OpenStreetMap contributors'
  }).addTo(map)

  markerGroup.addTo(map)
  renderizarMarcadores()
}

watch(publicados, () => {
  renderizarMarcadores()
}, { deep: true })

onMounted(async () => {
  await buscarPublicadosDoMapa()
  await nextTick()
  inicializarMapa()
  carregarAnimaisCatalogados()
  buscarNoticiasAPI()
})
</script>

<style scoped>
.rounded-0 { border-radius: 0px !important; }
.tracking-widest { letter-spacing: 0.15em; }
.tracking-wider { letter-spacing: 0.08em; }
.tracking-tight { letter-spacing: -0.02em; }
.fw-extrabold { font-weight: 800; }
.text-success-light { color: #58d68d; }

.hero-banner {
  min-height: 480px;
  background: url('@/assets/images/banner_macaco.jpg') center/cover no-repeat;
}

.hero-overlay {
  background: linear-gradient(90deg, rgba(15, 23, 42, 0.95) 0%, rgba(15, 23, 42, 0.4) 100%);
}

.card-noticia-hover {
  transition: transform 0.2s ease, border-color 0.2s ease;
}

.card-noticia-hover:hover {
  transform: translateY(-3px);
  border-color: #212529 !important;
}

.text-limit-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

.text-limit-3 {
  display: -webkit-box;
  -webkit-line-clamp: 3;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

@media (max-width: 767.98px) {
  .hero-banner {
    min-height: 360px;
  }

  .hero-banner .fs-4 {
    font-size: 1.1rem !important;
  }

  #mapa-fauna {
    min-height: 280px !important;
  }

  .hero-card h2 {
    font-size: 1.5rem;
  }
}

@media (min-width: 992px) {
  .border-end-lg {
    border-right: var(--bs-border-width) var(--bs-border-style) var(--bs-border-color) !important;
  }
}
</style>