NOCIS - National Olympic Academy of Indonesia System
NOCIS is a comprehensive web-based platform designed to manage sports events and the recruitment of volunteers/workers for the National Olympic Committee of Indonesia. It connects administrators (committee members) with potential candidates (volunteers/professionals) through a streamlined application and management process.

*🏗 System Architecture*
The project is built using the Laravel framework (PHP), following the standard MVC (Model-View-Controller) architecture.

Backend: Laravel 10.x / PHP 8.x
Frontend: Blade Templates, Tailwind CSS, Alpine.js (minimal), Vanilla JS.
Database: MySQL (Relational Schema).
Key User Roles
Admin:

Access to the /admin dashboard.
Full control over Events, Sports, Job Categories, and Job Openings.
Review and manage candidate applications.
Manage system profiles and passwords.
Customer (Candidate/Volunteer):

Access to the public landing page and /dashboard.
Can browse available jobs and apply.
Manage personal profile, social media links, and upload CVs.
Track application status (Pending, Approved, Rejected).

📦 Key Modules & Features
1. Event Management
Scope: Admin
Function: Create, edit, and manage sports events (e.g., "Asian Games 2026").
Details: Events track Status (Planning, Upcoming, Active, Completed), Dates, Venue, and associated Sports.
Analytics: Dashboard provides visual insights into event performance and applicant trends.

2. Sports & Categories
Scope: Admin
Sports: Manage specific sports codes (e.g., AQ01 for Aquatics).
Categories: Define volunteer roles (e.g., Liaison Officer, Medical Staff) with specific requirements (Certifications, Shift Hours).

3. Recruitment & Job Board
Scope: Public / Admin
Job Openings: Admins create specific openings linked to an Event and Category (e.g., "Medical Staff for Aquatics").
Tracks slots_available vs slots_filled.
Status: Open/Closed (Full).
Public Job Board: Candidates can search jobs by Title, Event, or Venue.
Live Search: Real-time filtering (AJAX).
Save Job: Candidates can bookmark jobs for later.

4. Application Application System
Scope: Customer / Admin
Application Flow: Candidates apply to open jobs.
Review Process:
Admins review applicants via a dedicated "Review Board".
Reviewers can see the candidate's Profile photo, CV, and Social Media.
Actions: Approve (fills a slot) or Reject.
Feedback: Changes are reflected instantly on the Customer Dashboard.

5. Customer Dashboard
Scope: Customer
My Applications: View history and status of all applications.
Profile Management:
Personal Info: Name, DOB, Address, Phone.
CV Upload: Direct upload for PDF resumes.
Social Media: Centralized modal to manage LinkedIn, Instagram, TikTok links.
Saved Jobs: Quick access to bookmarked opportunities.

6. Admin Tools
Scope: Admin
Dashboard: Real-time statistics (Active Events, Open Jobs, Total Candidates).
Search & Filters: Comprehensive filtering for Applications (by Status, Job, Event) and Workers.
Flash Messages: Green/Red toast notifications for actions (Create/Update).

*🚀 Setup & Installation*
1. Clone Repository
git clone https://github.com/nocis-repo/project.git
cd nocis

2. Install Dependencies
composer install
npm install

3. Environment Setup
Copy .env.example to .env.
Configure Database credentials (DB_DATABASE, DB_USERNAME, DB_PASSWORD).

4. Database Migration & Seeding
php artisan migrate --seed
Seeding creates default Admin (admin/admin123) and sample Events.

5. Run Application
# Terminal 1: Laravel Server
php artisan serve
# Terminal 2: Vite (Frontend Assets)
npm run dev
