<template>
    <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">
        <div class="bg-white rounded-lg p-6 w-full max-w-lg">
            <h2 class="text-xl font-bold mb-4">
                {{ employee ? 'Edit Employee' : 'Add Employee' }}
            </h2>

            <form @submit.prevent="handleSubmit" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium mb-1">Name</label>
                    <input
                        v-model="form.name"
                        type="text"
                        class="w-full px-3 py-2 border rounded"
                        required
                    />
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Email</label>
                    <input
                        v-model="form.email"
                        type="email"
                        class="w-full px-3 py-2 border rounded"
                        required
                    />
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Phone Number</label>
                    <input
                        v-model="form.phone_number"
                        type="text"
                        class="w-full px-3 py-2 border rounded"
                        required
                    />
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Job Title</label>
                    <input
                        v-model="form.job_title"
                        type="text"
                        class="w-full px-3 py-2 border rounded"
                        required
                    />
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Salary</label>
                    <input
                        v-model="form.salary"
                        type="number"
                        step="0.01"
                        class="w-full px-3 py-2 border rounded"
                        required
                    />
                </div>

                <div class="flex justify-end space-x-2">
                    <button
                        type="button"
                        @click="$emit('close')"
                        class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600"
                    >
                        Cancel
                    </button>
                    <button
                        type="submit"
                        class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600"
                    >
                        {{ employee ? 'Update' : 'Create' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>

<script setup>
import {onMounted, ref} from 'vue'

const props = defineProps({
    employee: {
        type: Object,
        default: null
    }
})

const emit = defineEmits(['close', 'submit'])

const form = ref({
    name: '',
    email: '',
    phone_number: '',
    job_title: '',
    salary: ''
})

onMounted(() => {
    if (props.employee) {
        form.value = {...props.employee}
    }
})

const handleSubmit = () => {
    emit('submit', form.value)
}
</script>
