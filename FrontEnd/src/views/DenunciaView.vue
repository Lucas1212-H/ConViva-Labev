<template>
  <div class="denuncia-page min-vh-100 bg-light py-5">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-lg-8">
          <div class="denuncia-card shadow-lg p-5 rounded-4">
            <h1 class="mb-4 fw-bold text-success">Denuncie um Animal Silvestre</h1>
            <p class="text-muted mb-4">Ajude-nos a catalogar e proteger a fauna da região</p>
            
            <form @submit.prevent="handleDenuncia">
              <div class="mb-4">
                <label class="form-label fw-bold">Tipo de Animal</label>
                <input type="text" v-model="formData.animal" class="form-control custom-input" placeholder="Ex: Serpente, Capivara..." required>
              </div>
              
              <div class="mb-4">
                <label class="form-label fw-bold">Status do Animal</label>
                <select v-model="formData.status" class="form-control custom-input" required>
                  <option value="">Selecione um status</option>
                  <option value="Vivo">Vivo</option>
                  <option value="Ferido">Ferido</option>
                  <option value="Morto">Morto</option>
                </select>
              </div>
              
              <div class="mb-4">
                <label class="form-label fw-bold">Local da Ocorrência</label>
                <input type="text" v-model="formData.local" class="form-control custom-input" placeholder="Endereço ou referência" required>
              </div>
              
              <div class="mb-4">
                <label class="form-label fw-bold">Descrição Detalhada</label>
                <textarea v-model="formData.descricao" class="form-control custom-input" rows="5" placeholder="Descreva o que viu em detalhes..." required></textarea>
              </div>
              
              <div class="mb-4">
                <label class="form-label fw-bold">Foto (opcional)</label>
                <input type="file" @change="handleFileUpload" class="form-control custom-input" accept="image/*">
              </div>
              
              <div class="d-grid gap-2">
                <button type="submit" :disabled="carregando" class="btn btn-success btn-lg fw-bold py-3">
                  {{ carregando ? 'Enviando...' : 'Enviar Denúncia' }}
                </button>
              </div>
            </form>
            
            <div v-if="erro" class="alert alert-danger mt-4 rounded-3">{{ erro }}</div>
            <div v-if="sucesso" class="alert alert-success mt-4 rounded-3">{{ sucesso }}</div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import { useRouter } from 'vue-router';

const router = useRouter();
const carregando = ref(false);
const erro = ref('');
const sucesso = ref('');

const formData = ref({
  animal: '',
  status: '',
  local: '',
  descricao: '',
  foto: null
});

const handleFileUpload = (event) => {
  const file = event.target.files[0];
  if (file) {
    if (file.size > 5000000) {
      erro.value = 'Arquivo muito grande. Máximo 5MB';
      return;
    }
    formData.value.foto = file;
    erro.value = '';
  }
};

const handleDenuncia = async () => {
  try {
    carregando.value = true;
    erro.value = '';
    sucesso.value = '';
    
    if (!formData.value.animal || !formData.value.status || !formData.value.local || !formData.value.descricao) {
      erro.value = 'Preencha todos os campos obrigatórios';
      return;
    }
    
    // TODO: Implementar envio com API (FormData para foto)
    console.log('Enviando denúncia:', formData.value);
    
    // Simulação de sucesso
    await new Promise(resolve => setTimeout(resolve, 1500));
    
    sucesso.value = 'Denúncia enviada com sucesso! Obrigado por contribuir.';
    
    // Limpar formulário
    formData.value = {
      animal: '',
      status: '',
      local: '',
      descricao: '',
      foto: null
    };
    
    setTimeout(() => {
      router.push('/home');
    }, 2000);
  } catch (err) {
    erro.value = 'Erro ao enviar denúncia. Tente novamente.';
    console.error(err);
  } finally {
    carregando.value = false;
  }
};
</script>

<style scoped>
.denuncia-card {
  background: white;
  border: 2px solid #58d68d;
}

.custom-input {
  background: #f0f7f4;
  border: 1px solid #58d68d;
  border-radius: 12px;
  padding: 12px;
  transition: all 0.3s ease;
}

.custom-input:focus {
  background: white;
  border-color: #2e8b57;
  box-shadow: 0 0 0 3px rgba(88, 214, 141, 0.1);
}

select.custom-input {
  cursor: pointer;
}

.btn-success {
  background-color: #58d68d;
  border: none;
  border-radius: 15px;
  transition: all 0.3s ease;
}

.btn-success:hover {
  background-color: #2e8b57;
  transform: translateY(-2px);
  box-shadow: 0 8px 16px rgba(88, 214, 141, 0.3);
}

.btn-success:disabled {
  opacity: 0.6;
  cursor: not-allowed;
}
</style>
