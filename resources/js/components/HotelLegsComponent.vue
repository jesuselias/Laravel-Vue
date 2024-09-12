<template>
    <div id="hotel-legs-component">
      <div class="title-container">
        <h1>HOTELLEGS</h1>
      </div>
      <form @submit.prevent="handleSubmit" class="search-form">
        <div class="form-group">
          <label for="hotel">Hotel:</label>
          <input v-model="searchParams.hotel" type="text" placeholder="ID del hotel" required>
          <label for="checkInDate">Check-in Date:</label>
          <input v-model="searchParams.checkInDate" type="date" required>
          <label for="numberOfNights">Number of Nights:</label>
          <input v-model.number="searchParams.numberOfNights" type="number" min="1" max="365" required>
          <label for="guests">Number of Guests:</label>
          <input v-model.number="searchParams.guests" type="number" min="1" max="10" required>
          <label for="rooms">Maximum Number of Rooms:</label>
          <input v-model.number="searchParams.rooms" type="number" min="1" max="10" required>
          <label for="currency">Currency:</label>
          <select v-model="searchParams.currency">
            <option value="EUR">EUR</option>
            <option value="USD">USD</option>
            <option value="GBP">GBP</option>
          </select>
          <div class="form-button">
            <button type="submit">Search</button>
          </div>
        </div>
      </form>
      <div v-if="showResults" class="results-container">
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
          <tr v-for="(item, index) in processedData" :key="index">
            <td>{{ item.room }}</td>
            <td>{{ item.meal }}</td>
            <td>{{ typeof item.price === 'string' ? item.price : item.price.toFixed(2) }}</td>
            <td>{{ item.isCancellable }}</td>
          </tr>
        </tbody>
      </table>
        <div class="pagination-controls">
          <button @click="prevPage" :disabled="currentPage === 1">&laquo;</button>
          <span>{{ currentPage }} / {{ totalPages }}</span>
          <button @click="nextPage" :disabled="currentPage === totalPages">&raquo;</button>
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
  
  const searchParams = ref({
    hotel: '',
    checkInDate: '',
    numberOfNights: 1,
    guests: 1,
    rooms: 1,
    currency: 'EUR'
  })
  
  const currentPage = ref(1)
  const itemsPerPage = ref(5)
  const totalPages = computed(() => Math.ceil(processedData.value.length / itemsPerPage.value))
  
  const paginatedResults = computed(() => {
  if (!Array.isArray(processedData.value)) {
    return []
  }
  const start = (currentPage.value - 1) * itemsPerPage.value
  const end = start + itemsPerPage.value
  return processedData.value.slice(start, end)
})
  
  const prevPage = () => {
    if (currentPage.value > 1) {
      currentPage.value--
    }
  }
  
  const nextPage = () => {
    if (currentPage.value < totalPages.value) {
      currentPage.value++
    }
  }
  
  const processedData = ref([])
  
  const showResults = ref(false)
  
  const handleSubmit = async () => {
  console.log('Formulario enviado:', searchParams.value)

  try {
    const response = await axios.post('http://localhost:8000/api/hotel-legs/search', searchParams.value)
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
      
      processedData.value = processedDataArray;
      showResults.value = true
    } else {
      throw new Error(`Error en la búsqueda: ${response.data.message}`)
    }
  } catch (error) {
    console.error('Error al procesar el resultado de búsqueda:', error.message)
    alert('Ocurrió un error al buscar. Por favor, inténtalo de nuevo.')
  }

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