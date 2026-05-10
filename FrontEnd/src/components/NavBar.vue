<template>
  <nav class="navbar-especialista d-flex align-items-center justify-content-between px-4">
    <div class="navbar-brand-container">
      <h1 class="m-0 brand-text">Fauna<span>aqui!</span></h1>
    </div>

    <div class="nav-pill-container">
      <div class="nav-pill-bg d-flex align-items-center justify-content-around">
        <RouterLink to="/especialista" class="nav-item-link active">Início</RouterLink>
        <a href="#" class="nav-item-link">Arquivados</a>
        <a href="#" class="nav-item-link">Publicados</a>
        <a href="#" class="nav-item-link">Sobre nós</a>
      </div>
    </div>

    <div class="user-section">
      <div class="avatar-circle shadow-sm">
        <span>A</span>
      </div>
    </div>
  </nav>
</template>

<style scoped>
.navbar-especialista {
  background-color: #58d68d; /* Verde principal do seu mockup */
  height: 80px;
  width: 100%;
}

.brand-text {
  color: white;
  font-weight: 800;
  font-size: 2.2rem;
  letter-spacing: -1px;
}

.brand-text span {
  font-weight: 400;
}

/* Container da Pílula Central */
.nav-pill-container {
  flex-grow: 1;
  display: flex;
  justify-content: center;
}

.nav-pill-bg {
  background-color: white;
  border-radius: 50px;
  padding: 8px 15px;
  min-width: 450px;
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
}

.nav-item-link {
  text-decoration: none;
  color: #9cdb81; /* Verde claro dos links */
  font-weight: 600;
  padding: 8px 20px;
  border-radius: 30px;
  transition: all 0.3s ease;
  font-size: 0.95rem;
}

.nav-item-link:hover {
  color: #45a049;
}

.nav-item-link.active {
  background-color: #9cdb81;
  color: white !important;
}

/* Avatar Circular */
.avatar-circle {
  width: 50px;
  height: 50px;
  background-color: white;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: bold;
  color: #333;
  font-size: 1.2rem;
}

/* Responsividade básica */
@media (max-width: 992px) {
  .nav-pill-bg {
    min-width: auto;
    padding: 5px;
  }
  .nav-item-link {
    padding: 5px 10px;
    font-size: 0.8rem;
  }
}
</style>