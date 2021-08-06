-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jul 01, 2021 at 01:14 PM
-- Server version: 10.1.38-MariaDB
-- PHP Version: 7.1.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ezana_lms`
--

-- --------------------------------------------------------

--
-- Table structure for table `ezanaLMS_AcademicSettings`
--

CREATE TABLE `ezanaLMS_AcademicSettings` (
  `id` int(20) NOT NULL,
  `current_academic_year` varchar(200) NOT NULL,
  `current_semester` varchar(200) NOT NULL,
  `status` varchar(200) NOT NULL,
  `start_date` varchar(200) NOT NULL,
  `end_date` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ezanaLMS_AcademicSettings`
--

INSERT INTO `ezanaLMS_AcademicSettings` (`id`, `current_academic_year`, `current_semester`, `status`, `start_date`, `end_date`) VALUES
(1, 'Sep 2020 - Sep 2021 ', 'Jan - Apr ', '', '2021-01-04', '2021-04-17'),
(2, 'Sep 2020 - Sep 2021', 'May - Aug', 'Current', '2021-05-03', '2021-08-13'),
(3, 'Sep 2020 - Sep 2021', 'Sep - Dec', '', '2021-09-13', '2021-12-10');

-- --------------------------------------------------------

--
-- Table structure for table `ezanaLMS_Admins`
--

CREATE TABLE `ezanaLMS_Admins` (
  `id` varchar(200) NOT NULL,
  `name` varchar(200) NOT NULL,
  `email` varchar(200) NOT NULL,
  `personal_email` varchar(200) NOT NULL,
  `password` varchar(200) NOT NULL,
  `rank` varchar(200) NOT NULL,
  `created_at` timestamp(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6) ON UPDATE CURRENT_TIMESTAMP(6),
  `phone` varchar(200) NOT NULL,
  `gender` varchar(200) NOT NULL,
  `employee_id` varchar(200) NOT NULL,
  `national_id` varchar(200) NOT NULL,
  `date_employed` varchar(200) NOT NULL,
  `school` varchar(200) NOT NULL,
  `school_id` varchar(200) NOT NULL,
  `adr` varchar(200) NOT NULL,
  `previledge` varchar(200) NOT NULL,
  `profile_pic` varchar(200) NOT NULL,
  `status` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ezanaLMS_Admins`
--

INSERT INTO `ezanaLMS_Admins` (`id`, `name`, `email`, `personal_email`, `password`, `rank`, `created_at`, `phone`, `gender`, `employee_id`, `national_id`, `date_employed`, `school`, `school_id`, `adr`, `previledge`, `profile_pic`, `status`) VALUES
('a69681bcf334ae130217fea4505fd3c994f5683f', 'Ezana LMS Sys Admin', 'sysadmin@ezana.org', 'sysadmin@ezana.org', 'adcd7048512e64b48da55b027577886ee5a36350', 'System Administrator', '2021-06-29 10:40:05.432551', '+90127690-90', 'Male', '90-126', 'sysadmin@ezana.org', '2021-04-07', 'School Of Computing Sciences', 'ba52fc866349d5af05addecba35600d0fd970ef7ba', '129 - 90 127 Localhost', 'Edit And Delete', '1624963205logo.png', '');

-- --------------------------------------------------------

--
-- Table structure for table `ezanaLMS_AssignmentsAttempts`
--

CREATE TABLE `ezanaLMS_AssignmentsAttempts` (
  `id` varchar(200) NOT NULL,
  `assignment_id` varchar(200) NOT NULL,
  `module_name` varchar(200) NOT NULL,
  `module_code` varchar(200) NOT NULL,
  `std_name` varchar(200) NOT NULL,
  `std_regno` varchar(200) NOT NULL,
  `attachments` longtext NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `ezanaLMS_BugReports`
--

CREATE TABLE `ezanaLMS_BugReports` (
  `id` varchar(200) NOT NULL,
  `name` varchar(200) NOT NULL,
  `email` varchar(200) NOT NULL,
  `number` varchar(200) NOT NULL,
  `bug_title` longtext NOT NULL,
  `bug_details` longblob NOT NULL,
  `severity` longtext NOT NULL,
  `date_reported` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `status` longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `ezanaLMS_Calendar`
--

CREATE TABLE `ezanaLMS_Calendar` (
  `id` varchar(200) NOT NULL,
  `faculty_id` varchar(200) NOT NULL,
  `academic_yr` varchar(200) NOT NULL,
  `semester_name` varchar(200) NOT NULL,
  `semester_start` varchar(200) NOT NULL,
  `semester_end` varchar(200) NOT NULL,
  `details` blob NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `ezanaLMS_ClassRecordings`
--

CREATE TABLE `ezanaLMS_ClassRecordings` (
  `id` varchar(200) NOT NULL,
  `faculty_id` varchar(200) NOT NULL,
  `clip_type` varchar(200) NOT NULL,
  `module_id` varchar(200) NOT NULL,
  `class_name` varchar(200) NOT NULL,
  `lecturer_name` varchar(200) NOT NULL,
  `external_link` varchar(200) NOT NULL,
  `video` longtext NOT NULL,
  `details` longblob NOT NULL,
  `created_at` varchar(200) NOT NULL,
  `updated_at` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `ezanaLMS_CourseDirectories`
--

CREATE TABLE `ezanaLMS_CourseDirectories` (
  `id` varchar(200) NOT NULL,
  `course_code` varchar(200) NOT NULL,
  `course_name` varchar(200) NOT NULL,
  `course_materials` varchar(200) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `ezanaLMS_CourseMemo`
--

CREATE TABLE `ezanaLMS_CourseMemo` (
  `id` varchar(200) NOT NULL,
  `course_id` varchar(200) NOT NULL,
  `course_name` varchar(200) NOT NULL,
  `course_memo` longblob NOT NULL,
  `attachments` longtext NOT NULL,
  `created_by` varchar(200) NOT NULL,
  `created_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `faculty_id` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `ezanaLMS_Courses`
--

CREATE TABLE `ezanaLMS_Courses` (
  `id` varchar(200) NOT NULL,
  `faculty_id` varchar(200) NOT NULL,
  `faculty_name` varchar(200) NOT NULL,
  `department_id` varchar(200) NOT NULL,
  `department_name` varchar(200) NOT NULL,
  `code` varchar(200) NOT NULL,
  `name` varchar(200) NOT NULL,
  `hod` varchar(200) NOT NULL,
  `email` varchar(200) NOT NULL,
  `details` longblob NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `ezanaLMS_DepartmentalMemos`
--

CREATE TABLE `ezanaLMS_DepartmentalMemos` (
  `id` varchar(200) NOT NULL,
  `department_id` varchar(200) NOT NULL,
  `department_name` varchar(200) NOT NULL,
  `memo_title` longtext NOT NULL,
  `departmental_memo` longblob NOT NULL,
  `target_audience` varchar(200) NOT NULL,
  `attachments` longtext NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_by` varchar(200) NOT NULL,
  `updated_at` varchar(200) NOT NULL,
  `type` varchar(200) NOT NULL,
  `faculty_id` varchar(200) NOT NULL,
  `update_status` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `ezanaLMS_Departments`
--

CREATE TABLE `ezanaLMS_Departments` (
  `id` varchar(200) NOT NULL,
  `code` varchar(200) NOT NULL,
  `name` varchar(200) NOT NULL,
  `faculty_id` varchar(200) NOT NULL,
  `faculty_name` varchar(200) NOT NULL,
  `hod` varchar(200) NOT NULL,
  `email` varchar(200) NOT NULL,
  `details` longblob NOT NULL,
  `created_at` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `ezanaLMS_Enrollments`
--

CREATE TABLE `ezanaLMS_Enrollments` (
  `id` varchar(200) NOT NULL,
  `faculty_id` varchar(200) NOT NULL,
  `course_id` varchar(200) NOT NULL,
  `code` varchar(200) NOT NULL,
  `student_adm` varchar(200) NOT NULL,
  `student_name` varchar(200) NOT NULL,
  `semester_enrolled` varchar(200) NOT NULL,
  `course_code` varchar(200) NOT NULL,
  `course_name` varchar(200) NOT NULL,
  `semester_start` varchar(200) NOT NULL,
  `semester_end` varchar(200) NOT NULL,
  `academic_year_enrolled` varchar(200) NOT NULL,
  `module_name` varchar(200) NOT NULL,
  `module_code` varchar(200) NOT NULL,
  `created_at` varchar(200) NOT NULL,
  `updated_at` varchar(200) NOT NULL,
  `stage` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `ezanaLMS_ExamQuestions`
--

CREATE TABLE `ezanaLMS_ExamQuestions` (
  `id` varchar(200) NOT NULL,
  `module_code` varchar(200) NOT NULL,
  `module_name` varchar(200) NOT NULL,
  `exam_time` varchar(200) NOT NULL,
  `instructions` longtext NOT NULL,
  `attachment` varchar(200) NOT NULL,
  `created_at` varchar(200) NOT NULL,
  `type` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `ezanaLMS_Faculties`
--

CREATE TABLE `ezanaLMS_Faculties` (
  `id` varchar(200) NOT NULL,
  `code` varchar(200) NOT NULL,
  `name` varchar(200) NOT NULL,
  `head` varchar(200) NOT NULL,
  `email` varchar(200) NOT NULL,
  `details` longblob NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `ezanaLMS_Groups`
--

CREATE TABLE `ezanaLMS_Groups` (
  `id` varchar(200) NOT NULL,
  `module_id` varchar(200) NOT NULL,
  `faculty_id` varchar(200) NOT NULL,
  `code` varchar(200) NOT NULL,
  `name` varchar(200) NOT NULL,
  `details` longblob NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `ezanaLMS_GroupsAnnouncements`
--

CREATE TABLE `ezanaLMS_GroupsAnnouncements` (
  `id` varchar(200) NOT NULL,
  `faculty_id` varchar(200) NOT NULL,
  `group_code` varchar(200) NOT NULL,
  `group_name` varchar(200) NOT NULL,
  `announcement` longblob NOT NULL,
  `created_by` varchar(200) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `ezanaLMS_GroupsAssignments`
--

CREATE TABLE `ezanaLMS_GroupsAssignments` (
  `id` varchar(200) NOT NULL,
  `faculty_id` varchar(200) NOT NULL,
  `module_id` varchar(200) NOT NULL,
  `group_code` varchar(200) NOT NULL,
  `group_name` varchar(200) NOT NULL,
  `type` varchar(200) NOT NULL,
  `details` longblob NOT NULL,
  `attachments` longtext NOT NULL,
  `submitted_on` varchar(200) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `ezanaLMS_GroupsAssignmentsGrades`
--

CREATE TABLE `ezanaLMS_GroupsAssignmentsGrades` (
  `id` varchar(200) NOT NULL,
  `faculty_id` varchar(200) NOT NULL,
  `group_name` varchar(200) NOT NULL,
  `group_code` varchar(200) NOT NULL,
  `project_id` varchar(200) NOT NULL,
  `Submitted_Files` longtext NOT NULL,
  `group_score` varchar(200) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `ezanaLMS_Lecturers`
--

CREATE TABLE `ezanaLMS_Lecturers` (
  `id` varchar(200) NOT NULL,
  `number` varchar(200) NOT NULL,
  `name` varchar(200) NOT NULL,
  `idno` varchar(200) NOT NULL,
  `phone` varchar(200) NOT NULL,
  `email` varchar(200) NOT NULL,
  `adr` longtext NOT NULL,
  `password` varchar(200) NOT NULL,
  `profile_pic` varchar(200) NOT NULL,
  `created_at` varchar(200) NOT NULL,
  `faculty_id` varchar(200) NOT NULL,
  `faculty_name` varchar(200) NOT NULL,
  `work_email` varchar(200) NOT NULL,
  `gender` varchar(200) NOT NULL,
  `employee_id` varchar(200) NOT NULL,
  `date_employed` varchar(200) NOT NULL,
  `status` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `ezanaLMS_ModuleAssignments`
--

CREATE TABLE `ezanaLMS_ModuleAssignments` (
  `id` varchar(200) NOT NULL,
  `module_code` varchar(200) NOT NULL,
  `title` varchar(200) NOT NULL,
  `module_name` varchar(200) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `submission_deadline` varchar(200) NOT NULL,
  `attachments` longtext NOT NULL,
  `faculty` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `ezanaLMS_ModuleAssigns`
--

CREATE TABLE `ezanaLMS_ModuleAssigns` (
  `id` varchar(200) NOT NULL,
  `faculty_id` varchar(200) NOT NULL,
  `course_id` varchar(200) NOT NULL,
  `module_id` varchar(200) NOT NULL,
  `module_code` varchar(200) NOT NULL,
  `module_name` varchar(200) NOT NULL,
  `lec_id` varchar(200) NOT NULL,
  `lec_name` varchar(200) NOT NULL,
  `created_at` varchar(200) NOT NULL,
  `updated_at` varchar(200) NOT NULL,
  `status` varchar(200) NOT NULL,
  `academic_year` varchar(200) NOT NULL,
  `semester` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `ezanaLMS_ModuleMemo`
--

CREATE TABLE `ezanaLMS_ModuleMemo` (
  `id` varchar(200) NOT NULL,
  `module_id` varchar(200) NOT NULL,
  `module_code` varchar(200) NOT NULL,
  `module_memo` longblob NOT NULL,
  `attachments` longtext NOT NULL,
  `created_by` varchar(200) NOT NULL,
  `created_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `faculty_id` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `ezanaLMS_ModuleRecommended`
--

CREATE TABLE `ezanaLMS_ModuleRecommended` (
  `id` varchar(200) NOT NULL,
  `faculty_id` varchar(200) NOT NULL,
  `module_code` varchar(200) NOT NULL,
  `module_name` varchar(200) NOT NULL,
  `readingMaterials` varchar(200) NOT NULL,
  `topic` longtext NOT NULL,
  `external_link` varchar(200) NOT NULL,
  `visibility` varchar(200) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `ezanaLMS_Modules`
--

CREATE TABLE `ezanaLMS_Modules` (
  `id` varchar(200) NOT NULL,
  `code` varchar(200) NOT NULL,
  `course_name` varchar(200) NOT NULL,
  `course_id` varchar(200) NOT NULL,
  `faculty_id` varchar(200) NOT NULL,
  `course_duration` varchar(200) NOT NULL,
  `name` varchar(200) NOT NULL,
  `exam_weight_percentage` varchar(200) NOT NULL,
  `cat_weight_percentage` varchar(200) NOT NULL,
  `lectures_number` varchar(200) NOT NULL,
  `details` longblob NOT NULL,
  `ass_status` tinyint(2) NOT NULL,
  `created_at` varchar(200) NOT NULL,
  `updated_at` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `ezanaLMS_ModulesAnnouncements`
--

CREATE TABLE `ezanaLMS_ModulesAnnouncements` (
  `id` varchar(200) NOT NULL,
  `module_name` varchar(200) NOT NULL,
  `module_code` varchar(200) NOT NULL,
  `announcements` longblob NOT NULL,
  `attachments` longtext NOT NULL,
  `created_by` varchar(200) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `faculty_id` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `ezanaLMS_Notifications`
--

CREATE TABLE `ezanaLMS_Notifications` (
  `id` int(20) NOT NULL,
  `type` varchar(200) NOT NULL,
  `status` varchar(200) NOT NULL,
  `notification_detail` longblob NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `ezanaLMS_PasswordResets`
--

CREATE TABLE `ezanaLMS_PasswordResets` (
  `id` varchar(200) NOT NULL,
  `token` varchar(200) NOT NULL,
  `new_pass` varchar(200) NOT NULL,
  `acc_type` varchar(200) NOT NULL,
  `email` varchar(200) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `status` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `ezanaLMS_PastPapers`
--

CREATE TABLE `ezanaLMS_PastPapers` (
  `id` varchar(200) NOT NULL,
  `faculty_id` varchar(200) NOT NULL,
  `module_name` varchar(200) NOT NULL,
  `course_name` varchar(200) NOT NULL,
  `paper_name` varchar(200) NOT NULL,
  `pastpaper` varchar(200) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `solution` varchar(200) NOT NULL,
  `solution_visibility` varchar(200) NOT NULL,
  `paper_visibility` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `ezanaLMS_Settings`
--

CREATE TABLE `ezanaLMS_Settings` (
  `id` int(20) NOT NULL,
  `sysname` longtext NOT NULL,
  `logo` longtext NOT NULL,
  `version` varchar(200) NOT NULL,
  `policy` blob NOT NULL,
  `calendar_iframe` longblob NOT NULL,
  `stmp_host` varchar(200) NOT NULL,
  `stmp_port` varchar(200) NOT NULL,
  `stmp_sent_from` varchar(200) NOT NULL,
  `stmp_username` varchar(200) NOT NULL,
  `stmp_password` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ezanaLMS_Settings`
--

INSERT INTO `ezanaLMS_Settings` (`id`, `sysname`, `logo`, `version`, `policy`, `calendar_iframe`, `stmp_host`, `stmp_port`, `stmp_sent_from`, `stmp_username`, `stmp_password`) VALUES
(1, 'Ezana', 'logo.png', '1.3.0 Beta', '', 0x3c696672616d65207372633d2268747470733a2f2f63616c656e6461722e676f6f676c652e636f6d2f63616c656e6461722f656d6265643f6865696768743d36303026776b73743d31266267636f6c6f723d2532336666666666662663747a3d4166726963612532464e6169726f6269267372633d4e7a5a6c636e4e7a645773344d7a4a6d5a445533645463316358497a4d7a4a314f5764415a334a7664584175593246735a57356b595849755a3239765a32786c4c6d4e766251267372633d5a5734756132556a61473973615752686555426e636d3931634335324c6d4e68624756755a4746794c6d6476623264735a53356a62323026636f6c6f723d25323345344334343126636f6c6f723d2532333042383034332673686f775469746c653d302673686f77547a3d3022207374796c653d22626f726465722d77696474683a30222077696474683d223130353022206865696768743d2236303022206672616d65626f726465723d223022207363726f6c6c696e673d226e6f22203e3c2f696672616d653e, 'smtp.gmail.com', '465', 'devlaninc18@gmail.com', 'devlaninc18@gmail.com', '20Devlan@');

-- --------------------------------------------------------

--
-- Table structure for table `ezanaLMS_StudentAnswers`
--

CREATE TABLE `ezanaLMS_StudentAnswers` (
  `id` varchar(200) NOT NULL,
  `exam_id` varchar(200) NOT NULL,
  `module_code` varchar(200) NOT NULL,
  `module_name` varchar(200) NOT NULL,
  `attachments` varchar(200) NOT NULL,
  `student_regno` varchar(200) NOT NULL,
  `student_name` varchar(200) NOT NULL,
  `created_at` varchar(200) NOT NULL,
  `status` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `ezanaLMS_StudentGrades`
--

CREATE TABLE `ezanaLMS_StudentGrades` (
  `id` varchar(200) NOT NULL,
  `module_name` varchar(200) NOT NULL,
  `module_code` varchar(200) NOT NULL,
  `student_name` varchar(200) NOT NULL,
  `student_regno` varchar(200) NOT NULL,
  `marks_attained` varchar(200) NOT NULL,
  `created_at` varchar(200) NOT NULL,
  `status` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `ezanaLMS_StudentModuleGrades`
--

CREATE TABLE `ezanaLMS_StudentModuleGrades` (
  `id` varchar(200) NOT NULL,
  `regno` varchar(200) NOT NULL,
  `name` varchar(200) NOT NULL,
  `module_code` varchar(200) NOT NULL,
  `module_name` varchar(200) NOT NULL,
  `assignment_name` varchar(200) NOT NULL,
  `marks` varchar(200) NOT NULL,
  `semester` varchar(200) NOT NULL,
  `academic_year` varchar(200) NOT NULL,
  `course_id` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `ezanaLMS_Students`
--

CREATE TABLE `ezanaLMS_Students` (
  `id` varchar(200) NOT NULL,
  `admno` varchar(200) NOT NULL,
  `name` varchar(200) NOT NULL,
  `email` varchar(200) NOT NULL,
  `password` varchar(200) NOT NULL,
  `phone` varchar(200) NOT NULL,
  `adr` varchar(200) NOT NULL,
  `dob` varchar(200) NOT NULL,
  `idno` varchar(200) NOT NULL,
  `gender` varchar(200) NOT NULL,
  `acc_status` varchar(200) NOT NULL,
  `created_at` varchar(200) NOT NULL,
  `updated_at` varchar(200) NOT NULL,
  `profile_pic` varchar(200) NOT NULL,
  `faculty_id` varchar(200) NOT NULL,
  `day_enrolled` varchar(200) NOT NULL,
  `school` varchar(200) NOT NULL,
  `course` varchar(200) NOT NULL,
  `department` varchar(200) NOT NULL,
  `current_year` varchar(200) NOT NULL,
  `no_of_modules` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `ezanaLMS_StudentsGroups`
--

CREATE TABLE `ezanaLMS_StudentsGroups` (
  `id` varchar(200) NOT NULL,
  `faculty_id` varchar(200) NOT NULL,
  `name` varchar(200) NOT NULL,
  `code` varchar(200) NOT NULL,
  `student_admn` varchar(200) NOT NULL,
  `student_name` varchar(200) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `ezanaLMS_TimeTable`
--

CREATE TABLE `ezanaLMS_TimeTable` (
  `id` varchar(200) NOT NULL,
  `faculty_id` varchar(200) NOT NULL,
  `course_code` varchar(200) NOT NULL,
  `course_name` varchar(200) NOT NULL,
  `module_code` varchar(200) NOT NULL,
  `module_name` varchar(200) NOT NULL,
  `lecturer` varchar(200) NOT NULL,
  `day` varchar(200) NOT NULL,
  `time` varchar(200) NOT NULL,
  `room` varchar(200) NOT NULL,
  `link` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `ezanaLMS_UserLog`
--

CREATE TABLE `ezanaLMS_UserLog` (
  `id` int(11) NOT NULL,
  `user_id` varchar(200) NOT NULL,
  `name` varchar(255) NOT NULL,
  `ip` varbinary(16) NOT NULL,
  `loginTime` varchar(200) NOT NULL,
  `exact_login_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `User_Rank` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `ezanaLMS_UserRequests`
--

CREATE TABLE `ezanaLMS_UserRequests` (
  `id` varchar(200) NOT NULL,
  `name` varchar(200) NOT NULL,
  `email` varchar(200) NOT NULL,
  `number` varchar(200) NOT NULL,
  `request` longtext NOT NULL,
  `progress` longtext NOT NULL,
  `status` longtext NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `ezanaLMS_AcademicSettings`
--
ALTER TABLE `ezanaLMS_AcademicSettings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ezanaLMS_Admins`
--
ALTER TABLE `ezanaLMS_Admins`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ezanaLMS_AssignmentsAttempts`
--
ALTER TABLE `ezanaLMS_AssignmentsAttempts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ezanaLMS_BugReports`
--
ALTER TABLE `ezanaLMS_BugReports`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ezanaLMS_Calendar`
--
ALTER TABLE `ezanaLMS_Calendar`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ezanaLMS_ClassRecordings`
--
ALTER TABLE `ezanaLMS_ClassRecordings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ezanaLMS_CourseDirectories`
--
ALTER TABLE `ezanaLMS_CourseDirectories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ezanaLMS_CourseMemo`
--
ALTER TABLE `ezanaLMS_CourseMemo`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ezanaLMS_Courses`
--
ALTER TABLE `ezanaLMS_Courses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ezanaLMS_DepartmentalMemos`
--
ALTER TABLE `ezanaLMS_DepartmentalMemos`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ezanaLMS_Departments`
--
ALTER TABLE `ezanaLMS_Departments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ezanaLMS_Enrollments`
--
ALTER TABLE `ezanaLMS_Enrollments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ezanaLMS_ExamQuestions`
--
ALTER TABLE `ezanaLMS_ExamQuestions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ezanaLMS_Faculties`
--
ALTER TABLE `ezanaLMS_Faculties`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ezanaLMS_Groups`
--
ALTER TABLE `ezanaLMS_Groups`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ezanaLMS_GroupsAnnouncements`
--
ALTER TABLE `ezanaLMS_GroupsAnnouncements`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ezanaLMS_GroupsAssignments`
--
ALTER TABLE `ezanaLMS_GroupsAssignments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ezanaLMS_GroupsAssignmentsGrades`
--
ALTER TABLE `ezanaLMS_GroupsAssignmentsGrades`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ezanaLMS_Lecturers`
--
ALTER TABLE `ezanaLMS_Lecturers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ezanaLMS_ModuleAssignments`
--
ALTER TABLE `ezanaLMS_ModuleAssignments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ezanaLMS_ModuleAssigns`
--
ALTER TABLE `ezanaLMS_ModuleAssigns`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ezanaLMS_ModuleMemo`
--
ALTER TABLE `ezanaLMS_ModuleMemo`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ezanaLMS_ModuleRecommended`
--
ALTER TABLE `ezanaLMS_ModuleRecommended`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ezanaLMS_Modules`
--
ALTER TABLE `ezanaLMS_Modules`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ezanaLMS_ModulesAnnouncements`
--
ALTER TABLE `ezanaLMS_ModulesAnnouncements`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ezanaLMS_Notifications`
--
ALTER TABLE `ezanaLMS_Notifications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ezanaLMS_PasswordResets`
--
ALTER TABLE `ezanaLMS_PasswordResets`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ezanaLMS_PastPapers`
--
ALTER TABLE `ezanaLMS_PastPapers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ezanaLMS_Settings`
--
ALTER TABLE `ezanaLMS_Settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ezanaLMS_StudentAnswers`
--
ALTER TABLE `ezanaLMS_StudentAnswers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ezanaLMS_StudentGrades`
--
ALTER TABLE `ezanaLMS_StudentGrades`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ezanaLMS_StudentModuleGrades`
--
ALTER TABLE `ezanaLMS_StudentModuleGrades`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ezanaLMS_Students`
--
ALTER TABLE `ezanaLMS_Students`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ezanaLMS_StudentsGroups`
--
ALTER TABLE `ezanaLMS_StudentsGroups`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ezanaLMS_TimeTable`
--
ALTER TABLE `ezanaLMS_TimeTable`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ezanaLMS_UserLog`
--
ALTER TABLE `ezanaLMS_UserLog`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ezanaLMS_UserRequests`
--
ALTER TABLE `ezanaLMS_UserRequests`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `ezanaLMS_AcademicSettings`
--
ALTER TABLE `ezanaLMS_AcademicSettings`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `ezanaLMS_Notifications`
--
ALTER TABLE `ezanaLMS_Notifications`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `ezanaLMS_Settings`
--
ALTER TABLE `ezanaLMS_Settings`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `ezanaLMS_UserLog`
--
ALTER TABLE `ezanaLMS_UserLog`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
