<template>
  <div class="navibar">
    <div class="top-bar py-2 px-3 px-md-4 d-flex justify-content-between align-items-center text-white small">
      <div class="d-flex align-items-center top-bar-email">
        <i class="bi bi-envelope-fill me-2"></i>
        <span class="d-none d-sm-inline">convivalabev@gmail.com</span>
        <span class="d-inline d-sm-none">Contato</span>
      </div>
      <div class="social-icons d-flex gap-3">
        <a href="https://www.instagram.com/conviva.labev/" target="_blank" class="text-white" aria-label="Instagram">
          <i class="bi bi-instagram"></i>
        </a>
        <a href="mailto:convivalabev@gmail.com" class="text-white" aria-label="E-mail">
          <i class="bi bi-envelope"></i>
        </a>
      </div>
    </div>

    <header class="navbar navbar-expand-lg navbar-light bg-white py-2 py-md-3 px-3 px-md-4 shadow-sm">
      <div class="container-fluid p-0">

        <RouterLink class="navbar-brand d-flex align-items-center p-0 me-2" to="/">
          <img
            src="../assets/images/logo_sf.png"
            class="navbar-logo"
            alt="ConViva"
          >
        </RouterLink>

        <button
          class="navbar-toggler border-0 ms-auto"
          type="button"
          data-bs-toggle="collapse"
          data-bs-target="#navbarNav"
          aria-controls="navbarNav"
          aria-expanded="false"
          aria-label="Abrir menu"
        >
          <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
          <ul class="navbar-nav mx-lg-auto gap-lg-3 fw-semibold mt-3 mt-lg-0">
            <li class="nav-item">
              <RouterLink class="nav-link text-dark-custom py-2" to="/">Home</RouterLink>
            </li>
            <li class="nav-item">
              <RouterLink class="nav-link text-dark-custom py-2" to="/sobre">Quem Somos</RouterLink>
            </li>
            <li class="nav-item">
              <RouterLink class="nav-link text-dark-custom py-2" to="/blog">Blog</RouterLink>
            </li>
            <li class="nav-item">
              <RouterLink class="nav-link text-dark-custom py-2" to="/contato">Contato</RouterLink>
            </li>
          </ul>

          <div class="navbar-actions d-flex flex-column flex-lg-row align-items-stretch align-items-lg-center gap-3 mt-3 mt-lg-0 pt-3 pt-lg-0 border-top border-lg-0">
            <a
              href="https://wa.me/5591998387277"
              target="_blank"
              class="nav-link d-flex align-items-center text-decoration-none px-0"
            >
              <i class="bi bi-whatsapp text-success fs-4 me-2"></i>
              <div class="d-flex flex-column lh-sm">
                <span class="fw-bold text-dark" style="font-size: 0.85rem;">Fale Conosco</span>
                <span class="text-muted" style="font-size: 0.8rem;">(91) 99153-0025</span>
              </div>
            </a>

            <button @click="irParaEspecialista" class="btn nav-btn text-white fw-semibold">
              {{ isAutenticado ? 'Minha Área' : 'Área do Especialista' }}
            </button>

            <img
              src="../assets/images/UFPA_logo.png"
              class="ufpa-logo d-none d-lg-block"
              alt="UFPA"
            >
          </div>
        </div>

      </div>
    </header>
  </div>
</template>

<script setup>
import { useRouter } from 'vue-router'
import { useAuth } from '@/composables/useAuth'

const router = useRouter()
const { isAutenticado } = useAuth()

const irParaEspecialista = () => {
  if (isAutenticado.value) router.push('/especialista')
  else router.push('/login')
}
</script>

<style scoped>
.top-bar {
  background-color: #1f6b6d;
}

.text-dark-custom {
  color: #333333;
}

.navbar-logo {
  height: 56px;
  width: auto;
}

.ufpa-logo {
  height: 52px;
  width: auto;
}

.nav-btn {
  background-color: #1f6b6d;
  border: none;
  border-radius: 8px;
  padding: 0.5rem 1.25rem;
  white-space: nowrap;
}

.nav-btn:hover {
  background-color: #185456;
  color: white;
}

.nav-link.router-link-active {
  color: #1f6b6d !important;
  font-weight: 700;
}

@media (min-width: 992px) {
  .navbar-logo {
    height: 75px;
  }

  .ufpa-logo {
    height: 60px;
  }
}

@media (max-width: 991.98px) {
  .navbar-actions {
    padding-bottom: 0.5rem;
  }

  .nav-btn {
    width: 100%;
    text-align: center;
  }
}
</style>
