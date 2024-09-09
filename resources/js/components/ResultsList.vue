<template>
    <ul v-if="results.length">
      <li v-for="result in results" :key="result.id">
        {{ result.name }} ({{ result.rating }}/5)
      </li>
    </ul>
    <p v-else>No hay resultados disponibles.</p>
  </template>
  
  <script setup>
  defineProps(['results']);
  </script>
  
  