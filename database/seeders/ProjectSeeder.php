<?php

namespace Database\Seeders;

use App\Models\Project;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    public function run(): void
    {
        // Delete all existing projects first to avoid duplicates on re-seed
        Project::truncate();

        $projects = [

            // ── Project 1 ────────────────────────────────────────────
            [
                'title'       => 'E-Commerce Website',
                'description' => 'A full-featured online store built with Laravel 12 and Bootstrap 5. '
                               . 'Includes product listings, shopping cart, user authentication, order '
                               . 'management, and Stripe payment gateway integration. Admin panel allows '
                               . 'managing products, categories, and viewing orders in real time.',
                'url'         => 'https://github.com',
                'image'       => null, // No image — placeholder will show via image_url accessor
            ],

            // ── Project 2 ────────────────────────────────────────────
            [
                'title'       => 'Portfolio Management System',
                'description' => 'This very project! A dynamic portfolio CMS built with Laravel 12. '
                               . 'Features an admin panel to manage skills, projects, and contact messages. '
                               . 'Includes file upload for project thumbnails, form validation, '
                               . 'search functionality, and read/unread message tracking.',
                'url'         => null,
                'image'       => null,
            ],

            // ── Project 3 ────────────────────────────────────────────
            [
                'title'       => 'Task Management App',
                'description' => 'A Kanban-style project management tool built with Laravel and Vue.js. '
                               . 'Users can create boards, add tasks with deadlines and priorities, '
                               . 'assign tasks to team members, and drag-and-drop between columns. '
                               . 'Real-time updates powered by Laravel Echo and Pusher.',
                'url'         => 'https://github.com',
                'image'       => null,
            ],

            // ── Project 4 ────────────────────────────────────────────
            [
                'title'       => 'Blog Platform',
                'description' => 'A multi-author blog platform with role-based access (Admin, Author, Reader). '
                               . 'Features include a rich text editor (TinyMCE), post categories and tags, '
                               . 'comment system with moderation, search, and an RSS feed. '
                               . 'Built with Laravel 12 using Blade templates.',
                'url'         => null,
                'image'       => null,
            ],

            // ── Project 5 ────────────────────────────────────────────
            [
                'title'       => 'Student Result System',
                'description' => 'A school management system for tracking student results and attendance. '
                               . 'Teachers can enter marks per subject, the system auto-calculates GPA '
                               . 'and generates printable result sheets. Built with Laravel, MySQL, '
                               . 'and Bootstrap 5 with role separation for Admin, Teacher, and Student.',
                'url'         => 'https://github.com',
                'image'       => null,
            ],

            // ── Project 6 ────────────────────────────────────────────
            [
                'title'       => 'Restaurant Food Ordering',
                'description' => 'An online food ordering system for a local restaurant. Customers can '
                               . 'browse the menu, add items to a cart, and place orders for delivery or '
                               . 'pickup. The kitchen dashboard shows incoming orders in real time. '
                               . 'Built with Laravel, Alpine.js, and Tailwind CSS.',
                'url'         => null,
                'image'       => null,
            ],

        ];

        foreach ($projects as $project) {
            Project::create($project);
        }
    }
}