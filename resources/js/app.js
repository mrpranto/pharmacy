
import { createApp } from 'vue';


const app = createApp({});

import ExampleComponent from './components/ExampleComponent.vue';
app.component('example-component', ExampleComponent);

import RoleListComponent from './components/roles/RoleList.vue';
app.component('role-list-component', RoleListComponent);
import DescriptionComponent from './components/roles/DescriptionComponent.vue';
app.component('role-description-component', DescriptionComponent);
import Action from './components/roles/Action.vue';
app.component('role-action-component', Action);
import PermissionComponent from './components/roles/PermissionComponent.vue';
app.component('role-permission-component', PermissionComponent);

import AppTable from './components/core/AppTable.vue';
app.component('app-table-component', AppTable);


app.mount('#app');
