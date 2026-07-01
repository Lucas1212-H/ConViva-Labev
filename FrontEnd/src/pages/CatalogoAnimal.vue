<template>
  <div>
    <NavBar />

    <main class="container py-5">
      
      <section v-if="!categoriaSelecionada">
        <header class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center mb-4 gap-3">
          <h1 class="h3 fw-bold text-dark m-0">Catálogo de Categorias</h1>
          <button v-if="eAdmin" class="btn btn-success fw-medium px-4 w-100 w-sm-auto" @click="modalNovaCategoria = true">
            + Nova Categoria
          </button>
        </header>

        <div class="row row-cols-2 row-cols-md-4 g-4 justify-content-center">
          <div class="col" v-for="cat in listaCategorias" :key="cat.id_categoria">
            <article 
              class="card p-4 text-center shadow-sm h-100 border-0 rounded-3 position-relative"
              style="cursor: pointer;"
              @click="selecionarCategoria(cat)"
            >
              <button 
                v-if="eAdmin" 
                class="btn btn-sm btn-light border position-absolute top-0 end-0 m-2 rounded-circle btn-editar-cat"
                title="Editar Categoria"
                @click.stop="prepararEdicaoCategoria(cat)"
              >
                ✏️
              </button>

              <figure class="m-0">
                <img 
                  :src="obterImagemUrlTratada(cat)" 
                  :alt="cat.nome_popular"
                  class="img-fluid mb-2 object-fit-cover" 
                  style="max-height: 80px; min-height: 80px; width: auto;"
                >
                <figcaption class="h5 fw-bold text-dark m-0">{{ cat.nome_popular }}</figcaption>
                <small class="text-muted fst-italic">{{ cat.nome_cientifico }}</small>
              </figure>
            </article>
          </div>
        </div>
      </section>

      <section v-else>
        <header class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center mb-4 gap-3">
          <div class="w-100">
            <button class="btn btn-sm btn-light border px-3 mb-2" @click="categoriaSelecionada = null">
              ← Voltar
            </button>
            <h1 class="h3 fw-bold text-success m-0">Espécies em {{ categoriaSelecionada.nome_popular }}</h1>
          </div>
          
          <button v-if="eAdmin" class="btn btn-primary fw-medium px-4 w-100 w-sm-auto" @click="modalNovaEspecie = true">
            + Nova Espécie
          </button>
        </header>

        <div class="row row-cols-1 row-cols-md-2 row-cols-xl-3 g-4">
          <div class="col" v-for="especie in (categoriaSelecionada?.especies || [])" :key="especie.id_especie">
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

        <div v-if="(categoriaSelecionada?.especies || []).length === 0" class="alert alert-light text-center border mt-4">
          Nenhuma espécie cadastrada em {{ categoriaSelecionada?.nome_popular }} ainda.
        </div>
      </section>

    </main>

    <div class="modal" :class="{ show: modalNovaCategoria }" :style="{ display: modalNovaCategoria ? 'block' : 'none' }" tabindex="-1">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Nova Categoria</h5>
            <button type="button" class="btn-close" @click="modalNovaCategoria = false"></button>
          </div>
          <div class="modal-body">
            <div class="mb-3">
              <label class="form-label">Nome Científico</label>
              <input type="text" class="form-control" v-model="formCategoria.nome_cientifico" placeholder="Ex: Felidae">
            </div>
            <div class="mb-3">
              <label class="form-label">Nome Popular</label>
              <input type="text" class="form-control" v-model="formCategoria.nome_popular" placeholder="Ex: Felinos">
            </div>
            <div class="mb-3">
              <label class="form-label">Foto</label>
              <input type="file" class="form-control" @change="handleFotoCategoriaChange" accept="image/*">
              <small class="text-muted">Formatos aceitos: JPG, PNG, GIF, WebP</small>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" @click="modalNovaCategoria = false">Cancelar</button>
            <button type="button" class="btn btn-success" @click="criarCategoria">Salvar</button>
          </div>
        </div>
      </div>
    </div>

    <div class="modal" :class="{ show: modalEditarCategoria }" :style="{ display: modalEditarCategoria ? 'block' : 'none' }" tabindex="-1">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header bg-primary text-white">
            <h5 class="modal-title">Editar Categoria</h5>
            <button type="button" class="btn-close btn-close-white" @click="modalEditarCategoria = false"></button>
          </div>
          <div class="modal-body">
            <div class="mb-3">
              <label class="form-label">Nome Científico</label>
              <input type="text" class="form-control" v-model="formEditarCategoria.nome_cientifico">
            </div>
            <div class="mb-3">
              <label class="form-label">Nome Popular</label>
              <input type="text" class="form-control" v-model="formEditarCategoria.nome_popular">
            </div>
            <div class="mb-3">
              <label class="form-label">Substituir Foto</label>
              <input type="file" class="form-control" @change="handleFotoEditarCategoriaChange" accept="image/*">
              <small class="text-muted">Deixe em branco se preferir manter a imagem atual.</small>
            </div>
          </div>
          <div class="modal-footer justify-content-between flex-wrap gap-2">
            <button type="button" class="btn btn-outline-danger" @click="excluirCategoriaEditando">Excluir Categoria</button>
            <div class="d-flex gap-2 ms-auto">
              <button type="button" class="btn btn-secondary" @click="modalEditarCategoria = false">Cancelar</button>
              <button type="button" class="btn btn-primary" @click="salvarEdicaoCategoria" :disabled="salvandoCategoria">
                <span v-if="salvandoCategoria" class="spinner-border spinner-border-sm me-1"></span>
                Atualizar
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>

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
                <label class="form-label">Categoria</label>
                <select class="form-select" v-model="formEditarEspecie.id_categoria">
                  <option v-for="cat in listaCategorias" :key="cat.id_categoria" :value="cat.id_categoria">
                    {{ cat.nome_popular }}
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

    <div class="modal-backdrop fade" :class="{ show: modalNovaCategoria || modalNovaEspecie || modalEditarCategoria || modalEditarEspecie }" v-if="modalNovaCategoria || modalNovaEspecie || modalEditarCategoria || modalEditarEspecie"></div>
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
      categoriaSelecionada: null,
      listaCategorias: [],
      modalNovaCategoria: false,
      modalNovaEspecie: false,
      modalEditarCategoria: false, // Controle do modal de edição
      modalEditarEspecie: false,
      salvandoCategoria: false,
      salvandoEspecie: false,
      carregandoOcorrencias: false,
      ocorrenciasPublicadas: [],
      ocorrenciasSelecionadas: [],
      formCategoria: {
        nome_cientifico: '',
        nome_popular: '',
        foto: null
      },
      formEditarCategoria: { // Formulário isolado para a edição
        id_categoria: null,
        nome_cientifico: '',
        nome_popular: '',
        foto: null
      },
      formEditarEspecie: {
        id_especie: null,
        id_categoria: null,
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
    // 🛡️ Propriedade Computada para verificar o nível de acesso em tempo de execução
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
        const response = await axios.get(`${API_BASE_URL}/api/categorias`);
        this.listaCategorias = response.data;
      } catch (error) {
        console.error('Erro ao conectar com a API:', error);
      }
    },
    
    async selecionarCategoria(categoria) {
      try {
        const id = categoria.id_categoria || categoria.id;
        const response = await axios.get(`${API_BASE_URL}/api/categorias/${id}`);
        let dados = response.data;
        
        if (Array.isArray(dados)) { dados = dados[0] || {}; }
        if (!dados.especies) { dados.especies = []; }
        
        this.categoriaSelecionada = dados;
      } catch (error) {
        console.error('Erro ao carregar espécies:', error);
        this.categoriaSelecionada = { ...categoria, especies: [] };
      }
    },
    
    handleFotoCategoriaChange(event) {
      this.formCategoria.foto = event.target.files[0] || null;
    },

    handleFotoEditarCategoriaChange(event) {
      this.formEditarCategoria.foto = event.target.files[0] || null;
    },
    
    handleFotoEspecieChange(event) {
      this.formEspecie.foto = event.target.files[0] || null;
    },
    
    async criarCategoria() {
      try {
        if (!this.formCategoria.nome_cientifico || !this.formCategoria.nome_popular) {
          alert('Preencha todos os campos obrigatórios!');
          return;
        }
        
        const formData = new FormData();
        formData.append('nome_cientifico', this.formCategoria.nome_cientifico);
        formData.append('nome_popular', this.formCategoria.nome_popular);
        if (this.formCategoria.foto) {
          formData.append('foto', this.formCategoria.foto);
        }
        
        await axios.post(`${API_BASE_URL}/api/categorias`, formData, {
          headers: { 'Content-Type': 'multipart/form-data' }
        });
        
        this.formCategoria = { nome_cientifico: '', nome_popular: '', foto: null };
        this.modalNovaCategoria = false;
        
        await this.buscarDadosDoCatalogo();
        alert('Categoria criada com sucesso!');
      } catch (error) {
        console.error('Erro ao criar categoria:', error);
        alert('Erro ao criar categoria.');
      }
    },

    // ✏️ Prepara os inputs do modal com as informações atuais da categoria escolhida
    prepararEdicaoCategoria(categoria) {
      this.formEditarCategoria.id_categoria = categoria.id_categoria || categoria.id;
      this.formEditarCategoria.nome_cientifico = categoria.nome_cientifico;
      this.formEditarCategoria.nome_popular = categoria.nome_popular;
      this.formEditarCategoria.foto = null; // Reseta o input de arquivo
      this.modalEditarCategoria = true;
    },

    //  Envia a atualização da categoria para a API do Laravel
    async salvarEdicaoCategoria() {
      try {
        this.salvandoCategoria = true;
        const id = this.formEditarCategoria.id_categoria;

        const formData = new FormData();
        formData.append('_method', 'PUT'); // Truque essencial para o Laravel ler arquivos em rotas de atualização
        formData.append('nome_cientifico', this.formEditarCategoria.nome_cientifico);
        formData.append('nome_popular', this.formEditarCategoria.nome_popular);
        
        if (this.formEditarCategoria.foto) {
          formData.append('foto', this.formEditarCategoria.foto);
        }

        await axios.post(`${API_BASE_URL}/api/categorias/${id}`, formData, {
          headers: { 'Content-Type': 'multipart/form-data' }
        });

        alert('Categoria atualizada com sucesso!');
        this.modalEditarCategoria = false;
        await this.buscarDadosDoCatalogo(); // Atualiza a grade visual da aplicação

      } catch (error) {
        console.error('Erro ao editar categoria:', error);
        alert('Erro ao atualizar a categoria.');
      } finally {
        this.salvandoCategoria = false;
      }
    },

    async excluirCategoriaEditando() {
      const id = this.formEditarCategoria.id_categoria;
      if (!id) return;

      if (!confirm('Excluir esta categoria também removerá as espécies vinculadas. Deseja continuar?')) {
        return;
      }

      try {
        await axios.delete(`${API_BASE_URL}/api/categorias/${id}`);
        this.modalEditarCategoria = false;
        this.formEditarCategoria = { id_categoria: null, nome_cientifico: '', nome_popular: '', foto: null };
        this.categoriaSelecionada = null;
        await this.buscarDadosDoCatalogo();
        alert('Categoria excluída com sucesso!');
      } catch (error) {
        console.error('Erro ao excluir categoria:', error);
        alert('Erro ao excluir a categoria.');
      }
    },

    prepararEdicaoEspecie(especie) {
      this.formEditarEspecie.id_especie = especie.id_especie;
      this.formEditarEspecie.id_categoria = especie.id_categoria || this.categoriaSelecionada?.id_categoria || this.categoriaSelecionada?.id;
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
        formData.append('id_categoria', String(this.formEditarEspecie.id_categoria));
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
          id_categoria: null,
          nome_cientifico: '',
          nome_popular: '',
          descricao: '',
          foto: null
        };

        if (this.categoriaSelecionada) {
          await this.selecionarCategoria(this.categoriaSelecionada);
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

        if (this.categoriaSelecionada) {
          await this.selecionarCategoria(this.categoriaSelecionada);
        }

        alert('Espécie excluída com sucesso!');
      } catch (error) {
        console.error('Erro ao excluir espécie:', error);
        alert('Erro ao excluir espécie.');
      }
    },
    
    async criarEspecie() {
      try {
        if (!this.categoriaSelecionada) { alert('Selecione uma categoria primeiro!'); return; }
        const categoriaId = this.categoriaSelecionada.id_categoria || this.categoriaSelecionada.id;
        
        if (!this.formEspecie.nome_cientifico || !this.formEspecie.nome_popular) {
          alert('Preencha todos os campos obrigatórios!');
          return;
        }
        
        const formData = new FormData();
        formData.append('id_categoria', String(categoriaId));
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
        
        const response = await axios.get(`${API_BASE_URL}/api/categorias/${categoriaId}`);
        const dados = response.data;
        this.categoriaSelecionada = dados.especies ? dados : { ...dados, especies: [] };
        
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