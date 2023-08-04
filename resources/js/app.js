
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
