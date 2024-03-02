import moment from "moment";
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
import UserName from './components/users/UserName.vue';
app.component('user-name-component', UserName);


import CategoryListComponent from './components/products/categories/CategoryList.vue';
app.component('category-list-component', CategoryListComponent);
import CategoryAction from './components/products/categories/Action.vue';
app.component('category-action-component', CategoryAction);


import CompanyListComponent from './components/products/companies/CompanyList.vue';
app.component('company-list-component', CompanyListComponent);
import CompanyAction from './components/products/companies/Action.vue';
app.component('company-action-component', CompanyAction);

import UnitListComponent from './components/products/units/UnitList.vue';
app.component('unit-list-component', UnitListComponent);
import UnitAction from './components/products/units/Action.vue';
app.component('unit-action-component', UnitAction);

import AttributeListComponent from './components/products/attributes/AttributeList.vue';
app.component('attribute-list-component', AttributeListComponent);
import AttributeAction from './components/products/attributes/Action.vue';
app.component('attribute-action-component', AttributeAction);

import ProductListComponent from './components/products/products/ProductList.vue';
app.component('product-list-component', ProductListComponent);
import ProductNameComponent from './components/products/products/ProductName.vue';
app.component('product-name-component', ProductNameComponent);
import ProductAction from './components/products/products/Action.vue';
app.component('product-action-component', ProductAction);

/*
* Peoples
* */

import SupplierListComponent from './components/people/supplier/SupplierList.vue';
app.component('supplier-list-component', SupplierListComponent);
import SupplierAction from './components/people/supplier/Action.vue';
app.component('supplier-action-component', SupplierAction);
import SupplierCompany from './components/people/supplier/SupplierCompany.vue';
app.component('supplier-company-component', SupplierCompany);


import CustomerListComponent from './components/people/customer/CustomerList.vue';
app.component('customer-list-component', CustomerListComponent);
import CustomerAction from './components/people/customer/Action.vue';
app.component('customer-action-component', CustomerAction);

/*
* Purchase components
*/
import AddNewPurchaseComponent from './components/purchases/AddNewPurchase.vue';
app.component('add-new-purchase-component', AddNewPurchaseComponent);
import EditPurchaseComponent from './components/purchases/EditPurchase.vue';
app.component('edit-purchase-component', EditPurchaseComponent);
import PurchaseListComponent from './components/purchases/PurchaseList.vue';
app.component('purchase-list-component', PurchaseListComponent);
import PurchaseActionComponent from './components/purchases/Action.vue';
app.component('purchase-action-component', PurchaseActionComponent);

/*
* Stock component
*/
import StockComponent from './components/stocks/StockList.vue';
app.component('stock-component', StockComponent);


/*
* Sale component
*/
import AddNewSaleComponent from './components/sales/AddNewSale.vue';
app.component('add-new-sale-component', AddNewSaleComponent);
import EditSaleComponent from './components/sales/EditSale.vue';
app.component('edit-sale-component', EditSaleComponent);
import SaleListComponent from './components/sales/SaleList.vue';
app.component('sale-list-component', SaleListComponent);
import SalesActionComponent from './components/sales/Action.vue';
app.component('sale-action-component', SalesActionComponent);


/*
* Expenses
*/
import ExpensesListComponent from './components/expenses/ExpensesList.vue';
app.component('expenses-list-component', ExpensesListComponent);
import ExpensesActionComponent from './components/expenses/Action.vue';
app.component('expenses-action-component', ExpensesActionComponent);
import ExpensesItemDetailsComponent from './components/expenses/ItemDetails.vue';
app.component('expenses-items-component', ExpensesItemDetailsComponent);

/*
* Reports
*/
import PurchaseReportComponent from './components/reports/Purchase.vue';
app.component('purchase-report-component', PurchaseReportComponent);
import SalesReportComponent from './components/reports/Sales.vue';
app.component('sales-report-component', SalesReportComponent);
import PaymentReportComponent from './components/reports/Payment.vue';
app.component('payment-report-component', PaymentReportComponent);

/*
* App core component
*/
import AppTable from './components/core/AppTable.vue';
app.component('app-table-component', AppTable);
import Counter from './components/core/Counter.vue';
app.component('app-table-counter-component', Counter);

app.config.globalProperties.$general_setting = window._general_setting;
app.config.globalProperties.$notification_setting = window._notification_setting;
app.config.globalProperties.$system_setting = window._system_setting;
import {
    attributeCombine,
    date_format,
    showCurrency,
    showErrorMessage,
    showSuccessMessage,
    time_format
} from './helper.js';
app.config.globalProperties.$showSuccessMessage = showSuccessMessage;
app.config.globalProperties.$showErrorMessage = showErrorMessage;
app.config.globalProperties.$notification_position = window._notification_setting?.notification_show_position;
app.config.globalProperties.$notification_sound = window._notification_setting?.notification_sound;
app.config.globalProperties.$pos_design = window._system_setting?.pos_design;
app.config.globalProperties.$variant = window._system_setting?.variant;
app.config.globalProperties.$opening_stock = window._system_setting?.opening_stock;
app.config.globalProperties.$date_format = date_format;
app.config.globalProperties.$time_format = time_format;
app.config.globalProperties.$currency_symbol = window._general_setting?.currency_symbol;
app.config.globalProperties.$currency_symbol_position = window._general_setting?.currency_symbol_position;
app.config.globalProperties.$showCurrency = showCurrency;
app.config.globalProperties.$today = moment().format("YYYY-MM-DD");
app.config.globalProperties.$today_time = moment().format("YYYY-MM-DD HH:mm:ss");
app.config.globalProperties.$attributeCombine = attributeCombine;

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
