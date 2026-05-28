# Integrated Job Posting Mobile App

This folder contains the Expo/React Native mobile frontend for the integrated Laravel job posting system.

The mobile app is not a separate backend. It uses the Laravel API from the parent project and shares the same MySQL database through Laravel.

```text
Mobile app -> Laravel API -> MySQL
```

## Mobile Scope

Included mobile features:

- Login/register through Laravel API.
- Job seeker dashboard.
- Active job browsing.
- Job detail view.
- Application submission.
- My applications/status tracking.
- Job seeker profile update.
- Resume PDF upload using `expo-document-picker`.
- Employer dashboard/job/applicant screens.
- Admin monitoring and job approval screens.

Not part of the final mobile scope:

- OCR
- NSRP
- PESO referral
- PESO referred
- referral-ready
- skill matching
- rule-based skill comparison
- AI screening
- ranking
- separate mobile database
- separate Node/Express backend

## Requirements

- Node.js and npm
- Expo Go or Android emulator
- Laravel backend running from the parent project
- MySQL running for the Laravel backend

## Install

From the `mobile` folder:

```bash
npm install
```

## Run With Local Web Preview Or Emulator

The mobile API client reads `EXPO_PUBLIC_BACKEND_URL` and falls back to `http://127.0.0.1:8000`.

If the mobile runtime can reach the Laravel backend at `http://127.0.0.1:8000`, start Expo:

```bash
npx.cmd expo start
```

## Run On A Physical Phone

For physical phone testing, `127.0.0.1` will not work because it points to the phone itself.

Start Laravel from the project root:

```bash
php artisan serve --host=0.0.0.0 --port=8000
```

Start Expo with the PC IP address:

```powershell
cd mobile
$env:EXPO_PUBLIC_BACKEND_URL="http://<PC_IP>:8000"
npx.cmd expo start --lan -c
```

Example:

```powershell
$env:EXPO_PUBLIC_BACKEND_URL="http://192.168.18.71:8000"
npx.cmd expo start --lan -c
```

Phone and PC must be on the same Wi-Fi. Allow firewall access if prompted.

The backend URL must point to Laravel without `/api` at the end. The mobile client adds `/api` automatically.

## Useful Commands

```bash
npx.cmd tsc --noEmit
npx.cmd expo start
npm.cmd run android
```

## Notes For Demo

- The backend source of truth is Laravel.
- The database source of truth is MySQL.
- The mobile app must use Laravel API routes only.
- Job seekers only see active jobs.
- Applying from mobile creates records that employers can see on web.
- Employer web status updates are visible to the job seeker on mobile after refresh.
