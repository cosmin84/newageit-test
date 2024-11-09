import {ref} from 'vue'
import axios from 'axios'

export const useEmployees = () => {
    const employees = ref([])
    const loading = ref(false)
    const error = ref(null)
    const pagination = ref({})

    const fetchEmployees = async (page = 1) => {
        loading.value = true
        try {
            const params = {
                page,
            }
            const response = await axios.get('/api/v1/employees', {params})
            employees.value = response.data.data
            pagination.value = {
                current_page: response.data.meta.current_page,
                last_page: response.data.meta.last_page,
                total: response.data.meta.total,
                per_page: response.data.meta.per_page
            }
        } catch (e) {
            error.value = 'Error fetching employees'
        } finally {
            loading.value = false
        }
    }

    const createEmployee = async (employeeData) => {
        try {
            const response = await axios.post('/api/v1/employees', employeeData)
            await fetchEmployees()
            return response.data
        } catch (e) {
            error.value = 'Error creating employee'
            throw e
        }
    }

    const updateEmployee = async (id, employeeData) => {
        try {
            const response = await axios.put(`/api/v1/employees/${id}`, employeeData)
            await fetchEmployees()
            return response.data
        } catch (e) {
            error.value = 'Error updating employee'
            throw e
        }
    }

    const deleteEmployee = async (id) => {
        try {
            await axios.delete(`/api/v1/employees/${id}`)
            await fetchEmployees()
        } catch (e) {
            error.value = 'Error deleting employee'
            throw e
        }
    }

    return {
        employees,
        loading,
        error,
        pagination,
        fetchEmployees,
        createEmployee,
        updateEmployee,
        deleteEmployee
    }
}
