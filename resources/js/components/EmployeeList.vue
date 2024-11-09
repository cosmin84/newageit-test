<template>
    <div class="p-6">
        <h1 class="text-2xl font-bold mb-6">Employee List</h1>

        <button
            @click="showCreateModal = true"
            class="mb-4 px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600"
        >
            Add Employee
        </button>

        <div v-if="loading" class="text-center py-4">Loading...</div>
        <div v-else-if="error" class="text-red-500 py-4">{{ error }}</div>
        <div v-else>
            <table v-if="employees.length" class="w-full bg-white rounded shadow">
                <thead>
                <tr class="bg-gray-200 text-left">
                    <th class="px-4 py-2">Name</th>
                    <th class="px-4 py-2">Email</th>
                    <th class="px-4 py-2">Phone Number</th>
                    <th class="px-4 py-2">Job Title</th>
                    <th class="px-4 py-2">Salary</th>
                    <th class="px-4 py-2">Actions</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="employee in employees" :key="employee.id" class="border-t">
                    <td class="px-4 py-2">{{ employee.name }}</td>
                    <td class="px-4 py-2">{{ employee.email }}</td>
                    <td class="px-4 py-2">{{ employee.phone_number }}</td>
                    <td class="px-4 py-2">{{ employee.job_title }}</td>
                    <td class="px-4 py-2">${{ formatSalary(employee.salary) }}</td>
                    <td class="px-4 py-2 flex space-x-2">
                        <button
                            @click="editEmployee(employee)"
                            class="px-2 py-1 bg-yellow-500 text-white rounded text-sm hover:bg-yellow-600"
                        >
                            Edit
                        </button>
                        <button
                            @click="confirmDelete(employee)"
                            class="px-2 py-1 bg-red-500 text-white rounded text-sm hover:bg-red-600"
                        >
                            Delete
                        </button>
                    </td>
                </tr>
                </tbody>
            </table>
            <p v-else class="mt-4">No employees found.</p>

            <div v-if="pagination.last_page > 1" class="mt-4 flex justify-center space-x-2">
                <button
                    v-for="page in pagination.last_page"
                    :key="page"
                    @click="changePage(page)"
                    :class="[
                        'px-3 py-1 rounded',
                        page === pagination.current_page
                          ? 'bg-blue-500 text-white'
                          : 'bg-gray-200'
                      ]"
                >
                    {{ page }}
                </button>
            </div>
        </div>

        <EmployeeModal
            v-if="showCreateModal"
            :employee="selectedEmployee"
            @close="closeModal"
            @submit="handleSubmit"
        />

        <ConfirmationModal
            v-if="showDeleteModal"
            :message="'Are you sure you want to delete this employee?'"
            @confirm="handleDelete"
            @cancel="showDeleteModal = false"
        />
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useEmployees } from '../composables/useEmployees.js'
import EmployeeModal from './EmployeeModal.vue'
import ConfirmationModal from './ConfirmationModal.vue'

const showCreateModal = ref(false)
const showDeleteModal = ref(false)
const selectedEmployee = ref(null)
const employeeToDelete = ref(null)

const {
    employees,
    loading,
    error,
    pagination,
    fetchEmployees,
    createEmployee,
    updateEmployee,
    deleteEmployee
} = useEmployees()

onMounted(() => {
    fetchEmployees()
})

const debounceSearch = (() => {
    let timeout
    return () => {
        clearTimeout(timeout)
        timeout = setTimeout(() => {
            fetchEmployees()
        }, 500)
    }
})()

const changePage = (page) => {
    fetchEmployees(page)
}

const formatSalary = (salary) => {
    return Number(salary).toLocaleString(undefined, {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    })
}

const editEmployee = (employee) => {
    selectedEmployee.value = employee
    showCreateModal.value = true
}

const confirmDelete = (employee) => {
    employeeToDelete.value = employee
    showDeleteModal.value = true
}

const handleDelete = async () => {
    if (employeeToDelete.value) {
        await deleteEmployee(employeeToDelete.value.id)
        showDeleteModal.value = false
        employeeToDelete.value = null
    }
}

const closeModal = () => {
    showCreateModal.value = false
    selectedEmployee.value = null
}

const handleSubmit = async (employeeData) => {
    try {
        if (selectedEmployee.value) {
            await updateEmployee(selectedEmployee.value.id, employeeData)
        } else {
            await createEmployee(employeeData)
        }
        closeModal()
    } catch (e) {
        console.error(e)
    }
}
</script>
