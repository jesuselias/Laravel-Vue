<template>
    <div>
      <h2>HUB Results</h2>
      <ul>
        <li v-for="result in hubStore.getResults" :key="result.roomId">
          Room ID: {{ result.roomId }}
          <ul>
            <li v-for="rate in result.rates" :key="rate.mealPlanId">
              Meal Plan: {{ rate.mealPlanId }}
              Price: €{{ rate.price.toFixed(2) }}
              Cancellable: {{ rate.isCancellable ? 'Yes' : 'No' }}
            </li>
          </ul>
        </li>
      </ul>
    </div>
  </template>
  
  <script setup>
  import { useHubStore } from '../stores/hubStore.js'
  
  const hubStore = useHubStore()
  </script>
  