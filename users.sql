
--
-- Database: `%db-name%`
--


--
-- Creating users login_user(hasD78PwD9login) and tasker(BidEd61oWl)
--

CREATE USER 'login_user'@'%db-host%' IDENTIFIED BY 'hasD78PwD9login' ;
GRANT SELECT, INSERT, UPDATE ON `%db-name%`.`users` TO 'login_user'@'%db-host%' ;
GRANT SELECT, INSERT ON `%db-name%`.`StudentSenior` TO 'login_user'@'%db-host%' ;
GRANT SELECT, INSERT ON `%db-name%`.`StudentVolunteer` TO 'login_users'@'%db-host%' ;

CREATE USER 'tasker'@'%db-host%' IDENTIFIED BY 'BidEd61oWl' ;
GRANT SELECT, INSERT,UPDATE ON `%db-name%`.`Companies` TO 'tasker'@'%db-host%' ;
GRANT SELECT, INSERT, DELETE ON `%db-name%`.`CompanyStudentAllocations` TO 'tasker'@'%db-host%' ;
GRANT SELECT, INSERT, DELETE ON `%db-name%`.`Notifications` TO 'tasker'@'%db-host%' ;
GRANT SELECT, INSERT ON `%db-name%`.`Probability Index` TO 'tasker'@'%db-host%' ;
GRANT SELECT, INSERT,UPDATE ON `%db-name%`.`Responses` TO 'tasker'@'%db-host%' ;
GRANT SELECT, INSERT ON `%db-name%`.`SponsorshipCategories` TO 'tasker'@'%db-host%' ;
GRANT SELECT, UPDATE ON `%db-name%`.`StudentSenior` TO 'tasker'@'%db-host%' ;
GRANT SELECT, UPDATE ON `%db-name%`.`StudentVolunteer` TO 'tasker'@'%db-host%' ;

-- --------------------------------------------------------
