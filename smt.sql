-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 10, 2024 at 02:53 PM
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
-- Database: `smt`
--

-- --------------------------------------------------------

--
-- Table structure for table `attendancerecord`
--

CREATE TABLE `attendancerecord` (
  `attendanceID` int(11) NOT NULL,
  `attendanceDate` datetime NOT NULL,
  `studentID` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `attendancerecord`
--

INSERT INTO `attendancerecord` (`attendanceID`, `attendanceDate`, `studentID`) VALUES
(1, '2024-12-10 11:25:20', 2);

-- --------------------------------------------------------

--
-- Table structure for table `monthattendancerecord`
--

CREATE TABLE `monthattendancerecord` (
  `RecordID` int(11) NOT NULL,
  `NumberOfAttendance` int(11) NOT NULL,
  `MonthlyRecordID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `monthlyrecords`
--

CREATE TABLE `monthlyrecords` (
  `MonthlyRecordID` int(11) NOT NULL,
  `Month` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `monthlyviolationrecord`
--

CREATE TABLE `monthlyviolationrecord` (
  `MonthlyViolationID` int(11) NOT NULL,
  `NumberOfViolations` int(11) NOT NULL,
  `PendingViolations` int(11) NOT NULL,
  `ReviewedViolations` int(11) NOT NULL,
  `MonthlyRecordID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `programs`
--

CREATE TABLE `programs` (
  `ProgramID` int(11) NOT NULL,
  `ProgramName` varchar(50) NOT NULL,
  `ProgramCode` varchar(10) NOT NULL,
  `ProgramCategory` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `programs`
--

INSERT INTO `programs` (`ProgramID`, `ProgramName`, `ProgramCode`, `ProgramCategory`) VALUES
(1, 'Bachelor of Science in Computer Science', 'BSCS', ''),
(2, 'Bachelor of Multimedia Arts', 'BMMA', ''),
(3, 'Bachelor of Science in Computer Engineering', 'BSCpE', ''),
(4, 'Bachelor of Science in Information Technology', 'BSIT', ''),
(5, 'Bachelor of Science in Hospitality Management', 'BSHM', ''),
(6, 'Bachelor of Science in Tourism Management', 'BSTM', ''),
(7, 'Bachelor of Science in Business Administration', 'BSBA', ''),
(8, 'Bachelor of Science in Accountancy', 'BSA', ''),
(9, 'Bachelor of Science in Accounting Information Syst', 'BSAIS', ''),
(10, 'Bachelor of Secondary Education', 'BSED', ''),
(11, 'Bachelor of Elementary Education', 'BEED', ''),
(12, 'Bachelor of Science in Psychology', 'BSP', '');

-- --------------------------------------------------------

--
-- Table structure for table `studentattendancerecord`
--

CREATE TABLE `studentattendancerecord` (
  `StudentAttendanceID` int(11) NOT NULL,
  `TotalAttendance` int(11) NOT NULL,
  `StudentRecordID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `studentrecords`
--

CREATE TABLE `studentrecords` (
  `StudentRecordID` int(11) NOT NULL,
  `StudentID` bigint(20) NOT NULL,
  `YearlyRecordID` int(11) NOT NULL,
  `MonthlyRecordID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `StudentID` bigint(20) NOT NULL,
  `StudentName` varchar(50) NOT NULL,
  `Year` varchar(10) NOT NULL,
  `ProgramID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`StudentID`, `StudentName`, `Year`, `ProgramID`) VALUES
(1, 'asdf', '1st', 2),
(2, 'aa', '2nd', 1),
(3, 'asdfa', '1st', 1),
(4, 'sadfasfas', '2nd', 2),
(5, 'sdfasfasf', '2nd', 1),
(6, 'sadfaa', '1st', 1),
(111111, 'asdfa', '1st', 1),
(123456, 'Marcelo, Jordan Limwell C.', '4th', 1),
(123457, 'Marcelo, Jordan Limwell C.', '1st', 2),
(222222, 'Jordan Limwell C. Marcelo', '1st', 1),
(236846, 'Icarus Jordan', '3rd', 2),
(654321, 'asdfa', '3rd', 2),
(765432, 'sadfa', '1st', 2),
(876543, 'asdfasfas', '1st', 2),
(987654, 'asdfas', '1st', 2),
(3333333, 'asdfasfa', '1st', 2),
(2000236845, 'Jordan Limwell C. Marcelo', '4th', 1);

-- --------------------------------------------------------

--
-- Table structure for table `studentviolationrecord`
--

CREATE TABLE `studentviolationrecord` (
  `StudentViolationID` int(11) NOT NULL,
  `TotalViolations` int(11) NOT NULL,
  `PendingViolations` int(11) NOT NULL,
  `ReviewedViolations` int(11) NOT NULL,
  `StudentRecordID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `violationrecord`
--

CREATE TABLE `violationrecord` (
  `ViolationID` int(11) NOT NULL,
  `ViolationDate` datetime NOT NULL,
  `ViolationType` varchar(50) NOT NULL,
  `StudentID` bigint(20) NOT NULL,
  `Notes` varchar(225) DEFAULT NULL,
  `ViolationPicture` varchar(255) DEFAULT NULL,
  `ViolationStatus` varchar(50) DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `violationrecord`
--

INSERT INTO `violationrecord` (`ViolationID`, `ViolationDate`, `ViolationType`, `StudentID`, `Notes`, `ViolationPicture`, `ViolationStatus`) VALUES
(1, '2024-12-09 00:00:00', 'WithoutUniform', 2000236845, '', 'images/placeholder.png', 'Reviewed'),
(5, '2024-12-09 14:26:00', 'WithoutID', 2000236845, '', 'images/placeholder.png', 'Pending'),
(6, '2024-12-09 14:27:00', 'WithoutID', 236846, '', 'images/placeholder.png', 'Pending'),
(7, '2024-12-09 14:27:00', 'WithoutUniform', 236846, '', 'images/placeholder.png', 'Pending'),
(9, '2024-12-09 14:30:00', 'WithoutID', 654321, '', 'images/placeholder.png', 'Pending'),
(10, '2024-12-09 14:30:00', 'WithoutID', 765432, '', 'images/placeholder.png', 'Pending'),
(11, '2024-12-09 14:30:00', 'WithoutID', 876543, '', 'images/placeholder.png', 'Pending'),
(12, '2024-12-09 14:30:00', 'WithoutID', 987654, '', 'images/placeholder.png', 'Pending'),
(13, '2024-12-09 14:36:00', 'WithoutID', 111111, '', 'images/placeholder.png', 'Pending'),
(14, '2024-12-09 14:36:00', 'WithoutID', 222222, '', 'images/placeholder.png', 'Pending'),
(15, '2024-12-09 14:41:00', 'WithoutID', 3333333, '', 'images/placeholder.png', 'Pending'),
(16, '2024-12-10 08:15:00', 'WithoutID', 2000236845, 'Tanga eh', 'images/placeholder.png', 'Pending');

-- --------------------------------------------------------

--
-- Table structure for table `yearlyattendancerecord`
--

CREATE TABLE `yearlyattendancerecord` (
  `YearlyAttendanceID` int(11) NOT NULL,
  `TotalAttendance` int(11) NOT NULL,
  `YearlyRecordID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `yearlyrecords`
--

CREATE TABLE `yearlyrecords` (
  `YearlyRecordID` int(11) NOT NULL,
  `Year` varchar(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `yearlyviolationrecord`
--

CREATE TABLE `yearlyviolationrecord` (
  `YearlyViolationID` int(11) NOT NULL,
  `TotalViolations` int(11) NOT NULL,
  `PendingViolations` int(11) NOT NULL,
  `ReviewedViolations` int(11) NOT NULL,
  `YearlyRecordID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `attendancerecord`
--
ALTER TABLE `attendancerecord`
  ADD PRIMARY KEY (`attendanceID`),
  ADD KEY `studentID` (`studentID`);

--
-- Indexes for table `monthattendancerecord`
--
ALTER TABLE `monthattendancerecord`
  ADD PRIMARY KEY (`RecordID`),
  ADD KEY `MonthlyRecordID` (`MonthlyRecordID`);

--
-- Indexes for table `monthlyrecords`
--
ALTER TABLE `monthlyrecords`
  ADD PRIMARY KEY (`MonthlyRecordID`);

--
-- Indexes for table `monthlyviolationrecord`
--
ALTER TABLE `monthlyviolationrecord`
  ADD PRIMARY KEY (`MonthlyViolationID`),
  ADD KEY `MonthlyRecordID` (`MonthlyRecordID`);

--
-- Indexes for table `programs`
--
ALTER TABLE `programs`
  ADD PRIMARY KEY (`ProgramID`);

--
-- Indexes for table `studentattendancerecord`
--
ALTER TABLE `studentattendancerecord`
  ADD PRIMARY KEY (`StudentAttendanceID`),
  ADD KEY `StudentRecordID` (`StudentRecordID`);

--
-- Indexes for table `studentrecords`
--
ALTER TABLE `studentrecords`
  ADD PRIMARY KEY (`StudentRecordID`),
  ADD KEY `StudentID` (`StudentID`),
  ADD KEY `YearlyRecordID` (`YearlyRecordID`),
  ADD KEY `MonthlyRecordID` (`MonthlyRecordID`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`StudentID`),
  ADD KEY `ProgramID` (`ProgramID`);

--
-- Indexes for table `studentviolationrecord`
--
ALTER TABLE `studentviolationrecord`
  ADD PRIMARY KEY (`StudentViolationID`),
  ADD KEY `StudentRecordID` (`StudentRecordID`);

--
-- Indexes for table `violationrecord`
--
ALTER TABLE `violationrecord`
  ADD PRIMARY KEY (`ViolationID`),
  ADD KEY `StudentID` (`StudentID`);

--
-- Indexes for table `yearlyattendancerecord`
--
ALTER TABLE `yearlyattendancerecord`
  ADD PRIMARY KEY (`YearlyAttendanceID`),
  ADD KEY `YearlyRecordID` (`YearlyRecordID`);

--
-- Indexes for table `yearlyrecords`
--
ALTER TABLE `yearlyrecords`
  ADD PRIMARY KEY (`YearlyRecordID`);

--
-- Indexes for table `yearlyviolationrecord`
--
ALTER TABLE `yearlyviolationrecord`
  ADD PRIMARY KEY (`YearlyViolationID`),
  ADD KEY `YearlyRecordID` (`YearlyRecordID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `attendancerecord`
--
ALTER TABLE `attendancerecord`
  MODIFY `attendanceID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `violationrecord`
--
ALTER TABLE `violationrecord`
  MODIFY `ViolationID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `attendancerecord`
--
ALTER TABLE `attendancerecord`
  ADD CONSTRAINT `attendancerecord_ibfk_1` FOREIGN KEY (`studentID`) REFERENCES `students` (`StudentID`);

--
-- Constraints for table `monthattendancerecord`
--
ALTER TABLE `monthattendancerecord`
  ADD CONSTRAINT `monthattendancerecord_ibfk_1` FOREIGN KEY (`MonthlyRecordID`) REFERENCES `monthlyrecords` (`MonthlyRecordID`);

--
-- Constraints for table `monthlyviolationrecord`
--
ALTER TABLE `monthlyviolationrecord`
  ADD CONSTRAINT `monthlyviolationrecord_ibfk_1` FOREIGN KEY (`MonthlyRecordID`) REFERENCES `monthlyrecords` (`MonthlyRecordID`);

--
-- Constraints for table `studentattendancerecord`
--
ALTER TABLE `studentattendancerecord`
  ADD CONSTRAINT `studentattendancerecord_ibfk_1` FOREIGN KEY (`StudentRecordID`) REFERENCES `studentrecords` (`StudentRecordID`);

--
-- Constraints for table `studentrecords`
--
ALTER TABLE `studentrecords`
  ADD CONSTRAINT `studentrecords_ibfk_1` FOREIGN KEY (`StudentID`) REFERENCES `students` (`StudentID`),
  ADD CONSTRAINT `studentrecords_ibfk_2` FOREIGN KEY (`YearlyRecordID`) REFERENCES `yearlyrecords` (`YearlyRecordID`),
  ADD CONSTRAINT `studentrecords_ibfk_3` FOREIGN KEY (`MonthlyRecordID`) REFERENCES `monthlyrecords` (`MonthlyRecordID`);

--
-- Constraints for table `students`
--
ALTER TABLE `students`
  ADD CONSTRAINT `students_ibfk_1` FOREIGN KEY (`ProgramID`) REFERENCES `programs` (`ProgramID`);

--
-- Constraints for table `studentviolationrecord`
--
ALTER TABLE `studentviolationrecord`
  ADD CONSTRAINT `studentviolationrecord_ibfk_1` FOREIGN KEY (`StudentRecordID`) REFERENCES `studentrecords` (`StudentRecordID`);

--
-- Constraints for table `violationrecord`
--
ALTER TABLE `violationrecord`
  ADD CONSTRAINT `violationrecord_ibfk_1` FOREIGN KEY (`StudentID`) REFERENCES `students` (`StudentID`);

--
-- Constraints for table `yearlyattendancerecord`
--
ALTER TABLE `yearlyattendancerecord`
  ADD CONSTRAINT `yearlyattendancerecord_ibfk_1` FOREIGN KEY (`YearlyRecordID`) REFERENCES `yearlyrecords` (`YearlyRecordID`);

--
-- Constraints for table `yearlyviolationrecord`
--
ALTER TABLE `yearlyviolationrecord`
  ADD CONSTRAINT `yearlyviolationrecord_ibfk_1` FOREIGN KEY (`YearlyRecordID`) REFERENCES `yearlyrecords` (`YearlyRecordID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
