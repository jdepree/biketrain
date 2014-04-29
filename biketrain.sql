/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `biketrain`
--

-- --------------------------------------------------------

--
-- Table structure for table `recorded_points`
--

CREATE TABLE IF NOT EXISTS `recorded_points` (
  `rp_id` int(11) NOT NULL AUTO_INCREMENT,
  `rp_user_id` int(11) NOT NULL,
  `rp_lat` float NOT NULL,
  `rp_lng` float NOT NULL,
  `rp_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`rp_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `routes`
--

CREATE TABLE IF NOT EXISTS `routes` (
  `route_id` int(11) NOT NULL AUTO_INCREMENT,
  `route_start_location` int(11) NOT NULL,
  `route_end_location` int(11) NOT NULL,
  `route_path` varchar(5000) NOT NULL,
  `route_levels` varchar(1000) NOT NULL,
  `route_distance` float NOT NULL,
  `route_label` varchar(200) NOT NULL,
  PRIMARY KEY (`route_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `trains`
--

CREATE TABLE IF NOT EXISTS `trains` (
  `train_id` int(11) NOT NULL AUTO_INCREMENT,
  `train_start_time` datetime NOT NULL,
  `train_end_time` datetime NOT NULL,
  `train_route_id` int(11) NOT NULL,
  `train_leader_id` int(11) NOT NULL,
  PRIMARY KEY (`train_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `train_riders`
--

CREATE TABLE IF NOT EXISTS `train_riders` (
  `tr_train_id` int(11) NOT NULL,
  `tr_user_id` int(11) NOT NULL,
  `tr_join_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `tr_left_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`tr_train_id`,`tr_user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_email` varchar(100) NOT NULL,
  `user_password` varchar(32) NOT NULL,
  `user_first_name` varchar(50) NOT NULL,
  `user_last_name` varchar(50) NOT NULL,
  `user_facebook_username` varchar(100) NOT NULL,
  `user_display_level` enum('never','friends','friendsOfFriends','everyone') NOT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `user_connections`
--

CREATE TABLE IF NOT EXISTS `user_connections` (
  `uc_first` int(11) NOT NULL,
  `uc_second` int(11) NOT NULL,
  `uc_reciprocated` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`uc_first`,`uc_second`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `waypoints`
--

CREATE TABLE IF NOT EXISTS `waypoints` (
  `loc_id` int(11) NOT NULL AUTO_INCREMENT,
  `loc_lat` float NOT NULL,
  `loc_lng` float NOT NULL,
  `loc_label` varchar(100) NOT NULL,
  PRIMARY KEY (`loc_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
