# Integrated Job Posting and Application Management System

Repository: `XZERUU/job-posting-service`

This project is an Integrative Programming system that connects a Laravel web application, a Laravel backend/API, a MySQL database, and an Expo/React Native mobile application.

## Project Overview

The system manages job posting, admin approval, job seeker applications, and employer applicant review.

The users are:

- Admins, who monitor users, review job posts, and approve or reject job postings.
- Employers, who create job posts and review applicants.
- Job seekers, who browse active jobs, apply, and track application status.

Laravel is the central application backend. The web interface and the mobile app both use the same Laravel backend and the same MySQL database. The Expo mobile app does not connect directly to MySQL. It communicates with Laravel through API routes, and Laravel reads/writes the shared database.

This proves the required integration:

```text
Web -> Laravel backend/API -> MySQL
Mobile -> Laravel API -> MySQL
```

## Final Integrative Scope

This final Integrative Programming scope focuses on backend functionality and cross-platform data interaction.

Included:

- Web login and role-based pages.
- Employer job posting.
- Admin job approval.
- Mobile job browsing.
- Mobile job application.
- Employer applicant review.
- Application status tracking.
- Job seeker profile and resume support.
- Shared Laravel/MySQL backend.

Not included in the final Integrative scope:

- OCR
- NSRP
- PESO referral
- PESO referred
- referral-ready
- skill matching
- rule-based skill comparison
- AI screening
- ranking
- automated hiring
- separate Node/Express backend
- separate mobile database

Resume upload is allowed because it belongs to the job seeker profile/resume feature.

## System Architecture

The project is one integrated system, not separate systems.

### Web Flow

```text
Laravel Blade web pages
    -> Laravel controllers/routes
    -> Eloquent models
    -> MySQL database
```

### Mobile Flow

```text
Expo/React Native mobile app
    -> Laravel API routes
    -> Laravel API controllers
    -> Eloquent models
    -> MySQL database
```

Important architecture rules:

- Web and mobile use the same database records.
- Mobile does not connect directly to MySQL.
- Laravel API is the bridge between mobile and database.
- Laravel is the source of truth for roles, job status, application status, and profile records.
- Do not create a second backend or second mobile database.

## User Roles

### Admin

Admin users can:

- Access the admin dashboard.
- View users.
- Update user roles.
- Delete users.
- View all job posts.
- Approve pending job posts.
- Reject job posts.
- Delete job posts.
- View all applications.
- Use mobile admin screens for monitoring and job approval if needed.

### Employer

Employer users can:

- Access the employer dashboard.
- Create job posts.
- View their own job posts.
- See whether their jobs are pending, active, rejected, or closed.
- View applicants for their own jobs only.
- Open applicant details.
- Approve or reject applicants.
- Close job posts through the API/mobile employer screen if needed.

### Job Seeker

Job seeker users can:

- Browse active job posts only.
- View active job details.
- Apply to active jobs.
- Track application status.
- Update profile details.
- Upload/view resume PDF through the profile feature.
- Use the mobile app for job browsing, application submission, and application status tracking.

## Core Workflow

The main demo workflow is:

1. Employer logs in on web.
2. Employer creates a job post.
3. Laravel saves the job post with `pending` status.
4. Admin logs in.
5. Admin reviews the pending job post.
6. Admin approves the job post.
7. Laravel updates the job status to `active`.
8. Job seeker logs in on mobile.
9. Mobile calls the Laravel API job list.
10. Laravel returns only active jobs.
11. Job seeker opens the approved job.
12. Job seeker applies from mobile.
13. Laravel creates an application record in MySQL with `for_review` status.
14. Employer opens the web dashboard/applications page.
15. Employer sees the applicant for their own job.
16. Employer approves or rejects the applicant.
17. Laravel updates the application status.
18. Job seeker refreshes mobile applications and sees the updated status.

This demonstrates web, mobile, backend API, and shared database integration.

## Status Rules

### Job Post Statuses

- `pending` = waiting for admin approval.
- `active` = approved and visible to job seekers.
- `rejected` = not approved by admin.
- `closed` = no longer accepting applicants.

Rules:

- Employer-created jobs must start as `pending`.
- Job seekers should only see `active` jobs.
- Pending, rejected, and closed jobs must not be visible to ordinary job seekers.
- Applications should only be allowed for active jobs.

### Application Statuses

- `for_review` = submitted and waiting for employer review.
- `approved` = employer approved the applicant.
- `rejected` = employer rejected the applicant.

Backward compatibility:

- Old application records using `pending` should display as `For Review`.

## Features by Platform

### Web Features

- Login/register.
- Role-based dashboard redirects.
- Employer dashboard.
- Employer job creation.
- Employer job list.
- Employer applicant list.
- Employer applicant detail page.
- Employer approve/reject applicant.
- Admin dashboard.
- Admin job post approval/rejection.
- Admin user/application monitoring.
- Job seeker profile page.
- Account settings.
- Job seeker profile details.
- Resume PDF upload/view.

### Mobile Features

- Login/register through Laravel API.
- Role-based navigation after login.
- Job seeker dashboard.
- Job seeker active job browsing.
- Job details.
- Job application submission.
- My applications/status tracking.
- Job seeker profile update.
- Resume PDF upload using `expo-document-picker`.
- Employer dashboard.
- Employer job management.
- Employer applicant view.
- Employer application status update.
- Admin dashboard.
- Admin job monitoring/approval.
- Admin application/job seeker/employer monitoring screens.

## Backend/API Features

Actual API routes are defined in `routes/api.php`. To inspect them:

```bash
php artisan route:list --path=api
```

Current API groups:

Route protection note:

- `POST /api/auth/register` and `POST /api/auth/login` are public.
- Every other API route below is protected by Laravel Sanctum and needs `Authorization: Bearer <token>`.
- In this branch, even `GET /api/jobs` and `GET /api/jobs/{id}` require login because they are inside the API auth group.

### Auth

- `POST /api/auth/register`
- `POST /api/auth/login`
- `GET /api/auth/me`
- `POST /api/auth/logout`
- `GET /api/user`

### Jobs

- `GET /api/jobs`
- `GET /api/jobs/{id}`

### Applications

- `POST /api/applications`
- `GET /api/applications/my-applications`

### Job Seeker Profile

- `GET /api/job-seeker/profile`
- `POST /api/job-seeker/profile`
- `POST /api/job-seeker/skills`
- `POST /api/job-seeker/password`
- `DELETE /api/job-seeker/account`
- `GET /api/skills`

### Employer

- `GET /api/employer/profile`
- `GET /api/employer/jobs`
- `POST /api/employer/jobs`
- `PUT /api/employer/jobs/{id}`
- `PUT /api/employer/jobs/{id}/close`
- `GET /api/employer/jobs/{id}/applicants`
- `PUT /api/employer/applications/{id}/status`

### Admin

- `GET /api/admin/stats`
- `GET /api/admin/employers`
- `GET /api/admin/employers/pending`
- `POST /api/admin/employers`
- `PUT /api/admin/employers/{id}/{action}`
- `GET /api/admin/job-seekers`
- `PUT /api/admin/job-seekers/{id}/{action}`
- `GET /api/admin/jobs`
- `PUT /api/admin/jobs/{id}/approve`
- `PUT /api/admin/jobs/{id}/close`
- `GET /api/admin/applications`

### Notifications

- `GET /api/notifications`
- `PUT /api/notifications/{id}/read`

### Important Request Bodies From Controllers

Use these fields when testing with Thunder Client or the mobile app.

`POST /api/auth/register`

- Required: `email`, `password`, `role`
- Role values accepted by the API: `job_seeker`, `employer`
- Optional: `name`, `company_name`, `first_name`, `last_name`, `profile.contact_number`
- The API stores job seekers as `seeker` in the `users.role` column, then returns `job_seeker` to the mobile app.

Example job seeker registration:

```json
{
  "email": "juan2@test.com",
  "password": "password123",
  "role": "job_seeker",
  "first_name": "Juan",
  "last_name": "Dela Cruz",
  "profile": {
    "contact_number": "09123456789"
  }
}
```

Example employer registration:

```json
{
  "email": "abc2@test.com",
  "password": "password123",
  "role": "employer",
  "company_name": "ABC Company"
}
```

`POST /api/applications`

- Required: `job_post_id`
- Optional: `cover_letter`
- The authenticated user must be a job seeker.
- The job must have `active` status.
- Duplicate applications to the same job are rejected.

`POST /api/employer/jobs` and `PUT /api/employer/jobs/{id}`

- Required: `job_title`, `job_description`, `job_type`, `location`, `vacancies`
- Optional: `salary_min`, `salary_max`, `requirements`, `closing_date`
- New employer jobs are saved with `pending` status and must be approved by an admin before job seekers can see them.

`PUT /api/employer/applications/{id}/status`

- Required: `status`
- Allowed values: `approved`, `rejected`
- The application must belong to one of the authenticated employer's job posts.

`POST /api/job-seeker/profile`

- Accepts profile fields such as `name`, `phone`, `location`, `headline`, `about`, `education`, `experiences`, `linkedin_url`, `portfolio_url`, `github_url`, and `skills`.
- Resume upload uses multipart/form-data with a `resume` PDF file.
- Profile skills are stored as profile information only; the final Integrative scope does not include skill matching, ranking, or automated screening.

`POST /api/job-seeker/password`

- Required: `current_password`, `password`, `password_confirmation`
- The new password must be confirmed.

`POST /api/admin/employers`

- Required: `company_name`, `email`, `password`
- This creates an employer account from the admin API.

## Database

Local database name used by the project:

```text
integrated_job_portal
```

Main tables used by the integrated workflow:

- `users`
- `job_posts`
- `applications`
- `seeker_profiles`
- `personal_access_tokens`
- `sessions`
- `password_reset_tokens`

Other existing tables:

- `job_listings`
- `skills`
- `job_required_skills`
- Laravel queue/cache tables

The integrated job workflow uses `job_posts`, not a separate mobile database.

Migrations create the database structure:

```bash
php artisan migrate
```

## Installation Requirements

Install these first:

- PHP 8.2 or newer
- Composer
- MySQL
- Node.js and npm
- Laravel 12 dependencies through Composer
- Expo Go on Android phone, or Android emulator
- Expo CLI through `npx expo`

Windows setup notes:

- Laragon or AMPPS can provide PHP, MySQL, and local server tools.
- If PowerShell blocks npm scripts, use `npm.cmd` and `npx.cmd`.

## How to Clone / Download

```bash
git clone https://github.com/XZERUU/job-posting-service.git
cd job-posting-service
git checkout backup-mobile-integration
```

Use `backup-mobile-integration` for the current integrated version. Do not merge to `main` until the group reviews and approves the integration.

## Laravel Setup

Install PHP dependencies:

```bash
composer install
```

Create the environment file:

```bash
copy .env.example .env
php artisan key:generate
```

Configure MySQL in `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=integrated_job_portal
DB_USERNAME=root
DB_PASSWORD=
```

Adjust `DB_USERNAME` and `DB_PASSWORD` depending on your Laragon/AMPPS/MySQL setup.

Create the database manually in MySQL first if it does not exist:

```sql
CREATE DATABASE integrated_job_portal;
```

Run migrations:

```bash
php artisan migrate
```

Create the public storage link for resume files:

```bash
php artisan storage:link
```

Install frontend dependencies for Laravel/Vite:

```bash
npm install
```

## Creating Test Accounts

Do not assume test accounts already exist. Create accounts using registration or Tinker.

Example local/demo accounts:

```text
Admin:      admin@test.com / password123
Employer:   abc2@test.com / password123
Job Seeker: juan2@test.com / password123
```

These are local/demo accounts only.

### Create Admin With Tinker

```bash
php artisan tinker
```

Then run:

```php
\App\Models\User::updateOrCreate(
    ['email' => 'admin@test.com'],
    [
        'name' => 'Admin User',
        'password' => \Illuminate\Support\Facades\Hash::make('password123'),
        'role' => 'admin',
    ]
);
```

Create employer and job seeker accounts through web/mobile registration, or use Tinker with role `employer` or `seeker`.

## Running the Web/Laravel App

Open two terminals from the Laravel project root.

Terminal 1:

```bash
php artisan serve --host=0.0.0.0 --port=8000
```

Terminal 2:

```bash
npm.cmd run dev
```

Useful URLs:

- `http://127.0.0.1:8000`
- `http://127.0.0.1:8000/login`
- `http://127.0.0.1:8000/admin/job-posts`
- `http://127.0.0.1:8000/employer/dashboard`
- `http://127.0.0.1:8000/jobs`
- `http://127.0.0.1:8000/applications`

## Running the Mobile App

Install mobile dependencies:

```bash
cd mobile
npm install
```

Start Expo:

```bash
npx.cmd expo start
```

### Physical Phone Testing

`127.0.0.1` points to the phone itself, not the PC. For phone testing, Laravel must listen on the network and Expo must use the PC IP address.

Start Laravel from the project root:

```bash
php artisan serve --host=0.0.0.0 --port=8000
```

Start Expo with a reachable backend URL:

```powershell
cd mobile
$env:EXPO_PUBLIC_BACKEND_URL="http://<PC_IP>:8000"
npx.cmd expo start --lan -c
```

Example:

```powershell
$env:EXPO_PUBLIC_BACKEND_URL="http://192.168.18.71:8000"
```

Requirements:

- Phone and PC must be on the same Wi-Fi.
- Allow firewall access if Windows asks.
- Use the actual PC IPv4 address.

## Thunder Client / API Testing Guide

Run Laravel first:

```bash
php artisan serve --host=0.0.0.0 --port=8000
```

### 1. Login And Get Token

If the account does not exist yet, create it first:

```http
POST http://127.0.0.1:8000/api/auth/register
```

Minimum job seeker body:

```json
{
  "email": "juan2@test.com",
  "password": "password123",
  "role": "job_seeker",
  "first_name": "Juan",
  "last_name": "Dela Cruz"
}
```

Request:

```http
POST http://127.0.0.1:8000/api/auth/login
```

JSON body:

```json
{
  "email": "juan2@test.com",
  "password": "password123"
}
```

Copy the `token` from the response.

For protected requests, add this header:

```http
Authorization: Bearer <token>
Accept: application/json
```

### 2. Test Current User

```http
GET http://127.0.0.1:8000/api/auth/me
```

### 3. Test Active Jobs

```http
GET http://127.0.0.1:8000/api/jobs
```

Optional query parameters:

```text
search=developer
job_type=full-time
```

### 4. Test Job Detail

```http
GET http://127.0.0.1:8000/api/jobs/{id}
```

Ordinary job seekers can only access active jobs.

### 5. Submit Application

Use a job seeker token.

```http
POST http://127.0.0.1:8000/api/applications
```

JSON body:

```json
{
  "job_post_id": 1,
  "cover_letter": "I am interested in this position."
}
```

### 6. View My Applications

Use a job seeker token.

```http
GET http://127.0.0.1:8000/api/applications/my-applications
```

### 7. Employer Jobs

Use an employer token.

```http
GET http://127.0.0.1:8000/api/employer/jobs
```

### 8. Employer Create Job

Use an employer token.

```http
POST http://127.0.0.1:8000/api/employer/jobs
```

JSON body:

```json
{
  "job_title": "Junior Web Developer",
  "job_description": "Build and maintain web application features.",
  "job_type": "full-time",
  "salary_min": 15000,
  "salary_max": 25000,
  "location": "Cagayan de Oro City",
  "vacancies": 1,
  "requirements": "Basic PHP, Laravel, and MySQL knowledge.",
  "closing_date": null
}
```

Created jobs start as `pending`.

### 9. Employer Applicants

Use an employer token.

```http
GET http://127.0.0.1:8000/api/employer/jobs/{id}/applicants
```

### 10. Employer Update Application Status

Use an employer token.

```http
PUT http://127.0.0.1:8000/api/employer/applications/{id}/status
```

JSON body:

```json
{
  "status": "approved"
}
```

Allowed values:

```text
approved
rejected
```

### 11. Admin Approve Job

Use an admin token.

```http
PUT http://127.0.0.1:8000/api/admin/jobs/{id}/approve
```

## Final Demo Script

### Step 1: Start The System

Start Laravel:

```bash
php artisan serve --host=0.0.0.0 --port=8000
```

Start Vite:

```bash
npm.cmd run dev
```

Start Expo:

```powershell
cd mobile
$env:EXPO_PUBLIC_BACKEND_URL="http://<PC_IP>:8000"
npx.cmd expo start --lan -c
```

### Step 2: Employer Creates Job On Web

1. Open `http://127.0.0.1:8000/login`.
2. Login as employer.
3. Go to employer dashboard.
4. Create a job post.
5. Show that the job status is `Pending Approval`.

Say:

```text
The employer creates the job on the Laravel web app. Laravel saves it in MySQL as pending.
```

### Step 3: Admin Approves Job

1. Login as admin.
2. Go to `Admin -> Job Posts`.
3. Find the pending job.
4. Approve it.
5. Show that the status becomes `Active`.

Say:

```text
The admin approval changes the job status in the shared database. Only active jobs are visible to job seekers.
```

### Step 4: Job Seeker Views Job On Mobile

1. Login as job seeker in the mobile app.
2. Open Jobs.
3. Show the approved job in the mobile job list.
4. Open job details.

Say:

```text
The mobile app is calling the Laravel API. It is not using a separate database.
```

### Step 5: Job Seeker Applies On Mobile

1. Submit the application from mobile.
2. Open My Applications.
3. Show status `For Review`.

Say:

```text
The application was created through the Laravel API and saved in the same MySQL database.
```

### Step 6: Employer Reviews Applicant On Web

1. Return to employer web account.
2. Open applications/applicants.
3. Show the mobile-submitted applicant.
4. Approve or reject the applicant.

Say:

```text
The employer can see the applicant submitted from mobile because both platforms share the same backend and database.
```

### Step 7: Job Seeker Sees Updated Status On Mobile

1. Return to mobile app.
2. Refresh My Applications.
3. Show `Approved` or `Rejected`.

Say:

```text
This demonstrates the interaction between web, mobile, backend API, and shared database.
```

## Troubleshooting

### Could not open input file: artisan

You are not inside the Laravel project root. Run:

```bash
cd job-posting-service
```

Then retry the command.

### npm scripts disabled in PowerShell

Use Windows command shims:

```bash
npm.cmd run dev
npx.cmd expo start
```

### Mobile cannot connect to backend

Do not use `127.0.0.1` for physical phone testing. Use the PC IP:

```powershell
$env:EXPO_PUBLIC_BACKEND_URL="http://<PC_IP>:8000"
npx.cmd expo start --lan -c
```

Also check:

- Laravel is running with `--host=0.0.0.0`.
- Phone and PC are on the same Wi-Fi.
- Windows firewall allows PHP/Laravel.

### Job not visible to seeker

The admin must approve the job first. Only `active` jobs appear to job seekers.

### Composer not recognized

Install Composer or configure the Composer/Laragon/AMPPS path.

### Database error

Check:

- MySQL is running.
- `.env` database settings are correct.
- `integrated_job_portal` exists.
- Migrations have been run.

### route:list works but mobile fails

Check:

- `EXPO_PUBLIC_BACKEND_URL`
- Laravel server URL
- firewall
- phone/PC network
- Bearer token/session state in the mobile app

## Branch / Integration History

The `backup-mobile-integration` branch includes integration work for the final demo:

- Mobile app integrated into the Laravel repository under `/mobile`.
- Laravel API controllers added for mobile.
- Mobile now calls Laravel API endpoints.
- Shared MySQL database used by both web and mobile.
- Admin approval flow aligned with job visibility.
- Application status tracking aligned between web/API/mobile.
- Out-of-scope OCR, NSRP, PESO referral/referred, referral-ready, skill matching, AI screening, ranking, and automated hiring features removed or hidden from the demo flow.
- Backend guards added for role/status correctness.
- Mobile frontend adjusted to match the Laravel workflow.

## Rules For Groupmates

- Do not commit directly to `main` without review.
- Work on branches.
- Pull the latest `backup-mobile-integration` branch before editing.
- Do not create a separate backend.
- Do not create a separate mobile database.
- Do not reintroduce OCR, NSRP, PESO referral/referred, referral-ready, skill matching, AI screening, ranking, or automated hiring features.
- Do not change status names unless web, API, mobile, and labels are updated together.
- Test before pushing:

```bash
php artisan route:list --path=api
php artisan test
cd mobile
npx.cmd tsc --noEmit
```

## Final Verification Checklist

- [ ] Laravel runs.
- [ ] Vite runs.
- [ ] Expo runs.
- [ ] API routes pass.
- [ ] Tests pass.
- [ ] TypeScript passes.
- [ ] Employer creates job.
- [ ] Job is saved as pending.
- [ ] Admin approves job.
- [ ] Job becomes active.
- [ ] Job seeker sees job on mobile.
- [ ] Job seeker applies.
- [ ] Employer sees applicant on web.
- [ ] Employer updates application status.
- [ ] Job seeker sees updated status on mobile.
- [ ] No OCR/NSRP/referral/skill matching is visible in the final demo.

## Quick Verification Commands

```bash
php artisan route:list
php artisan route:list --path=api
php artisan migrate:status
php artisan test
cd mobile
npx.cmd tsc --noEmit
```
