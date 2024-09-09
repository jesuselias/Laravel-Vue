<!-- App.vue -->
<template>
    <div id="app">
      <h1>HUB de Moonhotels</h1>
      <SearchForm @search-result="handleSearchResult" />
      <ResultsList :results="results" />
    </div>
  </template>
  
  <script setup>
  import SearchForm from '../components/SearchForm.vue';
  import ResultsList from '../components/ResultsList.vue';
  import { ref } from 'vue';
  
  const results = ref([]);
  
  const handleSearchResult = (result) => {
    try {
      if (Array.isArray(result)) {
        results.value = result;
      } else {
        console.error('Resultado de búsqueda inválido');
      }
    } catch (error) {
      console.error('Error al procesar el resultado de búsqueda:', error);
    }
  };
  </script>
  