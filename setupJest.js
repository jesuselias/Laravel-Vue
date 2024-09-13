import { createLocalVue, shallowMount } from '@vue/test-utils';
import VueRouter from 'vue-router';

const localVue = createLocalVue();
localVue.use(VueRouter);
localVue.config.productionTip = false;

export default localVue;