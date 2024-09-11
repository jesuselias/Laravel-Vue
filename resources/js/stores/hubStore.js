import { defineStore } from 'pinia'

export const useHubStore = defineStore('hub', {
  state: () => ({
    results: []
  }),
  getters: {
    getResults: (state) => state.results,
  },
  actions: {
    setSearchResults(results) {
      this.results = results
    },

    async fetchResults(searchData) {
      try {
        const response = await fetch('/api/hub/search', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
          },
          body: JSON.stringify(searchData),
        })
        const data = await response.json()
        this.setSearchResults(data.rooms)
      } catch (error) {
        console.error('Error fetching results:', error)
        throw new Error('Failed to fetch results')
      }
    }
  }
})

