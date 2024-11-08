<div class="mb-4">
    <label class="block text-gray-700">Name</label>
    <input type="text" name="name" class="w-full px-4 py-2 border rounded" value="{{ old('name', $employee->name ?? '') }}">
    @error('name') <small class="text-red-500">{{ $message }}</small> @enderror
</div>

<div class="mb-4">
    <label class="block text-gray-700">Email</label>
    <input type="email" name="email" class="w-full px-4 py-2 border rounded" value="{{ old('email', $employee->email ?? '') }}">
    @error('email') <small class="text-red-500">{{ $message }}</small> @enderror
</div>

<div class="mb-4">
    <label class="block text-gray-700">Phone Number</label>
    <input type="text" name="phone_number" class="w-full px-4 py-2 border rounded" value="{{ old('phone_number', $employee->phone_number ?? '') }}">
    @error('phone_number') <small class="text-red-500">{{ $message }}</small> @enderror
</div>

<div class="mb-4">
    <label class="block text-gray-700">Job Title</label>
    <input type="text" name="job_title" class="w-full px-4 py-2 border rounded" value="{{ old('job_title', $employee->job_title ?? '') }}">
    @error('job_title') <small class="text-red-500">{{ $message }}</small> @enderror
</div>

<div class="mb-4">
    <label class="block text-gray-700">Salary</label>
    <input type="number" name="salary" class="w-full px-4 py-2 border rounded" step="0.01" value="{{ old('salary', $employee->salary ?? '') }}">
    @error('salary') <small class="text-red-500">{{ $message }}</small> @enderror
</div>

<div class="flex space-x-4">
    <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded">{{ $buttonText }}</button>
    <a href="{{ route('employees.index') }}" class="px-4 py-2 bg-gray-500 text-white rounded">Cancel</a>
</div>
