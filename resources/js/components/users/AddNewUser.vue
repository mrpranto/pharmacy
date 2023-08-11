<template>
    <div>
        <a-drawer
            :title="__('default.add_user')"
            :width="720"
            :open="formState.openCreate"
            :body-style="{ paddingBottom: '80px' }"
            :footer-style="{ textAlign: 'right' }"
            @close="$parent.onClose"
        >
            <a-form v-bind="formState.layout">
                <a-form-item :label="__('default.roles')" required>
                    <a-select
                        v-model:value="formState.formData.role_id"
                        :class="formState.validation.role_id ? 'ant-input ant-input-status-error': ''"
                        show-search
                        :placeholder="__('default.roles')"
                        :options="roles"
                        :filter-option="roleFilterOption"
                    ></a-select>
                    <div class="ant-form-item-explain-error" style="" v-if="formState.validation.role_id">{{
                            formState.validation.role_id[0]
                        }}
                    </div>
                </a-form-item>

                <a-form-item :label="__('default.name')" required>
                    <a-input v-model:value="formState.formData.name"
                             :class="formState.validation.name ? 'ant-input ant-input-status-error': ''"/>
                    <div class="ant-form-item-explain-error" style="" v-if="formState.validation.name">{{
                            formState.validation.name[0]
                        }}
                    </div>
                </a-form-item>

                <a-form-item :label="__('default.email')">
                    <a-input type="email" v-model:value="formState.formData.email"
                             :class="formState.validation.email ? 'ant-input ant-input-status-error': ''">
                        <template #prefix>
                            <UserOutlined style="color: rgba(0, 0, 0, 0.25)"/>
                        </template>
                    </a-input>
                    <div class="ant-form-item-explain-error" style="" v-if="formState.validation.email">{{
                            formState.validation.email[0]
                        }}
                    </div>
                </a-form-item>

                <a-form-item :label="__('default.phone_number')" required>
                    <a-input type="number" v-model:value="formState.formData.phone_number"
                             :class="formState.validation.phone_number ? 'ant-input ant-input-status-error': ''">
                        <template #prefix>
                            <PhoneOutlined style="color: rgba(0, 0, 0, 0.25)"/>
                        </template>
                    </a-input>
                    <div class="ant-form-item-explain-error" style="" v-if="formState.validation.phone_number">{{
                            formState.validation.phone_number[0]
                        }}
                    </div>
                </a-form-item>

                <a-form-item :label="__('default.password')" required>
                    <a-input-password
                        v-model:value="formState.formData.password"
                        :class="formState.validation.password ? 'ant-input ant-input-status-error': ''">
                        <template #prefix>
                            <LockOutlined style="color: rgba(0, 0, 0, 0.25)"/>
                        </template>
                    </a-input-password>
                    <div class="ant-form-item-explain-error" style="" v-if="formState.validation.password">{{
                            formState.validation.password[0]
                        }}
                    </div>
                </a-form-item>

                <a-form-item name="upload" :label="__('default.profile_picture')">
                    <input type="file" class="form-control mr-2" @change="previewImage" accept="image/*" />

                    <!-- Display the preview of the selected image -->
                    <img v-if="previewURL" :src="previewURL" class="img-thumbnail" alt="Selected Image" style="width: 100px; height: 100px; margin-top: 20px;border-radius: 50%;">
                </a-form-item>


            </a-form>
            <template #footer>
                <a-button type="primary" danger style="margin-right: 8px" @click="$parent.onClose">
                    <i class="mdi mdi-window-close"></i> {{ __('default.close') }}
                </a-button>
                <a-button type="primary" style="margin-right: 8px" @click.prevent="save">
                    <i class="mdi mdi-content-save mr-1"></i> {{ __('default.save') }}
                </a-button>
            </template>
        </a-drawer>
    </div>
</template>
<script>
import {notification} from 'ant-design-vue';
import {UserOutlined, LockOutlined, PhoneOutlined, UploadOutlined} from '@ant-design/icons-vue';

export default {
    name: "AddNewUser",
    props: {
        formState: {
            type: Object,
            required: true
        }
    },
    components: {UserOutlined, LockOutlined, PhoneOutlined, UploadOutlined},
    data() {
        return {
            selectedImage: null, // To store the selected image file
            previewURL: 'https://t3.ftcdn.net/jpg/03/46/83/96/360_F_346839683_6nAPzbhpSkIpb8pmAwufkC7c5eD7wYws.jpg',
            formData: new FormData(),
            roles:[]
        }
    },
    watch: {
        'formState.responseRole':{
            immediate: true,
            deep: true,
            handler() {
                if (this.formState.responseRole) {
                    this.getRoles();
                }
            },
        }
    },
    mounted() {

    },
    methods: {
        async save() {
            this.formData.append('role_id', this.formState.formData.role_id);
            this.formData.append('name', this.formState.formData.name);
            this.formData.append('email', this.formState.formData.email);
            this.formData.append('phone_number', this.formState.formData.phone_number);
            this.formData.append('password', this.formState.formData.password);
            await axios.post('/users', this.formData, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            })
                .then(response => {
                    if (response.data.success) {
                        this.formState.formData.name = ''
                        this.formState.formData.email = ''
                        this.formState.formData.phone_number = ''
                        this.formState.formData.password = ''
                        this.formState.formData.profile_picture = {}
                        this.$parent.getData()
                        this.$parent.onClose()
                        this.$showSuccessMessage(response.data.success)
                    } else {
                        this.$showErrorMessage(response.data.error)
                    }
                })
                .catch(err => {
                    if (err.response.status === 422) {
                        this.formState.validation = err.response.data.errors
                    } else {
                        this.$showErrorMessage(err)
                        console.error(err)
                    }
                })
        },
        getRoles(){
            this.roles = this.formState.responseRole.map(item => {
                return {
                    value:item.id,
                    label:item.name
                }
            })
        },
        roleFilterOption(input, option){
            return option.label.toLowerCase().indexOf(input.toLowerCase()) >= 0;
        },
        previewImage(event) {
            // Get the selected image file
            this.selectedImage = event.target.files[0];
            this.formData.append('profile_picture', event.target.files[0]);

            // Create a FileReader to read the selected image and generate a preview URL
            let reader = new FileReader();

            reader.onload = () => {
                this.previewURL = reader.result; // Set the preview URL
            };

            if (this.selectedImage) {
                reader.readAsDataURL(this.selectedImage); // Read the selected image as a data URL
            } else {
                this.previewURL = ''; // Clear the preview if no image is selected
            }
        },
    },
}
</script>

<style scoped>

</style>
