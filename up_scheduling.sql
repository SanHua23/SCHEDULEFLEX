-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 07, 2024 at 09:40 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `up_scheduling`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_tbl`
--

CREATE TABLE `admin_tbl` (
  `admin_id` int(11) NOT NULL,
  `username` text NOT NULL,
  `password` text NOT NULL,
  `user_role` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin_tbl`
--

INSERT INTO `admin_tbl` (`admin_id`, `username`, `password`, `user_role`) VALUES
(1, 'admin', '$2y$10$1pCWfe7uhIXM7sIVJesuzOF95JFNxZ1pAPtZHMXKn9eKR/Z2s73QC', '1');

-- --------------------------------------------------------

--
-- Table structure for table `ay_tbl`
--

CREATE TABLE `ay_tbl` (
  `ay_id` int(11) NOT NULL,
  `start_year` text NOT NULL,
  `end_year` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `course_tbl`
--

CREATE TABLE `course_tbl` (
  `course_id` int(11) NOT NULL,
  `course_name` text NOT NULL,
  `course_description` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `faculty_tbl`
--

CREATE TABLE `faculty_tbl` (
  `faculty_id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `first_name` text NOT NULL,
  `last_name` text NOT NULL,
  `description` text NOT NULL,
  `pstart_time` time NOT NULL,
  `pend_time` time NOT NULL,
  `user_id` int(11) DEFAULT NULL COMMENT 'user faculty role\r\n'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `notification_tbl`
--

CREATE TABLE `notification_tbl` (
  `notification_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `description` text NOT NULL,
  `date_time` datetime NOT NULL DEFAULT current_timestamp(),
  `status` text NOT NULL DEFAULT 'new'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `preferred_subject_tbl`
--

CREATE TABLE `preferred_subject_tbl` (
  `ps_id` int(11) NOT NULL,
  `subject_id` int(11) NOT NULL,
  `faculty_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `room_tbl`
--

CREATE TABLE `room_tbl` (
  `room_id` int(11) NOT NULL,
  `room_number` text NOT NULL,
  `room_description` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `schedule_f2f_tbl`
--

CREATE TABLE `schedule_f2f_tbl` (
  `schedule_f2f_id` int(11) NOT NULL,
  `schedule_setup_id` int(11) DEFAULT NULL,
  `room_id` int(11) DEFAULT NULL,
  `start_datetime` datetime DEFAULT NULL,
  `end_datetime` datetime DEFAULT NULL,
  `remarks` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `schedule_ol_tbl`
--

CREATE TABLE `schedule_ol_tbl` (
  `schedule_id` int(11) NOT NULL,
  `schedule_setup_id` int(11) DEFAULT NULL,
  `time_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `schedule_setup_tbl`
--

CREATE TABLE `schedule_setup_tbl` (
  `schedule_setup_id` int(11) NOT NULL,
  `subject_id` int(11) DEFAULT NULL,
  `section_id` int(11) DEFAULT NULL,
  `course_id` int(11) DEFAULT NULL,
  `faculty_id` int(11) DEFAULT NULL,
  `semester` text NOT NULL,
  `ay_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `section_tbl`
--

CREATE TABLE `section_tbl` (
  `section_id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `section` text NOT NULL,
  `year_level` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `subject_tbl`
--

CREATE TABLE `subject_tbl` (
  `subject_id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `subject_code` text NOT NULL,
  `subject_title` text NOT NULL,
  `year_level` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `time_tbl`
--

CREATE TABLE `time_tbl` (
  `time_id` int(11) NOT NULL,
  `days` text NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `total_time` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_tbl`
--

CREATE TABLE `user_tbl` (
  `user_id` int(11) NOT NULL,
  `email` text NOT NULL,
  `password` text NOT NULL,
  `user_role` text NOT NULL,
  `verification` text DEFAULT NULL,
  `first_name` text NOT NULL,
  `last_name` text NOT NULL,
  `status` text NOT NULL DEFAULT 'pending',
  `student_number` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_tbl`
--
ALTER TABLE `admin_tbl`
  ADD PRIMARY KEY (`admin_id`);

--
-- Indexes for table `ay_tbl`
--
ALTER TABLE `ay_tbl`
  ADD PRIMARY KEY (`ay_id`);

--
-- Indexes for table `course_tbl`
--
ALTER TABLE `course_tbl`
  ADD PRIMARY KEY (`course_id`);

--
-- Indexes for table `faculty_tbl`
--
ALTER TABLE `faculty_tbl`
  ADD PRIMARY KEY (`faculty_id`);

--
-- Indexes for table `notification_tbl`
--
ALTER TABLE `notification_tbl`
  ADD PRIMARY KEY (`notification_id`);

--
-- Indexes for table `preferred_subject_tbl`
--
ALTER TABLE `preferred_subject_tbl`
  ADD PRIMARY KEY (`ps_id`);

--
-- Indexes for table `room_tbl`
--
ALTER TABLE `room_tbl`
  ADD PRIMARY KEY (`room_id`);

--
-- Indexes for table `schedule_f2f_tbl`
--
ALTER TABLE `schedule_f2f_tbl`
  ADD PRIMARY KEY (`schedule_f2f_id`);

--
-- Indexes for table `schedule_ol_tbl`
--
ALTER TABLE `schedule_ol_tbl`
  ADD PRIMARY KEY (`schedule_id`);

--
-- Indexes for table `schedule_setup_tbl`
--
ALTER TABLE `schedule_setup_tbl`
  ADD PRIMARY KEY (`schedule_setup_id`);

--
-- Indexes for table `section_tbl`
--
ALTER TABLE `section_tbl`
  ADD PRIMARY KEY (`section_id`);

--
-- Indexes for table `subject_tbl`
--
ALTER TABLE `subject_tbl`
  ADD PRIMARY KEY (`subject_id`);

--
-- Indexes for table `time_tbl`
--
ALTER TABLE `time_tbl`
  ADD PRIMARY KEY (`time_id`);

--
-- Indexes for table `user_tbl`
--
ALTER TABLE `user_tbl`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin_tbl`
--
ALTER TABLE `admin_tbl`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `ay_tbl`
--
ALTER TABLE `ay_tbl`
  MODIFY `ay_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `course_tbl`
--
ALTER TABLE `course_tbl`
  MODIFY `course_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `faculty_tbl`
--
ALTER TABLE `faculty_tbl`
  MODIFY `faculty_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=73;

--
-- AUTO_INCREMENT for table `notification_tbl`
--
ALTER TABLE `notification_tbl`
  MODIFY `notification_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `preferred_subject_tbl`
--
ALTER TABLE `preferred_subject_tbl`
  MODIFY `ps_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=181;

--
-- AUTO_INCREMENT for table `room_tbl`
--
ALTER TABLE `room_tbl`
  MODIFY `room_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `schedule_f2f_tbl`
--
ALTER TABLE `schedule_f2f_tbl`
  MODIFY `schedule_f2f_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT for table `schedule_ol_tbl`
--
ALTER TABLE `schedule_ol_tbl`
  MODIFY `schedule_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=256;

--
-- AUTO_INCREMENT for table `schedule_setup_tbl`
--
ALTER TABLE `schedule_setup_tbl`
  MODIFY `schedule_setup_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=85;

--
-- AUTO_INCREMENT for table `section_tbl`
--
ALTER TABLE `section_tbl`
  MODIFY `section_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT for table `subject_tbl`
--
ALTER TABLE `subject_tbl`
  MODIFY `subject_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `time_tbl`
--
ALTER TABLE `time_tbl`
  MODIFY `time_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT for table `user_tbl`
--
ALTER TABLE `user_tbl`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
