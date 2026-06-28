<template>
  <nav class="navbar-especialista">
    <div class="navbar-top d-flex align-items-center justify-content-between w-100">
      <div class="navbar-brand-container">
        <RouterLink class="brand-link text-decoration-none" :to="{ name: 'specialist-area' }">
          <h1 class="m-0 brand-text">ConViva <span>Labev</span></h1>
        </RouterLink>
      </div>

      <div class="user-section">
        <div class="user-info d-none d-md-block">
          <small class="user-name">{{ usuarioLogado?.nome || 'Usuário' }}</small>
        </div>
        <div class="dropdown">
          <button
            class="avatar-circle shadow-sm dropdown-toggle"
            :style="{ backgroundColor: avatarColor }"
            type="button"
            id="userDropdown"
            data-bs-toggle="dropdown"
            aria-expanded="false"
          >
            {{ inicialNome }}
          </button>
          <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
            <li>
              <RouterLink class="dropdown-item" :to="{ name: 'editar-perfil' }">Editar Perfil</RouterLink>
            </li>
            <li v-if="isAdmin">
              <RouterLink class="dropdown-item text-warning fw-semibold" :to="{ name: 'aprovar-usuario' }">Gerenciar Usuários</RouterLink>
            </li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item text-danger" href="#" @click.prevent="fazerLogout">Sair</a></li>
          </ul>
        </div>
      </div>
    </div>

    <div class="nav-pill-container w-100">
      <div class="nav-pill-bg d-flex align-items-center nav-pill-scroll">
        <button
          type="button"
          class="nav-item-link nav-button"
          :class="{ active: abaAtiva === 'triagem' }"
          @click="selecionarAba('triagem')"
        >Início</button>
        <button
          type="button"
          class="nav-item-link nav-button"
          :class="{ active: abaAtiva === 'arquivadas' }"
          @click="selecionarAba('arquivadas')"
        >Arquivadas</button>
        <button
          type="button"
          class="nav-item-link nav-button"
          :class="{ active: abaAtiva === 'publicados' }"
          @click="selecionarAba('publicados')"
        >Publicados</button>
        <button
          type="button"
          class="nav-item-link nav-button"
          :class="{ active: abaAtiva === 'postagens' }"
          @click="selecionarAba('postagens')"
        >Postagens</button>
      </div>
    </div>
  </nav>
</template>

<script setup>
import { computed, ref, onMounted } from 'vue'
import { useRouter, RouterLink } from 'vue-router'
import { useAuth } from '@/composables/useAuth'

const props = defineProps({
  abaAtiva: {
    type: String,
    default: undefined
  }
})

const emit = defineEmits(['mudarAba'])

const router = useRouter()
const { usuarioLogado, logout } = useAuth()

const avatarColor = ref('#ffffff')

onMounted(() => {
  let storedColor = localStorage.getItem('avatar_color')
  if (!storedColor) {
    const pastelColors = ['#FFD1DC', '#AEC6CF', '#B5EAD7', '#E0BBE4']
    storedColor = pastelColors[Math.floor(Math.random() * pastelColors.length)]
    localStorage.setItem('avatar_color', storedColor)
  }
  avatarColor.value = storedColor
})

const isAdmin = computed(() => {
  return localStorage.getItem('user_tipo') === 'Administrador'
})

const selecionarAba = (aba) => {
  if (aba === 'postagens') {
    router.push({ name: 'gerenciar-posts' })
    return
  }

  if (props.abaAtiva !== undefined) {
    emit('mudarAba', aba)
    return
  }

  router.push({ name: 'specialist-area', query: { aba } })
}

const inicialNome = computed(() => {
  const nome = usuarioLogado.value?.name || usuarioLogado.value?.nome || localStorage.getItem('user_name') || 'U'
  return nome.charAt(0).toUpperCase()
})

const fazerLogout = () => {
  logout()
  router.push('/')
}
</script>

<style scoped>
.navbar-especialista {
  background-color: #58d68d;
  width: 100%;
  display: flex;
  flex-direction: column;
  gap: 0.75rem;
  padding: 0.75rem 1rem;
}

.brand-link {
  display: inline-flex;
  align-items: center;
}

.brand-text {
  color: white;
  font-weight: 800;
  font-size: 1.5rem;
  letter-spacing: -1px;
}

.brand-text span {
  font-weight: 400;
}

.nav-pill-container {
  display: flex;
  justify-content: center;
}

.nav-pill-bg {
  background-color: white;
  border-radius: 50px;
  padding: 6px 10px;
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
  gap: 4px;
}

.nav-pill-scroll {
  overflow-x: auto;
  -webkit-overflow-scrolling: touch;
  scrollbar-width: none;
  max-width: 100%;
}

.nav-pill-scroll::-webkit-scrollbar {
  display: none;
}

.nav-item-link {
  text-decoration: none;
  color: #9cdb81;
  font-weight: 600;
  padding: 8px 16px;
  border-radius: 30px;
  transition: all 0.3s ease;
  font-size: 0.85rem;
  white-space: nowrap;
  flex-shrink: 0;
}

.nav-button {
  border: 0;
  background: transparent;
}

.nav-item-link:hover {
  color: #45a049;
}

.nav-item-link.active {
  background-color: #9cdb81;
  color: white !important;
}

.user-section {
  display: flex;
  align-items: center;
  gap: 12px;
  flex-shrink: 0;
}

.user-info {
  text-align: right;
}

.user-name {
  color: white;
  display: block;
  font-weight: 500;
  white-space: nowrap;
}

.avatar-circle {
  width: 44px;
  height: 44px;
  background-color: white;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: bold;
  color: #333;
  font-size: 1.1rem;
  border: none;
  cursor: pointer;
  transition: all 0.3s ease;
}

.avatar-circle:hover {
  background-color: #f0f0f0;
}

.dropdown-menu {
  border: none;
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
  border-radius: 10px;
}

.dropdown-item {
  color: #333;
  transition: all 0.3s ease;
}

.dropdown-item:hover {
  background-color: #9cdb81;
  color: white;
}

@media (min-width: 768px) {
  .brand-text {
    font-size: 2rem;
  }

  .nav-item-link {
    font-size: 0.9rem;
    padding: 8px 18px;
  }

  .avatar-circle {
    width: 50px;
    height: 50px;
    font-size: 1.2rem;
  }
}

@media (min-width: 992px) {
  .navbar-especialista {
    flex-direction: row;
    align-items: center;
    height: 80px;
    padding: 0 1.5rem;
    gap: 1rem;
  }

  .navbar-top {
    width: auto;
    flex-shrink: 0;
  }

  .nav-pill-container {
    flex-grow: 1;
  }

  .nav-pill-bg {
    min-width: 450px;
    padding: 8px 15px;
    margin: 0 auto;
  }

  .brand-text {
    font-size: 2.2rem;
  }

  .nav-item-link {
    font-size: 0.95rem;
    padding: 8px 20px;
  }
}
</style>
