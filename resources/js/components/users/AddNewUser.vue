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
                <a-form-item :label="__('default.name')">
                    <a-input v-model:value="formState.formData.name"
                             :class="validation.name ? 'ant-input ant-input-status-error': ''"/>
                    <div class="ant-form-item-explain-error" style="" v-if="validation.name">{{
                            validation.name[0]
                        }}
                    </div>
                </a-form-item>

                <a-form-item :label="__('default.email')">
                    <a-input v-model:value="formState.formData.email"
                             :class="validation.email ? 'ant-input ant-input-status-error': ''">
                        <template #prefix>
                            <UserOutlined style="color: rgba(0, 0, 0, 0.25)"/>
                        </template>
                    </a-input>
                    <div class="ant-form-item-explain-error" style="" v-if="validation.email">{{
                            validation.email[0]
                        }}
                    </div>
                </a-form-item>

                <a-form-item :label="__('default.phone_number')">
                    <a-input v-model:value="formState.formData.phone_number"
                             :class="validation.phone_number ? 'ant-input ant-input-status-error': ''">
                        <template #prefix>
                            <PhoneOutlined style="color: rgba(0, 0, 0, 0.25)"/>
                        </template>
                    </a-input>
                    <div class="ant-form-item-explain-error" style="" v-if="validation.phone_number">{{
                            validation.phone_number[0]
                        }}
                    </div>
                </a-form-item>

                <a-form-item :label="__('default.password')">
                    <a-input-password
                        v-model:value="formState.formData.password"
                        :class="validation.password ? 'ant-input ant-input-status-error': ''">
                        <template #prefix>
                            <LockOutlined style="color: rgba(0, 0, 0, 0.25)"/>
                        </template>
                    </a-input-password>
                    <div class="ant-form-item-explain-error" style="" v-if="validation.password">{{
                            validation.password[0]
                        }}
                    </div>
                </a-form-item>

                <a-form-item name="upload" :label="__('default.profile_picture')">
                    <input type="file" class="form-control mr-2" @change="previewImage" accept="image/*" />

                    <!-- Display the preview of the selected image -->
                    <img v-if="previewURL" :src="previewURL" class="img-thumbnail" alt="Selected Image" style="width: 100px; height: 100px; margin-top: 20px">
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
            validation: {},
            selectedImage: null, // To store the selected image file
            previewURL: '',
        }
    },
    watch: {},
    mounted() {

    },
    methods: {
        async save() {
            await axios.post('/users', this.formState.formData)
                .then(response => {
                    if (response.data.success) {
                        this.formState.formData.name = ''
                        this.$parent.getData()
                        this.$parent.onClose()
                        this.showSuccessMessage(response.data.success)
                    } else {
                        this.showErrorMessage(response.data.error)
                    }
                })
                .catch(err => {
                    if (err.response.status === 422) {
                        this.validation = err.response.data.errors
                    } else {
                        console.error(err)
                    }
                })
        },
        showSuccessMessage(message) {
            notification['success']({
                message: 'Success',
                description: message,
            });
        },
        showErrorMessage(message) {
            notification['error']({
                message: 'Error',
                description: message,
            });
        },
        previewImage(event) {
            // Get the selected image file
            this.selectedImage = event.target.files[0];

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
