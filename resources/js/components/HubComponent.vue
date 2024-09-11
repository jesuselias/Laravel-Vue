<template>
  <div class="container">
    <h1>HUB Results</h1>
    <ul v-if="hubStore.getResults.length > 0" class="results-list">
      <li v-for="result in hubStore.getResults" :key="result.roomId" class="result-item">
        <h2>Room {{ result.roomId }}</h2>
        <ul class="rates-list">
          <li v-for="rate in result.rates" :key="rate.mealPlanId" class="rate-item">
            <p><strong>Meal Plan:</strong> {{ rate.mealPlanId }}</p>
            <p><strong>Price:</strong> €{{ rate.price.toFixed(2) }}</p>
            <p><strong>Cancellable:</strong> {{ rate.isCancellable ? 'Yes' : 'No' }}</p>
          </li>
        </ul>
      </li>
    </ul>
    <p v-else>No hay resultados disponibles.</p>
  </div>
</template>

<script setup>
import { useHubStore } from '../stores/hubStore.js'

const hubStore = useHubStore()
</script>

<style scoped>
.container {
  max-width: 800px;
  margin: 0 auto;
  padding: 20px;
}

h1 {
  color: #333;
  font-size: 24px;
  margin-bottom: 20px;
}

.results-list {
  list-style-type: none;
  padding: 0;
}

.result-item {
  border: 1px solid #ddd;
  border-radius: 5px;
  margin-bottom: 20px;
  padding: 15px;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

h2 {
  color: #007bff;
  margin-top: 0;
}

.rates-list {
  list-style-type: none;
  padding: 0;
}

.rate-item {
  border-bottom: 1px solid #eee;
  padding-bottom: 10px;
}

.rate-item:last-child {
  border-bottom: none;
}

strong {
  color: #0066cc;
}

@media (max-width: 768px) {
  .container {
    padding: 10px;
  }

  h1 {
    font-size: 18px;
  }

  .result-item {
    padding: 10px;
  }
}
</style>
