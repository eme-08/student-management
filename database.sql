CREATE DATABASE IF NOT EXISTS `tokotiti`;
USE `tokotiti`;

CREATE TABLE `tbl_student` (
  `student_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `course` varchar(100) NOT NULL,
  `year` varchar(50) NOT NULL,
  `section` varchar(50) NOT NULL,
  `date_of_birth` date NOT NULL,
  `gender` varchar(20) NOT NULL,
  `status` varchar(50) NOT NULL,
  `address` text NOT NULL,
  `religion` varchar(100) NOT NULL,
  `phone_number` varchar(20) NOT NULL,
  `email` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  PRIMARY KEY (`student_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `tbl_users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `user_type` varchar(20) NOT NULL,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert a default admin account
INSERT INTO `tbl_users` (`username`, `password`, `user_type`) VALUES
('admin', 'admin123', 'admin');
