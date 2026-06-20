-- Seed Data for Team Incubation NGO Platform

-- 1. Insert Base Roles
INSERT INTO `roles` (`id`, `name`, `description`) VALUES
(1, 'Super Admin', 'Full system access, role, and server control'),
(2, 'Administrator', 'General administration access, user approvals'),
(3, 'Content Manager', 'Publishes blogs, media files, and pages content'),
(4, 'Project Manager', 'Manages NGO Projects and Events'),
(5, 'Event Manager', 'Schedules, tracks, and registers events'),
(6, 'Campaign Manager', 'Orchestrates collaborative campaigns'),
(7, 'Internship Manager', 'Manages applications, interviews, and onboarding'),
(8, 'Volunteer Coordinator', 'Matches volunteers to campaigns and events'),
(9, 'Certificate Manager', 'Issues, revokes, and imports certificate records'),
(10, 'Communications Manager', 'Handles email templates, contact inquiries'),
(11, 'Report Viewer', 'Access to read and export demographic statistics'),
(12, 'Incubant', 'Standard user/profile account role');

-- 2. Insert Permissions
INSERT INTO `permissions` (`id`, `name`, `description`) VALUES
(1, 'access_admin', 'Access to administration back-end dashboard'),
(2, 'manage_users', 'Edit status, assign roles to user registry'),
(3, 'manage_roles', 'Modify role-permissions configurations'),
(4, 'manage_projects', 'Create, update, delete Projects'),
(5, 'manage_events', 'Schedules events, custom questions, record attendance'),
(6, 'manage_campaigns', 'Create campaigns, upload reports, target stats'),
(7, 'manage_internships', 'Review applications, schedule interviews, grade tasks'),
(8, 'manage_volunteers', 'Approve volunteers, log volunteer hours'),
(9, 'manage_certificates', 'Generate, import, upload, and revoke certificates'),
(10, 'manage_banners', 'Manage homepage slides and announcement bars'),
(11, 'manage_content', 'Manage testimonials, legacy timeline, news updates, team, partners'),
(12, 'view_reports', 'Read and download CSV/XLSX analytics and audit logs'),
(13, 'manage_settings', 'Modify system, storage, and mail settings');

-- 3. Mapping Permissions to Roles (Role-Permissions Mapping)
-- Super Admin (Role 1) gets all permissions (1-13)
INSERT INTO `role_permissions` (`role_id`, `permission_id`) VALUES
(1, 1), (1, 2), (1, 3), (1, 4), (1, 5), (1, 6), (1, 7), (1, 8), (1, 9), (1, 10), (1, 11), (1, 12), (1, 13);

-- Administrator (Role 2) gets general control except database setup
INSERT INTO `role_permissions` (`role_id`, `permission_id`) VALUES
(2, 1), (2, 2), (2, 4), (2, 5), (2, 6), (2, 7), (2, 8), (2, 9), (2, 10), (2, 11), (2, 12);

-- Project/Event Managers
INSERT INTO `role_permissions` (`role_id`, `permission_id`) VALUES
(4, 1), (4, 4), (4, 5), (4, 11),
(5, 1), (5, 5);

-- Specialty Managers
INSERT INTO `role_permissions` (`role_id`, `permission_id`) VALUES
(6, 1), (6, 6),
(7, 1), (7, 7),
(8, 1), (8, 8),
(9, 1), (9, 9),
(10, 1), (10, 11),
(11, 1), (11, 12);

-- 4. Certificate Types
INSERT INTO `certificate_types` (`id`, `code`, `name`, `description`) VALUES
(1, 'INTERNSHIP', 'Internship Certificate', 'Issued to interns completing their assigned tenure'),
(2, 'VOLUNTEER', 'Volunteer Certificate', 'Recognizing voluntary contributions in campaigns/events'),
(3, 'EVENT_PARTICIPATION', 'Event Participation Certificate', 'Confirming active participation in seminars or conclaves'),
(4, 'APPRECIATION', 'Appreciation Certificate', 'Recognizing excellence or specific contributions to the NGO'),
(5, 'COURSE_COMPLETION', 'Course Completion Certificate', 'Given for completing specific training modules'),
(6, 'AUTH_LETTER', 'Authorization Letter', 'Official authorization document'),
(7, 'MOU', 'MOU/Association', 'Collaboration and association agreement'),
(8, 'RECOMMENDATION', 'Recommendation Letter', 'Official LOR from supervisor/directors'),
(9, 'EXPERIENCE', 'Experience Certificate', 'Employee/Intern experience verification statement'),
(10, 'CUSTOM', 'Custom Certificate', 'Admin configured custom certificate format');

-- 5. Sample Milestones (Journey Roadmap - Growing Tree Roadmap)
INSERT INTO `legacy_milestones` (`year`, `title`, `description`, `impact_stats`, `display_order`, `active`) VALUES
(2014, 'Genesis of Team Incubation', 'Founded by a group of passionate psychology and academic professionals to guide marginalized students in higher education.', '10 Founders', 1, 1),
(2015, 'First PEPP Launch', 'Premier Entrance Preparation Program (PEPP) rolled out to prepare students for Central University entrance exams.', '50+ Students', 2, 1),
(2016, 'NEST Remediation Success', 'Launched NEST (Nurturing and Exploring Students Talent) at Himayathul Islam school to address learning difficulties.', '36 UP Students', 3, 1),
(2017, 'Teen\'s Outreach Program (TOP)', 'Introduced TOP targeting SSLC students, aiming for academic improvements and creative life skills.', '120+ Beneficiaries', 4, 1),
(2018, 'Urava Talking Audio Library', 'Pioneered the "Happy Reading Campaign" recording digital talking books for print and visually impaired students.', '400+ Volunteers', 5, 1),
(2020, 'Digital Transition', 'Transitioned coaching, workshops, and conclaves online to support students globally during the pandemic.', '5000+ Online reach', 6, 1),
(2023, 'Alchemy Conclave 2023', 'Conducted a grand national career and academic conclave, aligning young graduates to industry choices.', '1000+ Attendees', 7, 1),
(2025, '11 Years of Impact', 'Sustained youth development programs, expanding campaigns across Kerala and southern states.', '15000+ Total Impact', 8, 1);

-- 6. Sample Partners
INSERT INTO `partners` (`name`, `logo`, `website`, `category`, `description`, `collaboration_start`, `display_order`, `active`) VALUES
('Ability Centre, Pulikkal', 'media/partners/ability.png', 'https://abilitycentre.in', 'Disability Support', 'Main collaborator in Happy Reading Campaign for Urava Audio Library.', '2018-09-01', 1, 1),
('Himayathul Islam School', 'media/partners/himayathul.png', NULL, 'Educational Partner', 'First academic partner hosting the NEST student remediation trials.', '2016-01-10', 2, 1),
('Codexives Developers', 'media/partners/codexives.png', 'https://codexives.com', 'Technology Sponsor', 'Initial platform development and IT sponsorship.', '2018-06-01', 3, 1);

-- 7. Default Announcement Bar and Hero Banners
INSERT INTO `banners` (`title`, `subtitle`, `desktop_image`, `mobile_image`, `cta_label`, `cta_url`, `display_location`, `display_order`, `active`) VALUES
('Welcome to Team Incubation', 'Nourishing dreams, educating, and empowering youth from marginalized backgrounds.', 'media/banners/home_banner1.jpg', 'media/banners/home_banner1_mobile.jpg', 'Join As Volunteer', '/volunteer', 'home_hero', 1, 1),
('Premier Entrance Prep (PEPP)', 'Nourish your academic potential and gain admissions to central universities.', 'media/banners/home_banner2.jpg', 'media/banners/home_banner2_mobile.jpg', 'Explore PEPP', '/projects/pepp', 'home_hero', 2, 1),
('Announcing Internships 2026', 'Applications are open for dynamic hybrid roles in social work and content curation.', 'media/banners/home_banner3.jpg', 'media/banners/home_banner3_mobile.jpg', 'Apply Now', '/internships', 'home_hero', 3, 1),
('Verify Certificate', 'Use our secure portal to instantly verify certificates and letters.', 'media/banners/cert_banner.jpg', NULL, NULL, NULL, 'certificates', 1, 1);

-- 8. Sample Testimonials
INSERT INTO `testimonials` (`author_name`, `author_role`, `author_institution`, `author_photo`, `content`, `rating`, `active`) VALUES
('Naveen K.', 'Alumnus', 'Central University of Pondicherry', NULL, 'PEPP entrance program changed my career path. I got the study resources and mentorship needed to clear the PG entrance exam.', 5, 1),
('Shadiya M.', 'NEST Volunteer', 'TISS Calicut', NULL, 'Volunteering with the NEST child development program gave me real-world exposure to educational psychology.', 5, 1),
('Prof. Abdul Latheef', 'Academic Consultant', 'University of Calicut', NULL, 'Team Incubation serves as a crucial bridge for students, directing resources where schools fall short.', 5, 1);


-- 9. Sample news/blog updates
INSERT INTO `news_updates` (`title`, `slug`, `content`, `banner_image`, `status`) VALUES
('How PEPP Supports Higher Education Aspirations', 'pepp-higher-education-support', '<p>Premier Entrance Preparation Program (PEPP) has been a flagship project under Team Incubation. It focuses on the career development of students, guiding them to choose their academic path based on genuine aptitude instead of social expectations. Over the years, PEPP has helped hundreds of students gain entry to prestigious central universities.</p><p>We provide students with counseling, structured study material, mock tests, and a dedicated network of student-mentors.</p>', 'media/news/pepp_news.jpg', 'published'),
('Volunteer Stories: Happy Reading Campaign', 'happy-reading-campaign-stories', '<p>The Happy Reading Campaign gathered over 400 volunteers in Kerala to record audio books for visually impaired students. In collaboration with the Ability Centre in Pulikkal, this campaign made over a hundred textbooks and magazines accessible digitally. We thank all the students, teachers, and home-makers who contributed their voice files to this cause.</p>', 'media/news/happy_reading.jpg', 'published');

-- 10. Sample Projects
INSERT INTO `projects` (`name`, `slug`, `category`, `short_description`, `full_description`, `objectives`, `target_beneficiaries`, `location`, `start_date`, `end_date`, `status`, `banner_image`, `featured_image`, `featured`) VALUES
('Premier Entrance Preparation Program (PEPP)', 'pepp', 'Academic Guidance', 'Empowering students from marginalized backgrounds to clear national entrance exams and gain admission to central universities.', '<p>PEPP (Premier Entrance Preparation Program) is a flagship initiative of Team Incubation designed to identify, mentor, and guide promising students from underprivileged communities towards central universities.</p><p>By providing quality preparation, mentoring, study material, and psychological support, PEPP dismantles academic inequality and helps candidates secure admission into elite higher education institutions across India.</p><p>Our dedicated team of mentors, who are central university alumni themselves, work closely with aspirants to ensure they have the academic depth and confidence required to excel.</p>', '• Provide top-tier academic coaching\n• Offer career counseling and psychological mentorship\n• Foster a network of student-mentors\n• Achieve higher representation of marginalized groups in central universities', 'Undergraduate and postgraduate aspirants from marginalized and economically backward communities.', 'Calicut, Kerala, India (Hybrid)', '2015-06-01', NULL, 'active', 'media/projects/pepp_banner.jpg', 'media/projects/pepp_featured.jpg', 1),
('Nurturing and Exploring Students Talent (NEST)', 'nest', 'Child Development', 'A comprehensive remediation program for primary school students with learning difficulties and academic gaps.', '<p>NEST is a targeted remediation program focusing on children with academic difficulties in school settings.</p><p>In collaboration with local public schools, NEST identifies students needing extra care and implements individualized coaching, creative therapies, and cognitive exercises.</p><p>Our volunteers work closely with parents and school teachers to nurture every child\'s inherent talent, overcoming learning boundaries and building base confidence.</p>', '• Early detection of learning gaps\n• Formulate customized psychological and academic tools\n• Run weekly remediation sessions\n• Enhance fundamental reading and numerical skills', 'Primary and upper-primary school pupils experiencing educational delays.', 'Calicut, Kerala', '2016-09-15', NULL, 'active', 'media/projects/nest_banner.jpg', 'media/projects/nest_featured.jpg', 1),
('Urava Digital Talking Library', 'urava', 'Accessibility Support', 'Creating and recording audiobooks and accessible digital resources for visually and print-impaired learners.', '<p>Urava (Happy Reading Campaign) is an accessibility project by Team Incubation aimed at recording and distributing digital talking books for visually impaired and print-disabled students.</p><p>With over 400 volunteer readers contributing voices, the campaign has digitised academic textbooks, magazines, and literary masterpieces into high-quality audio files, ensuring equal educational opportunities for everyone.</p>', '• Produce accessible audio library catalog\n• Mobilise volunteer voice recorders\n• Distribute free audio materials to educational institutions\n• Enhance screen reader accessibility', 'Visually impaired, dyslexic, and print-disabled students.', 'Calicut, Kerala', '2018-10-01', NULL, 'active', 'media/projects/urava_banner.jpg', 'media/projects/urava_featured.jpg', 1);

