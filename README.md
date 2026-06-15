# sembark-url-shortener-assignment
url shortener mini project

## Setup Instructions

## Follow these steps to set up and run the project locally:

composer install
cp .env.example .env
php artisan key:generate

# Configure database
Create Database
php artisan migrate --seed
npm install
npm run build
php artisan serve

## Process to run project
After successfully run php artisan migrate --seed
you can log as a admin
Email : superadmin@example.com
password : password

Also you can register as a admin or member

## Features & Role-Based Access Control (RBAC)

### 1. Company Management
* **Super Admin:** Can create new companies and view the complete list of all registered companies.

### 2. Invitation System & User Onboarding
* **Super Admin Privileges:** Can invite any user who is registered as an **Admin** or any user who is **not currently assigned** to any company.
* **Admin Privileges:** Can invite users registered as an **Admin** or a **Member**, provided they are **not currently assigned** to any other company.
* **Invitation Management:** Super Admins can view the complete list of invitations  and Admins can view the list of invitations they have sent or belong withis his company.
* **Acceptance Workflow:** When an invited Admin or Member logs into their account, they will see the invitation on their **Invitations Page**. Upon clicking **Accept**, they will automatically be assigned and linked to that specific company.

### 3. URL Shortener Modules (Scoped Access)
* **Super Admin:** Has global view-only access. They cannot create short URLs but can view **all short URLs** created across the entire platform.
* **Admin:** Can create new short URLs and view **all short URLs belonging to their assigned company**.
* **Member:** Can create new short URLs but can **only view their own** self-created URLs.


## Test
php artisan test --filter=ShortUrlPermissionTest

## Roles
- SuperAdmin
- Admin
- Member

## AI Usage
- Gemini for debugging, testing, and Laravel guidance.
