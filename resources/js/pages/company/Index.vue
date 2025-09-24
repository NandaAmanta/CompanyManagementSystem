<script setup lang="ts">
    import { ref, reactive, computed, onMounted } from 'vue'
    import { Head, router, useForm } from '@inertiajs/vue3'
    import { message } from 'ant-design-vue'
    import AppLayout from '@/layouts/AppLayout.vue';
    import CompanyController from '@/actions/App/Http/Controllers/Company/CompanyController';
    import {
    PlusOutlined,
    EditOutlined,
    DeleteOutlined
    } from '@ant-design/icons-vue'
    import { dashboard } from '@/routes';

    import { type BreadcrumbItem } from '@/types';

    const props = defineProps({
        companies: Object,
        filters: Object
    })

    const breadcrumbs: BreadcrumbItem[] = [
        {
            title: 'Dashboard',
            href: dashboard().url,
        },
        {
            title: 'Companies',
            href: null,
        }
    ];

    // Reactive data
    const showModal = ref(false)
    const loading = ref(false)
    const editingCompany = ref(null)
    const formRef = ref()
    const fileList = ref([])

    const searchForm = reactive({
        search: props.filters.search || ''
    })

    // Use regular reactive object instead of useForm for better FormData control
    const formData = reactive({
        name: '',
        website: '',
        email: '',
        logo: null
    })

    const formProcessing = ref(false)

    // Table columns
    const columns = [
        {
            title: 'Logo',
            key: 'logo',
            width: 80
        },
        {
            title: 'Name',
            dataIndex: 'name',
            key: 'name',
            sorter: true
        },
        {
            title: 'Website',
            key: 'website',
            dataIndex: 'website'
        },
        {
            title: 'Email',
            key: 'email',
            dataIndex: 'email'
        },
        {
            title: 'Created At',
            dataIndex: 'created_at',
            key: 'created_at'
        },
        {
            title: 'Actions',
            key: 'actions',
            width: 200
        }
    ]

    // Form validation rules
    const rules = {
        name: [
            { required: true, message: 'Please enter company name' },
            { max: 255, message: 'Company name must not exceed 255 characters' }
        ],
        website: [
            { type: 'url', message: 'Please enter a valid URL' }
        ],
        email: [
            { type: 'email', message: 'Please enter a valid email' }
        ]
    }

    // Pagination config
    const paginationConfig = computed(() => ({
        current: props.companies.current_page,
        total: props.companies.total,
        pageSize: props.companies.per_page,
        showSizeChanger: true,
        showQuickJumper: true,
        showTotal: (total, range) => `${range[0]}-${range[1]} of ${total} items`
        }))

        // Methods
        const handleSearch = () => {
            router.get(CompanyController.index(), searchForm, {
                preserveState: true,
                replace: true
            })
        }

        const handleTableChange = (pagination, filters, sorter) => {
        const params = {
            page: pagination.current,
            per_page: pagination.pageSize,
            ...searchForm
        }
        
        router.get(CompanyController.index(), params, {
            preserveState: true,
            replace: true
        })
    }

    const editCompany = (company) => {
        editingCompany.value = company
        formData.name = company.name
        formData.website = company.website || ''
        formData.email = company.email || ''
        formData.logo = null
        fileList.value = []
        showModal.value = true
    }

    const submitForm = async () => {
        try {
            await formRef.value.validate()
            formProcessing.value = true
            
            // Create FormData properly
            const submitData = new FormData()
            submitData.append('name', formData.name)
            submitData.append('website', formData.website || '')
            submitData.append('email', formData.email || '')
            
            if (fileList.value.length > 0 && fileList.value[0].originFileObj) {
                submitData.append('logo', fileList.value[0].originFileObj)
            }
            
            if (editingCompany.value) {
                submitData.append('_method', 'PUT')
                router.post(CompanyController.update(editingCompany.value.id), submitData, {
                    onSuccess: () => {
                        message.success('Company updated successfully')
                        closeModal()
                    },
                    onError: (errors) => {
                        Object.keys(errors).forEach(key => {
                            message.error(errors[key])
                        })
                    },
                    onFinish: () => {
                        formProcessing.value = false
                    }
                })
            } else {
                router.post(CompanyController.store(), submitData, {
                    onSuccess: () => {
                        message.success('Company created successfully')
                        closeModal()
                    },
                    onError: (errors) => {
                        Object.keys(errors).forEach(key => {
                            message.error(errors[key])
                        })
                    },
                    onFinish: () => {
                        formProcessing.value = false
                    }
                })
            }
        } catch (error) {
            console.error('Validation error:', error)
            formProcessing.value = false
        }
    }

    const deleteCompany = (id) => {
        router.delete(CompanyController.destroy(id), {
            onSuccess: () => {
                message.success('Company deleted successfully')
            },
            onError: () => {
                message.error('Failed to delete company')
            }
        })
    }

    const closeModal = () => {
        showModal.value = false
        editingCompany.value = null
        // Reset form data
        formData.name = ''
        formData.website = ''
        formData.email = ''
        formData.logo = null
        fileList.value = []
        formProcessing.value = false
        formRef.value?.resetFields()
    }

    const beforeUpload = (file) => {
        const isImage = file.type.startsWith('image/')
        if (!isImage) {
            message.error('You can only upload image files!')
            return false
        }
        
        const isLt2M = file.size / 1024 / 1024 < 2
        if (!isLt2M) {
            message.error('Image must be smaller than 2MB!')
            return false
        }
        
        return false // Prevent auto upload
    }

    const handleRemove = () => {
        fileList.value = []
        formData.logo = null
    }
</script>

<style scoped>
    .ant-upload-select-picture-card i {
    font-size: 32px;
    color: #999;
    }

    .ant-upload-select-picture-card .ant-upload-text {
    margin-top: 8px;
    color: #666;
    }
</style>

<template>
  <Head title="Companies" />
  
  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="p-6">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Companies</h1>
            <p class="text-gray-600">Manage company information</p>
        </div>
        <a-button type="primary" @click="showModal = true" size="large">
            <template #icon>
            <PlusOutlined />
            </template>
            Add Company
        </a-button>
        </div>

        <!-- Search -->
        <div class="mb-6">
        <a-input-search
            v-model:value="searchForm.search"
            placeholder="Search companies..."
            size="large"
            style="max-width: 400px"
            @search="handleSearch"
            @pressEnter="handleSearch"
            allowClear
        />
        </div>

        <!-- Companies Table -->
        <a-card>
        <a-table
            :columns="columns"
            :data-source="companies.data"
            :pagination="paginationConfig"
            :loading="loading"
            row-key="id"
            @change="handleTableChange"
        >
            <template #bodyCell="{ column, record }">
            <template v-if="column.key === 'logo'">
                <a-avatar
                v-if="record.logo_url"
                :src="record.logo_url"
                :size="40"
                shape="square"
                />
                <a-avatar v-else :size="40" shape="square">
                {{ record.name.charAt(0).toUpperCase() }}
                </a-avatar>
            </template>
            
            <template v-if="column.key === 'website'">
                <a
                v-if="record.website"
                :href="record.website"
                target="_blank"
                class="text-blue-600 hover:text-blue-800"
                >
                {{ record.website }}
                </a>
                <span v-else class="text-gray-400">-</span>
            </template>
            
            <template v-if="column.key === 'email'">
                <a
                v-if="record.email"
                :href="`mailto:${record.email}`"
                class="text-blue-600 hover:text-blue-800"
                >
                {{ record.email }}
                </a>
                <span v-else class="text-gray-400">-</span>
            </template>
            
            <template v-if="column.key === 'actions'">
                <a-space>
                <a-button
                    type="primary"
                    size="small"
                    ghost
                    @click="editCompany(record)"
                >
                    <template #icon>
                    <EditOutlined />
                    </template>
                    Edit
                </a-button>
                <a-popconfirm
                    title="Are you sure you want to delete this company?"
                    @confirm="deleteCompany(record.id)"
                    ok-text="Yes"
                    cancel-text="No"
                >
                    <a-button type="primary" danger size="small" ghost>
                    <template #icon>
                        <DeleteOutlined />
                    </template>
                    Delete
                    </a-button>
                </a-popconfirm>
                </a-space>
            </template>
            </template>
        </a-table>
        </a-card>

        <!-- Create/Edit Modal -->
        <a-modal
        v-model:open="showModal"
        :title="editingCompany ? 'Edit Company' : 'Add New Company'"
        :confirm-loading="formProcessing"
        @ok="submitForm"
        @cancel="closeModal"
        width="600px"
        >
        <a-form
            ref="formRef"
            :model="formData"
            :rules="rules"
            layout="vertical"
            class="mt-4"
        >
            <a-form-item label="Company Name" name="name">
            <a-input
                v-model:value="formData.name"
                placeholder="Enter company name"
                size="large"
            />
            </a-form-item>
            
            <a-form-item label="Website" name="website">
            <a-input
                v-model:value="formData.website"
                placeholder="https://example.com"
                size="large"
            />
            </a-form-item>
            
            <a-form-item label="Email" name="email">
            <a-input
                v-model:value="formData.email"
                placeholder="contact@example.com"
                size="large"
            />
            </a-form-item>
            
            <a-form-item label="Logo" name="logo">
            <div class="flex items-center space-x-4">
                <a-upload
                v-model:file-list="fileList"
                :before-upload="beforeUpload"
                :max-count="1"
                accept="image/*"
                list-type="picture-card"
                @remove="handleRemove"
                >
                <div v-if="fileList.length < 1">
                    <PlusOutlined />
                    <div class="mt-2">Upload</div>
                </div>
                </a-upload>
                <div v-if="editingCompany && editingCompany.logo_url && fileList.length === 0">
                <a-avatar
                    :src="editingCompany.logo_url"
                    :size="64"
                    shape="square"
                />
                <p class="text-xs text-gray-500 mt-1">Current logo</p>
                </div>
            </div>
            </a-form-item>
        </a-form>
        </a-modal>
    </div>
  </AppLayout>
</template>