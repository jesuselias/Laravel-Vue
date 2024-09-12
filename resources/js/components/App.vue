<template>
  <div id="app">
    <div class="title">
      <h1>Moonhotels</h1>
    </div>
    <HubComponent />
  </div>
</template>

<script setup>
import HubComponent from './HubComponent.vue';
</script>

<style scoped>
#app {
  max-width: 100%; /* Ajusta según tus preferencias */
  margin: 0 auto;
}

.title {
  text-align: center;
}

h1 {
  font-size: 36px; /* Ajusta según tus preferencias */
  color: #333; /* Ajusta según tus preferencias */
}
</style>