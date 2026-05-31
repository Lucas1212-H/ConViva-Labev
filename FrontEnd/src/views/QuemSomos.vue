<template>
  <div class="quem-somos-page">
    <NavBarPublic />

    <section class="hero-banner position-relative d-flex align-items-end">
      <div class="hero-overlay position-absolute top-0 start-0 w-100 h-100"></div>
      
      <div class="container-fluid px-5 pb-5 position-relative z-index-2 text-white">
        <nav aria-label="breadcrumb" class="mb-2">
          <ol class="breadcrumb tiny-text tracking-wider text-uppercase text-white-50 m-0">
            <li class="breadcrumb-item">
              <RouterLink class="text-white-50 text-decoration-none" to="/">Home</RouterLink>
            </li>
            <li class="breadcrumb-item active text-white" aria-current="page">Sobre Nós</li>
          </ol>
        </nav>
        
        <h1 class="display-4 fw-bold m-0 hero-title">Sobre Nós</h1>
      </div>
    </section>

    <!-- Conteúdo da página -->
    <section class="container my-5 py-4">
      <div class="row align-items-center g-5">
        
        <div class="col-md-6 col-lg-7">
          <div class="position-relative">
            <img 
              src="../assets/images/snake.jpg" 
              alt="cobra sobre uma folha" 
              class="img-fluid rounded shadow-sm w-100"
            />
          </div>
          
          <div class="mt-4">
            <span class="text-muted fw-bold text-uppercase small d-block mb-1">Quem Somos</span>
            <h2 class="h4 fw-bold text-dark">
              O projeto conviva é uma extensão do laboratório de biologia da UFPA.
            </h2>
          </div>
        </div>

        <div class="col-md-6 col-lg-5">
          <h1 class="display-6 fw-normal text-dark mb-4">
            Conheça o nosso projeto criado para defesa da <strong class="fw-bold">fauna</strong> local e para o apoio à ciência cidadã.
          </h1>
          
          <p class="text-secondary lh-lg" style="font-size: 0.95rem;">
            Organização foi fundada em 2000, pela professora Kita. Após uma aluna ser atacada por uma serpente, a professora decidiu criar um projeto para ajudar a população a lidar com esses animais e também para ajudar a ciência a entender melhor a fauna local.
          </p>
        </div>

      </div>
    </section>

    <Footer />
  </div>
</template>

<script setup>
import NavBarPublic from '@/components/NavBarPublic.vue'
import Footer from '@/components/Footer.vue'
</script>

<style scoped>
/* --- CONFIGURAÇÕES DE PALETA E TIPOGRAFIA --- */
.text-sans-serif {
  font-family: 'Inter', 'Montserrat', sans-serif;
  -webkit-font-smoothing: antialiased;
}

/* Cores Customizadas */
.top-bar {
  background-color: #1f6b6d; /* Verde Petróleo Escuro */
}
.text-petroleo { color: #1f6b6d; }
.text-oliva { color: #556b2f; }      /* Verde Oliva Corporativo */
.text-laranja-oliva { color: #d4a373; } /* Tom terroso secundário */
.text-dark-custom { color: #333333; }

/* Menu Ativo */
.active-green {
  color: #70a1ff !important; /* Ajuste para a cor ativa destacada */
  color: #7ba986 !important; /* Exemplo de verde claro institucional ativo */
}

/* Ajustes de Fontes Finas/Especiais */
.tiny-text {
  font-size: 0.7rem;
  font-weight: 600;
}
.tracking-wide { letter-spacing: 0.1em; }
.tracking-wider { letter-spacing: 0.15em; }
.tracking-widest { letter-spacing: 0.2em; }
.tracking-tight { letter-spacing: -0.05em; }

.phone-number {
  font-size: 0.95rem;
  color: #212529;
}

/* --- BANNER HERO --- */
.hero-banner {
  height: 420px; /* Layout Horizontal Widescreen */
  /* Substitua pelo caminho real da imagem da muda/solo */
  background-image: url('../assets/images/banner_macaco.jpg');
  background-size: cover;
  background-position: center 60%;
  position: relative;
  /* Linha inferior de acabamento ecológico */
  border-bottom: 6px solid #9ef01a; 
}

.hero-overlay {
  background: linear-gradient(180deg, rgba(0,0,0,0.2) 0%, rgba(0,0,0,0.65) 100%);
  z-index: 1;
}

.z-index-2 {
  z-index: 2;
}

.hero-title {
  font-size: 3.5rem;
  letter-spacing: -0.02em;
}

/* Customização leve do Breadcrumb para aceitar setas separadoras brancas */
.breadcrumb-item + .breadcrumb-item::before {
  content: ">" !important;
  color: rgba(255, 255, 255, 0.5) !important;
}

/* Responsividade de Mídia */
@media (max-width: 1200px) {
  .hidden-md-down {
    display: none !important;
  }
}
</style>