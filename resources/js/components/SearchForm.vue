<!-- components/SearchForm.vue -->
<template>
    <form @submit.prevent="handleSubmit">
      <input type="text" v-model="searchParams.hotelId" placeholder="ID del hotel" required>
      <input type="date" v-model="searchParams.checkIn" required>
      <input type="date" v-model="searchParams.checkOut" required>
      <input type="number" v-model="searchParams.numberOfGuests" min="1" max="10" required>
      <input type="number" v-model="searchParams.numberOfRooms" min="1" max="10" required>
      <select v-model="searchParams.currency">
        <option value="EUR">EUR</option>
        <option value="USD">USD</option>
        <option value="GBP">GBP</option>
      </select>
      <button type="submit">Buscar</button>
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
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify(searchParams.value),
      })
      
      if (!response.ok) {
        const errorText = await response.text()
        throw new Error(`HTTP error! status: ${response.status}, text: ${errorText}`)
      }
      
      const result = await response.json()
      console.log('Resultado de búsqueda:', result)
    } catch (error) {
      console.error('Error al procesar el resultado de búsqueda:', error.message)
      // Aquí podrías mostrar un mensaje de error al usuario
      alert('Ocurrió un error al buscar. Por favor, inténtalo de nuevo.')
    }
  }
  </script>
  