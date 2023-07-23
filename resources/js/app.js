import { createApp } from 'vue';
import BarcodeScanner from './components/ExampleComponent.vue';

const app = createApp({});
app.component('BarcodeScanner', BarcodeScanner);

app.mount('#app');
