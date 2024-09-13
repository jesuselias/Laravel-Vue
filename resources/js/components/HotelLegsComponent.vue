<template>
  <div id="hotel-legs-component">
    <div class="title-container">
      <h1>HOTELLEGS</h1>
    </div>
    <form @submit.prevent="handleSubmit" class="search-form">
      <div class="form-group">
        <label for="hotel">Hotel:</label>
        <input v-model="store.getSearchParams.hotel" type="text" placeholder="ID del hotel" required>
        <label for="checkInDate">Check-in Date:</label>
        <input v-model="store.getSearchParams.checkInDate" type="date" required>
        <label for="numberOfNights">Number of Nights:</label>
        <input v-model.number="store.getSearchParams.numberOfNights" type="number" min="1" max="365" required>
        <label for="guests">Number of Guests:</label>
        <input v-model.number="store.getSearchParams.guests" type="number" min="1" max="10" required>
        <label for="rooms">Maximum Number of Rooms:</label>
        <input v-model.number="store.getSearchParams.rooms" type="number" min="1" max="10" required>
        <label for="currency">Currency:</label>
        <select v-model="store.getSearchParams.currency">
          <option value="EUR">EUR</option>
          <option value="USD">USD</option>
          <option value="GBP">GBP</option>
        </select>
        <div class="form-button">
          <button type="submit">Search</button>
        </div>
      </div>
    </form>
    <div v-if="store.getShowResults" class="results-container">
      <h2>HOTEL LEGS RESULTS</h2>
      <table class="results-table">
        <thead>
          <tr>
            <th>Room ID</th>
            <th>Meal Plan</th>
            <th>Price</th>
            <th>Cancellable</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(item, index) in paginatedResults" :key="index">
            <td>{{ item.room }}</td>
            <td>{{ item.meal }}</td>
            <td>{{ typeof item.price === 'string' ? item.price : item.price.toFixed(2) }}</td>
            <td>{{ item.isCancellable }}</td>
          </tr>
        </tbody>
      </table>
      <div class="pagination-controls">
        <button @click="prevPage" :disabled="store.getCurrentPage === 1">&laquo;</button>
        <span>{{ store.getCurrentPage }} / {{ store.getTotalPages }}</span>
        <button @click="nextPage" :disabled="store.getCurrentPage === store.getTotalPages">&raquo;</button>
      </div>
    </div>
    <div v-else class="no-results">
      <p>No hay resultados disponibles.</p>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue'
import axios from 'axios'
import { useStore } from '../stores/HotelLegsStore'

const store = useStore()

const handleSubmit = async () => {
  console.log('Estado actual del store HotelLeg:', store.$state);
  console.log('Formulario enviado:', store.getSearchParams)

  try {
    const response = await axios.post('http://localhost:8000/api/hotel-legs/search', store.getSearchParams)
    console.log("response", response)

    if (response.data.success) {
      console.log('Resultado de búsqueda:', response.data)
      
      const processedDataArray = [];
      response.data.data.results.forEach(item => {
        const room = item.room;
        const meal = item.meal || 'N/A';
        const price = item.price?.toFixed(2) || 'N/A';
        const isCancellable = item.canCancel === undefined ? 'N/A' : item.canCancel ? 'Yes' : 'No';
        
        processedDataArray.push({
          room,
          meal,
          price,
          isCancellable
        });
      });
      
      store.setProcessedData(processedDataArray)
      store.setShowResults(true)
      store.setTotalPages(Math.ceil(processedDataArray.length / store.getItemsPerPage))
    } else {
      throw new Error(`Error en la búsqueda: ${response.data.message}`)
    }
  } catch (error) {
    console.error('Error al procesar el resultado de búsqueda:', error.message)
    alert('Ocurrió un error al buscar. Por favor, inténtalo de nuevo.')
  }
}

const paginatedResults = computed(() => {
  const start = (store.getCurrentPage - 1) * store.getItemsPerPage
  const end = start + store.getItemsPerPage
  return store.getProcessedData.slice(start, end)
})

const prevPage = () => {
  store.setCurrentPage(store.getCurrentPage - 1)
}

const nextPage = () => {
  store.setCurrentPage(store.getCurrentPage + 1)
}
</script>
  
  <style scoped>
  #hotel-legs-component {
    max-width: 90%;
    margin: 0 auto;
    padding: 7px;
    border: 1px solid #ddd;
    border-radius: 5px;
  }
  
  .title-container {
    display: flex;
    justify-content: left;
  }
  
  h1 {
    font-size: 24px;
    color: #333;
  }
  
  .search-form {
    margin-bottom: 20px;
  }
  
  .form-group {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
  }
  
  .form-group label {
    width: 15%;
  }
  
  .form-group input, .form-group select {
    width: 25%;
    height: 25px;
  }
  
  .form-button {
    width: 50%;
    height: 30%;
  }
  
  button[type="submit"] {
    background-color: #007bff;
    color: white;
    padding: 8px 16px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    transition: background-color 0.3s ease;
    margin-top: 7px;
    margin-right: auto;
  }
  
  button[type="submit"]:hover {
    background-color: #0056b3;
  }
  
  .results-container {
    margin-top: 20px;
  }
  
  .table-wrapper {
    display: flex;
    justify-content: flex-start;
    width: 10%;
  }
  
  .results-table {
    display: inline-flex;
    flex-direction: column;
    width: 80%;
    border-collapse: separate;
    border-spacing: 0 2ch;
    border: 1px solid #000000;
    margin-left: 10%;
  
  }
  
  .results-table thead tr,
  .results-table tbody tr {
    display: flex;
    justify-content: flex-start;
    align-items: center;
    width: 100%;
    border-bottom: 1px solid #ccc; 
  }
  
  .results-table th,
  .results-table td {
    width: 25%; 
    text-align: center;
    border-right: 1px solid #ccc;
  }
  
  .no-results {
    text-align: center;
    margin-top: 20px;
    border: 1px solid #ccc; 
    padding: 10px;
    border-radius: 4px;
  }
  
  .pagination-controls {
    margin-top: 20px;
    text-align: center;
  }
  .pagination-controls button {
    margin: 0 5px;
  }
  </style>