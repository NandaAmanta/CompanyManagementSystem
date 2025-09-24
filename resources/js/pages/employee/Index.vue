<script setup lang="ts">
    import { ref, reactive, computed, onMounted } from 'vue'
    import { Head, router, useForm } from '@inertiajs/vue3'
    import { message } from 'ant-design-vue'
    import AppLayout from '@/layouts/AppLayout.vue';
    import EmployeeController from '@/actions/App/Http/Controllers/Employee/EmployeeController';
    import {
    PlusOutlined,
    EditOutlined,
    DeleteOutlined
    } from '@ant-design/icons-vue'
    import { dashboard } from '@/routes';

    import { type BreadcrumbItem } from '@/types';

    const props = defineProps({
        companies: Array,
        employees: Object,
        filters: Object
    })

    const breadcrumbs: BreadcrumbItem[] = [
        {
            title: 'Dashboard',
            href: dashboard().url,
        },
        {
            title: 'Employee',
            href: null,
        }
    ];

    const showModal = ref(false)
    const loading = ref(false)
    const editingEmployee = ref(null)
    const formRef = ref()
    const fileList = ref([])

    const searchForm = reactive({
        search: props.filters.search || ''
    })

    const formData = reactive({
        first_name: '',
        last_name: '',
        email: '',
        phone_numer: '',
        company_id: null,
    })

    const formProcessing = ref(false)

    // Table columns
    const columns = [
        {
            title: 'Name',
            dataIndex: 'name',
            key: 'name',
            sorter: true
        },
        {
            title : 'Company',
            key: 'company',
            dataIndex: 'company'
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
        first_name: [
            { required: true, message: 'Please enter first name' }
        ],
        last_name: [
            { required: true, message: 'Please enter last name' }
        ],
        company_id: [
            { required: true, message: 'Please select a company' }
        ],
        email: [
            { type: 'email', message: 'Please enter a valid email' }
        ],
        phone_number: [
            { max: 20, message: 'Phone number must not exceed 20 characters' }
        ]

    }

    // Pagination config
    const paginationConfig = computed(() => ({
        current: props.employees.current_page,
        total: props.employees.total,
        pageSize: props.employees.per_page,
        showSizeChanger: true,
        showQuickJumper: true,
        showTotal: (total, range) => `${range[0]}-${range[1]} of ${total} items`
        }))

        // Methods
        const handleSearch = () => {
            router.get(EmployeeController.index(), searchForm, {
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
        
        router.get(EmployeeController.index(), params, {
            preserveState: true,
            replace: true
        })
    }

    const editEmployee = (employee) => {
        editingEmployee.value = employee
        formData.first_name = employee.first_name
        formData.last_name = employee.last_name
        formData.email = employee.email || ''
        formData.phone_number = employee.phone_number || ''
        formData.company_id = employee.company_id || null
        showModal.value = true
    }

    const submitForm = async () => {
        try {
            await formRef.value.validate()
            formProcessing.value = true
            
            const submitData = new FormData()
            submitData.append('first_name', formData.first_name)
            submitData.append('last_name', formData.last_name)
            submitData.append('email', formData.email || '')
            submitData.append('phone_number', formData.phone_number || '')
            submitData.append('company_id', formData.company_id)
            
            
            if (editingEmployee.value) {
                submitData.append('_method', 'PUT')
                router.post(EmployeeController.update(editingEmployee.value.id), submitData, {
                    onSuccess: () => {
                        message.success('Employee updated successfully')
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
                router.post(EmployeeController.store(), submitData, {
                    onSuccess: () => {
                        message.success('Employee created successfully')
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

    const deleteEmployee = (id) => {
        router.delete(EmployeeController.destroy(id), {
            onSuccess: () => {
                message.success('Employee deleted successfully')
            },
            onError: () => {
                message.error('Failed to delete Employee')
            }
        })
    }

    const closeModal = () => {
        showModal.value = false
        editingEmployee.value = null
        // Reset form data
        formData.first_name = ''
        formData.last_name = ''
        formData.email = ''
        formData.phone_number = ''
        formData.company_id = null
        formProcessing.value = false
        formRef.value?.resetFields()
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
  <Head title="Employees" />
  
  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="p-6">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Employees</h1>
            <p class="text-gray-600">Manage employee information</p>
        </div>
        <a-button type="primary" @click="showModal = true" size="large">
            <template #icon>
            <PlusOutlined />
            </template>
            Add Employee
        </a-button>
        </div>

        <div class="mb-6">
        <a-input-search
            v-model:value="searchForm.search"
            placeholder="Search employee..."
            size="large"
            style="max-width: 400px"
            @search="handleSearch"
            @pressEnter="handleSearch"
            allowClear
        />
        </div>

        <a-card>
        <a-table
            :columns="columns"
            :data-source="employees.data"
            :pagination="paginationConfig"
            :loading="loading"
            row-key="id"
            @change="handleTableChange"
        >
            <template #bodyCell="{ column, record }">
                <template v-if="column.key === 'name'">
                    {{ record.first_name }} {{ record.last_name }}
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
                <template v-if="column.key === 'phone_number'">
                    <a
                    v-if="record.phone_number"
                    :href="`tel:${record.phone_number}`"
                    class="text-blue-600 hover:text-blue-800"
                    >
                    {{ record.phone_number }}
                    </a>
                    <span v-else class="text-gray-400">-</span>
                </template>
                <template v-if="column.key === 'company'">
                    {{ record.company ? record.company.name : 'N/A' }}
                </template>
                <template v-if="column.key === 'actions'">
                    <a-space>
                    <a-button
                        type="primary"
                        size="small"
                        ghost
                        @click="editEmployee(record)"
                    >
                        <template #icon>
                        <EditOutlined />
                        </template>
                        Edit
                    </a-button>
                    <a-popconfirm
                        title="Are you sure you want to delete this employee?"
                        @confirm="deleteEmployee(record.id)"
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

        <a-modal
        v-model:open="showModal"
        :title="editingEmployee ? 'Edit Employee' : 'Add New Employee'"
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
            <a-form-item label="First Name" name="first_name">
            <a-input
                v-model:value="formData.first_name"
                placeholder="John"
                size="large"
                required
            />
            </a-form-item>

            <a-form-item label="Last Name" name="last_name">
            <a-input
                v-model:value="formData.last_name"
                placeholder="Doe"
                size="large"
                required
            />
            </a-form-item>
            <a-form-item label="Phone Number" name="phone_number">
            <a-input
                v-model:value="formData.phone_number"
                placeholder="+1 234 567 890"
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
            <a-form-item label="Company" name="company_id">
            <a-select
                v-model:value="formData.company_id"
                placeholder="Select a company"
                size="large"
                style="width: 100%"
                required
            >
                <a-select-option
                v-for="company in props.companies"
                :key="company.id"
                :value="company.id"
                >
                {{ company.name }}
                </a-select-option>
            </a-select>
            </a-form-item>
            
        </a-form>
        </a-modal>
    </div>
  </AppLayout>
</template>