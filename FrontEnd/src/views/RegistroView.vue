<template>
  <div class="register-page d-flex align-items-center justify-content-center">
    <div class="container">
      <div class="row align-items-center flex-row-reverse">
        <div class="col-md-5 d-none d-md-block text-center">
          <img src="@/assets/images/cobra-cadastro.png" alt="Cobra" class="img-fluid hero-img">
        </div>
        
        <div class="col-md-6">
          <div class="auth-card shadow-lg p-5">
            <h2 class="auth-title mb-4">Cadastro</h2>
            
            <form @submit.prevent="handleRegister">
              <div class="mb-3">
                <label class="form-label">Nome Completo</label>
                <input type="text" v-model="nomeCompleto" class="form-control custom-input" required>
              </div>
              
              <div class="mb-3">
                <label class="form-label">Email Institucional</label>
                <input type="email" v-model="email" class="form-control custom-input" required>
              </div>
              
              <div class="mb-4">
                <label class="form-label">Senha</label>
                <input type="password" v-model="senha" class="form-control custom-input" required>
              </div>
              
              <button type="submit" :disabled="carregando" class="btn btn-dark w-100 py-3 fw-bold mb-3">
                {{ carregando ? 'Cadastrando...' : 'Cadastrar' }}
              </button>
              
              <div class="text-center auth-footer">
                <p class="small opacity-75 mb-3">Seu nome de perfil não poderá ser alterado depois, nunca envie senhas.</p>
                <RouterLink to="/login" class="text-decoration-none text-primary fw-bold">Já possui conta? Clique Aqui</RouterLink>
              </div>
            </form>
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
const nomeCompleto = ref('');
const email = ref('');
const senha = ref('');
const carregando = ref(false);
const erro = ref('');

const handleRegister = async () => {
  try {
    carregando.value = true;
    erro.value = '';
    
    if (!nomeCompleto.value || !email.value || !senha.value) {
      erro.value = 'Todos os campos são obrigatórios';
      return;
    }
    
    if (senha.value.length < 6) {
      erro.value = 'Senha deve ter no mínimo 6 caracteres';
      return;
    }
    
    
    console.log('Cadastrando usuário:', { 
      nomeCompleto: nomeCompleto.value, 
      email: email.value 
    });
    
    await new Promise(resolve => setTimeout(resolve, 1000));
    router.push('/login');
  } catch (err) {
    erro.value = 'Erro ao cadastrar. Tente novamente.';
    console.error(err);
  } finally {
    carregando.value = false;
  }
};
</script>

<style scoped>
.register-page { min-height: 100vh; background-color: #f8fafc; }
.auth-card { background-color: #9cdb81; border-radius: 40px; }
.auth-title { font-weight: 700; font-size: 2.5rem; }
.custom-input { background: rgba(255,255,255,0.5); border: none; border-radius: 15px; }
</style>