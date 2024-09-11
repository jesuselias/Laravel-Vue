<template>
  <form @submit.prevent="handleSubmit" class="search-form">
    <div class="form-group">
      <label for="hotelId">ID del hotel</label>
      <input v-model="searchParams.hotelId" type="text" placeholder="ID del hotel" required>
      <label for="checkIn">Fecha de entrada</label>
      <input v-model="searchParams.checkIn" type="date" required>
      <label for="checkOut">Fecha de salida</label>
      <input v-model="searchParams.checkOut" type="date" required>
      <label for="guests">Número de huéspedes</label>
      <input v-model.number="searchParams.numberOfGuests" type="number" min="1" max="10" required>
      <label for="rooms">Número de habitaciones</label>
      <input v-model.number="searchParams.numberOfRooms" type="number" min="1" max="10" required>
      <label for="currency">Moneda</label>
      <select v-model="searchParams.currency">
        <option value="EUR">EUR</option>
        <option value="USD">USD</option>
        <option value="GBP">GBP</option>
      </select>
      <div class="form-button">
        <button type="submit">Buscar</button>
    </div>
    </div>
  </form>
</template>

<script setup>
import { ref } from 'vue'

const searchParams = ref({
  hotelId: '',
  checkIn: '',
  checkOut: '',
  numberOfGuests: 1,
  numberOfRooms: 1,
  currency: 'EUR'
})

const handleSubmit = async (e) => {
  e.preventDefault()
  console.log('Formulario enviado:', searchParams.value)

  try {
    const response = await fetch('/api/search', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(searchParams.value)
    })

    if (!response.ok) {
      throw new Error(`HTTP error! status: ${response.status}`)
    }

    const result = await response.json()
    console.log('Resultado de búsqueda:', result)
  } catch (error) {
    console.error('Error al procesar el resultado de búsqueda:', error.message)
    alert('Ocurrió un error al buscar. Por favor, inténtalo de nuevo.')
  }
}
</script>

<style scoped>
.search-form {
  max-width: 100%;
  margin: 0 auto;
  padding: 20px;
  border: 1px solid #ddd;
  border-radius: 5px;
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
  margin-top: 7px; /* Moverá el botón hacia la parte inferior */
  margin-right: auto; /* Alineará el botón hacia la derecha */
}

button[type="submit"]:hover {
  background-color: #0056b3;
}
</style>
