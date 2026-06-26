<template>
  <div class="quem-somos-page bg-light text-dark text-sans-serif">
    <NavBarPublic />

    <header class="hero-banner position-relative d-flex align-items-center justify-content-start text-start">
      <div class="hero-overlay position-absolute top-0 start-0 w-100 h-100"></div>
      
      <div class="container position-relative z-index-2 text-white px-4 px-md-5">
        <nav aria-label="breadcrumb" class="mb-3">
          <ol class="breadcrumb tiny-text tracking-widest text-uppercase m-0">
            <li class="breadcrumb-item">
              <RouterLink class="text-white-50 text-decoration-none" to="/">Início</RouterLink>
            </li>
            <li class="breadcrumb-item active text-white fw-bold" aria-current="page">Sobre Nós</li>
          </ol>
        </nav>
        
        <span class="text-uppercase tracking-widest fw-bold text-success-light mb-1 d-inline-block small">Nossa História</span>
        <h1 class="display-3 fw-extrabold m-0 text-uppercase tracking-tight hero-title">Conheça o ConViva</h1>
      </div>
    </header>

    <main class="container my-5 py-4 text-start">
      <section class="row align-items-stretch g-5 mb-5">
        
        <div class="col-lg-6 d-flex flex-column justify-content-between">
          <div class="border bg-white p-2 h-100 shadow-sm rounded-0">
            <figure class="m-0 h-100 overflow-hidden rounded-0">
              <img 
                src="../assets/images/snake.jpg" 
                alt="Fotografia de monitoramento biológico — Serpente" 
                class="w-100 h-100 img-sobre-foco"
              />
            </figure>
          </div>
        </div>

        <div class="col-lg-6 d-flex flex-column justify-content-center">
          <div class="ps-lg-4">
            <span class="text-success text-uppercase fw-bold small tracking-wider mb-2 d-block">Origem do Projeto</span>
            <h2 class="display-5 fw-extrabold text-uppercase tracking-tight text-dark mb-4 lh-tight">
              Ciência Cidadã e Apoio à <span class="fw-light text-lowercase text-capitalize text-success-light">Fauna</span> Local
            </h2>
            
            <p class="fs-5 text-secondary lh-lg mb-4">
              O projeto <strong>ConViva LABEV</strong>, também denominado <em>“Entre Macacos, Sapos, Cobras e Lagartos Salvam-se Todos”</em>, atua como uma extensão essencial vinculada ao <strong>Laboratório de Ecologia e Zoologia dos Vertebrados</strong>, do Instituto de Ciências Biológicas da UFPA.
            </p>
            
            <p class="text-body-secondary lh-lg mb-3">
              Coordenado pela <strong>Professora Maria Cristina dos Santos Costa</strong> (carinhosamente conhecida como <strong>Professora Kita</strong>), o projeto foi idealizado no ano de 2000 para responder a um desafio real de coexistência no campus universitário, localizado no bairro do Guamá, em Belém. Após a ocorrência de dois acidentes ofídicos dentro da universidade, a coordenação estruturou uma rede para mitigar conflitos e garantir a segurança tanto da comunidade acadêmica quanto dos animais silvestres.
            </p>

            <p class="text-body-secondary lh-lg mb-0">
              O campus da UFPA abriga ricas áreas de várzea e fragmentos florestais que fazem limite com importantes ecossistemas locais, como os da UFRA, do Museu Paraense Emílio Goeldi, da Embrapa e do Parque Estadual do Utinga. Essa forte conexão ecológica atrai espécies de jacarés, sucuris, capivaras, preguiças, quatis e macacos. Como define a colaboradora e doutoranda Iara Ramos, o ConViva é inovador e global justamente por trazer as pessoas como protagonistas da conservação e transformar a fauna silvestre em um ponto de união com o ambiente urbano.
            </p>
          </div>
        </div>

      </section>

      <section class="py-5 border-top">
        <div class="row g-4">
          
          <div class="col-md-4">
            <div class="p-4 bg-white border rounded-0 h-100 shadow-sm">
              <div class="text-success fw-bold h3 mb-3">01</div>
              <h3 class="h5 fw-bold text-dark text-uppercase tracking-wide mb-2">Monitoramento Ativo</h3>
              <p class="text-secondary small lh-lg mb-0">
                Coleta sistemática de relatórios de avistamentos feitos por estudantes, professores e moradores do entorno da UFPA para mapear corredores ecológicos urbanos.
              </p>
            </div>
          </div>

          <div class="col-md-4">
            <div class="p-4 bg-white border rounded-0 h-100 shadow-sm">
              <div class="text-success fw-bold h3 mb-3">02</div>
              <h3 class="h5 fw-bold text-dark text-uppercase tracking-wide mb-2">Preservação Segura</h3>
              <p class="text-secondary small lh-lg mb-0">
                Mitigação de acidentes e conflitos entre seres humanos e animais, assegurando o resgate e a destinação científica correta da fauna sem o extermínio de espécimes.
              </p>
            </div>
          </div>

          <div class="col-md-4">
            <div class="p-4 bg-white border rounded-0 h-100 shadow-sm">
              <div class="text-success fw-bold h3 mb-3">03</div>
              <h3 class="h5 fw-bold text-dark text-uppercase tracking-wide mb-2">Educação Ambiental</h3>
              <p class="text-secondary small lh-lg mb-0">
                Disseminação de cartilhas técnicas, relatórios biológicos abertos e palestras públicas para desmistificar o medo e combater preconceitos contra animais silvestres.
              </p>
            </div>
          </div>

        </div>
      </section>
    </main>

    <Footer />
  </div>
</template>

<script setup>
import { RouterLink } from 'vue-router'
import NavBarPublic from '@/components/NavBarPublic.vue'
import Footer from '@/components/Footer.vue'
</script>

<style scoped>
/* Sincronia com o Kit Global de Estilos do Projeto */
.rounded-0 { border-radius: 0px !important; }
.fw-extrabold { font-weight: 800; }
.tracking-widest { letter-spacing: 0.15em; }
.tracking-wider { letter-spacing: 0.08em; }
.tracking-tight { letter-spacing: -0.02em; }
.text-success-light { color: #58d68d; }

.text-sans-serif {
  font-family: 'Inter', system-ui, -apple-system, sans-serif;
  -webkit-font-smoothing: antialiased;
}

.hero-banner {
  min-height: 400px;
  background: url('@/assets/images/banner_macaco.jpg') center/cover no-repeat;
}

.hero-overlay {
  background: linear-gradient(90deg, rgba(15, 23, 42, 0.95) 0%, rgba(15, 23, 42, 0.5) 100%);
  z-index: 1;
}

.z-index-2 {
  z-index: 2;
}

.img-sobre-foco {
  object-fit: cover;
  width: 100%;
  height: 100%;
  min-height: 320px;
}

/* Customização minimalista do Breadcrumb */
.breadcrumb-item + .breadcrumb-item::before {
  content: "→" !important;
  color: rgba(255, 255, 255, 0.4) !important;
  font-size: 0.85rem;
  padding: 0 8px;
}

.tiny-text {
  font-size: 0.75rem;
}

@media (max-width: 767.98px) {
  .hero-banner { min-height: 320px; }
  .display-3 { font-size: 2.25rem; }
}
</style>