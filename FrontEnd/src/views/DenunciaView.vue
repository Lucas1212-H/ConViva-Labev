<template>
  <div class="Wizard-Container">
    <PassoInicial
    v-if="PassoAtual == 1" 
    @Proximo="avançarPasso1"
    />
    <PassoDetalhes
    v-if="PassoAtual == 2"
    @Proximo="avançarPasso2"
    @Anterior="voltarPasso1"
    />
    <PassoConfirmacao
    v-if="PassoAtual == 3"
    @Anterior="finalizarFormulario"
    />
  </div>
</template>

<script setup>

import { ref } from 'vue';
import PassoInicial from '../components/Denuncia/PassoInicial.vue';
import PassoDetalhes from '../components/Denuncia/PassoDetalhes.vue';
import PassoConfirmacao from '../components/Denuncia/PassoConfirmacao.vue';

const passAtual = ref(1);

const formData = reactive({
  categoria: '',
  tipoAnimal: '',
  situacao: '',
  descricao: '',
  localizacao: '',
  referencia: '',
  fotos: []
})

const avancarPasso1 = (CategoriaSelecionada => {
  formData.categoria = CategoriaSelecionada;
  passAtual.value = 2;
})

const avancarPasso = (dadosDetalhes) => {
  formData.tipoAnimal = dadosDetalhes.tipoAnimal;
  formData.situacao = dadosDetalhes.situacao;
  formData.descricao = dadosDetalhes.descricao;
  formData.localizacao = dadosDetalhes.localizacao;
  formData.referencia = dadosDetalhes.referencia;
  formData.fotos = dadosDetalhes.fotos;
  passAtual.value = 3;
}

const formularioFinalizar = (dadosLocalizacao) => {
  formData.localizacao = dadosLocalizacao.localizacao;
  formData.referencia = dadosLocalizacao.referencia;

  console.log('Dados do formulário de denuncia:', formData);
  alert('Ocorrencia registada com sucesso!')
}

<script/>