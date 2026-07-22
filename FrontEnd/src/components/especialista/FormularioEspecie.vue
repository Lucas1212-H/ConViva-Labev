<template>
    <div>
        <NavBar />
        <main>
            <h1>Cadastrar Espécie</h1>
            <form action="" @submit.prevent="salvarEspecieCompleta">
                <fieldset>
                    <legend>Dados da Espécie</legend>

                    <label for="nome_popular">Nome Popular</label>
                    <input type="text" name="NomePopular" id="nome_popular" placeholder="Ex: Sabiá-laranjeira" v-model="especie.nome_popular" required>
                    <label for="nome_cientifico">Nome Cientifico</label>
                    <input type="text" name="NomeCientifico" id="nome_cientifico" placeholder="Ex: Sturnus vulgaris" v-model="especie.nome_cientifico" required>
                    <label for="descricao">Descrição da Espécie</label>
                    <textarea name="Descricao" id="descricao" placeholder="Ex: Espécie de pássaro encontrada em áreas urbanas" v-model="especie.descricao"></textarea>
                    
                    <label for="classe">Classe</label>
                    <select name="Classe" id="classe" v-model="especie.id_classe" @change="carregarOrdens" required>
                        <option value="" disabled selected>Selecione uma classe</option>
                        <option v-for="classe in classes" :key="classe.id_classe" :value="classe.id_classe">
                            {{ classe.nome_popular }} ({{ classe.nome_cientifico }})
                        </option>
                    </select>
                    
                    <label for="ordem">Ordem</label>
                    <div class="ordem-container">
                        <div class="radio-group mb-2">
                            <label class="radio-label">
                                <input type="radio" v-model="modoOrdem" value="selecionar" :disabled="!especie.id_classe">
                                Selecionar existente
                            </label>
                            <label class="radio-label">
                                <input type="radio" v-model="modoOrdem" value="criar" :disabled="!especie.id_classe">
                                Criar nova
                            </label>
                        </div>
                        
                        <select 
                            v-if="modoOrdem === 'selecionar'" 
                            name="Ordem" 
                            id="ordem" 
                            v-model="especie.id_ordem" 
                            @change="carregarFamilias" 
                            :disabled="!especie.id_classe"
                        >
                            <option value="" disabled selected>Selecione uma ordem</option>
                            <option v-for="ordem in ordens" :key="ordem.id_ordem" :value="ordem.id_ordem">
                                {{ ordem.nome_popular }} ({{ ordem.nome_cientifico }})
                            </option>
                        </select>
                        
                        <div v-if="modoOrdem === 'criar'" class="nova-ordem">
                            <input 
                                type="text" 
                                placeholder="Nome Popular da Ordem" 
                                v-model="novaOrdem.nome_popular"
                                class="mb-2"
                            >
                            <input 
                                type="text" 
                                placeholder="Nome Científico da Ordem" 
                                v-model="novaOrdem.nome_cientifico"
                            >
                        </div>
                    </div>
                    
                    <label for="familia">Família</label>
                    <div class="familia-container">
                        <div class="radio-group mb-2">
                            <label class="radio-label">
                                <input type="radio" v-model="modoFamilia" value="selecionar" :disabled="!especie.id_ordem && !novaOrdem.nome_popular">
                                Selecionar existente
                            </label>
                            <label class="radio-label">
                                <input type="radio" v-model="modoFamilia" value="criar" :disabled="!especie.id_ordem && !novaOrdem.nome_popular">
                                Criar nova
                            </label>
                        </div>
                        
                        <select 
                            v-if="modoFamilia === 'selecionar'" 
                            name="Familia" 
                            id="familia" 
                            v-model="especie.id_familia" 
                            :disabled="!especie.id_ordem"
                        >
                            <option value="" disabled selected>Selecione uma família</option>
                            <option v-for="familia in familias" :key="familia.id_familia" :value="familia.id_familia">
                                {{ familia.nome_popular }} ({{ familia.nome_cientifico }})
                            </option>
                        </select>
                        
                        <div v-if="modoFamilia === 'criar'" class="nova-familia">
                            <input 
                                type="text" 
                                placeholder="Nome Popular da Família" 
                                v-model="novaFamilia.nome_popular"
                                class="mb-2"
                            >
                            <input 
                                type="text" 
                                placeholder="Nome Científico da Família" 
                                v-model="novaFamilia.nome_cientifico"
                            >
                        </div>
                    </div>
                </fieldset>

                <fieldset>
                    <legend>Atribuir Ocorrências Publicadas</legend>
                    <p>Selecione abaixo as ocorrências publicadas no mapa que pertencem a esta espécie.</p>
                    <div v-if="carregandoOcorrencias" class="text-center py-3">
                        <div class="spinner-border spinner-border-sm text-success" role="status"></div>
                        <small class="text-muted ms-2">Buscando ocorrências publicadas...</small>
                    </div>
                    <div v-else-if="ocorrenciasPublicadas.length > 0">
                        <label
                          v-for="ocorrencia in ocorrenciasPublicadas"
                          :key="ocorrencia.id"
                          class="d-block border rounded p-3 mb-3"
                        >
                          <input
                            v-model="ocorrenciasSelecionadas"
                            :value="ocorrencia.id"
                            type="checkbox"
                            class="me-2"
                          >
                          <div class="d-inline-block w-100 align-middle">
                            <div class="d-flex justify-content-between align-items-center">
                              <span class="badge rounded-pill px-3 py-1 text-uppercase bg-success">
                                {{ ocorrencia.status || 'Publicado' }}
                              </span>
                              <small class="text-secondary">{{ formatarData(ocorrencia.created_at) }}</small>
                            </div>
                            <p class="mb-0 mt-2 text-dark fw-medium small">
                              Animal: <span class="text-muted font-monospace">{{ ocorrencia.tipo_animal }}</span>
                            </p>
                            <p class="mb-0 mt-1 text-dark fw-medium small">
                              Localização: <span class="text-muted font-monospace">{{ ocorrencia.ponto_referencia || '-' }}</span>
                            </p>
                          </div>
                        </label>
                    </div>
                    <div v-else class="alert alert-light text-center border p-4 m-0">
                     Nenhuma ocorrência publicada disponível para vinculação no momento.
                    </div>
                    <button type="submit" :disabled="salvando">
                        <span v-if="salvando" class="spinner-border spinner-border-sm me-2"></span>
                        {{ salvando ? 'salvando...' : 'Publicar Espécie e Atribuir Casos' }}
                    </button>
                </fieldset>
                

            </form>
        </main>
    </div>
</template>
<script>
import NavBar from '@/components/NavBar.vue'
import axios from 'axios'

const isLocal = window.location.hostname === 'localhost' || window.location.hostname === '127.0.0.1';

// Configura a URL base da API dinamicamente baseado no ambiente
const API_BASE_URL = isLocal 
  ? 'http://localhost:8000' 
  : 'https://conviva-labev.onrender.com';

export default {
  components: {
    NavBar
  },
  data() {
    return {
      especie: {
        nome_popular: '',
        nome_cientifico: '',
        descricao: '',
        id_classe: '',
        id_ordem: '',
        id_familia: ''
      },
      
      // Estados de carregamento
      carregandoOcorrencias: true,
      salvando: false,

      // Dados vindo da API
      ocorrenciasPublicadas: [],
      classes: [],
      ordens: [],
      familias: [],

      // Caixinha (Array) que guarda automaticamente os IDs dos checkboxes marcados
      ocorrenciasSelecionadas: [],

      // Modo de seleção/criação
      modoOrdem: 'selecionar',
      modoFamilia: 'selecionar',

      // Dados para criar nova ordem/familia
      novaOrdem: {
        nome_popular: '',
        nome_cientifico: ''
      },
      novaFamilia: {
        nome_popular: '',
        nome_cientifico: ''
      }
    }
  },
  methods: {
    async buscarClasses() {
      try {
        const response = await axios.get(`${API_BASE_URL}/api/classes`);
        this.classes = response.data || [];
      } catch (error) {
        console.error('Erro ao buscar classes:', error);
        this.classes = [];
      }
    },

    async carregarOrdens() {
      this.especie.id_ordem = '';
      this.especie.id_familia = '';
      this.familias = [];
      
      if (!this.especie.id_classe) {
        this.ordens = [];
        return;
      }

      try {
        const response = await axios.get(`${API_BASE_URL}/api/ordens?id_classe=${this.especie.id_classe}`);
        this.ordens = response.data || [];
      } catch (error) {
        console.error('Erro ao buscar ordens:', error);
        this.ordens = [];
      }
    },

    async carregarFamilias() {
      this.especie.id_familia = '';
      
      // Se estiver criando nova ordem, não carrega famílias
      if (this.modoOrdem === 'criar') {
        this.familias = [];
        return;
      }
      
      if (!this.especie.id_ordem) {
        this.familias = [];
        return;
      }

      try {
        const response = await axios.get(`${API_BASE_URL}/api/familias?id_ordem=${this.especie.id_ordem}`);
        this.familias = response.data || [];
      } catch (error) {
        console.error('Erro ao buscar famílias:', error);
        this.familias = [];
      }
    },

    async buscarOcorrenciasPublicadas() {
      try {
        this.carregandoOcorrencias = true;
        const response = await axios.get(`${API_BASE_URL}/api/ocorrencias/publicados`);
        this.ocorrenciasPublicadas = response.data || [];
      } catch (error) {
        console.error('Erro ao buscar ocorrências publicadas:', error);
        this.ocorrenciasPublicadas = [];
      } finally {
        this.carregandoOcorrencias = false;
      }
    },

    async salvarEspecieCompleta() {
      try {
        this.salvando = true;

        let ordemId = this.especie.id_ordem;
        let familiaId = this.especie.id_familia;

        // Criar nova ordem se necessário
        if (this.modoOrdem === 'criar' && this.novaOrdem.nome_popular && this.novaOrdem.nome_cientifico) {
          const responseOrdem = await axios.post(`${API_BASE_URL}/api/ordens`, {
            nome_popular: this.novaOrdem.nome_popular,
            nome_cientifico: this.novaOrdem.nome_cientifico,
            id_classe: this.especie.id_classe
          });
          ordemId = responseOrdem.data.id_ordem;
        }

        // Criar nova família se necessário
        if (this.modoFamilia === 'criar' && this.novaFamilia.nome_popular && this.novaFamilia.nome_cientifico) {
          const responseFamilia = await axios.post(`${API_BASE_URL}/api/familias`, {
            nome_popular: this.novaFamilia.nome_popular,
            nome_cientifico: this.novaFamilia.nome_cientifico,
            id_ordem: ordemId
          });
          familiaId = responseFamilia.data.id_familia;
        }

        const formData = new FormData();
        formData.append('nome_popular', this.especie.nome_popular);
        formData.append('nome_cientifico', this.especie.nome_cientifico);
        formData.append('descricao', this.especie.descricao || '');
        formData.append('id_classe', this.especie.id_classe);
        if (ordemId) formData.append('id_ordem', ordemId);
        if (familiaId) formData.append('id_familia', familiaId);

        const responseEspecie = await axios.post(`${API_BASE_URL}/api/especies`, formData, {
          headers: { 'Content-Type': 'multipart/form-data' }
        });

        const novaEspecieId = responseEspecie.data.id_especie || responseEspecie.data.id;

        if (this.ocorrenciasSelecionadas.length > 0) {
          await axios.post(`${API_BASE_URL}/api/especies/${novaEspecieId}/vincular-ocorrencias`, {
            ocorrencias_ids: this.ocorrenciasSelecionadas
          });
        }

        alert('Espécie cadastrada e ocorrências vinculadas com sucesso!');

        this.especie = {
          nome_popular: '',
          nome_cientifico: '',
          descricao: '',
          id_classe: '',
          id_ordem: '',
          id_familia: ''
        };
        this.novaOrdem = { nome_popular: '', nome_cientifico: '' };
        this.novaFamilia = { nome_popular: '', nome_cientifico: '' };
        this.modoOrdem = 'selecionar';
        this.modoFamilia = 'selecionar';
        this.ocorrenciasSelecionadas = [];
        this.$router.push('/catalogo');

      } catch (error) {
        console.error('Erro no fluxo de cadastro/vinculação:', error);
        alert('Ocorreu um erro ao salvar o registro.');
      } finally {
        this.salvando = false;
      }
    },

    // Auxiliar apenas para formatar a data do banco (ISO) para o padrão brasileiro
    formatarData(stringData) {
      const data = new Date(stringData);
      return data.toLocaleDateString('pt-BR', { day: '2-digit', month: '2-digit', year: 'numeric' });
    }
  },
  mounted() {
    this.buscarClasses();
    this.buscarOcorrenciasPublicadas();
  }
}
</script>

<style scoped>
.radio-group {
  display: flex;
  gap: 1rem;
}

.radio-label {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  cursor: pointer;
  font-size: 0.9rem;
}

.radio-label input[type="radio"] {
  cursor: pointer;
}

.nova-ordem,
.nova-familia {
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
}

.nova-ordem input,
.nova-familia input {
  padding: 0.5rem;
  border: 1px solid #ddd;
  border-radius: 0.375rem;
  font-size: 0.9rem;
}

.ordem-container,
.familia-container {
  margin-bottom: 1rem;
}
</style>