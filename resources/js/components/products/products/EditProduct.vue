<template>
    <div>
        <a-drawer
            :title="__('default.edit_product')"
            :width="720"
            :open="formState.openEdit"
            :body-style="{ paddingBottom: '80px' }"
            :footer-style="{ textAlign: 'right' }"
            @close="$parent.onEditClose"
        >
            <a-form v-bind="formState.layout">

                <a-form-item :label="__('default.name')" required>
                    <a-input v-model:value="formState.formData.name" required=""
                             :placeholder="__('default.name')"
                             @keyup="generateBarcode"
                             :class="formState.validation.name ? 'ant-input ant-input-status-error': ''"/>
                    <div class="ant-form-item-explain-error" style="" v-if="formState.validation.name">{{
                            formState.validation.name[0]
                        }}
                    </div>
                </a-form-item>

                <a-form-item :label="__('default.barcode')" required>
                    <a-input v-model:value="formState.formData.barcode"
                             required="" :placeholder="__('default.barcode')"
                             :class="formState.validation.barcode ? 'ant-input ant-input-status-error': ''">
                        <div class="ant-form-item-explain-error" style="" v-if="formState.validation.barcode">{{
                                formState.validation.barcode[0]
                            }}
                        </div>
                        <template #suffix>
                            <a-tooltip title="Generate new barcode" @click="$parent.generateBarcode" placement="leftBottom">
                                <sync-outlined style="color: rgba(0, 0, 0, 0.45)"/>
                            </a-tooltip>
                        </template>
                    </a-input>
                </a-form-item>

                <a-form-item :label="__('default.category')" required>
                    <a-input-group compact
                                   :class="formState.validation.category ? 'ant-input ant-input-status-error': ''">
                        <a-select
                            v-model:value="formState.formData.category" style="width: calc(100% - 45px)"
                            show-search
                            :placeholder="__('default.category')"
                            :options="formState.dependencies.categories"
                            :filter-option="$parent.selectFilterOption"
                        ></a-select>
                        <a-tooltip :title="__('default.add_new_category')" placement="leftBottom">
                            <a-button @click="showAddNewCategoryModal">
                                <i class="mdi mdi-plus"></i>
                            </a-button>
                        </a-tooltip>
                    </a-input-group>
                    <div class="ant-form-item-explain-error" style="" v-if="formState.validation.category">
                        {{ formState.validation.category[0] }}
                    </div>
                </a-form-item>

                <a-form-item :label="__('default.company')" required>
                    <a-input-group compact
                                   :class="formState.validation.company ? 'ant-input ant-input-status-error': ''">
                        <a-select
                            v-model:value="formState.formData.company" style="width: calc(100% - 45px)"
                            show-search
                            :placeholder="__('default.company')"
                            :options="formState.dependencies.companies"
                            :filter-option="$parent.selectFilterOption"
                        ></a-select>
                        <a-tooltip :title="__('default.add_new_company')" placement="leftBottom">
                            <a-button @click="showAddNewCompanyModal">
                                <i class="mdi mdi-plus"></i>
                            </a-button>
                        </a-tooltip>
                    </a-input-group>
                    <div class="ant-form-item-explain-error" style="" v-if="formState.validation.company">
                        {{ formState.validation.company[0] }}
                    </div>
                </a-form-item>

                <a-form-item :label="__('default.unit')" required>
                    <a-input-group compact :class="formState.validation.unit ? 'ant-input ant-input-status-error': ''">
                        <a-select
                            v-model:value="formState.formData.unit" style="width: calc(100% - 45px)"
                            show-search
                            :placeholder="__('default.unit')"
                            :options="formState.dependencies.units"
                            :filter-option="$parent.selectFilterOption"
                        ></a-select>
                        <a-tooltip :title="__('default.add_new_unit')" placement="leftBottom">
                            <a-button @click="showAddNewUnitModal">
                                <i class="mdi mdi-plus"></i>
                            </a-button>
                        </a-tooltip>
                    </a-input-group>
                    <div class="ant-form-item-explain-error" style="" v-if="formState.validation.unit">
                        {{ formState.validation.unit[0] }}
                    </div>
                </a-form-item>

                <a-form-item :label="__('default.purchase_type')" required>
                    <a-radio-group v-model:value="formState.formData.purchase_type"
                                   :class="formState.validation.purchase_type ? 'ant-input ant-input-status-error': ''">
                        <a-radio value="$"> Direct Price ({{ $currency_symbol }})</a-radio>
                        <a-radio value="%"> Percentage (%)</a-radio>
                    </a-radio-group>
                    <div class="ant-form-item-explain-error" style="" v-if="formState.validation.purchase_type">
                        {{ formState.validation.purchase_type[0] }}
                    </div>
                </a-form-item>

                <a-form-item :label="__('default.status')" required>
                    <a-switch v-model:checked="formState.formData.status"/>
                </a-form-item>

                <a-form-item :name="['description']" :label="__('default.description')">
                    <a-textarea v-model:value="formState.formData.description"
                                :placeholder="__('default.description')"
                                :class="formState.validation.description ? 'ant-input ant-input-status-error': ''"/>
                    <div class="ant-form-item-explain-error" style="" v-if="formState.validation.description">
                        {{ formState.validation.description[0] }}
                    </div>
                </a-form-item>

                <a-form-item :label="__('default.image')">
                    <div class="form-group">
                        <input type="file" ref="file"
                               @change="productImage"
                               class="file-upload-default"
                               accept="image/*"
                               id="cropperImageUpload">
                        <div class="input-group col-xs-12">
                            <input type="text"
                                   class="form-control file-upload-info border-left-radius"
                                   :value="imageFileName"
                                   disabled=""
                                   placeholder="Upload Image">
                            <span class="input-group-append">
                              <button class="file-upload-browse btn btn-primary border-right-radius"
                                      @click="$refs.file.click()"
                                      type="button">
                                  <i class="mdi mdi-upload"></i> Upload
                              </button>
                            </span>
                        </div>
                    </div>

                    <img v-if="previewURL" :src="previewURL" class="img-thumbnail" alt="Selected Image"
                         style="width: 300px; height: 150px;">
                </a-form-item>

                <template v-if="$variant === 'yes'">
                    <a-form-item :label="__('default.attributes')">
                        <a-input-group compact :class="formState.validation.attributes ? 'ant-input ant-input-status-error': ''">
                            <a-select
                                v-model:value="formState.formData.attributes" style="width: 100%"
                                show-search
                                mode="multiple"
                                :placeholder="__('default.attributes')"
                                :options="formState.dependencies.attributes"
                                :filter-option="$parent.selectFilterOption"
                                @change="loadAttributeItems"
                            ></a-select>
                        </a-input-group>
                        <div class="ant-form-item-explain-error" style="" v-if="formState.validation.attributes">
                            {{ formState.validation.attributes[0] }}
                        </div>
                    </a-form-item>

                    <template v-for="(attributeItem, attributeItemIndex) in attributeItems">
                        <a-form-item :label="attributeItem.label">
                            <a-checkbox-group>
                                <template v-for="itemDetail in attributeItem.details">
                                    <div class="form-check ml-5">
                                        <label class="form-check-label cursor-pointer">
                                            <input type="checkbox" class="form-check-input"
                                                   :value="itemDetail.name"
                                                   :checked="isChecked(itemDetail.name)"
                                                   @change="addAttributeItems(attributeItem.label, itemDetail.name)">
                                            {{ itemDetail.name }}
                                            <i class="input-frame"></i></label>
                                    </div>
                                </template>
                            </a-checkbox-group>
                        </a-form-item>
                    </template>
                </template>

            </a-form>
            <template #footer>
                <a-button type="primary" danger style="margin-right: 8px" @click="$parent.onEditClose">
                    <i class="mdi mdi-window-close"></i> {{ __('default.close') }}
                </a-button>
                <a-button v-if="loading" type="primary" style="margin-right: 8px" loading>
                    {{ __('default.loading') }}
                </a-button>
                <a-button v-else type="primary" style="margin-right: 8px" @click.prevent="updateProduct">
                    <i class="mdi mdi-check-all mr-1"></i> {{ __('default.update') }}
                </a-button>
            </template>
        </a-drawer>


        <!--Add New Category-->
        <a-modal v-model:open="openAddNewCategory" :title="__('default.add_new_category')" @ok="saveCategory">
            <a-form v-bind="categoryFormData.layout" class="mt-4">
                <a-form-item :label="__('default.name')" required>
                    <a-input v-model:value="categoryFormData.formData.name" required=""
                             :placeholder="__('default.name')"
                             :class="categoryFormData.validation.name ? 'ant-input ant-input-status-error': ''"/>
                    <div class="ant-form-item-explain-error" style="" v-if="categoryFormData.validation.name">{{
                            categoryFormData.validation.name[0]
                        }}
                    </div>
                </a-form-item>

                <a-form-item :name="['description']" :label="__('default.description')">
                    <a-textarea v-model:value="categoryFormData.formData.description"
                                :placeholder="__('default.description')"
                                :class="categoryFormData.validation.description ? 'ant-input ant-input-status-error': ''"/>
                    <div class="ant-form-item-explain-error" style="" v-if="categoryFormData.validation.description">
                        {{ categoryFormData.validation.description[0] }}
                    </div>
                </a-form-item>
            </a-form>

            <template #footer>
                <a-button key="back" @click="closeAddNewCategory">{{ __('default.close') }}</a-button>
                <a-button v-if="categoryFormData.loading" type="primary" style="margin-right: 8px" loading>
                    {{ __('default.loading') }}
                </a-button>
                <a-button v-else key="submit" type="primary" :loading="loading" @click="saveCategory">
                    <i class="mdi mdi-content-save mr-1"></i> {{ __('default.save') }}
                </a-button>
            </template>
        </a-modal>

        <!--Add New Company-->
        <a-modal v-model:open="openAddNewCompany" :title="__('default.add_new_company')" @ok="saveCompany">
            <a-form v-bind="companyFormData.layout" class="mt-4">
                <a-form-item :label="__('default.name')" required>
                    <a-input v-model:value="companyFormData.formData.name" required=""
                             :placeholder="__('default.name')"
                             :class="companyFormData.validation.name ? 'ant-input ant-input-status-error': ''"/>
                    <div class="ant-form-item-explain-error" style="" v-if="companyFormData.validation.name">{{
                            companyFormData.validation.name[0]
                        }}
                    </div>
                </a-form-item>

                <a-form-item :name="['description']" :label="__('default.description')">
                    <a-textarea v-model:value="companyFormData.formData.description"
                                :placeholder="__('default.description')"
                                :class="companyFormData.validation.description ? 'ant-input ant-input-status-error': ''"/>
                    <div class="ant-form-item-explain-error" style="" v-if="companyFormData.validation.description">
                        {{ companyFormData.validation.description[0] }}
                    </div>
                </a-form-item>
            </a-form>

            <template #footer>
                <a-button key="back" @click="closeAddNewCompany">{{ __('default.close') }}</a-button>
                <a-button v-if="companyFormData.loading" type="primary" style="margin-right: 8px" loading>
                    {{ __('default.loading') }}
                </a-button>
                <a-button v-else key="submit" type="primary" :loading="loading" @click="saveCompany">
                    <i class="mdi mdi-content-save mr-1"></i> {{ __('default.save') }}
                </a-button>
            </template>
        </a-modal>

        <!--Add New Unit-->
        <a-modal v-model:open="openAddNewUnit" :title="__('default.add_new_unit')" @ok="saveUnit">
            <a-form v-bind="unitFormData.layout" class="mt-4">
                <a-form-item :label="__('default.name')" required>
                    <a-input v-model:value="unitFormData.formData.name" required=""
                             :placeholder="__('default.name')"
                             :class="unitFormData.validation.name ? 'ant-input ant-input-status-error': ''"/>
                    <div class="ant-form-item-explain-error" style="" v-if="unitFormData.validation.name">{{
                            unitFormData.validation.name[0]
                        }}
                    </div>
                </a-form-item>

                <a-form-item :label="__('default.pack_size')" required>
                    <a-input v-model:value="unitFormData.formData.pack_size" required=""
                             :placeholder="__('default.pack_size')"
                             :class="unitFormData.validation.pack_size ? 'ant-input ant-input-status-error': ''"/>
                    <div class="ant-form-item-explain-error" style="" v-if="unitFormData.validation.pack_size">{{
                            unitFormData.validation.pack_size[0]
                        }}
                    </div>
                </a-form-item>

                <a-form-item :name="['description']" :label="__('default.description')">
                    <a-textarea v-model:value="unitFormData.formData.description"
                                :placeholder="__('default.description')"
                                :class="unitFormData.validation.description ? 'ant-input ant-input-status-error': ''"/>
                    <div class="ant-form-item-explain-error" style="" v-if="unitFormData.validation.description">
                        {{ unitFormData.validation.description[0] }}
                    </div>
                </a-form-item>
            </a-form>

            <template #footer>
                <a-button key="back" @click="closeAddNewUnit">{{ __('default.close') }}</a-button>
                <a-button v-if="unitFormData.loading" type="primary" style="margin-right: 8px" loading>
                    {{ __('default.loading') }}
                </a-button>
                <a-button v-else key="submit" type="primary" :loading="loading" @click="saveUnit">
                    <i class="mdi mdi-content-save mr-1"></i> {{ __('default.save') }}
                </a-button>
            </template>
        </a-modal>

    </div>
</template>
<script>
import {CloseCircleOutlined, SearchOutlined, SyncOutlined} from "@ant-design/icons-vue";

export default {
    name: "EditProduct",
    components: {CloseCircleOutlined, SearchOutlined, SyncOutlined},
    props: {
        formState: {
            type: Object,
            required: true
        }
    },
    data() {
        return {
            previewURL: '/images/medicine.png',
            imageFileName: '',
            loading: false,
            openAddNewCategory: false,
            openAddNewCompany: false,
            openAddNewUnit: false,
            categoryFormData: {
                loading: false,
                formData: {
                    name: '',
                    description: '',
                    status: true,
                },
                validation: {},
                layout: {
                    labelCol: {span: 6},
                    wrapperCol: {span: 18},
                }
            },
            companyFormData: {
                loading: false,
                formData: {
                    name: '',
                    description: '',
                    status: true,
                },
                validation: {},
                layout: {
                    labelCol: {span: 6},
                    wrapperCol: {span: 18},
                }
            },
            unitFormData: {
                loading: false,
                formData: {
                    name: '',
                    pack_size: '',
                    description: '',
                    status: true,
                },
                validation: {},
                layout: {
                    labelCol: {span: 6},
                    wrapperCol: {span: 18},
                }
            },
            formData: new FormData(),
            attributeItems: [],
            onlyAttributeItems: [],
            attributeObj: {}
        }
    },
    watch: {
        'formState.formData.image': function (){
            if (this.formState.formData.image){
                this.previewURL = this.formState.formData.image
                this.imageFileName = ''
            }else {
                this.previewURL = '/images/medicine.png'
                this.imageFileName = ''
            }
        },
        'formState.formData.attributes': function (){
            this.loadAttributeItems()
        }
    },
    mounted() {
        this.loadAttributeItems();
    },
    methods: {
        /*
        * Product Create functions
        * */
        async updateProduct() {
            this.loading = true;
            this.formData.append('name', this.formState.formData.name);
            this.formData.append('barcode', this.formState.formData.barcode);
            this.formData.append('category', this.formState.formData.category);
            this.formData.append('company', this.formState.formData.company);
            this.formData.append('unit', this.formState.formData.unit);
            this.formData.append('description', this.formState.formData.description);
            this.formData.append('status', this.formState.formData.status);
            this.formData.append('purchase_type', this.formState.formData.purchase_type);
            this.formData.append('attribute_items', JSON.stringify(this.formState.formData.attributeItems));
            this.formData.append("_method", "put");
            await axios.post('/product/products/'+this.formState.current_id, this.formData, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            })
                .then(response => {
                    if (response.data.success) {
                        this.formState.formData.name = '';
                        this.formState.formData.barcode = '';
                        this.formState.formData.category = '';
                        this.formState.formData.company = '';
                        this.formState.formData.unit = '';
                        this.formState.formData.description = '';
                        this.formState.formData.status = true;
                        this.formState.formData.purchase_type = '';
                        this.imageFileName = "";
                        this.previewURL = '/images/medicine.png';
                        this.formData = new FormData()
                        let current_path = this.formState.list_path+'?page=' + this.formState.current_list_url
                        this.$parent.getData(current_path)
                        this.$parent.onEditClose()
                        this.$showSuccessMessage(response.data.success, this.$notification_position, this.$notification_sound)
                        this.loading = false;
                    } else {
                        this.$showErrorMessage(response.data.error, this.$notification_position, this.$notification_sound)
                        this.loading = false;
                    }
                })
                .catch(err => {
                    if (err.response.status === 422) {
                        this.$showErrorMessage(err.response.data.message, this.$notification_position, this.$notification_sound)
                        this.formState.validation = err.response.data.errors
                        this.loading = false;
                    } else {
                        this.$showErrorMessage(err, this.$notification_position, this.$notification_sound)
                        console.error(err)
                        this.loading = false;
                    }
                })
        },
        generateBarcode(){
            const slug =  this.formState.formData.name
                .toLowerCase() // Convert the string to lowercase
                .replace(/[^\w\s-]/g, '') // Remove non-word characters
                .replace(/\s+/g, '_') // Replace spaces with a dash
                .replace(/--+/g, '_') // Replace multiple dashes with a single dash
                .trim();
            this.formState.formData.barcode = slug;
        },
        loadAttributeItems(){
            if (this.formState.formData.attributes.length > 0){
                this.attributeItems = this.formState.dependencies.attributes.filter(
                    item => this.formState.formData.attributes.some(
                        element => element === item.value
                    )
                );
            }else {
                this.attributeItems = []
            }
            this.attributeObj = this.formState.formData.attributeItems
        },
        isChecked(itemName){
            let onlyAttributeItems = []
            Object.keys(this.formState.formData.attributeItems).forEach(item => {
                this.formState.formData.attributeItems[item].forEach(attItem => onlyAttributeItems.push(attItem))
            })
            if (onlyAttributeItems.includes(itemName)){
                return true
            }else {
                return false
            }
        },

        addAttributeItems(attribute, attributeItem){
            this.attributeItems.forEach(key => {
                if (attribute === key.label){
                    if (this.attributeObj.hasOwnProperty(key.label)) {
                        const checkItemExists = this.attributeObj[key.label].find(attItem => attItem === attributeItem)
                        if (!checkItemExists){
                            this.attributeObj[key.label].push(attributeItem);
                        }else {
                            const attIndex = this.attributeObj[key.label].indexOf(attributeItem)
                            this.attributeObj[key.label].splice(attIndex, 1)
                        }
                    } else {
                        this.attributeObj[key.label] = [attributeItem];
                    }
                }

            })
            this.formState.formData.attributeItems = this.attributeObj;
        },

        /*
        * Category Create functions
        * */
        showAddNewCategoryModal() {
            this.categoryFormData.formData.name = ''
            this.categoryFormData.formData.description = ''
            this.categoryFormData.formData.status = true
            this.categoryFormData.validation = {}
            this.openAddNewCategory = true
        },
        closeAddNewCategory() {
            this.openAddNewCategory = false
        },
        async saveCategory() {
            this.categoryFormData.loading = true;
            await axios.post('/product/categories', this.categoryFormData.formData)
                .then(response => {
                    if (response.data.success) {
                        this.categoryFormData.formData.name = ''
                        this.categoryFormData.formData.description = ''
                        this.categoryFormData.formData.status = true
                        this.closeAddNewCategory()
                        this.$parent.getDependency('addCategory')
                        this.$showSuccessMessage(response.data.success, this.$notification_position, this.$notification_sound)
                        this.categoryFormData.loading = false;
                    } else {
                        this.$showErrorMessage(response.data.error, this.$notification_position, this.$notification_sound)
                        this.categoryFormData.loading = false;
                    }
                })
                .catch(err => {
                    if (err.response.status === 422) {
                        this.$showErrorMessage('Category : '+ err.response.data.message, this.$notification_position, this.$notification_sound)
                        this.categoryFormData.validation = err.response.data.errors
                        this.categoryFormData.loading = false;
                    } else {
                        this.$showErrorMessage(err, this.$notification_position, this.$notification_sound)
                        console.error(err)
                        this.categoryFormData.loading = false;
                    }
                })
        },

        /*
        * Company Create functions
        * */
        showAddNewCompanyModal() {
            this.companyFormData.formData.name = ''
            this.companyFormData.formData.description = ''
            this.companyFormData.formData.status = true
            this.companyFormData.validation = {}
            this.openAddNewCompany = true
        },
        closeAddNewCompany() {
            this.openAddNewCompany = false
        },
        async saveCompany() {
            this.companyFormData.loading = true;
            await axios.post('/product/companies', this.companyFormData.formData)
                .then(response => {
                    if (response.data.success) {
                        this.companyFormData.formData.name = ''
                        this.companyFormData.formData.description = ''
                        this.companyFormData.formData.status = true
                        this.closeAddNewCompany()
                        this.$parent.getDependency('addCompany')
                        this.$showSuccessMessage(response.data.success, this.$notification_position, this.$notification_sound)
                        this.companyFormData.loading = true;
                    } else {
                        this.$showErrorMessage(response.data.error, this.$notification_position, this.$notification_sound)
                        this.companyFormData.loading = true;
                    }
                })
                .catch(err => {
                    if (err.response.status === 422) {
                        this.$showErrorMessage('Company : '+ err.response.data.message, this.$notification_position, this.$notification_sound)
                        this.companyFormData.validation = err.response.data.errors
                        this.companyFormData.loading = true;
                    } else {
                        this.$showErrorMessage(err, this.$notification_position, this.$notification_sound)
                        console.error(err)
                        this.companyFormData.loading = true;
                    }
                })
        },

        /*
        * Unit Create functions
        * */
        showAddNewUnitModal() {
            this.unitFormData.formData.name = ''
            this.unitFormData.formData.pack_size = ''
            this.unitFormData.formData.description = ''
            this.unitFormData.formData.status = true
            this.unitFormData.validation = {}
            this.openAddNewUnit = true
        },
        closeAddNewUnit() {
            this.openAddNewUnit = false
        },
        async saveUnit() {
            this.unitFormData.loading = true;
            await axios.post('/product/units', this.unitFormData.formData)
                .then(response => {
                    if (response.data.success) {
                        this.unitFormData.formData.name = ''
                        this.unitFormData.formData.pack_size = ''
                        this.unitFormData.formData.description = ''
                        this.unitFormData.formData.status = true
                        this.closeAddNewUnit()
                        this.$parent.getDependency('addUnit')
                        this.$showSuccessMessage(response.data.success, this.$notification_position, this.$notification_sound)
                        this.unitFormData.loading = false;
                    } else {
                        this.$showErrorMessage(response.data.error, this.$notification_position, this.$notification_sound)
                        this.unitFormData.loading = false;
                    }
                })
                .catch(err => {
                    if (err.response.status === 422) {
                        this.$showErrorMessage('Unit : '+ err.response.data.message, this.$notification_position, this.$notification_sound)
                        this.unitFormData.validation = err.response.data.errors
                        this.unitFormData.loading = false;
                    } else {
                        this.$showErrorMessage(err, this.$notification_position, this.$notification_sound)
                        console.error(err)
                        this.unitFormData.loading = false;
                    }
                })
        },

        productImage() {
            this.selectedImage = event.target.files[0];
            this.imageFileName = this.selectedImage?.name;
            this.formData.append('product_photo', event.target.files[0])

            // Create a FileReader to read the selected image and generate a preview URL
            let reader = new FileReader();

            reader.onload = () => {
                this.previewURL = reader.result; // Set the preview URL
            };

            if (this.selectedImage) {
                reader.readAsDataURL(this.selectedImage); // Read the selected image as a data URL
            } else {
                this.previewURL = '/images/medicine.png'; // Clear the preview if no image is selected
            }
        }
    },
}
</script>

<style scoped>
.border-left-radius {
    border-top-left-radius: 7px;
    border-bottom-left-radius: 7px;
}

.border-right-radius {
    border-top-right-radius: 7px;
    border-bottom-right-radius: 7px;
}
</style>
