<template>
  <div class="Blog-Page">    
    <NavBarPublic />
      
    <section class="hero-banner position-relative d-flex align-items-end">
      <div class="hero-overlay position-absolute top-0 start-0 w-100 h-100"></div>
      
      <div class="container-fluid px-5 pb-5 position-relative z-index-2 text-white">
        <nav aria-label="breadcrumb" class="mb-2">
          <ol class="breadcrumb tiny-text tracking-wider text-uppercase text-white-50 m-0">
            <li class="breadcrumb-item"><a href="#" class="text-white-50 text-decoration-none">Home</a></li>
            <li class="breadcrumb-item active text-white" aria-current="page">Blog</li>
          </ol>
        </nav>
        
        <h1 class="display-4 fw-bold m-0 hero-title">Blog</h1>
      </div>
    </section>

    <!-- Conteúdo da página -->


    <h4 class="text-center fw-bold mt-5 mb-4">Aqui será é um espaço para publicações</h4>

    <Footer />
  </div>
</template>

<script setup>
import { reactive } from 'vue'
import NavBarPublic from '@/components/NavBarPublic.vue'
import Footer from '@/components/Footer.vue'

const form = reactive({
  nome: '',
  email: '',
  mensagem: ''
})

const handleSubmit = () => {
  alert(`Mensagem enviada!\nNome: ${form.nome}\nEmail: ${form.email}`)
  form.nome = ''
  form.email = ''
  form.mensagem = ''
}
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

.contact-form-card {
  max-width: 720px;
  border: 1px solid rgba(34, 91, 56, 0.12);
  background-color: #eef6ec;
  box-shadow: 0 18px 50px rgba(15, 23, 42, 0.06);
}

.contact-form-card h2 {
  color: #214d30;
}

.contact-form-card p {
  color: #4f6b55;
}

.contact-form-card .form-control {
  border-radius: 12px;
  border-color: rgba(34, 91, 56, 0.18);
  background-color: #f8fbf7;
}

.contact-form-card .form-control:focus {
  box-shadow: 0 0 0 0.2rem rgba(34, 91, 56, 0.15);
}

.contact-form-card button {
  border-radius: 12px;
  padding: 0.9rem 1.5rem;
  background-color: #2f7a3d;
  border-color: #2f7a3d;
}

.contact-form-card button:hover {
  background-color: #245f30;
  border-color: #245f30;
}
</style>