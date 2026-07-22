<template>
  <div>
    <NavBar />

    <main class="container py-5">
      
      <!-- Visão da Lista de Classes -->
      <section v-if="!classeSelecionada">
        <header class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center mb-4 gap-3">
          <h1 class="h3 fw-bold text-dark m-0">Catálogo de Classes</h1>
          <button v-if="eAdmin" class="btn btn-success fw-medium px-4 w-100 w-sm-auto" @click="modalNovaClasse = true">
            + Nova Classe
          </button>
        </header>

        <div class="row row-cols-2 row-cols-md-4 g-4 justify-content-center">
          <div class="col" v-for="cls in listaClasses" :key="cls.id_classe">
            <article 
              class="card p-4 text-center shadow-sm h-100 border-0 rounded-3 position-relative"
              style="cursor: pointer;"
              @click="selecionarClasse(cls)"
            >
              <button 
                v-if="eAdmin" 
                class="btn btn-sm btn-light border position-absolute top-0 end-0 m-2 rounded-circle btn-editar-cat"
                title="Editar Classe"
                @click.stop="prepararEdicaoClasse(cls)"
              >
                ✏️
              </button>

              <figure class="m-0">
                <img 
                  :src="obterImagemUrlTratada(cls)" 
                  :alt="cls.nome_popular"
                  class="img-fluid mb-2 object-fit-cover" 
                  style="max-height: 80px; min-height: 80px; width: auto;"
                >
                <figcaption class="h5 fw-bold text-dark m-0">{{ cls.nome_popular }}</figcaption>
                <small class="text-muted fst-italic">{{ cls.nome_cientifico }}</small>
              </figure>
            </article>
          </div>
        </div>
      </section>

      <!-- Visão das Espécies da Classe Selecionada -->
      <section v-else>
        <header class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center mb-4 gap-3">
          <div class="w-100">
            <button class="btn btn-sm btn-light border px-3 mb-2" @click="classeSelecionada = null">
              ← Voltar
            </button>
            <h1 class="h3 fw-bold text-success m-0">Espécies em {{ classeSelecionada.nome_popular }}</h1>
          </div>
          
          <button v-if="eAdmin" class="btn btn-primary fw-medium px-4 w-100 w-sm-auto" @click="modalNovaEspecie = true">
            + Nova Espécie
          </button>
        </header>

        <div class="row row-cols-1 row-cols-md-2 row-cols-xl-3 g-4">
          <div class="col" v-for="especie in (classeSelecionada?.especies || [])" :key="especie.id_especie">
            <article class="card especie-card h-100 shadow-sm border-0 overflow-hidden rounded-3" style="cursor: pointer;" @click="IrParaDetalhes(especie.id_especie)">
              <div v-if="eAdmin" class="species-actions">
                <button
                  class="btn btn-sm btn-light border shadow-sm me-2"
                  title="Editar espécie"
                  @click.stop="prepararEdicaoEspecie(especie)"
                >
                  ✏️
                </button>
                <button
                  class="btn btn-sm btn-light border shadow-sm text-danger"
                  title="Excluir espécie"
                  @click.stop="confirmarExclusaoEspecie(especie)"
                >
                  🗑️
                </button>
              </div>

              <img 
                :src="obterImagemUrlTratada(especie)" 
                :alt="especie.nome_popular" 
                class="card-img-top object-fit-cover"
                style="height: 180px;"
              >
              <div class="card-body p-3 d-flex flex-column gap-3">
                <div>
                  <h2 class="h5 fw-bold text-dark mb-1">{{ especie.nome_popular }}</h2>
                  <small class="text-muted fst-italic d-block mb-2">{{ especie.nome_cientifico }}</small>
                  <p class="text-secondary small m-0">{{ especie.descricao }}</p>
                  <div v-if="especie.ordem || especie.familia" class="mt-2">
                    <small v-if="especie.ordem" class="text-muted d-block">
                      <span class="fw-semibold">Ordem:</span> {{ especie.ordem }}
                    </small>
                    <small v-if="especie.familia" class="text-muted d-block">
                      <span class="fw-semibold">Família:</span> {{ especie.familia }}
                    </small>
                  </div>
                </div>

                <div class="species-map-wrap" @click.stop>
                  <div class="d-flex justify-content-between align-items-center mb-2">
                    <small class="text-uppercase fw-semibold text-muted">Mapa da espécie</small>
                    <span class="badge rounded-pill bg-success-subtle text-success-emphasis">
                      {{ especie.ocorrencias?.length || 0 }} pontos
                    </span>
                  </div>
                  <EspecieMapaPreview :ocorrencias="especie.ocorrencias || []" />
                </div>
              </div>
            </article>
          </div>
        </div>

        <div v-if="(classeSelecionada?.especies || []).length === 0" class="alert alert-light text-center border mt-4">
          Nenhuma espécie cadastrada em {{ classeSelecionada?.nome_popular }} ainda.
        </div>
      </section>

    </main>

    <!-- Modal Nova Classe -->
    <div class="modal" :class="{ show: modalNovaClasse }" :style="{ display: modalNovaClasse ? 'block' : 'none' }" tabindex="-1">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Nova Classe</h5>
            <button type="button" class="btn-close" @click="modalNovaClasse = false"></button>
          </div>
          <div class="modal-body">
            <div class="mb-3">
              <label class="form-label">Nome Científico</label>
              <input type="text" class="form-control" v-model="formClasse.nome_cientifico" placeholder="Ex: Mammalia">
            </div>
            <div class="mb-3">
              <label class="form-label">Nome Popular</label>
              <input type="text" class="form-control" v-model="formClasse.nome_popular" placeholder="Ex: Mamíferos">
            </div>
            <div class="mb-3">
              <label class="form-label">Foto</label>
              <input type="file" class="form-control" @change="handleFotoClasseChange" accept="image/*">
              <small class="text-muted">Formatos aceitos: JPG, PNG, GIF, WebP</small>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" @click="modalNovaClasse = false">Cancelar</button>
            <button type="button" class="btn btn-success" @click="criarClasse">Salvar</button>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal Editar Classe -->
    <div class="modal" :class="{ show: modalEditarClasse }" :style="{ display: modalEditarClasse ? 'block' : 'none' }" tabindex="-1">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header bg-primary text-white">
            <h5 class="modal-title">Editar Classe</h5>
            <button type="button" class="btn-close btn-close-white" @click="modalEditarClasse = false"></button>
          </div>
          <div class="modal-body">
            <div class="mb-3">
              <label class="form-label">Nome Científico</label>
              <input type="text" class="form-control" v-model="formEditarClasse.nome_cientifico">
            </div>
            <div class="mb-3">
              <label class="form-label">Nome Popular</label>
              <input type="text" class="form-control" v-model="formEditarClasse.nome_popular">
            </div>
            <div class="mb-3">
              <label class="form-label">Substituir Foto</label>
              <input type="file" class="form-control" @change="handleFotoEditarClasseChange" accept="image/*">
              <small class="text-muted">Deixe em branco se preferir manter a imagem atual.</small>
            </div>
          </div>
          <div class="modal-footer justify-content-between flex-wrap gap-2">
            <button type="button" class="btn btn-outline-danger" @click="excluirClasseEditando">Excluir Classe</button>
            <div class="d-flex gap-2 ms-auto">
              <button type="button" class="btn btn-secondary" @click="modalEditarClasse = false">Cancelar</button>
              <button type="button" class="btn btn-primary" @click="salvarEdicaoClasse" :disabled="salvandoClasse">
                <span v-if="salvandoClasse" class="spinner-border spinner-border-sm me-1"></span>
                Atualizar
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal Editar Espécie -->
    <div class="modal" :class="{ show: modalEditarEspecie }" :style="{ display: modalEditarEspecie ? 'block' : 'none' }" tabindex="-1">
      <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
          <div class="modal-header bg-success text-white">
            <h5 class="modal-title">Editar Espécie</h5>
            <button type="button" class="btn-close btn-close-white" @click="modalEditarEspecie = false"></button>
          </div>
          <div class="modal-body">
            <div class="row g-3">
              <div class="col-md-6">
                <label class="form-label">Nome Científico</label>
                <input type="text" class="form-control" v-model="formEditarEspecie.nome_cientifico">
              </div>
              <div class="col-md-6">
                <label class="form-label">Nome Popular</label>
                <input type="text" class="form-control" v-model="formEditarEspecie.nome_popular">
              </div>
              <div class="col-md-6">
                <label class="form-label">Classe</label>
                <select class="form-select" v-model="formEditarEspecie.id_classe">
                  <option v-for="cls in listaClasses" :key="cls.id_classe" :value="cls.id_classe">
                    {{ cls.nome_popular }}
                  </option>
                </select>
              </div>
              <div class="col-12">
                <label class="form-label">Descrição</label>
                <textarea class="form-control" v-model="formEditarEspecie.descricao" rows="3"></textarea>
              </div>
              <div class="col-12">
                <label class="form-label">Substituir Foto</label>
                <input type="file" class="form-control" @change="handleFotoEditarEspecieChange" accept="image/*">
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" @click="modalEditarEspecie = false">Cancelar</button>
            <button type="button" class="btn btn-success" @click="salvarEdicaoEspecie" :disabled="salvandoEspecie">
              <span v-if="salvandoEspecie" class="spinner-border spinner-border-sm me-1"></span>
              Atualizar Espécie
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal Nova Espécie -->
    <div class="modal" :class="{ show: modalNovaEspecie }" :style="{ display: modalNovaEspecie ? 'block' : 'none' }" tabindex="-1">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Nova Espécie</h5>
            <button type="button" class="btn-close" @click="modalNovaEspecie = false"></button>
          </div>
          <div class="modal-body">
            <div class="mb-3">
              <label class="form-label">Nome Científico</label>
              <input type="text" class="form-control" v-model="formEspecie.nome_cientifico" placeholder="Ex: Panthera leo">
            </div>
            <div class="mb-3">
              <label class="form-label">Nome Popular</label>
              <input type="text" class="form-control" v-model="formEspecie.nome_popular" placeholder="Ex: Leão">
            </div>
            <div class="mb-3">
              <label class="form-label">Foto</label>
              <input type="file" class="form-control" @change="handleFotoEspecieChange" accept="image/*">
              <small class="text-muted">Formatos aceitos: JPG, PNG, GIF, WebP</small>
            </div>
            <div class="mb-3">
              <label class="form-label">Descrição</label>
              <textarea class="form-control" v-model="formEspecie.descricao" rows="3" placeholder="Ex: Descrição da espécie..."></textarea>
            </div>

            <hr>

            <div class="mb-2">
              <label class="form-label fw-semibold">Atribuir ocorrências publicadas</label>
              <p class="small text-muted mb-2">Selecione as ocorrências já publicadas no mapa que pertencem a esta espécie.</p>

              <div v-if="carregandoOcorrencias" class="text-center py-3">
                <div class="spinner-border spinner-border-sm text-success" role="status"></div>
                <small class="text-muted ms-2">Carregando ocorrências publicadas...</small>
              </div>

              <div v-else-if="ocorrenciasPublicadas.length > 0" class="border rounded p-2" style="max-height: 220px; overflow-y: auto;">
                <label
                  v-for="ocorrencia in ocorrenciasPublicadas"
                  :key="ocorrencia.id"
                  class="d-block border rounded p-2 mb-2"
                >
                  <input
                    type="checkbox"
                    class="form-check-input me-2"
                    :value="ocorrencia.id"
                    v-model="ocorrenciasSelecionadas"
                  >
                  <span class="fw-semibold">{{ ocorrencia.tipo_animal || 'Animal não informado' }}</span>
                  <small class="d-block text-muted">{{ ocorrencia.ponto_referencia || 'Local não informado' }}</small>
                  <small class="d-block text-muted">{{ new Date(ocorrencia.created_at).toLocaleDateString('pt-BR') }}</small>
                </label>
              </div>

              <div v-else class="alert alert-light border text-center py-2 mb-0">
                Nenhuma ocorrência publicada disponível para vínculo.
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" @click="modalNovaEspecie = false">Cancelar</button>
            <button type="button" class="btn btn-primary" @click="criarEspecie">Salvar</button>
          </div>
        </div>
      </div>
    </div>

    <!-- Backdrop dos Modais -->
    <div class="modal-backdrop fade" :class="{ show: modalNovaClasse || modalNovaEspecie || modalEditarClasse || modalEditarEspecie }" v-if="modalNovaClasse || modalNovaEspecie || modalEditarClasse || modalEditarEspecie"></div>
  </div>
</template>

<script>
import NavBar from '@/components/NavBar.vue';
import axios from 'axios';
import 'bootstrap/dist/css/bootstrap.min.css';
import 'bootstrap/dist/js/bootstrap.bundle.min.js';
import { getApiBaseUrl, resolveStorageUrl } from '@/utils/mediaUrl';
import EspecieMapaPreview from '@/components/especialista/EspecieMapaPreview.vue';

const API_BASE_URL = getApiBaseUrl();

export default {
  components: {
    NavBar,
    EspecieMapaPreview
  },
  data() {
    return {
      classeSelecionada: null,
      listaClasses: [],
      modalNovaClasse: false,
      modalNovaEspecie: false,
      modalEditarClasse: false,
      modalEditarEspecie: false,
      salvandoClasse: false,
      salvandoEspecie: false,
      carregandoOcorrencias: false,
      ocorrenciasPublicadas: [],
      ocorrenciasSelecionadas: [],
      formClasse: {
        nome_cientifico: '',
        nome_popular: '',
        foto: null
      },
      formEditarClasse: {
        id_classe: null,
        nome_cientifico: '',
        nome_popular: '',
        foto: null
      },
      formEditarEspecie: {
        id_especie: null,
        id_classe: null,
        nome_cientifico: '',
        nome_popular: '',
        descricao: '',
        foto: null
      },
      formEspecie: {
        nome_cientifico: '',
        nome_popular: '',
        foto: null,
        descricao: ''
      }
    }
  },
  computed: {
    eAdmin() {
      const tipo = localStorage.getItem('user_tipo');
      return tipo === 'Administrador';
    }
  },
  methods: {
    obterImagemUrlTratada(item) {
      const fallback = 'https://picsum.photos/seed/fauna/300/200';
      if (!item) return fallback;

      return resolveStorageUrl(item.foto || item.imagem, fallback);
    },

    async buscarDadosDoCatalogo() {
      try {
        const response = await axios.get(`${API_BASE_URL}/api/classes`);
        this.listaClasses = response.data;
      } catch (error) {
        console.error('Erro ao conectar com a API:', error);
      }
    },
    
    async selecionarClasse(classe) {
      try {
        const id = classe.id_classe || classe.id;
        const response = await axios.get(`${API_BASE_URL}/api/classes/${id}`);
        let dados = response.data;
        
        if (Array.isArray(dados)) { dados = dados[0] || {}; }
        if (!dados.especies) { dados.especies = []; }
        
        this.classeSelecionada = dados;
      } catch (error) {
        console.error('Erro ao carregar espécies:', error);
        this.classeSelecionada = { ...classe, especies: [] };
      }
    },
    
    handleFotoClasseChange(event) {
      this.formClasse.foto = event.target.files[0] || null;
    },

    handleFotoEditarClasseChange(event) {
      this.formEditarClasse.foto = event.target.files[0] || null;
    },
    
    handleFotoEspecieChange(event) {
      this.formEspecie.foto = event.target.files[0] || null;
    },
    
    async criarClasse() {
      try {
        if (!this.formClasse.nome_cientifico || !this.formClasse.nome_popular) {
          alert('Preencha todos os campos obrigatórios!');
          return;
        }
        
        const formData = new FormData();
        formData.append('nome_cientifico', this.formClasse.nome_cientifico);
        formData.append('nome_popular', this.formClasse.nome_popular);
        if (this.formClasse.foto) {
          formData.append('foto', this.formClasse.foto);
        }
        
        await axios.post(`${API_BASE_URL}/api/classes`, formData, {
          headers: { 'Content-Type': 'multipart/form-data' }
        });
        
        this.formClasse = { nome_cientifico: '', nome_popular: '', foto: null };
        this.modalNovaClasse = false;
        
        await this.buscarDadosDoCatalogo();
        alert('Classe criada com sucesso!');
      } catch (error) {
        console.error('Erro ao criar classe:', error);
        alert('Erro ao criar classe.');
      }
    },

    prepararEdicaoClasse(classe) {
      this.formEditarClasse.id_classe = classe.id_classe || classe.id;
      this.formEditarClasse.nome_cientifico = classe.nome_cientifico;
      this.formEditarClasse.nome_popular = classe.nome_popular;
      this.formEditarClasse.foto = null;
      this.modalEditarClasse = true;
    },

    async salvarEdicaoClasse() {
      try {
        this.salvandoClasse = true;
        const id = this.formEditarClasse.id_classe;

        const formData = new FormData();
        formData.append('_method', 'PUT');
        formData.append('nome_cientifico', this.formEditarClasse.nome_cientifico);
        formData.append('nome_popular', this.formEditarClasse.nome_popular);
        
        if (this.formEditarClasse.foto) {
          formData.append('foto', this.formEditarClasse.foto);
        }

        await axios.post(`${API_BASE_URL}/api/classes/${id}`, formData, {
          headers: { 'Content-Type': 'multipart/form-data' }
        });

        alert('Classe atualizada com sucesso!');
        this.modalEditarClasse = false;
        await this.buscarDadosDoCatalogo();

      } catch (error) {
        console.error('Erro ao editar classe:', error);
        alert('Erro ao atualizar a classe.');
      } finally {
        this.salvandoClasse = false;
      }
    },

    async excluirClasseEditando() {
      const id = this.formEditarClasse.id_classe;
      if (!id) return;

      if (!confirm('Excluir esta classe também removerá as espécies vinculadas. Deseja continuar?')) {
        return;
      }

      try {
        await axios.delete(`${API_BASE_URL}/api/classes/${id}`);
        this.modalEditarClasse = false;
        this.formEditarClasse = { id_classe: null, nome_cientifico: '', nome_popular: '', foto: null };
        this.classeSelecionada = null;
        await this.buscarDadosDoCatalogo();
        alert('Classe excluída com sucesso!');
      } catch (error) {
        console.error('Erro ao excluir classe:', error);
        alert('Erro ao excluir a classe.');
      }
    },

    prepararEdicaoEspecie(especie) {
      this.formEditarEspecie.id_especie = especie.id_especie;
      this.formEditarEspecie.id_classe = especie.id_classe || this.classeSelecionada?.id_classe || this.classeSelecionada?.id;
      this.formEditarEspecie.nome_cientifico = especie.nome_cientifico;
      this.formEditarEspecie.nome_popular = especie.nome_popular;
      this.formEditarEspecie.descricao = especie.descricao || '';
      this.formEditarEspecie.foto = null;
      this.modalEditarEspecie = true;
    },

    handleFotoEditarEspecieChange(event) {
      this.formEditarEspecie.foto = event.target.files[0] || null;
    },

    async salvarEdicaoEspecie() {
      try {
        this.salvandoEspecie = true;
        const id = this.formEditarEspecie.id_especie;

        const formData = new FormData();
        formData.append('_method', 'PUT');
        formData.append('id_classe', String(this.formEditarEspecie.id_classe));
        formData.append('nome_cientifico', this.formEditarEspecie.nome_cientifico);
        formData.append('nome_popular', this.formEditarEspecie.nome_popular);
        formData.append('descricao', this.formEditarEspecie.descricao || '');

        if (this.formEditarEspecie.foto) {
          formData.append('foto', this.formEditarEspecie.foto);
        }

        await axios.post(`${API_BASE_URL}/api/especies/${id}`, formData, {
          headers: { 'Content-Type': 'multipart/form-data' }
        });

        this.modalEditarEspecie = false;
        this.formEditarEspecie = {
          id_especie: null,
          id_classe: null,
          nome_cientifico: '',
          nome_popular: '',
          descricao: '',
          foto: null
        };

        if (this.classeSelecionada) {
          await this.selecionarClasse(this.classeSelecionada);
        }

        alert('Espécie atualizada com sucesso!');
      } catch (error) {
        console.error('Erro ao editar espécie:', error);
        alert('Erro ao atualizar a espécie.');
      } finally {
        this.salvandoEspecie = false;
      }
    },

    async confirmarExclusaoEspecie(especie) {
      if (!confirm(`Excluir a espécie "${especie.nome_popular}"? As ocorrências vinculadas permanecerão no sistema sem vínculo.`)) {
        return;
      }

      try {
        await axios.delete(`${API_BASE_URL}/api/especies/${especie.id_especie}`);

        if (this.classeSelecionada) {
          await this.selecionarClasse(this.classeSelecionada);
        }

        alert('Espécie excluída com sucesso!');
      } catch (error) {
        console.error('Erro ao excluir espécie:', error);
        alert('Erro ao excluir espécie.');
      }
    },
    
    async criarEspecie() {
      try {
        if (!this.classeSelecionada) { alert('Selecione uma classe primeiro!'); return; }
        const classeId = this.classeSelecionada.id_classe || this.classeSelecionada.id;
        
        if (!this.formEspecie.nome_cientifico || !this.formEspecie.nome_popular) {
          alert('Preencha todos os campos obrigatórios!');
          return;
        }
        
        const formData = new FormData();
        formData.append('id_classe', String(classeId));
        formData.append('nome_cientifico', this.formEspecie.nome_cientifico);
        formData.append('nome_popular', this.formEspecie.nome_popular);
        formData.append('descricao', this.formEspecie.descricao);
        if (this.formEspecie.foto) { formData.append('foto', this.formEspecie.foto); }
        
        const responseCriacao = await axios.post(`${API_BASE_URL}/api/especies`, formData, {
          headers: { 'Content-Type': 'multipart/form-data' }
        });

        const novaEspecieId = responseCriacao.data?.id_especie || responseCriacao.data?.id;

        if (novaEspecieId && this.ocorrenciasSelecionadas.length > 0) {
          await axios.post(`${API_BASE_URL}/api/especies/${novaEspecieId}/vincular-ocorrencias`, {
            ocorrencias_ids: this.ocorrenciasSelecionadas
          });
        }
        
        this.formEspecie = { nome_cientifico: '', nome_popular: '', foto: null, descricao: '' };
        this.ocorrenciasSelecionadas = [];
        this.modalNovaEspecie = false;
        
        const response = await axios.get(`${API_BASE_URL}/api/classes/${classeId}`);
        const dados = response.data;
        this.classeSelecionada = dados.especies ? dados : { ...dados, especies: [] };
        
        alert('Espécie criada com sucesso!');
      } catch (error) {
        console.error('Erro ao criar espécie:', error);
        alert('Erro ao cadastrar nova espécie.');
      }
    },

    async buscarOcorrenciasPublicadas() {
      try {
        this.carregandoOcorrencias = true;
        const response = await axios.get(`${API_BASE_URL}/api/ocorrencias/publicados`);
        this.ocorrenciasPublicadas = response.data || [];
      } catch (error) {
        console.error('Erro ao carregar ocorrências publicadas:', error);
        this.ocorrenciasPublicadas = [];
      } finally {
        this.carregandoOcorrencias = false;
      }
    },

    IrParaDetalhes(id_especie) {
      this.$router.push({ name: 'catalogo-detalhe', params: { id: id_especie } });
    }
  },
  watch: {
    modalNovaEspecie(valor) {
      if (valor) {
        this.ocorrenciasSelecionadas = [];
        this.buscarOcorrenciasPublicadas();
      }
    }
  },
  mounted() {
    this.buscarDadosDoCatalogo();
  }
}
</script>

<style scoped>
.card {
  transition: transform 0.2s ease, box-shadow 0.2s ease;
}
.card:hover {
  transform: translateY(-4px);
  box-shadow: 0 8px 15px rgba(0,0,0,0.08) !important;
}
.btn-editar-cat {
  opacity: 0.6;
  z-index: 10;
  transition: opacity 0.2s ease, transform 0.2s ease;
}
.btn-editar-cat:hover {
  opacity: 1 !important;
  transform: scale(1.1);
}

.especie-card {
  position: relative;
}

.species-actions {
  position: absolute;
  top: 0.75rem;
  right: 0.75rem;
  z-index: 5;
}

.species-map-wrap {
  cursor: default;
}

.modal.show {
  display: block !important;
  background-color: rgba(0, 0, 0, 0.5);
}
.modal-backdrop {
  position: fixed;
  top: 0;
  left: 0;
  z-index: 1040;
  width: 100vw;
  height: 100vh;
  background-color: #000;
}
.modal-backdrop.show {
  opacity: 0.5;
}
</style>