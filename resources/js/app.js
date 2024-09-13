// resources/js/app.js
import { createApp } from 'vue';
import App from './components/App.vue';
import HubComponent from './components/HubComponent.vue';
import { useStore } from './stores/HubStore';

const app = createApp({
    components: {
        App,
        HubComponent
    },
    mounted() {
        console.log('App loaded successfully');
    }
});

// Usa el store de una manera diferente
app.config.globalProperties.$HubStore = useStore;

// Importe Pinia dinámicamente
import('pinia').then(({ createPinia }) => {
    const pinia = createPinia();
    app.use(pinia);
    app.mount('#app');
});