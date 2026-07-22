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
                    
                    <label for="ordem_popular">Ordem (Nome Popular)</label>
                    <input type="text" name="OrdemPopular" id="ordem_popular" placeholder="Ex: Carnívoros" v-model="especie.ordem_popular">
                    
                    <label for="ordem_cientifico">Ordem (Nome Científico)</label>
                    <input type="text" name="OrdemCientifico" id="ordem_cientifico" placeholder="Ex: Carnivora" v-model="especie.ordem_cientifico">
                    
                    <label for="familia_popular">Família (Nome Popular)</label>
                    <input type="text" name="FamiliaPopular" id="familia_popular" placeholder="Ex: Felídeos" v-model="especie.familia_popular">
                    
                    <label for="familia_cientifico">Família (Nome Científico)</label>
                    <input type="text" name="FamiliaCientifico" id="familia_cientifico" placeholder="Ex: Felidae" v-model="especie.familia_cientifico">
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
        ordem_popular: '',
        ordem_cientifico: '',
        familia_popular: '',
        familia_cientifico: ''
      },
      
      // Estados de carregamento
      carregandoOcorrencias: true,
      salvando: false,

      // Dados vindo da API
      ocorrenciasPublicadas: [],
      classes: [],

      // Caixinha (Array) que guarda automaticamente os IDs dos checkboxes marcados
      ocorrenciasSelecionadas: []
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

        let ordemId = null;
        let familiaId = null;

        // Criar ordem se fornecida
        if (this.especie.ordem_popular && this.especie.ordem_cientifico) {
          const responseOrdem = await axios.post(`${API_BASE_URL}/api/ordens`, {
            nome_popular: this.especie.ordem_popular,
            nome_cientifico: this.especie.ordem_cientifico,
            id_classe: this.especie.id_classe
          });
          ordemId = responseOrdem.data.id_ordem;
        }

        // Criar família se fornecida
        if (this.especie.familia_popular && this.especie.familia_cientifico) {
          const responseFamilia = await axios.post(`${API_BASE_URL}/api/familias`, {
            nome_popular: this.especie.familia_popular,
            nome_cientifico: this.especie.familia_cientifico,
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
          ordem_popular: '',
          ordem_cientifico: '',
          familia_popular: '',
          familia_cientifico: ''
        };
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