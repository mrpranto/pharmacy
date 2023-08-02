
import { createApp } from 'vue';


const app = createApp({});

import ExampleComponent from './components/ExampleComponent.vue';
app.component('example-component', ExampleComponent);

import RoleListComponent from './components/roles/RoleList.vue';
app.component('role-list-component', RoleListComponent);

import AppTable from './components/core/AppTable.vue';
app.component('app-table-component', AppTable);


app.mount('#app');
