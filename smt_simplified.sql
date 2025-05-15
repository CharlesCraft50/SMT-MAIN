-- --------------------------------------------------------
-- Clean SQL Dump: Final Version with ProgramCode, ProgramCategory, and RFID
-- Database: smt
-- --------------------------------------------------------

-- Drop and recreate the database
DROP DATABASE IF EXISTS smt;
CREATE DATABASE smt;
USE smt;

-- --------------------------------------------------------
-- Table: Program
-- --------------------------------------------------------
CREATE TABLE Program (
  ProgramID INT NOT NULL AUTO_INCREMENT,
  ProgramName VARCHAR(100) NOT NULL,
  ProgramCode VARCHAR(10) NOT NULL,
  Department VARCHAR(100) NOT NULL,
  ProgramCategory VARCHAR(100) NOT NULL,
  PRIMARY KEY (ProgramID)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert provided programs with ProgramCode and ProgramCategory
INSERT INTO Program (ProgramID, ProgramName, ProgramCode, Department, ProgramCategory) VALUES
(1, 'Bachelor of Science in Computer Science', 'BSCS', 'College of Engineering', 'Science'),
(2, 'Bachelor of Multimedia Arts', 'BMMA', 'College of Arts', 'Arts'),
(3, 'Bachelor of Science in Computer Engineering', 'BSCpE', 'College of Engineering', 'Science'),
(4, 'Bachelor of Science in Information Technology', 'BSIT', 'College of Engineering', 'Technology'),
(5, 'Bachelor of Science in Hospitality Management', 'BSHM', 'College of Business', 'Hospitality'),
(6, 'Bachelor of Science in Tourism Management', 'BSTM', 'College of Business', 'Tourism'),
(7, 'Bachelor of Science in Business Administration', 'BSBA', 'College of Business', 'Business'),
(8, 'Bachelor of Science in Accountancy', 'BSA', 'College of Business', 'Accountancy'),
(9, 'Bachelor of Science in Accounting Information Systems', 'BSAIS', 'College of Business', 'Accountancy'),
(10, 'Bachelor of Secondary Education', 'BSED', 'College of Education', 'Education'),
(11, 'Bachelor of Elementary Education', 'BEED', 'College of Education', 'Education'),
(12, 'Bachelor of Science in Psychology', 'BSP', 'College of Arts', 'Psychology');

-- --------------------------------------------------------
-- Table: Students
-- --------------------------------------------------------
CREATE TABLE Students (
  StudentID INT NOT NULL AUTO_INCREMENT,
  StudentName VARCHAR(100) NOT NULL,
  YearLevel VARCHAR(10) NOT NULL,
  ProgramID INT NOT NULL,
  RFID VARCHAR(50) DEFAULT NULL,  -- RFID field: NULL if no RFID, else RFID code
  PRIMARY KEY (StudentID),
  FOREIGN KEY (ProgramID) REFERENCES Program(ProgramID)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- Table: DailyRecords
-- --------------------------------------------------------
CREATE TABLE DailyRecords (
  RecordID INT NOT NULL AUTO_INCREMENT,
  StudentID INT NOT NULL,
  ViolationDate DATE NOT NULL,
  Attendance BOOLEAN NOT NULL,
  Violated BOOLEAN NOT NULL,
  ViolationType VARCHAR(100),
  Notes VARCHAR(255),
  ViolationPicture VARCHAR(255) DEFAULT NULL,
  ViolationStatus VARCHAR(50) NOT NULL DEFAULT 'Pending',
  PRIMARY KEY (RecordID),
  FOREIGN KEY (StudentID) REFERENCES Students(StudentID)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- Table: StudentArchive
-- --------------------------------------------------------
CREATE TABLE StudentArchive (
  ArchiveID INT NOT NULL AUTO_INCREMENT,
  StudentID INT NOT NULL,
  ArchiveYear INT NOT NULL,
  ArchiveMonth INT NOT NULL,
  TotalAttendance INT NOT NULL DEFAULT 0,
  TotalViolations INT NOT NULL DEFAULT 0,
  PendingViolations INT NOT NULL DEFAULT 0,
  ReviewedViolations INT NOT NULL DEFAULT 0,
  PRIMARY KEY (ArchiveID),
  FOREIGN KEY (StudentID) REFERENCES Students(StudentID)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
