-- phpMyAdmin SQL Dump
-- version 4.5.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Oct 28, 2016 at 11:55 PM
-- Server version: 5.7.11
-- PHP Version: 5.6.19

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `rubric`
--

-- --------------------------------------------------------

--
-- Table structure for table `assignment`
--

CREATE TABLE `assignment` (
  `NUM` int(11) NOT NULL,
  `TEACHER_NUM` int(11) NOT NULL COMMENT 'FK to TEACHERS',
  `TITLE` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Name of the rubric',
  `DESCRIPTION` text COLLATE utf8_unicode_ci NOT NULL COMMENT 'Description/instructions for the master rubric.'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Contains many small rubrics';

-- --------------------------------------------------------

--
-- Table structure for table `assignment-class_linker`
--

CREATE TABLE `assignment-class_linker` (
  `NUM` int(11) NOT NULL,
  `ASSIGNMENT_NUM` int(11) NOT NULL COMMENT 'FK to ASSIGNMENT',
  `CLASS_NUM` int(11) NOT NULL COMMENT 'FK to CLASS',
  `DUE_DATE` date NOT NULL COMMENT 'The date the assignment should have been submitted'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Links an assignment to a class';

-- --------------------------------------------------------

--
-- Table structure for table `assignment-rubric_linker`
--

CREATE TABLE `assignment-rubric_linker` (
  `NUM` int(11) NOT NULL,
  `RUBRIC_NUM` int(11) NOT NULL COMMENT 'FK to RUBRIC_NUM',
  `ASSIGNMENT_NUM` int(11) NOT NULL COMMENT 'FK to ASSIGNMENT'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Links master rubrics and rubrics together.';

-- --------------------------------------------------------

--
-- Table structure for table `class`
--

CREATE TABLE `class` (
  `NUM` int(11) NOT NULL,
  `TEACHER_NUM` int(11) NOT NULL COMMENT 'FK to TEACHERS',
  `NAME` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Name of the class.',
  `YEAR` int(11) NOT NULL COMMENT 'Year the class took place',
  `PERIOD` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Period for the class',
  `TERM` int(11) NOT NULL COMMENT 'The term/semester of the class',
  `DESCRIPTOR` text COLLATE utf8_unicode_ci NOT NULL COMMENT 'A generic description field.'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='A standard class.';

-- --------------------------------------------------------

--
-- Table structure for table `class-student_linker`
--

CREATE TABLE `class-student_linker` (
  `NUM` int(11) NOT NULL,
  `STUDENT_NUM` int(11) NOT NULL COMMENT 'FK to STUDENT',
  `CLASS_NUM` int(11) NOT NULL COMMENT 'FK to CLASS'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Links students to classes.';

-- --------------------------------------------------------

--
-- Table structure for table `component`
--

CREATE TABLE `component` (
  `NUM` int(11) NOT NULL,
  `TEACHER_NUM` int(11) NOT NULL COMMENT 'FK to TEACHERS',
  `PARENT_NUM` int(11) DEFAULT NULL COMMENT 'Parent component',
  `SYMBOL` varchar(20) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Symbol that relates to this category (I. II. a. 1. etc)',
  `NAME` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Human name of this category.',
  `DESCRIPTION` text COLLATE utf8_unicode_ci NOT NULL COMMENT 'Description for the category'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Actual store of our hiarchy of data.';

-- --------------------------------------------------------

--
-- Table structure for table `criterion`
--

CREATE TABLE `criterion` (
  `NUM` int(11) NOT NULL,
  `RUBRIC_CRITERIA_NUM` int(11) NOT NULL COMMENT 'FK to RUBRIC_CRITERIA',
  `COMPONENT_NUM` int(11) NOT NULL COMMENT 'FK to COMPONENT',
  `COMPILED_SYMBOL_TREE` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT 'All of the symbols of the component and it''s parents tied together.'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `grade`
--

CREATE TABLE `grade` (
  `NUM` int(11) NOT NULL,
  `RUBRIC_CRITERIA_NUM` int(11) NOT NULL COMMENT 'FK to RUBRIC_CRITERIA',
  `STUDENT_NUM` int(11) NOT NULL COMMENT 'FK to STUDENT',
  `MASTER_RUBRIC_NUM` int(11) NOT NULL COMMENT 'FK to MASTER_RUBRIC',
  `POINTS_EARNED` int(11) NOT NULL COMMENT 'Points earned for the criteria.',
  `COMMENT` text COLLATE utf8_unicode_ci NOT NULL COMMENT 'Teacher coment for reason given if needed.'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Stores points for all of the rubrics.';

-- --------------------------------------------------------

--
-- Table structure for table `rubric`
--

CREATE TABLE `rubric` (
  `NUM` int(11) NOT NULL,
  `TEACHER_NUM` int(11) NOT NULL COMMENT 'FK to TEACHERS',
  `MAX_POINTS_PER_CRITERIA` int(11) NOT NULL COMMENT 'The max points a student can get on a particular column',
  `SUBTITLE` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT 'A subtitle for the GRAND_RUBRIC section.'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Stores parts of a GRAND_RUBRIC.';

-- --------------------------------------------------------

--
-- Table structure for table `rubric_cell`
--

CREATE TABLE `rubric_cell` (
  `NUM` int(11) NOT NULL,
  `RUBRIC_QUALITY_NUM` int(11) NOT NULL COMMENT 'FK to RUBRIC_QUALITY',
  `RUBRIC_CRITERIA_NUM` int(11) NOT NULL COMMENT 'FK to RUBRIC_CRITERIA',
  `CONTENTS` text COLLATE utf8_unicode_ci NOT NULL COMMENT 'Actual cell content.'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Holds descriptions of individual cells.';

-- --------------------------------------------------------

--
-- Table structure for table `rubric_criteria`
--

CREATE TABLE `rubric_criteria` (
  `NUM` int(11) NOT NULL,
  `RUBRIC_NUM` int(11) NOT NULL COMMENT 'FK to RUBRIC',
  `CRITERIA_TITLE` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Human readable title for the criteria. '
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `rubric_quality`
--

CREATE TABLE `rubric_quality` (
  `NUM` int(11) NOT NULL,
  `RUBRIC_NUM` int(11) NOT NULL COMMENT 'FK to RUBRIC',
  `POINTS` int(11) NOT NULL COMMENT 'Amount of points possible in this column.',
  `QUALITY_TITLE` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Human readable quality of this column. Example: Proficient, Poor, Satisfactory.'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Holds quality column information ';

-- --------------------------------------------------------

--
-- Table structure for table `student`
--

CREATE TABLE `student` (
  `NUM` int(11) NOT NULL,
  `USERNAME` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Username of student',
  `PASSWORD` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Password of student',
  `FIRST_NAME` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Student first name',
  `LAST_NAME` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Student last name',
  `NICK_NAME` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Student nick name (optional)',
  `GRADE` int(11) NOT NULL COMMENT 'Grade level of student',
  `EXTRA` text COLLATE utf8_unicode_ci NOT NULL COMMENT 'Extra descriptor field for teacher',
  `SETTINGS` text COLLATE utf8_unicode_ci NOT NULL COMMENT 'Settings (json)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Holds usernames, passwords, and more for student accounts.';

-- --------------------------------------------------------

--
-- Table structure for table `teacher`
--

CREATE TABLE `teacher` (
  `NUM` int(11) NOT NULL,
  `USERNAME` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Username of teacher',
  `PASSWORD` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Password of teacher',
  `FIRST_NAME` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT 'First name of teacher',
  `LAST_NAME` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Last name of teacher',
  `SETTINGS` text COLLATE utf8_unicode_ci NOT NULL COMMENT 'Settings, stored as JSON.'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Holds usernames, passwords, and more for teacher accounts.';

-- --------------------------------------------------------

--
-- Table structure for table `teaches`
--

CREATE TABLE `teaches` (
  `NUM` int(11) NOT NULL,
  `STUDENT_NUM` int(11) NOT NULL COMMENT 'FK to STUDENT',
  `TEACHER_NUM` int(11) NOT NULL COMMENT 'FK to TEACHER'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Links teacher accounts to student accounts.';

--
-- Indexes for dumped tables
--

--
-- Indexes for table `assignment`
--
ALTER TABLE `assignment`
  ADD PRIMARY KEY (`NUM`),
  ADD KEY `TEACHER_NUM` (`TEACHER_NUM`);

--
-- Indexes for table `assignment-class_linker`
--
ALTER TABLE `assignment-class_linker`
  ADD PRIMARY KEY (`NUM`),
  ADD KEY `ASSIGNMENT_NUM` (`ASSIGNMENT_NUM`),
  ADD KEY `CLASS_NUM` (`CLASS_NUM`);

--
-- Indexes for table `assignment-rubric_linker`
--
ALTER TABLE `assignment-rubric_linker`
  ADD PRIMARY KEY (`NUM`),
  ADD KEY `MASTER_RUBRIC_NUM` (`ASSIGNMENT_NUM`),
  ADD KEY `RUBRIC_NUM` (`RUBRIC_NUM`);

--
-- Indexes for table `class`
--
ALTER TABLE `class`
  ADD PRIMARY KEY (`NUM`),
  ADD KEY `TEACHER_NUM` (`TEACHER_NUM`);

--
-- Indexes for table `class-student_linker`
--
ALTER TABLE `class-student_linker`
  ADD PRIMARY KEY (`NUM`),
  ADD KEY `STUDENT_NUM` (`STUDENT_NUM`),
  ADD KEY `CLASS_NUM` (`CLASS_NUM`);

--
-- Indexes for table `component`
--
ALTER TABLE `component`
  ADD PRIMARY KEY (`NUM`),
  ADD KEY `TEACHER_NUM` (`TEACHER_NUM`),
  ADD KEY `PARENT_NUM` (`PARENT_NUM`);

--
-- Indexes for table `criterion`
--
ALTER TABLE `criterion`
  ADD PRIMARY KEY (`NUM`),
  ADD KEY `RUBRIC_CRITERIA_NUM` (`RUBRIC_CRITERIA_NUM`),
  ADD KEY `CATEGORY_NUM` (`COMPONENT_NUM`);

--
-- Indexes for table `grade`
--
ALTER TABLE `grade`
  ADD PRIMARY KEY (`NUM`),
  ADD KEY `STUDENT_NUM` (`STUDENT_NUM`),
  ADD KEY `RUBRIC_CRITERIA_NUM` (`RUBRIC_CRITERIA_NUM`),
  ADD KEY `MASTER_RUBRIC_NUM` (`MASTER_RUBRIC_NUM`);

--
-- Indexes for table `rubric`
--
ALTER TABLE `rubric`
  ADD PRIMARY KEY (`NUM`),
  ADD KEY `TEACHER_NUM` (`TEACHER_NUM`);

--
-- Indexes for table `rubric_cell`
--
ALTER TABLE `rubric_cell`
  ADD PRIMARY KEY (`NUM`),
  ADD KEY `RUBRIC_CRITERIA_NUM` (`RUBRIC_CRITERIA_NUM`),
  ADD KEY `RUBRIC_QUALITY_NUM` (`RUBRIC_QUALITY_NUM`);

--
-- Indexes for table `rubric_criteria`
--
ALTER TABLE `rubric_criteria`
  ADD PRIMARY KEY (`NUM`),
  ADD KEY `RUBRIC_NUM` (`RUBRIC_NUM`);

--
-- Indexes for table `rubric_quality`
--
ALTER TABLE `rubric_quality`
  ADD PRIMARY KEY (`NUM`),
  ADD KEY `RUBRIC_NUM` (`RUBRIC_NUM`);

--
-- Indexes for table `student`
--
ALTER TABLE `student`
  ADD PRIMARY KEY (`NUM`);

--
-- Indexes for table `teacher`
--
ALTER TABLE `teacher`
  ADD PRIMARY KEY (`NUM`);

--
-- Indexes for table `teaches`
--
ALTER TABLE `teaches`
  ADD PRIMARY KEY (`NUM`),
  ADD KEY `STUDENT_NUM` (`STUDENT_NUM`),
  ADD KEY `TEACHER_NUM` (`TEACHER_NUM`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `assignment`
--
ALTER TABLE `assignment`
  MODIFY `NUM` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `assignment-class_linker`
--
ALTER TABLE `assignment-class_linker`
  MODIFY `NUM` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `assignment-rubric_linker`
--
ALTER TABLE `assignment-rubric_linker`
  MODIFY `NUM` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `class`
--
ALTER TABLE `class`
  MODIFY `NUM` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `class-student_linker`
--
ALTER TABLE `class-student_linker`
  MODIFY `NUM` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `component`
--
ALTER TABLE `component`
  MODIFY `NUM` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `criterion`
--
ALTER TABLE `criterion`
  MODIFY `NUM` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `grade`
--
ALTER TABLE `grade`
  MODIFY `NUM` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `rubric`
--
ALTER TABLE `rubric`
  MODIFY `NUM` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `rubric_cell`
--
ALTER TABLE `rubric_cell`
  MODIFY `NUM` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `rubric_criteria`
--
ALTER TABLE `rubric_criteria`
  MODIFY `NUM` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `rubric_quality`
--
ALTER TABLE `rubric_quality`
  MODIFY `NUM` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `student`
--
ALTER TABLE `student`
  MODIFY `NUM` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `teacher`
--
ALTER TABLE `teacher`
  MODIFY `NUM` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `teaches`
--
ALTER TABLE `teaches`
  MODIFY `NUM` int(11) NOT NULL AUTO_INCREMENT;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `assignment-rubric_linker`
--
ALTER TABLE `assignment-rubric_linker`
  ADD CONSTRAINT `assignment-rubric_linker_ibfk_1` FOREIGN KEY (`RUBRIC_NUM`) REFERENCES `rubric` (`NUM`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `assignment-rubric_linker_ibfk_2` FOREIGN KEY (`ASSIGNMENT_NUM`) REFERENCES `assignment` (`NUM`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `class`
--
ALTER TABLE `class`
  ADD CONSTRAINT `class_ibfk_2` FOREIGN KEY (`TEACHER_NUM`) REFERENCES `teacher` (`NUM`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `class-student_linker`
--
ALTER TABLE `class-student_linker`
  ADD CONSTRAINT `class-student_linker_ibfk_1` FOREIGN KEY (`STUDENT_NUM`) REFERENCES `student` (`NUM`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `class-student_linker_ibfk_2` FOREIGN KEY (`CLASS_NUM`) REFERENCES `class` (`NUM`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `component`
--
ALTER TABLE `component`
  ADD CONSTRAINT `component_ibfk_1` FOREIGN KEY (`TEACHER_NUM`) REFERENCES `teacher` (`NUM`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `component_ibfk_2` FOREIGN KEY (`PARENT_NUM`) REFERENCES `component` (`NUM`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `criterion`
--
ALTER TABLE `criterion`
  ADD CONSTRAINT `criterion_ibfk_1` FOREIGN KEY (`RUBRIC_CRITERIA_NUM`) REFERENCES `rubric_criteria` (`NUM`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `criterion_ibfk_2` FOREIGN KEY (`COMPONENT_NUM`) REFERENCES `component` (`NUM`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `grade`
--
ALTER TABLE `grade`
  ADD CONSTRAINT `grade_ibfk_1` FOREIGN KEY (`RUBRIC_CRITERIA_NUM`) REFERENCES `rubric_criteria` (`NUM`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `grade_ibfk_2` FOREIGN KEY (`STUDENT_NUM`) REFERENCES `student` (`NUM`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `grade_ibfk_3` FOREIGN KEY (`MASTER_RUBRIC_NUM`) REFERENCES `assignment` (`NUM`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `rubric`
--
ALTER TABLE `rubric`
  ADD CONSTRAINT `rubric_ibfk_1` FOREIGN KEY (`TEACHER_NUM`) REFERENCES `teacher` (`NUM`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `rubric_cell`
--
ALTER TABLE `rubric_cell`
  ADD CONSTRAINT `rubric_cell_ibfk_1` FOREIGN KEY (`RUBRIC_QUALITY_NUM`) REFERENCES `rubric_quality` (`NUM`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `rubric_cell_ibfk_2` FOREIGN KEY (`RUBRIC_CRITERIA_NUM`) REFERENCES `rubric_criteria` (`NUM`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `rubric_criteria`
--
ALTER TABLE `rubric_criteria`
  ADD CONSTRAINT `rubric_criteria_ibfk_1` FOREIGN KEY (`RUBRIC_NUM`) REFERENCES `rubric` (`NUM`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `rubric_quality`
--
ALTER TABLE `rubric_quality`
  ADD CONSTRAINT `rubric_quality_ibfk_1` FOREIGN KEY (`RUBRIC_NUM`) REFERENCES `rubric` (`NUM`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `teaches`
--
ALTER TABLE `teaches`
  ADD CONSTRAINT `teaches_ibfk_1` FOREIGN KEY (`STUDENT_NUM`) REFERENCES `student` (`NUM`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `teaches_ibfk_2` FOREIGN KEY (`TEACHER_NUM`) REFERENCES `teacher` (`NUM`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
