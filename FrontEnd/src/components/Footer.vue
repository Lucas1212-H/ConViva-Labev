<template>
  <footer class="footer-professional pt-5 pb-3 mt-5">
    <div class="container">
      <div class="row g-4">
        <div class="col-lg-4">
          <h2 class="logo-text">Projeto ConViva</h2>
          <p class="mt-3 text-light-opacity">
            Plataforma dedicada ao monitoramento participativo e preservação da fauna silvestre da Universidade Federal do Pará. 
          </p>
          <div class="social-links mt-4">
            <a href="#" class="me-3"><i class="fab fa-instagram"></i></a>
            <a href="#" class="me-3"><i class="fab fa-facebook"></i></a>
            <a href="#"><i class="fab fa-github"></i></a>
          </div>
        </div>

        <div class="col-lg-2 ms-lg-auto">
          <h5 class="fw-bold mb-4">Plataforma</h5>
          <ul class="list-unstyled footer-links">
            <li><RouterLink to="/">Início</RouterLink></li>
            <li><a href="#">Mapa Global</a></li>
            <li><a href="#">Estatísticas</a></li>
            <li><RouterLink to="/login">Área Restrita</RouterLink></li>
          </ul>
        </div>

        <div class="col-lg-3">
          <h5 class="fw-bold mb-4">Recursos</h5>
          <ul class="list-unstyled footer-links">
            <li><a href="#">Guia de Identificação</a></li>
            <li><a href="#">Como Denunciar</a></li>
            <li><a href="#">Política de Privacidade</a></li>
            <li><a href="#">FAQ</a></li>
          </ul>
        </div>

        <div class="col-lg-3">
          <h5 class="fw-bold mb-4">Contato</h5>
          <ul class="list-unstyled contact-info">
            <li><i class="fas fa-envelope me-2"></i>convivalabev@gmail.com</li>
            <li><i class="fas fa-phone me-2"></i>  (91) 99153-0025</li>
            <li><i class="fas fa-map-marker-alt me-2"></i> Belém, PA  - Brasil</li>
          </ul>
        </div>
      </div>

      <hr class="mt-5 mb-4 border-light opacity-25">
      
      <div class="text-center text-light-opacity small">
        © 2024 conviva. Projeto do instituto de biologia da UFPA em colaboração intituto de computação.
      </div>
    </div>
  </footer>
</template>

<style scoped>
.footer-professional {
  background: linear-gradient(135deg, #1b5e20 0%, #004d40 100%);
  color: white;
}
.logo-text { font-weight: 800; color: #2ecc71; }
.text-light-opacity { color: rgba(255,255,255,0.7); }
.footer-links li { margin-bottom: 12px; }
.footer-links a { 
  color: rgba(255,255,255,0.7); 
  text-decoration: none; 
  transition: 0.3s; 
}
.footer-links a:hover { color: #2ecc71; padding-left: 5px; }
.social-links a { 
  font-size: 1.5rem; 
  color: white; 
  transition: 0.3s; 
}
.social-links a:hover { color: #2ecc71; }

@media (max-width: 767.98px) {
  .footer-professional {
    text-align: center;
  }

  .footer-professional .col-lg-4,
  .footer-professional .col-lg-2,
  .footer-professional .col-lg-3 {
    text-align: center;
  }

  .social-links {
    justify-content: center;
    display: flex;
  }

  .footer-links,
  .contact-info {
    padding-left: 0;
  }
}
</style>