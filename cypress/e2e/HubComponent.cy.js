describe('HubComponent', () => {
  beforeEach(() => {
    cy.visit('http://localhost:8000/')
  })

  it('componente se carga correctamente', () => {
    cy.get('#hub-component').should('be.visible')
    cy.get('.title-container h1').should('contain', 'HUB')
    cy.get('.search-form').should('be.visible')
  })

  it('formulario tiene campos correctos', () => {
    cy.get('#hub-component .search-form input[type="text"][placeholder="ID del hotel"]').should('be.visible')
    cy.get('#hub-component .search-form input[type="date"]').should('be.visible')
    cy.get('#hub-component .search-form input[type="number"][min="1"][max="10"]').should('have.length', 2)
    cy.get('#hub-component .search-form select').should('be.visible')
  })

  it('botón de submit existe', () => {
    cy.get('#hub-component .form-button button[type="submit"]').should('be.visible')
  })

})