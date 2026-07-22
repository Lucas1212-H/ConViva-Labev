<template>
  <div class="card border-0 shadow-sm p-3 p-sm-4 mx-auto passo-contato-card passo-card">

    <h1 class="h5 fw-bold text-dark mb-1">Seus dados</h1>
    <p class="small text-muted mb-3">Precisamos de um contato para retorno</p>

    <div class="mb-2">
      <label class="form-label small text-uppercase text-muted fw-bold">Nome</label>
      <input
        v-model.trim="nome"
        class="form-control shadow-none rounded-3"
        placeholder="Nome completo"
        autocomplete="name"
      />
    </div>

    <div class="mb-2">
      <div class="text-uppercase text-muted fw-bold mb-2" style="font-size: 0.7rem; letter-spacing: 0.8px;">Contato</div>
      <div class="d-flex gap-2 mb-2">
        <button type="button" @click="contatoTipo = 'telefone'" :class="contatoTipo === 'telefone' ? 'btn btn-success' : 'btn btn-outline-secondary'" class="btn btn-sm">Telefone</button>
        <button type="button" @click="contatoTipo = 'instagram'" :class="contatoTipo === 'instagram' ? 'btn btn-success' : 'btn btn-outline-secondary'" class="btn btn-sm">Instagram</button>
      </div>
      <input
        :value="contatoValor"
        @input="onContatoInput"
        :type="contatoTipo === 'instagram' ? 'text' : 'tel'"
        :inputmode="contatoTipo === 'instagram' ? 'text' : 'numeric'"
        :maxlength="contatoTipo === 'instagram' ? 30 : 11"
        :placeholder="contatoTipo === 'instagram' ? '@seuusuario' : '99 99999-9999'"
        :autocomplete="contatoTipo === 'instagram' ? 'off' : 'tel'"
        class="form-control shadow-none rounded-3"
      />
      <div class="form-text small text-muted mt-1">
        {{ contatoTipo === 'instagram' ? 'Digite seu @ do Instagram.' : 'Digite somente números, sem espaços, parênteses ou traços.' }}
      </div>
    </div>

    <button 
      class="btn btn-light w-100 py-3 fw-bold border d-flex justify-content-center align-items-center gap-2 mt-2" 
      style="border-radius: 12px; font-size: 14px;"
      @click="enviar" 
      :disabled="!podeAvancar"
    >
      ➔ Próximo — detalhes do animal
    </button>

  </div>
</template>

<script setup>
import { computed, ref } from 'vue'
const emit = defineEmits(['proximo'])

const nome = ref('')
const contatoTipo = ref('telefone')
const contatoValor = ref('')

const contatoSanitizado = computed(() => {
  if (contatoTipo.value === 'telefone') {
    return contatoValor.value.replace(/\D/g, '').slice(0, 11)
  }

  return contatoValor.value.trim().toLowerCase()
})

const nomeValido = computed(() => nome.value.trim().length > 0)

const contatoValido = computed(() => {
  const valor = contatoSanitizado.value

  if (contatoTipo.value === 'telefone') {
    return /^\d{10,11}$/.test(valor)
  }

  return /^@?[a-zA-Z0-9._]{1,30}$/.test(valor)
})

const podeAvancar = computed(() => nomeValido.value && contatoValido.value)

const onContatoInput = (event) => {
  const valorDigitado = event.target.value

  if (contatoTipo.value === 'telefone') {
    contatoValor.value = valorDigitado.replace(/\D/g, '').slice(0, 11)
    return
  }

  contatoValor.value = valorDigitado.slice(0, 30)
}

const enviar = () => {
  if (!podeAvancar.value) return

  emit('proximo', {
    nome: nome.value.trim(),
    contato: { tipo: contatoTipo.value, valor: contatoSanitizado.value }
  })
}
</script>

<style scoped>
.btn-sm { padding: 0.35rem 0.6rem; }
.form-control:focus { border-color: #198754; box-shadow: 0 0 0 0.15rem rgba(25,135,84,0.12); }

.passo-contato-card {
  border-radius: 20px;
}
</style>