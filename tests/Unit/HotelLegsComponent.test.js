import { mount } from '@vue/test-utils';
import HotelLegsComponent from '../../resources/js/components/HotelLegsComponent.vue';
import localVue from '../setupJest';

describe('HotelLegsComponent', () => {
  it('renders correctly', () => {
    const wrapper = mount(HotelLegsComponent, {
      localVue,
    });
    expect(wrapper.element).toMatchSnapshot();
  });
});