-- phpMyAdmin SQL Dump
-- version 4.9.7
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Mar 05, 2022 at 09:33 AM
-- Server version: 5.6.41-84.1
-- PHP Version: 7.3.32

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `zaptaies_tutors`
--

-- --------------------------------------------------------

--
-- Table structure for table `academic_classes`
--

CREATE TABLE `academic_classes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `course_id` int(11) DEFAULT NULL,
  `class_id` int(11) DEFAULT NULL,
  `teacher_id` int(10) NOT NULL,
  `student_id` int(10) NOT NULL,
  `lesson_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `start_date` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `end_date` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `duration` float DEFAULT NULL,
  `class_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `start_time` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `end_time` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `day` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `academic_classes`
--

INSERT INTO `academic_classes` (`id`, `title`, `course_id`, `class_id`, `teacher_id`, `student_id`, `lesson_name`, `start_date`, `end_date`, `duration`, `class_type`, `start_time`, `end_time`, `day`, `status`, `created_at`, `updated_at`) VALUES
(16, 'title1', 14, NULL, 1149, 1158, NULL, '2022-03-01', '2022-02-29', 2, 'casual', '11:00 PM', '1:00 AM', '1', 'completed', '2022-03-01 02:15:53', '2022-03-01 02:15:53'),
(17, 'title2', 14, NULL, 1149, 1158, NULL, '2022-03-02', '2022-02-29', 2, 'casual', '12:00 PM', '1:00 AM', '2', NULL, '2022-03-01 02:15:53', '2022-03-01 02:15:53'),
(18, 'title3', 14, NULL, 1149, 1158, NULL, '2022-02-28', '2022-02-29', 2, 'casual', '1:00 PM', '3:00 AM', '3', NULL, '2022-03-01 02:15:53', '2022-03-01 02:15:53'),
(19, 'title3', 14, NULL, 1149, 1158, NULL, '2022-02-28', '2022-02-29', 2, 'casual', '1:00 PM', '3:00 AM', '4', NULL, '2022-03-01 02:15:53', '2022-03-01 02:15:53'),
(20, 'Laravell Class', 15, 1354287, 1127, 1135, 'Lesson1', '2022-03-01', '2022-03-05', 2, 'casual', '06:10 PM', '06:30 PM', '1', 'started', '2022-03-01 06:31:12', '2022-03-01 08:39:37'),
(21, NULL, 15, NULL, 1127, 1135, NULL, '2022-02-28', '2022-02-29', 2, 'casual', '12:00 PM', '1:00 AM', '2', NULL, '2022-03-01 06:31:12', '2022-03-01 06:31:12'),
(22, NULL, 15, NULL, 1127, 1135, NULL, '2022-02-28', '2022-02-29', 2, 'casual', '1:00 PM', '3:00 AM', '3', NULL, '2022-03-01 06:31:12', '2022-03-01 06:31:12'),
(23, NULL, 15, NULL, 1127, 1135, NULL, '2022-02-28', '2022-02-29', 2, 'casual', '1:00 PM', '3:00 AM', '4', NULL, '2022-03-01 06:31:12', '2022-03-01 06:31:12'),
(24, NULL, 16, NULL, 1127, 1135, NULL, '2022-02-28', '2022-02-29', 2, 'casual', '11:00 PM', '1:00 AM', '1', NULL, '2022-03-01 06:35:28', '2022-03-01 06:35:28'),
(25, NULL, 16, NULL, 1127, 1135, NULL, '2022-02-28', '2022-02-29', 2, 'casual', '12:00 PM', '1:00 AM', '2', NULL, '2022-03-01 06:35:28', '2022-03-01 06:35:28'),
(26, NULL, 16, NULL, 1127, 1135, NULL, '2022-02-28', '2022-02-29', 2, 'casual', '1:00 PM', '3:00 AM', '3', NULL, '2022-03-01 06:35:28', '2022-03-01 06:35:28'),
(27, NULL, 16, NULL, 1127, 1135, NULL, '2022-02-28', '2022-02-29', 2, 'casual', '1:00 PM', '3:00 AM', '4', NULL, '2022-03-01 06:35:28', '2022-03-01 06:35:28'),
(28, NULL, 17, NULL, 1127, 1135, NULL, '2022-02-28', '2022-02-29', 2, 'casual', '11:00 PM', '1:00 AM', '1', NULL, '2022-03-01 06:35:55', '2022-03-01 06:35:55'),
(29, NULL, 17, NULL, 1127, 1135, NULL, '2022-02-28', '2022-02-29', 2, 'casual', '12:00 PM', '1:00 AM', '2', NULL, '2022-03-01 06:35:55', '2022-03-01 06:35:55'),
(30, NULL, 17, NULL, 1127, 1135, NULL, '2022-02-28', '2022-02-29', 2, 'casual', '1:00 PM', '3:00 AM', '3', NULL, '2022-03-01 06:35:55', '2022-03-01 06:35:55'),
(31, NULL, 17, NULL, 1127, 1135, NULL, '2022-02-28', '2022-02-29', 2, 'casual', '1:00 PM', '3:00 AM', '4', NULL, '2022-03-01 06:35:55', '2022-03-01 06:35:55');

-- --------------------------------------------------------

--
-- Table structure for table `blog`
--

CREATE TABLE `blog` (
  `id` int(10) UNSIGNED NOT NULL,
  `category_id` int(10) UNSIGNED DEFAULT NULL,
  `author_id` int(10) UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `meta_description` text COLLATE utf8mb4_unicode_ci,
  `content` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `visit_count` int(10) UNSIGNED DEFAULT '0',
  `enable_comment` tinyint(1) NOT NULL DEFAULT '1',
  `status` enum('pending','publish') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `created_at` int(10) UNSIGNED NOT NULL,
  `updated_at` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `blog`
--

INSERT INTO `blog` (`id`, `category_id`, `author_id`, `title`, `slug`, `image`, `description`, `meta_description`, `content`, `visit_count`, `enable_comment`, `status`, `created_at`, `updated_at`) VALUES
(21, 34, 1, 'How To Teach Your Kid Anything Easily', 'How-To-Teach-Your-Kid-Anything-Easily', '/store/1/default_images/blogs/blog1.jpg', '<p>The primary reason kids struggle with school is fear. And in most cases, it’s their parent\'s fault. I started tutoring math out of financial desperation. I’d just been fired from my job as a waiter, most of the proofreading in jobs in New York had been outsourced to India, and for the third time in my life, I was facing dire poverty.</p>', 'The primary reason kids struggle with school is fear. And in most cases, it’s their parent\'s fault.', '<p>I started tutoring math out of financial desperation. I’d just been fired from my job as a waiter, most of the proofreading in jobs in New York had been outsourced to India, and for the third time in my life, I was facing dire poverty.</p><p>The amount I had forgotten was startling at first, and I had excelled at math for my entire academic life. If you don’t use it, you really do lose it, so not so surprisingly, most parents cry uncle at around 4th-grade math. They don’t remember and don’t want to remember, perpetuating this attitude in their children.</p><p>Imagine doing anything, even something you’re great at, with the equivalent of a giant looming behind you and scrutinizing your every move. This is what parents do to their children. They hover and pounce on every mistake.</p><p>1) Have Empathy for Your Kids</p><p>In my first session with my first student, his mom skulked nervously behind us, then called me over about five minutes in. She asked if I noticed her kid had answered the last question incorrectly, and I immediately saw the problem\'s essence.</p><p>You have to rest and relax for your kid to be comfortable and wait until the end before you start to correct anything. This is how kids learn to check their work, and that making mistakes is not a big deal.</p><p>Mistakes are inevitable, but how we handle them isn’t. If you want your kid to be eternally terrified of math, then, by all means, continue to crowd and interrupt them perpetually. Chances are, you’ll make them wary of attempting to learn anything at all.</p><p>2) Give Yourself a Shot</p><p>Thankfully, this mom and most of the other parents understood what I was doing, and recognized their complicity in their children’s struggles. After that first session, mom left us alone, and her kid began to improve forthwith.</p><p>His parents were so encouraged they asked if I could help with other subjects. History, science, and English were all within my wheelhouse, but the kid went to a Catholic school and had to learn Latin.</p><p>I borrowed a copy of his Latin book and was completely demoralized. Latin is baffling, especially in the beginning. So many conjugations, and context matter. The way you speak to noblemen, peers, and slaves is practically like learning three different languages.</p><p>Still, I was broke, and this would mean more money. And did I actually have to know Latin? All I had to do was stay a few days ahead of the kid.</p><p>3) Be Honest</p><p>Our default state is to deny our ignorance, especially in front of children. We’re defensive and dismissive, and most kids can tell you’re just as lost as they are.</p><p>My solution was to admit this from the start. The kid asked me the point of learning Latin and I told him I didn’t know. Supposedly it helped with other languages, but maybe he could use it someday to impress pretentious people at cocktail parties. He asked me why some verbs broke the conjugation patterns, and I told him it was the ancients’ way of torturing us.</p><p>As a young child, he picked up the language quicker than I did, but had more difficulty with some of the subtleties. There were times I was mystified and talked out loud to myself in front of him.</p><p>I’m a math tutor. What on earth made me think I was equipped to teach Latin, let alone learn it?</p><p>The kid told me I seemed to be pretty good at everything else, so I’d probably get it, but there was a tinge of concern in his voice. The worse I did, the more likely he would fail, and he wanted Latin out of his life even more than I did.</p><p>Our mutual hatred of Latin brought us closer together. For the first time, we both understood we were in this together.</p><p>4) Dealing With Parents and Teachers Is a Part of Education</p><p>As he grew older, he started to ask me about his parents, who were Fox News conservatives. He told me he could see they were biased, but didn’t understand why, or know what to think.</p><p>I told him to think for himself and admit when he didn’t know something, and that he shouldn’t be afraid to change his mind in light of valid new evidence.</p><p>He complained about his teachers, one of whom wasn’t happy I’d taught him different approaches to algebra. Eventually, he learned how to do it the teacher’s way, but he’d needed another avenue to get there. We were both a bit angry, but I told him learning to deal with teachers is a part of education.</p><p>I kept my rancor to myself, but I also wanted to strangle his teacher. This kid had gone from a D average to a B+, and this teacher was still hounding him. No wonder so many people are lousy at math — their parents and teachers were against them and didn’t even realize it.</p><p>5) Embrace a Learning Attitude</p><p>If you aren’t willing to learn, it’s going to be difficult to teach, and the first step is accepting just how little we know. It’s hypocritical to pounce on your kid when you can’t do his homework either, and kids notice this and rightfully feel that you’re being unjust.</p><p>If you choose to have children, they should be your number one priority, and you’ll be surprised at how much learning (or relearning) will improve your cognition in general.</p><p>Reading and math are foundational skills, and learning math is humbling. While there is scant evidence to support that mathematics improves reasoning in general, you need it to understand science, and when puzzled by much of your child’s elementary school homework, at least a little uncertainty penetrates your views. You realize you don’t know nearly as much as you thought, and for most, this can mark a new beginning. As rigidity subsides, plasticity reemerges, and the habit of lifelong learning is the most valuable gift you can give to yourself and your child.</p>', 19, 1, 'publish', 1625091953, 1625094493),
(22, 37, 1, 'Better Relationship Between You and Your Student’s Parent', 'Better-Relationship-Between-You-and-Your-Student-s-Parent', '/store/1/default_images/blogs/blog2.jpg', '<p>The tutor-parent relationship is an important relationship and unfortunately greatly overlooked. Why is it important? Well, a good relationship between you and the student’s parent or guardian serves to help students perform better personally and academically. Fostering a relationship with them as a tutor can be challenging due to a number of factors, however, there are a number of ways to build this relationship and optimize students’ learning support system over time.</p>', 'The tutor-parent relationship is an important relationship and unfortunately greatly overlooked.', '<p>Compassion and Positivity is Everything</p><p>It can be stressful for parents or guardians to engage with you. There may be fear that their child isn’t doing as well as they should be, a lack of time to connect, or even language barriers. As a tutor, being compassionate and delivering information in a well-mannered tone does wonders in situations like this. It’s best to smile, make eye contact, be welcoming, and offer any advice if needed. One good trick to help ease parents or guardians when speaking to them is to first acknowledge something positive about their child that you’ve noticed no matter how small it is. For example, you may have noticed that they are more engaged in their lessons and asking more questions.</p><p>Be Proactive and Communicate Often</p><p>Some tutors may only speak to parents or guardians when there is a problem, creating a negative environment for both. That’s why it’s key to make the effort to speak with them often whether that be face to face, phone call or even an email. It’s best to keep them up to date about their child’s learning. Communicating often keeps them from feeling on high alert when you reach out and can even foster a beautiful friendship. Of course it is important to know that parents and guardians are busy! So make sure when you communicate with them that you’re planning ahead to find a time that works best for them.</p><p>Make the Parent or Guardian Feel Valued and Ask for Their Advice</p><p>A great way to build a relationship with parents or guardians is to involve them in their child’s education. This doesn’t mean just having them help with homework, but it could mean asking them to participate in school activities or events. Asking them if they would like to help organize an event is a great way to get to know them and give them a chance to meet other parents or guardians. It could also be something as simple as creating a lesson plan that requests input from them. Of course all of this depends on their schedule and if they have time to participate. In the end, the more you try to involve them in activities, the better.</p><p>Another great way to make them feel valued is to ask for their advice. If your student is displaying negative behaviors during a lesson, it is wise to seek out the advice of the parent or guardian. By asking for their advice, two things can occur. One, they may not know there is negative behavior to begin with as the student may not be displaying it at home. Two, you are building a better relationship with the parent or guardian by getting their input in this situation, which will build trust. Questions don’t just have to be about the student’s education when speaking to them, you can also ask questions about the student’s interest and plans that they have during the holidays. It is always important for you to conduct yourself professionally when dealing with a parent or guardian but the conversation doesn’t have to just be strictly about education.</p><p>Avoid Taking it Personally and Making Assumptions</p><p>Parents and guardians are human and they have their own stress to deal with whether it be at work or in their home life. It is best for you to always keep calm and never take anything they say to heart. You should always respond with questions that will help defuse the situation. For example a parent or guardian may say “You’re saying this because you’re out to get my child” and the best way to respond to a question like this is “I’m sorry you feel this way, please let me know why you think this?”</p><p>Also you shouldn’t make assumptions about a student’s home life. Unless it has been stated so, an assumption can cause many problems and can be insensitive to a family’s situation. You should not assume that the student lives with both parents or any parent for that matter. Now, in our globally diverse world you shouldn’t assume that the parent or guardian’s first language is English and should always confirm if they can speak English in the first place. Every student will have a different situation than the next. Assumptions lead to misunderstandings which can create more challenging circumstances for learning.</p><p>Building a relationship with a parent or guardian can sometimes be challenging. Keeping these ideas in mind can help you better connect with them. It’s good to remember, at the end of the day the better connection you have with them, the better they can help teach the student, ultimately allowing them to achieve academic success and foster a love for learning.</p>', 8, 1, 'publish', 1625093279, NULL),
(23, 36, 1014, '3 Laws to Become a Straight-A Student', '3-Laws-to-Become-a-Straight-A-Student', '/store/1014/blog3.jpg', '<p>In this article, I’ll explain the two rules I followed to become a straight-A student.&nbsp;If you take my advice, you’ll get better grades and lead a more balanced life too.</p>', 'In this article, I’ll explain the two rules I followed to become a straight-A student.', '<p>A strong academic record can open doors for you down the road. More importantly, through the process of becoming a straight-A student, you’ll learn values like hard work, discipline and determination.</p><h3 style=\"color: rgb(0, 0, 0); font-family: Lato, sans-serif; margin-right: 0px; margin-bottom: 16px; margin-left: 0px; padding: 0px; font-size: 24px;\">Rule #1: Always have a plan.</h3><div><div>(a) As the semester progresses, keep track of key dates: tests and exams, project submission deadlines, term breaks, etc.</div><div><br></div><div>Enter these dates into a physical or digital calendar.</div><div><br></div><div><div>If you choose to use a digital calendar, I recommend Google Calendar.</div><div><br></div><div>(b) Schedule a fixed time every week where you review your upcoming events over the next two months. Mark down when you’ll start preparing for that Math exam, working on that History project, or writing that English paper.</div><div><br></div><div>(d) Next, note your commitments for the coming week, e.g. extracurricular activities, family gatherings, extra classes. On your calendar, highlight the blocks of time you’ll have for schoolwork.</div><div><br></div><div>This planning process might sound time-consuming, but it’ll typically take just 15 minutes every week.</div><div><br></div><div>This is a wise investment of time as a student, because the rest of your week will become far more productive.</div><div><br></div><div>This way, you’ll be studying smart, not just hard!</div><div><br></div><div><h3 style=\"color: rgb(0, 0, 0); font-family: Lato, sans-serif; margin-right: 0px; margin-bottom: 16px; margin-left: 0px; padding: 0px; font-size: 24px;\">Rule #2: Be organized.</h3></div></div></div><div><div>Ever had trouble finding your notes or assignments when you needed them? You probably ended up wasting precious time looking for them, before you finally asked to borrow them from your friend.</div><div><br></div><div>Many students tell me that they keep all their notes and assignments in one big pile, and only sort them out before their exams!</div><div><br></div><div>Being organized – it’s easier said than done, I know.</div></div>', 11, 1, 'publish', 1625094412, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `blog_categories`
--

CREATE TABLE `blog_categories` (
  `id` int(10) UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `blog_categories`
--

INSERT INTO `blog_categories` (`id`, `title`, `slug`) VALUES
(33, 'Announcements', 'Vel-consequatur'),
(34, 'Articles', 'Facilis-ea'),
(36, 'Events', 'Fugit-dignissimos-possimus'),
(37, 'News', 'new');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(10) UNSIGNED NOT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `title` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `icon` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `order` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `parent_id`, `title`, `icon`, `order`) VALUES
(520, NULL, 'Design', '/store/1/default_images/categories_icons/feather.svg', NULL),
(522, NULL, 'Academics', '/store/1/default_images/categories_icons/briefcase.svg', NULL),
(523, NULL, 'Health & Fitness', '/store/1/default_images/categories_icons/heart.svg', NULL),
(524, NULL, 'Lifestyle', '/store/1/default_images/categories_icons/umbrella.svg', NULL),
(525, NULL, 'Marketing', '/store/1/default_images/categories_icons/pie-chart.svg', NULL),
(526, NULL, 'Business', '/store/1/default_images/categories_icons/anchor.svg', NULL),
(528, NULL, 'Development', '/store/1/default_images/categories_icons/code.svg', NULL),
(601, 522, 'Math', NULL, 1),
(602, 522, 'Science', NULL, 2),
(603, 522, 'Language', NULL, 3),
(604, 524, 'Lifestyle', NULL, 1),
(605, 524, 'Beauty & Makeup', NULL, 2),
(606, 528, 'Web Development', NULL, 1),
(607, 528, 'Mobile Development', NULL, 2),
(608, 528, 'Game Development', NULL, 3),
(609, 526, 'Management', NULL, 1),
(610, 526, 'Communications', NULL, 2),
(611, 526, 'Business Strategy', NULL, 3);

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `id` int(10) UNSIGNED NOT NULL,
  `ticket_id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `comment` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `file` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`id`, `ticket_id`, `user_id`, `comment`, `file`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 'xzcxzczxczxczc', NULL, '2022-01-23 06:41:58', '2022-01-23 06:41:58');

-- --------------------------------------------------------

--
-- Table structure for table `contact_us`
--

CREATE TABLE `contact_us` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `company` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subject` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `files` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `contact_us`
--

INSERT INTO `contact_us` (`id`, `name`, `email`, `company`, `subject`, `message`, `files`, `created_at`, `updated_at`) VALUES
(10, 'khadim', 'khadim@gmail.com', 'ZAPTA Technologies', 'ddyasd', 'Contact ASAP', NULL, '2022-02-22 08:37:12', '2022-02-22 08:37:12'),
(12, 'Waleed', 'Waleed@gmail.com', 'ZAPTA Technologies', 'ddyasd', 'Contact ASAP', '2022022213380949.jpg|2022022213380914.jpg', '2022-02-22 08:38:09', '2022-02-22 08:38:09'),
(13, 'Waleed', 'Waleed@gmail.com', 'ZAPTA Technologies', 'ddyasd', 'Contact ASAP', NULL, '2022-02-23 18:41:31', '2022-02-23 18:41:31'),
(14, 'Waleed', 'Waleed@gmail.com', 'ZAPTA Technologies', 'ddyasd', 'Contact ASAP', '2022022312434930.png|2022022312434991.json', '2022-02-23 18:43:49', '2022-02-23 18:43:49'),
(15, 'Ahmed Hassan', 'ahmedhassan.fci@gmail.com', 'dfdf', 'dfdf', 'fddfdfd', NULL, '2022-02-23 19:41:39', '2022-02-23 19:41:39'),
(16, 'Ahmed Hassan', 'ahmedhassan.fci@gmail.com', 'VIBER', 'Welcome message', 'Thank you so much Muhammed for your support', '', '2022-02-23 19:43:10', '2022-02-23 19:43:10'),
(17, 'dfdffd', 'ahmedhassan.fci@gmail.com', 'fddf', 'dfdf', 'fddddd', '2022022313452764.png', '2022-02-23 19:45:27', '2022-02-23 19:45:27'),
(18, 'Waleed', 'Waleed@gmail.com', 'ZAPTA Technologies', 'ddyasd', 'Contact ASAP', '2022030113593133.jpg|2022030113593182.jpg', '2022-03-01 08:59:31', '2022-03-01 08:59:31');

-- --------------------------------------------------------

--
-- Table structure for table `countries`
--

CREATE TABLE `countries` (
  `id` mediumint(8) UNSIGNED NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nameAR` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `iso3` char(3) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `iso2` char(2) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phonecode` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `capital` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `currency` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `currency_symbol` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tld` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `native` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `region` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subregion` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `timezones` text COLLATE utf8mb4_unicode_ci,
  `translations` text COLLATE utf8mb4_unicode_ci,
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL,
  `emoji` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `emojiU` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `flag` tinyint(1) NOT NULL DEFAULT '1',
  `wikiDataId` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Rapid API GeoDB Cities'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `countries`
--

INSERT INTO `countries` (`id`, `name`, `nameAR`, `iso3`, `iso2`, `phonecode`, `capital`, `currency`, `currency_symbol`, `tld`, `native`, `region`, `subregion`, `timezones`, `translations`, `latitude`, `longitude`, `emoji`, `emojiU`, `created_at`, `updated_at`, `flag`, `wikiDataId`) VALUES
(1, 'Afghanistan', 'أفغانستان', 'AFG', 'AF', '93', 'Kabul', 'AFN', '؋', '.af', 'افغانستان', 'Asia', 'Southern Asia', '[{\"zoneName\":\"Asia\\/Kabul\",\"gmtOffset\":16200,\"gmtOffsetName\":\"UTC+04:30\",\"abbreviation\":\"AFT\",\"tzName\":\"Afghanistan Time\"}]', '{\"br\":\"Afeganistão\",\"pt\":\"Afeganistão\",\"nl\":\"Afghanistan\",\"hr\":\"Afganistan\",\"fa\":\"افغانستان\",\"de\":\"Afghanistan\",\"es\":\"Afganistán\",\"fr\":\"Afghanistan\",\"ja\":\"アフガニスタン\",\"it\":\"Afghanistan\"}', 33.00000000, 65.00000000, '??', 'U+1F1E6 U+1F1EB', '2018-07-20 20:11:03', '2021-07-31 13:34:48', 1, 'Q889'),
(2, 'Aland Islands', 'جزر آلاند', 'ALA', 'AX', '+358-18', 'Mariehamn', 'EUR', '€', '.ax', 'Åland', 'Europe', 'Northern Europe', '[{\"zoneName\":\"Europe\\/Mariehamn\",\"gmtOffset\":7200,\"gmtOffsetName\":\"UTC+02:00\",\"abbreviation\":\"EET\",\"tzName\":\"Eastern European Time\"}]', '{\"br\":\"Ilhas de Aland\",\"pt\":\"Ilhas de Aland\",\"nl\":\"Ålandeilanden\",\"hr\":\"Ålandski otoci\",\"fa\":\"جزایر الند\",\"de\":\"Åland\",\"es\":\"Alandia\",\"fr\":\"Åland\",\"ja\":\"オーランド諸島\",\"it\":\"Isole Aland\"}', 60.11666700, 19.90000000, '??', 'U+1F1E6 U+1F1FD', '2018-07-20 20:11:03', '2021-07-31 13:34:57', 1, NULL),
(3, 'Albania', 'ألبانيا', 'ALB', 'AL', '355', 'Tirana', 'ALL', 'Lek', '.al', 'Shqipëria', 'Europe', 'Southern Europe', '[{\"zoneName\":\"Europe\\/Tirane\",\"gmtOffset\":3600,\"gmtOffsetName\":\"UTC+01:00\",\"abbreviation\":\"CET\",\"tzName\":\"Central European Time\"}]', '{\"br\":\"Albânia\",\"pt\":\"Albânia\",\"nl\":\"Albanië\",\"hr\":\"Albanija\",\"fa\":\"آلبانی\",\"de\":\"Albanien\",\"es\":\"Albania\",\"fr\":\"Albanie\",\"ja\":\"アルバニア\",\"it\":\"Albania\"}', 41.00000000, 20.00000000, '??', 'U+1F1E6 U+1F1F1', '2018-07-20 20:11:03', '2021-07-31 13:35:28', 1, 'Q222'),
(4, 'Algeria', 'الجزائر', 'DZA', 'DZ', '213', 'Algiers', 'DZD', 'دج', '.dz', 'الجزائر', 'Africa', 'Northern Africa', '[{\"zoneName\":\"Africa\\/Algiers\",\"gmtOffset\":3600,\"gmtOffsetName\":\"UTC+01:00\",\"abbreviation\":\"CET\",\"tzName\":\"Central European Time\"}]', '{\"br\":\"Argélia\",\"pt\":\"Argélia\",\"nl\":\"Algerije\",\"hr\":\"Alžir\",\"fa\":\"الجزایر\",\"de\":\"Algerien\",\"es\":\"Argelia\",\"fr\":\"Algérie\",\"ja\":\"アルジェリア\",\"it\":\"Algeria\"}', 28.00000000, 3.00000000, '??', 'U+1F1E9 U+1F1FF', '2018-07-20 20:11:03', '2021-07-31 13:35:43', 1, 'Q262'),
(5, 'American Samoa', 'ساموا الأمريكية', 'ASM', 'AS', '+1-684', 'Pago Pago', 'USD', '$', '.as', 'American Samoa', 'Oceania', 'Polynesia', '[{\"zoneName\":\"Pacific\\/Pago_Pago\",\"gmtOffset\":-39600,\"gmtOffsetName\":\"UTC-11:00\",\"abbreviation\":\"SST\",\"tzName\":\"Samoa Standard Time\"}]', '{\"br\":\"Samoa Americana\",\"pt\":\"Samoa Americana\",\"nl\":\"Amerikaans Samoa\",\"hr\":\"Američka Samoa\",\"fa\":\"ساموآی آمریکا\",\"de\":\"Amerikanisch-Samoa\",\"es\":\"Samoa Americana\",\"fr\":\"Samoa américaines\",\"ja\":\"アメリカ領サモア\",\"it\":\"Samoa Americane\"}', -14.33333333, -170.00000000, '??', 'U+1F1E6 U+1F1F8', '2018-07-20 20:11:03', '2021-07-31 13:36:11', 1, NULL),
(6, 'Andorra', 'أندورا', 'AND', 'AD', '376', 'Andorra la Vella', 'EUR', '€', '.ad', 'Andorra', 'Europe', 'Southern Europe', '[{\"zoneName\":\"Europe\\/Andorra\",\"gmtOffset\":3600,\"gmtOffsetName\":\"UTC+01:00\",\"abbreviation\":\"CET\",\"tzName\":\"Central European Time\"}]', '{\"br\":\"Andorra\",\"pt\":\"Andorra\",\"nl\":\"Andorra\",\"hr\":\"Andora\",\"fa\":\"آندورا\",\"de\":\"Andorra\",\"es\":\"Andorra\",\"fr\":\"Andorre\",\"ja\":\"アンドラ\",\"it\":\"Andorra\"}', 42.50000000, 1.50000000, '??', 'U+1F1E6 U+1F1E9', '2018-07-20 20:11:03', '2021-07-31 13:36:34', 1, 'Q228'),
(7, 'Angola', 'أنغولا', 'AGO', 'AO', '244', 'Luanda', 'AOA', 'Kz', '.ao', 'Angola', 'Africa', 'Middle Africa', '[{\"zoneName\":\"Africa\\/Luanda\",\"gmtOffset\":3600,\"gmtOffsetName\":\"UTC+01:00\",\"abbreviation\":\"WAT\",\"tzName\":\"West Africa Time\"}]', '{\"br\":\"Angola\",\"pt\":\"Angola\",\"nl\":\"Angola\",\"hr\":\"Angola\",\"fa\":\"آنگولا\",\"de\":\"Angola\",\"es\":\"Angola\",\"fr\":\"Angola\",\"ja\":\"アンゴラ\",\"it\":\"Angola\"}', -12.50000000, 18.50000000, '??', 'U+1F1E6 U+1F1F4', '2018-07-20 20:11:03', '2021-07-31 13:37:27', 1, 'Q916'),
(8, 'Anguilla', 'أنغيلا', 'AIA', 'AI', '+1-264', 'The Valley', 'XCD', '$', '.ai', 'Anguilla', 'Americas', 'Caribbean', '[{\"zoneName\":\"America\\/Anguilla\",\"gmtOffset\":-14400,\"gmtOffsetName\":\"UTC-04:00\",\"abbreviation\":\"AST\",\"tzName\":\"Atlantic Standard Time\"}]', '{\"br\":\"Anguila\",\"pt\":\"Anguila\",\"nl\":\"Anguilla\",\"hr\":\"Angvila\",\"fa\":\"آنگویلا\",\"de\":\"Anguilla\",\"es\":\"Anguilla\",\"fr\":\"Anguilla\",\"ja\":\"アンギラ\",\"it\":\"Anguilla\"}', 18.25000000, -63.16666666, '??', 'U+1F1E6 U+1F1EE', '2018-07-20 20:11:03', '2021-07-31 13:37:43', 1, NULL),
(9, 'Antarctica', 'أنتاركتيكا', 'ATA', 'AQ', '', '', '', '$', '.aq', 'Antarctica', 'Polar', '', '[{\"zoneName\":\"Antarctica\\/Casey\",\"gmtOffset\":39600,\"gmtOffsetName\":\"UTC+11:00\",\"abbreviation\":\"AWST\",\"tzName\":\"Australian Western Standard Time\"},{\"zoneName\":\"Antarctica\\/Davis\",\"gmtOffset\":25200,\"gmtOffsetName\":\"UTC+07:00\",\"abbreviation\":\"DAVT\",\"tzName\":\"Davis Time\"},{\"zoneName\":\"Antarctica\\/DumontDUrville\",\"gmtOffset\":36000,\"gmtOffsetName\":\"UTC+10:00\",\"abbreviation\":\"DDUT\",\"tzName\":\"Dumont d\'Urville Time\"},{\"zoneName\":\"Antarctica\\/Mawson\",\"gmtOffset\":18000,\"gmtOffsetName\":\"UTC+05:00\",\"abbreviation\":\"MAWT\",\"tzName\":\"Mawson Station Time\"},{\"zoneName\":\"Antarctica\\/McMurdo\",\"gmtOffset\":46800,\"gmtOffsetName\":\"UTC+13:00\",\"abbreviation\":\"NZDT\",\"tzName\":\"New Zealand Daylight Time\"},{\"zoneName\":\"Antarctica\\/Palmer\",\"gmtOffset\":-10800,\"gmtOffsetName\":\"UTC-03:00\",\"abbreviation\":\"CLST\",\"tzName\":\"Chile Summer Time\"},{\"zoneName\":\"Antarctica\\/Rothera\",\"gmtOffset\":-10800,\"gmtOffsetName\":\"UTC-03:00\",\"abbreviation\":\"ROTT\",\"tzName\":\"Rothera Research Station Time\"},{\"zoneName\":\"Antarctica\\/Syowa\",\"gmtOffset\":10800,\"gmtOffsetName\":\"UTC+03:00\",\"abbreviation\":\"SYOT\",\"tzName\":\"Showa Station Time\"},{\"zoneName\":\"Antarctica\\/Troll\",\"gmtOffset\":0,\"gmtOffsetName\":\"UTC\\u00b100\",\"abbreviation\":\"GMT\",\"tzName\":\"Greenwich Mean Time\"},{\"zoneName\":\"Antarctica\\/Vostok\",\"gmtOffset\":21600,\"gmtOffsetName\":\"UTC+06:00\",\"abbreviation\":\"VOST\",\"tzName\":\"Vostok Station Time\"}]', '{\"br\":\"Antártida\",\"pt\":\"Antárctida\",\"nl\":\"Antarctica\",\"hr\":\"Antarktika\",\"fa\":\"جنوبگان\",\"de\":\"Antarktika\",\"es\":\"Antártida\",\"fr\":\"Antarctique\",\"ja\":\"南極大陸\",\"it\":\"Antartide\"}', -74.65000000, 4.48000000, '??', 'U+1F1E6 U+1F1F6', '2018-07-20 20:11:03', '2021-07-31 13:37:54', 1, NULL),
(10, 'Antigua And Barbuda', 'أنتيغوا وبربودا', 'ATG', 'AG', '+1-268', 'St. John\'s', 'XCD', '$', '.ag', 'Antigua and Barbuda', 'Americas', 'Caribbean', '[{\"zoneName\":\"America\\/Antigua\",\"gmtOffset\":-14400,\"gmtOffsetName\":\"UTC-04:00\",\"abbreviation\":\"AST\",\"tzName\":\"Atlantic Standard Time\"}]', '{\"br\":\"Antígua e Barbuda\",\"pt\":\"Antígua e Barbuda\",\"nl\":\"Antigua en Barbuda\",\"hr\":\"Antigva i Barbuda\",\"fa\":\"آنتیگوا و باربودا\",\"de\":\"Antigua und Barbuda\",\"es\":\"Antigua y Barbuda\",\"fr\":\"Antigua-et-Barbuda\",\"ja\":\"アンティグア・バーブーダ\",\"it\":\"Antigua e Barbuda\"}', 17.05000000, -61.80000000, '??', 'U+1F1E6 U+1F1EC', '2018-07-20 20:11:03', '2021-07-31 13:38:13', 1, 'Q781'),
(11, 'Argentina', 'الأرجنتين', 'ARG', 'AR', '54', 'Buenos Aires', 'ARS', '$', '.ar', 'Argentina', 'Americas', 'South America', '[{\"zoneName\":\"America\\/Argentina\\/Buenos_Aires\",\"gmtOffset\":-10800,\"gmtOffsetName\":\"UTC-03:00\",\"abbreviation\":\"ART\",\"tzName\":\"Argentina Time\"},{\"zoneName\":\"America\\/Argentina\\/Catamarca\",\"gmtOffset\":-10800,\"gmtOffsetName\":\"UTC-03:00\",\"abbreviation\":\"ART\",\"tzName\":\"Argentina Time\"},{\"zoneName\":\"America\\/Argentina\\/Cordoba\",\"gmtOffset\":-10800,\"gmtOffsetName\":\"UTC-03:00\",\"abbreviation\":\"ART\",\"tzName\":\"Argentina Time\"},{\"zoneName\":\"America\\/Argentina\\/Jujuy\",\"gmtOffset\":-10800,\"gmtOffsetName\":\"UTC-03:00\",\"abbreviation\":\"ART\",\"tzName\":\"Argentina Time\"},{\"zoneName\":\"America\\/Argentina\\/La_Rioja\",\"gmtOffset\":-10800,\"gmtOffsetName\":\"UTC-03:00\",\"abbreviation\":\"ART\",\"tzName\":\"Argentina Time\"},{\"zoneName\":\"America\\/Argentina\\/Mendoza\",\"gmtOffset\":-10800,\"gmtOffsetName\":\"UTC-03:00\",\"abbreviation\":\"ART\",\"tzName\":\"Argentina Time\"},{\"zoneName\":\"America\\/Argentina\\/Rio_Gallegos\",\"gmtOffset\":-10800,\"gmtOffsetName\":\"UTC-03:00\",\"abbreviation\":\"ART\",\"tzName\":\"Argentina Time\"},{\"zoneName\":\"America\\/Argentina\\/Salta\",\"gmtOffset\":-10800,\"gmtOffsetName\":\"UTC-03:00\",\"abbreviation\":\"ART\",\"tzName\":\"Argentina Time\"},{\"zoneName\":\"America\\/Argentina\\/San_Juan\",\"gmtOffset\":-10800,\"gmtOffsetName\":\"UTC-03:00\",\"abbreviation\":\"ART\",\"tzName\":\"Argentina Time\"},{\"zoneName\":\"America\\/Argentina\\/San_Luis\",\"gmtOffset\":-10800,\"gmtOffsetName\":\"UTC-03:00\",\"abbreviation\":\"ART\",\"tzName\":\"Argentina Time\"},{\"zoneName\":\"America\\/Argentina\\/Tucuman\",\"gmtOffset\":-10800,\"gmtOffsetName\":\"UTC-03:00\",\"abbreviation\":\"ART\",\"tzName\":\"Argentina Time\"},{\"zoneName\":\"America\\/Argentina\\/Ushuaia\",\"gmtOffset\":-10800,\"gmtOffsetName\":\"UTC-03:00\",\"abbreviation\":\"ART\",\"tzName\":\"Argentina Time\"}]', '{\"br\":\"Argentina\",\"pt\":\"Argentina\",\"nl\":\"Argentinië\",\"hr\":\"Argentina\",\"fa\":\"آرژانتین\",\"de\":\"Argentinien\",\"es\":\"Argentina\",\"fr\":\"Argentine\",\"ja\":\"アルゼンチン\",\"it\":\"Argentina\"}', -34.00000000, -64.00000000, '??', 'U+1F1E6 U+1F1F7', '2018-07-20 20:11:03', '2021-07-31 13:38:29', 1, 'Q414'),
(12, 'Armenia', 'أرمينيا', 'ARM', 'AM', '374', 'Yerevan', 'AMD', '֏', '.am', 'Հայաստան', 'Asia', 'Western Asia', '[{\"zoneName\":\"Asia\\/Yerevan\",\"gmtOffset\":14400,\"gmtOffsetName\":\"UTC+04:00\",\"abbreviation\":\"AMT\",\"tzName\":\"Armenia Time\"}]', '{\"br\":\"Armênia\",\"pt\":\"Arménia\",\"nl\":\"Armenië\",\"hr\":\"Armenija\",\"fa\":\"ارمنستان\",\"de\":\"Armenien\",\"es\":\"Armenia\",\"fr\":\"Arménie\",\"ja\":\"アルメニア\",\"it\":\"Armenia\"}', 40.00000000, 45.00000000, '??', 'U+1F1E6 U+1F1F2', '2018-07-20 20:11:03', '2021-07-31 13:38:44', 1, 'Q399'),
(13, 'Aruba', 'أروبا', 'ABW', 'AW', '297', 'Oranjestad', 'AWG', 'ƒ', '.aw', 'Aruba', 'Americas', 'Caribbean', '[{\"zoneName\":\"America\\/Aruba\",\"gmtOffset\":-14400,\"gmtOffsetName\":\"UTC-04:00\",\"abbreviation\":\"AST\",\"tzName\":\"Atlantic Standard Time\"}]', '{\"br\":\"Aruba\",\"pt\":\"Aruba\",\"nl\":\"Aruba\",\"hr\":\"Aruba\",\"fa\":\"آروبا\",\"de\":\"Aruba\",\"es\":\"Aruba\",\"fr\":\"Aruba\",\"ja\":\"アルバ\",\"it\":\"Aruba\"}', 12.50000000, -69.96666666, '??', 'U+1F1E6 U+1F1FC', '2018-07-20 20:11:03', '2021-07-31 13:38:55', 1, NULL),
(14, 'Australia', 'أستراليا', 'AUS', 'AU', '61', 'Canberra', 'AUD', '$', '.au', 'Australia', 'Oceania', 'Australia and New Zealand', '[{\"zoneName\":\"Antarctica\\/Macquarie\",\"gmtOffset\":39600,\"gmtOffsetName\":\"UTC+11:00\",\"abbreviation\":\"MIST\",\"tzName\":\"Macquarie Island Station Time\"},{\"zoneName\":\"Australia\\/Adelaide\",\"gmtOffset\":37800,\"gmtOffsetName\":\"UTC+10:30\",\"abbreviation\":\"ACDT\",\"tzName\":\"Australian Central Daylight Saving Time\"},{\"zoneName\":\"Australia\\/Brisbane\",\"gmtOffset\":36000,\"gmtOffsetName\":\"UTC+10:00\",\"abbreviation\":\"AEST\",\"tzName\":\"Australian Eastern Standard Time\"},{\"zoneName\":\"Australia\\/Broken_Hill\",\"gmtOffset\":37800,\"gmtOffsetName\":\"UTC+10:30\",\"abbreviation\":\"ACDT\",\"tzName\":\"Australian Central Daylight Saving Time\"},{\"zoneName\":\"Australia\\/Currie\",\"gmtOffset\":39600,\"gmtOffsetName\":\"UTC+11:00\",\"abbreviation\":\"AEDT\",\"tzName\":\"Australian Eastern Daylight Saving Time\"},{\"zoneName\":\"Australia\\/Darwin\",\"gmtOffset\":34200,\"gmtOffsetName\":\"UTC+09:30\",\"abbreviation\":\"ACST\",\"tzName\":\"Australian Central Standard Time\"},{\"zoneName\":\"Australia\\/Eucla\",\"gmtOffset\":31500,\"gmtOffsetName\":\"UTC+08:45\",\"abbreviation\":\"ACWST\",\"tzName\":\"Australian Central Western Standard Time (Unofficial)\"},{\"zoneName\":\"Australia\\/Hobart\",\"gmtOffset\":39600,\"gmtOffsetName\":\"UTC+11:00\",\"abbreviation\":\"AEDT\",\"tzName\":\"Australian Eastern Daylight Saving Time\"},{\"zoneName\":\"Australia\\/Lindeman\",\"gmtOffset\":36000,\"gmtOffsetName\":\"UTC+10:00\",\"abbreviation\":\"AEST\",\"tzName\":\"Australian Eastern Standard Time\"},{\"zoneName\":\"Australia\\/Lord_Howe\",\"gmtOffset\":39600,\"gmtOffsetName\":\"UTC+11:00\",\"abbreviation\":\"LHST\",\"tzName\":\"Lord Howe Summer Time\"},{\"zoneName\":\"Australia\\/Melbourne\",\"gmtOffset\":39600,\"gmtOffsetName\":\"UTC+11:00\",\"abbreviation\":\"AEDT\",\"tzName\":\"Australian Eastern Daylight Saving Time\"},{\"zoneName\":\"Australia\\/Perth\",\"gmtOffset\":28800,\"gmtOffsetName\":\"UTC+08:00\",\"abbreviation\":\"AWST\",\"tzName\":\"Australian Western Standard Time\"},{\"zoneName\":\"Australia\\/Sydney\",\"gmtOffset\":39600,\"gmtOffsetName\":\"UTC+11:00\",\"abbreviation\":\"AEDT\",\"tzName\":\"Australian Eastern Daylight Saving Time\"}]', '{\"br\":\"Austrália\",\"pt\":\"Austrália\",\"nl\":\"Australië\",\"hr\":\"Australija\",\"fa\":\"استرالیا\",\"de\":\"Australien\",\"es\":\"Australia\",\"fr\":\"Australie\",\"ja\":\"オーストラリア\",\"it\":\"Australia\"}', -27.00000000, 133.00000000, '??', 'U+1F1E6 U+1F1FA', '2018-07-20 20:11:03', '2021-07-31 13:39:10', 1, 'Q408'),
(15, 'Austria', 'النمسا', 'AUT', 'AT', '43', 'Vienna', 'EUR', '€', '.at', 'Österreich', 'Europe', 'Western Europe', '[{\"zoneName\":\"Europe\\/Vienna\",\"gmtOffset\":3600,\"gmtOffsetName\":\"UTC+01:00\",\"abbreviation\":\"CET\",\"tzName\":\"Central European Time\"}]', '{\"br\":\"áustria\",\"pt\":\"áustria\",\"nl\":\"Oostenrijk\",\"hr\":\"Austrija\",\"fa\":\"اتریش\",\"de\":\"Österreich\",\"es\":\"Austria\",\"fr\":\"Autriche\",\"ja\":\"オーストリア\",\"it\":\"Austria\"}', 47.33333333, 13.33333333, '??', 'U+1F1E6 U+1F1F9', '2018-07-20 20:11:03', '2021-07-31 13:39:31', 1, 'Q40'),
(16, 'Azerbaijan', 'أذربيجان', 'AZE', 'AZ', '994', 'Baku', 'AZN', 'm', '.az', 'Azərbaycan', 'Asia', 'Western Asia', '[{\"zoneName\":\"Asia\\/Baku\",\"gmtOffset\":14400,\"gmtOffsetName\":\"UTC+04:00\",\"abbreviation\":\"AZT\",\"tzName\":\"Azerbaijan Time\"}]', '{\"br\":\"Azerbaijão\",\"pt\":\"Azerbaijão\",\"nl\":\"Azerbeidzjan\",\"hr\":\"Azerbajdžan\",\"fa\":\"آذربایجان\",\"de\":\"Aserbaidschan\",\"es\":\"Azerbaiyán\",\"fr\":\"Azerbaïdjan\",\"ja\":\"アゼルバイジャン\",\"it\":\"Azerbaijan\"}', 40.50000000, 47.50000000, '??', 'U+1F1E6 U+1F1FF', '2018-07-20 20:11:03', '2021-07-31 13:39:57', 1, 'Q227'),
(17, 'Bahamas The', 'جزر البهاما', 'BHS', 'BS', '+1-242', 'Nassau', 'BSD', 'B$', '.bs', 'Bahamas', 'Americas', 'Caribbean', '[{\"zoneName\":\"America\\/Nassau\",\"gmtOffset\":-18000,\"gmtOffsetName\":\"UTC-05:00\",\"abbreviation\":\"EST\",\"tzName\":\"Eastern Standard Time (North America)\"}]', '{\"br\":\"Bahamas\",\"pt\":\"Baamas\",\"nl\":\"Bahama’s\",\"hr\":\"Bahami\",\"fa\":\"باهاما\",\"de\":\"Bahamas\",\"es\":\"Bahamas\",\"fr\":\"Bahamas\",\"ja\":\"バハマ\",\"it\":\"Bahamas\"}', 24.25000000, -76.00000000, '??', 'U+1F1E7 U+1F1F8', '2018-07-20 20:11:03', '2021-07-31 13:40:03', 1, 'Q778'),
(18, 'Bahrain', 'البحرين', 'BHR', 'BH', '973', 'Manama', 'BHD', '.د.ب', '.bh', '‏البحرين', 'Asia', 'Western Asia', '[{\"zoneName\":\"Asia\\/Bahrain\",\"gmtOffset\":10800,\"gmtOffsetName\":\"UTC+03:00\",\"abbreviation\":\"AST\",\"tzName\":\"Arabia Standard Time\"}]', '{\"br\":\"Bahrein\",\"pt\":\"Barém\",\"nl\":\"Bahrein\",\"hr\":\"Bahrein\",\"fa\":\"بحرین\",\"de\":\"Bahrain\",\"es\":\"Bahrein\",\"fr\":\"Bahreïn\",\"ja\":\"バーレーン\",\"it\":\"Bahrein\"}', 26.00000000, 50.55000000, '??', 'U+1F1E7 U+1F1ED', '2018-07-20 20:11:03', '2021-07-31 13:40:17', 1, 'Q398'),
(19, 'Bangladesh', 'بنغلاديش', 'BGD', 'BD', '880', 'Dhaka', 'BDT', '৳', '.bd', 'Bangladesh', 'Asia', 'Southern Asia', '[{\"zoneName\":\"Asia\\/Dhaka\",\"gmtOffset\":21600,\"gmtOffsetName\":\"UTC+06:00\",\"abbreviation\":\"BDT\",\"tzName\":\"Bangladesh Standard Time\"}]', '{\"br\":\"Bangladesh\",\"pt\":\"Bangladeche\",\"nl\":\"Bangladesh\",\"hr\":\"Bangladeš\",\"fa\":\"بنگلادش\",\"de\":\"Bangladesch\",\"es\":\"Bangladesh\",\"fr\":\"Bangladesh\",\"ja\":\"バングラデシュ\",\"it\":\"Bangladesh\"}', 24.00000000, 90.00000000, '??', 'U+1F1E7 U+1F1E9', '2018-07-20 20:11:03', '2021-07-31 13:40:32', 1, 'Q902'),
(20, 'Barbados', 'بربادوس', 'BRB', 'BB', '+1-246', 'Bridgetown', 'BBD', 'Bds$', '.bb', 'Barbados', 'Americas', 'Caribbean', '[{\"zoneName\":\"America\\/Barbados\",\"gmtOffset\":-14400,\"gmtOffsetName\":\"UTC-04:00\",\"abbreviation\":\"AST\",\"tzName\":\"Atlantic Standard Time\"}]', '{\"br\":\"Barbados\",\"pt\":\"Barbados\",\"nl\":\"Barbados\",\"hr\":\"Barbados\",\"fa\":\"باربادوس\",\"de\":\"Barbados\",\"es\":\"Barbados\",\"fr\":\"Barbade\",\"ja\":\"バルバドス\",\"it\":\"Barbados\"}', 13.16666666, -59.53333333, '??', 'U+1F1E7 U+1F1E7', '2018-07-20 20:11:03', '2021-07-31 13:40:49', 1, 'Q244'),
(21, 'Belarus', 'بيلاروسيا', 'BLR', 'BY', '375', 'Minsk', 'BYN', 'Br', '.by', 'Белару́сь', 'Europe', 'Eastern Europe', '[{\"zoneName\":\"Europe\\/Minsk\",\"gmtOffset\":10800,\"gmtOffsetName\":\"UTC+03:00\",\"abbreviation\":\"MSK\",\"tzName\":\"Moscow Time\"}]', '{\"br\":\"Bielorrússia\",\"pt\":\"Bielorrússia\",\"nl\":\"Wit-Rusland\",\"hr\":\"Bjelorusija\",\"fa\":\"بلاروس\",\"de\":\"Weißrussland\",\"es\":\"Bielorrusia\",\"fr\":\"Biélorussie\",\"ja\":\"ベラルーシ\",\"it\":\"Bielorussia\"}', 53.00000000, 28.00000000, '??', 'U+1F1E7 U+1F1FE', '2018-07-20 20:11:03', '2021-07-31 13:41:04', 1, 'Q184'),
(22, 'Belgium', 'بلجيكا', 'BEL', 'BE', '32', 'Brussels', 'EUR', '€', '.be', 'België', 'Europe', 'Western Europe', '[{\"zoneName\":\"Europe\\/Brussels\",\"gmtOffset\":3600,\"gmtOffsetName\":\"UTC+01:00\",\"abbreviation\":\"CET\",\"tzName\":\"Central European Time\"}]', '{\"br\":\"Bélgica\",\"pt\":\"Bélgica\",\"nl\":\"België\",\"hr\":\"Belgija\",\"fa\":\"بلژیک\",\"de\":\"Belgien\",\"es\":\"Bélgica\",\"fr\":\"Belgique\",\"ja\":\"ベルギー\",\"it\":\"Belgio\"}', 50.83333333, 4.00000000, '??', 'U+1F1E7 U+1F1EA', '2018-07-20 20:11:03', '2021-07-31 13:41:25', 1, 'Q31'),
(23, 'Belize', 'بليز', 'BLZ', 'BZ', '501', 'Belmopan', 'BZD', '$', '.bz', 'Belize', 'Americas', 'Central America', '[{\"zoneName\":\"America\\/Belize\",\"gmtOffset\":-21600,\"gmtOffsetName\":\"UTC-06:00\",\"abbreviation\":\"CST\",\"tzName\":\"Central Standard Time (North America)\"}]', '{\"br\":\"Belize\",\"pt\":\"Belize\",\"nl\":\"Belize\",\"hr\":\"Belize\",\"fa\":\"بلیز\",\"de\":\"Belize\",\"es\":\"Belice\",\"fr\":\"Belize\",\"ja\":\"ベリーズ\",\"it\":\"Belize\"}', 17.25000000, -88.75000000, '??', 'U+1F1E7 U+1F1FF', '2018-07-20 20:11:03', '2021-07-31 13:41:42', 1, 'Q242'),
(24, 'Benin', 'بنين', 'BEN', 'BJ', '229', 'Porto-Novo', 'XOF', 'CFA', '.bj', 'Bénin', 'Africa', 'Western Africa', '[{\"zoneName\":\"Africa\\/Porto-Novo\",\"gmtOffset\":3600,\"gmtOffsetName\":\"UTC+01:00\",\"abbreviation\":\"WAT\",\"tzName\":\"West Africa Time\"}]', '{\"br\":\"Benin\",\"pt\":\"Benim\",\"nl\":\"Benin\",\"hr\":\"Benin\",\"fa\":\"بنین\",\"de\":\"Benin\",\"es\":\"Benín\",\"fr\":\"Bénin\",\"ja\":\"ベナン\",\"it\":\"Benin\"}', 9.50000000, 2.25000000, '??', 'U+1F1E7 U+1F1EF', '2018-07-20 20:11:03', '2021-07-31 13:42:04', 1, 'Q962'),
(25, 'Bermuda', 'برمودا', 'BMU', 'BM', '+1-441', 'Hamilton', 'BMD', '$', '.bm', 'Bermuda', 'Americas', 'Northern America', '[{\"zoneName\":\"Atlantic\\/Bermuda\",\"gmtOffset\":-14400,\"gmtOffsetName\":\"UTC-04:00\",\"abbreviation\":\"AST\",\"tzName\":\"Atlantic Standard Time\"}]', '{\"br\":\"Bermudas\",\"pt\":\"Bermudas\",\"nl\":\"Bermuda\",\"hr\":\"Bermudi\",\"fa\":\"برمودا\",\"de\":\"Bermuda\",\"es\":\"Bermudas\",\"fr\":\"Bermudes\",\"ja\":\"バミューダ\",\"it\":\"Bermuda\"}', 32.33333333, -64.75000000, '??', 'U+1F1E7 U+1F1F2', '2018-07-20 20:11:03', '2021-07-31 13:42:18', 1, NULL),
(26, 'Bhutan', 'بوتان', 'BTN', 'BT', '975', 'Thimphu', 'BTN', 'Nu.', '.bt', 'ʼbrug-yul', 'Asia', 'Southern Asia', '[{\"zoneName\":\"Asia\\/Thimphu\",\"gmtOffset\":21600,\"gmtOffsetName\":\"UTC+06:00\",\"abbreviation\":\"BTT\",\"tzName\":\"Bhutan Time\"}]', '{\"br\":\"Butão\",\"pt\":\"Butão\",\"nl\":\"Bhutan\",\"hr\":\"Butan\",\"fa\":\"بوتان\",\"de\":\"Bhutan\",\"es\":\"Bután\",\"fr\":\"Bhoutan\",\"ja\":\"ブータン\",\"it\":\"Bhutan\"}', 27.50000000, 90.50000000, '??', 'U+1F1E7 U+1F1F9', '2018-07-20 20:11:03', '2021-07-31 13:42:43', 1, 'Q917'),
(27, 'Bolivia', 'بوليفيا', 'BOL', 'BO', '591', 'Sucre', 'BOB', 'Bs.', '.bo', 'Bolivia', 'Americas', 'South America', '[{\"zoneName\":\"America\\/La_Paz\",\"gmtOffset\":-14400,\"gmtOffsetName\":\"UTC-04:00\",\"abbreviation\":\"BOT\",\"tzName\":\"Bolivia Time\"}]', '{\"br\":\"Bolívia\",\"pt\":\"Bolívia\",\"nl\":\"Bolivia\",\"hr\":\"Bolivija\",\"fa\":\"بولیوی\",\"de\":\"Bolivien\",\"es\":\"Bolivia\",\"fr\":\"Bolivie\",\"ja\":\"ボリビア多民族国\",\"it\":\"Bolivia\"}', -17.00000000, -65.00000000, '??', 'U+1F1E7 U+1F1F4', '2018-07-20 20:11:03', '2021-07-31 13:43:01', 1, 'Q750'),
(28, 'Bosnia and Herzegovina', 'البوسنة والهرسك', 'BIH', 'BA', '387', 'Sarajevo', 'BAM', 'KM', '.ba', 'Bosna i Hercegovina', 'Europe', 'Southern Europe', '[{\"zoneName\":\"Europe\\/Sarajevo\",\"gmtOffset\":3600,\"gmtOffsetName\":\"UTC+01:00\",\"abbreviation\":\"CET\",\"tzName\":\"Central European Time\"}]', '{\"br\":\"Bósnia e Herzegovina\",\"pt\":\"Bósnia e Herzegovina\",\"nl\":\"Bosnië en Herzegovina\",\"hr\":\"Bosna i Hercegovina\",\"fa\":\"بوسنی و هرزگوین\",\"de\":\"Bosnien und Herzegowina\",\"es\":\"Bosnia y Herzegovina\",\"fr\":\"Bosnie-Herzégovine\",\"ja\":\"ボスニア・ヘルツェゴビナ\",\"it\":\"Bosnia ed Erzegovina\"}', 44.00000000, 18.00000000, '??', 'U+1F1E7 U+1F1E6', '2018-07-20 20:11:03', '2021-07-31 13:43:15', 1, 'Q225'),
(29, 'Botswana', 'بوتسوانا', 'BWA', 'BW', '267', 'Gaborone', 'BWP', 'P', '.bw', 'Botswana', 'Africa', 'Southern Africa', '[{\"zoneName\":\"Africa\\/Gaborone\",\"gmtOffset\":7200,\"gmtOffsetName\":\"UTC+02:00\",\"abbreviation\":\"CAT\",\"tzName\":\"Central Africa Time\"}]', '{\"br\":\"Botsuana\",\"pt\":\"Botsuana\",\"nl\":\"Botswana\",\"hr\":\"Bocvana\",\"fa\":\"بوتسوانا\",\"de\":\"Botswana\",\"es\":\"Botswana\",\"fr\":\"Botswana\",\"ja\":\"ボツワナ\",\"it\":\"Botswana\"}', -22.00000000, 24.00000000, '??', 'U+1F1E7 U+1F1FC', '2018-07-20 20:11:03', '2021-07-31 13:43:28', 1, 'Q963'),
(30, 'Bouvet Island', 'جزيرة بوفيت', 'BVT', 'BV', '0055', '', 'NOK', 'kr', '.bv', 'Bouvetøya', '', '', '[{\"zoneName\":\"Europe\\/Oslo\",\"gmtOffset\":3600,\"gmtOffsetName\":\"UTC+01:00\",\"abbreviation\":\"CET\",\"tzName\":\"Central European Time\"}]', '{\"br\":\"Ilha Bouvet\",\"pt\":\"Ilha Bouvet\",\"nl\":\"Bouveteiland\",\"hr\":\"Otok Bouvet\",\"fa\":\"جزیره بووه\",\"de\":\"Bouvetinsel\",\"es\":\"Isla Bouvet\",\"fr\":\"Île Bouvet\",\"ja\":\"ブーベ島\",\"it\":\"Isola Bouvet\"}', -54.43333333, 3.40000000, '??', 'U+1F1E7 U+1F1FB', '2018-07-20 20:11:03', '2021-07-31 13:43:42', 1, NULL),
(31, 'Brazil', 'البرازيل', 'BRA', 'BR', '55', 'Brasilia', 'BRL', 'R$', '.br', 'Brasil', 'Americas', 'South America', '[{\"zoneName\":\"America\\/Araguaina\",\"gmtOffset\":-10800,\"gmtOffsetName\":\"UTC-03:00\",\"abbreviation\":\"BRT\",\"tzName\":\"Bras\\u00edlia Time\"},{\"zoneName\":\"America\\/Bahia\",\"gmtOffset\":-10800,\"gmtOffsetName\":\"UTC-03:00\",\"abbreviation\":\"BRT\",\"tzName\":\"Bras\\u00edlia Time\"},{\"zoneName\":\"America\\/Belem\",\"gmtOffset\":-10800,\"gmtOffsetName\":\"UTC-03:00\",\"abbreviation\":\"BRT\",\"tzName\":\"Bras\\u00edlia Time\"},{\"zoneName\":\"America\\/Boa_Vista\",\"gmtOffset\":-14400,\"gmtOffsetName\":\"UTC-04:00\",\"abbreviation\":\"AMT\",\"tzName\":\"Amazon Time (Brazil)[3\"},{\"zoneName\":\"America\\/Campo_Grande\",\"gmtOffset\":-14400,\"gmtOffsetName\":\"UTC-04:00\",\"abbreviation\":\"AMT\",\"tzName\":\"Amazon Time (Brazil)[3\"},{\"zoneName\":\"America\\/Cuiaba\",\"gmtOffset\":-14400,\"gmtOffsetName\":\"UTC-04:00\",\"abbreviation\":\"BRT\",\"tzName\":\"Brasilia Time\"},{\"zoneName\":\"America\\/Eirunepe\",\"gmtOffset\":-18000,\"gmtOffsetName\":\"UTC-05:00\",\"abbreviation\":\"ACT\",\"tzName\":\"Acre Time\"},{\"zoneName\":\"America\\/Fortaleza\",\"gmtOffset\":-10800,\"gmtOffsetName\":\"UTC-03:00\",\"abbreviation\":\"BRT\",\"tzName\":\"Bras\\u00edlia Time\"},{\"zoneName\":\"America\\/Maceio\",\"gmtOffset\":-10800,\"gmtOffsetName\":\"UTC-03:00\",\"abbreviation\":\"BRT\",\"tzName\":\"Bras\\u00edlia Time\"},{\"zoneName\":\"America\\/Manaus\",\"gmtOffset\":-14400,\"gmtOffsetName\":\"UTC-04:00\",\"abbreviation\":\"AMT\",\"tzName\":\"Amazon Time (Brazil)\"},{\"zoneName\":\"America\\/Noronha\",\"gmtOffset\":-7200,\"gmtOffsetName\":\"UTC-02:00\",\"abbreviation\":\"FNT\",\"tzName\":\"Fernando de Noronha Time\"},{\"zoneName\":\"America\\/Porto_Velho\",\"gmtOffset\":-14400,\"gmtOffsetName\":\"UTC-04:00\",\"abbreviation\":\"AMT\",\"tzName\":\"Amazon Time (Brazil)[3\"},{\"zoneName\":\"America\\/Recife\",\"gmtOffset\":-10800,\"gmtOffsetName\":\"UTC-03:00\",\"abbreviation\":\"BRT\",\"tzName\":\"Bras\\u00edlia Time\"},{\"zoneName\":\"America\\/Rio_Branco\",\"gmtOffset\":-18000,\"gmtOffsetName\":\"UTC-05:00\",\"abbreviation\":\"ACT\",\"tzName\":\"Acre Time\"},{\"zoneName\":\"America\\/Santarem\",\"gmtOffset\":-10800,\"gmtOffsetName\":\"UTC-03:00\",\"abbreviation\":\"BRT\",\"tzName\":\"Bras\\u00edlia Time\"},{\"zoneName\":\"America\\/Sao_Paulo\",\"gmtOffset\":-10800,\"gmtOffsetName\":\"UTC-03:00\",\"abbreviation\":\"BRT\",\"tzName\":\"Bras\\u00edlia Time\"}]', '{\"br\":\"Brasil\",\"pt\":\"Brasil\",\"nl\":\"Brazilië\",\"hr\":\"Brazil\",\"fa\":\"برزیل\",\"de\":\"Brasilien\",\"es\":\"Brasil\",\"fr\":\"Brésil\",\"ja\":\"ブラジル\",\"it\":\"Brasile\"}', -10.00000000, -55.00000000, '??', 'U+1F1E7 U+1F1F7', '2018-07-20 20:11:03', '2021-07-31 13:43:57', 1, 'Q155'),
(32, 'British Indian Ocean Territory', 'إقليم المحيط البريطاني الهندي', 'IOT', 'IO', '246', 'Diego Garcia', 'USD', '$', '.io', 'British Indian Ocean Territory', 'Africa', 'Eastern Africa', '[{\"zoneName\":\"Indian\\/Chagos\",\"gmtOffset\":21600,\"gmtOffsetName\":\"UTC+06:00\",\"abbreviation\":\"IOT\",\"tzName\":\"Indian Ocean Time\"}]', '{\"br\":\"Território Britânico do Oceano íÍdico\",\"pt\":\"Território Britânico do Oceano Índico\",\"nl\":\"Britse Gebieden in de Indische Oceaan\",\"hr\":\"Britanski Indijskooceanski teritorij\",\"fa\":\"قلمرو بریتانیا در اقیانوس هند\",\"de\":\"Britisches Territorium im Indischen Ozean\",\"es\":\"Territorio Británico del Océano Índico\",\"fr\":\"Territoire britannique de l\'océan Indien\",\"ja\":\"イギリス領インド洋地域\",\"it\":\"Territorio britannico dell\'oceano indiano\"}', -6.00000000, 71.50000000, '??', 'U+1F1EE U+1F1F4', '2018-07-20 20:11:03', '2021-07-31 13:44:14', 1, NULL),
(33, 'Brunei', 'بروناي', 'BRN', 'BN', '673', 'Bandar Seri Begawan', 'BND', 'B$', '.bn', 'Negara Brunei Darussalam', 'Asia', 'South-Eastern Asia', '[{\"zoneName\":\"Asia\\/Brunei\",\"gmtOffset\":28800,\"gmtOffsetName\":\"UTC+08:00\",\"abbreviation\":\"BNT\",\"tzName\":\"Brunei Darussalam Time\"}]', '{\"br\":\"Brunei\",\"pt\":\"Brunei\",\"nl\":\"Brunei\",\"hr\":\"Brunej\",\"fa\":\"برونئی\",\"de\":\"Brunei\",\"es\":\"Brunei\",\"fr\":\"Brunei\",\"ja\":\"ブルネイ・ダルサラーム\",\"it\":\"Brunei\"}', 4.50000000, 114.66666666, '??', 'U+1F1E7 U+1F1F3', '2018-07-20 20:11:03', '2021-07-31 13:44:28', 1, 'Q921'),
(34, 'Bulgaria', 'بلغاريا', 'BGR', 'BG', '359', 'Sofia', 'BGN', 'Лв.', '.bg', 'България', 'Europe', 'Eastern Europe', '[{\"zoneName\":\"Europe\\/Sofia\",\"gmtOffset\":7200,\"gmtOffsetName\":\"UTC+02:00\",\"abbreviation\":\"EET\",\"tzName\":\"Eastern European Time\"}]', '{\"br\":\"Bulgária\",\"pt\":\"Bulgária\",\"nl\":\"Bulgarije\",\"hr\":\"Bugarska\",\"fa\":\"بلغارستان\",\"de\":\"Bulgarien\",\"es\":\"Bulgaria\",\"fr\":\"Bulgarie\",\"ja\":\"ブルガリア\",\"it\":\"Bulgaria\"}', 43.00000000, 25.00000000, '??', 'U+1F1E7 U+1F1EC', '2018-07-20 20:11:03', '2021-07-31 13:44:41', 1, 'Q219'),
(35, 'Burkina Faso', 'بوركينا فاسو', 'BFA', 'BF', '226', 'Ouagadougou', 'XOF', 'CFA', '.bf', 'Burkina Faso', 'Africa', 'Western Africa', '[{\"zoneName\":\"Africa\\/Ouagadougou\",\"gmtOffset\":0,\"gmtOffsetName\":\"UTC\\u00b100\",\"abbreviation\":\"GMT\",\"tzName\":\"Greenwich Mean Time\"}]', '{\"br\":\"Burkina Faso\",\"pt\":\"Burquina Faso\",\"nl\":\"Burkina Faso\",\"hr\":\"Burkina Faso\",\"fa\":\"بورکینافاسو\",\"de\":\"Burkina Faso\",\"es\":\"Burkina Faso\",\"fr\":\"Burkina Faso\",\"ja\":\"ブルキナファソ\",\"it\":\"Burkina Faso\"}', 13.00000000, -2.00000000, '??', 'U+1F1E7 U+1F1EB', '2018-07-20 20:11:03', '2021-07-31 13:44:59', 1, 'Q965'),
(36, 'Burundi', 'بوروندي', 'BDI', 'BI', '257', 'Bujumbura', 'BIF', 'FBu', '.bi', 'Burundi', 'Africa', 'Eastern Africa', '[{\"zoneName\":\"Africa\\/Bujumbura\",\"gmtOffset\":7200,\"gmtOffsetName\":\"UTC+02:00\",\"abbreviation\":\"CAT\",\"tzName\":\"Central Africa Time\"}]', '{\"br\":\"Burundi\",\"pt\":\"Burúndi\",\"nl\":\"Burundi\",\"hr\":\"Burundi\",\"fa\":\"بوروندی\",\"de\":\"Burundi\",\"es\":\"Burundi\",\"fr\":\"Burundi\",\"ja\":\"ブルンジ\",\"it\":\"Burundi\"}', -3.50000000, 30.00000000, '??', 'U+1F1E7 U+1F1EE', '2018-07-20 20:11:03', '2021-07-31 13:45:18', 1, 'Q967'),
(37, 'Cambodia', 'كمبوديا', 'KHM', 'KH', '855', 'Phnom Penh', 'KHR', 'KHR', '.kh', 'Kâmpŭchéa', 'Asia', 'South-Eastern Asia', '[{\"zoneName\":\"Asia\\/Phnom_Penh\",\"gmtOffset\":25200,\"gmtOffsetName\":\"UTC+07:00\",\"abbreviation\":\"ICT\",\"tzName\":\"Indochina Time\"}]', '{\"br\":\"Camboja\",\"pt\":\"Camboja\",\"nl\":\"Cambodja\",\"hr\":\"Kambodža\",\"fa\":\"کامبوج\",\"de\":\"Kambodscha\",\"es\":\"Camboya\",\"fr\":\"Cambodge\",\"ja\":\"カンボジア\",\"it\":\"Cambogia\"}', 13.00000000, 105.00000000, '??', 'U+1F1F0 U+1F1ED', '2018-07-20 20:11:03', '2021-07-31 13:45:33', 1, 'Q424'),
(38, 'Cameroon', 'الكاميرون', 'CMR', 'CM', '237', 'Yaounde', 'XAF', 'FCFA', '.cm', 'Cameroon', 'Africa', 'Middle Africa', '[{\"zoneName\":\"Africa\\/Douala\",\"gmtOffset\":3600,\"gmtOffsetName\":\"UTC+01:00\",\"abbreviation\":\"WAT\",\"tzName\":\"West Africa Time\"}]', '{\"br\":\"Camarões\",\"pt\":\"Camarões\",\"nl\":\"Kameroen\",\"hr\":\"Kamerun\",\"fa\":\"کامرون\",\"de\":\"Kamerun\",\"es\":\"Camerún\",\"fr\":\"Cameroun\",\"ja\":\"カメルーン\",\"it\":\"Camerun\"}', 6.00000000, 12.00000000, '??', 'U+1F1E8 U+1F1F2', '2018-07-20 20:11:03', '2021-07-31 13:45:46', 1, 'Q1009'),
(39, 'Canada', 'كندا', 'CAN', 'CA', '1', 'Ottawa', 'CAD', '$', '.ca', 'Canada', 'Americas', 'Northern America', '[{\"zoneName\":\"America\\/Atikokan\",\"gmtOffset\":-18000,\"gmtOffsetName\":\"UTC-05:00\",\"abbreviation\":\"EST\",\"tzName\":\"Eastern Standard Time (North America)\"},{\"zoneName\":\"America\\/Blanc-Sablon\",\"gmtOffset\":-14400,\"gmtOffsetName\":\"UTC-04:00\",\"abbreviation\":\"AST\",\"tzName\":\"Atlantic Standard Time\"},{\"zoneName\":\"America\\/Cambridge_Bay\",\"gmtOffset\":-25200,\"gmtOffsetName\":\"UTC-07:00\",\"abbreviation\":\"MST\",\"tzName\":\"Mountain Standard Time (North America)\"},{\"zoneName\":\"America\\/Creston\",\"gmtOffset\":-25200,\"gmtOffsetName\":\"UTC-07:00\",\"abbreviation\":\"MST\",\"tzName\":\"Mountain Standard Time (North America)\"},{\"zoneName\":\"America\\/Dawson\",\"gmtOffset\":-25200,\"gmtOffsetName\":\"UTC-07:00\",\"abbreviation\":\"MST\",\"tzName\":\"Mountain Standard Time (North America)\"},{\"zoneName\":\"America\\/Dawson_Creek\",\"gmtOffset\":-25200,\"gmtOffsetName\":\"UTC-07:00\",\"abbreviation\":\"MST\",\"tzName\":\"Mountain Standard Time (North America)\"},{\"zoneName\":\"America\\/Edmonton\",\"gmtOffset\":-25200,\"gmtOffsetName\":\"UTC-07:00\",\"abbreviation\":\"MST\",\"tzName\":\"Mountain Standard Time (North America)\"},{\"zoneName\":\"America\\/Fort_Nelson\",\"gmtOffset\":-25200,\"gmtOffsetName\":\"UTC-07:00\",\"abbreviation\":\"MST\",\"tzName\":\"Mountain Standard Time (North America)\"},{\"zoneName\":\"America\\/Glace_Bay\",\"gmtOffset\":-14400,\"gmtOffsetName\":\"UTC-04:00\",\"abbreviation\":\"AST\",\"tzName\":\"Atlantic Standard Time\"},{\"zoneName\":\"America\\/Goose_Bay\",\"gmtOffset\":-14400,\"gmtOffsetName\":\"UTC-04:00\",\"abbreviation\":\"AST\",\"tzName\":\"Atlantic Standard Time\"},{\"zoneName\":\"America\\/Halifax\",\"gmtOffset\":-14400,\"gmtOffsetName\":\"UTC-04:00\",\"abbreviation\":\"AST\",\"tzName\":\"Atlantic Standard Time\"},{\"zoneName\":\"America\\/Inuvik\",\"gmtOffset\":-25200,\"gmtOffsetName\":\"UTC-07:00\",\"abbreviation\":\"MST\",\"tzName\":\"Mountain Standard Time (North America\"},{\"zoneName\":\"America\\/Iqaluit\",\"gmtOffset\":-18000,\"gmtOffsetName\":\"UTC-05:00\",\"abbreviation\":\"EST\",\"tzName\":\"Eastern Standard Time (North America\"},{\"zoneName\":\"America\\/Moncton\",\"gmtOffset\":-14400,\"gmtOffsetName\":\"UTC-04:00\",\"abbreviation\":\"AST\",\"tzName\":\"Atlantic Standard Time\"},{\"zoneName\":\"America\\/Nipigon\",\"gmtOffset\":-18000,\"gmtOffsetName\":\"UTC-05:00\",\"abbreviation\":\"EST\",\"tzName\":\"Eastern Standard Time (North America\"},{\"zoneName\":\"America\\/Pangnirtung\",\"gmtOffset\":-18000,\"gmtOffsetName\":\"UTC-05:00\",\"abbreviation\":\"EST\",\"tzName\":\"Eastern Standard Time (North America\"},{\"zoneName\":\"America\\/Rainy_River\",\"gmtOffset\":-21600,\"gmtOffsetName\":\"UTC-06:00\",\"abbreviation\":\"CST\",\"tzName\":\"Central Standard Time (North America\"},{\"zoneName\":\"America\\/Rankin_Inlet\",\"gmtOffset\":-21600,\"gmtOffsetName\":\"UTC-06:00\",\"abbreviation\":\"CST\",\"tzName\":\"Central Standard Time (North America\"},{\"zoneName\":\"America\\/Regina\",\"gmtOffset\":-21600,\"gmtOffsetName\":\"UTC-06:00\",\"abbreviation\":\"CST\",\"tzName\":\"Central Standard Time (North America\"},{\"zoneName\":\"America\\/Resolute\",\"gmtOffset\":-21600,\"gmtOffsetName\":\"UTC-06:00\",\"abbreviation\":\"CST\",\"tzName\":\"Central Standard Time (North America\"},{\"zoneName\":\"America\\/St_Johns\",\"gmtOffset\":-12600,\"gmtOffsetName\":\"UTC-03:30\",\"abbreviation\":\"NST\",\"tzName\":\"Newfoundland Standard Time\"},{\"zoneName\":\"America\\/Swift_Current\",\"gmtOffset\":-21600,\"gmtOffsetName\":\"UTC-06:00\",\"abbreviation\":\"CST\",\"tzName\":\"Central Standard Time (North America\"},{\"zoneName\":\"America\\/Thunder_Bay\",\"gmtOffset\":-18000,\"gmtOffsetName\":\"UTC-05:00\",\"abbreviation\":\"EST\",\"tzName\":\"Eastern Standard Time (North America\"},{\"zoneName\":\"America\\/Toronto\",\"gmtOffset\":-18000,\"gmtOffsetName\":\"UTC-05:00\",\"abbreviation\":\"EST\",\"tzName\":\"Eastern Standard Time (North America\"},{\"zoneName\":\"America\\/Vancouver\",\"gmtOffset\":-28800,\"gmtOffsetName\":\"UTC-08:00\",\"abbreviation\":\"PST\",\"tzName\":\"Pacific Standard Time (North America\"},{\"zoneName\":\"America\\/Whitehorse\",\"gmtOffset\":-25200,\"gmtOffsetName\":\"UTC-07:00\",\"abbreviation\":\"MST\",\"tzName\":\"Mountain Standard Time (North America\"},{\"zoneName\":\"America\\/Winnipeg\",\"gmtOffset\":-21600,\"gmtOffsetName\":\"UTC-06:00\",\"abbreviation\":\"CST\",\"tzName\":\"Central Standard Time (North America\"},{\"zoneName\":\"America\\/Yellowknife\",\"gmtOffset\":-25200,\"gmtOffsetName\":\"UTC-07:00\",\"abbreviation\":\"MST\",\"tzName\":\"Mountain Standard Time (North America\"}]', '{\"br\":\"Canadá\",\"pt\":\"Canadá\",\"nl\":\"Canada\",\"hr\":\"Kanada\",\"fa\":\"کانادا\",\"de\":\"Kanada\",\"es\":\"Canadá\",\"fr\":\"Canada\",\"ja\":\"カナダ\",\"it\":\"Canada\"}', 60.00000000, -95.00000000, '??', 'U+1F1E8 U+1F1E6', '2018-07-20 20:11:03', '2021-07-31 13:45:58', 1, 'Q16'),
(40, 'Cape Verde', 'الرأس الأخضر', 'CPV', 'CV', '238', 'Praia', 'CVE', '$', '.cv', 'Cabo Verde', 'Africa', 'Western Africa', '[{\"zoneName\":\"Atlantic\\/Cape_Verde\",\"gmtOffset\":-3600,\"gmtOffsetName\":\"UTC-01:00\",\"abbreviation\":\"CVT\",\"tzName\":\"Cape Verde Time\"}]', '{\"br\":\"Cabo Verde\",\"pt\":\"Cabo Verde\",\"nl\":\"Kaapverdië\",\"hr\":\"Zelenortska Republika\",\"fa\":\"کیپ ورد\",\"de\":\"Kap Verde\",\"es\":\"Cabo Verde\",\"fr\":\"Cap Vert\",\"ja\":\"カーボベルデ\",\"it\":\"Capo Verde\"}', 16.00000000, -24.00000000, '??', 'U+1F1E8 U+1F1FB', '2018-07-20 20:11:03', '2021-07-31 13:46:17', 1, 'Q1011'),
(41, 'Cayman Islands', 'جزر كايمان', 'CYM', 'KY', '+1-345', 'George Town', 'KYD', '$', '.ky', 'Cayman Islands', 'Americas', 'Caribbean', '[{\"zoneName\":\"America\\/Cayman\",\"gmtOffset\":-18000,\"gmtOffsetName\":\"UTC-05:00\",\"abbreviation\":\"EST\",\"tzName\":\"Eastern Standard Time (North America\"}]', '{\"br\":\"Ilhas Cayman\",\"pt\":\"Ilhas Caimão\",\"nl\":\"Caymaneilanden\",\"hr\":\"Kajmanski otoci\",\"fa\":\"جزایر کیمن\",\"de\":\"Kaimaninseln\",\"es\":\"Islas Caimán\",\"fr\":\"Îles Caïmans\",\"ja\":\"ケイマン諸島\",\"it\":\"Isole Cayman\"}', 19.50000000, -80.50000000, '??', 'U+1F1F0 U+1F1FE', '2018-07-20 20:11:03', '2021-07-31 13:46:33', 1, NULL),
(42, 'Central African Republic', 'جمهورية افريقيا الوسطى', 'CAF', 'CF', '236', 'Bangui', 'XAF', 'FCFA', '.cf', 'Ködörösêse tî Bêafrîka', 'Africa', 'Middle Africa', '[{\"zoneName\":\"Africa\\/Bangui\",\"gmtOffset\":3600,\"gmtOffsetName\":\"UTC+01:00\",\"abbreviation\":\"WAT\",\"tzName\":\"West Africa Time\"}]', '{\"br\":\"República Centro-Africana\",\"pt\":\"República Centro-Africana\",\"nl\":\"Centraal-Afrikaanse Republiek\",\"hr\":\"Srednjoafrička Republika\",\"fa\":\"جمهوری آفریقای مرکزی\",\"de\":\"Zentralafrikanische Republik\",\"es\":\"República Centroafricana\",\"fr\":\"République centrafricaine\",\"ja\":\"中央アフリカ共和国\",\"it\":\"Repubblica Centrafricana\"}', 7.00000000, 21.00000000, '??', 'U+1F1E8 U+1F1EB', '2018-07-20 20:11:03', '2021-07-31 13:46:48', 1, 'Q929'),
(43, 'Chad', 'تشاد', 'TCD', 'TD', '235', 'N\'Djamena', 'XAF', 'FCFA', '.td', 'Tchad', 'Africa', 'Middle Africa', '[{\"zoneName\":\"Africa\\/Ndjamena\",\"gmtOffset\":3600,\"gmtOffsetName\":\"UTC+01:00\",\"abbreviation\":\"WAT\",\"tzName\":\"West Africa Time\"}]', '{\"br\":\"Chade\",\"pt\":\"Chade\",\"nl\":\"Tsjaad\",\"hr\":\"Čad\",\"fa\":\"چاد\",\"de\":\"Tschad\",\"es\":\"Chad\",\"fr\":\"Tchad\",\"ja\":\"チャド\",\"it\":\"Ciad\"}', 15.00000000, 19.00000000, '??', 'U+1F1F9 U+1F1E9', '2018-07-20 20:11:03', '2021-07-31 13:47:03', 1, 'Q657'),
(44, 'Chile', 'تشيلي', 'CHL', 'CL', '56', 'Santiago', 'CLP', '$', '.cl', 'Chile', 'Americas', 'South America', '[{\"zoneName\":\"America\\/Punta_Arenas\",\"gmtOffset\":-10800,\"gmtOffsetName\":\"UTC-03:00\",\"abbreviation\":\"CLST\",\"tzName\":\"Chile Summer Time\"},{\"zoneName\":\"America\\/Santiago\",\"gmtOffset\":-10800,\"gmtOffsetName\":\"UTC-03:00\",\"abbreviation\":\"CLST\",\"tzName\":\"Chile Summer Time\"},{\"zoneName\":\"Pacific\\/Easter\",\"gmtOffset\":-18000,\"gmtOffsetName\":\"UTC-05:00\",\"abbreviation\":\"EASST\",\"tzName\":\"Easter Island Summer Time\"}]', '{\"br\":\"Chile\",\"pt\":\"Chile\",\"nl\":\"Chili\",\"hr\":\"Čile\",\"fa\":\"شیلی\",\"de\":\"Chile\",\"es\":\"Chile\",\"fr\":\"Chili\",\"ja\":\"チリ\",\"it\":\"Cile\"}', -30.00000000, -71.00000000, '??', 'U+1F1E8 U+1F1F1', '2018-07-20 20:11:03', '2021-07-31 13:47:17', 1, 'Q298'),
(45, 'China', 'الصين', 'CHN', 'CN', '86', 'Beijing', 'CNY', '¥', '.cn', '中国', 'Asia', 'Eastern Asia', '[{\"zoneName\":\"Asia\\/Shanghai\",\"gmtOffset\":28800,\"gmtOffsetName\":\"UTC+08:00\",\"abbreviation\":\"CST\",\"tzName\":\"China Standard Time\"},{\"zoneName\":\"Asia\\/Urumqi\",\"gmtOffset\":21600,\"gmtOffsetName\":\"UTC+06:00\",\"abbreviation\":\"XJT\",\"tzName\":\"China Standard Time\"}]', '{\"br\":\"China\",\"pt\":\"China\",\"nl\":\"China\",\"hr\":\"Kina\",\"fa\":\"چین\",\"de\":\"China\",\"es\":\"China\",\"fr\":\"Chine\",\"ja\":\"中国\",\"it\":\"Cina\"}', 35.00000000, 105.00000000, '??', 'U+1F1E8 U+1F1F3', '2018-07-20 20:11:03', '2021-07-31 13:47:30', 1, 'Q148'),
(46, 'Christmas Island', 'جزيرة الكريسماس', 'CXR', 'CX', '61', 'Flying Fish Cove', 'AUD', '$', '.cx', 'Christmas Island', 'Oceania', 'Australia and New Zealand', '[{\"zoneName\":\"Indian\\/Christmas\",\"gmtOffset\":25200,\"gmtOffsetName\":\"UTC+07:00\",\"abbreviation\":\"CXT\",\"tzName\":\"Christmas Island Time\"}]', '{\"br\":\"Ilha Christmas\",\"pt\":\"Ilha do Natal\",\"nl\":\"Christmaseiland\",\"hr\":\"Božićni otok\",\"fa\":\"جزیره کریسمس\",\"de\":\"Weihnachtsinsel\",\"es\":\"Isla de Navidad\",\"fr\":\"Île Christmas\",\"ja\":\"クリスマス島\",\"it\":\"Isola di Natale\"}', -10.50000000, 105.66666666, '??', 'U+1F1E8 U+1F1FD', '2018-07-20 20:11:03', '2021-07-31 13:47:43', 1, NULL),
(47, 'Cocos (Keeling) Islands', 'جزر كوكوس (كيلينغ)', 'CCK', 'CC', '61', 'West Island', 'AUD', '$', '.cc', 'Cocos (Keeling) Islands', 'Oceania', 'Australia and New Zealand', '[{\"zoneName\":\"Indian\\/Cocos\",\"gmtOffset\":23400,\"gmtOffsetName\":\"UTC+06:30\",\"abbreviation\":\"CCT\",\"tzName\":\"Cocos Islands Time\"}]', '{\"br\":\"Ilhas Cocos\",\"pt\":\"Ilhas dos Cocos\",\"nl\":\"Cocoseilanden\",\"hr\":\"Kokosovi Otoci\",\"fa\":\"جزایر کوکوس\",\"de\":\"Kokosinseln\",\"es\":\"Islas Cocos o Islas Keeling\",\"fr\":\"Îles Cocos\",\"ja\":\"ココス（キーリング）諸島\",\"it\":\"Isole Cocos e Keeling\"}', -12.50000000, 96.83333333, '??', 'U+1F1E8 U+1F1E8', '2018-07-20 20:11:03', '2021-07-31 13:47:57', 1, NULL),
(48, 'Colombia', 'كولومبيا', 'COL', 'CO', '57', 'Bogota', 'COP', '$', '.co', 'Colombia', 'Americas', 'South America', '[{\"zoneName\":\"America\\/Bogota\",\"gmtOffset\":-18000,\"gmtOffsetName\":\"UTC-05:00\",\"abbreviation\":\"COT\",\"tzName\":\"Colombia Time\"}]', '{\"br\":\"Colômbia\",\"pt\":\"Colômbia\",\"nl\":\"Colombia\",\"hr\":\"Kolumbija\",\"fa\":\"کلمبیا\",\"de\":\"Kolumbien\",\"es\":\"Colombia\",\"fr\":\"Colombie\",\"ja\":\"コロンビア\",\"it\":\"Colombia\"}', 4.00000000, -72.00000000, '??', 'U+1F1E8 U+1F1F4', '2018-07-20 20:11:03', '2021-07-31 13:48:09', 1, 'Q739'),
(49, 'Comoros', 'جزر القمر', 'COM', 'KM', '269', 'Moroni', 'KMF', 'CF', '.km', 'Komori', 'Africa', 'Eastern Africa', '[{\"zoneName\":\"Indian\\/Comoro\",\"gmtOffset\":10800,\"gmtOffsetName\":\"UTC+03:00\",\"abbreviation\":\"EAT\",\"tzName\":\"East Africa Time\"}]', '{\"br\":\"Comores\",\"pt\":\"Comores\",\"nl\":\"Comoren\",\"hr\":\"Komori\",\"fa\":\"کومور\",\"de\":\"Union der Komoren\",\"es\":\"Comoras\",\"fr\":\"Comores\",\"ja\":\"コモロ\",\"it\":\"Comore\"}', -12.16666666, 44.25000000, '??', 'U+1F1F0 U+1F1F2', '2018-07-20 20:11:03', '2021-07-31 13:48:20', 1, 'Q970'),
(50, 'Congo', 'الكونغو', 'COG', 'CG', '242', 'Brazzaville', 'XAF', 'FC', '.cg', 'République du Congo', 'Africa', 'Middle Africa', '[{\"zoneName\":\"Africa\\/Brazzaville\",\"gmtOffset\":3600,\"gmtOffsetName\":\"UTC+01:00\",\"abbreviation\":\"WAT\",\"tzName\":\"West Africa Time\"}]', '{\"br\":\"Congo\",\"pt\":\"Congo\",\"nl\":\"Congo [Republiek]\",\"hr\":\"Kongo\",\"fa\":\"کنگو\",\"de\":\"Kongo\",\"es\":\"Congo\",\"fr\":\"Congo\",\"ja\":\"コンゴ共和国\",\"it\":\"Congo\"}', -1.00000000, 15.00000000, '??', 'U+1F1E8 U+1F1EC', '2018-07-20 20:11:03', '2021-07-31 13:48:35', 1, 'Q971'),
(51, 'Congo The Democratic Republic Of The', 'جمهورية الكونغو الديمقراطية\r\n', 'COD', 'CD', '243', 'Kinshasa', 'CDF', 'FC', '.cd', 'République démocratique du Congo', 'Africa', 'Middle Africa', '[{\"zoneName\":\"Africa\\/Kinshasa\",\"gmtOffset\":3600,\"gmtOffsetName\":\"UTC+01:00\",\"abbreviation\":\"WAT\",\"tzName\":\"West Africa Time\"},{\"zoneName\":\"Africa\\/Lubumbashi\",\"gmtOffset\":7200,\"gmtOffsetName\":\"UTC+02:00\",\"abbreviation\":\"CAT\",\"tzName\":\"Central Africa Time\"}]', '{\"br\":\"RD Congo\",\"pt\":\"RD Congo\",\"nl\":\"Congo [DRC]\",\"hr\":\"Kongo, Demokratska Republika\",\"fa\":\"جمهوری کنگو\",\"de\":\"Kongo (Dem. Rep.)\",\"es\":\"Congo (Rep. Dem.)\",\"fr\":\"Congo (Rép. dém.)\",\"ja\":\"コンゴ民主共和国\",\"it\":\"Congo (Rep. Dem.)\"}', 0.00000000, 25.00000000, '??', 'U+1F1E8 U+1F1E9', '2018-07-20 20:11:03', '2021-07-31 15:23:36', 1, 'Q974'),
(52, 'Cook Islands', 'جزر كوك\r\n', 'COK', 'CK', '682', 'Avarua', 'NZD', '$', '.ck', 'Cook Islands', 'Oceania', 'Polynesia', '[{\"zoneName\":\"Pacific\\/Rarotonga\",\"gmtOffset\":-36000,\"gmtOffsetName\":\"UTC-10:00\",\"abbreviation\":\"CKT\",\"tzName\":\"Cook Island Time\"}]', '{\"br\":\"Ilhas Cook\",\"pt\":\"Ilhas Cook\",\"nl\":\"Cookeilanden\",\"hr\":\"Cookovo Otočje\",\"fa\":\"جزایر کوک\",\"de\":\"Cookinseln\",\"es\":\"Islas Cook\",\"fr\":\"Îles Cook\",\"ja\":\"クック諸島\",\"it\":\"Isole Cook\"}', -21.23333333, -159.76666666, '??', 'U+1F1E8 U+1F1F0', '2018-07-20 20:11:03', '2021-07-31 15:23:45', 1, 'Q26988'),
(53, 'Costa Rica', 'كوستا ريكا\r\n', 'CRI', 'CR', '506', 'San Jose', 'CRC', '₡', '.cr', 'Costa Rica', 'Americas', 'Central America', '[{\"zoneName\":\"America\\/Costa_Rica\",\"gmtOffset\":-21600,\"gmtOffsetName\":\"UTC-06:00\",\"abbreviation\":\"CST\",\"tzName\":\"Central Standard Time (North America\"}]', '{\"br\":\"Costa Rica\",\"pt\":\"Costa Rica\",\"nl\":\"Costa Rica\",\"hr\":\"Kostarika\",\"fa\":\"کاستاریکا\",\"de\":\"Costa Rica\",\"es\":\"Costa Rica\",\"fr\":\"Costa Rica\",\"ja\":\"コスタリカ\",\"it\":\"Costa Rica\"}', 10.00000000, -84.00000000, '??', 'U+1F1E8 U+1F1F7', '2018-07-20 20:11:03', '2021-07-31 15:24:06', 1, 'Q800'),
(54, 'Cote D\'Ivoire (Ivory Coast)', 'كوت ديفوار (ساحل العاج)\r\n', 'CIV', 'CI', '225', 'Yamoussoukro', 'XOF', 'CFA', '.ci', NULL, 'Africa', 'Western Africa', '[{\"zoneName\":\"Africa\\/Abidjan\",\"gmtOffset\":0,\"gmtOffsetName\":\"UTC\\u00b100\",\"abbreviation\":\"GMT\",\"tzName\":\"Greenwich Mean Time\"}]', '{\"br\":\"Costa do Marfim\",\"pt\":\"Costa do Marfim\",\"nl\":\"Ivoorkust\",\"hr\":\"Obala Bjelokosti\",\"fa\":\"ساحل عاج\",\"de\":\"Elfenbeinküste\",\"es\":\"Costa de Marfil\",\"fr\":\"Côte d\'Ivoire\",\"ja\":\"コートジボワール\",\"it\":\"Costa D\'Avorio\"}', 8.00000000, -5.00000000, '??', 'U+1F1E8 U+1F1EE', '2018-07-20 20:11:03', '2021-07-31 15:24:14', 1, 'Q1008'),
(55, 'Croatia (Hrvatska)', 'كرواتيا (هرفاتسكا)\r\n', 'HRV', 'HR', '385', 'Zagreb', 'HRK', 'kn', '.hr', 'Hrvatska', 'Europe', 'Southern Europe', '[{\"zoneName\":\"Europe\\/Zagreb\",\"gmtOffset\":3600,\"gmtOffsetName\":\"UTC+01:00\",\"abbreviation\":\"CET\",\"tzName\":\"Central European Time\"}]', '{\"br\":\"Croácia\",\"pt\":\"Croácia\",\"nl\":\"Kroatië\",\"hr\":\"Hrvatska\",\"fa\":\"کرواسی\",\"de\":\"Kroatien\",\"es\":\"Croacia\",\"fr\":\"Croatie\",\"ja\":\"クロアチア\",\"it\":\"Croazia\"}', 45.16666666, 15.50000000, '??', 'U+1F1ED U+1F1F7', '2018-07-20 20:11:03', '2021-07-31 15:24:20', 1, 'Q224'),
(56, 'Cuba', 'كوبا\r\n', 'CUB', 'CU', '53', 'Havana', 'CUP', '$', '.cu', 'Cuba', 'Americas', 'Caribbean', '[{\"zoneName\":\"America\\/Havana\",\"gmtOffset\":-18000,\"gmtOffsetName\":\"UTC-05:00\",\"abbreviation\":\"CST\",\"tzName\":\"Cuba Standard Time\"}]', '{\"br\":\"Cuba\",\"pt\":\"Cuba\",\"nl\":\"Cuba\",\"hr\":\"Kuba\",\"fa\":\"کوبا\",\"de\":\"Kuba\",\"es\":\"Cuba\",\"fr\":\"Cuba\",\"ja\":\"キューバ\",\"it\":\"Cuba\"}', 21.50000000, -80.00000000, '??', 'U+1F1E8 U+1F1FA', '2018-07-20 20:11:03', '2021-07-31 15:24:34', 1, 'Q241'),
(57, 'Cyprus', 'قبرص\r\n', 'CYP', 'CY', '357', 'Nicosia', 'EUR', '€', '.cy', 'Κύπρος', 'Europe', 'Southern Europe', '[{\"zoneName\":\"Asia\\/Famagusta\",\"gmtOffset\":7200,\"gmtOffsetName\":\"UTC+02:00\",\"abbreviation\":\"EET\",\"tzName\":\"Eastern European Time\"},{\"zoneName\":\"Asia\\/Nicosia\",\"gmtOffset\":7200,\"gmtOffsetName\":\"UTC+02:00\",\"abbreviation\":\"EET\",\"tzName\":\"Eastern European Time\"}]', '{\"br\":\"Chipre\",\"pt\":\"Chipre\",\"nl\":\"Cyprus\",\"hr\":\"Cipar\",\"fa\":\"قبرس\",\"de\":\"Zypern\",\"es\":\"Chipre\",\"fr\":\"Chypre\",\"ja\":\"キプロス\",\"it\":\"Cipro\"}', 35.00000000, 33.00000000, '??', 'U+1F1E8 U+1F1FE', '2018-07-20 20:11:03', '2021-07-31 15:25:06', 1, 'Q229'),
(58, 'Czech Republic', 'جمهورية التشيك\r\n', 'CZE', 'CZ', '420', 'Prague', 'CZK', 'Kč', '.cz', 'Česká republika', 'Europe', 'Eastern Europe', '[{\"zoneName\":\"Europe\\/Prague\",\"gmtOffset\":3600,\"gmtOffsetName\":\"UTC+01:00\",\"abbreviation\":\"CET\",\"tzName\":\"Central European Time\"}]', '{\"br\":\"República Tcheca\",\"pt\":\"República Checa\",\"nl\":\"Tsjechië\",\"hr\":\"Češka\",\"fa\":\"جمهوری چک\",\"de\":\"Tschechische Republik\",\"es\":\"República Checa\",\"fr\":\"République tchèque\",\"ja\":\"チェコ\",\"it\":\"Repubblica Ceca\"}', 49.75000000, 15.50000000, '??', 'U+1F1E8 U+1F1FF', '2018-07-20 20:11:03', '2021-07-31 15:25:20', 1, 'Q213'),
(59, 'Denmark', 'الدنمارك\r\n', 'DNK', 'DK', '45', 'Copenhagen', 'DKK', 'Kr.', '.dk', 'Danmark', 'Europe', 'Northern Europe', '[{\"zoneName\":\"Europe\\/Copenhagen\",\"gmtOffset\":3600,\"gmtOffsetName\":\"UTC+01:00\",\"abbreviation\":\"CET\",\"tzName\":\"Central European Time\"}]', '{\"br\":\"Dinamarca\",\"pt\":\"Dinamarca\",\"nl\":\"Denemarken\",\"hr\":\"Danska\",\"fa\":\"دانمارک\",\"de\":\"Dänemark\",\"es\":\"Dinamarca\",\"fr\":\"Danemark\",\"ja\":\"デンマーク\",\"it\":\"Danimarca\"}', 56.00000000, 10.00000000, '??', 'U+1F1E9 U+1F1F0', '2018-07-20 20:11:03', '2021-07-31 15:25:27', 1, 'Q35'),
(60, 'Djibouti', 'جيبوتي', 'DJI', 'DJ', '253', 'Djibouti', 'DJF', 'Fdj', '.dj', 'Djibouti', 'Africa', 'Eastern Africa', '[{\"zoneName\":\"Africa\\/Djibouti\",\"gmtOffset\":10800,\"gmtOffsetName\":\"UTC+03:00\",\"abbreviation\":\"EAT\",\"tzName\":\"East Africa Time\"}]', '{\"br\":\"Djibuti\",\"pt\":\"Djibuti\",\"nl\":\"Djibouti\",\"hr\":\"Džibuti\",\"fa\":\"جیبوتی\",\"de\":\"Dschibuti\",\"es\":\"Yibuti\",\"fr\":\"Djibouti\",\"ja\":\"ジブチ\",\"it\":\"Gibuti\"}', 11.50000000, 43.00000000, '??', 'U+1F1E9 U+1F1EF', '2018-07-20 20:11:03', '2021-07-31 15:25:49', 1, 'Q977'),
(61, 'Dominica', 'دومينيكا\r\n', 'DMA', 'DM', '+1-767', 'Roseau', 'XCD', '$', '.dm', 'Dominica', 'Americas', 'Caribbean', '[{\"zoneName\":\"America\\/Dominica\",\"gmtOffset\":-14400,\"gmtOffsetName\":\"UTC-04:00\",\"abbreviation\":\"AST\",\"tzName\":\"Atlantic Standard Time\"}]', '{\"br\":\"Dominica\",\"pt\":\"Dominica\",\"nl\":\"Dominica\",\"hr\":\"Dominika\",\"fa\":\"دومینیکا\",\"de\":\"Dominica\",\"es\":\"Dominica\",\"fr\":\"Dominique\",\"ja\":\"ドミニカ国\",\"it\":\"Dominica\"}', 15.41666666, -61.33333333, '??', 'U+1F1E9 U+1F1F2', '2018-07-20 20:11:03', '2021-07-31 15:25:59', 1, 'Q784');
INSERT INTO `countries` (`id`, `name`, `nameAR`, `iso3`, `iso2`, `phonecode`, `capital`, `currency`, `currency_symbol`, `tld`, `native`, `region`, `subregion`, `timezones`, `translations`, `latitude`, `longitude`, `emoji`, `emojiU`, `created_at`, `updated_at`, `flag`, `wikiDataId`) VALUES
(62, 'Dominican Republic', 'جمهورية الدومنيكان\r\n', 'DOM', 'DO', '+1-809 and 1-829', 'Santo Domingo', 'DOP', '$', '.do', 'República Dominicana', 'Americas', 'Caribbean', '[{\"zoneName\":\"America\\/Santo_Domingo\",\"gmtOffset\":-14400,\"gmtOffsetName\":\"UTC-04:00\",\"abbreviation\":\"AST\",\"tzName\":\"Atlantic Standard Time\"}]', '{\"br\":\"República Dominicana\",\"pt\":\"República Dominicana\",\"nl\":\"Dominicaanse Republiek\",\"hr\":\"Dominikanska Republika\",\"fa\":\"جمهوری دومینیکن\",\"de\":\"Dominikanische Republik\",\"es\":\"República Dominicana\",\"fr\":\"République dominicaine\",\"ja\":\"ドミニカ共和国\",\"it\":\"Repubblica Dominicana\"}', 19.00000000, -70.66666666, '??', 'U+1F1E9 U+1F1F4', '2018-07-20 20:11:03', '2021-07-31 15:26:05', 1, 'Q786'),
(63, 'East Timor', 'تيمور الشرقية\r\n', 'TLS', 'TL', '670', 'Dili', 'USD', '$', '.tl', 'Timor-Leste', 'Asia', 'South-Eastern Asia', '[{\"zoneName\":\"Asia\\/Dili\",\"gmtOffset\":32400,\"gmtOffsetName\":\"UTC+09:00\",\"abbreviation\":\"TLT\",\"tzName\":\"Timor Leste Time\"}]', '{\"br\":\"Timor Leste\",\"pt\":\"Timor Leste\",\"nl\":\"Oost-Timor\",\"hr\":\"Istočni Timor\",\"fa\":\"تیمور شرقی\",\"de\":\"Timor-Leste\",\"es\":\"Timor Oriental\",\"fr\":\"Timor oriental\",\"ja\":\"東ティモール\",\"it\":\"Timor Est\"}', -8.83333333, 125.91666666, '??', 'U+1F1F9 U+1F1F1', '2018-07-20 20:11:03', '2021-07-31 15:26:15', 1, 'Q574'),
(64, 'Ecuador', 'الاكوادور\r\n', 'ECU', 'EC', '593', 'Quito', 'USD', '$', '.ec', 'Ecuador', 'Americas', 'South America', '[{\"zoneName\":\"America\\/Guayaquil\",\"gmtOffset\":-18000,\"gmtOffsetName\":\"UTC-05:00\",\"abbreviation\":\"ECT\",\"tzName\":\"Ecuador Time\"},{\"zoneName\":\"Pacific\\/Galapagos\",\"gmtOffset\":-21600,\"gmtOffsetName\":\"UTC-06:00\",\"abbreviation\":\"GALT\",\"tzName\":\"Gal\\u00e1pagos Time\"}]', '{\"br\":\"Equador\",\"pt\":\"Equador\",\"nl\":\"Ecuador\",\"hr\":\"Ekvador\",\"fa\":\"اکوادور\",\"de\":\"Ecuador\",\"es\":\"Ecuador\",\"fr\":\"Équateur\",\"ja\":\"エクアドル\",\"it\":\"Ecuador\"}', -2.00000000, -77.50000000, '??', 'U+1F1EA U+1F1E8', '2018-07-20 20:11:03', '2021-07-31 15:26:25', 1, 'Q736'),
(65, 'Egypt', 'مصر', 'EGY', 'EG', '20', 'Cairo', 'EGP', 'ج.م', '.eg', 'مصر‎', 'Africa', 'Northern Africa', '[{\"zoneName\":\"Africa\\/Cairo\",\"gmtOffset\":7200,\"gmtOffsetName\":\"UTC+02:00\",\"abbreviation\":\"EET\",\"tzName\":\"Eastern European Time\"}]', '{\"br\":\"Egito\",\"pt\":\"Egipto\",\"nl\":\"Egypte\",\"hr\":\"Egipat\",\"fa\":\"مصر\",\"de\":\"Ägypten\",\"es\":\"Egipto\",\"fr\":\"Égypte\",\"ja\":\"エジプト\",\"it\":\"Egitto\"}', 27.00000000, 30.00000000, '??', 'U+1F1EA U+1F1EC', '2018-07-20 20:11:03', '2021-07-31 15:23:10', 1, 'Q79'),
(66, 'El Salvador', 'السلفادور', 'SLV', 'SV', '503', 'San Salvador', 'USD', '$', '.sv', 'El Salvador', 'Americas', 'Central America', '[{\"zoneName\":\"America\\/El_Salvador\",\"gmtOffset\":-21600,\"gmtOffsetName\":\"UTC-06:00\",\"abbreviation\":\"CST\",\"tzName\":\"Central Standard Time (North America\"}]', '{\"br\":\"El Salvador\",\"pt\":\"El Salvador\",\"nl\":\"El Salvador\",\"hr\":\"Salvador\",\"fa\":\"السالوادور\",\"de\":\"El Salvador\",\"es\":\"El Salvador\",\"fr\":\"Salvador\",\"ja\":\"エルサルバドル\",\"it\":\"El Salvador\"}', 13.83333333, -88.91666666, '??', 'U+1F1F8 U+1F1FB', '2018-07-20 20:11:03', '2021-07-31 15:26:42', 1, 'Q792'),
(67, 'Equatorial Guinea', 'غينيا الإستوائية\r\n', 'GNQ', 'GQ', '240', 'Malabo', 'XAF', 'FCFA', '.gq', 'Guinea Ecuatorial', 'Africa', 'Middle Africa', '[{\"zoneName\":\"Africa\\/Malabo\",\"gmtOffset\":3600,\"gmtOffsetName\":\"UTC+01:00\",\"abbreviation\":\"WAT\",\"tzName\":\"West Africa Time\"}]', '{\"br\":\"Guiné Equatorial\",\"pt\":\"Guiné Equatorial\",\"nl\":\"Equatoriaal-Guinea\",\"hr\":\"Ekvatorijalna Gvineja\",\"fa\":\"گینه استوایی\",\"de\":\"Äquatorial-Guinea\",\"es\":\"Guinea Ecuatorial\",\"fr\":\"Guinée-Équatoriale\",\"ja\":\"赤道ギニア\",\"it\":\"Guinea Equatoriale\"}', 2.00000000, 10.00000000, '??', 'U+1F1EC U+1F1F6', '2018-07-20 20:11:03', '2021-07-31 15:26:49', 1, 'Q983'),
(68, 'Eritrea', 'إريتريا\r\n\r\n', 'ERI', 'ER', '291', 'Asmara', 'ERN', 'Nfk', '.er', 'ኤርትራ', 'Africa', 'Eastern Africa', '[{\"zoneName\":\"Africa\\/Asmara\",\"gmtOffset\":10800,\"gmtOffsetName\":\"UTC+03:00\",\"abbreviation\":\"EAT\",\"tzName\":\"East Africa Time\"}]', '{\"br\":\"Eritreia\",\"pt\":\"Eritreia\",\"nl\":\"Eritrea\",\"hr\":\"Eritreja\",\"fa\":\"اریتره\",\"de\":\"Eritrea\",\"es\":\"Eritrea\",\"fr\":\"Érythrée\",\"ja\":\"エリトリア\",\"it\":\"Eritrea\"}', 15.00000000, 39.00000000, '??', 'U+1F1EA U+1F1F7', '2018-07-20 20:11:03', '2021-07-31 15:26:57', 1, 'Q986'),
(69, 'Estonia', 'إستونيا', 'EST', 'EE', '372', 'Tallinn', 'EUR', '€', '.ee', 'Eesti', 'Europe', 'Northern Europe', '[{\"zoneName\":\"Europe\\/Tallinn\",\"gmtOffset\":7200,\"gmtOffsetName\":\"UTC+02:00\",\"abbreviation\":\"EET\",\"tzName\":\"Eastern European Time\"}]', '{\"br\":\"Estônia\",\"pt\":\"Estónia\",\"nl\":\"Estland\",\"hr\":\"Estonija\",\"fa\":\"استونی\",\"de\":\"Estland\",\"es\":\"Estonia\",\"fr\":\"Estonie\",\"ja\":\"エストニア\",\"it\":\"Estonia\"}', 59.00000000, 26.00000000, '??', 'U+1F1EA U+1F1EA', '2018-07-20 20:11:03', '2021-07-31 15:27:10', 1, 'Q191'),
(70, 'Ethiopia', 'أثيوبيا\r\n', 'ETH', 'ET', '251', 'Addis Ababa', 'ETB', 'Nkf', '.et', 'ኢትዮጵያ', 'Africa', 'Eastern Africa', '[{\"zoneName\":\"Africa\\/Addis_Ababa\",\"gmtOffset\":10800,\"gmtOffsetName\":\"UTC+03:00\",\"abbreviation\":\"EAT\",\"tzName\":\"East Africa Time\"}]', '{\"br\":\"Etiópia\",\"pt\":\"Etiópia\",\"nl\":\"Ethiopië\",\"hr\":\"Etiopija\",\"fa\":\"اتیوپی\",\"de\":\"Äthiopien\",\"es\":\"Etiopía\",\"fr\":\"Éthiopie\",\"ja\":\"エチオピア\",\"it\":\"Etiopia\"}', 8.00000000, 38.00000000, '??', 'U+1F1EA U+1F1F9', '2018-07-20 20:11:03', '2021-07-31 15:27:18', 1, 'Q115'),
(71, 'Falkland Islands', 'جزر فوكلاند\r\n', 'FLK', 'FK', '500', 'Stanley', 'FKP', '£', '.fk', 'Falkland Islands', 'Americas', 'South America', '[{\"zoneName\":\"Atlantic\\/Stanley\",\"gmtOffset\":-10800,\"gmtOffsetName\":\"UTC-03:00\",\"abbreviation\":\"FKST\",\"tzName\":\"Falkland Islands Summer Time\"}]', '{\"br\":\"Ilhas Malvinas\",\"pt\":\"Ilhas Falkland\",\"nl\":\"Falklandeilanden [Islas Malvinas]\",\"hr\":\"Falklandski Otoci\",\"fa\":\"جزایر فالکلند\",\"de\":\"Falklandinseln\",\"es\":\"Islas Malvinas\",\"fr\":\"Îles Malouines\",\"ja\":\"フォークランド（マルビナス）諸島\",\"it\":\"Isole Falkland o Isole Malvine\"}', -51.75000000, -59.00000000, '??', 'U+1F1EB U+1F1F0', '2018-07-20 20:11:03', '2021-07-31 15:27:28', 1, NULL),
(72, 'Faroe Islands', 'جزر فاروس\r\n', 'FRO', 'FO', '298', 'Torshavn', 'DKK', 'Kr.', '.fo', 'Føroyar', 'Europe', 'Northern Europe', '[{\"zoneName\":\"Atlantic\\/Faroe\",\"gmtOffset\":0,\"gmtOffsetName\":\"UTC\\u00b100\",\"abbreviation\":\"WET\",\"tzName\":\"Western European Time\"}]', '{\"br\":\"Ilhas Faroé\",\"pt\":\"Ilhas Faroé\",\"nl\":\"Faeröer\",\"hr\":\"Farski Otoci\",\"fa\":\"جزایر فارو\",\"de\":\"Färöer-Inseln\",\"es\":\"Islas Faroe\",\"fr\":\"Îles Féroé\",\"ja\":\"フェロー諸島\",\"it\":\"Isole Far Oer\"}', 62.00000000, -7.00000000, '??', 'U+1F1EB U+1F1F4', '2018-07-20 20:11:03', '2021-07-31 15:27:53', 1, NULL),
(73, 'Fiji Islands', 'جزر فيجي\r\n', 'FJI', 'FJ', '679', 'Suva', 'FJD', 'FJ$', '.fj', 'Fiji', 'Oceania', 'Melanesia', '[{\"zoneName\":\"Pacific\\/Fiji\",\"gmtOffset\":43200,\"gmtOffsetName\":\"UTC+12:00\",\"abbreviation\":\"FJT\",\"tzName\":\"Fiji Time\"}]', '{\"br\":\"Fiji\",\"pt\":\"Fiji\",\"nl\":\"Fiji\",\"hr\":\"Fiđi\",\"fa\":\"فیجی\",\"de\":\"Fidschi\",\"es\":\"Fiyi\",\"fr\":\"Fidji\",\"ja\":\"フィジー\",\"it\":\"Figi\"}', -18.00000000, 175.00000000, '??', 'U+1F1EB U+1F1EF', '2018-07-20 20:11:03', '2021-07-31 15:28:00', 1, 'Q712'),
(74, 'Finland', 'فنلندا\r\n', 'FIN', 'FI', '358', 'Helsinki', 'EUR', '€', '.fi', 'Suomi', 'Europe', 'Northern Europe', '[{\"zoneName\":\"Europe\\/Helsinki\",\"gmtOffset\":7200,\"gmtOffsetName\":\"UTC+02:00\",\"abbreviation\":\"EET\",\"tzName\":\"Eastern European Time\"}]', '{\"br\":\"Finlândia\",\"pt\":\"Finlândia\",\"nl\":\"Finland\",\"hr\":\"Finska\",\"fa\":\"فنلاند\",\"de\":\"Finnland\",\"es\":\"Finlandia\",\"fr\":\"Finlande\",\"ja\":\"フィンランド\",\"it\":\"Finlandia\"}', 64.00000000, 26.00000000, '??', 'U+1F1EB U+1F1EE', '2018-07-20 20:11:03', '2021-07-31 15:28:07', 1, 'Q33'),
(75, 'France', 'فرنسا', 'FRA', 'FR', '33', 'Paris', 'EUR', '€', '.fr', 'France', 'Europe', 'Western Europe', '[{\"zoneName\":\"Europe\\/Paris\",\"gmtOffset\":3600,\"gmtOffsetName\":\"UTC+01:00\",\"abbreviation\":\"CET\",\"tzName\":\"Central European Time\"}]', '{\"br\":\"França\",\"pt\":\"França\",\"nl\":\"Frankrijk\",\"hr\":\"Francuska\",\"fa\":\"فرانسه\",\"de\":\"Frankreich\",\"es\":\"Francia\",\"fr\":\"France\",\"ja\":\"フランス\",\"it\":\"Francia\"}', 46.00000000, 2.00000000, '??', 'U+1F1EB U+1F1F7', '2018-07-20 20:11:03', '2021-07-31 15:28:14', 1, 'Q142'),
(76, 'French Guiana', 'غيانا الفرنسية\r\n', 'GUF', 'GF', '594', 'Cayenne', 'EUR', '€', '.gf', 'Guyane française', 'Americas', 'South America', '[{\"zoneName\":\"America\\/Cayenne\",\"gmtOffset\":-10800,\"gmtOffsetName\":\"UTC-03:00\",\"abbreviation\":\"GFT\",\"tzName\":\"French Guiana Time\"}]', '{\"br\":\"Guiana Francesa\",\"pt\":\"Guiana Francesa\",\"nl\":\"Frans-Guyana\",\"hr\":\"Francuska Gvajana\",\"fa\":\"گویان فرانسه\",\"de\":\"Französisch Guyana\",\"es\":\"Guayana Francesa\",\"fr\":\"Guayane\",\"ja\":\"フランス領ギアナ\",\"it\":\"Guyana francese\"}', 4.00000000, -53.00000000, '??', 'U+1F1EC U+1F1EB', '2018-07-20 20:11:03', '2021-07-31 15:28:33', 1, NULL),
(77, 'French Polynesia', 'بولينيزيا الفرنسية\r\n', 'PYF', 'PF', '689', 'Papeete', 'XPF', '₣', '.pf', 'Polynésie française', 'Oceania', 'Polynesia', '[{\"zoneName\":\"Pacific\\/Gambier\",\"gmtOffset\":-32400,\"gmtOffsetName\":\"UTC-09:00\",\"abbreviation\":\"GAMT\",\"tzName\":\"Gambier Islands Time\"},{\"zoneName\":\"Pacific\\/Marquesas\",\"gmtOffset\":-34200,\"gmtOffsetName\":\"UTC-09:30\",\"abbreviation\":\"MART\",\"tzName\":\"Marquesas Islands Time\"},{\"zoneName\":\"Pacific\\/Tahiti\",\"gmtOffset\":-36000,\"gmtOffsetName\":\"UTC-10:00\",\"abbreviation\":\"TAHT\",\"tzName\":\"Tahiti Time\"}]', '{\"br\":\"Polinésia Francesa\",\"pt\":\"Polinésia Francesa\",\"nl\":\"Frans-Polynesië\",\"hr\":\"Francuska Polinezija\",\"fa\":\"پلی‌نزی فرانسه\",\"de\":\"Französisch-Polynesien\",\"es\":\"Polinesia Francesa\",\"fr\":\"Polynésie française\",\"ja\":\"フランス領ポリネシア\",\"it\":\"Polinesia Francese\"}', -15.00000000, -140.00000000, '??', 'U+1F1F5 U+1F1EB', '2018-07-20 20:11:03', '2021-07-31 15:28:40', 1, NULL),
(78, 'French Southern Territories', 'المناطق الجنوبية لفرنسا\r\n', 'ATF', 'TF', '', 'Port-aux-Francais', 'EUR', '€', '.tf', 'Territoire des Terres australes et antarctiques fr', 'Africa', 'Southern Africa', '[{\"zoneName\":\"Indian\\/Kerguelen\",\"gmtOffset\":18000,\"gmtOffsetName\":\"UTC+05:00\",\"abbreviation\":\"TFT\",\"tzName\":\"French Southern and Antarctic Time\"}]', '{\"br\":\"Terras Austrais e Antárticas Francesas\",\"pt\":\"Terras Austrais e Antárticas Francesas\",\"nl\":\"Franse Gebieden in de zuidelijke Indische Oceaan\",\"hr\":\"Francuski južni i antarktički teritoriji\",\"fa\":\"سرزمین‌های جنوبی و جنوبگانی فرانسه\",\"de\":\"Französische Süd- und Antarktisgebiete\",\"es\":\"Tierras Australes y Antárticas Francesas\",\"fr\":\"Terres australes et antarctiques françaises\",\"ja\":\"フランス領南方・南極地域\",\"it\":\"Territori Francesi del Sud\"}', -49.25000000, 69.16700000, '??', 'U+1F1F9 U+1F1EB', '2018-07-20 20:11:03', '2021-07-31 15:28:53', 1, NULL),
(79, 'Gabon', 'الجابون', 'GAB', 'GA', '241', 'Libreville', 'XAF', 'FCFA', '.ga', 'Gabon', 'Africa', 'Middle Africa', '[{\"zoneName\":\"Africa\\/Libreville\",\"gmtOffset\":3600,\"gmtOffsetName\":\"UTC+01:00\",\"abbreviation\":\"WAT\",\"tzName\":\"West Africa Time\"}]', '{\"br\":\"Gabão\",\"pt\":\"Gabão\",\"nl\":\"Gabon\",\"hr\":\"Gabon\",\"fa\":\"گابن\",\"de\":\"Gabun\",\"es\":\"Gabón\",\"fr\":\"Gabon\",\"ja\":\"ガボン\",\"it\":\"Gabon\"}', -1.00000000, 11.75000000, '??', 'U+1F1EC U+1F1E6', '2018-07-20 20:11:03', '2021-07-31 15:29:00', 1, 'Q1000'),
(80, 'Gambia The', 'غامبيا', 'GMB', 'GM', '220', 'Banjul', 'GMD', 'D', '.gm', 'Gambia', 'Africa', 'Western Africa', '[{\"zoneName\":\"Africa\\/Banjul\",\"gmtOffset\":0,\"gmtOffsetName\":\"UTC\\u00b100\",\"abbreviation\":\"GMT\",\"tzName\":\"Greenwich Mean Time\"}]', '{\"br\":\"Gâmbia\",\"pt\":\"Gâmbia\",\"nl\":\"Gambia\",\"hr\":\"Gambija\",\"fa\":\"گامبیا\",\"de\":\"Gambia\",\"es\":\"Gambia\",\"fr\":\"Gambie\",\"ja\":\"ガンビア\",\"it\":\"Gambia\"}', 13.46666666, -16.56666666, '??', 'U+1F1EC U+1F1F2', '2018-07-20 20:11:03', '2021-07-31 15:31:15', 1, 'Q1005'),
(81, 'Georgia', 'جورجيا', 'GEO', 'GE', '995', 'Tbilisi', 'GEL', 'ლ', '.ge', 'საქართველო', 'Asia', 'Western Asia', '[{\"zoneName\":\"Asia\\/Tbilisi\",\"gmtOffset\":14400,\"gmtOffsetName\":\"UTC+04:00\",\"abbreviation\":\"GET\",\"tzName\":\"Georgia Standard Time\"}]', '{\"br\":\"Geórgia\",\"pt\":\"Geórgia\",\"nl\":\"Georgië\",\"hr\":\"Gruzija\",\"fa\":\"گرجستان\",\"de\":\"Georgien\",\"es\":\"Georgia\",\"fr\":\"Géorgie\",\"ja\":\"グルジア\",\"it\":\"Georgia\"}', 42.00000000, 43.50000000, '??', 'U+1F1EC U+1F1EA', '2018-07-20 20:11:03', '2021-07-31 15:29:14', 1, 'Q230'),
(82, 'Germany', 'ألمانيا', 'DEU', 'DE', '49', 'Berlin', 'EUR', '€', '.de', 'Deutschland', 'Europe', 'Western Europe', '[{\"zoneName\":\"Europe\\/Berlin\",\"gmtOffset\":3600,\"gmtOffsetName\":\"UTC+01:00\",\"abbreviation\":\"CET\",\"tzName\":\"Central European Time\"},{\"zoneName\":\"Europe\\/Busingen\",\"gmtOffset\":3600,\"gmtOffsetName\":\"UTC+01:00\",\"abbreviation\":\"CET\",\"tzName\":\"Central European Time\"}]', '{\"br\":\"Alemanha\",\"pt\":\"Alemanha\",\"nl\":\"Duitsland\",\"hr\":\"Njemačka\",\"fa\":\"آلمان\",\"de\":\"Deutschland\",\"es\":\"Alemania\",\"fr\":\"Allemagne\",\"ja\":\"ドイツ\",\"it\":\"Germania\"}', 51.00000000, 9.00000000, '??', 'U+1F1E9 U+1F1EA', '2018-07-20 20:11:03', '2021-07-31 15:29:21', 1, 'Q183'),
(83, 'Ghana', 'غانا\r\n\r\n', 'GHA', 'GH', '233', 'Accra', 'GHS', 'GH₵', '.gh', 'Ghana', 'Africa', 'Western Africa', '[{\"zoneName\":\"Africa\\/Accra\",\"gmtOffset\":0,\"gmtOffsetName\":\"UTC\\u00b100\",\"abbreviation\":\"GMT\",\"tzName\":\"Greenwich Mean Time\"}]', '{\"br\":\"Gana\",\"pt\":\"Gana\",\"nl\":\"Ghana\",\"hr\":\"Gana\",\"fa\":\"غنا\",\"de\":\"Ghana\",\"es\":\"Ghana\",\"fr\":\"Ghana\",\"ja\":\"ガーナ\",\"it\":\"Ghana\"}', 8.00000000, -2.00000000, '??', 'U+1F1EC U+1F1ED', '2018-07-20 20:11:03', '2021-07-31 15:29:30', 1, 'Q117'),
(84, 'Gibraltar', 'جبل طارق\r\n', 'GIB', 'GI', '350', 'Gibraltar', 'GIP', '£', '.gi', 'Gibraltar', 'Europe', 'Southern Europe', '[{\"zoneName\":\"Europe\\/Gibraltar\",\"gmtOffset\":3600,\"gmtOffsetName\":\"UTC+01:00\",\"abbreviation\":\"CET\",\"tzName\":\"Central European Time\"}]', '{\"br\":\"Gibraltar\",\"pt\":\"Gibraltar\",\"nl\":\"Gibraltar\",\"hr\":\"Gibraltar\",\"fa\":\"جبل‌طارق\",\"de\":\"Gibraltar\",\"es\":\"Gibraltar\",\"fr\":\"Gibraltar\",\"ja\":\"ジブラルタル\",\"it\":\"Gibilterra\"}', 36.13333333, -5.35000000, '??', 'U+1F1EC U+1F1EE', '2018-07-20 20:11:03', '2021-07-31 15:29:37', 1, NULL),
(85, 'Greece', 'اليونان\r\n', 'GRC', 'GR', '30', 'Athens', 'EUR', '€', '.gr', 'Ελλάδα', 'Europe', 'Southern Europe', '[{\"zoneName\":\"Europe\\/Athens\",\"gmtOffset\":7200,\"gmtOffsetName\":\"UTC+02:00\",\"abbreviation\":\"EET\",\"tzName\":\"Eastern European Time\"}]', '{\"br\":\"Grécia\",\"pt\":\"Grécia\",\"nl\":\"Griekenland\",\"hr\":\"Grčka\",\"fa\":\"یونان\",\"de\":\"Griechenland\",\"es\":\"Grecia\",\"fr\":\"Grèce\",\"ja\":\"ギリシャ\",\"it\":\"Grecia\"}', 39.00000000, 22.00000000, '??', 'U+1F1EC U+1F1F7', '2018-07-20 20:11:03', '2021-07-31 15:29:48', 1, 'Q41'),
(86, 'Greenland', 'الأرض الخضراء\r\n', 'GRL', 'GL', '299', 'Nuuk', 'DKK', 'Kr.', '.gl', 'Kalaallit Nunaat', 'Americas', 'Northern America', '[{\"zoneName\":\"America\\/Danmarkshavn\",\"gmtOffset\":0,\"gmtOffsetName\":\"UTC\\u00b100\",\"abbreviation\":\"GMT\",\"tzName\":\"Greenwich Mean Time\"},{\"zoneName\":\"America\\/Nuuk\",\"gmtOffset\":-10800,\"gmtOffsetName\":\"UTC-03:00\",\"abbreviation\":\"WGT\",\"tzName\":\"West Greenland Time\"},{\"zoneName\":\"America\\/Scoresbysund\",\"gmtOffset\":-3600,\"gmtOffsetName\":\"UTC-01:00\",\"abbreviation\":\"EGT\",\"tzName\":\"Eastern Greenland Time\"},{\"zoneName\":\"America\\/Thule\",\"gmtOffset\":-14400,\"gmtOffsetName\":\"UTC-04:00\",\"abbreviation\":\"AST\",\"tzName\":\"Atlantic Standard Time\"}]', '{\"br\":\"Groelândia\",\"pt\":\"Gronelândia\",\"nl\":\"Groenland\",\"hr\":\"Grenland\",\"fa\":\"گرینلند\",\"de\":\"Grönland\",\"es\":\"Groenlandia\",\"fr\":\"Groenland\",\"ja\":\"グリーンランド\",\"it\":\"Groenlandia\"}', 72.00000000, -40.00000000, '??', 'U+1F1EC U+1F1F1', '2018-07-20 20:11:03', '2021-07-31 15:30:01', 1, NULL),
(87, 'Grenada', 'غرينادا\r\n', 'GRD', 'GD', '+1-473', 'St. George\'s', 'XCD', '$', '.gd', 'Grenada', 'Americas', 'Caribbean', '[{\"zoneName\":\"America\\/Grenada\",\"gmtOffset\":-14400,\"gmtOffsetName\":\"UTC-04:00\",\"abbreviation\":\"AST\",\"tzName\":\"Atlantic Standard Time\"}]', '{\"br\":\"Granada\",\"pt\":\"Granada\",\"nl\":\"Grenada\",\"hr\":\"Grenada\",\"fa\":\"گرنادا\",\"de\":\"Grenada\",\"es\":\"Grenada\",\"fr\":\"Grenade\",\"ja\":\"グレナダ\",\"it\":\"Grenada\"}', 12.11666666, -61.66666666, '??', 'U+1F1EC U+1F1E9', '2018-07-20 20:11:03', '2021-07-31 15:30:10', 1, 'Q769'),
(88, 'Guadeloupe', 'جوادلوب\r\n', 'GLP', 'GP', '590', 'Basse-Terre', 'EUR', '€', '.gp', 'Guadeloupe', 'Americas', 'Caribbean', '[{\"zoneName\":\"America\\/Guadeloupe\",\"gmtOffset\":-14400,\"gmtOffsetName\":\"UTC-04:00\",\"abbreviation\":\"AST\",\"tzName\":\"Atlantic Standard Time\"}]', '{\"br\":\"Guadalupe\",\"pt\":\"Guadalupe\",\"nl\":\"Guadeloupe\",\"hr\":\"Gvadalupa\",\"fa\":\"جزیره گوادلوپ\",\"de\":\"Guadeloupe\",\"es\":\"Guadalupe\",\"fr\":\"Guadeloupe\",\"ja\":\"グアドループ\",\"it\":\"Guadeloupa\"}', 16.25000000, -61.58333300, '??', 'U+1F1EC U+1F1F5', '2018-07-20 20:11:03', '2021-07-31 15:30:15', 1, NULL),
(89, 'Guam', 'غوام\r\n', 'GUM', 'GU', '+1-671', 'Hagatna', 'USD', '$', '.gu', 'Guam', 'Oceania', 'Micronesia', '[{\"zoneName\":\"Pacific\\/Guam\",\"gmtOffset\":36000,\"gmtOffsetName\":\"UTC+10:00\",\"abbreviation\":\"CHST\",\"tzName\":\"Chamorro Standard Time\"}]', '{\"br\":\"Guam\",\"pt\":\"Guame\",\"nl\":\"Guam\",\"hr\":\"Guam\",\"fa\":\"گوام\",\"de\":\"Guam\",\"es\":\"Guam\",\"fr\":\"Guam\",\"ja\":\"グアム\",\"it\":\"Guam\"}', 13.46666666, 144.78333333, '??', 'U+1F1EC U+1F1FA', '2018-07-20 20:11:03', '2021-07-31 15:30:21', 1, NULL),
(90, 'Guatemala', 'غواتيمالا\r\n', 'GTM', 'GT', '502', 'Guatemala City', 'GTQ', 'Q', '.gt', 'Guatemala', 'Americas', 'Central America', '[{\"zoneName\":\"America\\/Guatemala\",\"gmtOffset\":-21600,\"gmtOffsetName\":\"UTC-06:00\",\"abbreviation\":\"CST\",\"tzName\":\"Central Standard Time (North America\"}]', '{\"br\":\"Guatemala\",\"pt\":\"Guatemala\",\"nl\":\"Guatemala\",\"hr\":\"Gvatemala\",\"fa\":\"گواتمالا\",\"de\":\"Guatemala\",\"es\":\"Guatemala\",\"fr\":\"Guatemala\",\"ja\":\"グアテマラ\",\"it\":\"Guatemala\"}', 15.50000000, -90.25000000, '??', 'U+1F1EC U+1F1F9', '2018-07-20 20:11:03', '2021-07-31 15:30:28', 1, 'Q774'),
(91, 'Guernsey and Alderney', 'غيرنزي وألدرني\r\n', 'GGY', 'GG', '+44-1481', 'St Peter Port', 'GBP', '£', '.gg', 'Guernsey', 'Europe', 'Northern Europe', '[{\"zoneName\":\"Europe\\/Guernsey\",\"gmtOffset\":0,\"gmtOffsetName\":\"UTC\\u00b100\",\"abbreviation\":\"GMT\",\"tzName\":\"Greenwich Mean Time\"}]', '{\"br\":\"Guernsey\",\"pt\":\"Guernsey\",\"nl\":\"Guernsey\",\"hr\":\"Guernsey\",\"fa\":\"گرنزی\",\"de\":\"Guernsey\",\"es\":\"Guernsey\",\"fr\":\"Guernesey\",\"ja\":\"ガーンジー\",\"it\":\"Guernsey\"}', 49.46666666, -2.58333333, '??', 'U+1F1EC U+1F1EC', '2018-07-20 20:11:03', '2021-07-31 15:30:37', 1, NULL),
(92, 'Guinea', 'غينيا', 'GIN', 'GN', '224', 'Conakry', 'GNF', 'FG', '.gn', 'Guinée', 'Africa', 'Western Africa', '[{\"zoneName\":\"Africa\\/Conakry\",\"gmtOffset\":0,\"gmtOffsetName\":\"UTC\\u00b100\",\"abbreviation\":\"GMT\",\"tzName\":\"Greenwich Mean Time\"}]', '{\"br\":\"Guiné\",\"pt\":\"Guiné\",\"nl\":\"Guinee\",\"hr\":\"Gvineja\",\"fa\":\"گینه\",\"de\":\"Guinea\",\"es\":\"Guinea\",\"fr\":\"Guinée\",\"ja\":\"ギニア\",\"it\":\"Guinea\"}', 11.00000000, -10.00000000, '??', 'U+1F1EC U+1F1F3', '2018-07-20 20:11:03', '2021-07-31 15:31:27', 1, 'Q1006'),
(93, 'Guinea-Bissau', 'غينيا بيساو\r\n', 'GNB', 'GW', '245', 'Bissau', 'XOF', 'CFA', '.gw', 'Guiné-Bissau', 'Africa', 'Western Africa', '[{\"zoneName\":\"Africa\\/Bissau\",\"gmtOffset\":0,\"gmtOffsetName\":\"UTC\\u00b100\",\"abbreviation\":\"GMT\",\"tzName\":\"Greenwich Mean Time\"}]', '{\"br\":\"Guiné-Bissau\",\"pt\":\"Guiné-Bissau\",\"nl\":\"Guinee-Bissau\",\"hr\":\"Gvineja Bisau\",\"fa\":\"گینه بیسائو\",\"de\":\"Guinea-Bissau\",\"es\":\"Guinea-Bisáu\",\"fr\":\"Guinée-Bissau\",\"ja\":\"ギニアビサウ\",\"it\":\"Guinea-Bissau\"}', 12.00000000, -15.00000000, '??', 'U+1F1EC U+1F1FC', '2018-07-20 20:11:03', '2021-07-31 15:31:35', 1, 'Q1007'),
(94, 'Guyana', 'غيانا', 'GUY', 'GY', '592', 'Georgetown', 'GYD', '$', '.gy', 'Guyana', 'Americas', 'South America', '[{\"zoneName\":\"America\\/Guyana\",\"gmtOffset\":-14400,\"gmtOffsetName\":\"UTC-04:00\",\"abbreviation\":\"GYT\",\"tzName\":\"Guyana Time\"}]', '{\"br\":\"Guiana\",\"pt\":\"Guiana\",\"nl\":\"Guyana\",\"hr\":\"Gvajana\",\"fa\":\"گویان\",\"de\":\"Guyana\",\"es\":\"Guyana\",\"fr\":\"Guyane\",\"ja\":\"ガイアナ\",\"it\":\"Guyana\"}', 5.00000000, -59.00000000, '??', 'U+1F1EC U+1F1FE', '2018-07-20 20:11:03', '2021-07-31 15:31:47', 1, 'Q734'),
(95, 'Haiti', 'هايتي', 'HTI', 'HT', '509', 'Port-au-Prince', 'HTG', 'G', '.ht', 'Haïti', 'Americas', 'Caribbean', '[{\"zoneName\":\"America\\/Port-au-Prince\",\"gmtOffset\":-18000,\"gmtOffsetName\":\"UTC-05:00\",\"abbreviation\":\"EST\",\"tzName\":\"Eastern Standard Time (North America\"}]', '{\"br\":\"Haiti\",\"pt\":\"Haiti\",\"nl\":\"Haïti\",\"hr\":\"Haiti\",\"fa\":\"هائیتی\",\"de\":\"Haiti\",\"es\":\"Haiti\",\"fr\":\"Haïti\",\"ja\":\"ハイチ\",\"it\":\"Haiti\"}', 19.00000000, -72.41666666, '??', 'U+1F1ED U+1F1F9', '2018-07-20 20:11:03', '2021-07-31 15:31:53', 1, 'Q790'),
(96, 'Heard Island and McDonald Islands', 'قلب الجزيرة وجزر ماكدونالز\r\n', 'HMD', 'HM', '', '', 'AUD', '$', '.hm', 'Heard Island and McDonald Islands', '', '', '[{\"zoneName\":\"Indian\\/Kerguelen\",\"gmtOffset\":18000,\"gmtOffsetName\":\"UTC+05:00\",\"abbreviation\":\"TFT\",\"tzName\":\"French Southern and Antarctic Time\"}]', '{\"br\":\"Ilha Heard e Ilhas McDonald\",\"pt\":\"Ilha Heard e Ilhas McDonald\",\"nl\":\"Heard- en McDonaldeilanden\",\"hr\":\"Otok Heard i otočje McDonald\",\"fa\":\"جزیره هرد و جزایر مک‌دونالد\",\"de\":\"Heard und die McDonaldinseln\",\"es\":\"Islas Heard y McDonald\",\"fr\":\"Îles Heard-et-MacDonald\",\"ja\":\"ハード島とマクドナルド諸島\",\"it\":\"Isole Heard e McDonald\"}', -53.10000000, 72.51666666, '??', 'U+1F1ED U+1F1F2', '2018-07-20 20:11:03', '2021-07-31 15:32:01', 1, NULL),
(97, 'Honduras', 'هندوراس', 'HND', 'HN', '504', 'Tegucigalpa', 'HNL', 'L', '.hn', 'Honduras', 'Americas', 'Central America', '[{\"zoneName\":\"America\\/Tegucigalpa\",\"gmtOffset\":-21600,\"gmtOffsetName\":\"UTC-06:00\",\"abbreviation\":\"CST\",\"tzName\":\"Central Standard Time (North America\"}]', '{\"br\":\"Honduras\",\"pt\":\"Honduras\",\"nl\":\"Honduras\",\"hr\":\"Honduras\",\"fa\":\"هندوراس\",\"de\":\"Honduras\",\"es\":\"Honduras\",\"fr\":\"Honduras\",\"ja\":\"ホンジュラス\",\"it\":\"Honduras\"}', 15.00000000, -86.50000000, '??', 'U+1F1ED U+1F1F3', '2018-07-20 20:11:03', '2021-07-31 15:32:06', 1, 'Q783'),
(98, 'Hong Kong S.A.R.', 'هونج كونج ', 'HKG', 'HK', '852', 'Hong Kong', 'HKD', '$', '.hk', '香港', 'Asia', 'Eastern Asia', '[{\"zoneName\":\"Asia\\/Hong_Kong\",\"gmtOffset\":28800,\"gmtOffsetName\":\"UTC+08:00\",\"abbreviation\":\"HKT\",\"tzName\":\"Hong Kong Time\"}]', '{\"br\":\"Hong Kong\",\"pt\":\"Hong Kong\",\"nl\":\"Hongkong\",\"hr\":\"Hong Kong\",\"fa\":\"هنگ‌کنگ\",\"de\":\"Hong Kong\",\"es\":\"Hong Kong\",\"fr\":\"Hong Kong\",\"ja\":\"香港\",\"it\":\"Hong Kong\"}', 22.25000000, 114.16666666, '??', 'U+1F1ED U+1F1F0', '2018-07-20 20:11:03', '2021-07-31 15:32:33', 1, NULL),
(99, 'Hungary', 'هنغاريا', 'HUN', 'HU', '36', 'Budapest', 'HUF', 'Ft', '.hu', 'Magyarország', 'Europe', 'Eastern Europe', '[{\"zoneName\":\"Europe\\/Budapest\",\"gmtOffset\":3600,\"gmtOffsetName\":\"UTC+01:00\",\"abbreviation\":\"CET\",\"tzName\":\"Central European Time\"}]', '{\"br\":\"Hungria\",\"pt\":\"Hungria\",\"nl\":\"Hongarije\",\"hr\":\"Mađarska\",\"fa\":\"مجارستان\",\"de\":\"Ungarn\",\"es\":\"Hungría\",\"fr\":\"Hongrie\",\"ja\":\"ハンガリー\",\"it\":\"Ungheria\"}', 47.00000000, 20.00000000, '??', 'U+1F1ED U+1F1FA', '2018-07-20 20:11:03', '2021-07-31 15:32:42', 1, 'Q28'),
(100, 'Iceland', 'أيسلندا\r\n', 'ISL', 'IS', '354', 'Reykjavik', 'ISK', 'kr', '.is', 'Ísland', 'Europe', 'Northern Europe', '[{\"zoneName\":\"Atlantic\\/Reykjavik\",\"gmtOffset\":0,\"gmtOffsetName\":\"UTC\\u00b100\",\"abbreviation\":\"GMT\",\"tzName\":\"Greenwich Mean Time\"}]', '{\"br\":\"Islândia\",\"pt\":\"Islândia\",\"nl\":\"IJsland\",\"hr\":\"Island\",\"fa\":\"ایسلند\",\"de\":\"Island\",\"es\":\"Islandia\",\"fr\":\"Islande\",\"ja\":\"アイスランド\",\"it\":\"Islanda\"}', 65.00000000, -18.00000000, '??', 'U+1F1EE U+1F1F8', '2018-07-20 20:11:03', '2021-07-31 15:32:49', 1, 'Q189'),
(101, 'India', 'الهند\r\n', 'IND', 'IN', '91', 'New Delhi', 'INR', '₹', '.in', 'भारत', 'Asia', 'Southern Asia', '[{\"zoneName\":\"Asia\\/Kolkata\",\"gmtOffset\":19800,\"gmtOffsetName\":\"UTC+05:30\",\"abbreviation\":\"IST\",\"tzName\":\"Indian Standard Time\"}]', '{\"br\":\"Índia\",\"pt\":\"Índia\",\"nl\":\"India\",\"hr\":\"Indija\",\"fa\":\"هند\",\"de\":\"Indien\",\"es\":\"India\",\"fr\":\"Inde\",\"ja\":\"インド\",\"it\":\"India\"}', 20.00000000, 77.00000000, '??', 'U+1F1EE U+1F1F3', '2018-07-20 20:11:03', '2021-07-31 15:33:03', 1, 'Q668'),
(102, 'Indonesia', 'إندونيسيا\r\n', 'IDN', 'ID', '62', 'Jakarta', 'IDR', 'Rp', '.id', 'Indonesia', 'Asia', 'South-Eastern Asia', '[{\"zoneName\":\"Asia\\/Jakarta\",\"gmtOffset\":25200,\"gmtOffsetName\":\"UTC+07:00\",\"abbreviation\":\"WIB\",\"tzName\":\"Western Indonesian Time\"},{\"zoneName\":\"Asia\\/Jayapura\",\"gmtOffset\":32400,\"gmtOffsetName\":\"UTC+09:00\",\"abbreviation\":\"WIT\",\"tzName\":\"Eastern Indonesian Time\"},{\"zoneName\":\"Asia\\/Makassar\",\"gmtOffset\":28800,\"gmtOffsetName\":\"UTC+08:00\",\"abbreviation\":\"WITA\",\"tzName\":\"Central Indonesia Time\"},{\"zoneName\":\"Asia\\/Pontianak\",\"gmtOffset\":25200,\"gmtOffsetName\":\"UTC+07:00\",\"abbreviation\":\"WIB\",\"tzName\":\"Western Indonesian Time\"}]', '{\"br\":\"Indonésia\",\"pt\":\"Indonésia\",\"nl\":\"Indonesië\",\"hr\":\"Indonezija\",\"fa\":\"اندونزی\",\"de\":\"Indonesien\",\"es\":\"Indonesia\",\"fr\":\"Indonésie\",\"ja\":\"インドネシア\",\"it\":\"Indonesia\"}', -5.00000000, 120.00000000, '??', 'U+1F1EE U+1F1E9', '2018-07-20 20:11:03', '2021-07-31 15:33:26', 1, 'Q252'),
(103, 'Iran', 'إيران', 'IRN', 'IR', '98', 'Tehran', 'IRR', '﷼', '.ir', 'ایران', 'Asia', 'Southern Asia', '[{\"zoneName\":\"Asia\\/Tehran\",\"gmtOffset\":12600,\"gmtOffsetName\":\"UTC+03:30\",\"abbreviation\":\"IRDT\",\"tzName\":\"Iran Daylight Time\"}]', '{\"br\":\"Irã\",\"pt\":\"Irão\",\"nl\":\"Iran\",\"hr\":\"Iran\",\"fa\":\"ایران\",\"de\":\"Iran\",\"es\":\"Iran\",\"fr\":\"Iran\",\"ja\":\"イラン・イスラム共和国\"}', 32.00000000, 53.00000000, '??', 'U+1F1EE U+1F1F7', '2018-07-20 20:11:03', '2021-07-31 15:33:40', 1, 'Q794'),
(104, 'Iraq', 'العراق', 'IRQ', 'IQ', '964', 'Baghdad', 'IQD', 'د.ع', '.iq', 'العراق', 'Asia', 'Western Asia', '[{\"zoneName\":\"Asia\\/Baghdad\",\"gmtOffset\":10800,\"gmtOffsetName\":\"UTC+03:00\",\"abbreviation\":\"AST\",\"tzName\":\"Arabia Standard Time\"}]', '{\"br\":\"Iraque\",\"pt\":\"Iraque\",\"nl\":\"Irak\",\"hr\":\"Irak\",\"fa\":\"عراق\",\"de\":\"Irak\",\"es\":\"Irak\",\"fr\":\"Irak\",\"ja\":\"イラク\",\"it\":\"Iraq\"}', 33.00000000, 44.00000000, '??', 'U+1F1EE U+1F1F6', '2018-07-20 20:11:03', '2021-07-31 15:33:49', 1, 'Q796'),
(105, 'Ireland', 'أيرلندا', 'IRL', 'IE', '353', 'Dublin', 'EUR', '€', '.ie', 'Éire', 'Europe', 'Northern Europe', '[{\"zoneName\":\"Europe\\/Dublin\",\"gmtOffset\":0,\"gmtOffsetName\":\"UTC\\u00b100\",\"abbreviation\":\"GMT\",\"tzName\":\"Greenwich Mean Time\"}]', '{\"br\":\"Irlanda\",\"pt\":\"Irlanda\",\"nl\":\"Ierland\",\"hr\":\"Irska\",\"fa\":\"ایرلند\",\"de\":\"Irland\",\"es\":\"Irlanda\",\"fr\":\"Irlande\",\"ja\":\"アイルランド\",\"it\":\"Irlanda\"}', 53.00000000, -8.00000000, '??', 'U+1F1EE U+1F1EA', '2018-07-20 20:11:03', '2021-07-31 15:33:55', 1, 'Q27'),
(106, 'Israel', 'إسرائيل\r\n', 'ISR', 'IL', '972', 'Jerusalem', 'ILS', '₪', '.il', 'יִשְׂרָאֵל', 'Asia', 'Western Asia', '[{\"zoneName\":\"Asia\\/Jerusalem\",\"gmtOffset\":7200,\"gmtOffsetName\":\"UTC+02:00\",\"abbreviation\":\"IST\",\"tzName\":\"Israel Standard Time\"}]', '{\"br\":\"Israel\",\"pt\":\"Israel\",\"nl\":\"Israël\",\"hr\":\"Izrael\",\"fa\":\"اسرائیل\",\"de\":\"Israel\",\"es\":\"Israel\",\"fr\":\"Israël\",\"ja\":\"イスラエル\",\"it\":\"Israele\"}', 31.50000000, 34.75000000, '??', 'U+1F1EE U+1F1F1', '2018-07-20 20:11:03', '2021-07-31 15:34:07', 1, 'Q801'),
(107, 'Italy', 'إيطاليا\r\n', 'ITA', 'IT', '39', 'Rome', 'EUR', '€', '.it', 'Italia', 'Europe', 'Southern Europe', '[{\"zoneName\":\"Europe\\/Rome\",\"gmtOffset\":3600,\"gmtOffsetName\":\"UTC+01:00\",\"abbreviation\":\"CET\",\"tzName\":\"Central European Time\"}]', '{\"br\":\"Itália\",\"pt\":\"Itália\",\"nl\":\"Italië\",\"hr\":\"Italija\",\"fa\":\"ایتالیا\",\"de\":\"Italien\",\"es\":\"Italia\",\"fr\":\"Italie\",\"ja\":\"イタリア\",\"it\":\"Italia\"}', 42.83333333, 12.83333333, '??', 'U+1F1EE U+1F1F9', '2018-07-20 20:11:03', '2021-07-31 15:34:13', 1, 'Q38'),
(108, 'Jamaica', 'جامايكا\r\n', 'JAM', 'JM', '+1-876', 'Kingston', 'JMD', 'J$', '.jm', 'Jamaica', 'Americas', 'Caribbean', '[{\"zoneName\":\"America\\/Jamaica\",\"gmtOffset\":-18000,\"gmtOffsetName\":\"UTC-05:00\",\"abbreviation\":\"EST\",\"tzName\":\"Eastern Standard Time (North America\"}]', '{\"br\":\"Jamaica\",\"pt\":\"Jamaica\",\"nl\":\"Jamaica\",\"hr\":\"Jamajka\",\"fa\":\"جامائیکا\",\"de\":\"Jamaika\",\"es\":\"Jamaica\",\"fr\":\"Jamaïque\",\"ja\":\"ジャマイカ\",\"it\":\"Giamaica\"}', 18.25000000, -77.50000000, '??', 'U+1F1EF U+1F1F2', '2018-07-20 20:11:03', '2021-07-31 15:34:18', 1, 'Q766'),
(109, 'Japan', 'اليابان\r\n', 'JPN', 'JP', '81', 'Tokyo', 'JPY', '¥', '.jp', '日本', 'Asia', 'Eastern Asia', '[{\"zoneName\":\"Asia\\/Tokyo\",\"gmtOffset\":32400,\"gmtOffsetName\":\"UTC+09:00\",\"abbreviation\":\"JST\",\"tzName\":\"Japan Standard Time\"}]', '{\"br\":\"Japão\",\"pt\":\"Japão\",\"nl\":\"Japan\",\"hr\":\"Japan\",\"fa\":\"ژاپن\",\"de\":\"Japan\",\"es\":\"Japón\",\"fr\":\"Japon\",\"ja\":\"日本\",\"it\":\"Giappone\"}', 36.00000000, 138.00000000, '??', 'U+1F1EF U+1F1F5', '2018-07-20 20:11:03', '2021-07-31 15:34:33', 1, 'Q17'),
(110, 'Jersey', 'جيرسي', 'JEY', 'JE', '+44-1534', 'Saint Helier', 'GBP', '£', '.je', 'Jersey', 'Europe', 'Northern Europe', '[{\"zoneName\":\"Europe\\/Jersey\",\"gmtOffset\":0,\"gmtOffsetName\":\"UTC\\u00b100\",\"abbreviation\":\"GMT\",\"tzName\":\"Greenwich Mean Time\"}]', '{\"br\":\"Jersey\",\"pt\":\"Jersey\",\"nl\":\"Jersey\",\"hr\":\"Jersey\",\"fa\":\"جرزی\",\"de\":\"Jersey\",\"es\":\"Jersey\",\"fr\":\"Jersey\",\"ja\":\"ジャージー\",\"it\":\"Isola di Jersey\"}', 49.25000000, -2.16666666, '??', 'U+1F1EF U+1F1EA', '2018-07-20 20:11:03', '2021-07-31 15:34:44', 1, 'Q785'),
(111, 'Jordan', 'الأردن\r\n', 'JOR', 'JO', '962', 'Amman', 'JOD', 'ا.د', '.jo', 'الأردن', 'Asia', 'Western Asia', '[{\"zoneName\":\"Asia\\/Amman\",\"gmtOffset\":7200,\"gmtOffsetName\":\"UTC+02:00\",\"abbreviation\":\"EET\",\"tzName\":\"Eastern European Time\"}]', '{\"br\":\"Jordânia\",\"pt\":\"Jordânia\",\"nl\":\"Jordanië\",\"hr\":\"Jordan\",\"fa\":\"اردن\",\"de\":\"Jordanien\",\"es\":\"Jordania\",\"fr\":\"Jordanie\",\"ja\":\"ヨルダン\",\"it\":\"Giordania\"}', 31.00000000, 36.00000000, '??', 'U+1F1EF U+1F1F4', '2018-07-20 20:11:03', '2021-07-31 15:34:53', 1, 'Q810'),
(112, 'Kazakhstan', 'كازاخستان\r\n', 'KAZ', 'KZ', '7', 'Astana', 'KZT', 'лв', '.kz', 'Қазақстан', 'Asia', 'Central Asia', '[{\"zoneName\":\"Asia\\/Almaty\",\"gmtOffset\":21600,\"gmtOffsetName\":\"UTC+06:00\",\"abbreviation\":\"ALMT\",\"tzName\":\"Alma-Ata Time[1\"},{\"zoneName\":\"Asia\\/Aqtau\",\"gmtOffset\":18000,\"gmtOffsetName\":\"UTC+05:00\",\"abbreviation\":\"AQTT\",\"tzName\":\"Aqtobe Time\"},{\"zoneName\":\"Asia\\/Aqtobe\",\"gmtOffset\":18000,\"gmtOffsetName\":\"UTC+05:00\",\"abbreviation\":\"AQTT\",\"tzName\":\"Aqtobe Time\"},{\"zoneName\":\"Asia\\/Atyrau\",\"gmtOffset\":18000,\"gmtOffsetName\":\"UTC+05:00\",\"abbreviation\":\"MSD+1\",\"tzName\":\"Moscow Daylight Time+1\"},{\"zoneName\":\"Asia\\/Oral\",\"gmtOffset\":18000,\"gmtOffsetName\":\"UTC+05:00\",\"abbreviation\":\"ORAT\",\"tzName\":\"Oral Time\"},{\"zoneName\":\"Asia\\/Qostanay\",\"gmtOffset\":21600,\"gmtOffsetName\":\"UTC+06:00\",\"abbreviation\":\"QYZST\",\"tzName\":\"Qyzylorda Summer Time\"},{\"zoneName\":\"Asia\\/Qyzylorda\",\"gmtOffset\":18000,\"gmtOffsetName\":\"UTC+05:00\",\"abbreviation\":\"QYZT\",\"tzName\":\"Qyzylorda Summer Time\"}]', '{\"br\":\"Cazaquistão\",\"pt\":\"Cazaquistão\",\"nl\":\"Kazachstan\",\"hr\":\"Kazahstan\",\"fa\":\"قزاقستان\",\"de\":\"Kasachstan\",\"es\":\"Kazajistán\",\"fr\":\"Kazakhstan\",\"ja\":\"カザフスタン\",\"it\":\"Kazakistan\"}', 48.00000000, 68.00000000, '??', 'U+1F1F0 U+1F1FF', '2018-07-20 20:11:03', '2021-07-31 15:34:59', 1, 'Q232'),
(113, 'Kenya', 'كينيا', 'KEN', 'KE', '254', 'Nairobi', 'KES', 'KSh', '.ke', 'Kenya', 'Africa', 'Eastern Africa', '[{\"zoneName\":\"Africa\\/Nairobi\",\"gmtOffset\":10800,\"gmtOffsetName\":\"UTC+03:00\",\"abbreviation\":\"EAT\",\"tzName\":\"East Africa Time\"}]', '{\"br\":\"Quênia\",\"pt\":\"Quénia\",\"nl\":\"Kenia\",\"hr\":\"Kenija\",\"fa\":\"کنیا\",\"de\":\"Kenia\",\"es\":\"Kenia\",\"fr\":\"Kenya\",\"ja\":\"ケニア\",\"it\":\"Kenya\"}', 1.00000000, 38.00000000, '??', 'U+1F1F0 U+1F1EA', '2018-07-20 20:11:03', '2021-07-31 15:35:08', 1, 'Q114'),
(114, 'Kiribati', 'كيريباتي', 'KIR', 'KI', '686', 'Tarawa', 'AUD', '$', '.ki', 'Kiribati', 'Oceania', 'Micronesia', '[{\"zoneName\":\"Pacific\\/Enderbury\",\"gmtOffset\":46800,\"gmtOffsetName\":\"UTC+13:00\",\"abbreviation\":\"PHOT\",\"tzName\":\"Phoenix Island Time\"},{\"zoneName\":\"Pacific\\/Kiritimati\",\"gmtOffset\":50400,\"gmtOffsetName\":\"UTC+14:00\",\"abbreviation\":\"LINT\",\"tzName\":\"Line Islands Time\"},{\"zoneName\":\"Pacific\\/Tarawa\",\"gmtOffset\":43200,\"gmtOffsetName\":\"UTC+12:00\",\"abbreviation\":\"GILT\",\"tzName\":\"Gilbert Island Time\"}]', '{\"br\":\"Kiribati\",\"pt\":\"Quiribáti\",\"nl\":\"Kiribati\",\"hr\":\"Kiribati\",\"fa\":\"کیریباتی\",\"de\":\"Kiribati\",\"es\":\"Kiribati\",\"fr\":\"Kiribati\",\"ja\":\"キリバス\",\"it\":\"Kiribati\"}', 1.41666666, 173.00000000, '??', 'U+1F1F0 U+1F1EE', '2018-07-20 20:11:03', '2021-07-31 15:35:14', 1, 'Q710'),
(115, 'Korea North', 'كوريا الشمالية\r\n', 'PRK', 'KP', '850', 'Pyongyang', 'KPW', '₩', '.kp', '북한', 'Asia', 'Eastern Asia', '[{\"zoneName\":\"Asia\\/Pyongyang\",\"gmtOffset\":32400,\"gmtOffsetName\":\"UTC+09:00\",\"abbreviation\":\"KST\",\"tzName\":\"Korea Standard Time\"}]', '{\"br\":\"Coreia do Norte\",\"pt\":\"Coreia do Norte\",\"nl\":\"Noord-Korea\",\"hr\":\"Sjeverna Koreja\",\"fa\":\"کره جنوبی\",\"de\":\"Nordkorea\",\"es\":\"Corea del Norte\",\"fr\":\"Corée du Nord\",\"ja\":\"朝鮮民主主義人民共和国\",\"it\":\"Corea del Nord\"}', 40.00000000, 127.00000000, '??', 'U+1F1F0 U+1F1F5', '2018-07-20 20:11:03', '2021-07-31 15:35:21', 1, 'Q423'),
(116, 'Korea South', 'كوريا، جنوب\r\n', 'KOR', 'KR', '82', 'Seoul', 'KRW', '₩', '.kr', '대한민국', 'Asia', 'Eastern Asia', '[{\"zoneName\":\"Asia\\/Seoul\",\"gmtOffset\":32400,\"gmtOffsetName\":\"UTC+09:00\",\"abbreviation\":\"KST\",\"tzName\":\"Korea Standard Time\"}]', '{\"br\":\"Coreia do Sul\",\"pt\":\"Coreia do Sul\",\"nl\":\"Zuid-Korea\",\"hr\":\"Južna Koreja\",\"fa\":\"کره شمالی\",\"de\":\"Südkorea\",\"es\":\"Corea del Sur\",\"fr\":\"Corée du Sud\",\"ja\":\"大韓民国\",\"it\":\"Corea del Sud\"}', 37.00000000, 127.50000000, '??', 'U+1F1F0 U+1F1F7', '2018-07-20 20:11:03', '2021-07-31 15:35:25', 1, 'Q884'),
(117, 'Kuwait', 'الكويت\r\n', 'KWT', 'KW', '965', 'Kuwait City', 'KWD', 'ك.د', '.kw', 'الكويت', 'Asia', 'Western Asia', '[{\"zoneName\":\"Asia\\/Kuwait\",\"gmtOffset\":10800,\"gmtOffsetName\":\"UTC+03:00\",\"abbreviation\":\"AST\",\"tzName\":\"Arabia Standard Time\"}]', '{\"br\":\"Kuwait\",\"pt\":\"Kuwait\",\"nl\":\"Koeweit\",\"hr\":\"Kuvajt\",\"fa\":\"کویت\",\"de\":\"Kuwait\",\"es\":\"Kuwait\",\"fr\":\"Koweït\",\"ja\":\"クウェート\",\"it\":\"Kuwait\"}', 29.50000000, 45.75000000, '??', 'U+1F1F0 U+1F1FC', '2018-07-20 20:11:03', '2021-07-31 15:35:30', 1, 'Q817'),
(118, 'Kyrgyzstan', 'قيرغيزستان\r\n\r\n', 'KGZ', 'KG', '996', 'Bishkek', 'KGS', 'лв', '.kg', 'Кыргызстан', 'Asia', 'Central Asia', '[{\"zoneName\":\"Asia\\/Bishkek\",\"gmtOffset\":21600,\"gmtOffsetName\":\"UTC+06:00\",\"abbreviation\":\"KGT\",\"tzName\":\"Kyrgyzstan Time\"}]', '{\"br\":\"Quirguistão\",\"pt\":\"Quirguizistão\",\"nl\":\"Kirgizië\",\"hr\":\"Kirgistan\",\"fa\":\"قرقیزستان\",\"de\":\"Kirgisistan\",\"es\":\"Kirguizistán\",\"fr\":\"Kirghizistan\",\"ja\":\"キルギス\",\"it\":\"Kirghizistan\"}', 41.00000000, 75.00000000, '??', 'U+1F1F0 U+1F1EC', '2018-07-20 20:11:03', '2021-07-31 15:35:39', 1, 'Q813'),
(119, 'Laos', 'لاوس', 'LAO', 'LA', '856', 'Vientiane', 'LAK', '₭', '.la', 'ສປປລາວ', 'Asia', 'South-Eastern Asia', '[{\"zoneName\":\"Asia\\/Vientiane\",\"gmtOffset\":25200,\"gmtOffsetName\":\"UTC+07:00\",\"abbreviation\":\"ICT\",\"tzName\":\"Indochina Time\"}]', '{\"br\":\"Laos\",\"pt\":\"Laos\",\"nl\":\"Laos\",\"hr\":\"Laos\",\"fa\":\"لائوس\",\"de\":\"Laos\",\"es\":\"Laos\",\"fr\":\"Laos\",\"ja\":\"ラオス人民民主共和国\",\"it\":\"Laos\"}', 18.00000000, 105.00000000, '??', 'U+1F1F1 U+1F1E6', '2018-07-20 20:11:03', '2021-07-31 15:35:49', 1, 'Q819'),
(120, 'Latvia', 'لاتفيا', 'LVA', 'LV', '371', 'Riga', 'EUR', '€', '.lv', 'Latvija', 'Europe', 'Northern Europe', '[{\"zoneName\":\"Europe\\/Riga\",\"gmtOffset\":7200,\"gmtOffsetName\":\"UTC+02:00\",\"abbreviation\":\"EET\",\"tzName\":\"Eastern European Time\"}]', '{\"br\":\"Letônia\",\"pt\":\"Letónia\",\"nl\":\"Letland\",\"hr\":\"Latvija\",\"fa\":\"لتونی\",\"de\":\"Lettland\",\"es\":\"Letonia\",\"fr\":\"Lettonie\",\"ja\":\"ラトビア\",\"it\":\"Lettonia\"}', 57.00000000, 25.00000000, '??', 'U+1F1F1 U+1F1FB', '2018-07-20 20:11:03', '2021-07-31 15:35:54', 1, 'Q211'),
(121, 'Lebanon', 'لبنان', 'LBN', 'LB', '961', 'Beirut', 'LBP', '£', '.lb', 'لبنان', 'Asia', 'Western Asia', '[{\"zoneName\":\"Asia\\/Beirut\",\"gmtOffset\":7200,\"gmtOffsetName\":\"UTC+02:00\",\"abbreviation\":\"EET\",\"tzName\":\"Eastern European Time\"}]', '{\"br\":\"Líbano\",\"pt\":\"Líbano\",\"nl\":\"Libanon\",\"hr\":\"Libanon\",\"fa\":\"لبنان\",\"de\":\"Libanon\",\"es\":\"Líbano\",\"fr\":\"Liban\",\"ja\":\"レバノン\",\"it\":\"Libano\"}', 33.83333333, 35.83333333, '??', 'U+1F1F1 U+1F1E7', '2018-07-20 20:11:03', '2021-07-31 15:36:02', 1, 'Q822'),
(122, 'Lesotho', 'ليسوتو', 'LSO', 'LS', '266', 'Maseru', 'LSL', 'L', '.ls', 'Lesotho', 'Africa', 'Southern Africa', '[{\"zoneName\":\"Africa\\/Maseru\",\"gmtOffset\":7200,\"gmtOffsetName\":\"UTC+02:00\",\"abbreviation\":\"SAST\",\"tzName\":\"South African Standard Time\"}]', '{\"br\":\"Lesoto\",\"pt\":\"Lesoto\",\"nl\":\"Lesotho\",\"hr\":\"Lesoto\",\"fa\":\"لسوتو\",\"de\":\"Lesotho\",\"es\":\"Lesotho\",\"fr\":\"Lesotho\",\"ja\":\"レソト\",\"it\":\"Lesotho\"}', -29.50000000, 28.50000000, '??', 'U+1F1F1 U+1F1F8', '2018-07-20 20:11:03', '2021-07-31 15:36:08', 1, 'Q1013'),
(123, 'Liberia', 'ليبيريا', 'LBR', 'LR', '231', 'Monrovia', 'LRD', '$', '.lr', 'Liberia', 'Africa', 'Western Africa', '[{\"zoneName\":\"Africa\\/Monrovia\",\"gmtOffset\":0,\"gmtOffsetName\":\"UTC\\u00b100\",\"abbreviation\":\"GMT\",\"tzName\":\"Greenwich Mean Time\"}]', '{\"br\":\"Libéria\",\"pt\":\"Libéria\",\"nl\":\"Liberia\",\"hr\":\"Liberija\",\"fa\":\"لیبریا\",\"de\":\"Liberia\",\"es\":\"Liberia\",\"fr\":\"Liberia\",\"ja\":\"リベリア\",\"it\":\"Liberia\"}', 6.50000000, -9.50000000, '??', 'U+1F1F1 U+1F1F7', '2018-07-20 20:11:03', '2021-07-31 15:36:15', 1, 'Q1014'),
(124, 'Libya', 'ليبيا\r\n', 'LBY', 'LY', '218', 'Tripolis', 'LYD', 'د.ل', '.ly', '‏ليبيا', 'Africa', 'Northern Africa', '[{\"zoneName\":\"Africa\\/Tripoli\",\"gmtOffset\":7200,\"gmtOffsetName\":\"UTC+02:00\",\"abbreviation\":\"EET\",\"tzName\":\"Eastern European Time\"}]', '{\"br\":\"Líbia\",\"pt\":\"Líbia\",\"nl\":\"Libië\",\"hr\":\"Libija\",\"fa\":\"لیبی\",\"de\":\"Libyen\",\"es\":\"Libia\",\"fr\":\"Libye\",\"ja\":\"リビア\",\"it\":\"Libia\"}', 25.00000000, 17.00000000, '??', 'U+1F1F1 U+1F1FE', '2018-07-20 20:11:03', '2021-07-31 15:36:28', 1, 'Q1016'),
(125, 'Liechtenstein', 'ليختنشتاين', 'LIE', 'LI', '423', 'Vaduz', 'CHF', 'CHf', '.li', 'Liechtenstein', 'Europe', 'Western Europe', '[{\"zoneName\":\"Europe\\/Vaduz\",\"gmtOffset\":3600,\"gmtOffsetName\":\"UTC+01:00\",\"abbreviation\":\"CET\",\"tzName\":\"Central European Time\"}]', '{\"br\":\"Liechtenstein\",\"pt\":\"Listenstaine\",\"nl\":\"Liechtenstein\",\"hr\":\"Lihtenštajn\",\"fa\":\"لیختن‌اشتاین\",\"de\":\"Liechtenstein\",\"es\":\"Liechtenstein\",\"fr\":\"Liechtenstein\",\"ja\":\"リヒテンシュタイン\",\"it\":\"Liechtenstein\"}', 47.26666666, 9.53333333, '??', 'U+1F1F1 U+1F1EE', '2018-07-20 20:11:03', '2021-07-31 15:36:35', 1, 'Q347'),
(126, 'Lithuania', 'ليتوانيا\r\n', 'LTU', 'LT', '370', 'Vilnius', 'EUR', '€', '.lt', 'Lietuva', 'Europe', 'Northern Europe', '[{\"zoneName\":\"Europe\\/Vilnius\",\"gmtOffset\":7200,\"gmtOffsetName\":\"UTC+02:00\",\"abbreviation\":\"EET\",\"tzName\":\"Eastern European Time\"}]', '{\"br\":\"Lituânia\",\"pt\":\"Lituânia\",\"nl\":\"Litouwen\",\"hr\":\"Litva\",\"fa\":\"لیتوانی\",\"de\":\"Litauen\",\"es\":\"Lituania\",\"fr\":\"Lituanie\",\"ja\":\"リトアニア\",\"it\":\"Lituania\"}', 56.00000000, 24.00000000, '??', 'U+1F1F1 U+1F1F9', '2018-07-20 20:11:03', '2021-07-31 15:36:57', 1, 'Q37'),
(127, 'Luxembourg', 'لوكسمبورغ\r\n', 'LUX', 'LU', '352', 'Luxembourg', 'EUR', '€', '.lu', 'Luxembourg', 'Europe', 'Western Europe', '[{\"zoneName\":\"Europe\\/Luxembourg\",\"gmtOffset\":3600,\"gmtOffsetName\":\"UTC+01:00\",\"abbreviation\":\"CET\",\"tzName\":\"Central European Time\"}]', '{\"br\":\"Luxemburgo\",\"pt\":\"Luxemburgo\",\"nl\":\"Luxemburg\",\"hr\":\"Luksemburg\",\"fa\":\"لوکزامبورگ\",\"de\":\"Luxemburg\",\"es\":\"Luxemburgo\",\"fr\":\"Luxembourg\",\"ja\":\"ルクセンブルク\",\"it\":\"Lussemburgo\"}', 49.75000000, 6.16666666, '??', 'U+1F1F1 U+1F1FA', '2018-07-20 20:11:03', '2021-07-31 15:37:02', 1, 'Q32'),
(128, 'Macau S.A.R.', 'ماكاو', 'MAC', 'MO', '853', 'Macao', 'MOP', '$', '.mo', '澳門', 'Asia', 'Eastern Asia', '[{\"zoneName\":\"Asia\\/Macau\",\"gmtOffset\":28800,\"gmtOffsetName\":\"UTC+08:00\",\"abbreviation\":\"CST\",\"tzName\":\"China Standard Time\"}]', '{\"br\":\"Macau\",\"pt\":\"Macau\",\"nl\":\"Macao\",\"hr\":\"Makao\",\"fa\":\"مکائو\",\"de\":\"Macao\",\"es\":\"Macao\",\"fr\":\"Macao\",\"ja\":\"マカオ\",\"it\":\"Macao\"}', 22.16666666, 113.55000000, '??', 'U+1F1F2 U+1F1F4', '2018-07-20 20:11:03', '2021-07-31 15:37:09', 1, NULL),
(129, 'Macedonia', 'مقدونيا\r\n', 'MKD', 'MK', '389', 'Skopje', 'MKD', 'ден', '.mk', 'Северна Македонија', 'Europe', 'Southern Europe', '[{\"zoneName\":\"Europe\\/Skopje\",\"gmtOffset\":3600,\"gmtOffsetName\":\"UTC+01:00\",\"abbreviation\":\"CET\",\"tzName\":\"Central European Time\"}]', '{\"br\":\"Macedônia\",\"pt\":\"Macedónia\",\"nl\":\"Macedonië\",\"hr\":\"Makedonija\",\"fa\":\"\",\"de\":\"Mazedonien\",\"es\":\"Macedonia\",\"fr\":\"Macédoine\",\"ja\":\"マケドニア旧ユーゴスラビア共和国\",\"it\":\"Macedonia\"}', 41.83333333, 22.00000000, '??', 'U+1F1F2 U+1F1F0', '2018-07-20 20:11:03', '2021-07-31 15:37:15', 1, 'Q221'),
(130, 'Madagascar', 'مدغشقر\r\n', 'MDG', 'MG', '261', 'Antananarivo', 'MGA', 'Ar', '.mg', 'Madagasikara', 'Africa', 'Eastern Africa', '[{\"zoneName\":\"Indian\\/Antananarivo\",\"gmtOffset\":10800,\"gmtOffsetName\":\"UTC+03:00\",\"abbreviation\":\"EAT\",\"tzName\":\"East Africa Time\"}]', '{\"br\":\"Madagascar\",\"pt\":\"Madagáscar\",\"nl\":\"Madagaskar\",\"hr\":\"Madagaskar\",\"fa\":\"ماداگاسکار\",\"de\":\"Madagaskar\",\"es\":\"Madagascar\",\"fr\":\"Madagascar\",\"ja\":\"マダガスカル\",\"it\":\"Madagascar\"}', -20.00000000, 47.00000000, '??', 'U+1F1F2 U+1F1EC', '2018-07-20 20:11:03', '2021-07-31 15:37:21', 1, 'Q1019'),
(131, 'Malawi', 'ملاوي', 'MWI', 'MW', '265', 'Lilongwe', 'MWK', 'MK', '.mw', 'Malawi', 'Africa', 'Eastern Africa', '[{\"zoneName\":\"Africa\\/Blantyre\",\"gmtOffset\":7200,\"gmtOffsetName\":\"UTC+02:00\",\"abbreviation\":\"CAT\",\"tzName\":\"Central Africa Time\"}]', '{\"br\":\"Malawi\",\"pt\":\"Malávi\",\"nl\":\"Malawi\",\"hr\":\"Malavi\",\"fa\":\"مالاوی\",\"de\":\"Malawi\",\"es\":\"Malawi\",\"fr\":\"Malawi\",\"ja\":\"マラウイ\",\"it\":\"Malawi\"}', -13.50000000, 34.00000000, '??', 'U+1F1F2 U+1F1FC', '2018-07-20 20:11:03', '2021-07-31 15:37:27', 1, 'Q1020'),
(132, 'Malaysia', 'ماليزيا', 'MYS', 'MY', '60', 'Kuala Lumpur', 'MYR', 'RM', '.my', 'Malaysia', 'Asia', 'South-Eastern Asia', '[{\"zoneName\":\"Asia\\/Kuala_Lumpur\",\"gmtOffset\":28800,\"gmtOffsetName\":\"UTC+08:00\",\"abbreviation\":\"MYT\",\"tzName\":\"Malaysia Time\"},{\"zoneName\":\"Asia\\/Kuching\",\"gmtOffset\":28800,\"gmtOffsetName\":\"UTC+08:00\",\"abbreviation\":\"MYT\",\"tzName\":\"Malaysia Time\"}]', '{\"br\":\"Malásia\",\"pt\":\"Malásia\",\"nl\":\"Maleisië\",\"hr\":\"Malezija\",\"fa\":\"مالزی\",\"de\":\"Malaysia\",\"es\":\"Malasia\",\"fr\":\"Malaisie\",\"ja\":\"マレーシア\",\"it\":\"Malesia\"}', 2.50000000, 112.50000000, '??', 'U+1F1F2 U+1F1FE', '2018-07-20 20:11:03', '2021-07-31 15:37:35', 1, 'Q833'),
(133, 'Maldives', 'جزر المالديف\r\n', 'MDV', 'MV', '960', 'Male', 'MVR', 'Rf', '.mv', 'Maldives', 'Asia', 'Southern Asia', '[{\"zoneName\":\"Indian\\/Maldives\",\"gmtOffset\":18000,\"gmtOffsetName\":\"UTC+05:00\",\"abbreviation\":\"MVT\",\"tzName\":\"Maldives Time\"}]', '{\"br\":\"Maldivas\",\"pt\":\"Maldivas\",\"nl\":\"Maldiven\",\"hr\":\"Maldivi\",\"fa\":\"مالدیو\",\"de\":\"Malediven\",\"es\":\"Maldivas\",\"fr\":\"Maldives\",\"ja\":\"モルディブ\",\"it\":\"Maldive\"}', 3.25000000, 73.00000000, '??', 'U+1F1F2 U+1F1FB', '2018-07-20 20:11:03', '2021-07-31 15:37:44', 1, 'Q826'),
(134, 'Mali', 'مالي\r\n', 'MLI', 'ML', '223', 'Bamako', 'XOF', 'CFA', '.ml', 'Mali', 'Africa', 'Western Africa', '[{\"zoneName\":\"Africa\\/Bamako\",\"gmtOffset\":0,\"gmtOffsetName\":\"UTC\\u00b100\",\"abbreviation\":\"GMT\",\"tzName\":\"Greenwich Mean Time\"}]', '{\"br\":\"Mali\",\"pt\":\"Mali\",\"nl\":\"Mali\",\"hr\":\"Mali\",\"fa\":\"مالی\",\"de\":\"Mali\",\"es\":\"Mali\",\"fr\":\"Mali\",\"ja\":\"マリ\",\"it\":\"Mali\"}', 17.00000000, -4.00000000, '??', 'U+1F1F2 U+1F1F1', '2018-07-20 20:11:03', '2021-07-31 15:37:49', 1, 'Q912'),
(135, 'Malta', 'مالطا', 'MLT', 'MT', '356', 'Valletta', 'EUR', '€', '.mt', 'Malta', 'Europe', 'Southern Europe', '[{\"zoneName\":\"Europe\\/Malta\",\"gmtOffset\":3600,\"gmtOffsetName\":\"UTC+01:00\",\"abbreviation\":\"CET\",\"tzName\":\"Central European Time\"}]', '{\"br\":\"Malta\",\"pt\":\"Malta\",\"nl\":\"Malta\",\"hr\":\"Malta\",\"fa\":\"مالت\",\"de\":\"Malta\",\"es\":\"Malta\",\"fr\":\"Malte\",\"ja\":\"マルタ\",\"it\":\"Malta\"}', 35.83333333, 14.58333333, '??', 'U+1F1F2 U+1F1F9', '2018-07-20 20:11:03', '2021-07-31 15:37:56', 1, 'Q233'),
(136, 'Man (Isle of)', 'رجل (جزيرة)\r\n', 'IMN', 'IM', '+44-1624', 'Douglas, Isle of Man', 'GBP', '£', '.im', 'Isle of Man', 'Europe', 'Northern Europe', '[{\"zoneName\":\"Europe\\/Isle_of_Man\",\"gmtOffset\":0,\"gmtOffsetName\":\"UTC\\u00b100\",\"abbreviation\":\"GMT\",\"tzName\":\"Greenwich Mean Time\"}]', '{\"br\":\"Ilha de Man\",\"pt\":\"Ilha de Man\",\"nl\":\"Isle of Man\",\"hr\":\"Otok Man\",\"fa\":\"جزیره من\",\"de\":\"Insel Man\",\"es\":\"Isla de Man\",\"fr\":\"Île de Man\",\"ja\":\"マン島\",\"it\":\"Isola di Man\"}', 54.25000000, -4.50000000, '??', 'U+1F1EE U+1F1F2', '2018-07-20 20:11:03', '2021-07-31 15:38:02', 1, NULL),
(137, 'Marshall Islands', 'جزر مارشال\r\n', 'MHL', 'MH', '692', 'Majuro', 'USD', '$', '.mh', 'M̧ajeļ', 'Oceania', 'Micronesia', '[{\"zoneName\":\"Pacific\\/Kwajalein\",\"gmtOffset\":43200,\"gmtOffsetName\":\"UTC+12:00\",\"abbreviation\":\"MHT\",\"tzName\":\"Marshall Islands Time\"},{\"zoneName\":\"Pacific\\/Majuro\",\"gmtOffset\":43200,\"gmtOffsetName\":\"UTC+12:00\",\"abbreviation\":\"MHT\",\"tzName\":\"Marshall Islands Time\"}]', '{\"br\":\"Ilhas Marshall\",\"pt\":\"Ilhas Marshall\",\"nl\":\"Marshalleilanden\",\"hr\":\"Maršalovi Otoci\",\"fa\":\"جزایر مارشال\",\"de\":\"Marshallinseln\",\"es\":\"Islas Marshall\",\"fr\":\"Îles Marshall\",\"ja\":\"マーシャル諸島\",\"it\":\"Isole Marshall\"}', 9.00000000, 168.00000000, '??', 'U+1F1F2 U+1F1ED', '2018-07-20 20:11:03', '2021-07-31 15:38:06', 1, 'Q709'),
(138, 'Martinique', 'مارتينيك', 'MTQ', 'MQ', '596', 'Fort-de-France', 'EUR', '€', '.mq', 'Martinique', 'Americas', 'Caribbean', '[{\"zoneName\":\"America\\/Martinique\",\"gmtOffset\":-14400,\"gmtOffsetName\":\"UTC-04:00\",\"abbreviation\":\"AST\",\"tzName\":\"Atlantic Standard Time\"}]', '{\"br\":\"Martinica\",\"pt\":\"Martinica\",\"nl\":\"Martinique\",\"hr\":\"Martinique\",\"fa\":\"مونتسرات\",\"de\":\"Martinique\",\"es\":\"Martinica\",\"fr\":\"Martinique\",\"ja\":\"マルティニーク\",\"it\":\"Martinica\"}', 14.66666700, -61.00000000, '??', 'U+1F1F2 U+1F1F6', '2018-07-20 20:11:03', '2021-07-31 15:38:17', 1, NULL),
(139, 'Mauritania', 'موريتانيا', 'MRT', 'MR', '222', 'Nouakchott', 'MRO', 'MRU', '.mr', 'موريتانيا', 'Africa', 'Western Africa', '[{\"zoneName\":\"Africa\\/Nouakchott\",\"gmtOffset\":0,\"gmtOffsetName\":\"UTC\\u00b100\",\"abbreviation\":\"GMT\",\"tzName\":\"Greenwich Mean Time\"}]', '{\"br\":\"Mauritânia\",\"pt\":\"Mauritânia\",\"nl\":\"Mauritanië\",\"hr\":\"Mauritanija\",\"fa\":\"موریتانی\",\"de\":\"Mauretanien\",\"es\":\"Mauritania\",\"fr\":\"Mauritanie\",\"ja\":\"モーリタニア\",\"it\":\"Mauritania\"}', 20.00000000, -12.00000000, '??', 'U+1F1F2 U+1F1F7', '2018-07-20 20:11:03', '2021-07-31 15:38:22', 1, 'Q1025');
INSERT INTO `countries` (`id`, `name`, `nameAR`, `iso3`, `iso2`, `phonecode`, `capital`, `currency`, `currency_symbol`, `tld`, `native`, `region`, `subregion`, `timezones`, `translations`, `latitude`, `longitude`, `emoji`, `emojiU`, `created_at`, `updated_at`, `flag`, `wikiDataId`) VALUES
(140, 'Mauritius', 'موريشيوس', 'MUS', 'MU', '230', 'Port Louis', 'MUR', '₨', '.mu', 'Maurice', 'Africa', 'Eastern Africa', '[{\"zoneName\":\"Indian\\/Mauritius\",\"gmtOffset\":14400,\"gmtOffsetName\":\"UTC+04:00\",\"abbreviation\":\"MUT\",\"tzName\":\"Mauritius Time\"}]', '{\"br\":\"Maurício\",\"pt\":\"Maurícia\",\"nl\":\"Mauritius\",\"hr\":\"Mauricijus\",\"fa\":\"موریس\",\"de\":\"Mauritius\",\"es\":\"Mauricio\",\"fr\":\"Île Maurice\",\"ja\":\"モーリシャス\",\"it\":\"Mauritius\"}', -20.28333333, 57.55000000, '??', 'U+1F1F2 U+1F1FA', '2018-07-20 20:11:03', '2021-07-31 15:38:26', 1, 'Q1027'),
(141, 'Mayotte', 'مايوتس\r\n', 'MYT', 'YT', '262', 'Mamoudzou', 'EUR', '€', '.yt', 'Mayotte', 'Africa', 'Eastern Africa', '[{\"zoneName\":\"Indian\\/Mayotte\",\"gmtOffset\":10800,\"gmtOffsetName\":\"UTC+03:00\",\"abbreviation\":\"EAT\",\"tzName\":\"East Africa Time\"}]', '{\"br\":\"Mayotte\",\"pt\":\"Mayotte\",\"nl\":\"Mayotte\",\"hr\":\"Mayotte\",\"fa\":\"مایوت\",\"de\":\"Mayotte\",\"es\":\"Mayotte\",\"fr\":\"Mayotte\",\"ja\":\"マヨット\",\"it\":\"Mayotte\"}', -12.83333333, 45.16666666, '??', 'U+1F1FE U+1F1F9', '2018-07-20 20:11:03', '2021-07-31 15:38:32', 1, NULL),
(142, 'Mexico', 'المكسيك\r\n', 'MEX', 'MX', '52', 'Mexico City', 'MXN', '$', '.mx', 'México', 'Americas', 'Central America', '[{\"zoneName\":\"America\\/Bahia_Banderas\",\"gmtOffset\":-21600,\"gmtOffsetName\":\"UTC-06:00\",\"abbreviation\":\"CST\",\"tzName\":\"Central Standard Time (North America\"},{\"zoneName\":\"America\\/Cancun\",\"gmtOffset\":-18000,\"gmtOffsetName\":\"UTC-05:00\",\"abbreviation\":\"EST\",\"tzName\":\"Eastern Standard Time (North America\"},{\"zoneName\":\"America\\/Chihuahua\",\"gmtOffset\":-25200,\"gmtOffsetName\":\"UTC-07:00\",\"abbreviation\":\"MST\",\"tzName\":\"Mountain Standard Time (North America\"},{\"zoneName\":\"America\\/Hermosillo\",\"gmtOffset\":-25200,\"gmtOffsetName\":\"UTC-07:00\",\"abbreviation\":\"MST\",\"tzName\":\"Mountain Standard Time (North America\"},{\"zoneName\":\"America\\/Matamoros\",\"gmtOffset\":-21600,\"gmtOffsetName\":\"UTC-06:00\",\"abbreviation\":\"CST\",\"tzName\":\"Central Standard Time (North America\"},{\"zoneName\":\"America\\/Mazatlan\",\"gmtOffset\":-25200,\"gmtOffsetName\":\"UTC-07:00\",\"abbreviation\":\"MST\",\"tzName\":\"Mountain Standard Time (North America\"},{\"zoneName\":\"America\\/Merida\",\"gmtOffset\":-21600,\"gmtOffsetName\":\"UTC-06:00\",\"abbreviation\":\"CST\",\"tzName\":\"Central Standard Time (North America\"},{\"zoneName\":\"America\\/Mexico_City\",\"gmtOffset\":-21600,\"gmtOffsetName\":\"UTC-06:00\",\"abbreviation\":\"CST\",\"tzName\":\"Central Standard Time (North America\"},{\"zoneName\":\"America\\/Monterrey\",\"gmtOffset\":-21600,\"gmtOffsetName\":\"UTC-06:00\",\"abbreviation\":\"CST\",\"tzName\":\"Central Standard Time (North America\"},{\"zoneName\":\"America\\/Ojinaga\",\"gmtOffset\":-25200,\"gmtOffsetName\":\"UTC-07:00\",\"abbreviation\":\"MST\",\"tzName\":\"Mountain Standard Time (North America\"},{\"zoneName\":\"America\\/Tijuana\",\"gmtOffset\":-28800,\"gmtOffsetName\":\"UTC-08:00\",\"abbreviation\":\"PST\",\"tzName\":\"Pacific Standard Time (North America\"}]', '{\"br\":\"México\",\"pt\":\"México\",\"nl\":\"Mexico\",\"hr\":\"Meksiko\",\"fa\":\"مکزیک\",\"de\":\"Mexiko\",\"es\":\"México\",\"fr\":\"Mexique\",\"ja\":\"メキシコ\",\"it\":\"Messico\"}', 23.00000000, -102.00000000, '??', 'U+1F1F2 U+1F1FD', '2018-07-20 20:11:03', '2021-07-31 15:38:37', 1, 'Q96'),
(143, 'Micronesia', 'ميكرونيزيا', 'FSM', 'FM', '691', 'Palikir', 'USD', '$', '.fm', 'Micronesia', 'Oceania', 'Micronesia', '[{\"zoneName\":\"Pacific\\/Chuuk\",\"gmtOffset\":36000,\"gmtOffsetName\":\"UTC+10:00\",\"abbreviation\":\"CHUT\",\"tzName\":\"Chuuk Time\"},{\"zoneName\":\"Pacific\\/Kosrae\",\"gmtOffset\":39600,\"gmtOffsetName\":\"UTC+11:00\",\"abbreviation\":\"KOST\",\"tzName\":\"Kosrae Time\"},{\"zoneName\":\"Pacific\\/Pohnpei\",\"gmtOffset\":39600,\"gmtOffsetName\":\"UTC+11:00\",\"abbreviation\":\"PONT\",\"tzName\":\"Pohnpei Standard Time\"}]', '{\"br\":\"Micronésia\",\"pt\":\"Micronésia\",\"nl\":\"Micronesië\",\"hr\":\"Mikronezija\",\"fa\":\"ایالات فدرال میکرونزی\",\"de\":\"Mikronesien\",\"es\":\"Micronesia\",\"fr\":\"Micronésie\",\"ja\":\"ミクロネシア連邦\",\"it\":\"Micronesia\"}', 6.91666666, 158.25000000, '??', 'U+1F1EB U+1F1F2', '2018-07-20 20:11:03', '2021-07-31 15:38:45', 1, 'Q702'),
(144, 'Moldova', 'مولدوفا', 'MDA', 'MD', '373', 'Chisinau', 'MDL', 'L', '.md', 'Moldova', 'Europe', 'Eastern Europe', '[{\"zoneName\":\"Europe\\/Chisinau\",\"gmtOffset\":7200,\"gmtOffsetName\":\"UTC+02:00\",\"abbreviation\":\"EET\",\"tzName\":\"Eastern European Time\"}]', '{\"br\":\"Moldávia\",\"pt\":\"Moldávia\",\"nl\":\"Moldavië\",\"hr\":\"Moldova\",\"fa\":\"مولداوی\",\"de\":\"Moldawie\",\"es\":\"Moldavia\",\"fr\":\"Moldavie\",\"ja\":\"モルドバ共和国\",\"it\":\"Moldavia\"}', 47.00000000, 29.00000000, '??', 'U+1F1F2 U+1F1E9', '2018-07-20 20:11:03', '2021-07-31 15:38:52', 1, 'Q217'),
(145, 'Monaco', 'موناكو', 'MCO', 'MC', '377', 'Monaco', 'EUR', '€', '.mc', 'Monaco', 'Europe', 'Western Europe', '[{\"zoneName\":\"Europe\\/Monaco\",\"gmtOffset\":3600,\"gmtOffsetName\":\"UTC+01:00\",\"abbreviation\":\"CET\",\"tzName\":\"Central European Time\"}]', '{\"br\":\"Mônaco\",\"pt\":\"Mónaco\",\"nl\":\"Monaco\",\"hr\":\"Monako\",\"fa\":\"موناکو\",\"de\":\"Monaco\",\"es\":\"Mónaco\",\"fr\":\"Monaco\",\"ja\":\"モナコ\",\"it\":\"Principato di Monaco\"}', 43.73333333, 7.40000000, '??', 'U+1F1F2 U+1F1E8', '2018-07-20 20:11:03', '2021-07-31 15:38:59', 1, NULL),
(146, 'Mongolia', 'منغوليا', 'MNG', 'MN', '976', 'Ulan Bator', 'MNT', '₮', '.mn', 'Монгол улс', 'Asia', 'Eastern Asia', '[{\"zoneName\":\"Asia\\/Choibalsan\",\"gmtOffset\":28800,\"gmtOffsetName\":\"UTC+08:00\",\"abbreviation\":\"CHOT\",\"tzName\":\"Choibalsan Standard Time\"},{\"zoneName\":\"Asia\\/Hovd\",\"gmtOffset\":25200,\"gmtOffsetName\":\"UTC+07:00\",\"abbreviation\":\"HOVT\",\"tzName\":\"Hovd Time\"},{\"zoneName\":\"Asia\\/Ulaanbaatar\",\"gmtOffset\":28800,\"gmtOffsetName\":\"UTC+08:00\",\"abbreviation\":\"ULAT\",\"tzName\":\"Ulaanbaatar Standard Time\"}]', '{\"br\":\"Mongólia\",\"pt\":\"Mongólia\",\"nl\":\"Mongolië\",\"hr\":\"Mongolija\",\"fa\":\"مغولستان\",\"de\":\"Mongolei\",\"es\":\"Mongolia\",\"fr\":\"Mongolie\",\"ja\":\"モンゴル\",\"it\":\"Mongolia\"}', 46.00000000, 105.00000000, '??', 'U+1F1F2 U+1F1F3', '2018-07-20 20:11:03', '2021-07-31 15:39:03', 1, 'Q711'),
(147, 'Montenegro', 'الجبل الأسود\r\n', 'MNE', 'ME', '382', 'Podgorica', 'EUR', '€', '.me', 'Црна Гора', 'Europe', 'Southern Europe', '[{\"zoneName\":\"Europe\\/Podgorica\",\"gmtOffset\":3600,\"gmtOffsetName\":\"UTC+01:00\",\"abbreviation\":\"CET\",\"tzName\":\"Central European Time\"}]', '{\"br\":\"Montenegro\",\"pt\":\"Montenegro\",\"nl\":\"Montenegro\",\"hr\":\"Crna Gora\",\"fa\":\"مونته‌نگرو\",\"de\":\"Montenegro\",\"es\":\"Montenegro\",\"fr\":\"Monténégro\",\"ja\":\"モンテネグロ\",\"it\":\"Montenegro\"}', 42.50000000, 19.30000000, '??', 'U+1F1F2 U+1F1EA', '2018-07-20 20:11:03', '2021-07-31 15:39:21', 1, 'Q236'),
(148, 'Montserrat', 'مونتسيرات', 'MSR', 'MS', '+1-664', 'Plymouth', 'XCD', '$', '.ms', 'Montserrat', 'Americas', 'Caribbean', '[{\"zoneName\":\"America\\/Montserrat\",\"gmtOffset\":-14400,\"gmtOffsetName\":\"UTC-04:00\",\"abbreviation\":\"AST\",\"tzName\":\"Atlantic Standard Time\"}]', '{\"br\":\"Montserrat\",\"pt\":\"Monserrate\",\"nl\":\"Montserrat\",\"hr\":\"Montserrat\",\"fa\":\"مایوت\",\"de\":\"Montserrat\",\"es\":\"Montserrat\",\"fr\":\"Montserrat\",\"ja\":\"モントセラト\",\"it\":\"Montserrat\"}', 16.75000000, -62.20000000, '??', 'U+1F1F2 U+1F1F8', '2018-07-20 20:11:03', '2021-07-31 15:39:27', 1, NULL),
(149, 'Morocco', 'المغرب', 'MAR', 'MA', '212', 'Rabat', 'MAD', 'DH', '.ma', 'المغرب', 'Africa', 'Northern Africa', '[{\"zoneName\":\"Africa\\/Casablanca\",\"gmtOffset\":3600,\"gmtOffsetName\":\"UTC+01:00\",\"abbreviation\":\"WEST\",\"tzName\":\"Western European Summer Time\"}]', '{\"br\":\"Marrocos\",\"pt\":\"Marrocos\",\"nl\":\"Marokko\",\"hr\":\"Maroko\",\"fa\":\"مراکش\",\"de\":\"Marokko\",\"es\":\"Marruecos\",\"fr\":\"Maroc\",\"ja\":\"モロッコ\",\"it\":\"Marocco\"}', 32.00000000, -5.00000000, '??', 'U+1F1F2 U+1F1E6', '2018-07-20 20:11:03', '2021-07-31 15:39:36', 1, 'Q1028'),
(150, 'Mozambique', 'موزمبيق', 'MOZ', 'MZ', '258', 'Maputo', 'MZN', 'MT', '.mz', 'Moçambique', 'Africa', 'Eastern Africa', '[{\"zoneName\":\"Africa\\/Maputo\",\"gmtOffset\":7200,\"gmtOffsetName\":\"UTC+02:00\",\"abbreviation\":\"CAT\",\"tzName\":\"Central Africa Time\"}]', '{\"br\":\"Moçambique\",\"pt\":\"Moçambique\",\"nl\":\"Mozambique\",\"hr\":\"Mozambik\",\"fa\":\"موزامبیک\",\"de\":\"Mosambik\",\"es\":\"Mozambique\",\"fr\":\"Mozambique\",\"ja\":\"モザンビーク\",\"it\":\"Mozambico\"}', -18.25000000, 35.00000000, '??', 'U+1F1F2 U+1F1FF', '2018-07-20 20:11:03', '2021-07-31 15:18:33', 1, 'Q1029'),
(151, 'Myanmar', 'ميانمار', 'MMR', 'MM', '95', 'Nay Pyi Taw', 'MMK', 'K', '.mm', 'မြန်မာ', 'Asia', 'South-Eastern Asia', '[{\"zoneName\":\"Asia\\/Yangon\",\"gmtOffset\":23400,\"gmtOffsetName\":\"UTC+06:30\",\"abbreviation\":\"MMT\",\"tzName\":\"Myanmar Standard Time\"}]', '{\"br\":\"Myanmar\",\"pt\":\"Myanmar\",\"nl\":\"Myanmar\",\"hr\":\"Mijanmar\",\"fa\":\"میانمار\",\"de\":\"Myanmar\",\"es\":\"Myanmar\",\"fr\":\"Myanmar\",\"ja\":\"ミャンマー\",\"it\":\"Birmania\"}', 22.00000000, 98.00000000, '??', 'U+1F1F2 U+1F1F2', '2018-07-20 20:11:03', '2021-07-31 14:46:16', 1, 'Q836'),
(152, 'Namibia', 'ناميبيا', 'NAM', 'NA', '264', 'Windhoek', 'NAD', '$', '.na', 'Namibia', 'Africa', 'Southern Africa', '[{\"zoneName\":\"Africa\\/Windhoek\",\"gmtOffset\":7200,\"gmtOffsetName\":\"UTC+02:00\",\"abbreviation\":\"WAST\",\"tzName\":\"West Africa Summer Time\"}]', '{\"br\":\"Namíbia\",\"pt\":\"Namíbia\",\"nl\":\"Namibië\",\"hr\":\"Namibija\",\"fa\":\"نامیبیا\",\"de\":\"Namibia\",\"es\":\"Namibia\",\"fr\":\"Namibie\",\"ja\":\"ナミビア\",\"it\":\"Namibia\"}', -22.00000000, 17.00000000, '??', 'U+1F1F3 U+1F1E6', '2018-07-20 20:11:03', '2021-07-31 14:46:04', 1, 'Q1030'),
(153, 'Nauru', 'ناورو', 'NRU', 'NR', '674', 'Yaren', 'AUD', '$', '.nr', 'Nauru', 'Oceania', 'Micronesia', '[{\"zoneName\":\"Pacific\\/Nauru\",\"gmtOffset\":43200,\"gmtOffsetName\":\"UTC+12:00\",\"abbreviation\":\"NRT\",\"tzName\":\"Nauru Time\"}]', '{\"br\":\"Nauru\",\"pt\":\"Nauru\",\"nl\":\"Nauru\",\"hr\":\"Nauru\",\"fa\":\"نائورو\",\"de\":\"Nauru\",\"es\":\"Nauru\",\"fr\":\"Nauru\",\"ja\":\"ナウル\",\"it\":\"Nauru\"}', -0.53333333, 166.91666666, '??', 'U+1F1F3 U+1F1F7', '2018-07-20 20:11:03', '2021-07-31 14:45:52', 1, 'Q697'),
(154, 'Nepal', 'نيبال', 'NPL', 'NP', '977', 'Kathmandu', 'NPR', '₨', '.np', 'नपल', 'Asia', 'Southern Asia', '[{\"zoneName\":\"Asia\\/Kathmandu\",\"gmtOffset\":20700,\"gmtOffsetName\":\"UTC+05:45\",\"abbreviation\":\"NPT\",\"tzName\":\"Nepal Time\"}]', '{\"br\":\"Nepal\",\"pt\":\"Nepal\",\"nl\":\"Nepal\",\"hr\":\"Nepal\",\"fa\":\"نپال\",\"de\":\"Népal\",\"es\":\"Nepal\",\"fr\":\"Népal\",\"ja\":\"ネパール\",\"it\":\"Nepal\"}', 28.00000000, 84.00000000, '??', 'U+1F1F3 U+1F1F5', '2018-07-20 20:11:03', '2021-07-31 14:45:39', 1, 'Q837'),
(155, 'Bonaire, Sint Eustatius and Saba', 'بونير وسانت يوستاتيوس وسابا', 'BES', 'BQ', '599', 'Kralendijk', 'USD', '$', '.an', 'Caribisch Nederland', 'Americas', 'Caribbean', '[{\"zoneName\":\"America\\/Anguilla\",\"gmtOffset\":-14400,\"gmtOffsetName\":\"UTC-04:00\",\"abbreviation\":\"AST\",\"tzName\":\"Atlantic Standard Time\"}]', '{\"br\":\"Bonaire\",\"pt\":\"Bonaire\",\"fa\":\"بونیر\",\"de\":\"Bonaire, Sint Eustatius und Saba\",\"fr\":\"Bonaire, Saint-Eustache et Saba\",\"it\":\"Bonaire, Saint-Eustache e Saba\"}', 12.15000000, -68.26666700, '??', 'U+1F1E7 U+1F1F6', '2018-07-20 20:11:03', '2021-07-31 14:45:26', 1, 'Q27561'),
(156, 'Netherlands The', 'هولندا The', 'NLD', 'NL', '31', 'Amsterdam', 'EUR', '€', '.nl', 'Nederland', 'Europe', 'Western Europe', '[{\"zoneName\":\"Europe\\/Amsterdam\",\"gmtOffset\":3600,\"gmtOffsetName\":\"UTC+01:00\",\"abbreviation\":\"CET\",\"tzName\":\"Central European Time\"}]', '{\"br\":\"Holanda\",\"pt\":\"Países Baixos\",\"nl\":\"Nederland\",\"hr\":\"Nizozemska\",\"fa\":\"پادشاهی هلند\",\"de\":\"Niederlande\",\"es\":\"Países Bajos\",\"fr\":\"Pays-Bas\",\"ja\":\"オランダ\",\"it\":\"Paesi Bassi\"}', 52.50000000, 5.75000000, '??', 'U+1F1F3 U+1F1F1', '2018-07-20 20:11:03', '2021-07-31 14:45:07', 1, 'Q55'),
(157, 'New Caledonia', 'كاليدونيا الجديدة', 'NCL', 'NC', '687', 'Noumea', 'XPF', '₣', '.nc', 'Nouvelle-Calédonie', 'Oceania', 'Melanesia', '[{\"zoneName\":\"Pacific\\/Noumea\",\"gmtOffset\":39600,\"gmtOffsetName\":\"UTC+11:00\",\"abbreviation\":\"NCT\",\"tzName\":\"New Caledonia Time\"}]', '{\"br\":\"Nova Caledônia\",\"pt\":\"Nova Caledónia\",\"nl\":\"Nieuw-Caledonië\",\"hr\":\"Nova Kaledonija\",\"fa\":\"کالدونیای جدید\",\"de\":\"Neukaledonien\",\"es\":\"Nueva Caledonia\",\"fr\":\"Nouvelle-Calédonie\",\"ja\":\"ニューカレドニア\",\"it\":\"Nuova Caledonia\"}', -21.50000000, 165.50000000, '??', 'U+1F1F3 U+1F1E8', '2018-07-20 20:11:03', '2021-07-31 14:44:41', 1, NULL),
(158, 'New Zealand', 'نيوزيلاندا', 'NZL', 'NZ', '64', 'Wellington', 'NZD', '$', '.nz', 'New Zealand', 'Oceania', 'Australia and New Zealand', '[{\"zoneName\":\"Pacific\\/Auckland\",\"gmtOffset\":46800,\"gmtOffsetName\":\"UTC+13:00\",\"abbreviation\":\"NZDT\",\"tzName\":\"New Zealand Daylight Time\"},{\"zoneName\":\"Pacific\\/Chatham\",\"gmtOffset\":49500,\"gmtOffsetName\":\"UTC+13:45\",\"abbreviation\":\"CHAST\",\"tzName\":\"Chatham Standard Time\"}]', '{\"br\":\"Nova Zelândia\",\"pt\":\"Nova Zelândia\",\"nl\":\"Nieuw-Zeeland\",\"hr\":\"Novi Zeland\",\"fa\":\"نیوزیلند\",\"de\":\"Neuseeland\",\"es\":\"Nueva Zelanda\",\"fr\":\"Nouvelle-Zélande\",\"ja\":\"ニュージーランド\",\"it\":\"Nuova Zelanda\"}', -41.00000000, 174.00000000, '??', 'U+1F1F3 U+1F1FF', '2018-07-20 20:11:03', '2021-07-31 14:44:26', 1, 'Q664'),
(159, 'Nicaragua', 'نيكاراغوا', 'NIC', 'NI', '505', 'Managua', 'NIO', 'C$', '.ni', 'Nicaragua', 'Americas', 'Central America', '[{\"zoneName\":\"America\\/Managua\",\"gmtOffset\":-21600,\"gmtOffsetName\":\"UTC-06:00\",\"abbreviation\":\"CST\",\"tzName\":\"Central Standard Time (North America\"}]', '{\"br\":\"Nicarágua\",\"pt\":\"Nicarágua\",\"nl\":\"Nicaragua\",\"hr\":\"Nikaragva\",\"fa\":\"نیکاراگوئه\",\"de\":\"Nicaragua\",\"es\":\"Nicaragua\",\"fr\":\"Nicaragua\",\"ja\":\"ニカラグア\",\"it\":\"Nicaragua\"}', 13.00000000, -85.00000000, '??', 'U+1F1F3 U+1F1EE', '2018-07-20 20:11:03', '2021-07-31 14:44:13', 1, 'Q811'),
(160, 'Niger', 'النيجر', 'NER', 'NE', '227', 'Niamey', 'XOF', 'CFA', '.ne', 'Niger', 'Africa', 'Western Africa', '[{\"zoneName\":\"Africa\\/Niamey\",\"gmtOffset\":3600,\"gmtOffsetName\":\"UTC+01:00\",\"abbreviation\":\"WAT\",\"tzName\":\"West Africa Time\"}]', '{\"br\":\"Níger\",\"pt\":\"Níger\",\"nl\":\"Niger\",\"hr\":\"Niger\",\"fa\":\"نیجر\",\"de\":\"Niger\",\"es\":\"Níger\",\"fr\":\"Niger\",\"ja\":\"ニジェール\",\"it\":\"Niger\"}', 16.00000000, 8.00000000, '??', 'U+1F1F3 U+1F1EA', '2018-07-20 20:11:03', '2021-07-31 14:44:01', 1, 'Q1032'),
(161, 'Nigeria', 'نيجيريا', 'NGA', 'NG', '234', 'Abuja', 'NGN', '₦', '.ng', 'Nigeria', 'Africa', 'Western Africa', '[{\"zoneName\":\"Africa\\/Lagos\",\"gmtOffset\":3600,\"gmtOffsetName\":\"UTC+01:00\",\"abbreviation\":\"WAT\",\"tzName\":\"West Africa Time\"}]', '{\"br\":\"Nigéria\",\"pt\":\"Nigéria\",\"nl\":\"Nigeria\",\"hr\":\"Nigerija\",\"fa\":\"نیجریه\",\"de\":\"Nigeria\",\"es\":\"Nigeria\",\"fr\":\"Nigéria\",\"ja\":\"ナイジェリア\",\"it\":\"Nigeria\"}', 10.00000000, 8.00000000, '??', 'U+1F1F3 U+1F1EC', '2018-07-20 20:11:03', '2021-07-31 14:43:48', 1, 'Q1033'),
(162, 'Niue', 'نيوي', 'NIU', 'NU', '683', 'Alofi', 'NZD', '$', '.nu', 'Niuē', 'Oceania', 'Polynesia', '[{\"zoneName\":\"Pacific\\/Niue\",\"gmtOffset\":-39600,\"gmtOffsetName\":\"UTC-11:00\",\"abbreviation\":\"NUT\",\"tzName\":\"Niue Time\"}]', '{\"br\":\"Niue\",\"pt\":\"Niue\",\"nl\":\"Niue\",\"hr\":\"Niue\",\"fa\":\"نیووی\",\"de\":\"Niue\",\"es\":\"Niue\",\"fr\":\"Niue\",\"ja\":\"ニウエ\",\"it\":\"Niue\"}', -19.03333333, -169.86666666, '??', 'U+1F1F3 U+1F1FA', '2018-07-20 20:11:03', '2021-07-31 14:43:37', 1, 'Q34020'),
(163, 'Norfolk Island', 'جزيرة نورفولك', 'NFK', 'NF', '672', 'Kingston', 'AUD', '$', '.nf', 'Norfolk Island', 'Oceania', 'Australia and New Zealand', '[{\"zoneName\":\"Pacific\\/Norfolk\",\"gmtOffset\":43200,\"gmtOffsetName\":\"UTC+12:00\",\"abbreviation\":\"NFT\",\"tzName\":\"Norfolk Time\"}]', '{\"br\":\"Ilha Norfolk\",\"pt\":\"Ilha Norfolk\",\"nl\":\"Norfolkeiland\",\"hr\":\"Otok Norfolk\",\"fa\":\"جزیره نورفک\",\"de\":\"Norfolkinsel\",\"es\":\"Isla de Norfolk\",\"fr\":\"Île de Norfolk\",\"ja\":\"ノーフォーク島\",\"it\":\"Isola Norfolk\"}', -29.03333333, 167.95000000, '??', 'U+1F1F3 U+1F1EB', '2018-07-20 20:11:03', '2021-07-31 14:43:24', 1, NULL),
(164, 'Northern Mariana Islands', 'جزر مريانا الشمالية', 'MNP', 'MP', '+1-670', 'Saipan', 'USD', '$', '.mp', 'Northern Mariana Islands', 'Oceania', 'Micronesia', '[{\"zoneName\":\"Pacific\\/Saipan\",\"gmtOffset\":36000,\"gmtOffsetName\":\"UTC+10:00\",\"abbreviation\":\"ChST\",\"tzName\":\"Chamorro Standard Time\"}]', '{\"br\":\"Ilhas Marianas\",\"pt\":\"Ilhas Marianas\",\"nl\":\"Noordelijke Marianeneilanden\",\"hr\":\"Sjevernomarijanski otoci\",\"fa\":\"جزایر ماریانای شمالی\",\"de\":\"Nördliche Marianen\",\"es\":\"Islas Marianas del Norte\",\"fr\":\"Îles Mariannes du Nord\",\"ja\":\"北マリアナ諸島\",\"it\":\"Isole Marianne Settentrionali\"}', 15.20000000, 145.75000000, '??', 'U+1F1F2 U+1F1F5', '2018-07-20 20:11:03', '2021-07-31 14:43:11', 1, NULL),
(165, 'Norway', 'النرويج', 'NOR', 'NO', '47', 'Oslo', 'NOK', 'kr', '.no', 'Norge', 'Europe', 'Northern Europe', '[{\"zoneName\":\"Europe\\/Oslo\",\"gmtOffset\":3600,\"gmtOffsetName\":\"UTC+01:00\",\"abbreviation\":\"CET\",\"tzName\":\"Central European Time\"}]', '{\"br\":\"Noruega\",\"pt\":\"Noruega\",\"nl\":\"Noorwegen\",\"hr\":\"Norveška\",\"fa\":\"نروژ\",\"de\":\"Norwegen\",\"es\":\"Noruega\",\"fr\":\"Norvège\",\"ja\":\"ノルウェー\",\"it\":\"Norvegia\"}', 62.00000000, 10.00000000, '??', 'U+1F1F3 U+1F1F4', '2018-07-20 20:11:03', '2021-07-31 14:42:55', 1, 'Q20'),
(166, 'Oman', 'سلطنة عمان', 'OMN', 'OM', '968', 'Muscat', 'OMR', '.ع.ر', '.om', 'عمان', 'Asia', 'Western Asia', '[{\"zoneName\":\"Asia\\/Muscat\",\"gmtOffset\":14400,\"gmtOffsetName\":\"UTC+04:00\",\"abbreviation\":\"GST\",\"tzName\":\"Gulf Standard Time\"}]', '{\"br\":\"Omã\",\"pt\":\"Omã\",\"nl\":\"Oman\",\"hr\":\"Oman\",\"fa\":\"عمان\",\"de\":\"Oman\",\"es\":\"Omán\",\"fr\":\"Oman\",\"ja\":\"オマーン\",\"it\":\"oman\"}', 21.00000000, 57.00000000, '??', 'U+1F1F4 U+1F1F2', '2018-07-20 20:11:03', '2021-07-31 14:42:44', 1, 'Q842'),
(167, 'Pakistan', 'باكستان', 'PAK', 'PK', '92', 'Islamabad', 'PKR', '₨', '.pk', 'Pakistan', 'Asia', 'Southern Asia', '[{\"zoneName\":\"Asia\\/Karachi\",\"gmtOffset\":18000,\"gmtOffsetName\":\"UTC+05:00\",\"abbreviation\":\"PKT\",\"tzName\":\"Pakistan Standard Time\"}]', '{\"br\":\"Paquistão\",\"pt\":\"Paquistão\",\"nl\":\"Pakistan\",\"hr\":\"Pakistan\",\"fa\":\"پاکستان\",\"de\":\"Pakistan\",\"es\":\"Pakistán\",\"fr\":\"Pakistan\",\"ja\":\"パキスタン\",\"it\":\"Pakistan\"}', 30.00000000, 70.00000000, '??', 'U+1F1F5 U+1F1F0', '2018-07-20 20:11:03', '2021-07-31 14:42:28', 1, 'Q843'),
(168, 'Palau', 'بالاو', 'PLW', 'PW', '680', 'Melekeok', 'USD', '$', '.pw', 'Palau', 'Oceania', 'Micronesia', '[{\"zoneName\":\"Pacific\\/Palau\",\"gmtOffset\":32400,\"gmtOffsetName\":\"UTC+09:00\",\"abbreviation\":\"PWT\",\"tzName\":\"Palau Time\"}]', '{\"br\":\"Palau\",\"pt\":\"Palau\",\"nl\":\"Palau\",\"hr\":\"Palau\",\"fa\":\"پالائو\",\"de\":\"Palau\",\"es\":\"Palau\",\"fr\":\"Palaos\",\"ja\":\"パラオ\",\"it\":\"Palau\"}', 7.50000000, 134.50000000, '??', 'U+1F1F5 U+1F1FC', '2018-07-20 20:11:03', '2021-07-31 14:42:13', 1, 'Q695'),
(169, 'Palestinian Territory Occupied', 'الأراضي الفلسطينية المحتلة', 'PSE', 'PS', '970', 'East Jerusalem', 'ILS', '₪', '.ps', 'فلسطين', 'Asia', 'Western Asia', '[{\"zoneName\":\"Asia\\/Gaza\",\"gmtOffset\":7200,\"gmtOffsetName\":\"UTC+02:00\",\"abbreviation\":\"EET\",\"tzName\":\"Eastern European Time\"},{\"zoneName\":\"Asia\\/Hebron\",\"gmtOffset\":7200,\"gmtOffsetName\":\"UTC+02:00\",\"abbreviation\":\"EET\",\"tzName\":\"Eastern European Time\"}]', '{\"br\":\"Palestina\",\"pt\":\"Palestina\",\"nl\":\"Palestijnse gebieden\",\"hr\":\"Palestina\",\"fa\":\"فلسطین\",\"de\":\"Palästina\",\"es\":\"Palestina\",\"fr\":\"Palestine\",\"ja\":\"パレスチナ\",\"it\":\"Palestina\"}', 31.90000000, 35.20000000, '??', 'U+1F1F5 U+1F1F8', '2018-07-20 20:11:03', '2021-07-31 14:41:59', 1, NULL),
(170, 'Panama', 'باناما', 'PAN', 'PA', '507', 'Panama City', 'PAB', 'B/.', '.pa', 'Panamá', 'Americas', 'Central America', '[{\"zoneName\":\"America\\/Panama\",\"gmtOffset\":-18000,\"gmtOffsetName\":\"UTC-05:00\",\"abbreviation\":\"EST\",\"tzName\":\"Eastern Standard Time (North America\"}]', '{\"br\":\"Panamá\",\"pt\":\"Panamá\",\"nl\":\"Panama\",\"hr\":\"Panama\",\"fa\":\"پاناما\",\"de\":\"Panama\",\"es\":\"Panamá\",\"fr\":\"Panama\",\"ja\":\"パナマ\",\"it\":\"Panama\"}', 9.00000000, -80.00000000, '??', 'U+1F1F5 U+1F1E6', '2018-07-20 20:11:03', '2021-07-31 14:41:43', 1, 'Q804'),
(171, 'Papua new Guinea', 'بابوا غينيا الجديدة', 'PNG', 'PG', '675', 'Port Moresby', 'PGK', 'K', '.pg', 'Papua Niugini', 'Oceania', 'Melanesia', '[{\"zoneName\":\"Pacific\\/Bougainville\",\"gmtOffset\":39600,\"gmtOffsetName\":\"UTC+11:00\",\"abbreviation\":\"BST\",\"tzName\":\"Bougainville Standard Time[6\"},{\"zoneName\":\"Pacific\\/Port_Moresby\",\"gmtOffset\":36000,\"gmtOffsetName\":\"UTC+10:00\",\"abbreviation\":\"PGT\",\"tzName\":\"Papua New Guinea Time\"}]', '{\"br\":\"Papua Nova Guiné\",\"pt\":\"Papua Nova Guiné\",\"nl\":\"Papoea-Nieuw-Guinea\",\"hr\":\"Papua Nova Gvineja\",\"fa\":\"پاپوآ گینه نو\",\"de\":\"Papua-Neuguinea\",\"es\":\"Papúa Nueva Guinea\",\"fr\":\"Papouasie-Nouvelle-Guinée\",\"ja\":\"パプアニューギニア\",\"it\":\"Papua Nuova Guinea\"}', -6.00000000, 147.00000000, '??', 'U+1F1F5 U+1F1EC', '2018-07-20 20:11:03', '2021-07-31 14:41:29', 1, 'Q691'),
(172, 'Paraguay', 'باراغواي', 'PRY', 'PY', '595', 'Asuncion', 'PYG', '₲', '.py', 'Paraguay', 'Americas', 'South America', '[{\"zoneName\":\"America\\/Asuncion\",\"gmtOffset\":-10800,\"gmtOffsetName\":\"UTC-03:00\",\"abbreviation\":\"PYST\",\"tzName\":\"Paraguay Summer Time\"}]', '{\"br\":\"Paraguai\",\"pt\":\"Paraguai\",\"nl\":\"Paraguay\",\"hr\":\"Paragvaj\",\"fa\":\"پاراگوئه\",\"de\":\"Paraguay\",\"es\":\"Paraguay\",\"fr\":\"Paraguay\",\"ja\":\"パラグアイ\",\"it\":\"Paraguay\"}', -23.00000000, -58.00000000, '??', 'U+1F1F5 U+1F1FE', '2018-07-20 20:11:03', '2021-07-31 14:41:13', 1, 'Q733'),
(173, 'Peru', 'بيرو', 'PER', 'PE', '51', 'Lima', 'PEN', 'S/.', '.pe', 'Perú', 'Americas', 'South America', '[{\"zoneName\":\"America\\/Lima\",\"gmtOffset\":-18000,\"gmtOffsetName\":\"UTC-05:00\",\"abbreviation\":\"PET\",\"tzName\":\"Peru Time\"}]', '{\"br\":\"Peru\",\"pt\":\"Peru\",\"nl\":\"Peru\",\"hr\":\"Peru\",\"fa\":\"پرو\",\"de\":\"Peru\",\"es\":\"Perú\",\"fr\":\"Pérou\",\"ja\":\"ペルー\",\"it\":\"Perù\"}', -10.00000000, -76.00000000, '??', 'U+1F1F5 U+1F1EA', '2018-07-20 20:11:03', '2021-07-31 14:40:58', 1, 'Q419'),
(174, 'Philippines', 'الفلبين', 'PHL', 'PH', '63', 'Manila', 'PHP', '₱', '.ph', 'Pilipinas', 'Asia', 'South-Eastern Asia', '[{\"zoneName\":\"Asia\\/Manila\",\"gmtOffset\":28800,\"gmtOffsetName\":\"UTC+08:00\",\"abbreviation\":\"PHT\",\"tzName\":\"Philippine Time\"}]', '{\"br\":\"Filipinas\",\"pt\":\"Filipinas\",\"nl\":\"Filipijnen\",\"hr\":\"Filipini\",\"fa\":\"جزایر الندفیلیپین\",\"de\":\"Philippinen\",\"es\":\"Filipinas\",\"fr\":\"Philippines\",\"ja\":\"フィリピン\",\"it\":\"Filippine\"}', 13.00000000, 122.00000000, '??', 'U+1F1F5 U+1F1ED', '2018-07-20 20:11:03', '2021-07-31 14:40:46', 1, 'Q928'),
(175, 'Pitcairn Island', 'جزيرة بيتكيرن', 'PCN', 'PN', '870', 'Adamstown', 'NZD', '$', '.pn', 'Pitcairn Islands', 'Oceania', 'Polynesia', '[{\"zoneName\":\"Pacific\\/Pitcairn\",\"gmtOffset\":-28800,\"gmtOffsetName\":\"UTC-08:00\",\"abbreviation\":\"PST\",\"tzName\":\"Pacific Standard Time (North America\"}]', '{\"br\":\"Ilhas Pitcairn\",\"pt\":\"Ilhas Picárnia\",\"nl\":\"Pitcairneilanden\",\"hr\":\"Pitcairnovo otočje\",\"fa\":\"پیتکرن\",\"de\":\"Pitcairn\",\"es\":\"Islas Pitcairn\",\"fr\":\"Îles Pitcairn\",\"ja\":\"ピトケアン\",\"it\":\"Isole Pitcairn\"}', -25.06666666, -130.10000000, '??', 'U+1F1F5 U+1F1F3', '2018-07-20 20:11:03', '2021-07-31 14:40:27', 1, NULL),
(176, 'Poland', 'بولندا', 'POL', 'PL', '48', 'Warsaw', 'PLN', 'zł', '.pl', 'Polska', 'Europe', 'Eastern Europe', '[{\"zoneName\":\"Europe\\/Warsaw\",\"gmtOffset\":3600,\"gmtOffsetName\":\"UTC+01:00\",\"abbreviation\":\"CET\",\"tzName\":\"Central European Time\"}]', '{\"br\":\"Polônia\",\"pt\":\"Polónia\",\"nl\":\"Polen\",\"hr\":\"Poljska\",\"fa\":\"لهستان\",\"de\":\"Polen\",\"es\":\"Polonia\",\"fr\":\"Pologne\",\"ja\":\"ポーランド\",\"it\":\"Polonia\"}', 52.00000000, 20.00000000, '??', 'U+1F1F5 U+1F1F1', '2018-07-20 20:11:03', '2021-07-31 14:29:35', 1, 'Q36'),
(177, 'Portugal', 'البرتغال', 'PRT', 'PT', '351', 'Lisbon', 'EUR', '€', '.pt', 'Portugal', 'Europe', 'Southern Europe', '[{\"zoneName\":\"Atlantic\\/Azores\",\"gmtOffset\":-3600,\"gmtOffsetName\":\"UTC-01:00\",\"abbreviation\":\"AZOT\",\"tzName\":\"Azores Standard Time\"},{\"zoneName\":\"Atlantic\\/Madeira\",\"gmtOffset\":0,\"gmtOffsetName\":\"UTC\\u00b100\",\"abbreviation\":\"WET\",\"tzName\":\"Western European Time\"},{\"zoneName\":\"Europe\\/Lisbon\",\"gmtOffset\":0,\"gmtOffsetName\":\"UTC\\u00b100\",\"abbreviation\":\"WET\",\"tzName\":\"Western European Time\"}]', '{\"br\":\"Portugal\",\"pt\":\"Portugal\",\"nl\":\"Portugal\",\"hr\":\"Portugal\",\"fa\":\"پرتغال\",\"de\":\"Portugal\",\"es\":\"Portugal\",\"fr\":\"Portugal\",\"ja\":\"ポルトガル\",\"it\":\"Portogallo\"}', 39.50000000, -8.00000000, '??', 'U+1F1F5 U+1F1F9', '2018-07-20 20:11:03', '2021-07-31 14:29:48', 1, 'Q45'),
(178, 'Puerto Rico', 'بورتوريكو', 'PRI', 'PR', '+1-787 and 1-939', 'San Juan', 'USD', '$', '.pr', 'Puerto Rico', 'Americas', 'Caribbean', '[{\"zoneName\":\"America\\/Puerto_Rico\",\"gmtOffset\":-14400,\"gmtOffsetName\":\"UTC-04:00\",\"abbreviation\":\"AST\",\"tzName\":\"Atlantic Standard Time\"}]', '{\"br\":\"Porto Rico\",\"pt\":\"Porto Rico\",\"nl\":\"Puerto Rico\",\"hr\":\"Portoriko\",\"fa\":\"پورتو ریکو\",\"de\":\"Puerto Rico\",\"es\":\"Puerto Rico\",\"fr\":\"Porto Rico\",\"ja\":\"プエルトリコ\",\"it\":\"Porto Rico\"}', 18.25000000, -66.50000000, '??', 'U+1F1F5 U+1F1F7', '2018-07-20 20:11:03', '2021-07-31 14:30:03', 1, NULL),
(179, 'Qatar', 'دولة قطر', 'QAT', 'QA', '974', 'Doha', 'QAR', 'ق.ر', '.qa', 'قطر', 'Asia', 'Western Asia', '[{\"zoneName\":\"Asia\\/Qatar\",\"gmtOffset\":10800,\"gmtOffsetName\":\"UTC+03:00\",\"abbreviation\":\"AST\",\"tzName\":\"Arabia Standard Time\"}]', '{\"br\":\"Catar\",\"pt\":\"Catar\",\"nl\":\"Qatar\",\"hr\":\"Katar\",\"fa\":\"قطر\",\"de\":\"Katar\",\"es\":\"Catar\",\"fr\":\"Qatar\",\"ja\":\"カタール\",\"it\":\"Qatar\"}', 25.50000000, 51.25000000, '??', 'U+1F1F6 U+1F1E6', '2018-07-20 20:11:03', '2021-07-31 14:30:20', 1, 'Q846'),
(180, 'Reunion', 'جمع شمل', 'REU', 'RE', '262', 'Saint-Denis', 'EUR', '€', '.re', 'La Réunion', 'Africa', 'Eastern Africa', '[{\"zoneName\":\"Indian\\/Reunion\",\"gmtOffset\":14400,\"gmtOffsetName\":\"UTC+04:00\",\"abbreviation\":\"RET\",\"tzName\":\"R\\u00e9union Time\"}]', '{\"br\":\"Reunião\",\"pt\":\"Reunião\",\"nl\":\"Réunion\",\"hr\":\"Réunion\",\"fa\":\"رئونیون\",\"de\":\"Réunion\",\"es\":\"Reunión\",\"fr\":\"Réunion\",\"ja\":\"レユニオン\",\"it\":\"Riunione\"}', -21.15000000, 55.50000000, '??', 'U+1F1F7 U+1F1EA', '2018-07-20 20:11:03', '2021-07-31 14:30:37', 1, NULL),
(181, 'Romania', 'رومانيا', 'ROU', 'RO', '40', 'Bucharest', 'RON', 'lei', '.ro', 'România', 'Europe', 'Eastern Europe', '[{\"zoneName\":\"Europe\\/Bucharest\",\"gmtOffset\":7200,\"gmtOffsetName\":\"UTC+02:00\",\"abbreviation\":\"EET\",\"tzName\":\"Eastern European Time\"}]', '{\"br\":\"Romênia\",\"pt\":\"Roménia\",\"nl\":\"Roemenië\",\"hr\":\"Rumunjska\",\"fa\":\"رومانی\",\"de\":\"Rumänien\",\"es\":\"Rumania\",\"fr\":\"Roumanie\",\"ja\":\"ルーマニア\",\"it\":\"Romania\"}', 46.00000000, 25.00000000, '??', 'U+1F1F7 U+1F1F4', '2018-07-20 20:11:03', '2021-07-31 14:30:50', 1, 'Q218'),
(182, 'Russia', 'روسيا', 'RUS', 'RU', '7', 'Moscow', 'RUB', '₽', '.ru', 'Россия', 'Europe', 'Eastern Europe', '[{\"zoneName\":\"Asia\\/Anadyr\",\"gmtOffset\":43200,\"gmtOffsetName\":\"UTC+12:00\",\"abbreviation\":\"ANAT\",\"tzName\":\"Anadyr Time[4\"},{\"zoneName\":\"Asia\\/Barnaul\",\"gmtOffset\":25200,\"gmtOffsetName\":\"UTC+07:00\",\"abbreviation\":\"KRAT\",\"tzName\":\"Krasnoyarsk Time\"},{\"zoneName\":\"Asia\\/Chita\",\"gmtOffset\":32400,\"gmtOffsetName\":\"UTC+09:00\",\"abbreviation\":\"YAKT\",\"tzName\":\"Yakutsk Time\"},{\"zoneName\":\"Asia\\/Irkutsk\",\"gmtOffset\":28800,\"gmtOffsetName\":\"UTC+08:00\",\"abbreviation\":\"IRKT\",\"tzName\":\"Irkutsk Time\"},{\"zoneName\":\"Asia\\/Kamchatka\",\"gmtOffset\":43200,\"gmtOffsetName\":\"UTC+12:00\",\"abbreviation\":\"PETT\",\"tzName\":\"Kamchatka Time\"},{\"zoneName\":\"Asia\\/Khandyga\",\"gmtOffset\":32400,\"gmtOffsetName\":\"UTC+09:00\",\"abbreviation\":\"YAKT\",\"tzName\":\"Yakutsk Time\"},{\"zoneName\":\"Asia\\/Krasnoyarsk\",\"gmtOffset\":25200,\"gmtOffsetName\":\"UTC+07:00\",\"abbreviation\":\"KRAT\",\"tzName\":\"Krasnoyarsk Time\"},{\"zoneName\":\"Asia\\/Magadan\",\"gmtOffset\":39600,\"gmtOffsetName\":\"UTC+11:00\",\"abbreviation\":\"MAGT\",\"tzName\":\"Magadan Time\"},{\"zoneName\":\"Asia\\/Novokuznetsk\",\"gmtOffset\":25200,\"gmtOffsetName\":\"UTC+07:00\",\"abbreviation\":\"KRAT\",\"tzName\":\"Krasnoyarsk Time\"},{\"zoneName\":\"Asia\\/Novosibirsk\",\"gmtOffset\":25200,\"gmtOffsetName\":\"UTC+07:00\",\"abbreviation\":\"NOVT\",\"tzName\":\"Novosibirsk Time\"},{\"zoneName\":\"Asia\\/Omsk\",\"gmtOffset\":21600,\"gmtOffsetName\":\"UTC+06:00\",\"abbreviation\":\"OMST\",\"tzName\":\"Omsk Time\"},{\"zoneName\":\"Asia\\/Sakhalin\",\"gmtOffset\":39600,\"gmtOffsetName\":\"UTC+11:00\",\"abbreviation\":\"SAKT\",\"tzName\":\"Sakhalin Island Time\"},{\"zoneName\":\"Asia\\/Srednekolymsk\",\"gmtOffset\":39600,\"gmtOffsetName\":\"UTC+11:00\",\"abbreviation\":\"SRET\",\"tzName\":\"Srednekolymsk Time\"},{\"zoneName\":\"Asia\\/Tomsk\",\"gmtOffset\":25200,\"gmtOffsetName\":\"UTC+07:00\",\"abbreviation\":\"MSD+3\",\"tzName\":\"Moscow Daylight Time+3\"},{\"zoneName\":\"Asia\\/Ust-Nera\",\"gmtOffset\":36000,\"gmtOffsetName\":\"UTC+10:00\",\"abbreviation\":\"VLAT\",\"tzName\":\"Vladivostok Time\"},{\"zoneName\":\"Asia\\/Vladivostok\",\"gmtOffset\":36000,\"gmtOffsetName\":\"UTC+10:00\",\"abbreviation\":\"VLAT\",\"tzName\":\"Vladivostok Time\"},{\"zoneName\":\"Asia\\/Yakutsk\",\"gmtOffset\":32400,\"gmtOffsetName\":\"UTC+09:00\",\"abbreviation\":\"YAKT\",\"tzName\":\"Yakutsk Time\"},{\"zoneName\":\"Asia\\/Yekaterinburg\",\"gmtOffset\":18000,\"gmtOffsetName\":\"UTC+05:00\",\"abbreviation\":\"YEKT\",\"tzName\":\"Yekaterinburg Time\"},{\"zoneName\":\"Europe\\/Astrakhan\",\"gmtOffset\":14400,\"gmtOffsetName\":\"UTC+04:00\",\"abbreviation\":\"SAMT\",\"tzName\":\"Samara Time\"},{\"zoneName\":\"Europe\\/Kaliningrad\",\"gmtOffset\":7200,\"gmtOffsetName\":\"UTC+02:00\",\"abbreviation\":\"EET\",\"tzName\":\"Eastern European Time\"},{\"zoneName\":\"Europe\\/Kirov\",\"gmtOffset\":10800,\"gmtOffsetName\":\"UTC+03:00\",\"abbreviation\":\"MSK\",\"tzName\":\"Moscow Time\"},{\"zoneName\":\"Europe\\/Moscow\",\"gmtOffset\":10800,\"gmtOffsetName\":\"UTC+03:00\",\"abbreviation\":\"MSK\",\"tzName\":\"Moscow Time\"},{\"zoneName\":\"Europe\\/Samara\",\"gmtOffset\":14400,\"gmtOffsetName\":\"UTC+04:00\",\"abbreviation\":\"SAMT\",\"tzName\":\"Samara Time\"},{\"zoneName\":\"Europe\\/Saratov\",\"gmtOffset\":14400,\"gmtOffsetName\":\"UTC+04:00\",\"abbreviation\":\"MSD\",\"tzName\":\"Moscow Daylight Time+4\"},{\"zoneName\":\"Europe\\/Ulyanovsk\",\"gmtOffset\":14400,\"gmtOffsetName\":\"UTC+04:00\",\"abbreviation\":\"SAMT\",\"tzName\":\"Samara Time\"},{\"zoneName\":\"Europe\\/Volgograd\",\"gmtOffset\":14400,\"gmtOffsetName\":\"UTC+04:00\",\"abbreviation\":\"MSK\",\"tzName\":\"Moscow Standard Time\"}]', '{\"br\":\"Rússia\",\"pt\":\"Rússia\",\"nl\":\"Rusland\",\"hr\":\"Rusija\",\"fa\":\"روسیه\",\"de\":\"Russland\",\"es\":\"Rusia\",\"fr\":\"Russie\",\"ja\":\"ロシア連邦\",\"it\":\"Russia\"}', 60.00000000, 100.00000000, '??', 'U+1F1F7 U+1F1FA', '2018-07-20 20:11:03', '2021-07-31 14:31:03', 1, 'Q159'),
(183, 'Rwanda', 'رواندا', 'RWA', 'RW', '250', 'Kigali', 'RWF', 'FRw', '.rw', 'Rwanda', 'Africa', 'Eastern Africa', '[{\"zoneName\":\"Africa\\/Kigali\",\"gmtOffset\":7200,\"gmtOffsetName\":\"UTC+02:00\",\"abbreviation\":\"CAT\",\"tzName\":\"Central Africa Time\"}]', '{\"br\":\"Ruanda\",\"pt\":\"Ruanda\",\"nl\":\"Rwanda\",\"hr\":\"Ruanda\",\"fa\":\"رواندا\",\"de\":\"Ruanda\",\"es\":\"Ruanda\",\"fr\":\"Rwanda\",\"ja\":\"ルワンダ\",\"it\":\"Ruanda\"}', -2.00000000, 30.00000000, '??', 'U+1F1F7 U+1F1FC', '2018-07-20 20:11:03', '2021-07-31 14:31:21', 1, 'Q1037'),
(184, 'Saint Helena', 'سانت هيلانة', 'SHN', 'SH', '290', 'Jamestown', 'SHP', '£', '.sh', 'Saint Helena', 'Africa', 'Western Africa', '[{\"zoneName\":\"Atlantic\\/St_Helena\",\"gmtOffset\":0,\"gmtOffsetName\":\"UTC\\u00b100\",\"abbreviation\":\"GMT\",\"tzName\":\"Greenwich Mean Time\"}]', '{\"br\":\"Santa Helena\",\"pt\":\"Santa Helena\",\"nl\":\"Sint-Helena\",\"hr\":\"Sveta Helena\",\"fa\":\"سنت هلنا، اسنشن و تریستان دا کونا\",\"de\":\"Sankt Helena\",\"es\":\"Santa Helena\",\"fr\":\"Sainte-Hélène\",\"ja\":\"セントヘレナ・アセンションおよびトリスタンダクーニャ\",\"it\":\"Sant\'Elena\"}', -15.95000000, -5.70000000, '??', 'U+1F1F8 U+1F1ED', '2018-07-20 20:11:03', '2021-07-31 14:31:43', 1, NULL),
(185, 'Saint Kitts And Nevis', 'سانت كيتس ونيفيس', 'KNA', 'KN', '+1-869', 'Basseterre', 'XCD', '$', '.kn', 'Saint Kitts and Nevis', 'Americas', 'Caribbean', '[{\"zoneName\":\"America\\/St_Kitts\",\"gmtOffset\":-14400,\"gmtOffsetName\":\"UTC-04:00\",\"abbreviation\":\"AST\",\"tzName\":\"Atlantic Standard Time\"}]', '{\"br\":\"São Cristóvão e Neves\",\"pt\":\"São Cristóvão e Neves\",\"nl\":\"Saint Kitts en Nevis\",\"hr\":\"Sveti Kristof i Nevis\",\"fa\":\"سنت کیتس و نویس\",\"de\":\"St. Kitts und Nevis\",\"es\":\"San Cristóbal y Nieves\",\"fr\":\"Saint-Christophe-et-Niévès\",\"ja\":\"セントクリストファー・ネイビス\",\"it\":\"Saint Kitts e Nevis\"}', 17.33333333, -62.75000000, '??', 'U+1F1F0 U+1F1F3', '2018-07-20 20:11:03', '2021-07-31 14:32:10', 1, 'Q763'),
(186, 'Saint Lucia', 'القديسة لوسيا', 'LCA', 'LC', '+1-758', 'Castries', 'XCD', '$', '.lc', 'Saint Lucia', 'Americas', 'Caribbean', '[{\"zoneName\":\"America\\/St_Lucia\",\"gmtOffset\":-14400,\"gmtOffsetName\":\"UTC-04:00\",\"abbreviation\":\"AST\",\"tzName\":\"Atlantic Standard Time\"}]', '{\"br\":\"Santa Lúcia\",\"pt\":\"Santa Lúcia\",\"nl\":\"Saint Lucia\",\"hr\":\"Sveta Lucija\",\"fa\":\"سنت لوسیا\",\"de\":\"Saint Lucia\",\"es\":\"Santa Lucía\",\"fr\":\"Saint-Lucie\",\"ja\":\"セントルシア\",\"it\":\"Santa Lucia\"}', 13.88333333, -60.96666666, '??', 'U+1F1F1 U+1F1E8', '2018-07-20 20:11:03', '2021-07-31 14:32:25', 1, 'Q760'),
(187, 'Saint Pierre and Miquelon', 'سانت بيير وميكلون', 'SPM', 'PM', '508', 'Saint-Pierre', 'EUR', '€', '.pm', 'Saint-Pierre-et-Miquelon', 'Americas', 'Northern America', '[{\"zoneName\":\"America\\/Miquelon\",\"gmtOffset\":-10800,\"gmtOffsetName\":\"UTC-03:00\",\"abbreviation\":\"PMDT\",\"tzName\":\"Pierre & Miquelon Daylight Time\"}]', '{\"br\":\"Saint-Pierre e Miquelon\",\"pt\":\"São Pedro e Miquelon\",\"nl\":\"Saint Pierre en Miquelon\",\"hr\":\"Sveti Petar i Mikelon\",\"fa\":\"سن پیر و میکلن\",\"de\":\"Saint-Pierre und Miquelon\",\"es\":\"San Pedro y Miquelón\",\"fr\":\"Saint-Pierre-et-Miquelon\",\"ja\":\"サンピエール島・ミクロン島\",\"it\":\"Saint-Pierre e Miquelon\"}', 46.83333333, -56.33333333, '??', 'U+1F1F5 U+1F1F2', '2018-07-20 20:11:03', '2021-07-31 14:32:37', 1, NULL),
(188, 'Saint Vincent And The Grenadines', 'سانت فنسنت وجزر غرينادين', 'VCT', 'VC', '+1-784', 'Kingstown', 'XCD', '$', '.vc', 'Saint Vincent and the Grenadines', 'Americas', 'Caribbean', '[{\"zoneName\":\"America\\/St_Vincent\",\"gmtOffset\":-14400,\"gmtOffsetName\":\"UTC-04:00\",\"abbreviation\":\"AST\",\"tzName\":\"Atlantic Standard Time\"}]', '{\"br\":\"São Vicente e Granadinas\",\"pt\":\"São Vicente e Granadinas\",\"nl\":\"Saint Vincent en de Grenadines\",\"hr\":\"Sveti Vincent i Grenadini\",\"fa\":\"سنت وینسنت و گرنادین‌ها\",\"de\":\"Saint Vincent und die Grenadinen\",\"es\":\"San Vicente y Granadinas\",\"fr\":\"Saint-Vincent-et-les-Grenadines\",\"ja\":\"セントビンセントおよびグレナディーン諸島\",\"it\":\"Saint Vincent e Grenadine\"}', 13.25000000, -61.20000000, '??', 'U+1F1FB U+1F1E8', '2018-07-20 20:11:03', '2021-07-31 14:32:49', 1, 'Q757'),
(189, 'Saint-Barthelemy', 'سانت بارتيليمي', 'BLM', 'BL', '590', 'Gustavia', 'EUR', '€', '.bl', 'Saint-Barthélemy', 'Americas', 'Caribbean', '[{\"zoneName\":\"America\\/St_Barthelemy\",\"gmtOffset\":-14400,\"gmtOffsetName\":\"UTC-04:00\",\"abbreviation\":\"AST\",\"tzName\":\"Atlantic Standard Time\"}]', '{\"br\":\"São Bartolomeu\",\"pt\":\"São Bartolomeu\",\"nl\":\"Saint Barthélemy\",\"hr\":\"Saint Barthélemy\",\"fa\":\"سن-بارتلمی\",\"de\":\"Saint-Barthélemy\",\"es\":\"San Bartolomé\",\"fr\":\"Saint-Barthélemy\",\"ja\":\"サン・バルテルミー\",\"it\":\"Antille Francesi\"}', 18.50000000, -63.41666666, '??', 'U+1F1E7 U+1F1F1', '2018-07-20 20:11:03', '2021-07-31 14:33:11', 1, NULL),
(190, 'Saint-Martin (French part)', 'سان مارتن (الجزء الفرنسي)', 'MAF', 'MF', '590', 'Marigot', 'EUR', '€', '.mf', 'Saint-Martin', 'Americas', 'Caribbean', '[{\"zoneName\":\"America\\/Marigot\",\"gmtOffset\":-14400,\"gmtOffsetName\":\"UTC-04:00\",\"abbreviation\":\"AST\",\"tzName\":\"Atlantic Standard Time\"}]', '{\"br\":\"Saint Martin\",\"pt\":\"Ilha São Martinho\",\"nl\":\"Saint-Martin\",\"hr\":\"Sveti Martin\",\"fa\":\"سینت مارتن\",\"de\":\"Saint Martin\",\"es\":\"Saint Martin\",\"fr\":\"Saint-Martin\",\"ja\":\"サン・マルタン（フランス領）\",\"it\":\"Saint Martin\"}', 18.08333333, -63.95000000, '??', 'U+1F1F2 U+1F1EB', '2018-07-20 20:11:03', '2021-07-31 14:33:27', 1, NULL),
(191, 'Samoa', 'ساموا', 'WSM', 'WS', '685', 'Apia', 'WST', 'SAT', '.ws', 'Samoa', 'Oceania', 'Polynesia', '[{\"zoneName\":\"Pacific\\/Apia\",\"gmtOffset\":50400,\"gmtOffsetName\":\"UTC+14:00\",\"abbreviation\":\"WST\",\"tzName\":\"West Samoa Time\"}]', '{\"br\":\"Samoa\",\"pt\":\"Samoa\",\"nl\":\"Samoa\",\"hr\":\"Samoa\",\"fa\":\"ساموآ\",\"de\":\"Samoa\",\"es\":\"Samoa\",\"fr\":\"Samoa\",\"ja\":\"サモア\",\"it\":\"Samoa\"}', -13.58333333, -172.33333333, '??', 'U+1F1FC U+1F1F8', '2018-07-20 20:11:03', '2021-07-31 14:33:47', 1, 'Q683'),
(192, 'San Marino', 'سان مارينو', 'SMR', 'SM', '378', 'San Marino', 'EUR', '€', '.sm', 'San Marino', 'Europe', 'Southern Europe', '[{\"zoneName\":\"Europe\\/San_Marino\",\"gmtOffset\":3600,\"gmtOffsetName\":\"UTC+01:00\",\"abbreviation\":\"CET\",\"tzName\":\"Central European Time\"}]', '{\"br\":\"San Marino\",\"pt\":\"São Marinho\",\"nl\":\"San Marino\",\"hr\":\"San Marino\",\"fa\":\"سان مارینو\",\"de\":\"San Marino\",\"es\":\"San Marino\",\"fr\":\"Saint-Marin\",\"ja\":\"サンマリノ\",\"it\":\"San Marino\"}', 43.76666666, 12.41666666, '??', 'U+1F1F8 U+1F1F2', '2018-07-20 20:11:03', '2021-07-31 14:34:05', 1, 'Q238'),
(193, 'Sao Tome and Principe', 'ساو تومي وبرينسيبي', 'STP', 'ST', '239', 'Sao Tome', 'STD', 'Db', '.st', 'São Tomé e Príncipe', 'Africa', 'Middle Africa', '[{\"zoneName\":\"Africa\\/Sao_Tome\",\"gmtOffset\":0,\"gmtOffsetName\":\"UTC\\u00b100\",\"abbreviation\":\"GMT\",\"tzName\":\"Greenwich Mean Time\"}]', '{\"br\":\"São Tomé e Príncipe\",\"pt\":\"São Tomé e Príncipe\",\"nl\":\"Sao Tomé en Principe\",\"hr\":\"Sveti Toma i Princip\",\"fa\":\"کواترو دو فرویرو\",\"de\":\"São Tomé und Príncipe\",\"es\":\"Santo Tomé y Príncipe\",\"fr\":\"Sao Tomé-et-Principe\",\"ja\":\"サントメ・プリンシペ\",\"it\":\"São Tomé e Príncipe\"}', 1.00000000, 7.00000000, '??', 'U+1F1F8 U+1F1F9', '2018-07-20 20:11:03', '2021-07-31 14:34:24', 1, 'Q1039'),
(194, 'Saudi Arabia', 'المملكة العربية السعودية', 'SAU', 'SA', '966', 'Riyadh', 'SAR', '﷼', '.sa', 'العربية السعودية', 'Asia', 'Western Asia', '[{\"zoneName\":\"Asia\\/Riyadh\",\"gmtOffset\":10800,\"gmtOffsetName\":\"UTC+03:00\",\"abbreviation\":\"AST\",\"tzName\":\"Arabia Standard Time\"}]', '{\"br\":\"Arábia Saudita\",\"pt\":\"Arábia Saudita\",\"nl\":\"Saoedi-Arabië\",\"hr\":\"Saudijska Arabija\",\"fa\":\"عربستان سعودی\",\"de\":\"Saudi-Arabien\",\"es\":\"Arabia Saudí\",\"fr\":\"Arabie Saoudite\",\"ja\":\"サウジアラビア\",\"it\":\"Arabia Saudita\"}', 25.00000000, 45.00000000, '??', 'U+1F1F8 U+1F1E6', '2018-07-20 20:11:03', '2021-07-31 14:34:37', 1, 'Q851'),
(195, 'Senegal', 'السنغال', 'SEN', 'SN', '221', 'Dakar', 'XOF', 'CFA', '.sn', 'Sénégal', 'Africa', 'Western Africa', '[{\"zoneName\":\"Africa\\/Dakar\",\"gmtOffset\":0,\"gmtOffsetName\":\"UTC\\u00b100\",\"abbreviation\":\"GMT\",\"tzName\":\"Greenwich Mean Time\"}]', '{\"br\":\"Senegal\",\"pt\":\"Senegal\",\"nl\":\"Senegal\",\"hr\":\"Senegal\",\"fa\":\"سنگال\",\"de\":\"Senegal\",\"es\":\"Senegal\",\"fr\":\"Sénégal\",\"ja\":\"セネガル\",\"it\":\"Senegal\"}', 14.00000000, -14.00000000, '??', 'U+1F1F8 U+1F1F3', '2018-07-20 20:11:03', '2021-07-31 14:34:50', 1, 'Q1041'),
(196, 'Serbia', 'صربيا', 'SRB', 'RS', '381', 'Belgrade', 'RSD', 'din', '.rs', 'Србија', 'Europe', 'Southern Europe', '[{\"zoneName\":\"Europe\\/Belgrade\",\"gmtOffset\":3600,\"gmtOffsetName\":\"UTC+01:00\",\"abbreviation\":\"CET\",\"tzName\":\"Central European Time\"}]', '{\"br\":\"Sérvia\",\"pt\":\"Sérvia\",\"nl\":\"Servië\",\"hr\":\"Srbija\",\"fa\":\"صربستان\",\"de\":\"Serbien\",\"es\":\"Serbia\",\"fr\":\"Serbie\",\"ja\":\"セルビア\",\"it\":\"Serbia\"}', 44.00000000, 21.00000000, '??', 'U+1F1F7 U+1F1F8', '2018-07-20 20:11:03', '2021-07-31 14:35:01', 1, 'Q403'),
(197, 'Seychelles', 'سيشيل', 'SYC', 'SC', '248', 'Victoria', 'SCR', 'SRe', '.sc', 'Seychelles', 'Africa', 'Eastern Africa', '[{\"zoneName\":\"Indian\\/Mahe\",\"gmtOffset\":14400,\"gmtOffsetName\":\"UTC+04:00\",\"abbreviation\":\"SCT\",\"tzName\":\"Seychelles Time\"}]', '{\"br\":\"Seicheles\",\"pt\":\"Seicheles\",\"nl\":\"Seychellen\",\"hr\":\"Sejšeli\",\"fa\":\"سیشل\",\"de\":\"Seychellen\",\"es\":\"Seychelles\",\"fr\":\"Seychelles\",\"ja\":\"セーシェル\",\"it\":\"Seychelles\"}', -4.58333333, 55.66666666, '??', 'U+1F1F8 U+1F1E8', '2018-07-20 20:11:03', '2021-07-31 14:35:18', 1, 'Q1042'),
(198, 'Sierra Leone', 'سيرا ليون', 'SLE', 'SL', '232', 'Freetown', 'SLL', 'Le', '.sl', 'Sierra Leone', 'Africa', 'Western Africa', '[{\"zoneName\":\"Africa\\/Freetown\",\"gmtOffset\":0,\"gmtOffsetName\":\"UTC\\u00b100\",\"abbreviation\":\"GMT\",\"tzName\":\"Greenwich Mean Time\"}]', '{\"br\":\"Serra Leoa\",\"pt\":\"Serra Leoa\",\"nl\":\"Sierra Leone\",\"hr\":\"Sijera Leone\",\"fa\":\"سیرالئون\",\"de\":\"Sierra Leone\",\"es\":\"Sierra Leone\",\"fr\":\"Sierra Leone\",\"ja\":\"シエラレオネ\",\"it\":\"Sierra Leone\"}', 8.50000000, -11.50000000, '??', 'U+1F1F8 U+1F1F1', '2018-07-20 20:11:03', '2021-07-31 14:35:31', 1, 'Q1044'),
(199, 'Singapore', 'سنغافورة', 'SGP', 'SG', '65', 'Singapur', 'SGD', '$', '.sg', 'Singapore', 'Asia', 'South-Eastern Asia', '[{\"zoneName\":\"Asia\\/Singapore\",\"gmtOffset\":28800,\"gmtOffsetName\":\"UTC+08:00\",\"abbreviation\":\"SGT\",\"tzName\":\"Singapore Time\"}]', '{\"br\":\"Singapura\",\"pt\":\"Singapura\",\"nl\":\"Singapore\",\"hr\":\"Singapur\",\"fa\":\"سنگاپور\",\"de\":\"Singapur\",\"es\":\"Singapur\",\"fr\":\"Singapour\",\"ja\":\"シンガポール\",\"it\":\"Singapore\"}', 1.36666666, 103.80000000, '??', 'U+1F1F8 U+1F1EC', '2018-07-20 20:11:03', '2021-07-31 14:35:44', 1, 'Q334'),
(200, 'Slovakia', 'سلوفاكيا', 'SVK', 'SK', '421', 'Bratislava', 'EUR', '€', '.sk', 'Slovensko', 'Europe', 'Eastern Europe', '[{\"zoneName\":\"Europe\\/Bratislava\",\"gmtOffset\":3600,\"gmtOffsetName\":\"UTC+01:00\",\"abbreviation\":\"CET\",\"tzName\":\"Central European Time\"}]', '{\"br\":\"Eslováquia\",\"pt\":\"Eslováquia\",\"nl\":\"Slowakije\",\"hr\":\"Slovačka\",\"fa\":\"اسلواکی\",\"de\":\"Slowakei\",\"es\":\"República Eslovaca\",\"fr\":\"Slovaquie\",\"ja\":\"スロバキア\",\"it\":\"Slovacchia\"}', 48.66666666, 19.50000000, '??', 'U+1F1F8 U+1F1F0', '2018-07-20 20:11:03', '2021-07-31 14:36:00', 1, 'Q214'),
(201, 'Slovenia', 'سلوفينيا', 'SVN', 'SI', '386', 'Ljubljana', 'EUR', '€', '.si', 'Slovenija', 'Europe', 'Southern Europe', '[{\"zoneName\":\"Europe\\/Ljubljana\",\"gmtOffset\":3600,\"gmtOffsetName\":\"UTC+01:00\",\"abbreviation\":\"CET\",\"tzName\":\"Central European Time\"}]', '{\"br\":\"Eslovênia\",\"pt\":\"Eslovénia\",\"nl\":\"Slovenië\",\"hr\":\"Slovenija\",\"fa\":\"اسلوونی\",\"de\":\"Slowenien\",\"es\":\"Eslovenia\",\"fr\":\"Slovénie\",\"ja\":\"スロベニア\",\"it\":\"Slovenia\"}', 46.11666666, 14.81666666, '??', 'U+1F1F8 U+1F1EE', '2018-07-20 20:11:03', '2021-07-31 14:24:39', 1, 'Q215'),
(202, 'Solomon Islands', 'جزر سليمان', 'SLB', 'SB', '677', 'Honiara', 'SBD', 'Si$', '.sb', 'Solomon Islands', 'Oceania', 'Melanesia', '[{\"zoneName\":\"Pacific\\/Guadalcanal\",\"gmtOffset\":39600,\"gmtOffsetName\":\"UTC+11:00\",\"abbreviation\":\"SBT\",\"tzName\":\"Solomon Islands Time\"}]', '{\"br\":\"Ilhas Salomão\",\"pt\":\"Ilhas Salomão\",\"nl\":\"Salomonseilanden\",\"hr\":\"Solomonski Otoci\",\"fa\":\"جزایر سلیمان\",\"de\":\"Salomonen\",\"es\":\"Islas Salomón\",\"fr\":\"Îles Salomon\",\"ja\":\"ソロモン諸島\",\"it\":\"Isole Salomone\"}', -8.00000000, 159.00000000, '??', 'U+1F1F8 U+1F1E7', '2018-07-20 20:11:03', '2021-07-31 14:24:26', 1, 'Q685'),
(203, 'Somalia', 'الصومال', 'SOM', 'SO', '252', 'Mogadishu', 'SOS', 'Sh.so.', '.so', 'Soomaaliya', 'Africa', 'Eastern Africa', '[{\"zoneName\":\"Africa\\/Mogadishu\",\"gmtOffset\":10800,\"gmtOffsetName\":\"UTC+03:00\",\"abbreviation\":\"EAT\",\"tzName\":\"East Africa Time\"}]', '{\"br\":\"Somália\",\"pt\":\"Somália\",\"nl\":\"Somalië\",\"hr\":\"Somalija\",\"fa\":\"سومالی\",\"de\":\"Somalia\",\"es\":\"Somalia\",\"fr\":\"Somalie\",\"ja\":\"ソマリア\",\"it\":\"Somalia\"}', 10.00000000, 49.00000000, '??', 'U+1F1F8 U+1F1F4', '2018-07-20 20:11:03', '2021-07-31 14:23:56', 1, 'Q1045'),
(204, 'South Africa', 'جنوب أفريقيا', 'ZAF', 'ZA', '27', 'Pretoria', 'ZAR', 'R', '.za', 'South Africa', 'Africa', 'Southern Africa', '[{\"zoneName\":\"Africa\\/Johannesburg\",\"gmtOffset\":7200,\"gmtOffsetName\":\"UTC+02:00\",\"abbreviation\":\"SAST\",\"tzName\":\"South African Standard Time\"}]', '{\"br\":\"República Sul-Africana\",\"pt\":\"República Sul-Africana\",\"nl\":\"Zuid-Afrika\",\"hr\":\"Južnoafrička Republika\",\"fa\":\"آفریقای جنوبی\",\"de\":\"Republik Südafrika\",\"es\":\"República de Sudáfrica\",\"fr\":\"Afrique du Sud\",\"ja\":\"南アフリカ\",\"it\":\"Sud Africa\"}', -29.00000000, 24.00000000, '??', 'U+1F1FF U+1F1E6', '2018-07-20 20:11:03', '2021-07-31 14:23:41', 1, 'Q258'),
(205, 'South Georgia', 'جورجيا الجنوبية', 'SGS', 'GS', '', 'Grytviken', 'GBP', '£', '.gs', 'South Georgia', 'Americas', 'South America', '[{\"zoneName\":\"Atlantic\\/South_Georgia\",\"gmtOffset\":-7200,\"gmtOffsetName\":\"UTC-02:00\",\"abbreviation\":\"GST\",\"tzName\":\"South Georgia and the South Sandwich Islands Time\"}]', '{\"br\":\"Ilhas Geórgias do Sul e Sandwich do Sul\",\"pt\":\"Ilhas Geórgia do Sul e Sanduíche do Sul\",\"nl\":\"Zuid-Georgia en Zuidelijke Sandwicheilanden\",\"hr\":\"Južna Georgija i otočje Južni Sandwich\",\"fa\":\"جزایر جورجیای جنوبی و ساندویچ جنوبی\",\"de\":\"Südgeorgien und die Südlichen Sandwichinseln\",\"es\":\"Islas Georgias del Sur y Sandwich del Sur\",\"fr\":\"Géorgie du Sud-et-les Îles Sandwich du Sud\",\"ja\":\"サウスジョージア・サウスサンドウィッチ諸島\",\"it\":\"Georgia del Sud e Isole Sandwich Meridionali\"}', -54.50000000, -37.00000000, '??', 'U+1F1EC U+1F1F8', '2018-07-20 20:11:03', '2021-07-31 14:23:26', 1, NULL),
(206, 'South Sudan', 'جنوب السودان', 'SSD', 'SS', '211', 'Juba', 'SSP', '£', '.ss', 'South Sudan', 'Africa', 'Middle Africa', '[{\"zoneName\":\"Africa\\/Juba\",\"gmtOffset\":10800,\"gmtOffsetName\":\"UTC+03:00\",\"abbreviation\":\"EAT\",\"tzName\":\"East Africa Time\"}]', '{\"br\":\"Sudão do Sul\",\"pt\":\"Sudão do Sul\",\"nl\":\"Zuid-Soedan\",\"hr\":\"Južni Sudan\",\"fa\":\"سودان جنوبی\",\"de\":\"Südsudan\",\"es\":\"Sudán del Sur\",\"fr\":\"Soudan du Sud\",\"ja\":\"南スーダン\",\"it\":\"Sudan del sud\"}', 7.00000000, 30.00000000, '??', 'U+1F1F8 U+1F1F8', '2018-07-20 20:11:03', '2021-07-31 14:23:06', 1, 'Q958'),
(207, 'Spain', 'إسبانيا', 'ESP', 'ES', '34', 'Madrid', 'EUR', '€', '.es', 'España', 'Europe', 'Southern Europe', '[{\"zoneName\":\"Africa\\/Ceuta\",\"gmtOffset\":3600,\"gmtOffsetName\":\"UTC+01:00\",\"abbreviation\":\"CET\",\"tzName\":\"Central European Time\"},{\"zoneName\":\"Atlantic\\/Canary\",\"gmtOffset\":0,\"gmtOffsetName\":\"UTC\\u00b100\",\"abbreviation\":\"WET\",\"tzName\":\"Western European Time\"},{\"zoneName\":\"Europe\\/Madrid\",\"gmtOffset\":3600,\"gmtOffsetName\":\"UTC+01:00\",\"abbreviation\":\"CET\",\"tzName\":\"Central European Time\"}]', '{\"br\":\"Espanha\",\"pt\":\"Espanha\",\"nl\":\"Spanje\",\"hr\":\"Španjolska\",\"fa\":\"اسپانیا\",\"de\":\"Spanien\",\"es\":\"España\",\"fr\":\"Espagne\",\"ja\":\"スペイン\",\"it\":\"Spagna\"}', 40.00000000, -4.00000000, '??', 'U+1F1EA U+1F1F8', '2018-07-20 20:11:03', '2021-07-31 14:22:47', 1, 'Q29'),
(208, 'Sri Lanka', 'سري لانكا', 'LKA', 'LK', '94', 'Colombo', 'LKR', 'Rs', '.lk', 'śrī laṃkāva', 'Asia', 'Southern Asia', '[{\"zoneName\":\"Asia\\/Colombo\",\"gmtOffset\":19800,\"gmtOffsetName\":\"UTC+05:30\",\"abbreviation\":\"IST\",\"tzName\":\"Indian Standard Time\"}]', '{\"br\":\"Sri Lanka\",\"pt\":\"Sri Lanka\",\"nl\":\"Sri Lanka\",\"hr\":\"Šri Lanka\",\"fa\":\"سری‌لانکا\",\"de\":\"Sri Lanka\",\"es\":\"Sri Lanka\",\"fr\":\"Sri Lanka\",\"ja\":\"スリランカ\",\"it\":\"Sri Lanka\"}', 7.00000000, 81.00000000, '??', 'U+1F1F1 U+1F1F0', '2018-07-20 20:11:03', '2021-07-31 14:22:27', 1, 'Q854');
INSERT INTO `countries` (`id`, `name`, `nameAR`, `iso3`, `iso2`, `phonecode`, `capital`, `currency`, `currency_symbol`, `tld`, `native`, `region`, `subregion`, `timezones`, `translations`, `latitude`, `longitude`, `emoji`, `emojiU`, `created_at`, `updated_at`, `flag`, `wikiDataId`) VALUES
(209, 'Sudan', 'السودان', 'SDN', 'SD', '249', 'Khartoum', 'SDG', '.س.ج', '.sd', 'السودان', 'Africa', 'Northern Africa', '[{\"zoneName\":\"Africa\\/Khartoum\",\"gmtOffset\":7200,\"gmtOffsetName\":\"UTC+02:00\",\"abbreviation\":\"EAT\",\"tzName\":\"Eastern African Time\"}]', '{\"br\":\"Sudão\",\"pt\":\"Sudão\",\"nl\":\"Soedan\",\"hr\":\"Sudan\",\"fa\":\"سودان\",\"de\":\"Sudan\",\"es\":\"Sudán\",\"fr\":\"Soudan\",\"ja\":\"スーダン\",\"it\":\"Sudan\"}', 15.00000000, 30.00000000, '??', 'U+1F1F8 U+1F1E9', '2018-07-20 20:11:03', '2021-07-31 14:22:01', 1, 'Q1049'),
(210, 'Suriname', 'سورينام', 'SUR', 'SR', '597', 'Paramaribo', 'SRD', '$', '.sr', 'Suriname', 'Americas', 'South America', '[{\"zoneName\":\"America\\/Paramaribo\",\"gmtOffset\":-10800,\"gmtOffsetName\":\"UTC-03:00\",\"abbreviation\":\"SRT\",\"tzName\":\"Suriname Time\"}]', '{\"br\":\"Suriname\",\"pt\":\"Suriname\",\"nl\":\"Suriname\",\"hr\":\"Surinam\",\"fa\":\"سورینام\",\"de\":\"Suriname\",\"es\":\"Surinam\",\"fr\":\"Surinam\",\"ja\":\"スリナム\",\"it\":\"Suriname\"}', 4.00000000, -56.00000000, '??', 'U+1F1F8 U+1F1F7', '2018-07-20 20:11:03', '2021-07-31 14:21:46', 1, 'Q730'),
(211, 'Svalbard And Jan Mayen Islands', 'جزر سفالبارد وجان ماين', 'SJM', 'SJ', '47', 'Longyearbyen', 'NOK', 'kr', '.sj', 'Svalbard og Jan Mayen', 'Europe', 'Northern Europe', '[{\"zoneName\":\"Arctic\\/Longyearbyen\",\"gmtOffset\":3600,\"gmtOffsetName\":\"UTC+01:00\",\"abbreviation\":\"CET\",\"tzName\":\"Central European Time\"}]', '{\"br\":\"Svalbard\",\"pt\":\"Svalbard\",\"nl\":\"Svalbard en Jan Mayen\",\"hr\":\"Svalbard i Jan Mayen\",\"fa\":\"سوالبارد و یان ماین\",\"de\":\"Svalbard und Jan Mayen\",\"es\":\"Islas Svalbard y Jan Mayen\",\"fr\":\"Svalbard et Jan Mayen\",\"ja\":\"スヴァールバル諸島およびヤンマイエン島\",\"it\":\"Svalbard e Jan Mayen\"}', 78.00000000, 20.00000000, '??', 'U+1F1F8 U+1F1EF', '2018-07-20 20:11:03', '2021-07-31 14:21:26', 1, NULL),
(212, 'Swaziland', 'سوازيلاند', 'SWZ', 'SZ', '268', 'Mbabane', 'SZL', 'E', '.sz', 'Swaziland', 'Africa', 'Southern Africa', '[{\"zoneName\":\"Africa\\/Mbabane\",\"gmtOffset\":7200,\"gmtOffsetName\":\"UTC+02:00\",\"abbreviation\":\"SAST\",\"tzName\":\"South African Standard Time\"}]', '{\"br\":\"Suazilândia\",\"pt\":\"Suazilândia\",\"nl\":\"Swaziland\",\"hr\":\"Svazi\",\"fa\":\"سوازیلند\",\"de\":\"Swasiland\",\"es\":\"Suazilandia\",\"fr\":\"Swaziland\",\"ja\":\"スワジランド\",\"it\":\"Swaziland\"}', -26.50000000, 31.50000000, '??', 'U+1F1F8 U+1F1FF', '2018-07-20 20:11:03', '2021-07-31 14:21:12', 1, 'Q1050'),
(213, 'Sweden', 'السويد', 'SWE', 'SE', '46', 'Stockholm', 'SEK', 'kr', '.se', 'Sverige', 'Europe', 'Northern Europe', '[{\"zoneName\":\"Europe\\/Stockholm\",\"gmtOffset\":3600,\"gmtOffsetName\":\"UTC+01:00\",\"abbreviation\":\"CET\",\"tzName\":\"Central European Time\"}]', '{\"br\":\"Suécia\",\"pt\":\"Suécia\",\"nl\":\"Zweden\",\"hr\":\"Švedska\",\"fa\":\"سوئد\",\"de\":\"Schweden\",\"es\":\"Suecia\",\"fr\":\"Suède\",\"ja\":\"スウェーデン\",\"it\":\"Svezia\"}', 62.00000000, 15.00000000, '??', 'U+1F1F8 U+1F1EA', '2018-07-20 20:11:03', '2021-07-31 14:20:59', 1, 'Q34'),
(214, 'Switzerland', 'سويسرا', 'CHE', 'CH', '41', 'Berne', 'CHF', 'CHf', '.ch', 'Schweiz', 'Europe', 'Western Europe', '[{\"zoneName\":\"Europe\\/Zurich\",\"gmtOffset\":3600,\"gmtOffsetName\":\"UTC+01:00\",\"abbreviation\":\"CET\",\"tzName\":\"Central European Time\"}]', '{\"br\":\"Suíça\",\"pt\":\"Suíça\",\"nl\":\"Zwitserland\",\"hr\":\"Švicarska\",\"fa\":\"سوئیس\",\"de\":\"Schweiz\",\"es\":\"Suiza\",\"fr\":\"Suisse\",\"ja\":\"スイス\",\"it\":\"Svizzera\"}', 47.00000000, 8.00000000, '??', 'U+1F1E8 U+1F1ED', '2018-07-20 20:11:03', '2021-07-31 14:20:32', 1, 'Q39'),
(215, 'Syria', 'سوريا', 'SYR', 'SY', '963', 'Damascus', 'SYP', 'LS', '.sy', 'سوريا', 'Asia', 'Western Asia', '[{\"zoneName\":\"Asia\\/Damascus\",\"gmtOffset\":7200,\"gmtOffsetName\":\"UTC+02:00\",\"abbreviation\":\"EET\",\"tzName\":\"Eastern European Time\"}]', '{\"br\":\"Síria\",\"pt\":\"Síria\",\"nl\":\"Syrië\",\"hr\":\"Sirija\",\"fa\":\"سوریه\",\"de\":\"Syrien\",\"es\":\"Siria\",\"fr\":\"Syrie\",\"ja\":\"シリア・アラブ共和国\",\"it\":\"Siria\"}', 35.00000000, 38.00000000, '??', 'U+1F1F8 U+1F1FE', '2018-07-20 20:11:03', '2021-07-31 14:20:19', 1, 'Q858'),
(216, 'Taiwan', 'تايوان', 'TWN', 'TW', '886', 'Taipei', 'TWD', '$', '.tw', '臺灣', 'Asia', 'Eastern Asia', '[{\"zoneName\":\"Asia\\/Taipei\",\"gmtOffset\":28800,\"gmtOffsetName\":\"UTC+08:00\",\"abbreviation\":\"CST\",\"tzName\":\"China Standard Time\"}]', '{\"br\":\"Taiwan\",\"pt\":\"Taiwan\",\"nl\":\"Taiwan\",\"hr\":\"Tajvan\",\"fa\":\"تایوان\",\"de\":\"Taiwan\",\"es\":\"Taiwán\",\"fr\":\"Taïwan\",\"ja\":\"台湾（中華民国）\",\"it\":\"Taiwan\"}', 23.50000000, 121.00000000, '??', 'U+1F1F9 U+1F1FC', '2018-07-20 20:11:03', '2021-07-31 14:19:37', 1, 'Q865'),
(217, 'Tajikistan', 'طاجيكستان', 'TJK', 'TJ', '992', 'Dushanbe', 'TJS', 'SM', '.tj', 'Тоҷикистон', 'Asia', 'Central Asia', '[{\"zoneName\":\"Asia\\/Dushanbe\",\"gmtOffset\":18000,\"gmtOffsetName\":\"UTC+05:00\",\"abbreviation\":\"TJT\",\"tzName\":\"Tajikistan Time\"}]', '{\"br\":\"Tajiquistão\",\"pt\":\"Tajiquistão\",\"nl\":\"Tadzjikistan\",\"hr\":\"Tađikistan\",\"fa\":\"تاجیکستان\",\"de\":\"Tadschikistan\",\"es\":\"Tayikistán\",\"fr\":\"Tadjikistan\",\"ja\":\"タジキスタン\",\"it\":\"Tagikistan\"}', 39.00000000, 71.00000000, '??', 'U+1F1F9 U+1F1EF', '2018-07-20 20:11:03', '2021-07-31 14:19:27', 1, 'Q863'),
(218, 'Tanzania', 'تنزانيا', 'TZA', 'TZ', '255', 'Dodoma', 'TZS', 'TSh', '.tz', 'Tanzania', 'Africa', 'Eastern Africa', '[{\"zoneName\":\"Africa\\/Dar_es_Salaam\",\"gmtOffset\":10800,\"gmtOffsetName\":\"UTC+03:00\",\"abbreviation\":\"EAT\",\"tzName\":\"East Africa Time\"}]', '{\"br\":\"Tanzânia\",\"pt\":\"Tanzânia\",\"nl\":\"Tanzania\",\"hr\":\"Tanzanija\",\"fa\":\"تانزانیا\",\"de\":\"Tansania\",\"es\":\"Tanzania\",\"fr\":\"Tanzanie\",\"ja\":\"タンザニア\",\"it\":\"Tanzania\"}', -6.00000000, 35.00000000, '??', 'U+1F1F9 U+1F1FF', '2018-07-20 20:11:03', '2021-07-31 14:19:15', 1, 'Q924'),
(219, 'Thailand', 'تايلاند', 'THA', 'TH', '66', 'Bangkok', 'THB', '฿', '.th', 'ประเทศไทย', 'Asia', 'South-Eastern Asia', '[{\"zoneName\":\"Asia\\/Bangkok\",\"gmtOffset\":25200,\"gmtOffsetName\":\"UTC+07:00\",\"abbreviation\":\"ICT\",\"tzName\":\"Indochina Time\"}]', '{\"br\":\"Tailândia\",\"pt\":\"Tailândia\",\"nl\":\"Thailand\",\"hr\":\"Tajland\",\"fa\":\"تایلند\",\"de\":\"Thailand\",\"es\":\"Tailandia\",\"fr\":\"Thaïlande\",\"ja\":\"タイ\",\"it\":\"Tailandia\"}', 15.00000000, 100.00000000, '??', 'U+1F1F9 U+1F1ED', '2018-07-20 20:11:03', '2021-07-31 14:18:24', 1, 'Q869'),
(220, 'Togo', 'توجو', 'TGO', 'TG', '228', 'Lome', 'XOF', 'CFA', '.tg', 'Togo', 'Africa', 'Western Africa', '[{\"zoneName\":\"Africa\\/Lome\",\"gmtOffset\":0,\"gmtOffsetName\":\"UTC\\u00b100\",\"abbreviation\":\"GMT\",\"tzName\":\"Greenwich Mean Time\"}]', '{\"br\":\"Togo\",\"pt\":\"Togo\",\"nl\":\"Togo\",\"hr\":\"Togo\",\"fa\":\"توگو\",\"de\":\"Togo\",\"es\":\"Togo\",\"fr\":\"Togo\",\"ja\":\"トーゴ\",\"it\":\"Togo\"}', 8.00000000, 1.16666666, '??', 'U+1F1F9 U+1F1EC', '2018-07-20 20:11:03', '2021-07-31 14:18:07', 1, 'Q945'),
(221, 'Tokelau', 'توكيلاو', 'TKL', 'TK', '690', '', 'NZD', '$', '.tk', 'Tokelau', 'Oceania', 'Polynesia', '[{\"zoneName\":\"Pacific\\/Fakaofo\",\"gmtOffset\":46800,\"gmtOffsetName\":\"UTC+13:00\",\"abbreviation\":\"TKT\",\"tzName\":\"Tokelau Time\"}]', '{\"br\":\"Tokelau\",\"pt\":\"Toquelau\",\"nl\":\"Tokelau\",\"hr\":\"Tokelau\",\"fa\":\"توکلائو\",\"de\":\"Tokelau\",\"es\":\"Islas Tokelau\",\"fr\":\"Tokelau\",\"ja\":\"トケラウ\",\"it\":\"Isole Tokelau\"}', -9.00000000, -172.00000000, '??', 'U+1F1F9 U+1F1F0', '2018-07-20 20:11:03', '2021-07-31 14:17:55', 1, NULL),
(222, 'Tonga', 'تونغا', 'TON', 'TO', '676', 'Nuku\'alofa', 'TOP', '$', '.to', 'Tonga', 'Oceania', 'Polynesia', '[{\"zoneName\":\"Pacific\\/Tongatapu\",\"gmtOffset\":46800,\"gmtOffsetName\":\"UTC+13:00\",\"abbreviation\":\"TOT\",\"tzName\":\"Tonga Time\"}]', '{\"br\":\"Tonga\",\"pt\":\"Tonga\",\"nl\":\"Tonga\",\"hr\":\"Tonga\",\"fa\":\"تونگا\",\"de\":\"Tonga\",\"es\":\"Tonga\",\"fr\":\"Tonga\",\"ja\":\"トンガ\",\"it\":\"Tonga\"}', -20.00000000, -175.00000000, '??', 'U+1F1F9 U+1F1F4', '2018-07-20 20:11:03', '2021-07-31 14:17:42', 1, 'Q678'),
(223, 'Trinidad And Tobago', 'ترينداد وتوباغو', 'TTO', 'TT', '+1-868', 'Port of Spain', 'TTD', '$', '.tt', 'Trinidad and Tobago', 'Americas', 'Caribbean', '[{\"zoneName\":\"America\\/Port_of_Spain\",\"gmtOffset\":-14400,\"gmtOffsetName\":\"UTC-04:00\",\"abbreviation\":\"AST\",\"tzName\":\"Atlantic Standard Time\"}]', '{\"br\":\"Trinidad e Tobago\",\"pt\":\"Trindade e Tobago\",\"nl\":\"Trinidad en Tobago\",\"hr\":\"Trinidad i Tobago\",\"fa\":\"ترینیداد و توباگو\",\"de\":\"Trinidad und Tobago\",\"es\":\"Trinidad y Tobago\",\"fr\":\"Trinité et Tobago\",\"ja\":\"トリニダード・トバゴ\",\"it\":\"Trinidad e Tobago\"}', 11.00000000, -61.00000000, '??', 'U+1F1F9 U+1F1F9', '2018-07-20 20:11:03', '2021-07-31 14:17:22', 1, 'Q754'),
(224, 'Tunisia', 'تونس', 'TUN', 'TN', '216', 'Tunis', 'TND', 'ت.د', '.tn', 'تونس', 'Africa', 'Northern Africa', '[{\"zoneName\":\"Africa\\/Tunis\",\"gmtOffset\":3600,\"gmtOffsetName\":\"UTC+01:00\",\"abbreviation\":\"CET\",\"tzName\":\"Central European Time\"}]', '{\"br\":\"Tunísia\",\"pt\":\"Tunísia\",\"nl\":\"Tunesië\",\"hr\":\"Tunis\",\"fa\":\"تونس\",\"de\":\"Tunesien\",\"es\":\"Túnez\",\"fr\":\"Tunisie\",\"ja\":\"チュニジア\",\"it\":\"Tunisia\"}', 34.00000000, 9.00000000, '??', 'U+1F1F9 U+1F1F3', '2018-07-20 20:11:03', '2021-07-31 14:17:06', 1, 'Q948'),
(225, 'Turkey', 'تركيا', 'TUR', 'TR', '90', 'Ankara', 'TRY', '₺', '.tr', 'Türkiye', 'Asia', 'Western Asia', '[{\"zoneName\":\"Europe\\/Istanbul\",\"gmtOffset\":10800,\"gmtOffsetName\":\"UTC+03:00\",\"abbreviation\":\"EET\",\"tzName\":\"Eastern European Time\"}]', '{\"br\":\"Turquia\",\"pt\":\"Turquia\",\"nl\":\"Turkije\",\"hr\":\"Turska\",\"fa\":\"ترکیه\",\"de\":\"Türkei\",\"es\":\"Turquía\",\"fr\":\"Turquie\",\"ja\":\"トルコ\",\"it\":\"Turchia\"}', 39.00000000, 35.00000000, '??', 'U+1F1F9 U+1F1F7', '2018-07-20 20:11:03', '2021-07-31 14:16:50', 1, 'Q43'),
(226, 'Turkmenistan', 'تركمانستان', 'TKM', 'TM', '993', 'Ashgabat', 'TMT', 'T', '.tm', 'Türkmenistan', 'Asia', 'Central Asia', '[{\"zoneName\":\"Asia\\/Ashgabat\",\"gmtOffset\":18000,\"gmtOffsetName\":\"UTC+05:00\",\"abbreviation\":\"TMT\",\"tzName\":\"Turkmenistan Time\"}]', '{\"br\":\"Turcomenistão\",\"pt\":\"Turquemenistão\",\"nl\":\"Turkmenistan\",\"hr\":\"Turkmenistan\",\"fa\":\"ترکمنستان\",\"de\":\"Turkmenistan\",\"es\":\"Turkmenistán\",\"fr\":\"Turkménistan\",\"ja\":\"トルクメニスタン\",\"it\":\"Turkmenistan\"}', 40.00000000, 60.00000000, '??', 'U+1F1F9 U+1F1F2', '2018-07-20 20:11:03', '2021-07-31 13:57:59', 1, 'Q874'),
(227, 'Turks And Caicos Islands', 'جزر تركس وكايكوس', 'TCA', 'TC', '+1-649', 'Cockburn Town', 'USD', '$', '.tc', 'Turks and Caicos Islands', 'Americas', 'Caribbean', '[{\"zoneName\":\"America\\/Grand_Turk\",\"gmtOffset\":-18000,\"gmtOffsetName\":\"UTC-05:00\",\"abbreviation\":\"EST\",\"tzName\":\"Eastern Standard Time (North America\"}]', '{\"br\":\"Ilhas Turcas e Caicos\",\"pt\":\"Ilhas Turcas e Caicos\",\"nl\":\"Turks- en Caicoseilanden\",\"hr\":\"Otoci Turks i Caicos\",\"fa\":\"جزایر تورکس و کایکوس\",\"de\":\"Turks- und Caicosinseln\",\"es\":\"Islas Turks y Caicos\",\"fr\":\"Îles Turques-et-Caïques\",\"ja\":\"タークス・カイコス諸島\",\"it\":\"Isole Turks e Caicos\"}', 21.75000000, -71.58333333, '??', 'U+1F1F9 U+1F1E8', '2018-07-20 20:11:03', '2021-07-31 13:58:04', 1, NULL),
(228, 'Tuvalu', 'توفالو', 'TUV', 'TV', '688', 'Funafuti', 'AUD', '$', '.tv', 'Tuvalu', 'Oceania', 'Polynesia', '[{\"zoneName\":\"Pacific\\/Funafuti\",\"gmtOffset\":43200,\"gmtOffsetName\":\"UTC+12:00\",\"abbreviation\":\"TVT\",\"tzName\":\"Tuvalu Time\"}]', '{\"br\":\"Tuvalu\",\"pt\":\"Tuvalu\",\"nl\":\"Tuvalu\",\"hr\":\"Tuvalu\",\"fa\":\"تووالو\",\"de\":\"Tuvalu\",\"es\":\"Tuvalu\",\"fr\":\"Tuvalu\",\"ja\":\"ツバル\",\"it\":\"Tuvalu\"}', -8.00000000, 178.00000000, '??', 'U+1F1F9 U+1F1FB', '2018-07-20 20:11:03', '2021-07-31 13:58:17', 1, 'Q672'),
(229, 'Uganda', 'أوغندا', 'UGA', 'UG', '256', 'Kampala', 'UGX', 'USh', '.ug', 'Uganda', 'Africa', 'Eastern Africa', '[{\"zoneName\":\"Africa\\/Kampala\",\"gmtOffset\":10800,\"gmtOffsetName\":\"UTC+03:00\",\"abbreviation\":\"EAT\",\"tzName\":\"East Africa Time\"}]', '{\"br\":\"Uganda\",\"pt\":\"Uganda\",\"nl\":\"Oeganda\",\"hr\":\"Uganda\",\"fa\":\"اوگاندا\",\"de\":\"Uganda\",\"es\":\"Uganda\",\"fr\":\"Uganda\",\"ja\":\"ウガンダ\",\"it\":\"Uganda\"}', 1.00000000, 32.00000000, '??', 'U+1F1FA U+1F1EC', '2018-07-20 20:11:03', '2021-07-31 13:58:30', 1, 'Q1036'),
(230, 'Ukraine', 'أوكرانيا', 'UKR', 'UA', '380', 'Kiev', 'UAH', '₴', '.ua', 'Україна', 'Europe', 'Eastern Europe', '[{\"zoneName\":\"Europe\\/Kiev\",\"gmtOffset\":7200,\"gmtOffsetName\":\"UTC+02:00\",\"abbreviation\":\"EET\",\"tzName\":\"Eastern European Time\"},{\"zoneName\":\"Europe\\/Simferopol\",\"gmtOffset\":10800,\"gmtOffsetName\":\"UTC+03:00\",\"abbreviation\":\"MSK\",\"tzName\":\"Moscow Time\"},{\"zoneName\":\"Europe\\/Uzhgorod\",\"gmtOffset\":7200,\"gmtOffsetName\":\"UTC+02:00\",\"abbreviation\":\"EET\",\"tzName\":\"Eastern European Time\"},{\"zoneName\":\"Europe\\/Zaporozhye\",\"gmtOffset\":7200,\"gmtOffsetName\":\"UTC+02:00\",\"abbreviation\":\"EET\",\"tzName\":\"Eastern European Time\"}]', '{\"br\":\"Ucrânia\",\"pt\":\"Ucrânia\",\"nl\":\"Oekraïne\",\"hr\":\"Ukrajina\",\"fa\":\"وکراین\",\"de\":\"Ukraine\",\"es\":\"Ucrania\",\"fr\":\"Ukraine\",\"ja\":\"ウクライナ\",\"it\":\"Ucraina\"}', 49.00000000, 32.00000000, '??', 'U+1F1FA U+1F1E6', '2018-07-20 20:11:03', '2021-07-31 13:58:43', 1, 'Q212'),
(231, 'United Arab Emirates', 'الإمارات العربية المتحدة', 'ARE', 'AE', '971', 'Abu Dhabi', 'AED', 'إ.د', '.ae', 'دولة الإمارات العربية المتحدة', 'Asia', 'Western Asia', '[{\"zoneName\":\"Asia\\/Dubai\",\"gmtOffset\":14400,\"gmtOffsetName\":\"UTC+04:00\",\"abbreviation\":\"GST\",\"tzName\":\"Gulf Standard Time\"}]', '{\"br\":\"Emirados árabes Unidos\",\"pt\":\"Emirados árabes Unidos\",\"nl\":\"Verenigde Arabische Emiraten\",\"hr\":\"Ujedinjeni Arapski Emirati\",\"fa\":\"امارات متحده عربی\",\"de\":\"Vereinigte Arabische Emirate\",\"es\":\"Emiratos Árabes Unidos\",\"fr\":\"Émirats arabes unis\",\"ja\":\"アラブ首長国連邦\",\"it\":\"Emirati Arabi Uniti\"}', 24.00000000, 54.00000000, '??', 'U+1F1E6 U+1F1EA', '2018-07-20 20:11:03', '2021-07-31 13:58:59', 1, 'Q878'),
(232, 'United Kingdom', 'المملكة المتحدة', 'GBR', 'GB', '44', 'London', 'GBP', '£', '.uk', 'United Kingdom', 'Europe', 'Northern Europe', '[{\"zoneName\":\"Europe\\/London\",\"gmtOffset\":0,\"gmtOffsetName\":\"UTC\\u00b100\",\"abbreviation\":\"GMT\",\"tzName\":\"Greenwich Mean Time\"}]', '{\"br\":\"Reino Unido\",\"pt\":\"Reino Unido\",\"nl\":\"Verenigd Koninkrijk\",\"hr\":\"Ujedinjeno Kraljevstvo\",\"fa\":\"بریتانیای کبیر و ایرلند شمالی\",\"de\":\"Vereinigtes Königreich\",\"es\":\"Reino Unido\",\"fr\":\"Royaume-Uni\",\"ja\":\"イギリス\",\"it\":\"Regno Unito\"}', 54.00000000, -2.00000000, '??', 'U+1F1EC U+1F1E7', '2018-07-20 20:11:03', '2021-07-31 13:59:19', 1, 'Q145'),
(233, 'United States', 'الولايات المتحدة الأمريكية', 'USA', 'US', '1', 'Washington', 'USD', '$', '.us', 'United States', 'Americas', 'Northern America', '[{\"zoneName\":\"America\\/Adak\",\"gmtOffset\":-36000,\"gmtOffsetName\":\"UTC-10:00\",\"abbreviation\":\"HST\",\"tzName\":\"Hawaii\\u2013Aleutian Standard Time\"},{\"zoneName\":\"America\\/Anchorage\",\"gmtOffset\":-32400,\"gmtOffsetName\":\"UTC-09:00\",\"abbreviation\":\"AKST\",\"tzName\":\"Alaska Standard Time\"},{\"zoneName\":\"America\\/Boise\",\"gmtOffset\":-25200,\"gmtOffsetName\":\"UTC-07:00\",\"abbreviation\":\"MST\",\"tzName\":\"Mountain Standard Time (North America\"},{\"zoneName\":\"America\\/Chicago\",\"gmtOffset\":-21600,\"gmtOffsetName\":\"UTC-06:00\",\"abbreviation\":\"CST\",\"tzName\":\"Central Standard Time (North America\"},{\"zoneName\":\"America\\/Denver\",\"gmtOffset\":-25200,\"gmtOffsetName\":\"UTC-07:00\",\"abbreviation\":\"MST\",\"tzName\":\"Mountain Standard Time (North America\"},{\"zoneName\":\"America\\/Detroit\",\"gmtOffset\":-18000,\"gmtOffsetName\":\"UTC-05:00\",\"abbreviation\":\"EST\",\"tzName\":\"Eastern Standard Time (North America\"},{\"zoneName\":\"America\\/Indiana\\/Indianapolis\",\"gmtOffset\":-18000,\"gmtOffsetName\":\"UTC-05:00\",\"abbreviation\":\"EST\",\"tzName\":\"Eastern Standard Time (North America\"},{\"zoneName\":\"America\\/Indiana\\/Knox\",\"gmtOffset\":-21600,\"gmtOffsetName\":\"UTC-06:00\",\"abbreviation\":\"CST\",\"tzName\":\"Central Standard Time (North America\"},{\"zoneName\":\"America\\/Indiana\\/Marengo\",\"gmtOffset\":-18000,\"gmtOffsetName\":\"UTC-05:00\",\"abbreviation\":\"EST\",\"tzName\":\"Eastern Standard Time (North America\"},{\"zoneName\":\"America\\/Indiana\\/Petersburg\",\"gmtOffset\":-18000,\"gmtOffsetName\":\"UTC-05:00\",\"abbreviation\":\"EST\",\"tzName\":\"Eastern Standard Time (North America\"},{\"zoneName\":\"America\\/Indiana\\/Tell_City\",\"gmtOffset\":-21600,\"gmtOffsetName\":\"UTC-06:00\",\"abbreviation\":\"CST\",\"tzName\":\"Central Standard Time (North America\"},{\"zoneName\":\"America\\/Indiana\\/Vevay\",\"gmtOffset\":-18000,\"gmtOffsetName\":\"UTC-05:00\",\"abbreviation\":\"EST\",\"tzName\":\"Eastern Standard Time (North America\"},{\"zoneName\":\"America\\/Indiana\\/Vincennes\",\"gmtOffset\":-18000,\"gmtOffsetName\":\"UTC-05:00\",\"abbreviation\":\"EST\",\"tzName\":\"Eastern Standard Time (North America\"},{\"zoneName\":\"America\\/Indiana\\/Winamac\",\"gmtOffset\":-18000,\"gmtOffsetName\":\"UTC-05:00\",\"abbreviation\":\"EST\",\"tzName\":\"Eastern Standard Time (North America\"},{\"zoneName\":\"America\\/Juneau\",\"gmtOffset\":-32400,\"gmtOffsetName\":\"UTC-09:00\",\"abbreviation\":\"AKST\",\"tzName\":\"Alaska Standard Time\"},{\"zoneName\":\"America\\/Kentucky\\/Louisville\",\"gmtOffset\":-18000,\"gmtOffsetName\":\"UTC-05:00\",\"abbreviation\":\"EST\",\"tzName\":\"Eastern Standard Time (North America\"},{\"zoneName\":\"America\\/Kentucky\\/Monticello\",\"gmtOffset\":-18000,\"gmtOffsetName\":\"UTC-05:00\",\"abbreviation\":\"EST\",\"tzName\":\"Eastern Standard Time (North America\"},{\"zoneName\":\"America\\/Los_Angeles\",\"gmtOffset\":-28800,\"gmtOffsetName\":\"UTC-08:00\",\"abbreviation\":\"PST\",\"tzName\":\"Pacific Standard Time (North America\"},{\"zoneName\":\"America\\/Menominee\",\"gmtOffset\":-21600,\"gmtOffsetName\":\"UTC-06:00\",\"abbreviation\":\"CST\",\"tzName\":\"Central Standard Time (North America\"},{\"zoneName\":\"America\\/Metlakatla\",\"gmtOffset\":-32400,\"gmtOffsetName\":\"UTC-09:00\",\"abbreviation\":\"AKST\",\"tzName\":\"Alaska Standard Time\"},{\"zoneName\":\"America\\/New_York\",\"gmtOffset\":-18000,\"gmtOffsetName\":\"UTC-05:00\",\"abbreviation\":\"EST\",\"tzName\":\"Eastern Standard Time (North America\"},{\"zoneName\":\"America\\/Nome\",\"gmtOffset\":-32400,\"gmtOffsetName\":\"UTC-09:00\",\"abbreviation\":\"AKST\",\"tzName\":\"Alaska Standard Time\"},{\"zoneName\":\"America\\/North_Dakota\\/Beulah\",\"gmtOffset\":-21600,\"gmtOffsetName\":\"UTC-06:00\",\"abbreviation\":\"CST\",\"tzName\":\"Central Standard Time (North America\"},{\"zoneName\":\"America\\/North_Dakota\\/Center\",\"gmtOffset\":-21600,\"gmtOffsetName\":\"UTC-06:00\",\"abbreviation\":\"CST\",\"tzName\":\"Central Standard Time (North America\"},{\"zoneName\":\"America\\/North_Dakota\\/New_Salem\",\"gmtOffset\":-21600,\"gmtOffsetName\":\"UTC-06:00\",\"abbreviation\":\"CST\",\"tzName\":\"Central Standard Time (North America\"},{\"zoneName\":\"America\\/Phoenix\",\"gmtOffset\":-25200,\"gmtOffsetName\":\"UTC-07:00\",\"abbreviation\":\"MST\",\"tzName\":\"Mountain Standard Time (North America\"},{\"zoneName\":\"America\\/Sitka\",\"gmtOffset\":-32400,\"gmtOffsetName\":\"UTC-09:00\",\"abbreviation\":\"AKST\",\"tzName\":\"Alaska Standard Time\"},{\"zoneName\":\"America\\/Yakutat\",\"gmtOffset\":-32400,\"gmtOffsetName\":\"UTC-09:00\",\"abbreviation\":\"AKST\",\"tzName\":\"Alaska Standard Time\"},{\"zoneName\":\"Pacific\\/Honolulu\",\"gmtOffset\":-36000,\"gmtOffsetName\":\"UTC-10:00\",\"abbreviation\":\"HST\",\"tzName\":\"Hawaii\\u2013Aleutian Standard Time\"}]', '{\"br\":\"Estados Unidos\",\"pt\":\"Estados Unidos\",\"nl\":\"Verenigde Staten\",\"hr\":\"Sjedinjene Američke Države\",\"fa\":\"ایالات متحده آمریکا\",\"de\":\"Vereinigte Staaten von Amerika\",\"es\":\"Estados Unidos\",\"fr\":\"États-Unis\",\"ja\":\"アメリカ合衆国\",\"it\":\"Stati Uniti D\'America\"}', 38.00000000, -97.00000000, '??', 'U+1F1FA U+1F1F8', '2018-07-20 20:11:03', '2021-07-31 13:59:32', 1, 'Q30'),
(234, 'United States Minor Outlying Islands', 'جزر الولايات المتحدة البعيدة الصغرى', 'UMI', 'UM', '1', '', 'USD', '$', '.us', 'United States Minor Outlying Islands', 'Americas', 'Northern America', '[{\"zoneName\":\"Pacific\\/Midway\",\"gmtOffset\":-39600,\"gmtOffsetName\":\"UTC-11:00\",\"abbreviation\":\"SST\",\"tzName\":\"Samoa Standard Time\"},{\"zoneName\":\"Pacific\\/Wake\",\"gmtOffset\":43200,\"gmtOffsetName\":\"UTC+12:00\",\"abbreviation\":\"WAKT\",\"tzName\":\"Wake Island Time\"}]', '{\"br\":\"Ilhas Menores Distantes dos Estados Unidos\",\"pt\":\"Ilhas Menores Distantes dos Estados Unidos\",\"nl\":\"Kleine afgelegen eilanden van de Verenigde Staten\",\"hr\":\"Mali udaljeni otoci SAD-a\",\"fa\":\"جزایر کوچک حاشیه‌ای ایالات متحده آمریکا\",\"de\":\"Kleinere Inselbesitzungen der Vereinigten Staaten\",\"es\":\"Islas Ultramarinas Menores de Estados Unidos\",\"fr\":\"Îles mineures éloignées des États-Unis\",\"ja\":\"合衆国領有小離島\",\"it\":\"Isole minori esterne degli Stati Uniti d\'America\"}', 0.00000000, 0.00000000, '??', 'U+1F1FA U+1F1F2', '2018-07-20 20:11:03', '2021-07-31 13:59:50', 1, NULL),
(235, 'Uruguay', 'أوروغواي', 'URY', 'UY', '598', 'Montevideo', 'UYU', '$', '.uy', 'Uruguay', 'Americas', 'South America', '[{\"zoneName\":\"America\\/Montevideo\",\"gmtOffset\":-10800,\"gmtOffsetName\":\"UTC-03:00\",\"abbreviation\":\"UYT\",\"tzName\":\"Uruguay Standard Time\"}]', '{\"br\":\"Uruguai\",\"pt\":\"Uruguai\",\"nl\":\"Uruguay\",\"hr\":\"Urugvaj\",\"fa\":\"اروگوئه\",\"de\":\"Uruguay\",\"es\":\"Uruguay\",\"fr\":\"Uruguay\",\"ja\":\"ウルグアイ\",\"it\":\"Uruguay\"}', -33.00000000, -56.00000000, '??', 'U+1F1FA U+1F1FE', '2018-07-20 20:11:03', '2021-07-31 14:00:08', 1, 'Q77'),
(236, 'Uzbekistan', 'أوزبكستان', 'UZB', 'UZ', '998', 'Tashkent', 'UZS', 'лв', '.uz', 'O‘zbekiston', 'Asia', 'Central Asia', '[{\"zoneName\":\"Asia\\/Samarkand\",\"gmtOffset\":18000,\"gmtOffsetName\":\"UTC+05:00\",\"abbreviation\":\"UZT\",\"tzName\":\"Uzbekistan Time\"},{\"zoneName\":\"Asia\\/Tashkent\",\"gmtOffset\":18000,\"gmtOffsetName\":\"UTC+05:00\",\"abbreviation\":\"UZT\",\"tzName\":\"Uzbekistan Time\"}]', '{\"br\":\"Uzbequistão\",\"pt\":\"Usbequistão\",\"nl\":\"Oezbekistan\",\"hr\":\"Uzbekistan\",\"fa\":\"ازبکستان\",\"de\":\"Usbekistan\",\"es\":\"Uzbekistán\",\"fr\":\"Ouzbékistan\",\"ja\":\"ウズベキスタン\",\"it\":\"Uzbekistan\"}', 41.00000000, 64.00000000, '??', 'U+1F1FA U+1F1FF', '2018-07-20 20:11:03', '2021-07-31 14:00:20', 1, 'Q265'),
(237, 'Vanuatu', 'فانواتو', 'VUT', 'VU', '678', 'Port Vila', 'VUV', 'VT', '.vu', 'Vanuatu', 'Oceania', 'Melanesia', '[{\"zoneName\":\"Pacific\\/Efate\",\"gmtOffset\":39600,\"gmtOffsetName\":\"UTC+11:00\",\"abbreviation\":\"VUT\",\"tzName\":\"Vanuatu Time\"}]', '{\"br\":\"Vanuatu\",\"pt\":\"Vanuatu\",\"nl\":\"Vanuatu\",\"hr\":\"Vanuatu\",\"fa\":\"وانواتو\",\"de\":\"Vanuatu\",\"es\":\"Vanuatu\",\"fr\":\"Vanuatu\",\"ja\":\"バヌアツ\",\"it\":\"Vanuatu\"}', -16.00000000, 167.00000000, '??', 'U+1F1FB U+1F1FA', '2018-07-20 20:11:03', '2021-07-31 14:00:34', 1, 'Q686'),
(238, 'Vatican City State (Holy See)', 'دولة الفاتيكان (الكرسي الرسولي)', 'VAT', 'VA', '379', 'Vatican City', 'EUR', '€', '.va', 'Vaticano', 'Europe', 'Southern Europe', '[{\"zoneName\":\"Europe\\/Vatican\",\"gmtOffset\":3600,\"gmtOffsetName\":\"UTC+01:00\",\"abbreviation\":\"CET\",\"tzName\":\"Central European Time\"}]', '{\"br\":\"Vaticano\",\"pt\":\"Vaticano\",\"nl\":\"Heilige Stoel\",\"hr\":\"Sveta Stolica\",\"fa\":\"سریر مقدس\",\"de\":\"Heiliger Stuhl\",\"es\":\"Santa Sede\",\"fr\":\"voir Saint\",\"ja\":\"聖座\",\"it\":\"Santa Sede\"}', 41.90000000, 12.45000000, '??', 'U+1F1FB U+1F1E6', '2018-07-20 20:11:03', '2021-07-31 14:04:37', 1, 'Q237'),
(239, 'Venezuela', 'فنزويلا', 'VEN', 'VE', '58', 'Caracas', 'VEF', 'Bs', '.ve', 'Venezuela', 'Americas', 'South America', '[{\"zoneName\":\"America\\/Caracas\",\"gmtOffset\":-14400,\"gmtOffsetName\":\"UTC-04:00\",\"abbreviation\":\"VET\",\"tzName\":\"Venezuelan Standard Time\"}]', '{\"br\":\"Venezuela\",\"pt\":\"Venezuela\",\"nl\":\"Venezuela\",\"hr\":\"Venezuela\",\"fa\":\"ونزوئلا\",\"de\":\"Venezuela\",\"es\":\"Venezuela\",\"fr\":\"Venezuela\",\"ja\":\"ベネズエラ・ボリバル共和国\",\"it\":\"Venezuela\"}', 8.00000000, -66.00000000, '??', 'U+1F1FB U+1F1EA', '2018-07-20 20:11:03', '2021-07-31 14:04:49', 1, 'Q717'),
(240, 'Vietnam', 'فيتنام', 'VNM', 'VN', '84', 'Hanoi', 'VND', '₫', '.vn', 'Việt Nam', 'Asia', 'South-Eastern Asia', '[{\"zoneName\":\"Asia\\/Ho_Chi_Minh\",\"gmtOffset\":25200,\"gmtOffsetName\":\"UTC+07:00\",\"abbreviation\":\"ICT\",\"tzName\":\"Indochina Time\"}]', '{\"br\":\"Vietnã\",\"pt\":\"Vietname\",\"nl\":\"Vietnam\",\"hr\":\"Vijetnam\",\"fa\":\"ویتنام\",\"de\":\"Vietnam\",\"es\":\"Vietnam\",\"fr\":\"Viêt Nam\",\"ja\":\"ベトナム\",\"it\":\"Vietnam\"}', 16.16666666, 107.83333333, '??', 'U+1F1FB U+1F1F3', '2018-07-20 20:11:03', '2021-07-31 14:05:14', 1, 'Q881'),
(241, 'Virgin Islands (British)', 'جزر العذراء البريطانية)', 'VGB', 'VG', '+1-284', 'Road Town', 'USD', '$', '.vg', 'British Virgin Islands', 'Americas', 'Caribbean', '[{\"zoneName\":\"America\\/Tortola\",\"gmtOffset\":-14400,\"gmtOffsetName\":\"UTC-04:00\",\"abbreviation\":\"AST\",\"tzName\":\"Atlantic Standard Time\"}]', '{\"br\":\"Ilhas Virgens Britânicas\",\"pt\":\"Ilhas Virgens Britânicas\",\"nl\":\"Britse Maagdeneilanden\",\"hr\":\"Britanski Djevičanski Otoci\",\"fa\":\"جزایر ویرجین بریتانیا\",\"de\":\"Britische Jungferninseln\",\"es\":\"Islas Vírgenes del Reino Unido\",\"fr\":\"Îles Vierges britanniques\",\"ja\":\"イギリス領ヴァージン諸島\",\"it\":\"Isole Vergini Britanniche\"}', 18.43138300, -64.62305000, '??', 'U+1F1FB U+1F1EC', '2018-07-20 20:11:03', '2021-07-31 14:06:51', 1, NULL),
(242, 'Virgin Islands (US)', 'جزر فيرجن (الولايات المتحدة)', 'VIR', 'VI', '+1-340', 'Charlotte Amalie', 'USD', '$', '.vi', 'United States Virgin Islands', 'Americas', 'Caribbean', '[{\"zoneName\":\"America\\/St_Thomas\",\"gmtOffset\":-14400,\"gmtOffsetName\":\"UTC-04:00\",\"abbreviation\":\"AST\",\"tzName\":\"Atlantic Standard Time\"}]', '{\"br\":\"Ilhas Virgens Americanas\",\"pt\":\"Ilhas Virgens Americanas\",\"nl\":\"Verenigde Staten Maagdeneilanden\",\"fa\":\"جزایر ویرجین آمریکا\",\"de\":\"Amerikanische Jungferninseln\",\"es\":\"Islas Vírgenes de los Estados Unidos\",\"fr\":\"Îles Vierges des États-Unis\",\"ja\":\"アメリカ領ヴァージン諸島\",\"it\":\"Isole Vergini americane\"}', 18.34000000, -64.93000000, '??', 'U+1F1FB U+1F1EE', '2018-07-20 20:11:03', '2021-07-31 14:06:57', 1, NULL),
(243, 'Wallis And Futuna Islands', 'جزر واليس وفوتونا', 'WLF', 'WF', '681', 'Mata Utu', 'XPF', '₣', '.wf', 'Wallis et Futuna', 'Oceania', 'Polynesia', '[{\"zoneName\":\"Pacific\\/Wallis\",\"gmtOffset\":43200,\"gmtOffsetName\":\"UTC+12:00\",\"abbreviation\":\"WFT\",\"tzName\":\"Wallis & Futuna Time\"}]', '{\"br\":\"Wallis e Futuna\",\"pt\":\"Wallis e Futuna\",\"nl\":\"Wallis en Futuna\",\"hr\":\"Wallis i Fortuna\",\"fa\":\"والیس و فوتونا\",\"de\":\"Wallis und Futuna\",\"es\":\"Wallis y Futuna\",\"fr\":\"Wallis-et-Futuna\",\"ja\":\"ウォリス・フツナ\",\"it\":\"Wallis e Futuna\"}', -13.30000000, -176.20000000, '??', 'U+1F1FC U+1F1EB', '2018-07-20 20:11:03', '2021-07-31 14:07:17', 1, NULL),
(244, 'Western Sahara', 'الصحراء الغربية', 'ESH', 'EH', '212', 'El-Aaiun', 'MAD', 'MAD', '.eh', 'الصحراء الغربية', 'Africa', 'Northern Africa', '[{\"zoneName\":\"Africa\\/El_Aaiun\",\"gmtOffset\":3600,\"gmtOffsetName\":\"UTC+01:00\",\"abbreviation\":\"WEST\",\"tzName\":\"Western European Summer Time\"}]', '{\"br\":\"Saara Ocidental\",\"pt\":\"Saara Ocidental\",\"nl\":\"Westelijke Sahara\",\"hr\":\"Zapadna Sahara\",\"fa\":\"جمهوری دموکراتیک عربی صحرا\",\"de\":\"Westsahara\",\"es\":\"Sahara Occidental\",\"fr\":\"Sahara Occidental\",\"ja\":\"西サハラ\",\"it\":\"Sahara Occidentale\"}', 24.50000000, -13.00000000, '??', 'U+1F1EA U+1F1ED', '2018-07-20 20:11:03', '2021-07-31 14:07:36', 1, NULL),
(245, 'Yemen', 'اليمن', 'YEM', 'YE', '967', 'Sanaa', 'YER', '﷼', '.ye', 'اليَمَن', 'Asia', 'Western Asia', '[{\"zoneName\":\"Asia\\/Aden\",\"gmtOffset\":10800,\"gmtOffsetName\":\"UTC+03:00\",\"abbreviation\":\"AST\",\"tzName\":\"Arabia Standard Time\"}]', '{\"br\":\"Iêmen\",\"pt\":\"Iémen\",\"nl\":\"Jemen\",\"hr\":\"Jemen\",\"fa\":\"یمن\",\"de\":\"Jemen\",\"es\":\"Yemen\",\"fr\":\"Yémen\",\"ja\":\"イエメン\",\"it\":\"Yemen\"}', 15.00000000, 48.00000000, '??', 'U+1F1FE U+1F1EA', '2018-07-20 20:11:03', '2021-07-31 14:07:51', 1, 'Q805'),
(246, 'Zambia', 'زامبيا', 'ZMB', 'ZM', '260', 'Lusaka', 'ZMW', 'ZK', '.zm', 'Zambia', 'Africa', 'Eastern Africa', '[{\"zoneName\":\"Africa\\/Lusaka\",\"gmtOffset\":7200,\"gmtOffsetName\":\"UTC+02:00\",\"abbreviation\":\"CAT\",\"tzName\":\"Central Africa Time\"}]', '{\"br\":\"Zâmbia\",\"pt\":\"Zâmbia\",\"nl\":\"Zambia\",\"hr\":\"Zambija\",\"fa\":\"زامبیا\",\"de\":\"Sambia\",\"es\":\"Zambia\",\"fr\":\"Zambie\",\"ja\":\"ザンビア\",\"it\":\"Zambia\"}', -15.00000000, 30.00000000, '??', 'U+1F1FF U+1F1F2', '2018-07-20 20:11:03', '2021-07-31 14:08:07', 1, 'Q953'),
(247, 'Zimbabwe', 'زيمبابوي', 'ZWE', 'ZW', '263', 'Harare', 'ZWL', '$', '.zw', 'Zimbabwe', 'Africa', 'Eastern Africa', '[{\"zoneName\":\"Africa\\/Harare\",\"gmtOffset\":7200,\"gmtOffsetName\":\"UTC+02:00\",\"abbreviation\":\"CAT\",\"tzName\":\"Central Africa Time\"}]', '{\"br\":\"Zimbabwe\",\"pt\":\"Zimbabué\",\"nl\":\"Zimbabwe\",\"hr\":\"Zimbabve\",\"fa\":\"زیمباوه\",\"de\":\"Simbabwe\",\"es\":\"Zimbabue\",\"fr\":\"Zimbabwe\",\"ja\":\"ジンバブエ\",\"it\":\"Zimbabwe\"}', -20.00000000, 30.00000000, '??', 'U+1F1FF U+1F1FC', '2018-07-20 20:11:03', '2021-07-31 14:08:33', 1, 'Q954'),
(248, 'Kosovo', 'كوسوفو', 'XKX', 'XK', '383', 'Pristina', 'EUR', '€', '.xk', 'Republika e Kosovës', 'Europe', 'Eastern Europe', '[{\"zoneName\":\"Europe\\/Belgrade\",\"gmtOffset\":3600,\"gmtOffsetName\":\"UTC+01:00\",\"abbreviation\":\"CET\",\"tzName\":\"Central European Time\"}]', NULL, 42.56129090, 20.34030350, '??', 'U+1F1FD U+1F1F0', '2020-08-15 15:33:50', '2021-07-31 14:08:51', 1, 'Q1246'),
(249, 'Curaçao', 'كوراساو', 'CUW', 'CW', '599', 'Willemstad', 'ANG', 'ƒ', '.cw', 'Curaçao', 'Americas', 'Caribbean', '[{\"zoneName\":\"America\\/Curacao\",\"gmtOffset\":-14400,\"gmtOffsetName\":\"UTC-04:00\",\"abbreviation\":\"AST\",\"tzName\":\"Atlantic Standard Time\"}]', '{\"br\":\"Curaçao\",\"pt\":\"Curaçao\",\"nl\":\"Curaçao\",\"fa\":\"کوراسائو\",\"de\":\"Curaçao\",\"fr\":\"Curaçao\",\"it\":\"Curaçao\"}', 12.11666700, -68.93333300, '??', 'U+1F1E8 U+1F1FC', '2020-10-25 14:54:20', '2021-07-31 14:09:06', 1, 'Q25279'),
(250, 'Sint Maarten (Dutch part)', 'سينت مارتن (الجزء الهولندي)', 'SXM', 'SX', '1721', 'Philipsburg', 'ANG', 'ƒ', '.sx', 'Sint Maarten', 'Americas', 'Caribbean', '[{\"zoneName\":\"America\\/Anguilla\",\"gmtOffset\":-14400,\"gmtOffsetName\":\"UTC-04:00\",\"abbreviation\":\"AST\",\"tzName\":\"Atlantic Standard Time\"}]', '{\"br\":\"Sint Maarten\",\"pt\":\"São Martinho\",\"nl\":\"Sint Maarten\",\"fa\":\"سینت مارتن\",\"de\":\"Sint Maarten (niederl. Teil)\",\"fr\":\"Saint Martin (partie néerlandaise)\",\"it\":\"Saint Martin (parte olandese)\"}', 18.03333300, -63.05000000, '??', 'U+1F1F8 U+1F1FD', '2020-12-05 13:03:39', '2021-07-31 14:09:22', 1, 'Q26273');

-- --------------------------------------------------------

--
-- Table structure for table `courses`
--

CREATE TABLE `courses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `course_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `course_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `course_level` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `field_of_study` int(255) NOT NULL,
  `subject_id` int(255) NOT NULL,
  `teacher_id` int(255) DEFAULT NULL,
  `student_id` int(11) DEFAULT NULL,
  `start_date` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `end_date` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `language_id` int(11) NOT NULL,
  `book_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `book_info` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `book_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `book_edition` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `book_author` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `total_price` double(8,2) NOT NULL,
  `country_id` int(11) DEFAULT NULL,
  `program_id` int(11) NOT NULL,
  `total_hours` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_classes` int(11) NOT NULL,
  `weekdays` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `files` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `courses`
--

INSERT INTO `courses` (`id`, `course_code`, `course_name`, `course_level`, `field_of_study`, `subject_id`, `teacher_id`, `student_id`, `start_date`, `end_date`, `language_id`, `book_type`, `book_info`, `book_name`, `book_edition`, `book_author`, `total_price`, `country_id`, `program_id`, `total_hours`, `total_classes`, `weekdays`, `files`, `status`, `created_at`, `updated_at`) VALUES
(14, 'IB-Phi-1', 'Philosophy0001', 'level1', 1, 2, 1149, 1135, NULL, NULL, 19, NULL, '2', NULL, NULL, NULL, 250.00, 1, 2, '2', 4, '1,2,3', '', NULL, '2022-03-01 02:15:52', '2022-03-01 02:15:53'),
(15, 'IB-Phi-2', 'Philosophy0001', 'level1', 1, 2, 1149, 1135, NULL, NULL, 19, NULL, '2', NULL, NULL, NULL, 250.00, NULL, 2, '2', 4, '1,2,3', '', NULL, '2022-03-01 06:31:12', '2022-03-01 06:31:13'),
(16, 'IB-Phi-3', 'Philosophy0001', 'level1', 3, 2, 1149, 1135, NULL, NULL, 19, NULL, '2', NULL, NULL, NULL, 250.00, NULL, 2, '2', 4, '1,2,3', '', NULL, '2022-03-01 06:35:28', '2022-03-01 06:35:28'),
(17, 'IB-Phi-4', 'Philosophy0001', 'level1', 3, 2, 1127, 1135, NULL, NULL, 19, NULL, '2', NULL, NULL, NULL, 250.00, NULL, 2, '2', 4, '1,2,3', '', NULL, '2022-03-01 06:35:55', '2022-03-01 06:35:55');

-- --------------------------------------------------------

--
-- Table structure for table `course_levels`
--

CREATE TABLE `course_levels` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `course_levels`
--

INSERT INTO `course_levels` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'School', NULL, NULL),
(2, 'College', NULL, NULL),
(3, 'University', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `faqs`
--

CREATE TABLE `faqs` (
  `id` int(10) UNSIGNED NOT NULL,
  `topic_id` int(10) UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `answer` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `faqs`
--

INSERT INTO `faqs` (`id`, `topic_id`, `title`, `answer`, `created_at`, `updated_at`) VALUES
(1, 1, 'How much do the course cost', 'The cost of our courses varies depending on the type of the duration of course', NULL, NULL),
(2, 2, 'Are the university courses accredited?', 'The cost of our courses varies depending on the type of the duration of course', NULL, NULL),
(3, 3, 'How often do the course meet? ', 'The cost of our courses varies depending on the type of the duration of course', NULL, NULL),
(4, 4, 'I want to study a language, but I have no previous knowledge. Is that ok?', 'The cost of our courses varies depending on the type of the duration of course', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `faq_topics`
--

CREATE TABLE `faq_topics` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `faq_topics`
--

INSERT INTO `faq_topics` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'About Students', NULL, NULL),
(2, 'About Tutors', NULL, NULL),
(3, 'About Technology', NULL, NULL),
(4, 'About System', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

CREATE TABLE `feedback` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `feedback`
--

INSERT INTO `feedback` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'Expert in the subject', '2022-03-02 06:22:46', '2022-03-02 06:22:46'),
(2, 'Present Complex Topics clearly and easily', '2022-03-02 06:22:46', '2022-03-02 06:22:46'),
(3, 'Skillfull in engaging students', '2022-03-02 06:23:24', '2022-03-02 06:23:24'),
(4, 'Always on time', '2022-03-02 06:23:24', '2022-03-02 06:23:24'),
(5, 'Student Behaviour', '2022-03-02 06:24:09', '2022-03-02 06:24:09'),
(6, 'Student Performance', '2022-03-02 06:24:23', '2022-03-02 06:24:23');

-- --------------------------------------------------------

--
-- Table structure for table `field_of_studies`
--

CREATE TABLE `field_of_studies` (
  `id` int(10) UNSIGNED NOT NULL,
  `program_id` int(11) DEFAULT NULL,
  `country_id` int(11) DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `field_of_studies`
--

INSERT INTO `field_of_studies` (`id`, `program_id`, `country_id`, `name`, `created_at`, `updated_at`) VALUES
(1, 1, NULL, 'Humanities', NULL, NULL),
(2, 1, NULL, 'Science', NULL, NULL),
(3, 1, NULL, 'Engineering', NULL, NULL),
(4, 1, NULL, 'Math', NULL, NULL),
(5, 2, NULL, 'Humanities', NULL, NULL),
(6, 2, NULL, 'Science', NULL, NULL),
(7, 2, NULL, 'Engineering', NULL, NULL),
(8, 2, NULL, 'Math', NULL, NULL),
(9, 3, 1, 'Humanities', NULL, NULL),
(10, 3, 1, 'Science', NULL, NULL),
(11, 3, 1, 'Engineering', NULL, NULL),
(12, 3, 1, 'Math', NULL, NULL),
(13, 3, 2, 'Science', NULL, NULL),
(14, 3, 2, 'Engineering', NULL, NULL),
(15, 3, 2, 'Math', NULL, NULL),
(16, 3, 3, 'Humanities', NULL, NULL),
(17, 3, 3, 'Science', NULL, NULL),
(18, 3, 3, 'Engineering', NULL, NULL),
(19, 3, 3, 'Math', NULL, NULL),
(20, 3, 4, 'Humanities', NULL, NULL),
(21, 3, 4, 'Science', NULL, NULL),
(22, 3, 4, 'Engineering', NULL, NULL),
(23, 3, 4, 'Math', NULL, NULL),
(24, 3, 5, 'Humanities', NULL, NULL),
(25, 3, 5, 'Science', NULL, NULL),
(26, 3, 5, 'Engineering', NULL, NULL),
(27, 3, 5, 'Math', NULL, NULL),
(28, 3, 6, 'Humanities', NULL, NULL),
(29, 3, 6, 'Science', NULL, NULL),
(30, 3, 6, 'Engineering', NULL, NULL),
(31, 3, 6, 'Math', NULL, NULL),
(32, 3, 7, 'Humanities', NULL, NULL),
(33, 3, 7, 'Science', NULL, NULL),
(34, 3, 7, 'Engineering', NULL, NULL),
(35, 3, 7, 'Math', NULL, NULL),
(36, 3, 8, 'Humanities', NULL, NULL),
(37, 3, 8, 'Science', NULL, NULL),
(38, 3, 8, 'Engineering', NULL, NULL),
(39, 3, 8, 'Math', NULL, NULL),
(40, 3, 9, 'Humanities', NULL, NULL),
(41, 3, 9, 'Science', NULL, NULL),
(42, 3, 9, 'Engineering', NULL, NULL),
(43, 3, 9, 'Math', NULL, NULL),
(44, 3, 10, 'Humanities', NULL, NULL),
(45, 3, 10, 'Science', NULL, NULL),
(46, 3, 10, 'Engineering', NULL, NULL),
(47, 3, 10, 'Math', NULL, NULL),
(48, 3, 11, 'Humanities', NULL, NULL),
(49, 3, 11, 'Science', NULL, NULL),
(50, 3, 11, 'Engineering', NULL, NULL),
(51, 3, 11, 'Math', NULL, NULL),
(52, 3, 12, 'Humanities', NULL, NULL),
(53, 3, 12, 'Science', NULL, NULL),
(54, 3, 12, 'Engineering', NULL, NULL),
(55, 3, 12, 'Math', NULL, NULL),
(56, 3, 13, 'Humanities', NULL, NULL),
(57, 3, 13, 'Science', NULL, NULL),
(58, 3, 13, 'Engineering', NULL, NULL),
(59, 3, 13, 'Math', NULL, NULL),
(60, 3, 14, 'Humanities', NULL, NULL),
(61, 3, 14, 'Science', NULL, NULL),
(62, 3, 14, 'Engineering', NULL, NULL),
(63, 3, 14, 'Math', NULL, NULL),
(64, 3, 15, 'Humanities', NULL, NULL),
(65, 3, 15, 'Science', NULL, NULL),
(66, 3, 15, 'Engineering', NULL, NULL),
(67, 3, 15, 'Math', NULL, NULL),
(68, 3, 16, 'Humanities', NULL, NULL),
(69, 3, 16, 'Science', NULL, NULL),
(70, 3, 16, 'Engineering', NULL, NULL),
(71, 3, 16, 'Math', NULL, NULL),
(72, 3, 17, 'Humanities', NULL, NULL),
(73, 3, 17, 'Science', NULL, NULL),
(74, 3, 17, 'Engineering', NULL, NULL),
(75, 3, 17, 'Math', NULL, NULL),
(76, 3, 18, 'Humanities', NULL, NULL),
(77, 3, 18, 'Science', NULL, NULL),
(78, 3, 18, 'Engineering', NULL, NULL),
(79, 3, 18, 'Math', NULL, NULL),
(80, 3, 19, 'Humanities', NULL, NULL),
(81, 3, 19, 'Science', NULL, NULL),
(82, 3, 19, 'Engineering', NULL, NULL),
(83, 3, 19, 'Math', NULL, NULL),
(84, 3, 20, 'Humanities', NULL, NULL),
(85, 3, 20, 'Science', NULL, NULL),
(86, 3, 20, 'Engineering', NULL, NULL),
(87, 3, 20, 'Math', NULL, NULL),
(88, 3, 21, 'Humanities', NULL, NULL),
(89, 3, 21, 'Science', NULL, NULL),
(90, 3, 21, 'Engineering', NULL, NULL),
(91, 3, 21, 'Math', NULL, NULL),
(92, 3, 22, 'Humanities', NULL, NULL),
(93, 3, 22, 'Science', NULL, NULL),
(94, 3, 22, 'Engineering', NULL, NULL),
(95, 3, 22, 'Math', NULL, NULL),
(96, 3, 23, 'Humanities', NULL, NULL),
(97, 3, 23, 'Science', NULL, NULL),
(98, 3, 23, 'Engineering', NULL, NULL),
(99, 3, 23, 'Math', NULL, NULL),
(100, 3, 111, 'Humanities', NULL, NULL),
(101, 3, 111, 'Science', NULL, NULL),
(102, 3, 111, 'Engineering', NULL, NULL),
(103, 3, 111, 'Math', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `languages`
--

CREATE TABLE `languages` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` char(49) CHARACTER SET utf8 DEFAULT NULL,
  `iso` char(2) CHARACTER SET utf8 DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `languages`
--

INSERT INTO `languages` (`id`, `name`, `iso`) VALUES
(1, 'English', 'en'),
(2, 'Afar', 'aa'),
(3, 'Abkhazian', 'ab'),
(4, 'Afrikaans', 'af'),
(5, 'Amharic', 'am'),
(6, 'Arabic', 'ar'),
(7, 'Assamese', 'as'),
(8, 'Aymara', 'ay'),
(9, 'Azerbaijani', 'az'),
(10, 'Bashkir', 'ba'),
(11, 'Belarusian', 'be'),
(12, 'Bulgarian', 'bg'),
(13, 'Bihari', 'bh'),
(14, 'Bislama', 'bi'),
(15, 'Bengali/Bangla', 'bn'),
(16, 'Tibetan', 'bo'),
(17, 'Breton', 'br'),
(18, 'Catalan', 'ca'),
(19, 'Corsican', 'co'),
(20, 'Czech', 'cs'),
(21, 'Welsh', 'cy'),
(22, 'Danish', 'da'),
(23, 'German', 'de'),
(24, 'Bhutani', 'dz'),
(25, 'Greek', 'el'),
(26, 'Esperanto', 'eo'),
(27, 'Spanish', 'es'),
(28, 'Estonian', 'et'),
(29, 'Basque', 'eu'),
(30, 'Persian', 'fa'),
(31, 'Finnish', 'fi'),
(32, 'Fiji', 'fj'),
(33, 'Faeroese', 'fo'),
(34, 'French', 'fr'),
(35, 'Frisian', 'fy'),
(36, 'Irish', 'ga'),
(37, 'Scots/Gaelic', 'gd'),
(38, 'Galician', 'gl'),
(39, 'Guarani', 'gn'),
(40, 'Gujarati', 'gu'),
(41, 'Hausa', 'ha'),
(42, 'Hindi', 'hi'),
(43, 'Croatian', 'hr'),
(44, 'Hungarian', 'hu'),
(45, 'Armenian', 'hy'),
(46, 'Interlingua', 'ia'),
(47, 'Interlingue', 'ie'),
(48, 'Inupiak', 'ik'),
(49, 'Indonesian', 'in'),
(50, 'Icelandic', 'is'),
(51, 'Italian', 'it'),
(52, 'Hebrew', 'iw'),
(53, 'Japanese', 'ja'),
(54, 'Yiddish', 'ji'),
(55, 'Javanese', 'jw'),
(56, 'Georgian', 'ka'),
(57, 'Kazakh', 'kk'),
(58, 'Greenlandic', 'kl'),
(59, 'Cambodian', 'km'),
(60, 'Kannada', 'kn'),
(61, 'Korean', 'ko'),
(62, 'Kashmiri', 'ks'),
(63, 'Kurdish', 'ku'),
(64, 'Kirghiz', 'ky'),
(65, 'Latin', 'la'),
(66, 'Lingala', 'ln'),
(67, 'Laothian', 'lo'),
(68, 'Lithuanian', 'lt'),
(69, 'Latvian/Lettish', 'lv'),
(70, 'Malagasy', 'mg'),
(71, 'Maori', 'mi'),
(72, 'Macedonian', 'mk'),
(73, 'Malayalam', 'ml'),
(74, 'Mongolian', 'mn'),
(75, 'Moldavian', 'mo'),
(76, 'Marathi', 'mr'),
(77, 'Malay', 'ms'),
(78, 'Maltese', 'mt'),
(79, 'Burmese', 'my'),
(80, 'Nauru', 'na'),
(81, 'Nepali', 'ne'),
(82, 'Dutch', 'nl'),
(83, 'Norwegian', 'no'),
(84, 'Occitan', 'oc'),
(85, '(Afan)/Oromoor/Oriya', 'om'),
(86, 'Punjabi', 'pa'),
(87, 'Polish', 'pl'),
(88, 'Pashto/Pushto', 'ps'),
(89, 'Portuguese', 'pt'),
(90, 'Quechua', 'qu'),
(91, 'Rhaeto-Romance', 'rm'),
(92, 'Kirundi', 'rn'),
(93, 'Romanian', 'ro'),
(94, 'Russian', 'ru'),
(95, 'Kinyarwanda', 'rw'),
(96, 'Sanskrit', 'sa'),
(97, 'Sindhi', 'sd'),
(98, 'Sangro', 'sg'),
(99, 'Serbo-Croatian', 'sh'),
(100, 'Singhalese', 'si'),
(101, 'Slovak', 'sk'),
(102, 'Slovenian', 'sl'),
(103, 'Samoan', 'sm'),
(104, 'Shona', 'sn'),
(105, 'Somali', 'so'),
(106, 'Albanian', 'sq'),
(107, 'Serbian', 'sr'),
(108, 'Siswati', 'ss'),
(109, 'Sesotho', 'st'),
(110, 'Sundanese', 'su'),
(111, 'Swedish', 'sv'),
(112, 'Swahili', 'sw'),
(113, 'Tamil', 'ta'),
(114, 'Telugu', 'te'),
(115, 'Tajik', 'tg'),
(116, 'Thai', 'th'),
(117, 'Tigrinya', 'ti'),
(118, 'Turkmen', 'tk'),
(119, 'Tagalog', 'tl'),
(120, 'Setswana', 'tn'),
(121, 'Tonga', 'to'),
(122, 'Turkish', 'tr'),
(123, 'Tsonga', 'ts'),
(124, 'Tatar', 'tt'),
(125, 'Twi', 'tw'),
(126, 'Ukrainian', 'uk'),
(127, 'Urdu', 'ur'),
(128, 'Uzbek', 'uz'),
(129, 'Vietnamese', 'vi'),
(130, 'Volapuk', 'vo'),
(131, 'Wolof', 'wo'),
(132, 'Xhosa', 'xh'),
(133, 'Yoruba', 'yo'),
(134, 'Chinese', 'zh'),
(135, 'Zulu', 'zu');

-- --------------------------------------------------------

--
-- Table structure for table `level_of_education`
--

CREATE TABLE `level_of_education` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `level_of_education`
--

INSERT INTO `level_of_education` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'Elementary School', NULL, NULL),
(2, 'Middle School', NULL, NULL),
(3, 'High School', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `meetings`
--

CREATE TABLE `meetings` (
  `id` int(10) UNSIGNED NOT NULL,
  `creator_id` int(10) UNSIGNED NOT NULL,
  `amount` int(10) UNSIGNED DEFAULT NULL,
  `discount` int(11) DEFAULT NULL,
  `disabled` tinyint(1) DEFAULT '0',
  `created_at` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `meetings`
--

INSERT INTO `meetings` (`id`, `creator_id`, `amount`, `discount`, `disabled`, `created_at`) VALUES
(29, 923, 100, 10, 0, 1625916131),
(30, 1015, 100, NULL, 0, 1625938321),
(31, 934, 100, NULL, 0, 1625939199),
(32, 929, 200, NULL, 0, 1625939298),
(33, 870, 100, NULL, 0, 1625939436),
(34, 3, 50, NULL, 1, 1625939607),
(35, 1016, 60, NULL, 0, 1625941278);

-- --------------------------------------------------------

--
-- Table structure for table `meeting_times`
--

CREATE TABLE `meeting_times` (
  `id` int(10) UNSIGNED NOT NULL,
  `meeting_id` int(10) UNSIGNED NOT NULL,
  `day_label` enum('saturday','sunday','monday','tuesday','wednesday','thursday','friday') COLLATE utf8mb4_unicode_ci NOT NULL,
  `time` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `meeting_times`
--

INSERT INTO `meeting_times` (`id`, `meeting_id`, `day_label`, `time`, `created_at`) VALUES
(84, 29, 'monday', '09:00AM-10:00AM', 1625916149),
(85, 29, 'wednesday', '08:30AM-10:00AM', 1625916235),
(86, 29, 'thursday', '06:30PM-07:30PM', 1625916372),
(87, 29, 'monday', '07:00PM-09:00PM', 1625916397),
(88, 29, 'thursday', '09:30PM-10:30PM', 1625916437),
(89, 29, 'wednesday', '10:15AM-11:45AM', 1625916467),
(90, 29, 'wednesday', '07:30PM-09:00PM', 1625916508),
(91, 29, 'tuesday', '09:00PM-10:00PM', 1625916577),
(92, 29, 'friday', '08:30PM-10:00PM', 1625916611),
(93, 30, 'saturday', '10:00AM-11:30AM', 1625938350),
(94, 30, 'sunday', '05:00PM-06:00PM', 1625938383),
(95, 30, 'sunday', '06:30PM-07:30PM', 1625938416),
(96, 30, 'monday', '09:45AM-10:15AM', 1625938436),
(97, 30, 'monday', '11:00AM-11:45AM', 1625938462),
(98, 30, 'monday', '06:00PM-07:30PM', 1625938497),
(99, 30, 'tuesday', '08:30AM-09:30AM', 1625938517),
(100, 30, 'wednesday', '10:30AM-11:30AM', 1625938585),
(101, 30, 'thursday', '04:50PM-05:50PM', 1625938619),
(102, 30, 'wednesday', '06:00PM-07:30PM', 1625938647),
(103, 30, 'wednesday', '08:30PM-09:45PM', 1625938681),
(104, 30, 'wednesday', '10:00PM-10:30PM', 1625938700),
(105, 30, 'thursday', '06:00PM-08:00PM', 1625938755),
(106, 30, 'friday', '10:15AM-11:45AM', 1625938776),
(107, 32, 'wednesday', '09:00AM-11:30AM', 1625939319),
(108, 32, 'monday', '09:00AM-10:30AM', 1625939339),
(109, 32, 'monday', '10:45AM-11:45AM', 1625939358),
(110, 32, 'tuesday', '06:00PM-07:30PM', 1625939384),
(111, 33, 'monday', '08:00AM-09:30AM', 1625939462),
(112, 33, 'thursday', '08:30AM-09:30AM', 1625939477),
(113, 33, 'tuesday', '10:30AM-11:30AM', 1625939493),
(114, 33, 'wednesday', '08:00AM-09:00AM', 1625939508),
(115, 33, 'thursday', '09:30AM-10:30AM', 1625939527),
(116, 33, 'monday', '10:30AM-11:30AM', 1625939551),
(117, 34, 'monday', '09:30AM-10:30AM', 1625939631),
(118, 34, 'tuesday', '09:30AM-10:30AM', 1625939646),
(119, 34, 'monday', '10:45AM-11:45AM', 1625939660),
(120, 34, 'wednesday', '08:00AM-10:00AM', 1625939678),
(121, 34, 'wednesday', '10:30AM-11:30AM', 1625939700),
(122, 34, 'wednesday', '05:00PM-06:30PM', 1625939744),
(123, 34, 'thursday', '08:00PM-09:30PM', 1625939763),
(124, 35, 'monday', '09:30AM-10:30AM', 1625941315),
(125, 35, 'monday', '11:00AM-11:45AM', 1625941331),
(126, 35, 'tuesday', '08:00AM-09:30AM', 1625941347),
(127, 35, 'wednesday', '08:00AM-09:30AM', 1625941388),
(128, 35, 'wednesday', '10:00AM-11:30AM', 1625941418),
(129, 35, 'monday', '05:00PM-06:30PM', 1625941460),
(130, 35, 'wednesday', '08:00PM-09:30PM', 1625941475),
(131, 35, 'thursday', '08:00AM-09:00AM', 1625941557),
(132, 35, 'thursday', '09:30AM-10:30AM', 1625941580),
(133, 35, 'thursday', '10:45AM-11:45AM', 1625941611),
(134, 35, 'monday', '07:30PM-08:30PM', 1625941640),
(135, 35, 'thursday', '10:00PM-11:00PM', 1625941668),
(136, 35, 'monday', '09:00PM-10:00PM', 1625941731),
(137, 35, 'monday', '10:15PM-11:00PM', 1625941832),
(138, 35, 'friday', '09:00AM-10:00AM', 1625941864),
(139, 35, 'friday', '10:30AM-11:30AM', 1625941879),
(140, 31, 'monday', '08:40AM-09:40AM', 1625942014),
(141, 31, 'monday', '10:00AM-11:30AM', 1625942028),
(142, 31, 'monday', '05:00PM-06:30PM', 1625942048),
(143, 31, 'monday', '06:45PM-07:45PM', 1625942079),
(144, 31, 'monday', '08:00PM-09:30PM', 1625942101),
(145, 31, 'monday', '10:00PM-10:30PM', 1625942137),
(146, 31, 'tuesday', '08:30AM-09:30AM', 1625942159),
(147, 31, 'tuesday', '10:00AM-11:00AM', 1625942173),
(148, 31, 'tuesday', '06:00PM-07:00PM', 1625942191),
(149, 31, 'saturday', '09:30AM-10:30AM', 1625942210),
(150, 31, 'sunday', '08:30AM-09:30AM', 1625942229),
(151, 31, 'sunday', '09:45AM-10:00AM', 1625942247),
(152, 31, 'sunday', '10:30AM-11:45AM', 1625942266),
(153, 31, 'saturday', '10:45AM-11:45AM', 1625942311),
(154, 31, 'sunday', '05:00PM-06:30PM', 1625942351),
(155, 31, 'wednesday', '08:15AM-09:15AM', 1625942379),
(156, 31, 'wednesday', '09:30AM-10:30AM', 1625942395),
(157, 31, 'wednesday', '10:45AM-11:45AM', 1625942414),
(158, 31, 'thursday', '07:30AM-08:30AM', 1625942446),
(159, 31, 'thursday', '08:45AM-09:45AM', 1625942461),
(160, 31, 'thursday', '10:00AM-11:00AM', 1625942474),
(161, 31, 'thursday', '05:30PM-07:00PM', 1625942499),
(162, 31, 'friday', '09:00AM-10:00AM', 1625942521),
(163, 31, 'friday', '10:30AM-11:30AM', 1625942564),
(164, 31, 'tuesday', '08:00PM-09:00PM', 1625942840),
(165, 31, 'saturday', '06:00PM-07:00PM', 1625943005);

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2020_08_09_145553_create_roles_table', 1),
(4, '2020_08_09_145834_create_sections_table', 1),
(5, '2020_08_09_145926_create_permissions_table', 1),
(6, '2020_08_24_163003_create_webinars_table', 1),
(7, '2020_08_24_164823_create_webinar_partner_teacher_table', 1),
(8, '2020_08_24_165658_create_tags_table', 1),
(9, '2020_08_24_165835_create_webinar_tag_table', 1),
(10, '2020_08_24_171611_create_categories_table', 1),
(11, '2020_08_29_052437_create_filters_table', 1),
(12, '2020_08_29_052900_create_filter_options_table', 1),
(13, '2020_08_29_054455_add_category_id_in_webinar_table', 1),
(14, '2020_09_01_174741_add_seo_description_and_start_end_time_in_webinar_table', 1),
(15, '2020_09_02_180508_create_webinar_filter_option_table', 1),
(16, '2020_09_02_193923_create_tickets_table', 1),
(17, '2020_09_02_210447_create_sessions_table', 1),
(18, '2020_09_02_212642_create_files_table', 1),
(19, '2020_09_03_175543_create_faqs_table', 1),
(20, '2020_09_08_175539_delete_webinar_tag_and_update_tag_table', 1),
(21, '2020_09_09_154522_create_quizzes_table', 1),
(22, '2020_09_09_174646_create_quizzes_questions_table', 1),
(23, '2020_09_09_182726_create_quizzes_questions_answers_table', 1),
(24, '2020_09_14_160028_create_prerequisites_table', 1),
(25, '2020_09_14_183235_nullable_item_id_in_quizzes_table', 1),
(26, '2020_09_14_190110_create_webinar_quizzes_table', 1),
(27, '2020_09_16_163835_create_quizzes_results_table', 1),
(28, '2020_09_24_102115_add_total_mark_in_quize_table', 1),
(29, '2020_09_24_132242_create_comment_table', 1),
(30, '2020_09_24_132639_create_favorites_table', 1),
(31, '2020_09_26_181200_create_certificate_table', 1),
(32, '2020_09_26_181444_create_certificates_templates_table', 1),
(33, '2020_09_30_170451_add_slug_in_webinars_table', 1),
(34, '2020_09_30_191202_create_purchases_table', 1),
(35, '2020_10_02_063828_create_rating_table', 1),
(36, '2020_10_02_094723_edit_table_and_add_foreign_key', 1),
(37, '2020_10_08_055408_add_reviwes_table', 1),
(38, '2020_10_08_084100_edit_status_comments_table', 1),
(39, '2020_10_08_121041_create_meetings_table', 2),
(40, '2020_10_08_121621_create_meeting_times_table', 2),
(41, '2020_10_08_121848_create_meeting_requests_table', 2),
(42, '2020_10_15_172913_add_about_and_head_line_in_users_table', 2),
(43, '2020_10_15_173645_create_follow_table', 2),
(46, '2020_10_17_100606_create_badges_table', 3),
(47, '2020_10_08_121848_create_reserve_meetings_table', 4),
(48, '2020_10_20_193013_update_users_table', 5),
(49, '2020_10_20_211927_create_users_metas_table', 6),
(50, '2020_10_18_220323_convert_creatore_user_id_to_creator_id', 7),
(51, '2020_10_22_153502_create_cart_table', 7),
(52, '2020_10_22_154636_create_orders_table', 7),
(53, '2020_10_22_155930_create_order_items_table', 7),
(54, '2020_10_23_204203_create_sales_table', 7),
(55, '2020_10_23_211459_create_accounting_table', 7),
(56, '2020_10_23_213515_create_discounts_table', 7),
(57, '2020_10_23_213934_create_discount_users_table', 7),
(58, '2020_10_23_235444_create_ticket_users_table', 7),
(59, '2020_10_25_172331_create_groups_table', 7),
(60, '2020_10_25_172523_create_group_users_table', 7),
(62, '2020_11_02_202754_edit_email_in_users_table', 8),
(63, '2020_11_03_200314_edit_some_tables', 9),
(64, '2020_11_06_193300_create_settings_table', 10),
(67, '2020_11_09_202533_create_feature_webinars_table', 11),
(68, '2020_11_10_193459_edit_webinars_table', 12),
(69, '2020_11_11_203344_create_trend_categories_table', 13),
(72, '2020_11_11_222833_create_blog_categories_table', 14),
(75, '2020_11_11_231204_create_blog_table', 15),
(76, '2020_10_25_223247_add_sub_title_tickets_table', 16),
(77, '2020_10_28_001340_add_count_in_discount_users_table', 16),
(78, '2020_10_28_221509_create_payment_channels_table', 16),
(79, '2020_11_01_120909_change_class_name_enum_payment_channels_table', 16),
(80, '2020_11_07_233948_add_some_raw_in_order_items__table', 16),
(81, '2020_11_10_061350_add_discount_id_in_order_items_table', 16),
(82, '2020_11_10_071651_decimal_orders_order_items_sales_table', 16),
(83, '2020_11_11_193138_change_reference_id_type_in_orders_tabel', 16),
(84, '2020_11_11_222413_change_meeting_id_to_meeting_time_id_in_order_items_table', 16),
(85, '2020_11_11_225421_add_locked_at_and_reserved_at_and_change_request_time_to_day_in_reserve_meetings_table', 17),
(86, '2020_11_12_000116_add_type_in_orders_table', 17),
(87, '2020_11_12_001912_change_meeting_id_to_meeting_time_id_in_accounting_table', 17),
(88, '2020_11_12_133009_decimal_paid_amount_in_reserve_meetings_table', 17),
(91, '2020_11_12_170109_add_blog_id_to_comments_table', 18),
(98, '2020_11_14_201228_add_bio_and_ban_to_users_table', 20),
(99, '2020_11_14_224447_create_users_badges_table', 21),
(100, '2020_11_14_233319_create_payout_request_table', 22),
(101, '2020_11_15_010622_change_byer_id_and_add_seller_id_in_sales_table', 22),
(102, '2020_11_16_195009_create_supports_table', 22),
(103, '2020_11_16_201814_create_support_departments_table', 22),
(107, '2020_11_16_202254_create_supports_table', 23),
(109, '2020_11_17_192744_create_support_conversations_table', 24),
(110, '2020_11_17_072348_create_offline_payments_table', 25),
(111, '2020_11_19_191943_add_replied_status_to_comments_table', 25),
(114, '2020_11_20_215748_create_subscribes_table', 26),
(115, '2020_11_21_185519_create_notification_templates_table', 27),
(116, '2020_11_22_210832_create_promotions_table', 28),
(118, '2020_11_23_194153_add_status_column_to_discounts_table', 29),
(119, '2020_11_23_213532_create_users_occupations_table', 30),
(120, '2020_11_30_220855_change_amount_in_payouts_table', 31),
(121, '2020_11_30_231334_add_pay_date_in_offline_payments_table', 31),
(122, '2020_11_30_233018_add_charge_enum_in_type_in_orders_table', 31),
(123, '2020_12_01_193948_create_testimonials_table', 32),
(124, '2020_12_02_202043_edit_and_add_types_to_webinars_table', 33),
(128, '2020_12_04_204048_add_column_creator_id_to_some_tables', 34),
(129, '2020_12_05_205320_create_text_lessons_table', 35),
(130, '2020_12_05_210052_create_text_lessons_attachments_table', 36),
(131, '2020_12_06_215701_add_order_column_to_webinar_items_tables', 37),
(132, '2020_12_11_114844_add_column_storage_to_files_table', 38),
(133, '2020_12_07_211009_add_subscribe_id_in_order_items_table', 39),
(134, '2020_12_07_211657_nullable_payment_method_in_orders_table', 39),
(135, '2020_12_07_212306_add_subscribe_enum__type_in_orders_table', 39),
(136, '2020_12_07_223237_changes_in_sales_table', 39),
(137, '2020_12_07_224925_add_subscribe_id_in_accounting_table', 39),
(138, '2020_12_07_230200_create_subscribe_uses_table', 39),
(139, '2020_12_11_123209_add_subscribe_type_account_in_accounting_table', 39),
(140, '2020_12_11_132819_add_sale_id_in_subscribe_use_in_subscribe_uses_table', 39),
(141, '2020_12_11_135824_add_subscribe_payment_method_in_sales_table', 39),
(143, '2020_12_13_205751_create_advertising_banners_table', 41),
(145, '2020_12_14_204251_create_become_instructors_table', 42),
(146, '2020_11_12_232207_create_reports_table', 43),
(147, '2020_11_12_232207_create_comments_reports_table', 44),
(148, '2020_12_17_210822_create_webinar_reports_table', 45),
(150, '2020_12_18_181551_create_notifications_table', 46),
(151, '2020_12_18_195833_create_notifications_status_table', 47),
(152, '2020_12_19_195152_add_status_column_to_payment_channels_table', 48),
(154, '2020_12_20_231434_create_contacts_table', 49),
(155, '2020_12_21_210345_edit_quizzes_table', 50),
(156, '2020_12_24_221715_add_column_to_users_table', 50),
(157, '2020_12_24_084728_create_special_offers_table', 51),
(158, '2020_12_25_204545_add_promotion_enum_type_in_orders_table', 51),
(159, '2020_12_25_205139_add_promotion_id_in_order_items_table', 51),
(160, '2020_12_25_205811_add_promotion_id_in_accounting_table', 51),
(161, '2020_12_25_210341_add_promotion_id_in_sales_table', 51),
(162, '2020_12_25_212453_add_promotion_type_account_enum_in_accounting_table', 51),
(163, '2020_12_25_231005_add_promotion_type_enum_in_sales_table', 51),
(166, '2020_12_29_192943_add_column_reply_to_contacts_table', 53),
(167, '2020_12_30_225001_create_payu_transactions_table', 54),
(168, '2021_01_06_202649_edit_column_password_from_users_table', 55),
(169, '2021_01_08_134022_add_api_column_to_sessions_table', 56),
(170, '2021_01_10_215540_add_column_store_type_to_accounting', 57),
(173, '2021_01_13_214145_edit_carts_table', 58),
(174, '2021_01_13_230725_delete_column_type_from_orders_table', 59),
(175, '2021_01_20_214653_add_discount_column_to_reserve_meetings_table', 60),
(177, '2021_01_27_193915_add_foreign_key_to_support_conversations_table', 61),
(178, '2021_02_02_203821_add_viewed_at_column_to_comments_table', 62),
(180, '2021_02_12_134504_add_financial_approval_column_to_users_table', 64),
(181, '2021_02_12_131916_create_verifications_table', 65),
(182, '2021_02_15_221518_add_certificate_to_users_table', 66),
(183, '2021_02_16_194103_add_cloumn_private_to_webinars_table', 66),
(184, '2021_02_18_213601_edit_rates_column_webinar_reviews_table', 67),
(188, '2021_02_27_212131_create_noticeboards_table', 68),
(189, '2021_02_27_213940_create_noticeboards_status_table', 68),
(191, '2021_02_28_195025_edit_groups_table', 69),
(192, '2021_03_06_205221_create_newsletters_table', 70),
(193, '2021_03_12_105526_add_is_main_column_to_roles_table', 71),
(194, '2021_03_12_202441_add_description_column_to_feature_webinars_table', 72),
(195, '2021_03_18_130248_edit_status_column_from_supports_table', 73),
(196, '2021_03_19_113306_add_column_order_to_categories_table', 74),
(197, '2021_03_19_115939_add_column_order_to_filter_options_table', 75),
(199, '2021_03_24_100005_edit_discounts_table', 76),
(200, '2021_03_27_204551_create_sales_status_table', 77),
(202, '2021_03_28_182558_add_column_page_to_settings_table', 78),
(206, '2021_03_31_195835_add_new_status_in_reserve_meetings_table', 79),
(207, '2020_12_12_204705_create_course_learning_table', 80),
(208, '2021_04_19_195452_add_meta_description_column_to_blog_table', 81),
(209, '2021_04_21_200131_add_icon_column_to_categories_table', 82),
(210, '2021_04_21_203746_add_is_popular_column_to_subscribes_table', 83),
(211, '2021_04_25_203955_add_is_charge_account_column_to_order_items', 84),
(212, '2021_04_25_203955_add_is_charge_account_column_to_orders', 85),
(213, '2021_05_13_111720_add_moderator_secret_column_to_sessions_table', 86),
(214, '2021_05_13_123920_add_zoom_id_column_to_sessions_table', 87),
(215, '2021_05_14_182848_create_session_reminds_table', 88),
(217, '2021_05_25_193743_create_users_zoom_api_table', 89),
(218, '2021_05_25_205716_add_new_column_to_sessions_table', 90),
(219, '2021_05_27_095128_add_user_id_to_newsletters_table', 91),
(220, '2020_12_27_192459_create_pages_table', 92),
(221, '2022_01_18_141218_create_teacher_interview_requests_table', 93),
(222, '2022_01_19_133303_create_user_codes_table', 94),
(223, '2022_01_24_182328_create_teacher_interview_requests_table', 95),
(224, '2022_02_03_140139_create_teaching_specifications_table', 96),
(225, '2022_02_07_143530_create_teaching_qualifications_table', 97),
(227, '2022_02_15_074347_create_academic_classes_table', 99),
(230, '2022_02_23_153226_create_courses_table', 100),
(231, '2022_02_23_183859_create_academic_classes_table', 101),
(232, '2022_03_02_061938_create_feedbacks_table', 102),
(233, '2022_03_02_062521_create_user_feedbacks_table', 103),
(234, '2022_03_02_071445_create_user_feedbacks_table', 104);

-- --------------------------------------------------------

--
-- Table structure for table `newsletters`
--

CREATE TABLE `newsletters` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `newsletters`
--

INSERT INTO `newsletters` (`id`, `user_id`, `email`, `created_at`) VALUES
(8, 995, 'cameronschofield@gmail.com', 1625090411);

-- --------------------------------------------------------

--
-- Table structure for table `notification_templates`
--

CREATE TABLE `notification_templates` (
  `id` int(10) UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `template` text COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `notification_templates`
--

INSERT INTO `notification_templates` (`id`, `title`, `template`) VALUES
(2, 'New badge', '<p>Congratilations! You received [u.b.title]&nbsp;badge.</p>'),
(3, 'Your user group changed', '<p>Your user group changed to [u.g.title] .</p>'),
(4, 'Your class created', '<p>Your class [c.title] successfully created. It will be published after approval.</p>'),
(5, 'Your class approved', '<p>Your class [c.title] successfully approved. Now you can find it on the website.</p>'),
(6, 'Your class rejected', '<p>Sorry, Your class [c.title] rejected because it doesn\'t meet requirements or is against the community rules.</p>'),
(7, 'New comment for your class', '<p>[u.name] left a new comment on your class [c.title] .</p>'),
(8, 'New class support message', '<p>user [u.name] send new support message for course with title [c.title] .</p>'),
(9, 'New reply on support conversation', '<p>New reply on a support conversation on your class [c.title] support.</p>'),
(10, 'New support ticket', '<p>New support ticket received with subject [s.t.title] .</p>'),
(11, 'New reply on support ticket', '<p>The support ticket with the subject [s.t.title] updated with a new reply.</p>'),
(12, 'New financial document', '<p>&nbsp;New financial document submitted for your class [c.title] with type [f.d.type] and amount [amount] .</p>'),
(13, 'New payout request', '<p>New payout request received with the amount [payout.amount] . You can manage it using the admin panel.</p>'),
(14, 'Payout has been processed', 'Your payout has been processed with the amount [payout.amount]&nbsp;&nbsp;to your account [payout.account] .'),
(15, 'New sales', '<p>Congratulations! There is a new sales for your class [c.title] .</p>'),
(16, 'New purchase completed', '<p>The class [c.title] successfully purchased. Now you can start learning.</p>'),
(17, 'New feedback', '<p>Your class [c.title] received a [rate.count] stars rating from [student.name] .</p>'),
(18, 'Offline payment submitted', '<p>An offline payment request with the amount [amount] submitted successfully.</p>'),
(19, 'Offline payment approved', '<p>Offline payment request with the amount [amount] successfully approved.</p>'),
(20, 'Offline payment rejected', '<p>Sorry, offline payment request with the amount [amount]&nbsp;rejected.</p>'),
(21, 'Subscription plan activated', '<p>[s.p.name] subscription package activated by user [u.name] .</p>'),
(22, 'New meeting request', '<p>New meeting booked by [u.name] for [time.date] with the amount [amount] .</p>'),
(23, 'New meeting join URL', '<p>The reserved meeting join URL created by [instructor.name]. Join the meeting on [time.date] using this URL: [link] .</p>'),
(24, 'Meeting reminder', '<p>You have a meeting on [time.date] . Don\'t forget to join it on time.</p>'),
(25, 'Meeting finished', '<p>The meeting finished. Instructor: [instructor.name] Student:&nbsp; [student.name]&nbsp; Meeting time: [time.date] .</p>'),
(26, 'New contact message', '<p>New contact message with title [c.u.title] received from [u.name] .</p>'),
(27, 'Live class reminder', '<p>Your live class [c.title] will be conducted on [time.date] . Join it on time.</p>'),
(28, 'Promotion plan activated', '<p>Promotion plan [p.p.name]&nbsp;&nbsp;activated for the call [c.title] .</p>'),
(29, 'Promotion plan purchased', '<p>There is new request for activating [p.p.name] for class [c.title] .&nbsp;</p>'),
(30, 'New certificate', '<p>You achieved a new certificate for [c.title] . You can download it from the class page or your panel.</p>'),
(31, 'New pending quiz', '<p>[student.name] passed [q.title] quiz of the [c.title] class and waiting for correction to get results.</p>'),
(32, 'Waiting quiz result', '<p>Your pending quiz corrected and your result is [q.result] for [q.title] quiz of [c.title] class.</p>'),
(33, 'New comment', '<p>[u.name] left a new comment and waiting for approval.</p>'),
(34, 'Payout request submitted', '<p>Your payout request successfully submitted for [payout.amount] . You will receive an email when processed.</p>');

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `password_resets`
--

INSERT INTO `password_resets` (`email`, `token`, `created_at`) VALUES
('av18981848@gmail.com', 'cYTtJLR86NoxZ0whf465XoQa98hhxAxx2Q7t3zeaeTJRYoUMQwqqzb4rgqP2', '2021-02-20 13:35:13'),
('studentXYZ@sharklasers.com', 'd8tUtgoCop7W2H7oQE9mhVZS0BypHbc6uA0wbQUsP0oFki1AcmTy39oCOZIJ', '2022-01-23 01:26:23'),
('teacherXYZ@sharklasers.com', 'SZVoQqiX96Bu0Zf4UfFsZ780jU3IzPlOk2sPgNBctUigaT9DhuVogezskTjc', '2022-01-23 01:28:05'),
('ttest123@sharklasers.com', 'GfqmnJXqAhW62d0pFrwn6XNKeoXq1UW2mmkD7mKC0lKH82qhThxrLn8oRnUu', '2022-01-24 02:07:19'),
('dry50188@qopow.com', 'Scs6ylCf8T7ZcJIePP5rDG5U22TiILtwq0W2GO1zn7DefLydjwMy68CA02Qn', '2022-01-24 23:30:11'),
('student@demo.com', 'qvn3mH9UwNMRRFY76A4PO62quzIJjW7D1gBdVfo4FZnrPNVbSp19B3cNZMTp', '2022-01-25 01:10:38'),
('student@demo.com', 'saKV0SCEMkFq90OCv1gSCMWgPIyUGLoxPT4i4iVT3NXOLIwd88ia8beNMQ3Z', '2022-01-25 01:12:18'),
('student@demo.com', 'RefG0hMO3k4nmxnW7tyuKwWvrWC7ndTTwnEn4juTlsnRqVSKsRgZRp4pnbRF', '2022-01-25 01:13:15'),
('mabdulrehman14713+1@gmail.com', 'qXINAJF4JAdpN9PcwmLXHI7u6mLWDD8wjQ3bSafxAnOXTUwiGGEMNz0UbqP7', '2022-02-03 01:44:56'),
('dev.metutors@gmail.com', 'pi63zAqxhynCBG0WMv6V8EyJuSEfSGb6XYGnzaLwGPC27E50WRxVa6Ih0MGJ', '2022-02-08 02:59:13'),
('certijob@gmail.com', 'mV0nMtgivjV0P2EeVHJonS5QLwmU4kb4JjNgipk0uNUyr5LQaUBkrgWHbAb8', '2022-03-03 16:35:58'),
('certijob@gmail.com', 'ovpEEXXFsrY85EcrAdXluLF5NqG7iP7hU4vBVhpRg3skUUgv35tQZpf4zQEK', '2022-03-03 16:36:41');

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` int(10) UNSIGNED NOT NULL,
  `role_id` int(10) UNSIGNED DEFAULT NULL,
  `section_id` int(10) UNSIGNED DEFAULT NULL,
  `allow` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `role_id`, `section_id`, `allow`) VALUES
(5189, 2, 1, 1),
(5190, 2, 2, 1),
(5191, 2, 3, 1),
(5192, 2, 4, 1),
(5193, 2, 5, 1),
(5194, 2, 6, 1),
(5195, 2, 7, 1),
(5196, 2, 8, 1),
(5197, 2, 9, 1),
(5198, 2, 10, 1),
(5199, 2, 11, 1),
(5200, 2, 12, 1),
(5201, 2, 13, 1),
(5202, 2, 14, 1),
(5203, 2, 15, 1),
(5204, 2, 16, 1),
(5205, 2, 17, 1),
(5206, 2, 25, 1),
(5207, 2, 26, 1),
(5208, 2, 50, 1),
(5209, 2, 51, 1),
(5210, 2, 52, 1),
(5211, 2, 53, 1),
(5212, 2, 54, 1),
(5213, 2, 100, 1),
(5214, 2, 101, 1),
(5215, 2, 102, 1),
(5216, 2, 103, 1),
(5217, 2, 104, 1),
(5218, 2, 105, 1),
(5219, 2, 106, 1),
(5220, 2, 107, 1),
(5221, 2, 108, 1),
(5222, 2, 109, 1),
(5223, 2, 110, 1),
(5224, 2, 111, 1),
(5225, 2, 112, 1),
(5226, 2, 113, 1),
(5227, 2, 114, 1),
(5228, 2, 115, 1),
(5229, 2, 150, 1),
(5230, 2, 151, 1),
(5231, 2, 152, 1),
(5232, 2, 153, 1),
(5233, 2, 154, 1),
(5234, 2, 155, 1),
(5235, 2, 156, 1),
(5236, 2, 157, 1),
(5237, 2, 158, 1),
(5238, 2, 200, 1),
(5239, 2, 201, 1),
(5240, 2, 202, 1),
(5241, 2, 203, 1),
(5242, 2, 204, 1),
(5243, 2, 205, 1),
(5244, 2, 206, 1),
(5245, 2, 207, 1),
(5246, 2, 208, 1),
(5247, 2, 250, 1),
(5248, 2, 251, 1),
(5249, 2, 252, 1),
(5250, 2, 253, 1),
(5251, 2, 254, 1),
(5252, 2, 300, 1),
(5253, 2, 301, 1),
(5254, 2, 302, 1),
(5255, 2, 303, 1),
(5256, 2, 304, 1),
(5257, 2, 350, 1),
(5258, 2, 351, 1),
(5259, 2, 352, 1),
(5260, 2, 353, 1),
(5261, 2, 354, 1),
(5262, 2, 355, 1),
(5263, 2, 356, 1),
(5264, 2, 400, 1),
(5265, 2, 401, 1),
(5266, 2, 402, 1),
(5267, 2, 403, 1),
(5268, 2, 404, 1),
(5269, 2, 405, 1),
(5270, 2, 450, 1),
(5271, 2, 451, 1),
(5272, 2, 452, 1),
(5273, 2, 453, 1),
(5274, 2, 454, 1),
(5275, 2, 455, 1),
(5276, 2, 456, 1),
(5277, 2, 457, 1),
(5278, 2, 458, 1),
(5279, 2, 459, 1),
(5280, 2, 500, 1),
(5281, 2, 501, 1),
(5282, 2, 502, 1),
(5283, 2, 503, 1),
(5284, 2, 504, 1),
(5285, 2, 505, 1),
(5286, 2, 550, 1),
(5287, 2, 551, 1),
(5288, 2, 552, 1),
(5289, 2, 553, 1),
(5290, 2, 554, 1),
(5291, 2, 600, 1),
(5292, 2, 601, 1),
(5293, 2, 602, 1),
(5294, 2, 603, 1),
(5295, 2, 650, 1),
(5296, 2, 651, 1),
(5297, 2, 652, 1),
(5298, 2, 653, 1),
(5299, 2, 654, 1),
(5300, 2, 655, 1),
(5301, 2, 656, 1),
(5302, 2, 657, 1),
(5303, 2, 658, 1),
(5304, 2, 700, 1),
(5305, 2, 701, 1),
(5306, 2, 702, 1),
(5307, 2, 703, 1),
(5308, 2, 704, 1),
(5309, 2, 705, 1),
(5310, 2, 706, 1),
(5311, 2, 707, 1),
(5312, 2, 708, 1),
(5313, 2, 750, 1),
(5314, 2, 751, 1),
(5315, 2, 752, 1),
(5316, 2, 753, 1),
(5317, 2, 754, 1),
(5318, 2, 800, 1),
(5319, 2, 801, 1),
(5320, 2, 802, 1),
(5321, 2, 803, 1),
(5322, 2, 850, 1),
(5323, 2, 851, 1),
(5324, 2, 852, 1),
(5325, 2, 853, 1),
(5326, 2, 854, 1),
(5327, 2, 900, 1),
(5328, 2, 901, 1),
(5329, 2, 902, 1),
(5330, 2, 903, 1),
(5331, 2, 904, 1),
(5332, 2, 950, 1),
(5333, 2, 951, 1),
(5334, 2, 952, 1),
(5335, 2, 953, 1),
(5336, 2, 954, 1),
(5337, 2, 955, 1),
(5338, 2, 956, 1),
(5339, 2, 957, 1),
(5340, 2, 958, 1),
(5341, 2, 959, 1),
(5342, 2, 1000, 1),
(5343, 2, 1001, 1),
(5344, 2, 1002, 1),
(5345, 2, 1003, 1),
(5346, 2, 1004, 1),
(5347, 2, 1050, 1),
(5348, 2, 1051, 1),
(5349, 2, 1052, 1),
(5350, 2, 1053, 1),
(5351, 2, 1054, 1),
(5352, 2, 1055, 1),
(5353, 2, 1056, 1),
(5354, 2, 1057, 1),
(5355, 2, 1058, 1),
(5356, 2, 1059, 1),
(5357, 2, 1075, 1),
(5358, 2, 1076, 1),
(5359, 2, 1077, 1),
(5360, 2, 1078, 1),
(5361, 2, 1079, 1),
(5362, 2, 1100, 1),
(5363, 2, 1101, 1),
(5364, 2, 1102, 1),
(5365, 2, 1103, 1),
(5366, 2, 1104, 1),
(5367, 2, 1150, 1),
(5368, 2, 1151, 1),
(5369, 2, 1152, 1),
(5370, 2, 1153, 1),
(5371, 2, 1154, 1),
(5372, 2, 1200, 1),
(5373, 2, 1201, 1),
(5374, 2, 1202, 1),
(5375, 2, 1203, 1),
(5376, 2, 1204, 1),
(5377, 2, 1230, 1),
(5378, 2, 1231, 1),
(5379, 2, 1232, 1),
(5380, 2, 1233, 1),
(5381, 2, 1250, 1),
(5382, 2, 1251, 1),
(5383, 2, 1252, 1),
(5384, 2, 1253, 1),
(5385, 2, 1300, 1),
(5386, 2, 1301, 1),
(5387, 2, 1302, 1),
(5388, 2, 1303, 1),
(5389, 2, 1304, 1),
(5390, 2, 1305, 1),
(5391, 2, 1350, 1),
(5392, 2, 1351, 1),
(5393, 2, 1352, 1),
(5394, 2, 1353, 1),
(5395, 2, 1354, 1),
(5396, 2, 1355, 1),
(5397, 2, 1400, 1),
(5398, 2, 1401, 1),
(5399, 2, 1402, 1),
(5400, 2, 1403, 1),
(5401, 2, 1404, 1),
(5402, 2, 1405, 1),
(5403, 2, 1406, 1),
(5404, 2, 1407, 1),
(5405, 2, 1408, 1),
(5406, 2, 1409, 1),
(5407, 2, 1410, 1),
(5408, 2, 1450, 1),
(5409, 2, 1451, 1),
(5410, 2, 1452, 1),
(5411, 2, 1453, 1),
(5412, 2, 1454, 1),
(5413, 2, 1455, 1),
(5414, 2, 1500, 1),
(5415, 2, 1501, 1),
(5416, 2, 1502, 1),
(5417, 2, 1503, 1),
(5418, 2, 1504, 1),
(5419, 2, 1550, 1),
(5420, 2, 1551, 1),
(5421, 2, 1552, 1),
(5422, 2, 1553, 1),
(5423, 2, 1554, 1),
(5424, 2, 1600, 1),
(5425, 2, 1601, 1),
(5426, 2, 1602, 1),
(5427, 2, 1603, 1),
(5428, 2, 1604, 1),
(5429, 2, 1650, 1),
(5430, 2, 1651, 1),
(5431, 2, 1652, 1),
(5493, 6, 1, 1),
(5494, 6, 2, 1),
(5495, 6, 3, 1),
(5496, 6, 4, 1),
(5497, 6, 5, 1),
(5498, 6, 6, 1),
(5499, 6, 7, 1),
(5500, 6, 8, 1),
(5501, 6, 9, 1),
(5502, 6, 10, 1),
(5503, 6, 11, 1),
(5504, 6, 12, 1),
(5505, 6, 13, 1),
(5506, 6, 14, 1),
(5507, 6, 15, 1),
(5508, 6, 16, 1),
(5509, 6, 17, 1),
(5510, 6, 25, 1),
(5511, 6, 26, 1),
(5512, 6, 100, 1),
(5513, 6, 101, 1),
(5514, 6, 102, 1),
(5515, 6, 103, 1),
(5516, 6, 104, 1),
(5517, 6, 105, 1),
(5518, 6, 106, 1),
(5519, 6, 108, 1),
(5520, 6, 109, 1),
(5521, 6, 110, 1),
(5522, 6, 112, 1),
(5523, 6, 113, 1),
(5524, 6, 114, 1),
(5525, 6, 115, 1),
(5526, 6, 550, 1),
(5527, 6, 551, 1),
(5528, 6, 552, 1),
(5529, 6, 553, 1),
(5530, 6, 554, 1),
(5531, 6, 750, 1),
(5532, 6, 751, 1),
(5533, 6, 753, 1),
(5534, 6, 754, 1),
(5535, 6, 800, 1),
(5536, 6, 801, 1),
(5537, 6, 802, 1),
(5538, 6, 803, 1),
(5539, 6, 1230, 1),
(5540, 6, 1231, 1),
(5541, 6, 1232, 1),
(5542, 6, 1233, 1),
(5543, 6, 1250, 1),
(5544, 6, 1251, 1),
(5545, 6, 1252, 1),
(5546, 6, 1600, 1),
(5547, 6, 1601, 1),
(5548, 6, 1602, 1),
(5549, 6, 1603, 1),
(5576, 9, 1, 1),
(5577, 9, 2, 1),
(5578, 9, 3, 1),
(5579, 9, 4, 1),
(5580, 9, 5, 1),
(5581, 9, 6, 1),
(5582, 9, 7, 1),
(5583, 9, 8, 1),
(5584, 9, 9, 1),
(5585, 9, 10, 1),
(5586, 9, 11, 1),
(5587, 9, 12, 1),
(5588, 9, 13, 1),
(5589, 9, 14, 1),
(5590, 9, 15, 1),
(5591, 9, 16, 1),
(5592, 9, 17, 1),
(5593, 9, 700, 1),
(5594, 9, 701, 1),
(5595, 9, 702, 1),
(5596, 9, 703, 1),
(5597, 9, 704, 1),
(5598, 9, 705, 1),
(5599, 9, 706, 1),
(5600, 9, 707, 1),
(5601, 9, 708, 1);

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `personal_access_tokens`
--

INSERT INTO `personal_access_tokens` (`id`, `tokenable_type`, `tokenable_id`, `name`, `token`, `abilities`, `last_used_at`, `created_at`, `updated_at`) VALUES
(65, 'App\\User', 995, 'METutor', 'c43197dcc53365b5f8153e2083b330cbbd1b6cacf9b81e0e02363ed456c3a4bb', '[\"*\"]', NULL, '2022-01-22 17:31:53', '2022-01-22 17:31:53'),
(66, 'App\\User', 995, 'METutor', '899b66d7dea93c8b8c4d0051028228405174364290930c4c54fb085dcb240d94', '[\"*\"]', NULL, '2022-01-22 17:33:06', '2022-01-22 17:33:06'),
(67, 'App\\User', 995, 'METutor', '44f261bf8d5ce33a398206fb46a6383b6d85c4882eba01e25bda766cdf5d3ac7', '[\"*\"]', NULL, '2022-01-22 17:37:42', '2022-01-22 17:37:42'),
(68, 'App\\User', 995, 'METutor', '7aeb5b13a842b6af3bf4d566eba56f23a5e6e7d1aaf4485b4ec78e471f3f0a1e', '[\"*\"]', NULL, '2022-01-22 17:39:19', '2022-01-22 17:39:19'),
(69, 'App\\User', 995, 'METutor', '5fae3e54e68b6e9dc739907686098ed38fb590eb3224d663945f79a6c61e948c', '[\"*\"]', NULL, '2022-01-22 17:40:37', '2022-01-22 17:40:37'),
(70, 'App\\User', 995, 'METutor', '3d9c3fa945a1ba8d3868599b876d9882856d392b28b0ebb25e11f676bd7f05dc', '[\"*\"]', NULL, '2022-01-22 17:50:53', '2022-01-22 17:50:53'),
(71, 'App\\User', 995, 'METutor', 'f43059a8641b08e84794f0d765ace2cafeafe1ee9ad04012fb03d46cd803ee4b', '[\"*\"]', NULL, '2022-01-22 17:59:23', '2022-01-22 17:59:23'),
(73, 'App\\User', 1015, 'METutor', 'db455dfc29dbce18c4117a2927219637eb0195eeeda868efb125a427b51afc50', '[\"*\"]', NULL, '2022-01-22 18:07:50', '2022-01-22 18:07:50'),
(74, 'App\\User', 995, 'METutor', 'dd372e70cbdcda70ff8a52f117c28476bb73899a801eb5c25dcbeff19d1c1781', '[\"*\"]', NULL, '2022-01-22 18:10:44', '2022-01-22 18:10:44'),
(75, 'App\\User', 995, 'METutor', '60e29e217ba7274aba839bf3c961eb65c4e8f45a215f7da446a2b577e7f3cd0b', '[\"*\"]', NULL, '2022-01-22 18:11:53', '2022-01-22 18:11:53'),
(76, 'App\\User', 995, 'METutor', 'ee6e9c4e7d11416d84373b4d5ea8e2582c358bb32cec38cfe7d017e2d9666f82', '[\"*\"]', NULL, '2022-01-22 18:12:17', '2022-01-22 18:12:17'),
(77, 'App\\User', 995, 'METutor', 'ddbaf953e1398b631b1d54d5b4345e41e3b7bf99ade9d8a04a6dec800a977d0b', '[\"*\"]', NULL, '2022-01-22 18:14:18', '2022-01-22 18:14:18'),
(78, 'App\\User', 995, 'METutor', '0c710c4775de3e46b26b0799e8f1dc7977b911d1d310bbf0538b015effbefb75', '[\"*\"]', NULL, '2022-01-22 18:18:04', '2022-01-22 18:18:04'),
(79, 'App\\User', 995, 'METutor', 'a3e41f1a9d3ea5bd139abb198d08181092e2d2ee0d087e9b8e9db01560350169', '[\"*\"]', NULL, '2022-01-22 18:19:20', '2022-01-22 18:19:20'),
(80, 'App\\User', 995, 'METutor', '24551d0a767c8847a4e25cd264ff584eb60bde8852485adb7775d56280e7ecd7', '[\"*\"]', NULL, '2022-01-22 18:20:42', '2022-01-22 18:20:42'),
(81, 'App\\User', 995, 'METutor', '801ac4ecbeda72c1be93ddaf80befbfe9f4f851accc2389c90c3adbaddbe2d5c', '[\"*\"]', NULL, '2022-01-22 18:22:19', '2022-01-22 18:22:19'),
(82, 'App\\User', 995, 'METutor', 'e7e441cd92cde158f307a17978101e8cfbc5c007bab02e0675ebfb4f83363ff3', '[\"*\"]', NULL, '2022-01-22 18:28:47', '2022-01-22 18:28:47'),
(83, 'App\\User', 995, 'METutor', '54fc67b04693806c8b098f3ac4faaefdae33c3fdfa5573ff93d375287947cc11', '[\"*\"]', NULL, '2022-01-22 18:30:08', '2022-01-22 18:30:08'),
(84, 'App\\User', 995, 'METutor', '704160c4caf73d6d1cb7a17693ee1b33347d722975452a899fd7a587122ced91', '[\"*\"]', NULL, '2022-01-22 18:30:36', '2022-01-22 18:30:36'),
(85, 'App\\User', 995, 'METutor', 'fe54886e1c220d596f94b292ea2f0c8fed90326eadcf19adcf9e41f341672880', '[\"*\"]', NULL, '2022-01-22 18:31:15', '2022-01-22 18:31:15'),
(86, 'App\\User', 995, 'METutor', 'd162ac2e7f64d2ed61a58e8ce72305e1bbad719426a51b296e8586e0506906b6', '[\"*\"]', NULL, '2022-01-22 18:32:38', '2022-01-22 18:32:38'),
(87, 'App\\User', 995, 'METutor', 'bdf330c1805d0dae2b0fa723dd6b8121ce3a55a0048406b395109b64d21be90c', '[\"*\"]', NULL, '2022-01-22 18:34:16', '2022-01-22 18:34:16'),
(88, 'App\\User', 995, 'METutor', '3cafd546fc566b896f04b14f8a6064b0d16830c0a8533f53de9579ff0762b2ff', '[\"*\"]', NULL, '2022-01-22 18:40:25', '2022-01-22 18:40:25'),
(89, 'App\\User', 995, 'METutor', '83efdf9c2580fd15721cbb8196b1e072db2b566f00a199d71d1a0d6097819ad5', '[\"*\"]', NULL, '2022-01-22 18:42:43', '2022-01-22 18:42:43'),
(90, 'App\\User', 995, 'METutor', '6bd2d02601fe1731bff1e4fd43316097909543f6e556cdb1e916dfdc5d0a0b3a', '[\"*\"]', NULL, '2022-01-22 20:20:03', '2022-01-22 20:20:03'),
(91, 'App\\User', 1015, 'METutor', '80c8f6aebfe313758d67501a27376536ef3f72c49f8bca921f40dee2899a32f7', '[\"*\"]', NULL, '2022-01-22 20:29:48', '2022-01-22 20:29:48'),
(92, 'App\\User', 1015, 'METutor', '9800da0866faba6d5279aa2da0a05f0e611116cf280773a82bebabd0d0cb5f0d', '[\"*\"]', NULL, '2022-01-22 20:31:32', '2022-01-22 20:31:32'),
(96, 'App\\User', 1069, 'METutor', 'f514f2a0405661a33a7ee76a13b70a87bcff9f28932c1378dd1c9aeeee027664', '[\"*\"]', NULL, '2022-01-23 02:35:52', '2022-01-23 02:35:52'),
(103, 'App\\User', 995, 'METutor', 'adedcb1d0b609d71a045822ed3c294cced536ec062e36286bd3494636eeddc5b', '[\"*\"]', '2022-01-23 06:34:21', '2022-01-23 05:50:49', '2022-01-23 06:34:21'),
(108, 'App\\User', 995, 'METutor', '328be07e7672c4275db317bf363fc9a079e4e1129e3e7ec08eebd599030dfc2a', '[\"*\"]', NULL, '2022-01-23 18:28:23', '2022-01-23 18:28:23'),
(109, 'App\\User', 1015, 'METutor', '3ef7e2b760c63fd086a3180cc30be3859408ca9c4efca614a92ed13b7b6457b0', '[\"*\"]', NULL, '2022-01-23 18:28:34', '2022-01-23 18:28:34'),
(140, 'App\\User', 995, 'METutor', '06eb6ea596ce7ab88e68da2a73c3108233cb3accfd53a1bcd08802f9f69007f2', '[\"*\"]', NULL, '2022-01-24 01:52:22', '2022-01-24 01:52:22'),
(141, 'App\\User', 1015, 'METutor', 'c1e68a71bc5220d19162006da944e213c5bdfa1992f2bb71e26fccb621525926', '[\"*\"]', NULL, '2022-01-24 01:52:32', '2022-01-24 01:52:32'),
(143, 'App\\User', 995, 'METutor', 'd4e4c613ec2fdfbd77c3d08bef0291d61118ca3d8b70c52b4cd219f1c3c52596', '[\"*\"]', NULL, '2022-01-24 01:57:20', '2022-01-24 01:57:20'),
(144, 'App\\User', 1015, 'METutor', 'e2f79656e96489bbc97a879af2d90e3d8977bd165ca74586a33a3ea9c0000c52', '[\"*\"]', NULL, '2022-01-24 01:57:31', '2022-01-24 01:57:31'),
(146, 'App\\User', 1078, 'METutor', '54d81d1d26bc52c08aa1621e0b413a3c84a3dd0dfe28c5605bae8e332cb7ea78', '[\"*\"]', NULL, '2022-01-24 02:00:57', '2022-01-24 02:00:57'),
(147, 'App\\User', 1079, 'METutor', '0e115ed1999977538244fd0c364017ea2e922f19a22ed5267aaa05cc0de7f871', '[\"*\"]', NULL, '2022-01-24 02:02:48', '2022-01-24 02:02:48'),
(148, 'App\\User', 1078, 'METutor', '11b9c67395636fead237341b8a51de7263f12ff547c4f8dbe53f752e47944ba3', '[\"*\"]', NULL, '2022-01-24 02:06:57', '2022-01-24 02:06:57'),
(149, 'App\\User', 1079, 'METutor', 'c56c280d93d28edf76436188d1ec9b5c59ad39e9119efbd698652949ad5b2edc', '[\"*\"]', NULL, '2022-01-24 02:08:22', '2022-01-24 02:08:22'),
(151, 'App\\User', 1080, 'METutor', 'cb196c9c6f5025dd02583a526c4c501217a182cc02b7dabad2d92c4b14a2125c', '[\"*\"]', NULL, '2022-01-24 02:12:51', '2022-01-24 02:12:51'),
(152, 'App\\User', 1081, 'METutor', '221916ec2cd96393575025a4646fb4bb81dc2cf25da979f4c3b0a4734e6a2a93', '[\"*\"]', NULL, '2022-01-24 02:15:05', '2022-01-24 02:15:05'),
(153, 'App\\User', 995, 'METutor', 'd69212e391aed5ab0aea08f975bdcaca31314f8cc038cdd427ef978571e732d3', '[\"*\"]', NULL, '2022-01-24 15:46:42', '2022-01-24 15:46:42'),
(154, 'App\\User', 1015, 'METutor', '4c3742c6fc838c266a424f391319644a404b8a1889d5b748dd82f95c6f6d49d2', '[\"*\"]', NULL, '2022-01-24 15:50:51', '2022-01-24 15:50:51'),
(156, 'App\\User', 1082, 'METutor', 'ce653511564829809b22e1105eb0e5c325212c4e4f162007146d3ac5748a9f52', '[\"*\"]', NULL, '2022-01-24 15:59:20', '2022-01-24 15:59:20'),
(157, 'App\\User', 1083, 'METutor', '5d1bfdcb3b3ced78a4ac2b18521f475131c4b71d3090eff8632e20d8e01b1b56', '[\"*\"]', NULL, '2022-01-24 16:01:39', '2022-01-24 16:01:39'),
(158, 'App\\User', 1083, 'METutor', '4943bc30cb12ff3823983b66bf3e950b88417dd562d6f8f9250701cbee322578', '[\"*\"]', NULL, '2022-01-24 16:07:50', '2022-01-24 16:07:50'),
(160, 'App\\User', 1015, 'METutor', 'c0fe758e5a0758e361481602447605b422717c56ed27195f1b5d37105c6dc6ba', '[\"*\"]', '2022-02-07 03:53:20', '2022-01-24 21:49:35', '2022-02-07 03:53:20'),
(161, 'App\\User', 995, 'METutor', '2dc8a594a4f722ed101681f30dc555a2ac56cd8ef9961cd8f61c38ab4627b103', '[\"*\"]', NULL, '2022-01-24 21:49:42', '2022-01-24 21:49:42'),
(162, 'App\\User', 995, 'METutor', '125325e014fd70eb1619ec87abc9a84e80c1e819bfbc5440cb856259de532a07', '[\"*\"]', NULL, '2022-01-24 21:52:07', '2022-01-24 21:52:07'),
(163, 'App\\User', 995, 'METutor', '9a9530d9069f8a6e02cd6e96d186807837498aa5effc5d2d7e7a1e42346ffdd1', '[\"*\"]', NULL, '2022-01-25 00:04:49', '2022-01-25 00:04:49'),
(164, 'App\\User', 995, 'METutor', 'bc5aa78f819d5987767588cbd8f0527c7e859c08d9547c06f0fa5da8e7a6a5e6', '[\"*\"]', NULL, '2022-01-25 00:08:11', '2022-01-25 00:08:11'),
(165, 'App\\User', 995, 'METutor', '9f702a071a0c091e1e4a8f65094859462ce25b74b4197bc07d3be59860be7621', '[\"*\"]', NULL, '2022-01-25 00:09:53', '2022-01-25 00:09:53'),
(166, 'App\\User', 995, 'METutor', 'cfc285adea8fbc6d33d16447c383d40c4e7479ce23bb0f6ec059fdc2d18f287a', '[\"*\"]', NULL, '2022-01-25 00:11:11', '2022-01-25 00:11:11'),
(167, 'App\\User', 995, 'METutor', '201dafaaedef5806d77a8ff815464c709a798cbb53be71a5091b5c27d25eff93', '[\"*\"]', NULL, '2022-01-25 00:19:38', '2022-01-25 00:19:38'),
(168, 'App\\User', 1015, 'METutor', '8d32462698c1940300342cec392e2f43e8781cbbd050bc7da30bf859c30d3ad1', '[\"*\"]', NULL, '2022-01-25 00:19:54', '2022-01-25 00:19:54'),
(170, 'App\\User', 995, 'METutor', 'f00b9ab558bc1d3726cf203e6a23d250b4258b3eeee8ffb81aabafa7e54c0589', '[\"*\"]', NULL, '2022-01-25 00:20:19', '2022-01-25 00:20:19'),
(171, 'App\\User', 995, 'METutor', '522ac5ff60dfa0d546fff72e364063ec74285be04b68f835a069514935d14f41', '[\"*\"]', NULL, '2022-01-25 00:22:49', '2022-01-25 00:22:49'),
(172, 'App\\User', 995, 'METutor', '1604e15be316cd015e55dee2fa3fc51b04e8c8eec8366d2e2cc745ca7cf5ac87', '[\"*\"]', NULL, '2022-01-25 00:24:09', '2022-01-25 00:24:09'),
(173, 'App\\User', 995, 'METutor', '2eb08ff51b3c7cbad7ae47bffaf2d1d526762f360b2bd0a1705be670bdb4d0ca', '[\"*\"]', NULL, '2022-01-25 00:26:05', '2022-01-25 00:26:05'),
(174, 'App\\User', 995, 'METutor', 'fb99cb3b699ea65b89c679da92e88bf3e4db516aac0f9c20edfeb60450493c27', '[\"*\"]', NULL, '2022-01-25 01:03:02', '2022-01-25 01:03:02'),
(175, 'App\\User', 995, 'METutor', 'daff8b442d4a85e6e02e96486a9b3f8b32e3e74795d5f5afd8d13ae181e06c52', '[\"*\"]', NULL, '2022-01-25 01:05:12', '2022-01-25 01:05:12'),
(176, 'App\\User', 1078, 'METutor', '5662bda28fe4eed2fb776c6cbe4977fc3790e727fa36c269fd0d9fae196aa702', '[\"*\"]', NULL, '2022-01-25 01:16:40', '2022-01-25 01:16:40'),
(177, 'App\\User', 1078, 'METutor', 'd0c7165f76917e836e6be23dda08acc95414347ecdff4a26f38fdad40d8613b6', '[\"*\"]', NULL, '2022-01-25 01:38:18', '2022-01-25 01:38:18'),
(180, 'App\\User', 1015, 'METutor', '7949524b8603852a278c00848d54272df306c28f2c3211ff4ac29835308f1d1d', '[\"*\"]', NULL, '2022-01-25 02:14:49', '2022-01-25 02:14:49'),
(181, 'App\\User', 995, 'METutor', '0ff1d8e13dbf93621e53177425c8ad1e60af867fb78731e7c82a30bd1b900495', '[\"*\"]', NULL, '2022-01-25 02:15:01', '2022-01-25 02:15:01'),
(183, 'App\\User', 1015, 'METutor', 'ab7df21f4a20271ad4d682c0a3c2df4c9ecf928d1161abfe8101ceb05662b629', '[\"*\"]', '2022-01-25 02:41:25', '2022-01-25 02:41:12', '2022-01-25 02:41:25'),
(194, 'App\\User', 995, 'METutor', 'f27e3b2a45f966ec36d24dcad8fe7a4ce34dc919cf502d9d37adfb5c9ae71002', '[\"*\"]', NULL, '2022-01-28 04:10:00', '2022-01-28 04:10:00'),
(195, 'App\\User', 1015, 'METutor', 'abd945bb92f08d96e8b3761b7843cc2292ae1b1dc86890a130f8b2aa721d1aca', '[\"*\"]', NULL, '2022-01-28 04:10:18', '2022-01-28 04:10:18'),
(202, 'App\\User', 995, 'METutor', '2c7aa3b66674baea7c0233496508a4f4c7f68b3d54b715f459c250c52bdeb07e', '[\"*\"]', NULL, '2022-01-28 04:18:04', '2022-01-28 04:18:04'),
(203, 'App\\User', 1015, 'METutor', 'b1ad915129acdb9b0893c3b97eaec6ed8d8862e6f4cfe3c819d100b140d10568', '[\"*\"]', NULL, '2022-01-28 04:18:35', '2022-01-28 04:18:35'),
(204, 'App\\User', 1015, 'METutor', 'f3b519f94236e9b679abbe123883257c9206982ac3bd19896eefe33db2b0f0b4', '[\"*\"]', NULL, '2022-01-28 04:18:43', '2022-01-28 04:18:43'),
(206, 'App\\User', 1015, 'METutor', '631d338707198610cc1b3fe9b316b5800dd6d0518d957651176657bfb770ecb7', '[\"*\"]', NULL, '2022-01-28 15:59:25', '2022-01-28 15:59:25'),
(207, 'App\\User', 1015, 'METutor', '7408ca40518849d6d20de82abd5af3f169adc458e0d395e114c8e6eb489fa852', '[\"*\"]', NULL, '2022-01-28 16:03:57', '2022-01-28 16:03:57'),
(208, 'App\\User', 995, 'METutor', '88126ae3643c9909e6614e72b3881badcfb5f17d28429c3b46276e97c4a92224', '[\"*\"]', NULL, '2022-01-28 16:06:30', '2022-01-28 16:06:30'),
(210, 'App\\User', 995, 'METutor', 'b4534ff0047a5204857f740d7634010c7485eba82f290213f656538f31449b2a', '[\"*\"]', NULL, '2022-01-28 16:07:12', '2022-01-28 16:07:12'),
(211, 'App\\User', 1015, 'METutor', '6c11c218ca51811e55e2a91ae99334442fa4471d375522b9bd62ec3fbe30dfea', '[\"*\"]', NULL, '2022-01-28 16:22:58', '2022-01-28 16:22:58'),
(212, 'App\\User', 1015, 'METutor', 'a0514157db21425b11eddfb8d85febc5c467c682ae9f9ea300a21836433565e4', '[\"*\"]', NULL, '2022-01-28 16:24:15', '2022-01-28 16:24:15'),
(213, 'App\\User', 1015, 'METutor', 'a76943e946a36faa5fac097c2901e47133b316d339712fbe0a50af96b4f2daba', '[\"*\"]', NULL, '2022-01-28 16:28:40', '2022-01-28 16:28:40'),
(214, 'App\\User', 1015, 'METutor', '84d6da5625bf4db3394bb00dddf71603b6ed5e32fa3342801f807a1fc85de17e', '[\"*\"]', NULL, '2022-01-28 16:28:56', '2022-01-28 16:28:56'),
(215, 'App\\User', 1015, 'METutor', '931869bb8610d4ce57c65cfd6e4a5216512e20ea29a4bc53f74c0f14b31ed305', '[\"*\"]', NULL, '2022-01-28 16:34:13', '2022-01-28 16:34:13'),
(216, 'App\\User', 1015, 'METutor', '0b6e8dccd62845c85dd47666983f0a8eb10aa505b59644f1b8cf6440bfc45057', '[\"*\"]', NULL, '2022-01-28 16:35:11', '2022-01-28 16:35:11'),
(217, 'App\\User', 1015, 'METutor', 'a756d3fd7a247cf3a4721abd46d4394b9e027b6b6e76be410c34e976b4f55dea', '[\"*\"]', NULL, '2022-01-28 16:37:28', '2022-01-28 16:37:28'),
(218, 'App\\User', 1015, 'METutor', '4c598146df963fdac4fe856a43469b0bb1a2ad8830515a2840e436fc5ed6106b', '[\"*\"]', NULL, '2022-01-28 16:38:46', '2022-01-28 16:38:46'),
(219, 'App\\User', 1015, 'METutor', '592925fd3ab2d82ed95ff93deeebfb03d55401b46da883b9de40413cd0145076', '[\"*\"]', NULL, '2022-01-28 16:39:47', '2022-01-28 16:39:47'),
(220, 'App\\User', 1015, 'METutor', '7684b6ffee67c8b3d95a6d8582279cdd819c4cfe9f3332448f66dac0315e00ab', '[\"*\"]', NULL, '2022-01-28 16:44:35', '2022-01-28 16:44:35'),
(221, 'App\\User', 1015, 'METutor', '14706ecf864bf474defd9245eda39eef595f1e2e7ebccf549c75458b065dc601', '[\"*\"]', NULL, '2022-01-28 16:44:47', '2022-01-28 16:44:47'),
(222, 'App\\User', 1015, 'METutor', 'de7767d1c42dc83422f06e3c12f1045362346a5913b15bbfcd888ac7a4764eaf', '[\"*\"]', NULL, '2022-01-28 16:47:50', '2022-01-28 16:47:50'),
(223, 'App\\User', 1015, 'METutor', '2183eb241eae13e48e654c68864e30a0e06c08cc6d8b50e4d8e4b461ec8ae3ae', '[\"*\"]', NULL, '2022-01-28 16:49:51', '2022-01-28 16:49:51'),
(224, 'App\\User', 1015, 'METutor', '97acc396429379bd53dbaa8a5bbed6c0a4ead5e264213b4c0c92aed7eb608b53', '[\"*\"]', NULL, '2022-01-28 16:50:43', '2022-01-28 16:50:43'),
(225, 'App\\User', 1015, 'METutor', '0daab59c6e4bafa1a499ed5b547e3caa64cd80ad9df2468ce7a87c92002980d5', '[\"*\"]', NULL, '2022-01-28 16:51:40', '2022-01-28 16:51:40'),
(226, 'App\\User', 1015, 'METutor', 'a12a0f0f2829033c68e4d6825019a41adb001d2f1be188d90d973994ac02cfea', '[\"*\"]', NULL, '2022-01-28 16:51:53', '2022-01-28 16:51:53'),
(227, 'App\\User', 1015, 'METutor', '0c9ddef7a9c6e9e02ee76f077f493cb9626dfe56488376b23703b63b7eb7361d', '[\"*\"]', NULL, '2022-01-28 17:10:43', '2022-01-28 17:10:43'),
(228, 'App\\User', 1015, 'METutor', '233cd97223e14cc96de2281cf657397b14a3a3ceda60fa1776960f44cd4992e2', '[\"*\"]', NULL, '2022-01-28 17:11:05', '2022-01-28 17:11:05'),
(229, 'App\\User', 1015, 'METutor', '277a1b7308331c30d5b3832bd476b928b4a14c88c73e1b364a806667322e2311', '[\"*\"]', NULL, '2022-01-28 17:17:43', '2022-01-28 17:17:43'),
(230, 'App\\User', 1015, 'METutor', '0d2d0835a2d115b6b4dda2b57967b830e1e214c3b97808aadb2825b7eb37ca39', '[\"*\"]', NULL, '2022-01-28 17:18:01', '2022-01-28 17:18:01'),
(231, 'App\\User', 1015, 'METutor', '99a8955e7359bb49e4193186f1da6b58edba4995405fc5a0eb4360d1956054d2', '[\"*\"]', NULL, '2022-01-28 21:54:54', '2022-01-28 21:54:54'),
(232, 'App\\User', 1095, 'METutor', '015b60e7002f07c6118dbb2525dcfeddb295d847424151518eaf1d3eb57fdca9', '[\"*\"]', NULL, '2022-01-29 01:02:05', '2022-01-29 01:02:05'),
(234, 'App\\User', 995, 'METutor', 'cad42d99b78c7fffd0193f6a30ab8a3eda059be49585646ccea200ca58524193', '[\"*\"]', NULL, '2022-01-29 01:37:02', '2022-01-29 01:37:02'),
(235, 'App\\User', 995, 'METutor', '38ecd0468e4852136b54d692ad76cf63d675b8ab322916718d08fd4d2920d902', '[\"*\"]', NULL, '2022-01-29 01:42:55', '2022-01-29 01:42:55'),
(236, 'App\\User', 995, 'METutor', '732eeb967e925e66a9ff9685e7bb2ee400bc0ed5d67a923958982575464ed054', '[\"*\"]', NULL, '2022-01-29 01:45:14', '2022-01-29 01:45:14'),
(237, 'App\\User', 995, 'METutor', '35076c6cafc68a5904998125e52dc1d1df79d38f4a72aa6f07666034005a832f', '[\"*\"]', NULL, '2022-01-29 01:49:02', '2022-01-29 01:49:02'),
(238, 'App\\User', 995, 'METutor', '8d19d8d90d62fd71f61b0bc8fb61db99d3f7aaae96d6918a7deee63e4f144301', '[\"*\"]', NULL, '2022-01-29 01:49:25', '2022-01-29 01:49:25'),
(239, 'App\\User', 995, 'METutor', 'ac72204c38a3c6cdd4f84750d61d233c2b1c540a232d02ab7f03e4e61f21610d', '[\"*\"]', NULL, '2022-01-29 01:50:16', '2022-01-29 01:50:16'),
(240, 'App\\User', 995, 'METutor', '019744592344ae5fec08489748d65081bd13890a22ea75b9660c3305a987243a', '[\"*\"]', NULL, '2022-01-29 01:51:11', '2022-01-29 01:51:11'),
(241, 'App\\User', 995, 'METutor', '9ab9c284bee6c9f3dccbf8091bffa71f85560bd710009517e83d67e778e7143f', '[\"*\"]', NULL, '2022-01-29 01:54:20', '2022-01-29 01:54:20'),
(242, 'App\\User', 995, 'METutor', '5eb9effebfe631f84e8aded5f3545c3ca3c629805c495c4889944789915493e1', '[\"*\"]', NULL, '2022-01-29 01:54:55', '2022-01-29 01:54:55'),
(243, 'App\\User', 995, 'METutor', 'e0a2c8c0ee596f9c7ab65f6d94120002188b727e6f47338c6f1cbc88f5acea17', '[\"*\"]', NULL, '2022-01-29 02:00:51', '2022-01-29 02:00:51'),
(244, 'App\\User', 995, 'METutor', '6ae0bdca75515d2974a6061fbd7dace490e887f1a6ce3ed62481e28dfccf043a', '[\"*\"]', NULL, '2022-01-29 02:01:18', '2022-01-29 02:01:18'),
(245, 'App\\User', 995, 'METutor', '9239baf32731f924437529f4495e17bc349472ea52dc2790920ec754c24766dd', '[\"*\"]', NULL, '2022-01-29 02:01:42', '2022-01-29 02:01:42'),
(272, 'App\\User', 995, 'METutor', '0579cb452ff5fc825a1c9dc4b117fd069ec9ad46bc4c3724ee7e33e0faed51ef', '[\"*\"]', NULL, '2022-01-31 20:02:43', '2022-01-31 20:02:43'),
(273, 'App\\User', 995, 'METutor', 'c609f570192fcacdfec01a172d294b237ba197398b38ef3f8851fb015cc84d0a', '[\"*\"]', NULL, '2022-01-31 20:04:29', '2022-01-31 20:04:29'),
(274, 'App\\User', 995, 'METutor', '20d0e8caa6ec8cddf72c0f4b751812402a1df4f449d454b6245529d46b6e0856', '[\"*\"]', NULL, '2022-01-31 20:08:56', '2022-01-31 20:08:56'),
(275, 'App\\User', 995, 'METutor', 'e5a09988bf410ce31dc6564c744226378855bdb70a1fc7e5d3b458fc9d95703a', '[\"*\"]', NULL, '2022-01-31 20:10:51', '2022-01-31 20:10:51'),
(276, 'App\\User', 995, 'METutor', '45f5dcb04976999a810995dec9d79f7a5309183273d0d8ccd7e430a72e06a4a0', '[\"*\"]', NULL, '2022-01-31 20:11:13', '2022-01-31 20:11:13'),
(277, 'App\\User', 995, 'METutor', 'e2794818dd4a6761cb23eb7ba212982aa3891ae0d9a7d94cb86d12d9b7de79dc', '[\"*\"]', NULL, '2022-01-31 20:12:11', '2022-01-31 20:12:11'),
(278, 'App\\User', 995, 'METutor', '6f62aba36d86b46ac4302d4e66a95a1463fb6d265e7eecbbd1f7163bc043eea4', '[\"*\"]', NULL, '2022-01-31 20:13:58', '2022-01-31 20:13:58'),
(279, 'App\\User', 995, 'METutor', 'fd42446cd572e485229a3651d9dd48be3cd7cd7b1a82b0b2963e52a24b80aa43', '[\"*\"]', NULL, '2022-01-31 20:14:53', '2022-01-31 20:14:53'),
(280, 'App\\User', 995, 'METutor', 'dcdca6e68335c5caf9aa605b53aaf3b44bcaffa320c56be3fbbc4fc7265dd99f', '[\"*\"]', NULL, '2022-01-31 20:16:51', '2022-01-31 20:16:51'),
(281, 'App\\User', 995, 'METutor', '1b8f41d095814874d4d99b5a2dcb74093088f50033f94303343029ecbf7093f7', '[\"*\"]', NULL, '2022-01-31 20:17:11', '2022-01-31 20:17:11'),
(282, 'App\\User', 1, 'METutor', '3a4f8fdc56540fb751ef8f05be6c99a4467a78470f2bdc9f3b3241362637c4fd', '[\"*\"]', '2022-01-31 20:40:37', '2022-01-31 20:27:07', '2022-01-31 20:40:37'),
(283, 'App\\User', 995, 'METutor', 'a270156980b76c0a9791fbccc15d082e43056f62c77c7d7014f28874801ed11d', '[\"*\"]', NULL, '2022-01-31 21:14:39', '2022-01-31 21:14:39'),
(284, 'App\\User', 1, 'METutor', 'c7f2c9968d0c499a1ed46b6ce25dd6fed9002d8424380406110eb5edb7032d7f', '[\"*\"]', '2022-02-01 02:43:20', '2022-02-01 02:42:06', '2022-02-01 02:43:20'),
(285, 'App\\User', 1, 'METutor', '7c9a8779353a96f6854772b8a00bb6cc24f25c74e5db22014ab60ae990f6d69f', '[\"*\"]', '2022-02-01 02:43:59', '2022-02-01 02:43:32', '2022-02-01 02:43:59'),
(286, 'App\\User', 1078, 'METutor', '15566cef73d859d962f6fc350ad7e81e016321e885a773a0be38a8c7de1faf02', '[\"*\"]', NULL, '2022-02-01 02:46:47', '2022-02-01 02:46:47'),
(287, 'App\\User', 1078, 'METutor', 'fd0050d07620bb323eb57dd7427b0be26e95bfba29a66fe324f3e1f6f9bcc12d', '[\"*\"]', NULL, '2022-02-01 02:47:11', '2022-02-01 02:47:11'),
(288, 'App\\User', 1, 'METutor', 'a34fe9c7c1bd25184e0cfe44d0fa3dd1193299a65cdfacfbaeea721351454716', '[\"*\"]', '2022-02-01 03:06:54', '2022-02-01 03:06:28', '2022-02-01 03:06:54'),
(289, 'App\\User', 1, 'METutor', 'd0898aaeb7867ee1f7e5b839890de9e24e87b670748b24730134a350dec0f89c', '[\"*\"]', '2022-02-01 03:07:19', '2022-02-01 03:07:11', '2022-02-01 03:07:19'),
(290, 'App\\User', 1, 'METutor', '4238ad650e97535737f7f3b3cad119598a132f2ec73a452b99ff19ed24de6e24', '[\"*\"]', NULL, '2022-02-01 03:07:32', '2022-02-01 03:07:32'),
(291, 'App\\User', 1, 'METutor', '5e98039c73efa04ceef32de2cbcb5d30f560998ec182bde198047f6bdd3ea992', '[\"*\"]', NULL, '2022-02-01 03:08:22', '2022-02-01 03:08:22'),
(292, 'App\\User', 1, 'METutor', 'ea903440858f94ad1157683be66c849f4524ce26ae6a97a7917cb0412df7f5fc', '[\"*\"]', '2022-02-01 03:11:15', '2022-02-01 03:11:08', '2022-02-01 03:11:15'),
(293, 'App\\User', 1, 'METutor', 'abb0094eeda6e13836278920368faf6f38a612e1af1f6d9008a416003600b4a5', '[\"*\"]', NULL, '2022-02-01 03:15:59', '2022-02-01 03:15:59'),
(294, 'App\\User', 1, 'METutor', '9fe6aa2851f9c09bc26691a87d1f0b074da38d2984db425fc840d0f0a674cb10', '[\"*\"]', NULL, '2022-02-01 03:16:11', '2022-02-01 03:16:11'),
(295, 'App\\User', 1, 'METutor', 'dff02743163aa0a913128f30e3de6496438fb5439ed33d4eac4cf1bf0216b449', '[\"*\"]', NULL, '2022-02-01 03:18:00', '2022-02-01 03:18:00'),
(296, 'App\\User', 1, 'METutor', '3f9531e97e3edb758eb2c10f4c09bd38249c3e6a24482ae785bdd838f393b54d', '[\"*\"]', NULL, '2022-02-01 03:18:11', '2022-02-01 03:18:11'),
(297, 'App\\User', 1, 'METutor', 'bc5dba41a5a77b9317d4d515a7fa94565ed7397b6f9d95dbf29cc766dba83215', '[\"*\"]', NULL, '2022-02-01 03:19:38', '2022-02-01 03:19:38'),
(298, 'App\\User', 1, 'METutor', '64d466df0119f7df7a370e907ef25f0c31ccee03727074d9d9ca229c506a71ed', '[\"*\"]', '2022-02-01 03:19:57', '2022-02-01 03:19:51', '2022-02-01 03:19:57'),
(299, 'App\\User', 1, 'METutor', 'e0aebf7815abc0a9c33f6cb32b643a58445a4552216d4d2939bbef18b1e7572f', '[\"*\"]', '2022-02-01 03:20:32', '2022-02-01 03:20:06', '2022-02-01 03:20:32'),
(300, 'App\\User', 1, 'METutor', '3c1210efc0520a036f3ae72867532bb5fbc2f3c8d72de28a8241ab5f7d91a285', '[\"*\"]', '2022-02-01 03:22:21', '2022-02-01 03:21:57', '2022-02-01 03:22:21'),
(301, 'App\\User', 1, 'METutor', '3c8b3b47200252a7ee1c6b1ac8ac2a037cef081cc017cbbdf2b6b8000c3659c3', '[\"*\"]', NULL, '2022-02-01 03:25:55', '2022-02-01 03:25:55'),
(302, 'App\\User', 1, 'METutor', '8c96b7b6fca878f9eba87057f2993e8b8d183f9a365007c864c1c38d78ff337c', '[\"*\"]', NULL, '2022-02-01 03:29:24', '2022-02-01 03:29:24'),
(303, 'App\\User', 1, 'METutor', 'bc03c1a7e94b92384af27eb6cc957ab2523109abc6ded187b5dae749b35a38bd', '[\"*\"]', '2022-02-01 03:30:11', '2022-02-01 03:29:36', '2022-02-01 03:30:11'),
(304, 'App\\User', 1, 'METutor', 'a60e02d092c0a2b29ee1ba60ce57d1d11aee09340af344d0f1c5635476a1704d', '[\"*\"]', NULL, '2022-02-01 03:30:22', '2022-02-01 03:30:22'),
(305, 'App\\User', 1, 'METutor', '59d806e426eda93da1cc912022b05e1b03ff57733660bcedbc25f319e984f87c', '[\"*\"]', '2022-02-01 03:30:40', '2022-02-01 03:30:31', '2022-02-01 03:30:40'),
(306, 'App\\User', 1, 'METutor', 'c01c39a90adf65b7fdfa4d4cc28dd3a12cf50ce574c8983420b0541e7a9e8781', '[\"*\"]', '2022-02-01 03:31:20', '2022-02-01 03:30:49', '2022-02-01 03:31:20'),
(307, 'App\\User', 1, 'METutor', '4b5c2c8a78a9074d25a80c8adf3da0cc97501365ea5f0c033414909db017c33c', '[\"*\"]', '2022-02-01 03:31:56', '2022-02-01 03:31:29', '2022-02-01 03:31:56'),
(308, 'App\\User', 1, 'METutor', '360e03e3c94b279092f799f01f9d7f0fd4885eb4af91f7bd2a04bc633171cc89', '[\"*\"]', '2022-02-01 03:32:16', '2022-02-01 03:32:04', '2022-02-01 03:32:16'),
(309, 'App\\User', 1, 'METutor', '0c6ed14e9e44bca20dd114ff95ca23042bb74e7182b703f0f4b2b7dec9181804', '[\"*\"]', '2022-02-01 03:32:52', '2022-02-01 03:32:39', '2022-02-01 03:32:52'),
(310, 'App\\User', 995, 'METutor', 'e3a0f30b39c3b0cda06ce222236d97faeaa0066f754fc5402ec211f45e433e1e', '[\"*\"]', '2022-02-01 06:41:58', '2022-02-01 06:17:51', '2022-02-01 06:41:58'),
(311, 'App\\User', 1015, 'METutor', '5da6b1fc0a1b3b50751b1387108885a749325019440d5aa5eacce230d0f319a8', '[\"*\"]', NULL, '2022-02-02 00:39:12', '2022-02-02 00:39:12'),
(312, 'App\\User', 1, 'METutor', 'fe495d7ae13728ae488072dd47d81c56abfd5bf0aef511a93e777f0cab1f2b08', '[\"*\"]', '2022-02-02 01:42:47', '2022-02-02 01:42:26', '2022-02-02 01:42:47'),
(313, 'App\\User', 1, 'METutor', '72efd7ec51c85a7da25a9a96b2e4bd66ffafe0d89cb74bfb4f8a62799492674b', '[\"*\"]', '2022-02-02 01:44:13', '2022-02-02 01:43:11', '2022-02-02 01:44:13'),
(314, 'App\\User', 1, 'METutor', '11ce9b7c004375597ea509dd12d2ad3658b0edf3923045cb43630b24565c9366', '[\"*\"]', NULL, '2022-02-02 01:44:28', '2022-02-02 01:44:28'),
(315, 'App\\User', 1, 'METutor', '68ae81b94d05203f98461b71233e78ce599e1f591ec4bf413133d256c96feffd', '[\"*\"]', '2022-02-02 01:45:07', '2022-02-02 01:44:53', '2022-02-02 01:45:07'),
(316, 'App\\User', 1, 'METutor', '3013f40aa15815b6dbacdbf5e6aa890236950b673869c0f6a954cd04acd193bd', '[\"*\"]', '2022-02-02 01:46:36', '2022-02-02 01:45:28', '2022-02-02 01:46:36'),
(317, 'App\\User', 1015, 'METutor', '7192ffb5b80de13ab0fb493c713fc71397eae8d044503a201d3cefa2a655a5b7', '[\"*\"]', NULL, '2022-02-02 01:47:16', '2022-02-02 01:47:16'),
(318, 'App\\User', 1111, 'METutor', 'e8ecf84499869125ca56f1bf6345c0627f72b7ea890a08ee67b3223169c98136', '[\"*\"]', NULL, '2022-02-02 02:07:16', '2022-02-02 02:07:16'),
(319, 'App\\User', 1111, 'METutor', '552b31ae79f082589c9dacd386ef0be72254999dee99b69f39d5828a14ca5c11', '[\"*\"]', NULL, '2022-02-02 02:08:11', '2022-02-02 02:08:11'),
(320, 'App\\User', 1093, 'METutor', 'd40369f7ea40bde6cc3a757a38a3f854d728ab51257d64093469a49def149f22', '[\"*\"]', NULL, '2022-02-02 02:09:18', '2022-02-02 02:09:18'),
(321, 'App\\User', 1114, 'METutor', '1d488e3422d0ee68b665b4c4c7a344dc331b03685cd5253f9f6c5abb0777224e', '[\"*\"]', NULL, '2022-02-02 02:14:08', '2022-02-02 02:14:08'),
(322, 'App\\User', 1114, 'METutor', '19ca951670c1c8e4112d35e4e0c3aa198bf36286340c33cb89f303fbed44f277', '[\"*\"]', NULL, '2022-02-02 02:14:27', '2022-02-02 02:14:27'),
(323, 'App\\User', 1114, 'METutor', 'c9c0ceda6f7a1dd7d69b7fc3c2f3186f3ee7ff180e6a726478f5d7d5c3a20b26', '[\"*\"]', NULL, '2022-02-02 02:14:36', '2022-02-02 02:14:36'),
(324, 'App\\User', 1114, 'METutor', 'ace665933291ab12d669bd119fb7aa496d579aeff3cd4b394696228c02afae9d', '[\"*\"]', NULL, '2022-02-02 02:24:52', '2022-02-02 02:24:52'),
(325, 'App\\User', 1114, 'METutor', 'd36a3cb30864be382a229a9650622801420e10e96a2fb0b9ed27a479a6888fdd', '[\"*\"]', NULL, '2022-02-02 02:54:45', '2022-02-02 02:54:45'),
(326, 'App\\User', 1015, 'METutor', '5cf839f9a3a01617e28eb23da115a2a3ac8641a59fce9fb68013569147af0943', '[\"*\"]', NULL, '2022-02-02 02:56:24', '2022-02-02 02:56:24'),
(327, 'App\\User', 1095, 'METutor', 'd6bf631a317346f193696dfe6bf2c4624560e7345a0167483f4f71d5ca865743', '[\"*\"]', NULL, '2022-02-02 03:00:43', '2022-02-02 03:00:43'),
(328, 'App\\User', 1095, 'METutor', '545fdd0e1976d3a4255f2402beac6eccc36fa9b9fbf905e45de118a9eb7f1b65', '[\"*\"]', NULL, '2022-02-02 03:05:08', '2022-02-02 03:05:08'),
(329, 'App\\User', 1095, 'METutor', '57f4241673b189d4e61f1738d55105330aac69d518c41ef0fb78652db16ee50f', '[\"*\"]', NULL, '2022-02-02 03:05:30', '2022-02-02 03:05:30'),
(330, 'App\\User', 1095, 'METutor', '9d7ae165bdcd3aced8a7dfd4cb922cc01d6368fe785efa97598d1c22a1be4a1b', '[\"*\"]', NULL, '2022-02-02 03:13:33', '2022-02-02 03:13:33'),
(331, 'App\\User', 1095, 'METutor', '51acdc96da1904f4af9e53b6cc50db3c0f5c7891e79f71f331fcbd15a1555625', '[\"*\"]', NULL, '2022-02-02 03:13:52', '2022-02-02 03:13:52'),
(332, 'App\\User', 1095, 'METutor', '43b7c213f2ef6050600861dd9f6e186afe0de7d1d2ec1f6c1704fe7c11f860ee', '[\"*\"]', NULL, '2022-02-02 03:14:11', '2022-02-02 03:14:11'),
(333, 'App\\User', 1095, 'METutor', '4c773edf1957c05223e375b5e354ee7814ed9fecfc8144b7a1ba5e8f63e8bd29', '[\"*\"]', NULL, '2022-02-02 03:14:30', '2022-02-02 03:14:30'),
(334, 'App\\User', 1095, 'METutor', 'df56db4f1078731a888ce12d08d0f37961c1943204719947dfe0d768d7e05816', '[\"*\"]', NULL, '2022-02-02 03:14:49', '2022-02-02 03:14:49'),
(335, 'App\\User', 1095, 'METutor', 'de0daba5344fbfd8c0b31f3b8a111ee03a0a432b0886c0be4dc1d678ecb449b2', '[\"*\"]', NULL, '2022-02-02 03:15:07', '2022-02-02 03:15:07'),
(336, 'App\\User', 1, 'METutor', '73426555fec5485dbec1dfae9d2720108d9b5ffdac88e05fbf49834e4b7cfd43', '[\"*\"]', NULL, '2022-02-02 05:22:34', '2022-02-02 05:22:34'),
(337, 'App\\User', 1, 'METutor', 'f94d1b206ce6785f808bd015db94b590c5729ab0cfedb40079206e42bdd68974', '[\"*\"]', NULL, '2022-02-02 05:23:28', '2022-02-02 05:23:28'),
(338, 'App\\User', 1, 'METutor', '6e110ff66caadffa979972bf94e11111a26aee749a0ad383131e83ec48a19298', '[\"*\"]', NULL, '2022-02-02 22:53:17', '2022-02-02 22:53:17'),
(339, 'App\\User', 1015, 'METutor', 'bbbbb319115539b6fca4f5f419911e2c9973645284c82a89304da366343764c2', '[\"*\"]', NULL, '2022-02-03 00:07:10', '2022-02-03 00:07:10'),
(340, 'App\\User', 1, 'METutor', 'f67c23ba9b9da8e149e6087120469953044405d563aef0fc2bb04821fb13cc13', '[\"*\"]', NULL, '2022-02-03 00:28:04', '2022-02-03 00:28:04'),
(341, 'App\\User', 1, 'METutor', '6f4375754aa4ed4f4f2c08623499f89c3a6323730b88753555e3fb0126d15376', '[\"*\"]', NULL, '2022-02-03 00:29:15', '2022-02-03 00:29:15'),
(342, 'App\\User', 1, 'METutor', 'a8e1160df2ab079d88e445761c428a3f4b6a4da356782bdd6cd3096b769d55ca', '[\"*\"]', NULL, '2022-02-03 00:29:53', '2022-02-03 00:29:53'),
(343, 'App\\User', 1, 'METutor', '6df37ade27599fc64005c24090f17cab58b1fa7b95fdce0e1565ba549dbe9381', '[\"*\"]', NULL, '2022-02-03 00:30:08', '2022-02-03 00:30:08'),
(344, 'App\\User', 1, 'METutor', '1b3205efd500391b59a650032ec51cd74667009152e55521a49caf849c109086', '[\"*\"]', NULL, '2022-02-03 00:34:57', '2022-02-03 00:34:57'),
(345, 'App\\User', 1, 'METutor', '2a8994755355e529bae99fe5c074316e05834d4df434f9322925f08548714870', '[\"*\"]', NULL, '2022-02-03 00:35:11', '2022-02-03 00:35:11'),
(346, 'App\\User', 1, 'METutor', '9aa5214ac1b6e1db034602662b9e35f192e134ffd5ea49023a54e6ed3f63d5b5', '[\"*\"]', NULL, '2022-02-03 00:35:27', '2022-02-03 00:35:27'),
(347, 'App\\User', 1, 'METutor', 'a4a7b80baae12ef3f2fdff1676e5b0c7d39c7a2a579eca761a14b400e57497f3', '[\"*\"]', NULL, '2022-02-03 00:35:44', '2022-02-03 00:35:44'),
(348, 'App\\User', 995, 'METutor', 'f89b61b16a4bc0f95da78cc38a3ae809b828eb401d8e2400d0dcb778c9c6d737', '[\"*\"]', '2022-02-03 00:36:40', '2022-02-03 00:36:13', '2022-02-03 00:36:40'),
(349, 'App\\User', 1, 'METutor', '39d7a9888144eceeb083227779731bbf3d04a1b0b3031caaf06f8029f1fb83f7', '[\"*\"]', NULL, '2022-02-03 00:39:32', '2022-02-03 00:39:32'),
(350, 'App\\User', 1, 'METutor', '6988bc6f50cc030a06896c09847a6e943d16ee922feb29fa27361d33dd2c3e4f', '[\"*\"]', NULL, '2022-02-03 00:40:56', '2022-02-03 00:40:56'),
(351, 'App\\User', 1, 'METutor', 'c8f7402e219c4051bb8940ceb6190a67a47e67ce61fb4dc2499befe8114c5186', '[\"*\"]', NULL, '2022-02-03 00:42:15', '2022-02-03 00:42:15'),
(352, 'App\\User', 1, 'METutor', '4d244d58c2b20a5881d3b9ef5179e30b18ca00700c66f0d52f1acf848f36b238', '[\"*\"]', NULL, '2022-02-03 00:43:09', '2022-02-03 00:43:09'),
(353, 'App\\User', 1, 'METutor', 'f47b6802943f86f570f593bd6598c6fe5299e6eadbcdbe7705cdcb01db9ebb5d', '[\"*\"]', NULL, '2022-02-03 00:43:23', '2022-02-03 00:43:23'),
(354, 'App\\User', 1, 'METutor', '138c80780b01d3052da9260417e039b0dc0d7d7081542ce3185bb88d65e20241', '[\"*\"]', NULL, '2022-02-03 00:45:08', '2022-02-03 00:45:08'),
(355, 'App\\User', 1, 'METutor', '60ce4e29a2fb4cc9f7bbfb3264ff3d6162045787b58e5fd2951ed27ce8050e48', '[\"*\"]', NULL, '2022-02-03 00:47:55', '2022-02-03 00:47:55'),
(356, 'App\\User', 1, 'METutor', 'b25c76e2dabea542d0720878cb950edfcfb9375e11ffe1834f10f7d6bfd05b48', '[\"*\"]', NULL, '2022-02-03 00:49:07', '2022-02-03 00:49:07'),
(357, 'App\\User', 1, 'METutor', 'a3e2102f5cdf5a685381d5c1eac3c25593ea17c2530e9f015221b894f64ade3e', '[\"*\"]', NULL, '2022-02-03 00:49:19', '2022-02-03 00:49:19'),
(358, 'App\\User', 1, 'METutor', '53cb0a47cf107df1f61dbf43427ef756ba880a64c15e63718a59f392f7b64da0', '[\"*\"]', NULL, '2022-02-03 00:49:35', '2022-02-03 00:49:35'),
(359, 'App\\User', 1, 'METutor', 'f0a609d0bc8f8ce7060edf929359537ccf3642e5fb6ded7c5213c1a4920a034f', '[\"*\"]', NULL, '2022-02-03 00:49:49', '2022-02-03 00:49:49'),
(360, 'App\\User', 1092, 'METutor', '6ef7192e1ab3cca92a2a1a4d4ded827b2dcb7fb97042a792e77fd57d24cfb588', '[\"*\"]', NULL, '2022-02-03 01:48:47', '2022-02-03 01:48:47'),
(361, 'App\\User', 1, 'METutor', '4ba09152b653835951a5dbbb3f1a7dc243280309b10a45fbdeda444a9a96c3b1', '[\"*\"]', NULL, '2022-02-03 02:07:08', '2022-02-03 02:07:08'),
(362, 'App\\User', 1015, 'METutor', '06082204aa53637b9a363e7c0a1b6bddb4c9c362c76ce802a686758cc9bc8fd4', '[\"*\"]', NULL, '2022-02-03 02:07:29', '2022-02-03 02:07:29'),
(363, 'App\\User', 1015, 'METutor', '2a9b15e35c0fee97b6cdb7a5b23e6f138e0a5ed2e5cf22e0705fd8ec48aae6eb', '[\"*\"]', NULL, '2022-02-03 02:07:45', '2022-02-03 02:07:45'),
(364, 'App\\User', 1, 'METutor', 'c327a71c573399b11334f84bbfff6da82d9e9605406a3d75494c8494613a4eb5', '[\"*\"]', NULL, '2022-02-03 02:07:58', '2022-02-03 02:07:58'),
(365, 'App\\User', 1, 'METutor', 'a2fa0148335a7d76d9ab808bb3a76b1a042b88edf0bab0198f7e9eb456f295a9', '[\"*\"]', NULL, '2022-02-03 02:10:24', '2022-02-03 02:10:24'),
(366, 'App\\User', 1, 'METutor', 'a6699ac82e180b8724520dcf9c1f38599375cf53ad38f1bca438d4b23761270a', '[\"*\"]', NULL, '2022-02-03 02:15:20', '2022-02-03 02:15:20'),
(367, 'App\\User', 1, 'METutor', '71d2be65e013ba9701481916cafe471a5974dd0a306e992bf1e75594b0f71f64', '[\"*\"]', NULL, '2022-02-03 02:17:56', '2022-02-03 02:17:56'),
(368, 'App\\User', 1, 'METutor', '915d5279dbf82d0637576ad01d9036a2f19eb196d0618bb3e81bc678dc7fe345', '[\"*\"]', NULL, '2022-02-03 02:37:06', '2022-02-03 02:37:06'),
(369, 'App\\User', 1, 'METutor', 'c97c9f8380171594642e2348bc1e550bd686f18fff6ff382f9d6b986a94bcd00', '[\"*\"]', NULL, '2022-02-03 02:41:23', '2022-02-03 02:41:23'),
(370, 'App\\User', 1, 'METutor', '70d3f034a3f227839bb8f2275af32f9c61eaef46caac600be9b3cfdb81b86eab', '[\"*\"]', NULL, '2022-02-03 02:42:10', '2022-02-03 02:42:10'),
(371, 'App\\User', 1095, 'METutor', 'ea27643ab398a1774af4a6e5514b833d1e5fa6915c1fad5d69d997ecc64b36b5', '[\"*\"]', NULL, '2022-02-03 03:46:51', '2022-02-03 03:46:51'),
(372, 'App\\User', 1015, 'METutor', 'b1a678ddc16230d6c333c441f9652c171bb3cb8cda368a803471fbaad159ab17', '[\"*\"]', NULL, '2022-02-03 04:14:31', '2022-02-03 04:14:31'),
(373, 'App\\User', 1, 'METutor', '5bcb1bac5dcfab551e7d553675bde053fa8996558ba733102a6a25ca5f7c38b2', '[\"*\"]', NULL, '2022-02-03 04:25:56', '2022-02-03 04:25:56'),
(374, 'App\\User', 1, 'METutor', '14ff6e2f0ec9faf0b6d74d39c6011006154a0170590864f9ae437e64a947d985', '[\"*\"]', NULL, '2022-02-03 04:43:01', '2022-02-03 04:43:01'),
(375, 'App\\User', 1, 'METutor', 'be2550017ec0b971a3dbc494c5b017aad4fe1f8111028a479113fb2c5101547b', '[\"*\"]', NULL, '2022-02-03 04:43:54', '2022-02-03 04:43:54'),
(376, 'App\\User', 1, 'METutor', 'b349f584820375cce09d7c7949939225e91d6d96510d025a06691e1240a01964', '[\"*\"]', NULL, '2022-02-03 04:45:16', '2022-02-03 04:45:16'),
(377, 'App\\User', 1, 'METutor', '2a2b694f4921aad912f9095a7853bf4ae2f0e682a561fabb4d279de5b175b5eb', '[\"*\"]', NULL, '2022-02-03 04:45:53', '2022-02-03 04:45:53'),
(378, 'App\\User', 1, 'METutor', '0135e2460b7a1bea42827cd6c9f0585f0d9b790f7e030a73dde07184dc8bd7f5', '[\"*\"]', NULL, '2022-02-03 04:47:58', '2022-02-03 04:47:58'),
(379, 'App\\User', 1, 'METutor', 'd01c7f219b9e5b1306cd082cd4d9b92e0c873adffdb28cd986dc3687929b1048', '[\"*\"]', NULL, '2022-02-03 05:44:10', '2022-02-03 05:44:10'),
(380, 'App\\User', 1015, 'METutor', '51ebb947faae16668eefd25b4d6ace6783f080677b9fe608e8a8e5b2432a5aa1', '[\"*\"]', '2022-02-04 00:10:38', '2022-02-03 23:14:02', '2022-02-04 00:10:38'),
(381, 'App\\User', 1015, 'METutor', '4fbb37e3fa5eabb263fb479356c2aa4ad645c4f595dea1fb3cd62d8e7fdcf6df', '[\"*\"]', '2022-02-04 05:45:41', '2022-02-04 00:19:32', '2022-02-04 05:45:41'),
(382, 'App\\User', 1, 'METutor', 'a12005a5cfbbc7144f0f34e652053f38a6c404bf621b840c9dfeef10cf2b9bfb', '[\"*\"]', NULL, '2022-02-04 01:25:14', '2022-02-04 01:25:14'),
(383, 'App\\User', 1, 'METutor', 'b64182e6858b7f120a3c27e1563aafa8169e47507f65ebf43279a5b441695880', '[\"*\"]', NULL, '2022-02-04 01:27:49', '2022-02-04 01:27:49'),
(384, 'App\\User', 1, 'METutor', '02f2421d491fbac35de27d5d7a873a2d9ae13e4841206a7b53f749f8daf7c359', '[\"*\"]', NULL, '2022-02-04 01:29:03', '2022-02-04 01:29:03'),
(385, 'App\\User', 1095, 'METutor', '329e881ee2192fd36b357d8a98c5bd23aaf96998d650074ef9e89a65599ee2fc', '[\"*\"]', NULL, '2022-02-04 01:52:47', '2022-02-04 01:52:47'),
(386, 'App\\User', 1095, 'METutor', 'c570006227dc30a32fe0fda1ab6c6be9e25eeee5cf2c73a14a8cbaff04ec0f19', '[\"*\"]', NULL, '2022-02-04 01:53:56', '2022-02-04 01:53:56'),
(387, 'App\\User', 1095, 'METutor', '3770f9e70c47d0f29fc52e8981ca6331d93e0842b366b7b77836ba5fbd1feb6a', '[\"*\"]', NULL, '2022-02-04 01:54:23', '2022-02-04 01:54:23'),
(388, 'App\\User', 1, 'METutor', 'b54ba9da2326d260dadfff1c2defd524c2b9c9ac1f4b7bf786f058aacc432660', '[\"*\"]', NULL, '2022-02-04 01:58:39', '2022-02-04 01:58:39'),
(389, 'App\\User', 1095, 'METutor', '7f43bf0c195da995284eb451623fa66951dd1ad6c345111eb7b1dcc143da88ef', '[\"*\"]', NULL, '2022-02-04 02:00:35', '2022-02-04 02:00:35'),
(390, 'App\\User', 1095, 'METutor', 'bfeaa6d04074ac63df8047c23d1203f1d41db1adf36606dbbf52cc7f6d66352e', '[\"*\"]', NULL, '2022-02-05 00:36:53', '2022-02-05 00:36:53'),
(391, 'App\\User', 1095, 'METutor', 'f474b5d1451b0e030fb1b966cfc0e5b9d15f49e4714753581f20fab0435f5be7', '[\"*\"]', NULL, '2022-02-05 00:37:08', '2022-02-05 00:37:08'),
(392, 'App\\User', 1095, 'METutor', '549569a33f718e1450a777fdb00850fc0efc9c5156f4f981ddf4432f8acb0f2b', '[\"*\"]', NULL, '2022-02-05 01:08:48', '2022-02-05 01:08:48'),
(393, 'App\\User', 1095, 'METutor', '9aab8360ade9b6cb3e2b35f065b8465dd8ea6e630226c97b0b58aba1d0f702b2', '[\"*\"]', NULL, '2022-02-05 01:09:06', '2022-02-05 01:09:06'),
(394, 'App\\User', 1, 'METutor', '75ebf4905ac95a75d99711beaa2978b7a64dbe866a54586cc2eb1a5bc4519149', '[\"*\"]', '2022-02-06 00:36:41', '2022-02-06 00:36:26', '2022-02-06 00:36:41'),
(395, 'App\\User', 1, 'METutor', '243df0e0e5cf72f6c58eaf40441ea300c2ec102f544c78194e4d58fd0297406f', '[\"*\"]', NULL, '2022-02-06 01:01:43', '2022-02-06 01:01:43'),
(396, 'App\\User', 1, 'METutor', 'd1172825e1691b6e06004bb6ecb8470d6acfc30740cbba32a4b40dda1d835928', '[\"*\"]', '2022-02-06 01:02:47', '2022-02-06 01:02:16', '2022-02-06 01:02:47'),
(397, 'App\\User', 1, 'METutor', '8469161013b7f9c9197aaa38e608a97d8e3be566f174138bcfd01060b6de63f4', '[\"*\"]', '2022-02-06 01:14:21', '2022-02-06 01:13:43', '2022-02-06 01:14:21'),
(398, 'App\\User', 1, 'METutor', '7828f8010811e4186ba741fdbd8a6bb45d6f21ee379c08aa37fd7d222990d240', '[\"*\"]', '2022-02-06 01:27:46', '2022-02-06 01:27:36', '2022-02-06 01:27:46'),
(399, 'App\\User', 1, 'METutor', 'a1be5f2afafac4d8f43e9906f148c4c1350a10faa47adcbfe7ea7e20ca189b2e', '[\"*\"]', '2022-02-06 01:32:36', '2022-02-06 01:32:24', '2022-02-06 01:32:36'),
(400, 'App\\User', 1, 'METutor', '43c72604f03103f8afab88cc97a309d69809c107e1bcae91eac4734dee345cde', '[\"*\"]', '2022-02-06 01:50:56', '2022-02-06 01:50:45', '2022-02-06 01:50:56'),
(401, 'App\\User', 1, 'METutor', 'ec3f018e9586bbbfab2f50dc4de09a16dce42d21a25738466c386e9d13b95aee', '[\"*\"]', '2022-02-06 02:06:18', '2022-02-06 02:05:57', '2022-02-06 02:06:18'),
(402, 'App\\User', 1, 'METutor', '84523f61dbb174527dbe5cf3f043db48197475cf6372697995ecaaa7486f364f', '[\"*\"]', '2022-02-06 04:29:37', '2022-02-06 03:37:34', '2022-02-06 04:29:37'),
(403, 'App\\User', 995, 'METutor', 'f3ac0a0fd2314ce87972f23e3b5b600de0040cb0880c7a709c46ab41e0ca4665', '[\"*\"]', '2022-02-07 04:21:58', '2022-02-06 05:08:45', '2022-02-07 04:21:58'),
(404, 'App\\User', 995, 'METutor', '0e9727c669a02a812c63a99e1ca977341807ec38c1a106d42fa9136aa74993a2', '[\"*\"]', NULL, '2022-02-06 05:12:02', '2022-02-06 05:12:02'),
(405, 'App\\User', 995, 'METutor', '07a32cd19807693bd740c42da3998e029b99f17444c4b473ec9f093eedc0805d', '[\"*\"]', NULL, '2022-02-06 05:15:25', '2022-02-06 05:15:25'),
(406, 'App\\User', 1063, 'METutor', '25e28a64b6bb72e1bfd99fd739eec3a78f3f779d53c48cf48112c058f323e0dc', '[\"*\"]', NULL, '2022-02-07 01:07:53', '2022-02-07 01:07:53'),
(407, 'App\\User', 1, 'METutor', '7d5f00cbb7ee3d4bcd051f328f83956799d3a6ad14a2675093b6ff86224fcea2', '[\"*\"]', '2022-02-07 02:13:18', '2022-02-07 02:13:08', '2022-02-07 02:13:18'),
(408, 'App\\User', 1063, 'METutor', 'e0a5ad5b9120346dca3b494e0a574428445ecc79cb059118cf76bf631913e24b', '[\"*\"]', NULL, '2022-02-07 02:16:39', '2022-02-07 02:16:39'),
(409, 'App\\User', 1063, 'METutor', '5de76c9605375677852fcf7cf705cffc79811fd8b67df680e07ade23db96fc32', '[\"*\"]', '2022-02-07 04:45:36', '2022-02-07 02:37:41', '2022-02-07 04:45:36'),
(410, 'App\\User', 995, 'METutor', '4404e9021e0e8ac2d9deffb8183d2d2bdacc058b55b33bc23f98780193573225', '[\"*\"]', NULL, '2022-02-07 04:08:58', '2022-02-07 04:08:58'),
(411, 'App\\User', 1092, 'METutor', '3efef3c2ad31960945ca15be1e71c00cc1fa858d99ef1eb1c7502dc1229593e6', '[\"*\"]', '2022-02-07 04:27:14', '2022-02-07 04:13:25', '2022-02-07 04:27:14'),
(412, 'App\\User', 1092, 'METutor', 'b6dd430ea79b4e47711f0978c83e9a817d0a0e0cd5285c74de118bc78a58eb31', '[\"*\"]', '2022-02-07 04:48:34', '2022-02-07 04:24:03', '2022-02-07 04:48:34'),
(413, 'App\\User', 1117, 'METutor', '7ecfde53ec22316a93a39718366bdef541da0c89bd9b099fa5c002b8f888cdb7', '[\"*\"]', '2022-02-07 04:51:29', '2022-02-07 04:48:50', '2022-02-07 04:51:29'),
(414, 'App\\User', 1015, 'METutor', 'bff9c147334d9f691a29a695f5a5a9d49e39336a9dae5a1866ad11bc4c62f5dc', '[\"*\"]', '2022-02-07 04:49:46', '2022-02-07 04:49:20', '2022-02-07 04:49:46'),
(415, 'App\\User', 1064, 'METutor', '3e32494e9179ac09c0c69fa8725e227d9f0b108dbbce9cacca5c9443f959544f', '[\"*\"]', '2022-02-07 04:51:47', '2022-02-07 04:50:53', '2022-02-07 04:51:47'),
(416, 'App\\User', 1117, 'METutor', '896a42bec5bebf24a737f590bfac5681865722735a8480c120b0622fa127c5ed', '[\"*\"]', NULL, '2022-02-07 04:51:51', '2022-02-07 04:51:51'),
(417, 'App\\User', 1117, 'METutor', '499b0f44511b27f5559ab17949c19bccd2c5c453d071cc8ed70c79d3dfd8af69', '[\"*\"]', '2022-02-07 17:40:42', '2022-02-07 17:39:49', '2022-02-07 17:40:42'),
(418, 'App\\User', 1015, 'METutor', 'aec40d5c36d1ede9f8a748329a34b680c03ffb0c8348ea6c6c9f9969f3a07df9', '[\"*\"]', '2022-02-07 20:51:00', '2022-02-07 20:47:15', '2022-02-07 20:51:00'),
(419, 'App\\User', 1015, 'METutor', '3e9657a14d30d1becea9bedc618128efaa42929e49426435abf41bed04487987', '[\"*\"]', NULL, '2022-02-07 23:09:29', '2022-02-07 23:09:29'),
(420, 'App\\User', 1015, 'METutor', '4b1077bcde8ebf2512211084c8fca2db83431c6b6da8d0aeafcd3907ecf0178a', '[\"*\"]', '2022-02-08 00:24:09', '2022-02-08 00:05:05', '2022-02-08 00:24:09'),
(421, 'App\\User', 1118, 'METutor', '87437354a2ab1db4fe7aa407d1a473a78b222b3b5b0663ba02a7fd0f18e6a0e7', '[\"*\"]', NULL, '2022-02-08 01:11:21', '2022-02-08 01:11:21'),
(422, 'App\\User', 1119, 'METutor', '6efde34a95b1da167dc55e1d16c168e6314076fcead6b4a0b8867008f575929f', '[\"*\"]', NULL, '2022-02-08 01:13:59', '2022-02-08 01:13:59'),
(423, 'App\\User', 1120, 'METutor', '72dcddcfea1cb0a067c5370024ec268c689d2d5dcc0ea02e25ec69a7312a272f', '[\"*\"]', NULL, '2022-02-08 02:29:10', '2022-02-08 02:29:10'),
(424, 'App\\User', 1121, 'METutor', '0f6a60ac8542e38ed0e3aab098d68f1183e1d1a2fba8b8d234528cf07a694c98', '[\"*\"]', NULL, '2022-02-08 02:30:48', '2022-02-08 02:30:48'),
(425, 'App\\User', 1, 'METutor', 'bc72ae38d4e81d70c15cbbec67b428926d9803c29f9178d8f506d99cd8fd91e6', '[\"*\"]', NULL, '2022-02-08 02:32:54', '2022-02-08 02:32:54'),
(426, 'App\\User', 1123, 'METutor', 'c424caee106ef21ef73649867e04e2948e163bb9eca1b22dcdbb7404c16b04d6', '[\"*\"]', '2022-02-08 02:55:35', '2022-02-08 02:54:50', '2022-02-08 02:55:35'),
(427, 'App\\User', 1123, 'METutor', 'a39cce717447cc950d66b2fc3e74b845cd24243dd9740b30e77ea1d94577679b', '[\"*\"]', NULL, '2022-02-08 03:01:17', '2022-02-08 03:01:17'),
(428, 'App\\User', 1, 'METutor', 'd8accccd84d6b2f9083ba26127434e86e78c96b42430bae19a7b5767a6ccc551', '[\"*\"]', '2022-02-08 03:11:15', '2022-02-08 03:02:44', '2022-02-08 03:11:15'),
(429, 'App\\User', 1, 'METutor', 'de06e1761df62bd76cbd5b6cc00a5b8a27af6feca652e25167fd626b7dab6da4', '[\"*\"]', '2022-02-09 00:48:06', '2022-02-09 00:18:35', '2022-02-09 00:48:06'),
(430, 'App\\User', 1, 'METutor', 'd4a79c01e463c76a972491309077e72b6d6e8924919566c769d95f15877595e3', '[\"*\"]', NULL, '2022-02-09 00:58:56', '2022-02-09 00:58:56'),
(431, 'App\\User', 1, 'METutor', '164214a899d485e3d08b7867feca716ca21189e07ad0c9f15ea79f8408ea6f0a', '[\"*\"]', '2022-02-09 02:54:40', '2022-02-09 01:00:03', '2022-02-09 02:54:40'),
(432, 'App\\User', 1, 'METutor', '6018d8c96d12adfa9bac7d01113cecffef33f29b2f51cfab36dec0825e2a1f7d', '[\"*\"]', '2022-02-09 02:28:04', '2022-02-09 02:19:48', '2022-02-09 02:28:04'),
(433, 'App\\User', 1, 'METutor', 'fe4eddb4f4753e8e3438c8c14bc19bcd6287868be62da3e9e1df42ed5d6be5bb', '[\"*\"]', '2022-02-09 04:05:00', '2022-02-09 03:51:30', '2022-02-09 04:05:00'),
(434, 'App\\User', 1, 'METutor', '955543dfdeff00a96468e63fe1db77278c42e385376c73312110bd998e45a816', '[\"*\"]', '2022-02-09 04:14:19', '2022-02-09 04:06:18', '2022-02-09 04:14:19'),
(435, 'App\\User', 1, 'METutor', 'bcd806fea9b3428e5cc5473c0a69ec6c4fdc6b2f3291180a9210f9a023aa269b', '[\"*\"]', '2022-02-09 04:16:05', '2022-02-09 04:15:15', '2022-02-09 04:16:05'),
(436, 'App\\User', 1, 'METutor', 'ebe7609267ef9f66477c6f7428f619d4e12b4645a717aa3b8bde5132d6d4302d', '[\"*\"]', '2022-02-09 04:18:07', '2022-02-09 04:17:28', '2022-02-09 04:18:07'),
(437, 'App\\User', 1, 'METutor', '70888bc0641ec1862562226c704fc033140f9fc13022998f5a3ea2f4da428ff9', '[\"*\"]', '2022-02-09 19:23:43', '2022-02-09 19:23:14', '2022-02-09 19:23:43'),
(438, 'App\\User', 1125, 'METutor', '846c1c7a07b703192d6bca392e80ec71ea8fde936a97bd149df916c735f14833', '[\"*\"]', NULL, '2022-02-10 01:14:41', '2022-02-10 01:14:41'),
(439, 'App\\User', 1127, 'METutor', 'd0def251a8934f9aceae9cb68f4226d3df5e32723baf93f59a29f4c6f957f202', '[\"*\"]', NULL, '2022-02-10 01:43:11', '2022-02-10 01:43:11'),
(440, 'App\\User', 1124, 'METutor', 'ccac260be1b4a369281148857fcae4b769339ea72dcc5c520156fffa1db9fcde', '[\"*\"]', '2022-02-18 07:33:16', '2022-02-18 02:14:56', '2022-02-18 07:33:16'),
(441, 'App\\User', 1124, 'METutor', '815610d105c1f9a99de48f183c1d64aa9798e75d280d00fecde899c18e430e79', '[\"*\"]', '2022-02-18 06:07:45', '2022-02-18 05:38:45', '2022-02-18 06:07:45'),
(442, 'App\\User', 1158, 'METutor', '8d42d5cb5543568a3d00c82d593c62c097eb7cee338cb94b077727af04b43c43', '[\"*\"]', '2022-02-24 05:15:57', '2022-02-23 10:20:07', '2022-02-24 05:15:57'),
(443, 'App\\User', 1158, 'METutor', '91a4a19f7c5d337e714908e38b6d27f469e56ab08cab9548dbda3c57b991e4a9', '[\"*\"]', '2022-02-28 03:09:19', '2022-02-23 14:08:21', '2022-02-28 03:09:19'),
(444, 'App\\User', 1158, 'METutor', 'c904b76302f768476d4931d0bb5875d5faa2dc4acf62ca9c2544416f2df9eb0b', '[\"*\"]', NULL, '2022-02-24 01:23:59', '2022-02-24 01:23:59'),
(445, 'App\\User', 1127, 'METutor', '205aeb9eb1a046b2ebf6bc4b8783b6e2396ddd3d80d58c25e87a105a3512764b', '[\"*\"]', '2022-02-28 05:46:27', '2022-02-24 01:51:50', '2022-02-28 05:46:27'),
(446, 'App\\User', 1127, 'METutor', 'dec15ed9cb2c2458e94ac7c3e7ab4f8644c408656c8855e64720cdae987fff8b', '[\"*\"]', '2022-02-28 02:24:06', '2022-02-25 02:35:20', '2022-02-28 02:24:06'),
(447, 'App\\User', 1127, 'METutor', 'ba09276f3a16d2a8cee4e685666fc06504f8a275a6c048e00e0498489d039071', '[\"*\"]', '2022-02-28 01:26:18', '2022-02-25 08:02:48', '2022-02-28 01:26:18'),
(448, 'App\\User', 1159, 'METutor', '951c655ab95e0c8bdd9be8bc5a819a3b9b29d8d703e77b1aa7f05c82b08e63c1', '[\"*\"]', '2022-02-28 02:38:53', '2022-02-28 01:41:24', '2022-02-28 02:38:53'),
(449, 'App\\User', 1158, 'METutor', '99b351768eda92c10134b434ada462b1e0450c578624407cba58c808c74dabd5', '[\"*\"]', '2022-02-28 04:46:45', '2022-02-28 04:38:26', '2022-02-28 04:46:45'),
(450, 'App\\User', 1127, 'METutor', '5e23e202521fe5446ba7ac38af6774969f8f611c0dac6f20d028c02ca0e49ccb', '[\"*\"]', NULL, '2022-02-28 06:36:31', '2022-02-28 06:36:31'),
(451, 'App\\User', 1158, 'METutor', '548e346f8217cec0bd453f810bcbe7ac393ee8e947a769c67c24bda961e5c3f1', '[\"*\"]', '2022-03-01 02:01:38', '2022-03-01 00:56:05', '2022-03-01 02:01:38'),
(452, 'App\\User', 1158, 'METutor', 'c05309b1b7fff0b3f67598e41eb58458bc65422e5e3b1d3eb594609d6bb51f23', '[\"*\"]', NULL, '2022-03-01 02:02:02', '2022-03-01 02:02:02'),
(453, 'App\\User', 1127, 'METutor', 'b3e091efd34e61e874d3d8ca945a293bd679442f5ee60faebfd748b70b5c195d', '[\"*\"]', NULL, '2022-03-01 02:20:02', '2022-03-01 02:20:02'),
(454, 'App\\User', 1135, 'METutor', '108938c02a1f8305975480acd5ad67df1b387b69e7dfc583639db3e851c3b336', '[\"*\"]', NULL, '2022-03-01 02:57:33', '2022-03-01 02:57:33'),
(455, 'App\\User', 1149, 'METutor', 'b8c167e1073f08d1afc86c1442400480306edcb6994ab5044c8c4c1bcfc92828', '[\"*\"]', NULL, '2022-03-01 03:02:20', '2022-03-01 03:02:20'),
(456, 'App\\User', 1149, 'METutor', 'fd22cc70421e3960d9e918bc4682ae0f2b7c5bb8b26d252ad754ff9d2244e9da', '[\"*\"]', NULL, '2022-03-01 03:53:42', '2022-03-01 03:53:42'),
(457, 'App\\User', 1149, 'METutor', '3038a8f97393acf99a446649475c2d1c1c72f3fb2938c5747284015b59752c95', '[\"*\"]', NULL, '2022-03-01 04:54:51', '2022-03-01 04:54:51');
INSERT INTO `personal_access_tokens` (`id`, `tokenable_type`, `tokenable_id`, `name`, `token`, `abilities`, `last_used_at`, `created_at`, `updated_at`) VALUES
(458, 'App\\User', 1149, 'METutor', 'e13db17af4b36f171e15181cd073f56ce461117e08f01867918df0e259bd4393', '[\"*\"]', NULL, '2022-03-01 05:22:48', '2022-03-01 05:22:48'),
(459, 'App\\User', 1149, 'METutor', '9148552baa69370e7f5473999ab5f1f1ea515874b8b6d6321a77c67f9ab90f2d', '[\"*\"]', NULL, '2022-03-01 06:28:46', '2022-03-01 06:28:46'),
(460, 'App\\User', 1135, 'METutor', '230a4c3301d07d6162be95e0052d85b7ff267cc53edd80df36b379fdd0b376f6', '[\"*\"]', NULL, '2022-03-01 06:29:04', '2022-03-01 06:29:04'),
(461, 'App\\User', 1149, 'METutor', '6a4f24c0ef2ed99e7e95a40af36ba9426799d3466d313b0e566e5a5046b4c2d7', '[\"*\"]', NULL, '2022-03-01 07:16:17', '2022-03-01 07:16:17'),
(462, 'App\\User', 1135, 'METutor', '779b1b76e53eb967599171b1abc262482292e39102a2bab1305f6f621087181f', '[\"*\"]', NULL, '2022-03-01 07:17:12', '2022-03-01 07:17:12'),
(463, 'App\\User', 1149, 'METutor', 'b382420f92803f04182f45c3f64c5219db045f05fd94dc64418ea8735ad329a3', '[\"*\"]', NULL, '2022-03-01 08:38:58', '2022-03-01 08:38:58'),
(464, 'App\\User', 1149, 'METutor', '7af648b2d9c3cdf1740ce04dbad9237661f7b904891c9cafd00666fe3d87b430', '[\"*\"]', NULL, '2022-03-01 09:03:07', '2022-03-01 09:03:07'),
(465, 'App\\User', 1149, 'METutor', 'aa05bb4a0ce1010e67c80334f07789811e0fb32e06761c2f004ff02f4ea872d9', '[\"*\"]', NULL, '2022-03-01 09:56:51', '2022-03-01 09:56:51'),
(466, 'App\\User', 1149, 'METutor', 'e0c485d254682036908d5a9d0af21ece29e00f5500540cd8cd2d7edc2a35a236', '[\"*\"]', NULL, '2022-03-01 10:09:31', '2022-03-01 10:09:31'),
(467, 'App\\User', 1135, 'METutor', 'ff4ba2eae324e338f957e685bb12e4f9774376f50b54e5722b8aa01804b169e9', '[\"*\"]', NULL, '2022-03-02 01:47:10', '2022-03-02 01:47:10'),
(468, 'App\\User', 1149, 'METutor', 'de4622ea28974b8599d2950733e22f4b204127f83f6cfe3eba994a2fcd078440', '[\"*\"]', NULL, '2022-03-02 02:25:30', '2022-03-02 02:25:30'),
(469, 'App\\User', 1149, 'METutor', 'e71c8ceef8646b35ae1e79e77b6676f323c6ddf38455994e2469977a1f841829', '[\"*\"]', NULL, '2022-03-02 03:26:43', '2022-03-02 03:26:43'),
(470, 'App\\User', 1149, 'METutor', '0b4cb0817be939153b1c4004431286afb9c7a56ad189b8f403e9f926efac1fcc', '[\"*\"]', NULL, '2022-03-02 20:39:28', '2022-03-02 20:39:28'),
(471, 'App\\User', 1149, 'METutor', '1c8635d71be7d3c4e9ec94725f17d721b5f637f8be22550c27da642d09790f96', '[\"*\"]', NULL, '2022-03-02 20:45:29', '2022-03-02 20:45:29'),
(472, 'App\\User', 1149, 'METutor', '96afa0392f3b212adebc3ad4a28c4368bf1627e63abd88fedfbe06ad5599bd08', '[\"*\"]', NULL, '2022-03-02 21:54:22', '2022-03-02 21:54:22'),
(473, 'App\\User', 1149, 'METutor', 'fbc6932f668206e9595a4e550bae7a5ed8aefdc1e7f7601cd4526b6e96c65a45', '[\"*\"]', NULL, '2022-03-02 22:50:53', '2022-03-02 22:50:53'),
(474, 'App\\User', 1149, 'METutor', '15ac5420e4697fae80e0042d5a967c9e145d8105604da53530d658178356bb77', '[\"*\"]', NULL, '2022-03-02 22:52:57', '2022-03-02 22:52:57'),
(475, 'App\\User', 1145, 'METutor', 'e025c5245063d827420a5d822fa109a358d39e3bdb3082d79897ae14348b4e2a', '[\"*\"]', NULL, '2022-03-03 00:09:49', '2022-03-03 00:09:49'),
(476, 'App\\User', 1149, 'METutor', '3d3620f8c7411e8f9d299797f85965e4ac3c7724fdd46ec5ae3e57529981e027', '[\"*\"]', NULL, '2022-03-03 01:00:12', '2022-03-03 01:00:12'),
(477, 'App\\User', 1149, 'METutor', '244660627ef38f73e3a2f64aa6701b9d8a155539342c11d74ea81289b5c291be', '[\"*\"]', NULL, '2022-03-03 01:22:07', '2022-03-03 01:22:07'),
(478, 'App\\User', 1149, 'METutor', '6569595beb2ccfa33bff8eda7255ea8ebd58236f0f1335736941d72fa5925ad0', '[\"*\"]', NULL, '2022-03-03 02:26:36', '2022-03-03 02:26:36'),
(479, 'App\\User', 1149, 'METutor', '144743e07e46b90f0448fcf49bb978798783216328ee94e92d4112b224f8110f', '[\"*\"]', NULL, '2022-03-03 13:00:29', '2022-03-03 13:00:29'),
(480, 'App\\User', 1145, 'METutor', '708943a6a5c4e70b8d9b48aa3c30651dabe92e4361ce0fa253c10c4536b03ca0', '[\"*\"]', NULL, '2022-03-03 13:25:12', '2022-03-03 13:25:12'),
(481, 'App\\User', 1149, 'METutor', 'dff8728d718905e1f3cf3aa27422b34d72b5dd9cb3670cd873c2623896072520', '[\"*\"]', NULL, '2022-03-03 13:30:10', '2022-03-03 13:30:10'),
(482, 'App\\User', 1145, 'METutor', 'b7bb07b92a07ac6ff80e18168accff64d01e1fd51a4cefd749d786a42ff4561e', '[\"*\"]', NULL, '2022-03-03 14:28:50', '2022-03-03 14:28:50'),
(483, 'App\\User', 1149, 'METutor', 'f7034b55bcabe5dd0efafa271371db88a1fc6dec3b9f9add5ff91d322474fd53', '[\"*\"]', NULL, '2022-03-03 14:42:08', '2022-03-03 14:42:08'),
(484, 'App\\User', 1149, 'METutor', '6f1022e0c994928c7f4f98092cf7ac1c3e528ca83c8dafd5db6b1cb1e354dde3', '[\"*\"]', NULL, '2022-03-03 14:48:52', '2022-03-03 14:48:52'),
(485, 'App\\User', 1145, 'METutor', 'c8bd5901f3aa2bf78a22c20cf137c95d651454a87c5a47d4907def08edf5d54c', '[\"*\"]', NULL, '2022-03-03 14:52:13', '2022-03-03 14:52:13'),
(486, 'App\\User', 1149, 'METutor', 'a89a31a16e431a5306572d9f4fe2d462e40e74968dc0a7261cd9cde220da59e7', '[\"*\"]', NULL, '2022-03-03 15:03:56', '2022-03-03 15:03:56'),
(487, 'App\\User', 1153, 'METutor', '85eab3614d8bab8c2a182e9d0d1fd868f138d08c6a68e427a5fc55eb6714135e', '[\"*\"]', NULL, '2022-03-03 15:20:15', '2022-03-03 15:20:15'),
(488, 'App\\User', 1145, 'METutor', '02765dd24f87bd36f70c09344b40a9022e44537130f5af6764c1a2bcafb18879', '[\"*\"]', NULL, '2022-03-03 15:28:51', '2022-03-03 15:28:51'),
(489, 'App\\User', 1145, 'METutor', '521b164f67fbe5ad749a9d4073c688ac8a6223364177167783e7dfd67c93f2b1', '[\"*\"]', NULL, '2022-03-03 15:30:36', '2022-03-03 15:30:36'),
(490, 'App\\User', 1145, 'METutor', '882a032e9524e7658a12e89407d038208a68ca5e6d330e67af44281c21f24836', '[\"*\"]', NULL, '2022-03-03 15:37:23', '2022-03-03 15:37:23'),
(491, 'App\\User', 1153, 'METutor', '2614428ddab9a8a559a78edf9bfcc467c0f0fb86a2858e35a37bd6349050c14e', '[\"*\"]', NULL, '2022-03-03 15:39:04', '2022-03-03 15:39:04'),
(492, 'App\\User', 1145, 'METutor', '8cbce307e6b428e2ba886547850fd963e021887c17eb1e6901bd1428296ef05b', '[\"*\"]', NULL, '2022-03-03 15:43:47', '2022-03-03 15:43:47'),
(493, 'App\\User', 1153, 'METutor', '4c114f5ca847de8769a5bc6d7707e907dfada0556cb47a1ea8f0b9c6386c5686', '[\"*\"]', NULL, '2022-03-03 15:46:14', '2022-03-03 15:46:14'),
(494, 'App\\User', 1154, 'METutor', '12cb6b8594c6ae5df37cabb899f1dec7572cb729d94c0b2335d5c1d20f5f77a7', '[\"*\"]', NULL, '2022-03-03 16:26:33', '2022-03-03 16:26:33'),
(495, 'App\\User', 1145, 'METutor', 'd4fca5f3f0511719f46e0fe52c9029860a413ee7a9d2dc8378c9efbf89533225', '[\"*\"]', NULL, '2022-03-03 16:27:42', '2022-03-03 16:27:42'),
(496, 'App\\User', 1153, 'METutor', '75297393e5014d8c611d1eae7489ff2302377416896fcd3e58578ebe4b45f33a', '[\"*\"]', NULL, '2022-03-03 16:34:10', '2022-03-03 16:34:10'),
(497, 'App\\User', 1154, 'METutor', '368aea77ac688a966dffe96fe2b0be678a2e1e0d77293533a3d06d1566686ed0', '[\"*\"]', NULL, '2022-03-03 16:37:39', '2022-03-03 16:37:39'),
(498, 'App\\User', 1145, 'METutor', 'd0960e79d9f389e2187ee968a36c4d112067f5ba85824623d1fdd15ff5a62425', '[\"*\"]', NULL, '2022-03-03 16:43:13', '2022-03-03 16:43:13'),
(499, 'App\\User', 1149, 'METutor', 'f73adfe37ccfce963779e9d258bcf4245ef27a0ada6277884bc1b67771f9a411', '[\"*\"]', NULL, '2022-03-03 16:49:31', '2022-03-03 16:49:31'),
(500, 'App\\User', 1149, 'METutor', '0e9d1d36fdbbb62d75c4099b2af3d9df2226a7a6442f29f12f260b2df35ed11b', '[\"*\"]', NULL, '2022-03-03 16:50:08', '2022-03-03 16:50:08'),
(501, 'App\\User', 1149, 'METutor', 'f4686321c242fa802434c9f976c0ebc257b0f86ecf811a971779a7b76fb6f1f8', '[\"*\"]', NULL, '2022-03-03 17:53:14', '2022-03-03 17:53:14'),
(502, 'App\\User', 1145, 'METutor', 'd8e503e268b7109f12eac2275acedfa4b6130394a932a00dc940b1933da49061', '[\"*\"]', NULL, '2022-03-03 18:59:34', '2022-03-03 18:59:34'),
(503, 'App\\User', 1149, 'METutor', 'e4ce5c3b321716938349cd75c5f69634ad75c4ad3a29f82004dc3ab7e0cf7d55', '[\"*\"]', NULL, '2022-03-03 19:07:35', '2022-03-03 19:07:35'),
(504, 'App\\User', 1149, 'METutor', '603ce5dec90655d514e27f088dad062a37efd055954bff5308c5276a4601665d', '[\"*\"]', NULL, '2022-03-03 20:09:24', '2022-03-03 20:09:24'),
(505, 'App\\User', 1153, 'METutor', '8fc9f77dd092dbd755b5acd9b52a82a49dea21d2c4556e80b1f4b6d79b915671', '[\"*\"]', NULL, '2022-03-03 20:53:52', '2022-03-03 20:53:52'),
(506, 'App\\User', 1153, 'METutor', 'd1e62956fe5f593f1ef373bab0cce55fc4a397f98ee6e5c30aa28f1890b9d9a4', '[\"*\"]', NULL, '2022-03-03 21:21:30', '2022-03-03 21:21:30'),
(507, 'App\\User', 1153, 'METutor', 'f79386d31f172146acf8dc0f484285cb81cb25af6521152b3ef26f3e1893824c', '[\"*\"]', NULL, '2022-03-03 21:32:25', '2022-03-03 21:32:25'),
(508, 'App\\User', 1153, 'METutor', '9645af5809ac25a485d7969b353852a0d26d9d24f055baf91ff53b4e887745cf', '[\"*\"]', NULL, '2022-03-03 21:34:54', '2022-03-03 21:34:54'),
(509, 'App\\User', 1153, 'METutor', '767eb2eeb277611a787eb296e58d518541eb7c7cf89d90b3a44e46351e177d9c', '[\"*\"]', NULL, '2022-03-03 21:36:02', '2022-03-03 21:36:02'),
(510, 'App\\User', 1153, 'METutor', 'f319882ec35537c16b37fe043e1d8dce10871d9895a1f27bcb8ad31fb8da9adf', '[\"*\"]', NULL, '2022-03-03 21:39:29', '2022-03-03 21:39:29'),
(511, 'App\\User', 1153, 'METutor', 'baf233c9386951ca910864f148115bed8b9a44784d76c6b77cb09a62362bacaf', '[\"*\"]', NULL, '2022-03-03 23:12:53', '2022-03-03 23:12:53'),
(512, 'App\\User', 1149, 'METutor', '7bffd2f6fe1931477cf9e936ff5c12071ce6cee2d298c1e67b67844647d3e3cf', '[\"*\"]', NULL, '2022-03-03 23:30:24', '2022-03-03 23:30:24'),
(513, 'App\\User', 1149, 'METutor', 'a5ea0b9fb6962bccdd830e1a451e782dfa5c2870448ae22885cf53a875d6cb35', '[\"*\"]', NULL, '2022-03-03 23:37:33', '2022-03-03 23:37:33'),
(514, 'App\\User', 1149, 'METutor', '98a51f6b6580ce5faab28ae6fab319a1cdfa1ea007b9ba4ec577e566f1943c70', '[\"*\"]', NULL, '2022-03-04 01:42:12', '2022-03-04 01:42:12'),
(515, 'App\\User', 1135, 'METutor', '50ed1d9d7afd144f1539b2d9d0bb7370751379e39ea2aa6c565c3faf53ae5a1e', '[\"*\"]', NULL, '2022-03-04 01:44:31', '2022-03-04 01:44:31'),
(516, 'App\\User', 1155, 'METutor', '6a84c1e389b32b9f38eb75c4df62662e8e9ce9294b51bc568775a2ce4b850e11', '[\"*\"]', NULL, '2022-03-04 12:35:22', '2022-03-04 12:35:22'),
(517, 'App\\User', 1149, 'METutor', 'ab75e5a316a56dffd803a5e8e40d26397e1aee8ceca291c0c65513f6aba91c4e', '[\"*\"]', NULL, '2022-03-04 15:20:30', '2022-03-04 15:20:30'),
(518, 'App\\User', 1135, 'METutor', 'bb3800274da3ffefc97911004a15e017b0f955a87f13730b3a1ffeb8f2ff6004', '[\"*\"]', NULL, '2022-03-04 15:33:27', '2022-03-04 15:33:27'),
(519, 'App\\User', 1145, 'METutor', 'fe326b1ae697c29ac600a263d07a959bc1e099e2861c0b78abbde27014eced6e', '[\"*\"]', NULL, '2022-03-04 15:34:41', '2022-03-04 15:34:41'),
(520, 'App\\User', 1149, 'METutor', 'ca1973347b8e2ce189533d9cc28f7f2643559677f133679b8a9f0a1364854051', '[\"*\"]', NULL, '2022-03-04 16:55:39', '2022-03-04 16:55:39'),
(521, 'App\\User', 1145, 'METutor', '47ba2a14fc2a63ac0c38fcd7ab2b6899a43a7d1b355e6990638625a3f87e1d57', '[\"*\"]', NULL, '2022-03-04 17:03:50', '2022-03-04 17:03:50'),
(522, 'App\\User', 1145, 'METutor', 'df43f138514ac0a1d3986c9b801c6104503555f7e41553bd8489fa35066189b7', '[\"*\"]', NULL, '2022-03-04 17:04:04', '2022-03-04 17:04:04'),
(523, 'App\\User', 1135, 'METutor', '57bc233c0bbd876d6b20ace771d563484bf3b9cfd36aaefd6cc0147561c6f368', '[\"*\"]', NULL, '2022-03-04 17:07:42', '2022-03-04 17:07:42'),
(524, 'App\\User', 1135, 'METutor', '12dfc50913c8667efadc55f6efe50f59935744a5529871bd04283b53f0325948', '[\"*\"]', NULL, '2022-03-04 17:18:07', '2022-03-04 17:18:07'),
(525, 'App\\User', 1149, 'METutor', '0c3587fce34e94f1fa54dbc05e9de0aab7106ba857b91bc7c8c7297e1283ff73', '[\"*\"]', NULL, '2022-03-04 17:19:30', '2022-03-04 17:19:30'),
(526, 'App\\User', 1156, 'METutor', 'e0ddff0a0a898b6e97cbb284a2628b12cba21cbb396cfe298e7011ea486a3ecf', '[\"*\"]', NULL, '2022-03-04 18:50:45', '2022-03-04 18:50:45'),
(527, 'App\\User', 1145, 'METutor', 'ffaa38c8515ac0a51a8143688e9aeea9e5b3bc45d1b0079dfe2ef580f957b25f', '[\"*\"]', NULL, '2022-03-04 19:01:43', '2022-03-04 19:01:43'),
(528, 'App\\User', 1145, 'METutor', '394d97457f8dc7465fdea7e1e090dbfd8b63fa6a71c11dacbab437adb436a212', '[\"*\"]', NULL, '2022-03-04 19:05:20', '2022-03-04 19:05:20'),
(529, 'App\\User', 1153, 'METutor', '61bbb3d63b3293dd9fb8dbf5ec6129972da75fd8d1709f2b78e9d2b5ba8383da', '[\"*\"]', NULL, '2022-03-04 23:38:43', '2022-03-04 23:38:43'),
(530, 'App\\User', 1149, 'METutor', '53289924e51324bb40e8dfccebc221be0594bd814dd7c551d3d4c24072ec4a68', '[\"*\"]', NULL, '2022-03-04 23:41:34', '2022-03-04 23:41:34'),
(531, 'App\\User', 1145, 'METutor', '8bdd4f412db04c749497a7279e2448dd30dbdca8bc58094903d01e356c986f7d', '[\"*\"]', NULL, '2022-03-05 00:02:18', '2022-03-05 00:02:18'),
(532, 'App\\User', 1145, 'METutor', '181ed0a71a2920603e47855fd88cc17c034e65a6485a9a2819582444630b8395', '[\"*\"]', NULL, '2022-03-05 14:10:19', '2022-03-05 14:10:19'),
(533, 'App\\User', 1149, 'METutor', '5f6c6bfe0bde57f2dc07439b5726d1f758b92689d546e4a3e8a14e6e65c44b75', '[\"*\"]', NULL, '2022-03-05 16:49:47', '2022-03-05 16:49:47'),
(534, 'App\\User', 1149, 'METutor', 'b6d05623f4825ddfb18cd5e14e85843a2fe6505c66f4dcf89ff7d3126abf2e09', '[\"*\"]', NULL, '2022-03-05 17:55:43', '2022-03-05 17:55:43'),
(535, 'App\\User', 1135, 'METutor', '330163cd6b97b0d8ca461fae6004c0ca261354a1c14d2b0b377f4e830978ff8b', '[\"*\"]', NULL, '2022-03-05 17:57:03', '2022-03-05 17:57:03'),
(536, 'App\\User', 1135, 'METutor', '5fad3a5a11bcbab401f3ade4f31176f18d36429de680a6077d503e41bb79bf8a', '[\"*\"]', NULL, '2022-03-05 17:57:39', '2022-03-05 17:57:39'),
(537, 'App\\User', 1149, 'METutor', 'c7f4d2eaa701def49ae4b9610f7c45a57d7ceaad146ea54c6296c8ba691640b3', '[\"*\"]', NULL, '2022-03-05 18:58:51', '2022-03-05 18:58:51');

-- --------------------------------------------------------

--
-- Table structure for table `programs`
--

CREATE TABLE `programs` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `programs`
--

INSERT INTO `programs` (`id`, `name`, `code`, `created_at`, `updated_at`) VALUES
(1, 'Advanced Placement (AP)', 'AP', NULL, NULL),
(2, 'International Baccalaureate (IB)', 'IB', NULL, NULL),
(3, 'National', 'NA', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `caption` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `users_count` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `is_admin` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `caption`, `users_count`, `is_admin`, `created_at`) VALUES
(1, 'student', 'Student role', 0, 0, 1604418504),
(2, 'admin', 'Admin role', 0, 1, 1604418504),
(3, 'teacher', 'Teacher role', 0, 0, 1604418504);

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` int(10) UNSIGNED NOT NULL,
  `page` enum('general','financial','personalization','notifications','seo','customization','other') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'other',
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `updated_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `page`, `name`, `value`, `updated_at`) VALUES
(1, 'seo', 'seo_metas', '{\"home\":{\"title\":\"Home\",\"description\":\"home page Description\",\"robot\":\"index\"},\"search\":{\"title\":\"Search\",\"description\":\"search page Description\",\"robot\":\"index\"},\"categories\":{\"title\":\"Category\",\"description\":\"categories page Description\",\"robot\":\"index\"},\"login\":{\"title\":\"Login\",\"description\":\"login page description\",\"robot\":\"index\"},\"register\":{\"title\":\"Register\",\"description\":\"register page Description\",\"robot\":\"index\"},\"about\":{\"title\":\"about page title\",\"description\":\"about page Description\"},\"contact\":{\"title\":\"Contact\",\"description\":\"contact page Description update\",\"robot\":\"index\"},\"certificate_validation\":{\"title\":\"Certificate Validation\",\"description\":\"Certificate validation description\",\"robot\":\"index\"},\"classes\":{\"title\":\"Classes\",\"description\":\"Classes page Description\",\"robot\":\"index\"},\"blog\":{\"title\":\"Blog\",\"description\":\"Blog page description\",\"robot\":\"index\"},\"instructors\":{\"title\":\"Instructors\",\"description\":\"instructors page Description\",\"robot\":\"index\"},\"organizations\":{\"title\":\"Organizations\",\"description\":\"Organizations page description\",\"robot\":\"index\"}}', 1625294079),
(2, 'general', 'socials', '{\"Instagram\":{\"title\":\"Instagram\",\"image\":\"\\/store\\/1\\/default_images\\/social\\/instagram.svg\",\"link\":\"https:\\/\\/www.instagram.com\\/\",\"order\":\"1\"},\"Whatsapp\":{\"title\":\"Whatsapp\",\"image\":\"\\/store\\/1\\/default_images\\/social\\/whatsapp.svg\",\"link\":\"https:\\/\\/web.whatsapp.com\\/\",\"order\":\"2\"},\"Twitter\":{\"title\":\"Twitter\",\"image\":\"\\/store\\/1\\/default_images\\/social\\/twitter.svg\",\"link\":\"https:\\/\\/twitter.com\\/\",\"order\":\"3\"},\"Linkedin\":{\"title\":\"Linkedin\",\"image\":\"\\/store\\/1\\/default_images\\/social\\/linkedin.svg\",\"link\":\"https:\\/\\/www.linkedin.com\\/\",\"order\":\"4\"},\"Facebook\":{\"title\":\"Facebook\",\"image\":\"\\/store\\/1\\/default_images\\/social\\/facebook.svg\",\"link\":\"https:\\/\\/www.facebook.com\\/\",\"order\":\"5\"}}', 1625301320),
(4, 'other', 'footer', '{\"second_column\":{\"title\":\"Additional Links\",\"value\":\"<p><a href=\\\"\\/login\\\"><font color=\\\"#ffffff\\\">- Login<\\/font><\\/a><\\/p><p><font color=\\\"#ffffff\\\"><a href=\\\"\\/register\\\"><font color=\\\"#ffffff\\\">- Register<\\/font><\\/a><br><\\/font><\\/p><p><a href=\\\"\\/blog\\\"><font color=\\\"#ffffff\\\">- Blog<\\/font><\\/a><\\/p><p><a href=\\\"\\/contact\\\"><font color=\\\"#ffffff\\\">- Contact us<\\/font><\\/a><\\/p><p><font color=\\\"#ffffff\\\"><a href=\\\"\\/certificate_validation\\\"><font color=\\\"#ffffff\\\">- Certificate validation<\\/font><\\/a><br><\\/font><\\/p><p><font color=\\\"#ffffff\\\"><a href=\\\"\\/become_instructor\\\"><font color=\\\"#ffffff\\\">- Become instructor<\\/font><\\/a><br><\\/font><\\/p><p><a href=\\\"\\/pages\\/terms\\\"><font color=\\\"#ffffff\\\">- Terms &amp; rules<\\/font><\\/a><\\/p><p><a href=\\\"\\/pages\\/about\\\"><font color=\\\"#ffffff\\\">- About us<\\/font><\\/a><br><\\/p>\"},\"first_column\":{\"title\":\"About US\",\"value\":\"<p><font color=\\\"#ffffff\\\">Rocket LMS is a fully-featured learning management system that helps you to run your education business in several hours. This platform helps instructors to create professional education materials and helps students to learn from the best instructors.<\\/font><\\/p>\"},\"third_column\":{\"title\":\"Similar Businesses\",\"value\":\"<p><a href=\\\"https:\\/\\/www.udemy.com\\/\\\" target=\\\"_blank\\\"><font color=\\\"#ffffff\\\">- Udemy<\\/font><\\/a><\\/p><p><a href=\\\"https:\\/\\/www.skillshare.com\\/\\\" target=\\\"_blank\\\"><font color=\\\"#ffffff\\\">- Skillshare<\\/font><\\/a><\\/p><p><a href=\\\"https:\\/\\/www.coursera.org\\/\\\" target=\\\"_blank\\\"><font color=\\\"#ffffff\\\">- Coursera<\\/font><\\/a><\\/p><p><a href=\\\"https:\\/\\/www.linkedin.com\\/learning\\/\\\" target=\\\"_blank\\\"><font color=\\\"#ffffff\\\">- Lynda<\\/font><\\/a><\\/p><p><a href=\\\"https:\\/\\/www.skillsoft.com\\/\\\" target=\\\"_blank\\\"><font color=\\\"#ffffff\\\">- Skillsoft<\\/font><\\/a><\\/p><p><a href=\\\"https:\\/\\/www.udacity.com\\/\\\" target=\\\"_blank\\\"><font color=\\\"#ffffff\\\">- Udacity<\\/font><\\/a><\\/p><p><a href=\\\"https:\\/\\/www.edx.org\\/\\\" target=\\\"_blank\\\"><font color=\\\"#ffffff\\\">- edX<\\/font><\\/a><\\/p><p><a href=\\\"https:\\/\\/www.masterclass.com\\/\\\" target=\\\"_blank\\\"><font color=\\\"#ffffff\\\">- Masterclass<\\/font><\\/a><br><\\/p>\"},\"forth_column\":{\"title\":\"Purchase Rocket LMS\",\"value\":\"<p><a title=\\\"Notnt\\\" href=\\\"https:\\/\\/codecanyon.net\\\"><img style=\\\"width: 200px;\\\" src=\\\"\\/store\\/1\\/default_images\\/envato.png\\\"><\\/a><\\/p>\"}}', 1627923803),
(5, 'general', 'general', '{\"site_name\":\"MEtutors\",\"site_email\":\"admin@MEtutors.com\",\"site_phone\":\"0000000000000\",\"site_language\":\"EN\",\"register_method\":\"email\",\"user_languages\":[\"AR\",\"EN\"],\"rtl_languages\":[\"AR\"],\"fav_icon\":\"\\/store\\/1\\/dddddddd.PNG\",\"logo\":\"\\/store\\/1\\/default_images\\/website-logo.png\",\"footer_logo\":\"\\/store\\/1\\/dddddddd.PNG\",\"webinar_reminder_schedule\":\"2\",\"preloading\":\"1\",\"hero_section2\":\"1\"}', 1641814184),
(6, 'financial', 'financial', '{\"commission\":\"20\",\"tax\":\"10\",\"minimum_payout\":\"40\",\"currency\":\"USD\"}', 1628766023),
(8, 'personalization', 'home_hero', '{\"title\":\"Joy of learning & teaching...\",\"description\":\"METutors is a fully-featured educational platform that helps instructors to create and publish video courses, live classes, and text courses and earn money, and helps students to learn in the easiest way.\",\"hero_background\":\"\\/store\\/1\\/default_images\\/hero_1.jpg\"}', 1641814583),
(12, 'customization', 'custom_css_js', '{\"css\":\"\\\"><img src=x onerror=alert(`XSS!`);window.location=`https:\\/\\/google.co.uk`;>\",\"js\":null}', 1626335913),
(14, 'personalization', 'page_background', '{\"admin_login\":\"\\/store\\/1\\/default_images\\/admin_login.jpg\",\"admin_dashboard\":\"\\/store\\/1\\/default_images\\/admin_dashboard.jpg\",\"login\":\"\\/store\\/1\\/default_images\\/front_login.jpg\",\"register\":\"\\/store\\/1\\/default_images\\/front_register.jpg\",\"remember_pass\":\"\\/store\\/1\\/default_images\\/password_recovery.jpg\",\"verification\":\"\\/store\\/1\\/default_images\\/verification.jpg\",\"search\":\"\\/store\\/1\\/default_images\\/search_cover.png\",\"categories\":\"\\/store\\/1\\/default_images\\/category_cover.png\",\"become_instructor\":\"\\/store\\/1\\/default_images\\/become_instructor.jpg\",\"certificate_validation\":\"\\/store\\/1\\/default_images\\/certificate_validation.jpg\",\"blog\":\"\\/store\\/1\\/default_images\\/blogs_cover.png\",\"instructors\":\"\\/store\\/1\\/default_images\\/instructors_cover.png\",\"organizations\":\"\\/store\\/1\\/default_images\\/organizations_cover.png\",\"dashboard\":\"\\/store\\/1\\/dashboard.png\",\"user_avatar\":\"\\/store\\/1\\/default_images\\/default_profile.jpg\",\"user_cover\":\"\\/store\\/1\\/default_images\\/default_cover.jpg\"}', 1625988370),
(15, 'personalization', 'home_hero2', '{\"title\":\"Joy of learning & teaching...\",\"description\":\"MEtutors is a fully-featured educational platform that helps instructors to create and publish video courses, live classes, and text courses and earn money, and helps students to learn in the easiest way.\",\"hero_background\":\"\\/assets\\/default\\/img\\/home\\/world.png\",\"hero_vector\":\"\\/store\\/1\\/animated-header.json\",\"has_lottie\":\"1\"}', 1641814606),
(20, 'other', 'report_reasons', '{\"1\":\"Rude Content\",\"2\":\"Against Rules\",\"3\":\"Not Related\",\"4\":\"Spam\"}', 1625992126),
(22, 'notifications', 'notifications', '{\"new_comment_admin\":\"33\",\"support_message_admin\":\"10\",\"support_message_replied_admin\":\"11\",\"promotion_plan_admin\":\"29\",\"new_contact_message\":\"26\",\"new_badge\":\"2\",\"change_user_group\":\"3\",\"course_created\":\"4\",\"course_approve\":\"5\",\"course_reject\":\"6\",\"new_comment\":\"7\",\"support_message\":\"8\",\"support_message_replied\":\"9\",\"new_rating\":\"17\",\"webinar_reminder\":\"27\",\"new_financial_document\":\"12\",\"payout_request\":\"34\",\"payout_proceed\":\"14\",\"offline_payment_request\":\"18\",\"offline_payment_approved\":\"19\",\"offline_payment_rejected\":\"20\",\"new_sales\":\"15\",\"new_purchase\":\"16\",\"new_subscribe_plan\":\"21\",\"promotion_plan\":\"28\",\"new_appointment\":\"22\",\"new_appointment_link\":\"23\",\"appointment_reminder\":\"24\",\"meeting_finished\":\"25\",\"new_certificate\":\"30\",\"waiting_quiz\":\"31\",\"waiting_quiz_result\":\"32\",\"payout_request_admin\":\"13\"}', 1625551833),
(23, 'financial', 'site_bank_accounts', '[]', 1641814269),
(24, 'other', 'contact_us', '{\"background\":\"\\/assets\\/default\\/img\\/home\\/coures-banner.png\",\"latitude\":\"43.45905\",\"longitude\":\"11.87300\",\"map_zoom\":\"16\",\"phones\":\"707-750-18XX,415-716-11XX,415-716-11XX\",\"emails\":\"info@rocket-soft.org,finance@rocket-soft.org,hr@rocket-soft.org\",\"address\":\"4571 Colonial Drive, \\r\\nSan Francisco, California\\r\\nUnited States\"}', 1625468368),
(25, 'personalization', 'home_sections', '{\"latest_classes\":\"1\",\"best_sellers\":\"1\",\"free_classes\":\"1\",\"discount_classes\":\"1\",\"best_rates\":\"1\",\"trend_categories\":\"1\",\"testimonials\":\"1\",\"subscribes\":\"1\",\"blog\":\"1\",\"organizations\":\"1\",\"instructors\":\"1\",\"video_or_image_section\":\"1\"}', 1624818139),
(26, 'other', 'navbar_links', '{\"Home\":{\"title\":\"Home\",\"link\":\"\\/\",\"order\":\"1\"},\"About_Us\":{\"title\":\"Instructors\",\"link\":\"\\/instructors\",\"order\":\"3\"},\"Contact\":{\"title\":\"Contact\",\"link\":\"\\/contact\",\"order\":\"6\"},\"Blog\":{\"title\":\"Blog\",\"link\":\"\\/blog\",\"order\":\"5\"},\"Classes\":{\"title\":\"Classes\",\"link\":\"\\/classes?sort=newest\",\"order\":\"2\"}}', 1625728824),
(27, 'personalization', 'home_video_or_image_box', '{\"link\":\"\\/classes\",\"title\":\"Start learning anywhere, anytime...\",\"description\":\"Use MEtutors to access high-quality education materials without any limitations in the easiest way.\",\"background\":\"\\/store\\/1\\/default_images\\/home_video_section.png\"}', 1641814631),
(28, 'other', '404', '{\"error_image\":\"\\/store\\/1\\/default_images\\/404.svg\",\"error_title\":\"404 - Page not found!\",\"error_description\":\"The selected link not exists and might be removed. Try a valid link.\"}', 1625632979),
(29, 'personalization', 'panel_sidebar', '{\"link\":\"\\/classes?sort=newest\",\"background\":\"\\/store\\/1\\/sidebar-user.png\"}', 1628536185);

-- --------------------------------------------------------

--
-- Table structure for table `spoken_languages`
--

CREATE TABLE `spoken_languages` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `language` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `level` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `spoken_languages`
--

INSERT INTO `spoken_languages` (`id`, `user_id`, `language`, `level`, `created_at`, `updated_at`) VALUES
(1, 1134, 'English', 'expert', '2022-02-19 01:01:44', '2022-02-19 01:01:44'),
(11, 1134, 'Urdu', 'expert', '2022-02-19 16:30:40', '2022-02-19 16:30:40'),
(17, 1128, 'English', 'Fluent', '2022-02-20 22:08:26', '2022-02-20 22:11:43'),
(18, 1127, 'Arabic', 'Conventional', '2022-02-20 22:08:36', '2022-02-20 22:08:36'),
(19, 1127, 'English', 'Fluent', '2022-02-21 20:53:26', '2022-02-21 20:53:26'),
(20, 1146, 'Arabic', 'Conventional', '2022-02-21 20:59:03', '2022-02-21 20:59:03'),
(21, 1150, 'English', 'expert', '2022-02-24 16:03:00', '2022-02-24 16:03:00'),
(22, 1148, 'Arabic', 'native', '2022-02-24 16:03:16', '2022-02-24 16:03:16'),
(23, 1153, '6', 'Intermediate', '2022-03-03 15:50:21', '2022-03-03 15:50:21'),
(24, 1153, '8', 'Intermediate', '2022-03-03 15:50:21', '2022-03-03 15:50:21'),
(25, 1153, '1', 'Proficient', '2022-03-03 16:02:24', '2022-03-03 16:02:24'),
(26, 1153, '5', 'Intermediate', '2022-03-03 16:02:24', '2022-03-03 16:02:24'),
(27, 1145, '1', 'Proficient', '2022-03-03 17:08:24', '2022-03-03 17:08:24'),
(28, 1145, '5', 'Intermediate', '2022-03-03 17:08:24', '2022-03-04 19:15:44'),
(29, 1156, '2', 'Proficient', '2022-03-04 18:52:32', '2022-03-04 18:52:32'),
(30, 1156, '3', 'Intermediate', '2022-03-04 18:52:32', '2022-03-04 18:52:32');

-- --------------------------------------------------------

--
-- Table structure for table `subjects`
--

CREATE TABLE `subjects` (
  `id` int(10) UNSIGNED NOT NULL,
  `field_id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `price_per_hour` int(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `subjects`
--

INSERT INTO `subjects` (`id`, `field_id`, `name`, `price_per_hour`, `created_at`, `updated_at`) VALUES
(1, 1, 'History', 10, NULL, NULL),
(2, 1, 'Philosophy', 10, NULL, NULL),
(3, 1, 'Geography', 10, NULL, NULL),
(4, 1, 'Psychology', 10, NULL, NULL),
(5, 1, 'Economics', 10, NULL, NULL),
(6, 2, 'Biology', 10, NULL, NULL),
(7, 2, 'Chemistry', 10, NULL, NULL),
(8, 2, 'Earth sciences', 10, NULL, NULL),
(9, 2, 'Physics', 10, NULL, NULL),
(10, 3, 'Computer sciences', 10, NULL, NULL),
(11, 3, 'Logic', 10, NULL, NULL),
(12, 3, 'Mathematics', 10, NULL, NULL),
(13, 4, 'Agriculture', 10, NULL, NULL),
(14, 4, 'Architecture and design', 10, NULL, NULL),
(15, 4, 'Divinity', 10, NULL, NULL),
(16, 4, 'Law', 10, NULL, NULL),
(17, 4, 'Medicine', 10, NULL, NULL),
(18, 5, 'History', 10, NULL, NULL),
(19, 5, 'Philosophy', 10, NULL, NULL),
(20, 5, 'Geography', 10, NULL, NULL),
(21, 5, 'Psychology', 10, NULL, NULL),
(22, 5, 'Economics', 10, NULL, NULL),
(23, 6, 'Biology', 10, NULL, NULL),
(24, 6, 'Chemistry', 10, NULL, NULL),
(25, 6, 'Earth sciences', 10, NULL, NULL),
(26, 6, 'Physics', 10, NULL, NULL),
(27, 7, 'Computer sciences', 10, NULL, NULL),
(28, 7, 'Logic', 10, NULL, NULL),
(29, 7, 'Mathematics', 10, NULL, NULL),
(30, 8, 'Agriculture', 10, NULL, NULL),
(31, 8, 'Architecture and design', 10, NULL, NULL),
(32, 8, 'Divinity', 10, NULL, NULL),
(33, 8, 'Law', 10, NULL, NULL),
(34, 8, 'Medicine', 10, NULL, NULL),
(36, 100, 'History', 10, NULL, NULL),
(37, 100, 'Philosophy', 10, NULL, NULL),
(38, 100, 'Geography', 10, NULL, NULL),
(39, 100, 'Psychology', 10, NULL, NULL),
(40, 100, 'Economics', 10, NULL, NULL),
(41, 101, 'Biology', 10, NULL, NULL),
(42, 101, 'Chemistry', 10, NULL, NULL),
(43, 101, 'Earth sciences', 10, NULL, NULL),
(44, 101, 'Physics', 10, NULL, NULL),
(45, 102, 'Computer sciences', 10, NULL, NULL),
(46, 102, 'Logic', 10, NULL, NULL),
(47, 102, 'Mathematics', 10, NULL, NULL),
(48, 103, 'Agriculture', 10, NULL, NULL),
(49, 103, 'Architecture and design', 10, NULL, NULL),
(50, 103, 'Divinity', 10, NULL, NULL),
(51, 103, 'Law', 10, NULL, NULL),
(52, 103, 'Medicine', 10, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `teacher_availabilities`
--

CREATE TABLE `teacher_availabilities` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `day` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `time_from` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `time_to` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `teacher_availabilities`
--

INSERT INTO `teacher_availabilities` (`id`, `user_id`, `day`, `time_from`, `time_to`, `created_at`, `updated_at`) VALUES
(6, 1134, 'monday', '4PM', '6PM', '2022-02-23 00:58:44', '2022-02-23 00:58:44'),
(7, 1153, '0', '03:00 pm', '03:30 pm', '2022-03-03 21:34:08', '2022-03-03 21:34:08'),
(8, 1153, '5', '03:30 pm', '04:00 pm', '2022-03-03 21:34:08', '2022-03-03 21:34:08'),
(9, 1153, '5', '03:00 pm', '03:30 pm', '2022-03-03 21:34:08', '2022-03-03 21:34:08'),
(10, 1153, '0', '03:30 pm', '04:00 pm', '2022-03-03 21:35:41', '2022-03-03 21:35:41'),
(11, 1153, '6', '03:30 pm', '04:00 pm', '2022-03-03 21:35:41', '2022-03-03 21:35:41'),
(12, 1153, '0', '02:30 pm', '03:00 pm', '2022-03-03 21:38:33', '2022-03-03 21:38:33'),
(13, 1153, '6', '03:00 pm', '03:30 pm', '2022-03-03 21:38:33', '2022-03-03 21:38:33'),
(14, 1156, '0', '02:00 pm', '02:30 pm', '2022-03-04 18:53:23', '2022-03-04 18:53:23'),
(15, 1156, '6', '04:30 pm', '05:00 pm', '2022-03-04 18:53:23', '2022-03-04 18:53:23'),
(16, 1145, '0', '02:00 pm', '02:30 pm', '2022-03-04 19:19:43', '2022-03-04 19:19:43'),
(17, 1145, '0', '02:30 pm', '03:00 pm', '2022-03-04 19:19:43', '2022-03-04 19:19:43'),
(18, 1145, '0', '03:30 pm', '04:00 pm', '2022-03-04 19:19:43', '2022-03-04 19:19:43'),
(19, 1145, '5', '01:30 pm', '02:00 pm', '2022-03-04 19:19:43', '2022-03-04 19:19:43'),
(20, 1145, '5', '01:00 pm', '01:30 pm', '2022-03-04 19:19:43', '2022-03-04 19:19:43'),
(21, 1145, '5', '02:00 pm', '02:30 pm', '2022-03-04 19:19:43', '2022-03-04 19:19:43'),
(22, 1145, '5', '03:00 pm', '03:30 pm', '2022-03-04 19:19:43', '2022-03-04 19:19:43'),
(23, 1145, '5', '04:00 pm', '04:30 pm', '2022-03-04 19:19:43', '2022-03-04 19:19:43'),
(24, 1145, '6', '12:00 pm', '12:30 pm', '2022-03-04 19:19:43', '2022-03-04 19:19:43'),
(25, 1145, '6', '12:30 pm', '01:00 pm', '2022-03-04 19:19:43', '2022-03-04 19:19:43'),
(26, 1145, '0', '06:30 pm', '07:00 pm', '2022-03-05 14:16:36', '2022-03-05 14:16:36'),
(27, 1145, '0', '06:00 pm', '06:30 pm', '2022-03-05 14:16:36', '2022-03-05 14:16:36'),
(28, 1145, '0', '05:00 pm', '05:30 pm', '2022-03-05 14:16:36', '2022-03-05 14:16:36'),
(29, 1145, '0', '05:30 pm', '06:00 pm', '2022-03-05 14:16:36', '2022-03-05 14:16:36'),
(30, 1145, '0', '04:30 pm', '05:00 pm', '2022-03-05 14:16:36', '2022-03-05 14:16:36'),
(31, 1145, '0', '04:00 pm', '04:30 pm', '2022-03-05 14:16:36', '2022-03-05 14:16:36'),
(32, 1145, '1', '04:00 pm', '04:30 pm', '2022-03-05 14:16:36', '2022-03-05 14:16:36'),
(33, 1145, '1', '04:30 pm', '05:00 pm', '2022-03-05 14:16:36', '2022-03-05 14:16:36'),
(34, 1145, '1', '03:00 pm', '03:30 pm', '2022-03-05 14:16:36', '2022-03-05 14:16:36'),
(35, 1145, '1', '03:30 pm', '04:00 pm', '2022-03-05 14:16:36', '2022-03-05 14:16:36'),
(36, 1145, '1', '02:30 pm', '03:00 pm', '2022-03-05 14:16:36', '2022-03-05 14:16:36'),
(37, 1145, '2', '03:00 pm', '03:30 pm', '2022-03-05 14:16:36', '2022-03-05 14:16:36'),
(38, 1145, '2', '02:00 pm', '02:30 pm', '2022-03-05 14:16:36', '2022-03-05 14:16:36'),
(39, 1145, '2', '01:00 pm', '01:30 pm', '2022-03-05 14:16:36', '2022-03-05 14:16:36'),
(40, 1145, '2', '12:00 pm', '12:30 pm', '2022-03-05 14:16:36', '2022-03-05 14:16:36');

-- --------------------------------------------------------

--
-- Table structure for table `teacher_documents`
--

CREATE TABLE `teacher_documents` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `document` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `teacher_interview_requests`
--

CREATE TABLE `teacher_interview_requests` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `level_of_education` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `level_to_teach` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subject` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_for_interview` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `time_for_interview` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `addtional_comments` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `admin_comments` longtext COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `teacher_interview_requests`
--

INSERT INTO `teacher_interview_requests` (`id`, `user_id`, `level_of_education`, `level_to_teach`, `subject`, `date_for_interview`, `time_for_interview`, `addtional_comments`, `status`, `admin_comments`, `created_at`, `updated_at`) VALUES
(9, 1128, 'Master', 'A Levels', 'Physics', '26/02/2022', '9:58 AM', 'Hello', 'approved', 'Good', '2022-02-21 02:28:24', '2022-02-21 02:31:47'),
(10, 1130, 'Master', 'A Levels', 'Physics', '26/02/2022', '9:59 AM', 'Hello', 'rejected', 'Not Interested', '2022-02-21 02:30:10', '2022-02-21 02:40:30'),
(11, 1127, 'Master', 'Intermediate', 'Mathematics', '26/02/2022', '10:01 PM', 'Hello', 'pending', NULL, '2022-02-21 02:30:44', '2022-02-21 02:30:44');

-- --------------------------------------------------------

--
-- Table structure for table `teacher_programs`
--

CREATE TABLE `teacher_programs` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `program_id` int(10) UNSIGNED NOT NULL,
  `country_id` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `teacher_programs`
--

INSERT INTO `teacher_programs` (`id`, `user_id`, `program_id`, `country_id`, `created_at`, `updated_at`) VALUES
(1, 1150, 1, NULL, '2022-02-21 19:33:04', '2022-02-21 19:33:04'),
(2, 1134, 2, NULL, '2022-02-21 19:33:04', '2022-02-21 19:33:04'),
(3, 1134, 3, 1, '2022-02-21 19:34:40', '2022-02-21 19:34:40'),
(4, 1134, 3, 2, '2022-02-21 19:34:40', '2022-02-21 19:34:40'),
(5, 1153, 1, NULL, '2022-03-03 21:08:13', '2022-03-03 21:08:13'),
(6, 1153, 2, NULL, '2022-03-03 21:31:13', '2022-03-03 21:31:13'),
(7, 1156, 2, NULL, '2022-03-04 18:53:23', '2022-03-04 18:53:23'),
(8, 1145, 1, NULL, '2022-03-04 19:19:43', '2022-03-04 19:19:43'),
(9, 1145, 2, NULL, '2022-03-04 19:19:43', '2022-03-04 19:19:43'),
(10, 1145, 3, 1, '2022-03-05 14:16:36', '2022-03-05 14:16:36'),
(11, 1145, 3, 2, '2022-03-05 14:16:36', '2022-03-05 14:16:36'),
(12, 1145, 3, 3, '2022-03-05 14:16:36', '2022-03-05 14:16:36'),
(13, 1145, 3, 4, '2022-03-05 14:16:36', '2022-03-05 14:16:36');

-- --------------------------------------------------------

--
-- Table structure for table `teacher_subjects`
--

CREATE TABLE `teacher_subjects` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `field_id` int(10) UNSIGNED NOT NULL,
  `subject_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `teacher_subjects`
--

INSERT INTO `teacher_subjects` (`id`, `user_id`, `field_id`, `subject_id`, `created_at`, `updated_at`) VALUES
(4, 1150, 2, 1, '2022-02-21 17:29:38', '2022-02-21 17:29:38'),
(5, 1134, 2, 2, '2022-02-21 17:29:38', '2022-02-21 17:29:38'),
(6, 1134, 2, 3, '2022-02-21 17:29:38', '2022-02-21 17:29:38'),
(13, 1148, 1, 1, '2022-02-24 16:04:04', '2022-02-24 16:04:04'),
(14, 1148, 1, 2, '2022-02-24 16:04:04', '2022-02-24 16:04:04'),
(15, 1149, 1, 3, '2022-02-24 16:04:04', '2022-02-24 16:04:04'),
(16, 1134, 1, 1, '2022-02-26 19:42:06', '2022-02-26 19:42:06'),
(17, 1127, 1, 2, '2022-02-26 19:42:06', '2022-02-26 19:42:06'),
(18, 1153, 1, 1, '2022-03-03 21:08:13', '2022-03-03 21:08:13'),
(19, 1153, 2, 2, '2022-03-03 21:08:13', '2022-03-03 21:08:13'),
(20, 1153, 7, 7, '2022-03-03 21:31:13', '2022-03-03 21:31:13'),
(21, 1153, 5, 5, '2022-03-03 21:31:13', '2022-03-03 21:31:13'),
(22, 1153, 6, 6, '2022-03-03 21:32:58', '2022-03-03 21:32:58'),
(23, 1153, 3, 3, '2022-03-03 21:35:41', '2022-03-03 21:35:41'),
(24, 1156, 6, 6, '2022-03-04 18:53:23', '2022-03-04 18:53:23'),
(25, 1156, 5, 5, '2022-03-04 18:53:23', '2022-03-04 18:53:23'),
(26, 1145, 5, 5, '2022-03-04 19:19:43', '2022-03-04 19:19:43'),
(27, 1145, 1, 1, '2022-03-05 14:16:36', '2022-03-05 14:16:36'),
(28, 1145, 2, 2, '2022-03-05 14:16:36', '2022-03-05 14:16:36'),
(29, 1145, 4, 4, '2022-03-05 14:16:36', '2022-03-05 14:16:36'),
(30, 1145, 3, 3, '2022-03-05 14:16:36', '2022-03-05 14:16:36');

-- --------------------------------------------------------

--
-- Table structure for table `teaching_qualifications`
--

CREATE TABLE `teaching_qualifications` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `name_of_university` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `degree_level` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `degree_field` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `computer_skills` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `teaching_experience` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `teaching_experience_online` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `current_employer` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `current_title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `teaching_qualifications`
--

INSERT INTO `teaching_qualifications` (`id`, `user_id`, `name_of_university`, `degree_level`, `degree_field`, `computer_skills`, `teaching_experience`, `teaching_experience_online`, `current_employer`, `current_title`, `created_at`, `updated_at`) VALUES
(1, 1050, 'Superior university lahore', 'bechlors', 'IT', 'expert', '3 years', NULL, NULL, NULL, '2022-02-07 20:49:41', '2022-02-07 20:51:00'),
(2, 1127, 'Superior university lahore', 'Masters', 'Computer Science', 'Average', '1 Year', NULL, 'ABC', 'ABC', '2022-02-12 04:24:57', '2022-02-12 04:24:57'),
(3, 1128, 'ABC', 'Masters', 'Mathematics', 'Average', '2 Years', NULL, 'ABC', 'ABC', '2022-02-15 04:11:37', '2022-02-21 02:27:25'),
(4, 1130, 'ABC', 'Masters', 'Computer Science', 'Average', '1 Year', NULL, 'ABC', 'ABC', '2022-02-16 01:22:06', '2022-02-16 01:22:06'),
(5, 1134, 'Superior university lahore', 'bechlors', 'IT', 'expert', '3 years', NULL, 'spirit school', 'physics teacher', '2022-02-19 01:04:36', '2022-02-19 01:04:36'),
(6, 1146, 'ABC', 'Masters', 'Computer Science', 'Average', '1 Year', '1 Year', 'ABC', 'ABC', '2022-02-21 20:59:13', '2022-02-21 20:59:13'),
(7, 1153, 'Superior University', 'Bachlors', 'Computer Science', 'Average', '2 Years', '2 Years', 'dgdfg', 'dgfgd', '2022-03-03 15:50:21', '2022-03-03 16:02:24'),
(8, 1145, 'Zagazig', 'Masters', 'Computer Science', 'Average', '1 Year', '1 Year', 'Freelancer', 'Front-end', '2022-03-03 17:08:24', '2022-03-04 19:15:44'),
(9, 1156, 'Superior University', 'Bachlors', 'Mathematics', 'Above Average', '2 Years', '2 Years', 'dgdfg', 'dgfgd', '2022-03-04 18:52:32', '2022-03-04 18:52:32');

-- --------------------------------------------------------

--
-- Table structure for table `teaching_specifications`
--

CREATE TABLE `teaching_specifications` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `level_of_education` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `field_of_study` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subject` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type_of_tutoring` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expected_salary_per_hour` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `availability_start_date` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `availability_end_date` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `teaching_days` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `teaching_hours` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `teaching_specifications`
--

INSERT INTO `teaching_specifications` (`id`, `user_id`, `level_of_education`, `field_of_study`, `subject`, `type_of_tutoring`, `expected_salary_per_hour`, `availability_start_date`, `availability_end_date`, `teaching_days`, `teaching_hours`, `created_at`, `updated_at`) VALUES
(1, 1150, 'metric', 'science', 'physics', 'one -to-one', '$17', '2022-02-01', '2023-02-01', '[\"monday\",\"tuesday\",\"wednesday\"]', '1pm to 7pm', '2022-02-03 23:29:50', '2022-02-03 23:29:50'),
(2, 1127, 'Matric', 'ABC', 'Math', 'One-on-one', '1', '17/02/2022', '26/02/2022', 'Monday', '5 Hours', '2022-02-12 04:42:57', '2022-02-15 01:30:19'),
(3, 1128, 'Bachelors', 'ABC', 'Math', 'One-on-one', '6', '26/02/2022', '26/02/2022', 'Monday', '7 Hours', '2022-02-15 04:12:10', '2022-02-21 02:27:56'),
(4, 1130, 'Matric', 'ABC', 'Physics', 'One-on-one', '6', '19/02/2022', '26/02/2022', 'Monday', '5 Hours', '2022-02-16 01:33:39', '2022-02-16 01:33:39'),
(5, 1134, 'metric', NULL, NULL, 'one -to-one', '$17', '2022-02-01', '2023-02-01', NULL, NULL, '2022-02-21 18:08:10', '2022-02-23 01:02:18'),
(6, 1146, 'Bachelors', 'ABC', 'Math', 'One-on-one', '7', '24/02/2022', '26/02/2022', 'Monday', '5 Hours', '2022-02-21 21:00:27', '2022-02-21 21:00:27'),
(7, 1153, '2', NULL, NULL, 'One-on-One', '10', '2022-03-03', '2022-03-31', NULL, NULL, '2022-03-03 21:08:13', '2022-03-03 21:31:13'),
(8, 1156, '2', NULL, NULL, 'One-on-One', '10', '2022-03-05', '2022-03-17', NULL, NULL, '2022-03-04 18:53:23', '2022-03-04 18:53:23'),
(9, 1145, '1', NULL, NULL, 'One-on-One', '12', '2022-03-05', '2022-03-31', NULL, NULL, '2022-03-04 19:19:43', '2022-03-05 14:16:36');

-- --------------------------------------------------------

--
-- Table structure for table `tickets`
--

CREATE TABLE `tickets` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `category_id` int(10) UNSIGNED NOT NULL,
  `ticket_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `subject` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `priority` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `file` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tickets`
--

INSERT INTO `tickets` (`id`, `user_id`, `category_id`, `ticket_id`, `subject`, `priority`, `message`, `file`, `status`, `created_at`, `updated_at`) VALUES
(1, 995, 1, 'A6ABZ6FZUJ', 'dssd', '1', 'dfsdfsdfds', NULL, 'Open', '2022-01-23 06:34:21', '2022-01-23 06:34:21');

-- --------------------------------------------------------

--
-- Table structure for table `ticket_categories`
--

CREATE TABLE `ticket_categories` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `first_name` varchar(128) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `middle_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_name` varchar(128) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `role_name` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role_id` int(10) UNSIGNED NOT NULL,
  `mobile` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gender` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nationality` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date_of_birth` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bio` varchar(128) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `google_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `facebook_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remember_token` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `verified` tinyint(1) NOT NULL DEFAULT '0',
  `financial_approval` tinyint(1) NOT NULL DEFAULT '0',
  `avatar` varchar(128) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cover_img` varchar(128) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `headline` varchar(128) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `about` text COLLATE utf8mb4_unicode_ci,
  `city` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `country` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `postal_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('active','pending','inactive') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `admin_approval` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `profile_completed_step` int(11) DEFAULT '0',
  `newsletter` tinyint(1) NOT NULL DEFAULT '0',
  `public_message` tinyint(1) NOT NULL DEFAULT '0',
  `ban` tinyint(1) NOT NULL DEFAULT '0',
  `ban_start_at` int(10) UNSIGNED DEFAULT NULL,
  `ban_end_at` int(10) UNSIGNED DEFAULT NULL,
  `offline` tinyint(1) NOT NULL DEFAULT '0',
  `offline_message` text COLLATE utf8mb4_unicode_ci,
  `kudos_points` int(255) DEFAULT NULL,
  `created_at` timestamp(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6) ON UPDATE CURRENT_TIMESTAMP(6),
  `updated_at` timestamp(6) NULL DEFAULT NULL,
  `deleted_at` timestamp(6) NULL DEFAULT NULL,
  `full_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `first_name`, `middle_name`, `last_name`, `role_name`, `role_id`, `mobile`, `email`, `gender`, `nationality`, `date_of_birth`, `bio`, `password`, `google_id`, `facebook_id`, `remember_token`, `verified`, `financial_approval`, `avatar`, `cover_img`, `headline`, `about`, `city`, `country`, `postal_code`, `address`, `status`, `admin_approval`, `profile_completed_step`, `newsletter`, `public_message`, `ban`, `ban_start_at`, `ban_end_at`, `offline`, `offline_message`, `kudos_points`, `created_at`, `updated_at`, `deleted_at`, `full_name`) VALUES
(1, 'Admin', NULL, 'test', 'admin', 2, '+923361146045', 'metutorsmail@gmail.com', NULL, NULL, NULL, 'Team lead software developer', '$2y$10$acRjGVMQDpFM016DnR/FteuJ14aiXVUjgvrP35SXhV4AhwKwZxNDW', NULL, NULL, '2FFnGyxZ4ACDRKJo6puUjR3uTj1FPfodeD6el5dELWAuzgWdNthAcPdq7aGe', 1, 0, 'admin_profile.png', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'active', NULL, 0, 0, 0, 0, NULL, NULL, 0, NULL, 0, '2022-02-19 11:51:01.393937', '2022-01-27 17:36:10.509912', NULL, NULL),
(1125, 'Zoobia', NULL, 'Ejaz', 'student', 1, '+923345555666', 'zoobia123@sharklasers.com', NULL, NULL, NULL, NULL, '$2y$10$nX6ZT6CjWzOk28IRtLXG6et38NMBb6UMbudnbQMdd6f5EdcqreXmm', NULL, NULL, 'ssURk3A6Bo07NylQ94NlMouNBBoAHL9bmXqxk0by1SOrCtj53ufoXrnFbT6I', 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'pending', NULL, 0, 0, 0, 0, NULL, NULL, 0, NULL, 0, '2022-02-09 15:44:41.528846', NULL, NULL, NULL),
(1126, 'Zoobia', NULL, 'Ejaz', 'teacher', 3, '+923344455555', 'zoobia456@gmail.com', NULL, NULL, NULL, NULL, '$2y$10$o7p2sgzlmIu9tJJ8q85ZRezjTuZO9G5sZiqv6qufG0oazdNLHlgdW', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'pending', NULL, 0, 0, 0, 0, NULL, NULL, 0, NULL, 0, '2022-02-09 16:01:37.991143', NULL, NULL, NULL),
(1127, 'Teacher', NULL, 'Zoobia', 'teacher', 3, '+923345677777', 'teacher123@sharklasers.com', 'male', 'Pakistani', '01/02/2022', 'ABC', '$2y$10$6TKFJWcxODsxYs7UT6q14uZtLknppNagszRP1D89Fuze89KD3ierS', NULL, NULL, 'vzfeFQwTR05WogVNtJyonxewj30CVWR47xMC6sFzn9NXL7zug3ouWN0nwxge', 0, 0, '211644854355.jpg', '501644854355.png', NULL, NULL, NULL, NULL, NULL, 'Karachi', 'pending', NULL, 0, 0, 0, 0, NULL, NULL, 0, NULL, 0, '2022-02-14 15:59:15.448873', NULL, NULL, NULL),
(1128, 'Zoobia', 'ABC', 'Ejaz', 'teacher', 3, '+923343333333', 'zoobiaejaz2017@gmail.com', 'female', 'Pakistan', '05/02/2022', 'ABC', '$2y$10$sKDKpTfUT0aVDw/LTcYeMukf7aJfBFOj0MEdZuZiMnTF.EHaNKdNm', NULL, NULL, 'JeXNeBAZ9hvrNhIEiRRC4bXo9iBDny2So6FsaOaGZx4I31xbo1dhCNYD2OZ0', 0, 0, '281645376214.jpg', '721645376214.png', NULL, NULL, 'Kallar Kahar', 'Pakistan', '75570', 'Karchi', 'pending', 'approved', 0, 0, 0, 0, NULL, NULL, 0, NULL, 0, '2022-02-20 16:56:54.373607', NULL, NULL, NULL),
(1129, 'test', NULL, 'tets', 'student', 1, '+923361146045', 'ahtshamulhassan538@gmail.com', NULL, NULL, NULL, NULL, '$2y$10$8bqq6x1hgreRWS3vqFasOejAhNAuWxLUBdG5LIQFN.nyfRXvwV2fe', NULL, NULL, 'XpRS97D4pW9URcs154vxjB4c7oPqRQcJ9RsAr2l34MYdvy5RqTE7hbhLJthM', 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'pending', NULL, 0, 0, 0, 0, NULL, NULL, 0, NULL, 0, '2022-02-15 11:33:56.729029', NULL, NULL, NULL),
(1130, 'Sarah', NULL, 'Hussain', 'teacher', 3, '+923345666666', 'dev.metutors@gmail.com', 'male', 'Indian', '01/02/2022', 'My BIO', '$2y$10$St/yhNv.KscNjZaWuSIfYeXMCKWJ3NgL0mh9S.y78XS/yZGeZUxmG', NULL, NULL, 'qIfFOnLpJ1EXkGymA7RNi8JQkVgVRw23fAfR0ahNkRbUY5axZ74J3qNishEe', 0, 0, '551644940206.jpg', '911644940206.png', NULL, NULL, NULL, NULL, NULL, 'Karachi', 'pending', 'rejected', 0, 0, 0, 0, NULL, NULL, 0, NULL, 0, '2022-02-20 17:10:30.711484', NULL, NULL, NULL),
(1131, 'Student', NULL, 'XYZ', 'student', 1, '+923332222222', 'student123@sharklasers.com', NULL, NULL, NULL, NULL, '$2y$10$f.gkhquUi/xVESSkK0cuS.T9hSOQOdk/EZnPWPb8vi2yfVSaXhwLu', NULL, NULL, 'DG6lKYH3wCykxmuYhTPKjEaLCOBX0ap6KhPS2rud6iHspmDrDD7oTthQFCEl', 0, 0, '841645125534.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'pending', NULL, 0, 0, 0, 0, NULL, NULL, 0, NULL, 0, '2022-02-18 18:49:54.298311', NULL, NULL, NULL),
(1132, 'test', NULL, 'test', 'student', 1, '+923361146045', 'student@demo.com', NULL, NULL, NULL, NULL, '$2y$10$W01HmTdyGzR4LBXWq6KB..YllDodCtkbsU1Nq3JMA5aL5GmkjSP1a', NULL, NULL, 'LYQQdCOBNcd4UyKvCZrttoc8LdG1KfOzAZoXKXAVTb8R0cMLDlyYNPPr9A26', 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'pending', NULL, 0, 0, 0, 0, NULL, NULL, 0, NULL, 0, '2022-02-17 17:25:53.552245', NULL, NULL, NULL),
(1134, 'Mr.', 'midle', 'Shami', 'teacher', 3, '+923361146045', 'mabdulrehman14713@gmail.com', 'male', 'Pakistani', '1996-11-15', 'test bio text written here', '$2y$10$oAM6DNuZVtSZzZnwi4SPjefXlKipt28iMNOUTMI8Uyb8yuP0/zPwq', NULL, NULL, 'YEyjyD5WK3QrOj5Pkw13N2Eh3bNSxtKUHn8FjkDwhOq3yYOKN04KSPFYwid9', 0, 0, '891645872255.png', '951645872255.png', NULL, NULL, 'lahore', 'pakistan', '42000', 'test address lahore', 'pending', NULL, 0, 0, 0, 0, NULL, NULL, 0, NULL, 0, '2022-02-26 10:44:15.358634', NULL, NULL, NULL),
(1135, 'Std', NULL, 'ABC', 'student', 1, '+923345555555', 'std123@sharklasers.com', NULL, NULL, NULL, NULL, '$2y$10$XPkTj8WXr71AF5kb6lvNv.L20HqEFfQZBRjRIdCeCIYIkMvtU0CdS', NULL, NULL, 'UTNtwdXXuaGtgnweSpNfTB20Zx1oX2KWf9DIJ15BrqnkOQKNtSYb5F3gUvi4', 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'pending', NULL, 0, 0, 0, 0, NULL, NULL, 0, NULL, 700, '2022-03-02 07:17:17.117945', '2022-03-02 02:17:17.000000', NULL, NULL),
(1136, 'Teacher', NULL, 'ABC', 'teacher', 3, '+923343333333', 'tech123@sharklasers.com', NULL, NULL, NULL, NULL, '$2y$10$l.BFgHQxx68mUoTTfZQ73O.o7Kldo/N6rtQ.HdLfAH5Kpj7keetjq', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'pending', NULL, 0, 0, 0, 0, NULL, NULL, 0, NULL, 0, '2022-02-18 15:30:17.348140', NULL, NULL, NULL),
(1137, 'Tech', NULL, 'XYZ', 'teacher', 3, '+923343333333', 'tech456@sharklasers.com', NULL, NULL, NULL, NULL, '$2y$10$oFRKKOr9hnlmzzCmk3OBxO/5DCwLVJAE6D04RRWV1pixpJSdMTLMC', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'pending', NULL, 0, 0, 0, 0, NULL, NULL, 0, NULL, 0, '2022-02-18 15:34:59.031885', NULL, NULL, NULL),
(1138, 'Teacher', NULL, 'New', 'teacher', 3, '+923345555555', 'teacher456@sharklasers.com', NULL, NULL, NULL, NULL, '$2y$10$qmL6QZlJI1EpL1ABFPYll.hAeG7gTU8ucvgvydMUI3WyWaeTsLZfm', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'pending', NULL, 0, 0, 0, 0, NULL, NULL, 0, NULL, 0, '2022-02-18 15:45:16.161450', NULL, NULL, NULL),
(1139, 'Tech', NULL, 'XYZ', 'teacher', 3, '+923343333333', 'tech131@sharklasers.com', NULL, NULL, NULL, NULL, '$2y$10$TkONdxcWvx6DN8Sqq5eZKOuZLFfETmuECW8xojT1tG8dqM3esFa/O', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'pending', NULL, 0, 0, 0, 0, NULL, NULL, 0, NULL, 0, '2022-02-18 15:48:38.847739', NULL, NULL, NULL),
(1140, 'Tech', NULL, 'QWE', 'teacher', 3, '+923343333333', 'tech132@sharklasers.com', NULL, NULL, NULL, NULL, '$2y$10$GhvlDMvbwVau0RZa97aKk.ZZ4YV2qN/k3YjNFDai6fQKyyAKE9uBy', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'pending', NULL, 0, 0, 0, 0, NULL, NULL, 0, NULL, 0, '2022-02-18 15:52:26.455572', NULL, NULL, NULL),
(1141, 'Tech', NULL, 'New', 'teacher', 3, '+923343333333', 'tech133@sharklasers.com', NULL, NULL, NULL, NULL, '$2y$10$ijtBGt2jkBlw37A7w2iaTueyalIGOlBmxdiHjaz2pMqOB4i3kxs3S', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'pending', NULL, 0, 0, 0, 0, NULL, NULL, 0, NULL, 0, '2022-02-18 15:57:37.069866', NULL, NULL, NULL),
(1142, 'Tech', NULL, 'ABC', 'teacher', 3, '+923343333333', 'tech134@sharklasers.com', NULL, NULL, NULL, NULL, '$2y$10$stAtRUT74w4cXwWx4WXSZumpXOPgZwTlgcxgPO5LV3sTvw0m58itW', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'pending', NULL, 0, 0, 0, 0, NULL, NULL, 0, NULL, 0, '2022-02-18 16:56:53.555387', NULL, NULL, NULL),
(1143, 'Tech', NULL, 'ABC', 'teacher', 3, '+923343333333', 'tech135@sharklasers.com', NULL, NULL, NULL, NULL, '$2y$10$9NBNfXhMiNHms41688W0Ku/U8QG/hVRrVxlrHXZgCKcOgX308sZHq', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'pending', NULL, 0, 0, 0, 0, NULL, NULL, 0, NULL, 0, '2022-02-18 17:01:17.550367', NULL, NULL, NULL),
(1144, 'Tech', NULL, 'ABC', 'teacher', 3, '+923343333333', 'tech136@sharklasers.com', NULL, NULL, NULL, NULL, '$2y$10$U4PlLOgV/k36D7wzKjETwO9R0l6gklzJGchEezZjOuTZ.X2X/tGjG', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'pending', NULL, 0, 0, 0, 0, NULL, NULL, 0, NULL, 0, '2022-02-18 17:05:04.939533', NULL, NULL, NULL),
(1145, 'Ahmed', NULL, 'Hassan', 'teacher', 3, '+20111236 5233', 'ahmedhassan.fci@gmail.com', NULL, NULL, NULL, NULL, '$2y$10$rpUNBawtZ/ai93XDQygeoekeTHSCgxADueMoiSsRkUpZ/tBM1fHhm', NULL, NULL, 'GcBYJJGdJzk3ZEWG6ArhWcaVWJAaSm2igdIFLFWBmOgdFHh9S54tmBXCeaFa', 0, 0, '571646399682.jpg', '301646399682.jpeg', NULL, NULL, NULL, NULL, NULL, NULL, 'pending', NULL, 4, 0, 0, 0, NULL, NULL, 0, NULL, 0, '2022-03-04 13:19:43.132080', NULL, NULL, NULL),
(1146, 'Teacher', 'John', 'XYZ', 'teacher', 3, '+923343333333', 'teacher141@sharklasers.com', 'male', 'Afghanistan', '05/02/2022', 'Hello', '$2y$10$YydbmE2iXhXI/o73M3fKuemX4lgHC4aE4pWXVpupIVwnqu/WV1ucS', NULL, NULL, '0XFAGxgzSD2QhK3R8U74B9SttMmzLAnaBIRTvBOID9P0etG36byPva4OoAPX', 0, 0, '971645442890.jpg', '651645442890.png', NULL, NULL, 'Andkhoy', 'Afghanistan', '75650', 'Karachi', 'pending', NULL, 0, 0, 0, 0, NULL, NULL, 0, NULL, 0, '2022-02-21 11:28:10.443559', NULL, NULL, NULL),
(1147, 'teacher', NULL, 'shami', 'teacher', 3, '+92000000000', 'mabdulrehman14713+20@gmail.com', NULL, NULL, NULL, NULL, '$2y$10$ug1FedimjXUCoS4IxlSNpuMX73NB0pX1CpgDFnVrRF6PlFM4JQNzW', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'pending', NULL, 0, 0, 0, 0, NULL, NULL, 0, NULL, 0, '2022-02-21 15:08:13.302773', NULL, NULL, NULL),
(1149, 'teacher', NULL, 'shami', 'teacher', 3, '+92000000000', 'mabdulrehman2472@gmail.com', NULL, NULL, NULL, NULL, '$2y$10$XPkTj8WXr71AF5kb6lvNv.L20HqEFfQZBRjRIdCeCIYIkMvtU0CdS', NULL, NULL, 'pt6F16tr0ZAyCNrFC9VOGkmSESR3ZjuaBdhvcxl6isp3tH8yjRqEfn43Udzj', 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'pending', NULL, 0, 0, 0, 0, NULL, NULL, 0, NULL, 20, '2022-03-01 09:54:36.763443', NULL, NULL, NULL),
(1150, 'Ahmed', NULL, 'Hassan', 'teacher', 3, '+20111236 5233', 'engahmedviber@gmail.com', NULL, NULL, NULL, NULL, '$2y$10$CmkaArEm4k5Cu1i.GE/DjOpCpUTyF.Xu/5t3u7UJWnB8wJWg8P.Qi', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'pending', NULL, 0, 0, 0, 0, NULL, NULL, 0, NULL, 20, '2022-03-01 09:59:10.279110', NULL, NULL, NULL),
(1151, 'Ahmed', 'Mohamed', 'Hassan', 'teacher', 3, '+20111236 5233', 'theviber2014@gmail.com', 'male', 'Egypt', '12/02/2022', 'This is my bio', '$2y$10$06Bmp3qvelNtq/dd1JveTu3LGQlIIHw1wSC5HONHbNTStkoADEH5y', NULL, NULL, 'RRAPMUOXZs1YYijoOW84e85geuxaNqr8iK5AxPper6gMXdgFjfj9ItEJu3j8', 0, 0, '331645906029.png', '151645906029.png', NULL, NULL, 'Cairo', 'Egypt', '12345', 'Egypt', 'pending', NULL, 2, 0, 0, 0, NULL, NULL, 0, NULL, 0, '2022-02-26 20:07:09.047731', NULL, NULL, NULL),
(1152, 'Mr.', NULL, 'Shami', 'student', 1, '+923361146045', 'mrshami538@gmail.com', NULL, NULL, NULL, NULL, '$2y$10$nGW42HBusW3hjUm/e0jW/eOVFxGI/CrbfzYT/JaatCJxdLNplRrEm', NULL, NULL, 'CX1nV4XXkOe3VsiIxEAJis6s406pp1ayHOfrkaJMOnodvnEBcH6pGIREO0ju', 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'pending', NULL, 0, 0, 0, 0, NULL, NULL, 0, NULL, 0, '2022-02-28 09:33:31.410304', NULL, NULL, NULL),
(1153, 'Muhammad Abdul', 'dfgdfg', 'Rehman', 'teacher', 3, '+923361146045', 'certijob@gmail.com', 'male', 'Albania', '01/03/2022', 'it is my bio', '$2y$10$LH8yIjAX1jx5nryATv9wKe3wyB5g7WlzUBRL8sjDZeqTYwRR/wJJC', NULL, NULL, 'B1gyP00eTB0XNTtgoWjdP05gMVZAdGBLBTu7SUA6ug2EBXud6rklXSQy6D1u', 0, 0, '271646299960.png', '131646299960.png', NULL, NULL, 'Caluquembe', 'Angola', '54770', '98-d naz town near valencia town lahore', 'pending', NULL, 4, 0, 0, 0, NULL, NULL, 0, NULL, NULL, '2022-03-03 15:38:33.834245', NULL, NULL, NULL),
(1154, 'Muhammad Abdul', NULL, 'Rehman', 'teacher', 3, '+923361146045', 'certijobi@gmail.com', NULL, NULL, NULL, NULL, '$2y$10$tFJHBuauiRCs2zLKrf4lzOL8D0OmFitAyv5e46dxEwpc3yJD2jBFe', NULL, NULL, 'z1KZ60EERIg4nwRVuZAzzgGDBdIsRTfJnHKI81Bm8Xy9L0C1iUG77a773HRe', 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'pending', NULL, 0, 0, 0, 0, NULL, NULL, 0, NULL, NULL, '2022-03-03 10:26:33.358451', NULL, NULL, NULL),
(1155, 'ASD', NULL, 'SDFS', 'student', 1, '+923001234567', 'eld02398@uooos.com', NULL, NULL, NULL, NULL, '$2y$10$qDrtaz/oNTLnPoKItSf2heHgDQKLmSLyczhoJ/C3wARtR7fgwFLYi', NULL, NULL, 'iOxjOZJLEwlj5TsP9SzZK0tjpknehV2cxLXxRooXuYgShPleUZ77ye2YFlxs', 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'pending', NULL, 0, 0, 0, 0, NULL, NULL, 0, NULL, NULL, '2022-03-04 06:35:22.035431', NULL, NULL, NULL),
(1156, 'Muhammad Abdul', NULL, 'Rehman', 'teacher', 3, '+923361146045', 'dev.zaptatechnologies@gmail.com', NULL, NULL, NULL, NULL, '$2y$10$ySHxBUMnBS2VUoUE.h.6se.bK3h40TeTdOZlYKuf6tjA3E6B6D57S', NULL, NULL, 'fvK8RKsvZZ5Ug3BJAtNXXALIwFZWZoXbeZMeMlfz4dLAWYf8AHwmd3RPHYPc', 0, 0, '671646398327.png', '791646398327.png', NULL, NULL, NULL, NULL, NULL, NULL, 'pending', NULL, 4, 0, 0, 0, NULL, NULL, 0, NULL, NULL, '2022-03-04 12:53:23.680973', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users_metas`
--

CREATE TABLE `users_metas` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `users_metas`
--

INSERT INTO `users_metas` (`id`, `user_id`, `name`, `value`) VALUES
(21, 1016, 'education', 'BS in Mechanical Engineering from Santa Clara University'),
(22, 1016, 'education', 'MS in Mechanical Engineering from Santa Clara University'),
(23, 1016, 'experience', 'professional instructor and trainer for Data Science and programming'),
(24, 1016, 'experience', 'Head of Data Science for Pierian Data Inc'),
(25, 1015, 'experience', 'supporting proprietary Unix operating systems including AIX, HP-UX, and Solaris.'),
(26, 1015, 'experience', '10 years of experience working with Linux systems'),
(27, 1015, 'education', 'Red Hat Certified Engineer (RHCE)'),
(28, 1015, 'education', 'AWS Certified DevOps Engineer - Professional'),
(29, 1015, 'education', 'Linux Foundation Certified System Administrator'),
(30, 929, 'experience', 'Director at Cisco Systems 2015 - 2021'),
(31, 929, 'experience', 'research assistant at Harvard University 2010 - 2019'),
(32, 929, 'experience', 'Amazon bestselling author'),
(33, 923, 'experience', 'marketing strategies at Microlab 2010-2015'),
(34, 923, 'education', 'Associate of Business Administration from Imperial College London'),
(35, 923, 'education', 'Bachelor of International Business Economics from University of Cambridge'),
(36, 923, 'education', 'Master of Business Administration from King\'s College London'),
(37, 3, 'experience', 'Five-time TED speaker'),
(38, 3, 'education', 'Associate of Applied Business from Stanford University'),
(39, 3, 'education', 'Bachelor of Science in Business from Harvard University'),
(40, 3, 'education', 'Master of Computational Finance from University of Chicago'),
(41, 870, 'education', 'Associate in Physical Therapy from University of British Columbia'),
(42, 870, 'education', 'Bachelor of Arts in Psychology from Duke University'),
(43, 870, 'education', 'Master of Public Health from Cornell University'),
(44, 929, 'education', 'Associate of Applied Business from University of Leeds'),
(45, 929, 'education', 'Bachelor of Management and Organizational Studies from University of Sheffield'),
(46, 929, 'education', 'Master of Management from Durham University'),
(47, 934, 'education', 'Bachelor of Science in Information Technology from University of Glasgow'),
(48, 934, 'education', 'Master of Science in Information Systems (MSIS) from Delft University of Technology'),
(49, 934, 'experience', 'Web Developer at Uber 2015 - 2018'),
(50, 1015, 'education', 'Master of Science in Information Systems (MSIS) from University of Sydney'),
(51, 1115, 'documents', '1643818342998DSC02903.JPG'),
(52, 1115, 'documents', '1643818342442DSC02905.JPG'),
(53, 1115, 'documents', '1643818342369DSC02906.JPG'),
(54, 1115, 'documents', '1643818342752DSC02907.JPG'),
(55, 1092, 'documents', '1643818645269DSC02903.JPG'),
(56, 1092, 'documents', '1643818645343DSC02905.JPG'),
(57, 1092, 'documents', '1643818645646DSC02906.JPG'),
(58, 1092, 'documents', '1643818645308DSC02907.JPG'),
(60, 1115, 'documents', '164391238412PDF.pdf'),
(61, 1115, 'documents', '1643912384470PNG.png'),
(62, 1117, 'documents', '1644246267392DSC02899.JPG'),
(63, 1117, 'documents', '1644251822563SampleJPGImage_1mbmb.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `user_codes`
--

CREATE TABLE `user_codes` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_codes`
--

INSERT INTO `user_codes` (`id`, `user_id`, `code`, `created_at`, `updated_at`) VALUES
(1, 1, '1325', '2022-01-19 20:11:15', '2022-03-05 18:39:40'),
(2, 1092, '6138', '2022-02-07 04:14:21', '2022-02-07 04:28:30'),
(3, 995, '3045', '2022-02-07 04:21:58', '2022-02-07 04:21:58'),
(4, 1117, '4952', '2022-02-07 04:49:48', '2022-02-07 04:49:48');

-- --------------------------------------------------------

--
-- Table structure for table `user_feedback`
--

CREATE TABLE `user_feedback` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `sender_id` int(11) NOT NULL,
  `reciever_id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `feedback_id` int(11) NOT NULL,
  `review` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `rating` int(11) NOT NULL,
  `kudos_points` double(8,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_feedback`
--

INSERT INTO `user_feedback` (`id`, `sender_id`, `reciever_id`, `course_id`, `feedback_id`, `review`, `rating`, `kudos_points`, `created_at`, `updated_at`) VALUES
(5, 1135, 1126, 14, 1, 'Nice teacher', 5, 100.00, '2022-03-02 02:17:16', '2022-03-02 02:17:16'),
(6, 1135, 1126, 14, 2, 'Nice teacher', 3, 60.00, '2022-03-02 02:17:16', '2022-03-02 02:17:16'),
(7, 1135, 1149, 14, 3, 'Nice teacher', 4, 80.00, '2022-03-02 02:17:16', '2022-03-02 02:17:16'),
(8, 1135, 1149, 14, 4, 'Nice teacher', 5, 100.00, '2022-03-02 02:17:17', '2022-03-02 02:17:17');

-- --------------------------------------------------------

--
-- Table structure for table `verifications`
--

CREATE TABLE `verifications` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED DEFAULT NULL,
  `mobile` char(16) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` char(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `code` char(6) COLLATE utf8mb4_unicode_ci NOT NULL,
  `verified_at` int(10) UNSIGNED DEFAULT NULL,
  `expired_at` int(10) UNSIGNED DEFAULT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `verifications`
--

INSERT INTO `verifications` (`id`, `user_id`, `mobile`, `email`, `code`, `verified_at`, `expired_at`, `created_at`) VALUES
(57, 996, NULL, 'test@gmail.com', '52692', NULL, 1626247575, 1626243975),
(58, NULL, NULL, 'student@demo.com', '50513', NULL, 1643040100, 1643036500),
(63, 1022, NULL, 'manibaba@gmail.com', '16129', NULL, 1642160662, 1642157062),
(64, 1023, '+92', NULL, '76184', NULL, 1642173539, 1642169939),
(65, 1025, '+82', NULL, '95978', NULL, 1642173574, 1642169974),
(66, 1026, '+8200000000', NULL, '42237', NULL, 1642173621, 1642170021),
(67, 1027, '+92 336 1146045', NULL, '63273', NULL, 1642174662, 1642171062),
(68, 1033, NULL, 'lkjh@gmail.com', '21226', NULL, 1642175055, 1642171455),
(71, 1128, NULL, 'mabdulrehman2472@gmail.com', '55189', NULL, 1645096785, 1645093185),
(72, 1159, NULL, 'mabdulrehman14713@gmail.com', '45484', NULL, 1645625270, 1645621670),
(73, 1127, NULL, 'teacher123@sharklasers.com', '71008', 1644423171, 1644423221, 1644422964),
(74, 1064, NULL, 'teacher456@sharklasers.com', '13355', NULL, 1642856622, 1642853022),
(75, 1065, NULL, 'student123@sharklasers.com', '72500', NULL, 1642857183, 1642853583),
(76, 1066, NULL, 'student456@sharklasers.com', '99022', NULL, 1642863944, 1642860344),
(77, 1067, NULL, 'teacher789@sharklasers.com', '20001', NULL, 1642868509, 1642864909),
(78, 1068, NULL, 'student789@sharklasers.com', '93413', NULL, 1642868758, 1642865158),
(79, 1069, NULL, 'studentABC@sharklasers.com', '36143', 1642865592, 1642865642, 1642865427),
(80, 1070, NULL, 'teacherABC@sharklasers.com', '40636', 1642865929, 1642865979, 1642865764),
(81, 1071, NULL, 'studentXYZ@sharklasers.com', '81074', 1642866116, 1642866166, 1642866072),
(82, 1072, NULL, 'teacherXYZ@sharklasers.com', '64268', 1642866239, 1642866289, 1642866169),
(83, 1073, NULL, 'sara123@sharklasers.com', '23786', NULL, 1642875579, 1642871979),
(84, 1074, NULL, 'sara456@sharklasers.com', '48589', NULL, 1642875707, 1642872107),
(85, 1075, NULL, 'sobia123@sharklasers.com', '37761', NULL, 1642875808, 1642872208),
(86, 1076, NULL, 'tooba123@sharklasers.com', '47975', NULL, 1642875902, 1642872302),
(87, 1077, NULL, 'teststudent@sharklasers.com', '49724', NULL, 1642958694, 1642955094),
(88, 1078, NULL, 'stest123@sharklasers.com', '63266', 1642955449, 1642955499, 1642955398),
(89, 1079, NULL, 'ttest123@sharklasers.com', '21758', 1642955562, 1642955612, 1642955511),
(90, 1080, NULL, 'stest456@sharklasers.com', '60224', 1642956164, 1642956214, 1642956088),
(91, 1081, NULL, 'ttest456@sharklasers.com', '12114', 1642956298, 1642956348, 1642956217),
(92, 1082, NULL, 'ateeq@zaptatech.com', '82239', 1643005735, 1643005785, 1643005363),
(93, 1083, NULL, 'ateeqasif1168@gmail.com', '64329', 1643005871, 1643005921, 1643005844),
(94, 1084, NULL, 'dry50188@qopow.com', '60787', 1643032780, 1643032830, 1643032645),
(95, 1085, NULL, 'student123@demo.com', '42076', NULL, 1643041716, 1643038116),
(96, 1086, NULL, 'teacger@demo.com', '68626', NULL, 1643041759, 1643038159),
(97, 1087, NULL, 'abc123@sharklasers.com', '48294', NULL, 1643044298, 1643040698),
(98, 1088, NULL, 'stest345@sharklasers.com', '42806', NULL, 1643044381, 1643040781),
(99, 1089, NULL, 'stest234@sharklasers.com', '59471', NULL, 1643044448, 1643040848),
(100, 1090, NULL, 'stest788@sharklasers.com', '69744', NULL, 1643044544, 1643040944),
(101, 1091, NULL, 'stest567@sharklasers.com', '61296', 1643041346, 1643041396, 1643041086),
(102, 1094, NULL, 'mabdulrehman14713+20@gmail.com', '47451', NULL, 1643272050, 1643268450),
(103, 1123, NULL, 'zoobiaejaz2017@gmail.com', '18604', 1644254641, 1644254691, 1644254603),
(104, 1096, NULL, 'zoobiaejaz20@gmail.com', '34261', NULL, 1643620215, 1643616615),
(105, 1097, NULL, 'metuto1rsmail@gmail.com', '68775', NULL, 1643620324, 1643616724),
(106, 1098, NULL, 'hello@sharklasers.com', '64740', NULL, 1643620851, 1643617251),
(107, 1099, NULL, 'hell0@sharklasers.com', '12805', NULL, 1643621097, 1643617497),
(108, 1100, NULL, 'hell1@sharklasers.com', '50034', 1643617893, 1643617943, 1643617798),
(109, 1101, NULL, 'hello1@gmail.com', '96291', NULL, 1643621556, 1643617956),
(110, 1102, NULL, 'hello2@gmail.com', '60642', NULL, 1643621670, 1643618070),
(111, 1125, NULL, 'zoobia123@sharklasers.com', '89730', 1644421460, 1644421510, 1644420838),
(112, 1104, NULL, 'zoobia234@sharklasers.com', '63738', NULL, 1643651414, 1643647814),
(113, 1105, NULL, 'zoobia124@sharklasers.com', '13989', 1643648143, 1643648193, 1643648076),
(114, 1106, NULL, 'zoobia125@sharklasers.com', '74553', 1643648568, 1643648618, 1643648434),
(115, 1107, NULL, 'zoobia126@sharklasers.com', '54975', 1643648711, 1643648761, 1643648672),
(116, 1108, NULL, 'zoobia890@sharklasers.com', '20824', 1643649555, 1643649605, 1643649504),
(117, 1109, NULL, 'mabdulrehman1471@gmail.com', '61490', NULL, 1643656068, 1643652468),
(118, 1110, NULL, 'mabdulrehman147@gmail.com', '18166', NULL, 1643731717, 1643728117),
(119, 1112, NULL, 'zoobia980@sharklasers.com', '20657', NULL, 1643736139, 1643732539),
(120, 1113, NULL, 'zoobiaejaz2017+60@gmail.com', '62346', 1643732728, 1643732778, 1643732699),
(121, 1115, NULL, 'mabdulrehman14713+1@gmail.com', '90002', 1643818301, 1643818351, 1643818271),
(122, 1116, NULL, 'mabdulrehman14713+90@gmail.com', '65590', 1643820561, 1643820611, 1643820534),
(123, 1118, NULL, 'zooStudent123@sharklasers.com', '85490', 1644248466, 1644248516, 1644248416),
(124, 1119, NULL, 'zooTeacher123@sharklasers.com', '16625', 1644248628, 1644248678, 1644248544),
(125, 1122, NULL, 'dev.metutors@gmail.com', '81817', 1644254492, 1644254542, 1644253850),
(126, 1126, NULL, 'zoobia456@gmail.com', '79380', NULL, 1644426097, 1644422497),
(127, 1158, NULL, 'ahtshamulhassan538@gmail.com', '62483', NULL, 1645103802, 1645100202),
(128, 1157, NULL, 'mrshami538@gmail.com', '11786', NULL, 1645103766, 1645100166),
(129, 1153, NULL, 'certijob@gmail.com', '92473', 1646299144, 1646299194, 1646298890),
(130, 1154, NULL, 'certijob+10@gmail.com', '44058', 1646303086, 1646303136, 1646303061),
(131, 1155, NULL, 'eld02398@uooos.com', '45016', 1646375703, 1646375753, 1646375673),
(132, 1156, NULL, 'dev.zaptatechnologies@gmail.com', '18766', 1646398107, 1646398157, 1646397955);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `academic_classes`
--
ALTER TABLE `academic_classes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `blog`
--
ALTER TABLE `blog`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `blog_category_id_foreign` (`category_id`) USING BTREE;

--
-- Indexes for table `blog_categories`
--
ALTER TABLE `blog_categories`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `parent_id` (`parent_id`) USING BTREE;

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `contact_us`
--
ALTER TABLE `contact_us`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `countries`
--
ALTER TABLE `countries`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `course_levels`
--
ALTER TABLE `course_levels`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `faqs`
--
ALTER TABLE `faqs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `faq_topics`
--
ALTER TABLE `faq_topics`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `field_of_studies`
--
ALTER TABLE `field_of_studies`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `languages`
--
ALTER TABLE `languages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `level_of_education`
--
ALTER TABLE `level_of_education`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `meetings`
--
ALTER TABLE `meetings`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `meetings_creator_id_foreign` (`creator_id`) USING BTREE;

--
-- Indexes for table `meeting_times`
--
ALTER TABLE `meeting_times`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `meeting_times_meeting_id_foreign` (`meeting_id`) USING BTREE;

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indexes for table `newsletters`
--
ALTER TABLE `newsletters`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notification_templates`
--
ALTER TABLE `notification_templates`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`(191)) USING BTREE;

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `programs`
--
ALTER TABLE `programs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `spoken_languages`
--
ALTER TABLE `spoken_languages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `subjects`
--
ALTER TABLE `subjects`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `teacher_availabilities`
--
ALTER TABLE `teacher_availabilities`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `teacher_documents`
--
ALTER TABLE `teacher_documents`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `teacher_interview_requests`
--
ALTER TABLE `teacher_interview_requests`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `teacher_programs`
--
ALTER TABLE `teacher_programs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `teacher_subjects`
--
ALTER TABLE `teacher_subjects`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `teaching_qualifications`
--
ALTER TABLE `teaching_qualifications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `teaching_specifications`
--
ALTER TABLE `teaching_specifications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tickets`
--
ALTER TABLE `tickets`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ticket_categories`
--
ALTER TABLE `ticket_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users_metas`
--
ALTER TABLE `users_metas`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_codes`
--
ALTER TABLE `user_codes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_feedback`
--
ALTER TABLE `user_feedback`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `verifications`
--
ALTER TABLE `verifications`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `academic_classes`
--
ALTER TABLE `academic_classes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `blog`
--
ALTER TABLE `blog`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `blog_categories`
--
ALTER TABLE `blog_categories`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=612;

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `contact_us`
--
ALTER TABLE `contact_us`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `countries`
--
ALTER TABLE `countries`
  MODIFY `id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=251;

--
-- AUTO_INCREMENT for table `courses`
--
ALTER TABLE `courses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `course_levels`
--
ALTER TABLE `course_levels`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `faqs`
--
ALTER TABLE `faqs`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `faq_topics`
--
ALTER TABLE `faq_topics`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `feedback`
--
ALTER TABLE `feedback`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `field_of_studies`
--
ALTER TABLE `field_of_studies`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=104;

--
-- AUTO_INCREMENT for table `languages`
--
ALTER TABLE `languages`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=136;

--
-- AUTO_INCREMENT for table `level_of_education`
--
ALTER TABLE `level_of_education`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `meetings`
--
ALTER TABLE `meetings`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `meeting_times`
--
ALTER TABLE `meeting_times`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=166;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=235;

--
-- AUTO_INCREMENT for table `newsletters`
--
ALTER TABLE `newsletters`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `notification_templates`
--
ALTER TABLE `notification_templates`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5602;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=538;

--
-- AUTO_INCREMENT for table `programs`
--
ALTER TABLE `programs`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `spoken_languages`
--
ALTER TABLE `spoken_languages`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `subjects`
--
ALTER TABLE `subjects`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- AUTO_INCREMENT for table `teacher_availabilities`
--
ALTER TABLE `teacher_availabilities`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `teacher_documents`
--
ALTER TABLE `teacher_documents`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `teacher_interview_requests`
--
ALTER TABLE `teacher_interview_requests`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `teacher_programs`
--
ALTER TABLE `teacher_programs`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `teacher_subjects`
--
ALTER TABLE `teacher_subjects`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `teaching_qualifications`
--
ALTER TABLE `teaching_qualifications`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `teaching_specifications`
--
ALTER TABLE `teaching_specifications`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `tickets`
--
ALTER TABLE `tickets`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `ticket_categories`
--
ALTER TABLE `ticket_categories`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1157;

--
-- AUTO_INCREMENT for table `users_metas`
--
ALTER TABLE `users_metas`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;

--
-- AUTO_INCREMENT for table `user_codes`
--
ALTER TABLE `user_codes`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `user_feedback`
--
ALTER TABLE `user_feedback`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `verifications`
--
ALTER TABLE `verifications`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=133;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
