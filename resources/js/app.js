import './bootstrap';

import { createApp } from 'vue'
import EmployeeList from './components/EmployeeList.vue'

const app = createApp({})
app.component('employee-list', EmployeeList)
app.mount('#app')
