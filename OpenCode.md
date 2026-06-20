Act as an Expert Fullstack Laravel Developer and UI/UX Engineer. I want to build a web application for an Indonesian Independence Day ("17an") competition management system. 

### PROJECT OVERVIEW
The app manages competition registrations, live participant lounges, tournament brackets, and certificate generation. The brand name is "Kafeinarts Management Tools".

### TECH STACK & CONSTRAINTS
- Backend: Laravel 13 (sudah terinstall).
- Frontend/Auth: Laravel Breeze (Blade + Tailwind CSS + Alpine.js).
- Interactivity: Laravel Livewire 3 (Crucial for the multi-step wizard state management and real-time lounge updates).
- Database: MySQL
- Performance: Must be optimized for HIGH TRAFFIC (Use Redis for caching, Laravel Queues for heavy tasks like PDF generation).
- UI/UX: Fully responsive, must include a Light/Dark mode toggle.

### CORE FEATURES & WORKFLOWS

#### 1. Layout & Navigation
- Navbar: Extremely minimalist. It should ONLY contain the logo/text "Kafeinarts Management Tools" on the left, and the Light/Dark mode toggle + Login button on the right. No other menu items in the public navbar.

#### 2. Authentication & Admin Access
- Use Laravel Breeze for the admin panel.
- CONSTRAINT: Only exactly TWO (2) admin accounts are allowed to be registered (for 2 physical laptops). 
- Implementation: Create a custom middleware or override the registration controller to block any new user registration if the `users` table already has 2 records. Provide a seeder to create the initial 2 admin accounts.

#### 3. Public View (No Login Required)
- **Lounge Area:** A real-time, auto-refreshing (via Livewire polling or Echo) list displaying the names of all registered participants.
- **Competition Cards:** Display available competitions as cards. 
  - Each card must feature an auto-generated/inline SVG vector illustration relevant to the competition (e.g., a sack race, tug of war, etc.). Use a predefined PHP array of SVG strings mapped to competition slugs/types to render these dynamically.
  - Cards must show the Age Category (Anak-anak, Remaja, Dewasa) and Type (Individu/Tim).

#### 4. Registration Wizard (Livewire Multi-step Form)
- A multi-step wizard form for participants. 
- State Management: Data must be held in the frontend state (Livewire component properties) step-by-step, and ONLY saved to the database on the final confirmation step.
- Form Fields:
  - Step 1: Full Name, Gender, Age (Must map to categories: Anak-anak [<=12], Remaja [13-17], Dewasa [>=18]).
  - Step 2: Contact Info (Phone Number OR Email - at least one required), Social Media handles (Optional).
  - Step 3: Competition Selection (Filter available competitions based on the age calculated in Step 1).
  - Step 4: Team Details (If "Tim" is selected, allow adding 2 or more team members' names. If "Individu", skip this).
  - Step 5: Review & Submit.

#### 5. Admin / Committee Dashboard (Breeze Layout)
- **Queue List:** A real-time dashboard showing the queue of participants/teams for ongoing competitions.
- **Tournament Bracket:** For team competitions, generate a visual bracket system (Single Elimination). Allow admins to input match results to advance teams to the next round.
- **Certificate Generation:** 
  - Ability to generate and download certificates for all participants.
  - Special distinct certificate templates/layouts for Juara 1 (1st), Juara 2 (2nd), and Juara 3 (3rd) based on the competition they registered for. Use `barryvdh/laravel-dompdf` or `spatie/browsershot` dispatched via Laravel Queues to prevent blocking during high traffic.

### DATABASE SCHEMA GUIDELINES
- `users`: id, name, email, password, role (admin).
- `participants`: id, name, gender, birth_date/age, phone, email, social_media.
- `competitions`: id, name, slug, type (individu/tim), age_category, svg_illustration_key.
- `registrations`: id, participant_id (nullable if team), competition_id, team_name (nullable), rank (1,2,3 or null).
- `team_members`: id, registration_id, name.
- `matches`: id, competition_id, team_a_registration_id, team_b_registration_id, winner_registration_id, round.

### EXECUTION INSTRUCTIONS FOR AI AGENT
Please do not write all code at once. Follow this step-by-step execution plan and wait for my approval after each step:
1. Setup database migrations, models, and relationships.
2. Setup Breeze, Dark Mode toggle, and the 2-user registration limit logic.
3. Build the Public Layout (Navbar, Lounge, and SVG Competition Cards).
4. Build the Livewire Registration Wizard with state management and age-category filtering.
5. Build the Admin Dashboard (Queue list, Bracket logic, and Queued Certificate generation).

Acknowledge this prompt, confirm your understanding of the constraints (especially the 2-admin limit and high-traffic optimization), and propose the database schema for Step 1.