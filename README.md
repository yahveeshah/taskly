# Taskly

A warm, focused task manager built with Laravel — organize your work, track progress, and stay ahead of every deadline.

---

## Features

### Authentication and accounts
- Session-based registration and login
- Personal mode or group/team mode at signup
- Manager and member roles with team codes (`TEAM-xxxx`)
- Profile editing (name, email, password)
- Role-based authorization via policies

### Task management
- Full task CRUD with title, description, priority, and deadline
- Three priority columns: High, Medium, Low
- Drag-and-drop between columns using SortableJS
- Status workflow: Pending, In Progress, Completed
- Deadline alerts for tasks due soon
- Managers can assign tasks to team members and filter by member

### Dashboard and insights
- Personalized greeting with time-of-day message
- Streak tracker for consecutive completion days
- Stat cards: Total, Completed, In Progress, Pending
- Weekly summary with completion rate and motivating copy
- Tasks due today and upcoming deadlines (next 7 days)

### Progress and visualization
- Progress page with encouraging status cards
- Vertical bar graph of task status breakdown
- Deadline calendar widget on the graph page
- Track page for overdue and upcoming task overview

### Team mode
- Manager team dashboard with shareable team code
- Member cards with completion stats and progress bars
- Per-member drilldown with task list and progress graph
- Members can update their own task status; managers control creation and assignment

### Design and UX
- Two complete themes: **Lavender** and **Vintage**
- Collapsible sidebar with icon-only mode and tooltips
- Topbar with page title, date, and profile dropdown on every authenticated page
- Smooth page transitions and theme persistence via `localStorage`
- Avatar colors generated consistently from the user's name
- Flash message banners for success feedback
- Responsive layout for tablet and mobile

---

## Tech stack

| Layer | Technology |
|-------|------------|
| Backend | Laravel, PHP |
| Database | SQLite |
| Views | Blade templates |
| Frontend | Vanilla JavaScript, inline CSS |
| Drag and drop | SortableJS (CDN) |
| Fonts | Cormorant Garamond, DM Sans (Google Fonts) |
| Auth | Laravel session guard |

---

## Themes

### Lavender
The default theme — soft lavender accents (`#C7A0CB`), navy sidebar (`#000080`), gold highlights (`#F6BE00`), and a light lavender page background with a subtle dot texture. Clean, modern, and calm.

### Vintage
A warm editorial feel — espresso sidebar (`#2C1810`), cream backgrounds (`#FAF8F5`, `#FAF7F2`), gold accents (`#D4A853`), and tan borders (`#C4B49A`). Same layout and features, richer and more tactile.

Both themes are toggled via `data-theme="vintage"` on the `<html>` element and saved in `localStorage` under `taskly-theme`.

---

## Local setup

### Requirements
- PHP 8.2+
- Composer
- [Laravel Herd](https://herd.laravel.com/) (recommended) or any local PHP server

### Installation

```bash
# Clone or navigate to the project
cd C:\Users\Admin\Herd\project

# Install PHP dependencies
composer install

# Copy environment file and generate app key
cp .env.example .env
php artisan key:generate

# Create the SQLite database file
touch database/database.sqlite

# Run migrations
php artisan migrate

# (Optional) Seed or create a user via /register in the browser
```

### Running locally

**With Laravel Herd** — if the project lives under your Herd directory, it is served automatically:

```
http://project.test
```

**With Artisan** — alternative for any environment:

```bash
php artisan serve
```

Then open `http://127.0.0.1:8000`.

### First steps
1. Visit the home page and click **Register**
2. Choose **Personal Use** or **Group / Team Use**
3. For teams: managers receive a team code to share; members join with that code
4. Log in and explore Dashboard, Tasks, Progress, Graph, and Track

### Running tests

```bash
php artisan test
```

---

## Screenshots

> Screenshots coming soon.

| Page | Preview |
|------|---------|
| Home | _Landing page with hero, features, and CTA_ |
| Dashboard | _Greeting, stats, weekly summary, due today_ |
| Tasks | _Priority columns with drag-and-drop cards_ |
| Progress | _Status cards with encouraging messages_ |
| Graph | _Bar chart and deadline calendar_ |
| Team | _Manager dashboard with member overview_ |
| Lavender theme | _Default lavender and navy palette_ |
| Vintage theme | _Warm cream and espresso palette_ |

---

## Project structure

```
app/
  Http/Controllers/   Task, Team, Auth, Profile controllers
  Models/             User, Task, Team
  Policies/           TaskPolicy
resources/views/
  components/         layout.blade.php (shared authenticated shell)
  tasks/              Dashboard, tasks, progress, graph, track
  team/               Team index and member drilldown
  auth/               Login and register
routes/web.php        All application routes
```

---

## License

This project is open-source software. See the repository license file for details.
