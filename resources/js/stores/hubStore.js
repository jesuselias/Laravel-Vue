import { defineStore } from 'pinia'

export const useStore = defineStore('hub', {
  state: () => ({
    results: [],
    searchParams: {
      hotelId: '',
      checkIn: '',
      checkOut: '',
      numberOfGuests: 1,
      numberOfRooms: 1,
      currency: 'EUR'
    },
    showResults: false,
    currentPage: 1,
    itemsPerPage: 2,
    totalPages: 0
  }),
  getters: {
    getResults: (state) => state.results,
    getSearchParams: (state) => state.searchParams,
    getShowResults: (state) => state.showResults,
    getCurrentPage: (state) => state.currentPage,
    getItemsPerPage: (state) => state.itemsPerPage,
    getTotalPages: (state) => state.totalPages
  },
  actions: {
    setSearchResults(results) {
      this.results = results
    },
    setSearchParams(params) {
      this.searchParams = { ...this.searchParams, ...params }
    },
    setShowResults(value) {
      this.showResults = value
    },
    setCurrentPage(page) {
      this.currentPage = page
    },
    setItemsPerPage(items) {
      this.itemsPerPage = items
    },
    setTotalPages(pages) {
      this.totalPages = pages
    },
    clearResults() {
      this.results = []
      this.showResults = false
    }
  }
})

