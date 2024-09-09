// resources/js/app.js
import { createApp } from 'vue';
import App from './components/App.vue';
import HubComponent from './components/HubComponent.vue';
import { useHubStore } from './stores/hubStore.js';

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
app.config.globalProperties.$hubStore = useHubStore;

app.mount('#app');
