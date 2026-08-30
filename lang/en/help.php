<?php

/**
 * resources/lang/en/help.php
 *
 * English mirror of resources/lang/id/help.php.
 * Keep the array structure identical between locales so the views never break
 * when the app locale is switched.
 */

return [

    'back' => 'Back',

    'menu' => [
        'label'   => 'Help & Info',
        'guide'   => 'System Guide',
        'sop'     => 'Company SOP',
        'support' => 'Contact IT Support',
    ],

    'guide' => [
        'title'    => 'System Guide',
        'subtitle' => 'Everything you need to know to use the app smoothly.',

        'sections' => [
            [
                'icon'  => 'compass',
                'title' => 'Where to Start?',
                'body'  => "The Dashboard is your home screen. There you can see your career progress, pending tasks, and (for supervisors) a summary of your team's performance.",
            ],
            [
                'icon'  => 'map',
                'title' => 'How to View Career Path',
                'body'  => "Tap the 'My Career Path' card on the Dashboard, or open the Career menu from the navigation. You'll see your current level, collected EXP, and requirements for the next level.",
            ],
            [
                'icon'  => 'check-square',
                'title' => 'How to Complete Tasks',
                'body'  => "Open the Task menu, pick an evaluation (Multiple Choice, Survey, or File Upload), then follow the instructions. Once completed, you'll earn EXP based on the task type.",
            ],
            [
                'icon'  => 'credit-card',
                'title' => 'Why Are Tasks Locked?',
                'body'  => 'Some tasks require you to upload your ID Badge Photo in the Profile page first. This ensures your identity is validated before working on evaluations.',
            ],
            [
                'icon'  => 'user',
                'title' => 'Updating Profile Data',
                'body'  => 'Open the Profile menu to change your name, photo, email, or password. Changes will sync across the whole app immediately.',
            ],
        ],
    ],

    'sop' => [
        'title'    => 'Company SOP',
        'subtitle' => 'Official policies and procedures you should know.',
        'updated'  => 'Updated',

        'items' => [
            [
                'icon'       => 'clock',
                'updated_at' => '2026',
                'title'      => 'Attendance Policy',
                'body'       => 'Employees must clock in and out through the system. Being more than 15 minutes late without notice will be recorded as a minor violation.',
            ],
            [
                'icon'       => 'calendar',
                'updated_at' => '2026',
                'title'      => 'Leave Request Policy',
                'body'       => 'Annual leave requests must be submitted at least 3 working days in advance through your direct supervisor. Emergency leave (sick/urgent) can be submitted on the same day with supporting evidence.',
            ],
            [
                'icon'       => 'shield',
                'updated_at' => '2026',
                'title'      => 'Employee Code of Conduct',
                'body'       => 'Every employee must safeguard company data confidentiality, act professionally toward colleagues, and avoid any form of conflict of interest.',
            ],
            [
                'icon'       => 'alert-triangle',
                'updated_at' => '2026',
                'title'      => 'Workplace Safety Policy',
                'body'       => 'Use appropriate personal protective equipment (PPE) for your work area. Report any potential hazard immediately to your supervisor or the nearest safety team.',
            ],
            [
                'icon'       => 'trending-up',
                'updated_at' => '2026',
                'title'      => 'Evaluation & Promotion Policy',
                'body'       => "Career level promotions are determined by accumulated EXP from completed evaluations, plus a direct supervisor's assessment each review period.",
            ],
        ],
    ],

    'support' => [
        'title'        => 'Contact IT Support',
        'subtitle'     => 'Having trouble with the app? Reach out through any channel below.',
        'hours_label'  => 'Service hours',
        'hours'        => 'Mon–Fri, 08:00–17:00 (GMT+7)',
        'call_title'   => 'Call',
        'wa_desc'      => 'Fastest response',
        'wa_message'   => 'Hi IT Support, I need help with the app.',
        'mail_subject' => 'App Support Request',
    ],

];
