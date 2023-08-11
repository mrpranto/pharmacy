
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

/*
* Users component
* */
import UsersListComponent from './components/users/UsersList.vue';
app.component('user-list-component', UsersListComponent);
import UserAction from './components/users/Action.vue';
app.component('user-action-component', UserAction);


import CategoryListComponent from './components/products/categories/CategoryList.vue';
app.component('category-list-component', CategoryListComponent);
import CategoryAction from './components/products/categories/Action.vue';
app.component('category-action-component', CategoryAction);


import AppTable from './components/core/AppTable.vue';
app.component('app-table-component', AppTable);
import Counter from './components/core/Counter.vue';
app.component('app-table-counter-component', Counter);

import {showErrorMessage, showSuccessMessage} from './helper.js';
app.config.globalProperties.$showSuccessMessage = showSuccessMessage;
app.config.globalProperties.$showErrorMessage = showErrorMessage;
app.config.globalProperties.$general_setting = window._general_setting;

const trans = {
    methods: {
        /**
         * Translate the given key.
         */
        __(key, replace) {
            let translation, translationNotFound = true

            try {
                translation = key.split('.').reduce((t, i) => t[i] || null, window._translations[window._locale].php)

                if (translation) {
                    translationNotFound = false
                }
            } catch (e) {
                translation = key
            }

            if (translationNotFound) {
                translation = window._translations[window._locale]['json'][key]
                    ? window._translations[window._locale]['json'][key]
                    : key
            }

            _.forEach(replace, (value, key) => {
                translation = translation.replace(':' + key, value)
            })

            return translation
        }
    },
}

app.mixin(trans);

app.mount('#app');
