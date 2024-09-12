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
  max-width: 100%; 
  margin: 0 auto;
}

.title {
  text-align: center;
}

h1 {
  font-size: 36px; 
  color: #333; 
}
</style>