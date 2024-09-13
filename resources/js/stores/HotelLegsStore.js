import { defineStore } from 'pinia'

export const useStore = defineStore('hotelLegs', {
  state: () => ({
    searchParams: {
      hotel: '',
      checkInDate: '',
      numberOfNights: 1,
      guests: 1,
      rooms: 1,
      currency: 'EUR'
    },
    processedData: [],
    showResults: false,
    currentPage: 1,
    itemsPerPage: 5,
    totalPages: 0
  }),
  getters: {
    getSearchParams: (state) => state.searchParams,
    getProcessedData: (state) => state.processedData,
    getShowResults: (state) => state.showResults,
    getCurrentPage: (state) => state.currentPage,
    getItemsPerPage: (state) => state.itemsPerPage,
    getTotalPages: (state) => state.totalPages
  },
  actions: {
    setSearchParams(params) {
      this.searchParams = { ...this.searchParams, ...params }
    },
    setShowResults(value) {
        this.showResults = value
      },
    setProcessedData(data) {
      this.processedData = data
      this.showResults = true
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
      this.processedData = []
      this.showResults = false
    }
  }
})