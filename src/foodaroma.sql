SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;


CREATE TABLE IF NOT EXISTS `admin` (
  `aid` int(5) NOT NULL AUTO_INCREMENT,
  `uname` varchar(25) NOT NULL,
  `pass` varchar(50) NOT NULL,
  PRIMARY KEY (`aid`),
  UNIQUE KEY `uname` (`uname`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

INSERT INTO `admin` (`aid`, `uname`, `pass`) VALUES
(4, 'admin', '5b4a1b9cabfe838d598173d2e891864eb0a45b5f');

CREATE TABLE IF NOT EXISTS `cart` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `iid` int(5) NOT NULL,
  `quantity` int(2) NOT NULL,
  `hid` int(5) NOT NULL,
  `cid` int(5) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `iid` (`iid`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=11 ;


CREATE TABLE IF NOT EXISTS `category` (
  `ctid` int(5) NOT NULL AUTO_INCREMENT,
  `pid` int(5) NOT NULL,
  `cname` varchar(25) NOT NULL,
  `hid` int(5) NOT NULL,
  PRIMARY KEY (`ctid`),
  UNIQUE KEY `name` (`cname`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=11 ;

INSERT INTO `category` (`ctid`, `pid`, `cname`, `hid`) VALUES
(1, 0, 'Dessert', 0),
(8, 1, 'Bon Bon', 2),
(10, 0, 'Main Course', 2);

CREATE TABLE IF NOT EXISTS `item` (
  `iid` int(5) NOT NULL AUTO_INCREMENT,
  `hid` int(5) NOT NULL,
  `name` varchar(25) NOT NULL,
  `category` int(5) DEFAULT NULL,
  `price` float NOT NULL,
  `discount` float NOT NULL,
  `special` int(1) NOT NULL,
  PRIMARY KEY (`iid`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=11 ;

INSERT INTO `item` (`iid`, `hid`, `name`, `category`, `price`, `discount`, `special`) VALUES
(4, 2, 'Pineapple Cake', 1, 150, 10, 0),
(7, 2, 'Butterscotch Pastry', 1, 125, 0, 0),
(8, 7, 'Butterscotch Pastry', 1, 200, 0, 0),
(9, 2, 'Hot Chocolate', 1, 100, 5, 1),
(10, 2, 'Biryani', 10, 300, 0, 0);

CREATE TABLE IF NOT EXISTS `orders` (
  `oid` int(5) NOT NULL AUTO_INCREMENT,
  `hid` int(5) NOT NULL,
  `cid` int(5) NOT NULL,
  `date` bigint(20) NOT NULL,
  `address` text NOT NULL,
  `status` varchar(15) NOT NULL,
  `omsg` text,
  `total` float NOT NULL,
  PRIMARY KEY (`oid`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=46 ;

INSERT INTO `orders` (`oid`, `hid`, `cid`, `date`, `address`, `status`, `omsg`, `total`) VALUES
(15, 2, 1, 1456762920, 'Anjuman Chawk', 'complete', '30 Minutes', 135),
(26, 2, 1, 1457700773, 'Malegaon', 'complete', '1 hour', 300),
(43, 2, 1, 1459509778, 'SmallVille', 'incomplete', NULL, 735),
(44, 2, 1, 1459598846, 'Malegaon', 'incomplete', NULL, 270),
(45, 2, 28, 1459673633, 'Malegaon', 'request', NULL, 135);

CREATE TABLE IF NOT EXISTS `orders_items` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `oid` int(5) NOT NULL,
  `iid` int(5) NOT NULL,
  `quantity` float NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3995 ;

INSERT INTO `orders_items` (`id`, `oid`, `iid`, `quantity`) VALUES
(6, 15, 4, 1),
(3994, 45, 4, 1),
(3992, 43, 10, 2),
(3993, 44, 4, 2),
(3976, 33, 4, 1),
(3964, 26, 4, 2),
(3991, 43, 4, 1);

CREATE TABLE IF NOT EXISTS `review` (
  `rid` int(5) NOT NULL AUTO_INCREMENT,
  `hid` int(5) NOT NULL,
  `cid` int(5) NOT NULL,
  `msg` text NOT NULL,
  `date` bigint(20) NOT NULL,
  PRIMARY KEY (`rid`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=13 ;

INSERT INTO `review` (`rid`, `hid`, `cid`, `msg`, `date`) VALUES
(11, 2, 1, 'Hello?', 1459399929),
(7, 2, 4, 'Hey there!', 1457451636),
(4, 2, 1, 'Hello!\r\n', 1457285392),
(9, 2, 1, 'sdsfsdf', 1458666578),
(10, 2, 1, 'dafuq', 1458666623),
(12, 2, 1, 'Hello! Testing!', 1459611147);

CREATE TABLE IF NOT EXISTS `stats` (
  `sid` int(5) NOT NULL AUTO_INCREMENT,
  `hid` int(5) NOT NULL,
  `des` text NOT NULL,
  `oaccepted` int(4) NOT NULL,
  `orejected` int(4) NOT NULL,
  `status` varchar(20) NOT NULL,
  PRIMARY KEY (`sid`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=11 ;

INSERT INTO `stats` (`sid`, `hid`, `des`, `oaccepted`, `orejected`, `status`) VALUES
(1, 2, 'Fast Delivery!', 6, 8, 'accepted'),
(8, 24, '', 0, 0, 'rejected'),
(9, 25, '', 0, 0, 'accepted');

CREATE TABLE IF NOT EXISTS `user` (
  `uid` int(5) NOT NULL AUTO_INCREMENT,
  `uname` varchar(30) NOT NULL,
  `pass` varchar(50) NOT NULL,
  `name` varchar(80) NOT NULL,
  `role` int(1) NOT NULL,
  `email` varchar(50) NOT NULL,
  `contact` decimal(10,0) NOT NULL,
  `city` varchar(20) NOT NULL,
  `area` varchar(40) NOT NULL,
  PRIMARY KEY (`uid`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=29 ;

INSERT INTO `user` (`uid`, `uname`, `pass`, `name`, `role`, `email`, `contact`, `city`, `area`) VALUES
(1, 'tooba', '1e09e2780e74cbc6317355f6d79b810002f07e1f', 'Tooba', 1, 'tooba.uroob@gmail.com', '9975812992', 'Malegaon', 'Mahesh Nagar'),
(2, 'bigbelly', '1e09e2780e74cbc6317355f6d79b810002f07e1f', 'Big Belly Burger', 0, 'bigbelly@gmail.com', '9999999999', 'Central City', 'Star Labs'),
(24, 'elite', '1e09e2780e74cbc6317355f6d79b810002f07e1f', 'elite', 0, 'elite@gmail.com', '1234567', 'nashik', ''),
(25, 'cojitters', '1e09e2780e74cbc6317355f6d79b810002f07e1f', 'Cojitters', 0, 'cojitters@dc.com', '9876543567', 'Central City', ''),
(28, 'aqsa', '1e09e2780e74cbc6317355f6d79b810002f07e1f', 'Aqsa', 1, 'osh.shaikh@gmail.com', '9113456789', 'malegaon', 'Mahesh Nagar');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
