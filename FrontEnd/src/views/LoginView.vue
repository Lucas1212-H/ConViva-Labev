<template>
  <div class="login-page d-flex align-items-center justify-content-center">
    <div class="container">
      <div class="row align-items-center justify-content-center">
        <div class="col-md-5 d-none d-md-block text-center">
          <img src="@/assets/images/capivara-login.png" alt="Capivara" class="img-fluid hero-img">
        </div>
        
        <div class="col-md-6">
          <div class="auth-card shadow-lg p-5">
            <h2 class="auth-title mb-4">Login Organizacional</h2>
            
            <form @submit.prevent="handleLogin">
              <div class="mb-4">
                <label class="form-label">Email</label>
                <input type="email" v-model="email" class="form-control custom-input" placeholder="seu@email.com" required>
              </div>
              
              <div class="mb-4">
                <label class="form-label">Senha</label>
                <input type="password" v-model="senha" class="form-control custom-input" placeholder="********" required>
              </div>
              
              <button type="submit" class="btn btn-dark w-100 py-3 fw-bold mb-3">Entrar</button>
              
              <div class="d-flex justify-content-between auth-footer">
                <RouterLink to="/cadastro" class="text-decoration-none text-dark fw-bold">Cadastre Aqui</RouterLink>
                <a href="#" class="text-decoration-none text-dark opacity-75">Esqueci minha senha</a>
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
const email = ref('');
const senha = ref('');
const carregando = ref(false);
const erro = ref('');

const handleLogin = async () => {
  try {
    carregando.value = true;
    erro.value = '';
    
    if (!email.value || !senha.value) {
      erro.value = 'Email e senha são obrigatórios';
      return;
    }
    
    // TODO: Implementar autenticação com API
    console.log('Tentando login com:', { email: email.value });
    
    // Simulação de sucesso (remover após integração com API)
    await new Promise(resolve => setTimeout(resolve, 1000));
    
    // Salvar estado de autenticação
    localStorage.setItem('usuarioLogado', JSON.stringify({
      email: email.value,
      dataLogin: new Date().toISOString()
    }));
    
    // Redirecionar para área do especialista
    router.push('/especialista');
  } catch (err) {
    erro.value = 'Erro ao fazer login. Tente novamente.';
    console.error(err);
  } finally {
    carregando.value = false;
  }
};
</script>

<style scoped>
.login-page { min-height: 100vh; background-color: #f8fafc; }
.auth-card { 
  background-color: #9cdb81; /* Verde do seu mockup */
  border-radius: 40px; 
}
.auth-title { font-weight: 700; color: #1e293b; }
.custom-input { 
  background: rgba(255,255,255,0.5); 
  border: 1px solid rgba(0,0,0,0.1);
  border-radius: 10px;
  padding: 12px;
}
.hero-img { max-width: 80%; }
</style>