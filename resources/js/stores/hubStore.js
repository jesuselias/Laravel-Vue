// resources/js/stores/hubStore.js
import { defineStore } from 'pinia'

export const useHubStore = defineStore({
  id: 'hub',
  state: () => ({
    results: []
  }),
  getters: {
    getResults: (state) => state.results,
  },
  actions: {
    setSearchResults(results) {
      this.results = results;
    },

    // Otras acciones que necesites
    addResult(result) {
      this.results.push(result);
    },

    removeResult(index) {
      this.results.splice(index, 1);
    },

    // Ejemplo de acción asíncrona
    async fetchResults() {
      // Simulando una API call
      await new Promise(resolve => setTimeout(resolve, 2000));
      
      // Actualizando el estado
      this.setSearchResults([
        { roomId: 1, rates: [{ mealPlanId: 'MP1', price: 10.99, isCancellable: true }] },
        { roomId: 2, rates: [{ mealPlanId: 'MP2', price: 15.50, isCancellable: false }] },
      ]);
    }
  }
})
