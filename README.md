## Setting up the project locally
***
* Clone the project repository from GitHub to your local machine
* Create environment file by copying .env.example to .env
* Ensure Docker is installed on your system, then run the following command in the project's root directory:
```
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v "$(pwd):/var/www/html" \
    -w /var/www/html \
    laravelsail/php83-composer:latest \
    composer install --ignore-platform-reqs
```
* Start the containers using `sail up -d`
* Set the application key by running `sail artisan key:generate`
* Install Javascript dependencies using `sail npm install`
* Run the database migrations and seeders with `sail artisan migrate --seed`
* Run the Vite development server with `sail npm run dev` if you're doing changes in Blade, Javascript or CSS/SCSS, or run `sail npm run build` to have the assets built and versioned for production

*** 
## Testing
The test suite consists of 37 feature tests that verify the functionality implemented in Task 1 and Task 2.

Run the test suite by executing `sail artisan test`
***
## API Endpoints
***
### List Employees
**Request:**
```
GET /api/v1/employees
```
**Response:**
```
{
  "current_page": 1,
  "data": [
    {
      "id": 1,
      "name": "Alice Smith",
      "email": "alice@example.com",
      "phone_number": "1234567890",
      "job_title": "Developer",
      "salary": "60000.00",
      "created_at": "2023-10-10T12:00:00Z",
      "updated_at": "2023-10-10T12:00:00Z"
    },
    // ... more employees
  ],
  "first_page_url": "...",
  "from": 1,
  "last_page": 5,
  "last_page_url": "...",
  "next_page_url": "...",
  "path": "...",
  "per_page": 10,
  "prev_page_url": null,
  "to": 10,
  "total": 50
}
```
**Status Code:** `200 OK`

### Add a New Employee
**Request:**
```
POST /api/v1/employees
Content-Type: application/json

{
  "name": "John Doe",
  "email": "john.doe@example.com",
  "phone_number": "5551234567",
  "job_title": "Designer",
  "salary": 70000
}
```
**Response:**
```
{
  "message": "Employee created successfully.",
  "data": {
    "id": 51,
    "name": "John Doe",
    "email": "john.doe@example.com",
    "phone_number": "5551234567",
    "job_title": "Designer",
    "salary": "70000.00",
    "created_at": "2023-10-10T12:05:00Z",
    "updated_at": "2023-10-10T12:05:00Z"
  }
}
```
**Status Code:** `201 Created`

### Get Employee Details
**Request:**
```
GET /api/v1/employees/51
```
**Response:**
```
{
  "id": 51,
  "name": "John Doe",
  "email": "john.doe@example.com",
  "phone_number": "5551234567",
  "job_title": "Designer",
  "salary": "70000.00",
  "created_at": "2023-10-10T12:05:00Z",
  "updated_at": "2023-10-10T12:05:00Z"
}
```
**Status Code:** `200 OK`

### Update an Employee
**Request:**
```
PUT /api/v1/employees/51
Content-Type: application/json

{
  "job_title": "Senior Designer",
  "salary": 80000
}
```
**Response:**
```
{
  "message": "Employee updated successfully.",
  "data": {
    "id": 51,
    "name": "John Doe",
    "email": "john.doe@example.com",
    "phone_number": "5551234567",
    "job_title": "Senior Designer",
    "salary": "80000.00",
    "created_at": "2023-10-10T12:05:00Z",
    "updated_at": "2023-10-10T12:10:00Z"
  }
}
```
**Status Code:** `200 OK`

### Delete an Employee
**Request:**
```
DELETE /api/v1/employees/51
```
**Response:**
```
{
  "message": "Employee deleted successfully."
}
```
**Status Code:** `200 OK`

### Error Handling
**Request:**
```
POST /api/employees
Content-Type: application/json

{
  "name": "",
  "email": "not-an-email",
  "phone_number": "",
  "job_title": "",
  "salary": -100
}
```
**Response:**
```
{
  "message": "The given data was invalid.",
  "errors": {
    "name": ["The name field is required."],
    "email": ["The email must be a valid email address."],
    "phone_number": ["The phone number field is required."],
    "job_title": ["The job title field is required."],
    "salary": ["The salary must be at least 0."]
  }
}
```
**Status Code:** `422 Unprocessable Entity`
