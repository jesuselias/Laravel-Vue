<template>
  <div id="hub-component">
    <div class="title-container">
      <h1>HUB</h1>
    </div>
    <form @submit.prevent="handleSubmit" class="search-form">
      <div class="form-group">
        <label for="hotelId">Hotel ID:</label>
        <input v-model="HubStore.getSearchParams.hotelId" type="text" placeholder="ID del hotel" required>
        <label for="checkIn">Date from:</label>
        <input v-model="HubStore.getSearchParams.checkIn" type="date" required>
        <label for="checkOut">Date to:</label>
        <input v-model="HubStore.getSearchParams.checkOut" type="date" required>
        <label for="guests">number of guests:</label>
        <input v-model.number="HubStore.getSearchParams.numberOfGuests" type="number" min="1" max="10" required>
        <label for="rooms">number of rooms:</label>
        <input v-model.number="HubStore.getSearchParams.numberOfRooms" type="number" min="1" max="10" required>
        <label for="currency">Currency:</label>
        <select v-model="HubStore.getSearchParams.currency">
          <option value="EUR">EUR</option>
          <option value="USD">USD</option>
          <option value="GBP">GBP</option>
        </select>
        <div class="form-button">
          <button type="submit">Search</button>
        </div>
      </div>
    </form>
    <div v-if="HubStore.getShowResults" class="results-container">
      <h2>HUB Results</h2>
      <h2>Rooms:</h2>
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
          <tr v-for="(room, index) in paginatedResults" :key="index">
            <td>{{ room.roomId }}</td>
            <td>{{ room.mealPlanId }}</td>
            <td>{{ room.price }}</td>
            <td>{{ room.isCancellable }}</td>
          </tr>
        </tbody>
      </table>
      <div class="pagination-controls">
        <button @click="prevPage" :disabled="HubStore.getCurrentPage === 1">&laquo;</button>
        <span>{{ HubStore.getCurrentPage }} / {{ HubStore.getTotalPages }}</span>
        <button @click="nextPage" :disabled="HubStore.getCurrentPage === HubStore.getTotalPages">&raquo;</button>
      </div>
    </div>
    <div v-else class="no-results">
      <p>No hay resultados disponibles.</p>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import axios from 'axios'
import { useStore } from '../stores/HubStore'

const HubStore = useStore()

const handleSubmit = async () => {
  console.log('Estado actual del store Hub:', HubStore.$state);
  console.log('Formulario enviado:', HubStore.getSearchParams)

  try {
    const response = await axios.post('http://localhost:8000/api/hub/search', HubStore.getSearchParams)
    
    if (response.data.success) {
      console.log('Resultado de búsqueda:', response.data.data)
      
      // Procesa los datos
      const processedData = response.data.data.rooms.map(room => ({
        roomId: room.roomId,
        mealPlanId: room.rates[0]?.mealPlanId || 'N/A',
        price: room.rates[0]?.price?.toFixed(2) || 'N/A',
        isCancellable: room.rates[0]?.isCancellable === undefined ? 'N/A' : room.rates[0].isCancellable ? 'Yes' : 'No'
      }))
      
      HubStore.setSearchResults(processedData)
      HubStore.setShowResults(true)
      
      // Actualice las páginas
      const totalPages = Math.ceil(processedData.length / HubStore.getItemsPerPage)
      HubStore.setTotalPages(totalPages)
    } else {
      throw new Error(`Error en la búsqueda: ${response.data.message}`)
    }
  } catch (error) {
    console.error('Error al procesar el resultado de búsqueda:', error.message)
    alert('Ocurrió un error al buscar. Por favor, inténtalo de nuevo.')
  }
}

const paginatedResults = computed(() => {
  const start = (HubStore.getCurrentPage - 1) * HubStore.getItemsPerPage
  const end = start + HubStore.getItemsPerPage
  return HubStore.getResults.slice(start, end)
})

const prevPage = () => {
  HubStore.setCurrentPage(HubStore.getCurrentPage - 1)
}

const nextPage = () => {
  HubStore.setCurrentPage(HubStore.getCurrentPage + 1)
}
</script>

<style scoped>
#hub-component {
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
  width: 8%;
}

.form-group input, .form-group select {
  width: 9%;
  height: 25px;
}

.form-group input:focus, .form-group select:focus {
  outline: none;
  border-color: #007bff;
  box-shadow: 0 0 0 2px rgba(0,123,255,.2);
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

.results-list {
  list-style-type: none;
  padding-left: 0;
}

.result-item {
  margin-bottom: 15px;
  padding: 10px;
  border: 1px solid #ddd;
  border-radius: 5px;
}

.rate-item {
  margin-bottom: 5px;
}

.no-results {
  text-align: center;
  margin-top: 20px;
}

.pagination-controls {
  margin-top: 20px;
  text-align: center;
}
.pagination-controls button {
  margin: 0 5px;
}
</style>