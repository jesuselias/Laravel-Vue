describe('Hotel Legs Component', () => {
  beforeEach(() => {
    cy.visit('http://localhost:8000/')
  })

  it('renders correctly', () => {
    cy.get('#hotel-legs-component').should('be.visible')
    cy.get('.title-container h1').should('contain', 'HOTELLEGS')
    cy.get('.search-form').should('be.visible')
  })

  it('handles form input', () => {
    cy.get('#hotel-legs-component .search-form input[type="text"][placeholder="ID del hotel"]').type('TestHotel')
    cy.get('#hotel-legs-component .search-form input[type="date"]').type('2024-09-13')
    cy.get('#hotel-legs-component .search-form input[type="number"][min="1"][max="365"]').clear().type('5')
    cy.get('#hotel-legs-component .search-form input[type="number"][min="1"][max="10"]:first').clear().type('2')
    cy.get('#hotel-legs-component .search-form input[type="number"][min="1"][max="10"]:last').clear().type('3')
    cy.get('#hotel-legs-component .search-form select').select('EUR')
  })

  it('submits form', () => {
    cy.get('#hotel-legs-component .form-button button[type="submit"]').click()
  })

  it('componente se carga correctamente', () => {
    cy.get('#hotel-legs-component').should('be.visible')
    cy.get('.title-container h1').should('contain', 'HOTELLEGS')
    cy.get('.search-form').should('be.visible')
  })

  it('formulario tiene campos correctos', () => {
    cy.get('#hotel-legs-component .search-form input[type="text"][placeholder="ID del hotel"]').should('be.visible')
    cy.get('#hotel-legs-component .search-form input[type="date"]').should('be.visible')
    cy.get('#hotel-legs-component .search-form input[type="number"][min="1"][max="365"]').should('be.visible')
    cy.get('#hotel-legs-component .search-form input[type="number"][min="1"][max="10"]').should('have.length', 2)
    cy.get('#hotel-legs-component .search-form select').should('be.visible')
  })

  it('botón de submit existe', () => {
    cy.get('#hotel-legs-component .form-button button[type="submit"]').should('be.visible')
  })
  
})