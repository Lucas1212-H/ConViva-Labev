<template>
  <div class="dashboard-page bg-light min-vh-100 d-flex flex-column">
    <NavBar :abaAtiva="abaAtiva" @mudarAba="mudarAba" />

    <div class="container-fluid specialist-content px-3 px-md-4 flex-grow-1 py-3 py-md-4">
      
      <div v-if="carregando" class="text-center py-5 my-auto">
        <div class="spinner-border text-success" role="status"></div>
        <p class="text-muted mt-2">Carregando informações...</p>
      </div>

      <div v-else class="flex-grow-1">
        <TriagemPainel 
          v-if="abaAtiva === 'triagem'"
          :denuncias="denunciasTriagem"
          :totalRegistros="totalRegistros"
          :pendentes="pendentesCount"
          :urgentes="urgentes"
          :especies="especies"
          @validar="handleValidar"
          @exportar="gerarLaudo"
          @gerenciarEspecies="handleGerenciarEspecies"
        />

        <HistoricoPainel 
          v-else-if="abaAtiva === 'arquivadas'"
          :arquivadas="denunciasArquivadas"
          :pendentes="denunciasPendentes"
          @selecionarHistorico="abrirArquivada"
          @selecionarPendente="abrirPendente"
          @alternarPublicacao="alternarPublicacaoArquivada"
        />

        <PublicadosPainel 
          v-else-if="abaAtiva === 'publicados'"
          :publicados="publicadosLista"
        />

        <AnalisesView
          v-else-if="abaAtiva === 'analises'"
        />
      </div>
    </div>

    <!-- Modal de Triagem (Validação) -->
    <ModalValidacao 
      :denuncia="selectedDenuncia" 
      @fechar="selectedDenuncia = null" 
      @aprovar="processarAprovacao"
      @alocar="handleAlocar"
      @arquivar="handleArquivar"
      @publicar="handlePublicar"
    />

    <!-- Modal de Denúncia Pendente (em atendimento) -->
    <ModalEmAnalise
      :denuncia="pendenteSelecionada"
      @fechar="pendenteSelecionada = null"
      @arquivar="handleArquivarComDados"
    />

    <!-- Modal de Denúncia Arquivada (visualização final) -->
    <ModalArquivada
      :denuncia="arquivadaSelecionada"
      @fechar="arquivadaSelecionada = null"
    />
  </div>
</template>

<script setup>
import { computed, ref, onMounted, watch } from 'vue';
import axios from 'axios';
import { useRoute, useRouter } from 'vue-router';
import NavBar from '@/components/NavBar.vue';
import ModalValidacao from '@/components/especialista/ModalValidação.vue';
import ModalEmAnalise from '@/components/especialista/ModalEmAnalise.vue';
import ModalArquivada from '@/components/especialista/ModalArquivada.vue';

// Importação das subpáginas estruturadas
import TriagemPainel from '@/pages/especialista/TriagemPainel.vue';
import HistoricoPainel from '@/pages/especialista/HistoricoPainel.vue';
import PublicadosPainel from '@/pages/especialista/PublicadosPainel.vue';
import AnalisesView from '@/pages/especialista/AnalisesView.vue';
import { exportarLaudoOcorrencia } from '@/utils/exportLaudo';
import { resolveStorageUrl } from '@/utils/mediaUrl';

const FALLBACK_OCORRENCIA_IMAGE = 'https://picsum.photos/seed/fauna/640/360';

// --- State ---
const selectedDenuncia = ref(null);    // modal triagem
const pendenteSelecionada = ref(null); // modal em análise (pendentes)
const arquivadaSelecionada = ref(null);// modal arquivada

const route = useRoute();
const router = useRouter();
const abasValidas = new Set(['triagem', 'arquivadas', 'publicados', 'analises']);
const normalizarAba = (valor) => (abasValidas.has(String(valor)) ? String(valor) : 'triagem');
const abaAtiva = ref(normalizarAba(route.query.aba));
const carregando = ref(true);

// Listas separadas
const denunciasTriagem = ref([]);      // ainda não alocadas (status Pendente)
const denunciasPendentes = ref([]);    // alocadas / em atendimento
const denunciasArquivadas = ref([]);   // finalizadas
const publicadosLista = ref([]);

const isLocal = window.location.hostname === 'localhost' || window.location.hostname === '127.0.0.1'
const API_BASE = isLocal ? 'http://localhost:8000/api/ocorrencias' : 'https://conviva-labev.onrender.com/api/ocorrencias';

// --- Helper para obter nome do usuário logado ---
const getCurrentUserName = () => {
  try {
    const stored = localStorage.getItem('usuarioLogado')
    if (!stored) return null
    const u = JSON.parse(stored)
    return u.nome || u.email || null
  } catch (e) {
    return null
  }
}

const buscarDadosDoBanco = async () => {
  try {
    carregando.value = true;
    const [resPendentes, resArquivados, resPublicados] = await Promise.all([
      axios.get(`${API_BASE}/pendentes`),
      axios.get(`${API_BASE}/arquivadas`).catch(() => ({ data: [] })),
      axios.get(`${API_BASE}/publicados`).catch(() => ({ data: [] }))
    ]);

    // Combina todas as denúncias para separá-las estritamente pelo status no frontend
    const todasPendentes = resPendentes.data || [];
    const todosArquivados = resArquivados.data || [];
    
    // Helper de mapeamento completo (cobre campos de ambas as origens)
    const mapearItem = (item) => ({
      id: item.id,
      animal: item.tipo_animal,
      tipo_animal: item.tipo_animal,
      titulo: `${item.distincao_biologica || 'Animal'} de ${item.tipo_animal || 'espécie não informada'}`,
      denunciante: item.denunciante_nome,
      denunciante_nome: item.denunciante_nome,
      tipo: item.distincao_biologica,
      distincao_biologica: item.distincao_biologica,
      distincaoBiologica: item.distincao_biologica,
      descricao: item.descricao,
      descricao_ocorrencia: item.descricao,
      imagem: resolveStorageUrl(item.foto_url || item.foto_path, FALLBACK_OCORRENCIA_IMAGE),
      foto_url: item.foto_url || null,
      video_url: item.video_url || null,
      local: item.ponto_referencia,
      data: new Date(item.created_at).toLocaleDateString('pt-BR'),
      created_at: item.created_at,
      situacao_animal: item.situacao_animal,
      status: item.status || item.situacao_animal,
      statusWorkflow: item.status,
      statusFinal: item.status,
      parecer_tecnico: item.parecer_tecnico,
      assigned: item.parecer_tecnico || item.registrado_por ? (item.registrado_por || 'Especialista') : null,
      
      // Localização
      latitude: item.latitude !== null ? parseFloat(item.latitude) : null,
      longitude: item.longitude !== null ? parseFloat(item.longitude) : null,
      descricao_local_exato: item.descricao_local_exato || null,
      ponto_referencia: item.ponto_referencia || null,
      coordenadas: { 
        lat: item.latitude !== null ? parseFloat(item.latitude) : null, 
        lng: item.longitude !== null ? parseFloat(item.longitude) : null 
      },
      
      // Campos técnicos
      habitat: item.habitat || null,
      microhabitat: item.microhabitat || null,
      classe: item.classe || null,
      ordem: item.ordem || null,
      familia: item.familia || null,
      especie: item.especie || null,
      que_coletou: item.que_coletou || null,
      destino: item.destino || null,
      registrado_por: item.registrado_por || null,
      
      // Novos campos
      classe_etaria: item.classe_etaria || null,
      tempo: item.tempo || null,
      campo_encontrado: item.campo_encontrado || null,
      observacoes: item.observacoes || null,
      codigo_registro: item.codigo_registro || `LABEV${String(item.id).padStart(2, '0')}`,
      
      // Processo Final para exibição nas arquivadas
      publicadoNoMapa: !!item.publicado_no_mapa,
      processoFinal: item.status === 'Publicado'
        ? (item.publicado_no_mapa ? 'Publicado no mapa' : 'Publicado fora do mapa')
        : (item.status === 'Resolvido' ? 'Resgate > Arquivamento' : 'Falso Alarme > Descarte'),
        
      historico: [
        { titulo: 'Denúncia recebida', data: new Date(item.created_at).toLocaleDateString('pt-BR'), descricao: item.descricao },
        { titulo: 'Finalizado', data: new Date(item.updated_at || item.created_at).toLocaleDateString('pt-BR'), descricao: item.parecer_tecnico || 'Sem parecer.' }
      ]
    });

    const todasDenunciasUnificadas = [...todasPendentes, ...todosArquivados].map(mapearItem);
    
    // Para evitar duplicatas caso a API retorne a mesma denúncia em ambas rotas
    const mapaUnicas = new Map();
    todasDenunciasUnificadas.forEach(item => mapaUnicas.set(item.id, item));
    const listaUnica = Array.from(mapaUnicas.values());

    // 1. Triagem: status Pendente (ainda não alocada)
    // Assumindo que qualquer coisa diferente dos status finais ou 'Em Atendimento' fica na triagem
    denunciasTriagem.value = listaUnica.filter(item => 
      item.status !== 'Em Atendimento' && 
      item.status !== 'Resolvido' && 
      item.status !== 'Falso Alarme' && 
      item.status !== 'Publicado'
    );

    // 2. Pendentes em atendimento: status 'Em Atendimento'
    denunciasPendentes.value = listaUnica.filter(item => 
      item.status === 'Em Atendimento'
    );

    // 3. Arquivadas: status finais (Resolvido, Falso Alarme, Publicado)
    denunciasArquivadas.value = listaUnica.filter(item => 
      item.status === 'Resolvido' || 
      item.status === 'Falso Alarme' || 
      item.status === 'Publicado'
    );

    publicadosLista.value = resPublicados.data.map(item => ({
      id: item.id,
      animal: item.tipo_animal,
      local: item.ponto_referencia,
      data: new Date(item.created_at).toLocaleDateString('pt-BR'),
      coordenadas: { lat: item.latitude !== null ? parseFloat(item.latitude) : null, lng: item.longitude !== null ? parseFloat(item.longitude) : null }
    }));
  } catch (error) {
    console.error("Erro na sincronização:", error);
  } finally {
    carregando.value = false;
  }
};

onMounted(() => { buscarDadosDoBanco(); });

// --- Métricas Globais ---
const totalRegistros = computed(() => denunciasTriagem.value.length + denunciasPendentes.value.length + denunciasArquivadas.value.length);
const pendentesCount = computed(() => denunciasTriagem.value.length);
const urgentes = computed(() => denunciasTriagem.value.filter(item => item.status === 'Morto' || item.status === 'Ferido').length);
const especies = computed(() => new Set([...denunciasTriagem.value, ...denunciasArquivadas.value].map(item => item.animal)).size);

const mudarAba = (aba) => {
  abaAtiva.value = aba;
  selectedDenuncia.value = null;
  pendenteSelecionada.value = null;
  arquivadaSelecionada.value = null;
  
  router.replace({
    name: 'specialist-area',
    query: { ...route.query, aba },
  });
};

watch(
  () => route.query.aba,
  (novaAba) => {
    abaAtiva.value = normalizarAba(novaAba);
  }
);

// --- Handlers de abertura de modais ---
const handleValidar = (d) => {
  pendenteSelecionada.value = null;
  arquivadaSelecionada.value = null;
  selectedDenuncia.value = d;
};

const abrirPendente = (item) => {
  selectedDenuncia.value = null;
  arquivadaSelecionada.value = null;
  pendenteSelecionada.value = item;
};

const abrirArquivada = (item) => {
  selectedDenuncia.value = null;
  pendenteSelecionada.value = null;
  arquivadaSelecionada.value = item;
};

// --- Ações de triagem ---
const processarAprovacao = async (dadosAprovacao) => {
  const denunciaAtual = selectedDenuncia.value;
  if (!denunciaAtual) return;
  try {
    await axios.put(`${API_BASE}/${denunciaAtual.id}/avaliar`, {
      status: 'Resolvido',
      parecer_tecnico: dadosAprovacao.parecer || 'Atendido pelo especialista.'
    });
    selectedDenuncia.value = null;
    alert('Ocorrência resolvida com sucesso!');
    buscarDadosDoBanco();
  } catch (error) {
    alert("Erro ao salvar parecer.");
  }
};

const handleAlocar = async ({ denunciaId }) => {
  try {
    // Muda status para 'Em Atendimento' no backend
    await axios.put(`${API_BASE}/${denunciaId}/avaliar`, {
      status: 'Em Atendimento',
      registrado_por: getCurrentUserName() || 'Especialista'
    });
    selectedDenuncia.value = null;
    alert('Denúncia alocada com sucesso! Acesse a aba "Denúncias" > "Pendentes" para preencher as informações técnicas.');
    // Muda automaticamente para a aba de denúncias e recarrega
    mudarAba('arquivadas');
    await buscarDadosDoBanco();
  } catch (error) {
    console.error(error);
    alert("Erro ao alocar a denúncia.");
  }
};

const handleArquivar = async ({ denunciaId }) => {
  try {
    await axios.put(`${API_BASE}/${denunciaId}/avaliar`, {
      status: 'Falso Alarme',
      parecer_tecnico: 'Falso alarme / Trote.'
    });
    selectedDenuncia.value = null;
    alert('Caso arquivado.');
    buscarDadosDoBanco();
  } catch (error) {
    alert("Erro ao arquivar.");
  }
};

const handlePublicar = async ({ denunciaId }) => {
  try {
    await axios.put(`${API_BASE}/${denunciaId}/publicar`, {
      status: 'Publicado'
    });
    selectedDenuncia.value = null;
    alert('Ocorrência publicada no mapa!');
    buscarDadosDoBanco();
  } catch (error) {
    alert("Erro ao publicar.");
    console.error(error);
  }
};

// --- Arquivar denúncia pendente com dados técnicos ---
const handleArquivarComDados = async (dados) => {
  const { denunciaId, ...camposTecnicos } = dados;
  try {
    await axios.put(`${API_BASE}/${denunciaId}/arquivar`, {
      status: 'Resolvido',
      parecer_tecnico: `Arquivado por ${camposTecnicos.registrado_por || 'Especialista'}.`,
      ...camposTecnicos
    });
    pendenteSelecionada.value = null;
    alert('Denúncia arquivada com sucesso!');
    buscarDadosDoBanco();
  } catch (error) {
    console.error(error);
    alert("Erro ao arquivar a denúncia.");
  }
};

// --- Ações de publicação nas arquivadas ---
const alternarPublicacaoArquivada = async (item) => {
  try {
    if (item.publicadoNoMapa) {
      await axios.put(`${API_BASE}/${item.id}/despublicar`)
      alert('Ocorrência removida do mapa!')
    } else {
      await axios.put(`${API_BASE}/${item.id}/publicar`, {
        status: 'Publicado'
      })
      alert('Ocorrência publicada no mapa!')
    }
    await buscarDadosDoBanco()
  } catch (error) {
    console.error(error)
    alert('Erro ao atualizar a publicação da ocorrência.')
  }
}

const gerarLaudo = (denuncia) => {
  const exportado = exportarLaudoOcorrencia(denuncia);
  if (!exportado) {
    alert('Não foi possível abrir a janela de exportação. Verifique se o bloqueador de pop-ups está desativado.');
  }
};
const handleGerenciarEspecies = () => console.log('Gerenciar Espécies');
</script>

<style scoped>
.specialist-content {
  width: 100%;
  max-width: 1440px;
  margin-inline: auto;
}
</style>