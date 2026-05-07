<template>
  <div class="home-page">

    <nav class="navbar navbar-expand-lg custom-navbar px-4 py-3">
      <div class="container-fluid">

        <a class="navbar-brand logo" href="#">
          FaunaAqui!
        </a>

        <button
          class="navbar-toggler bg-light"
          type="button"
          data-bs-toggle="collapse"
          data-bs-target="#navbarNav"
        >
          <span class="navbar-toggler-icon"></span>
        </button>

        <div
          class="collapse navbar-collapse justify-content-end"
          id="navbarNav"
        >
          <div class="d-flex gap-3 mt-3 mt-lg-0">

            <RouterLink class="btn nav-btn" to="/area-especialista">
              Área do Especialista
            </RouterLink>

            <button class="btn nav-btn">
              Sobre
            </button>

            <button class="btn nav-btn">
              Contato
            </button>

          </div>
        </div>

      </div>
    </nav>

    <section class="hero-section">

      <div class="overlay"></div>

      <div class="container-fluid h-100 position-relative">

        <div class="row h-100 align-items-center">

          <!-- LEFT -->
          <div class="col-lg-5">

            <div class="hero-card">

              <h1 class="hero-title">
                Encontrou um animal silvestre na região?
              </h1>

              <button class="btn denuncie-btn mt-4">
                Denuncie Aqui
              </button>

            </div>

          </div>

          <!-- RIGHT -->
          <div class="col-lg-7 d-flex justify-content-center">

            <button class="btn mapa-btn">
              Visualizar mapa
            </button>

          </div>

        </div>

      </div>

    </section>

    <!-- ANIMAIS -->
    <section class="py-5 bg-light">

      <div class="container-fluid px-4">

        <h2 class="section-title">
          Animais Catalogados
        </h2>

        <div class="row g-4 mt-4">

          <div class="col-12 col-md-6 col-lg-4">

            <div class="card animal-card border-0 shadow-sm h-100">

              <img
                src="https://picsum.photos/500/300?1"
                class="card-img-top"
              >

              <div class="card-body">

                <div class="d-flex justify-content-between align-items-center">

                  <h5 class="fw-bold">
                    Lobo-guará
                  </h5>

                  <span class="badge bg-success">
                    Mamífero
                  </span>

                </div>

                <p class="text-muted mt-2">
                  Área rural próxima ao rio
                </p>

                <small class="text-secondary">
                  12/05/2024
                </small>

              </div>

            </div>

          </div>

          <div class="col-12 col-md-6 col-lg-4">

            <div class="card animal-card border-0 shadow-sm h-100">

              <img
                src="https://picsum.photos/500/300?2"
                class="card-img-top"
              >

              <div class="card-body">

                <div class="d-flex justify-content-between align-items-center">

                  <h5 class="fw-bold">
                    Tucano
                  </h5>

                  <span class="badge bg-success">
                    Ave
                  </span>

                </div>

                <p class="text-muted mt-2">
                  Região central da cidade
                </p>

                <small class="text-secondary">
                  10/05/2024
                </small>

              </div>

            </div>

          </div>

          <div class="col-12 col-md-6 col-lg-4">

            <div class="card animal-card border-0 shadow-sm h-100">

              <img
                src="https://picsum.photos/500/300?3"
                class="card-img-top"
              >

              <div class="card-body">

                <div class="d-flex justify-content-between align-items-center">

                  <h5 class="fw-bold">
                    Tucano-de-bico-verde
                  </h5>

                  <span class="badge bg-success">
                    Ave
                  </span>

                </div>

                <p class="text-muted mt-2">
                  Próximo à reserva ambiental
                </p>

                <small class="text-secondary">
                  09/05/2024
                </small>

              </div>

            </div>

          </div>

        </div>

      </div>

    </section>

    <!-- INFO CARDS -->
    <section class="py-5">

      <div class="container-fluid px-4">

        <div class="row g-4">

          <div class="col-lg-6">

            <div class="info-card green-card">

              <h3>
                Ajude a preservar nossa fauna
              </h3>

              <p>
                Sua denúncia ajuda a proteger os animais silvestres e o meio ambiente.
              </p>

              <button class="btn btn-success">
                Saiba mais
              </button>

            </div>

          </div>

          <div class="col-lg-6">

            <div class="info-card orange-card">

              <h3>
                É rápido e seguro
              </h3>

              <p>
                Informações analisadas por especialistas com total confidencialidade.
              </p>

              <button class="btn btn-warning text-white">
                Como denunciar
              </button>

            </div>

          </div>

        </div>

      </div>

    </section>

    <!-- COMO FUNCIONA -->
    <section class="py-5 bg-light">

      <div class="container">

        <h2 class="section-title">
          Como funciona?
        </h2>

        <div class="row text-center mt-5 g-4">

          <div class="col-md-3">

            <div class="step-card">

              <div class="step-icon">
                📝
              </div>

              <h5>
                Faça seu registro
              </h5>

              <p>
                Informe onde encontrou o animal.
              </p>

            </div>

          </div>

          <div class="col-md-3">

            <div class="step-card">

              <div class="step-icon">
                📷
              </div>

              <h5>
                Envie fotos
              </h5>

              <p>
                Ajude na identificação do animal.
              </p>

            </div>

          </div>

          <div class="col-md-3">

            <div class="step-card">

              <div class="step-icon">
                👨‍🔬
              </div>

              <h5>
                Especialistas analisam
              </h5>

              <p>
                Verificação das informações.
              </p>

            </div>

          </div>

          <div class="col-md-3">

            <div class="step-card">

              <div class="step-icon">
                📍
              </div>

              <h5>
                Aparece no mapa
              </h5>

              <p>
                Registro publicado no sistema.
              </p>

            </div>

          </div>

        </div>

      </div>

    </section>

    <!-- FOOTER -->
    <footer class="footer-section py-5">

      <div class="container-fluid px-5">

        <div class="row g-5">

          <div class="col-lg-4">

            <h2 class="logo">
              FaunaAqui!
            </h2>

            <p class="mt-3">
              Plataforma colaborativa para monitoramento da fauna silvestre.
            </p>

          </div>

          <div class="col-lg-2">

            <h5>
              Navegação
            </h5>

            <ul class="footer-links">

              <li>Início</li>
              <li>Mapa</li>
              <li>Sobre</li>

            </ul>

          </div>

          <div class="col-lg-3">

            <h5>
              Suporte
            </h5>

            <ul class="footer-links">

              <li>Como denunciar</li>
              <li>Perguntas frequentes</li>
              <li>Política de privacidade</li>

            </ul>

          </div>

          <div class="col-lg-3">

            <h5>
              Contato
            </h5>

            <ul class="footer-links">

              <li>contato@faunaaqui.org</li>
              <li>(00) 1234-5678</li>
              <li>Brasil</li>

            </ul>

          </div>

        </div>

      </div>

    </footer>

  </div>
</template>

<script setup>
</script>

<style scoped>

.home-page {
  width: 100%;
  min-height: 100vh;
  background: #f5f5f5;
}

/* NAVBAR */
.custom-navbar {
  background: linear-gradient(90deg, #2ecc71, #00b894);
}

.logo {
  color: white;
  font-weight: 800;
  font-size: 2rem;
  text-decoration: none;
}

.nav-btn {
  background: rgba(0,0,0,0.3);
  color: white;
  border-radius: 10px;
  padding: 10px 20px;
}

.nav-btn:hover {
  background: rgba(0,0,0,0.5);
  color: white;
}

/* HERO */
.hero-section {
  min-height: 500px;
  position: relative;
  background-image: url('../assets/images/map.png');
  background-size: cover;
  background-position: center;
}

.overlay {
  position: absolute;
  inset: 0;
  background: rgba(0,0,0,0.35);
}

.hero-card {
  background: rgba(0,0,0,0.45);
  color: white;
  padding: 50px;
  border-radius: 20px;
  backdrop-filter: blur(5px);
}

.hero-title {
  font-size: 3rem;
  font-weight: 800;
}

.denuncie-btn {
  background: #ff8c42;
  color: white;
  border-radius: 12px;
  padding: 12px 30px;
}

.mapa-btn {
  background: rgba(0,0,0,0.5);
  color: white;
  border-radius: 12px;
  padding: 15px 35px;
}

/* TITLES */
.section-title {
  text-align: center;
  font-weight: 800;
  font-size: 2.5rem;
  color: #14532d;
}

/* CARDS */
.animal-card {
  border-radius: 18px;
  overflow: hidden;
  transition: 0.2s;
}

.animal-card:hover {
  transform: translateY(-5px);
}

/* INFO */
.info-card {
  border-radius: 20px;
  padding: 40px;
  height: 100%;
}

.green-card {
  background: #eafaf1;
}

.orange-card {
  background: #fff4ea;
}

/* STEPS */
.step-card {
  background: white;
  padding: 30px;
  border-radius: 20px;
  height: 100%;
}

.step-icon {
  font-size: 3rem;
  margin-bottom: 20px;
}

/* FOOTER */
.footer-section {
  background: linear-gradient(90deg, #00b894, #009966);
  color: white;
}

.footer-links {
  list-style: none;
  padding: 0;
}

.footer-links li {
  margin-top: 10px;
}

@media (max-width: 768px) {

  .hero-title {
    font-size: 2rem;
  }

  .hero-card {
    padding: 30px;
  }

}

</style>