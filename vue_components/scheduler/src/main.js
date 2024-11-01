import './assets/main.css'
// Syncfusion Material Theme CSS
import '@syncfusion/ej2-base/styles/material.css';
import '@syncfusion/ej2-buttons/styles/material.css';
import '@syncfusion/ej2-calendars/styles/material.css';
import '@syncfusion/ej2-dropdowns/styles/material.css';
import '@syncfusion/ej2-inputs/styles/material.css';
import '@syncfusion/ej2-navigations/styles/material.css';
import '@syncfusion/ej2-popups/styles/material.css';
import '@syncfusion/ej2-vue-schedule/styles/material.css';

import { createApp } from 'vue'
import App from './App.vue'
import { SchedulePlugin } from '@syncfusion/ej2-vue-schedule';
const app = createApp(App);
app.use(SchedulePlugin);
app.mount('#app');
