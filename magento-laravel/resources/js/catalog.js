import './bootstrap';
import { createApp } from 'vue';
import CategoryView from './CategoryView.vue';

const categoryViewApp = createApp(CategoryView);
console.log('categoryViewApp?', categoryViewApp);
categoryViewApp.mount('#catalog-container');