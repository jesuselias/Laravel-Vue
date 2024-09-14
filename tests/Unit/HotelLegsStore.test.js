const HotelLegsStore = require('../../resources/js/stores/HotelLegsStore');

console.log('Ejecutando la prueba...');
console.log('HotelLegsStore:sdsd', HotelLegsStore);

describe('HotelLegsStore', () => {
  it('existe y es un objeto', () => {
    console.log('Probando HotelLegsStore...');
    
    expect(HotelLegsStore).toBeDefined();
  });

  it('tiene una función useStore definida', () => {
    expect(typeof HotelLegsStore.useStore).toBe('function');
  });

  it('useStore es una función', () => {
    expect(typeof HotelLegsStore.useStore).toBe('function');
  });

  it('tiene una propiedad $id en la función useStore', () => {
    expect(HotelLegsStore.useStore.$id).toBe('hotelLegs');
  });
});