<template>
  <div id="app">
    <div class="title">
      <h2>Moonhotels</h2>
    </div>
    <HubComponent />
    <HotelLegsComponent />
  </div>
</template>

<script setup>
import HubComponent from './HubComponent.vue';
import HotelLegsComponent from './HotelLegsComponent.vue';
</script>

<style scoped>
#app {
  max-width: 100%; 
  margin: 0 auto;
}

.title {
  text-align: center;
}

h2 {
  font-size: 27px; 
  color: #333; 
}

/* Agregamos un poco de espacio vertical entre los componentes */
#app > * + * {
  margin-top: 7px;
}

</style>