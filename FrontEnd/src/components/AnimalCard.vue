<script setup>
defineProps(['nome', 'especie', 'local', 'data', 'imagem']);
</script>

<template>
  <div class="col-12 col-md-6 col-lg-4">
    <div class="card animal-card border-0 shadow-sm h-100">
      <img :src="imagem" class="card-img-top" :alt="nome">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-center">
          <h5 class="fw-bold mb-0">{{ nome }}</h5>
          <span class="badge bg-success">{{ especie }}</span>
        </div>
        <p class="text-muted mt-2 mb-1">{{ local }}</p>
        <small class="text-secondary">{{ data }}</small>
      </div>
    </div>
  </div>
</template>

<style scoped>
.animal-card { border-radius: 18px; overflow: hidden; transition: 0.3s; }
.animal-card:hover { transform: translateY(-5px); }
.card-img-top { height: 200px; object-fit: cover; }
</style>