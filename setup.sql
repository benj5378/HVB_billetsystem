-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Feb 03, 2021 at 06:36 PM
-- Server version: 8.0.23
-- PHP Version: 8.0.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

--
-- Database: `hvb`
--

-- --------------------------------------------------------

--
-- Table structure for table `hvb_departures`
--

CREATE TABLE `hvb_departures` (
  `departure_id` int NOT NULL,
  `departure_type` enum('outbond','homebond') NOT NULL,
  `trains__train_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hvb_events`
--

CREATE TABLE `hvb_events` (
  `event_id` int NOT NULL,
  `event_type` enum('særtog','plantog') NOT NULL,
  `event_date` date DEFAULT NULL,
  `event_name` varchar(255) DEFAULT NULL,
  `event_description` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hvb_passengers`
--

CREATE TABLE `hvb_passengers` (
  `passenger_id` int NOT NULL,
  `ticket_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `tickets__ticket_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hvb_payments`
--

CREATE TABLE `hvb_payments` (
  `payment_id` int NOT NULL,
  `payment_id_dibs` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `payment.reservation.created_datetime` timestamp NULL DEFAULT NULL,
  `payment.charge.created_datetime` timestamp NULL DEFAULT NULL,
  `payment_consumer_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `payment_email_address` varchar(255) DEFAULT NULL,
  `payment_email_sent` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hvb_scans`
--

CREATE TABLE `hvb_scans` (
  `scan_id` int NOT NULL,
  `scan_datetime` datetime DEFAULT NULL,
  `tickets__ticket_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hvb_stops`
--

CREATE TABLE `hvb_stops` (
  `stop_id` int NOT NULL,
  `stop_name` varchar(255) NOT NULL,
  `stop_departure_time` datetime NOT NULL,
  `departures__departure_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hvb_ticket`
--

CREATE TABLE `hvb_ticket` (
  `ticket_id` int NOT NULL,
  `ticket_qr` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `ticket_valid` tinyint(1) NOT NULL DEFAULT '0',
  `ticket_reserved_compartments` tinyint DEFAULT NULL,
  `ticket_start__stops__stop_id` int NOT NULL,
  `ticket_end__stops__stop_id` int NOT NULL,
  `payments__payment_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hvb_trains`
--

CREATE TABLE `hvb_trains` (
  `train_id` int NOT NULL,
  `train_seats` tinyint NOT NULL DEFAULT '1',
  `train_locomotive` enum('motor','damp') NOT NULL,
  `train_compartments` tinyint NOT NULL,
  `events__event_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `hvb_departures`
--
ALTER TABLE `hvb_departures`
  ADD PRIMARY KEY (`departure_id`),
  ADD KEY `fk_hvb_departures_hvb_trains` (`trains__train_id`);

--
-- Indexes for table `hvb_events`
--
ALTER TABLE `hvb_events`
  ADD PRIMARY KEY (`event_id`);

--
-- Indexes for table `hvb_passengers`
--
ALTER TABLE `hvb_passengers`
  ADD PRIMARY KEY (`passenger_id`),
  ADD KEY `fk_hvb_passengers_hvb_ticket` (`tickets__ticket_id`);

--
-- Indexes for table `hvb_payments`
--
ALTER TABLE `hvb_payments`
  ADD PRIMARY KEY (`payment_id`);

--
-- Indexes for table `hvb_scans`
--
ALTER TABLE `hvb_scans`
  ADD PRIMARY KEY (`scan_id`),
  ADD KEY `fk_hvb_scans_hvb_ticket` (`tickets__ticket_id`);

--
-- Indexes for table `hvb_stops`
--
ALTER TABLE `hvb_stops`
  ADD PRIMARY KEY (`stop_id`),
  ADD KEY `fk_hvb_stops_hvb_departures` (`departures__departure_id`);

--
-- Indexes for table `hvb_ticket`
--
ALTER TABLE `hvb_ticket`
  ADD PRIMARY KEY (`ticket_id`),
  ADD UNIQUE KEY `ticket_qr` (`ticket_qr`),
  ADD KEY `fk_hvb_ticket_hvb_stops` (`ticket_start__stops__stop_id`),
  ADD KEY `fk_hvb_ticket_hvb_stops_0` (`ticket_end__stops__stop_id`),
  ADD KEY `fk_hvb_ticket_hvb_payments` (`payments__payment_id`);

--
-- Indexes for table `hvb_trains`
--
ALTER TABLE `hvb_trains`
  ADD PRIMARY KEY (`train_id`),
  ADD KEY `fk_hvb_trains_hvb_events` (`events__event_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `hvb_departures`
--
ALTER TABLE `hvb_departures`
  MODIFY `departure_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `hvb_events`
--
ALTER TABLE `hvb_events`
  MODIFY `event_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `hvb_passengers`
--
ALTER TABLE `hvb_passengers`
  MODIFY `passenger_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `hvb_payments`
--
ALTER TABLE `hvb_payments`
  MODIFY `payment_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `hvb_scans`
--
ALTER TABLE `hvb_scans`
  MODIFY `scan_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `hvb_stops`
--
ALTER TABLE `hvb_stops`
  MODIFY `stop_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `hvb_ticket`
--
ALTER TABLE `hvb_ticket`
  MODIFY `ticket_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `hvb_trains`
--
ALTER TABLE `hvb_trains`
  MODIFY `train_id` int NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `hvb_departures`
--
ALTER TABLE `hvb_departures`
  ADD CONSTRAINT `fk_hvb_departures_hvb_trains` FOREIGN KEY (`trains__train_id`) REFERENCES `hvb_trains` (`train_id`);

--
-- Constraints for table `hvb_passengers`
--
ALTER TABLE `hvb_passengers`
  ADD CONSTRAINT `fk_hvb_passengers_hvb_ticket` FOREIGN KEY (`tickets__ticket_id`) REFERENCES `hvb_ticket` (`ticket_id`);

--
-- Constraints for table `hvb_scans`
--
ALTER TABLE `hvb_scans`
  ADD CONSTRAINT `fk_hvb_scans_hvb_ticket` FOREIGN KEY (`tickets__ticket_id`) REFERENCES `hvb_ticket` (`ticket_id`);

--
-- Constraints for table `hvb_stops`
--
ALTER TABLE `hvb_stops`
  ADD CONSTRAINT `fk_hvb_stops_hvb_departures` FOREIGN KEY (`departures__departure_id`) REFERENCES `hvb_departures` (`departure_id`);

--
-- Constraints for table `hvb_ticket`
--
ALTER TABLE `hvb_ticket`
  ADD CONSTRAINT `fk_hvb_ticket_hvb_payments` FOREIGN KEY (`payments__payment_id`) REFERENCES `hvb_payments` (`payment_id`),
  ADD CONSTRAINT `fk_hvb_ticket_hvb_stops` FOREIGN KEY (`ticket_start__stops__stop_id`) REFERENCES `hvb_stops` (`stop_id`),
  ADD CONSTRAINT `fk_hvb_ticket_hvb_stops_0` FOREIGN KEY (`ticket_end__stops__stop_id`) REFERENCES `hvb_stops` (`stop_id`);

--
-- Constraints for table `hvb_trains`
--
ALTER TABLE `hvb_trains`
  ADD CONSTRAINT `fk_hvb_trains_hvb_events` FOREIGN KEY (`events__event_id`) REFERENCES `hvb_events` (`event_id`);
COMMIT;


-- Set PLANTOG row
INSERT INTO `hvb_events` (`event_id`, `event_type`, `event_date`, `event_description`) VALUES ('1', 'plantog', NULL, NULL);
