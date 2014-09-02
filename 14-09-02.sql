-- phpMyAdmin SQL Dump
-- version 3.2.3
-- http://www.phpmyadmin.net
--
-- ȣ��Ʈ: localhost
-- ó���� �ð�: 14-09-02 19:30 
-- ���� ����: 5.1.41
-- PHP ����: 5.2.12

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- �����ͺ��̽�: `study`
--

-- --------------------------------------------------------

--
-- ���̺� ���� `evaluate`
--

CREATE TABLE IF NOT EXISTS `evaluate` (
  `IDevaluate` int(11) NOT NULL AUTO_INCREMENT,
  `evaluateName` text COLLATE utf8_unicode_ci NOT NULL,
  `picPath1` text COLLATE utf8_unicode_ci NOT NULL,
  `picPath2` text COLLATE utf8_unicode_ci NOT NULL,
  `picPath3` text COLLATE utf8_unicode_ci NOT NULL,
  `picPath4` text COLLATE utf8_unicode_ci NOT NULL,
  `picPath5` text COLLATE utf8_unicode_ci NOT NULL,
  `unit` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`IDevaluate`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=3 ;

--
-- ���̺��� ���� ������ `evaluate`
--

INSERT INTO `evaluate` (`IDevaluate`, `evaluateName`, `picPath1`, `picPath2`, `picPath3`, `picPath4`, `picPath5`, `unit`) VALUES
(1, '���ӽǷ�', '1', '2', '3', '4', '5', '��'),
(2, '������', '1', '2', '3', '4', '5', '����');

-- --------------------------------------------------------

--
-- ���̺� ���� `friend`
--

CREATE TABLE IF NOT EXISTS `friend` (
  `IDfriend` int(11) NOT NULL AUTO_INCREMENT,
  `userID1` int(11) NOT NULL,
  `name1` text COLLATE utf8_unicode_ci NOT NULL,
  `userID2` int(11) NOT NULL,
  `name2` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`IDfriend`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=5 ;

--
-- ���̺��� ���� ������ `friend`
--

INSERT INTO `friend` (`IDfriend`, `userID1`, `name1`, `userID2`, `name2`) VALUES
(1, 1, '����ȯ', 2, '�����ι�'),
(2, 1, '����ȯ', 3, '�����'),
(3, 1, '����ȯ', 4, '�̱���'),
(4, 3, '�����', 4, '�̱���');

-- --------------------------------------------------------

--
-- ���̺� ���� `reply`
--

CREATE TABLE IF NOT EXISTS `reply` (
  `IDreply` int(11) NOT NULL AUTO_INCREMENT,
  `userEvaluateID` int(11) NOT NULL,
  `userID` int(11) NOT NULL,
  `name` text COLLATE utf8_unicode_ci NOT NULL,
  `body` text COLLATE utf8_unicode_ci NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`IDreply`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=12 ;

--
-- ���̺��� ���� ������ `reply`
--

INSERT INTO `reply` (`IDreply`, `userEvaluateID`, `userID`, `name`, `body`, `date`) VALUES
(5, 56, 1, '����ȯ', '��������������', '2014-08-28 16:34:00'),
(4, 56, 1, '����ȯ', '��������', '2014-08-28 16:33:57'),
(6, 56, 3, '�����', '������ �̰� ���� �Ѥ�', '2014-08-28 16:38:28'),
(7, 56, 3, '�����', '��������', '2014-08-28 16:44:08'),
(8, 56, 3, '�����', '������', '2014-08-28 16:44:09'),
(9, 56, 3, '�����', '����������', '2014-08-28 16:44:12'),
(10, 56, 3, '�����', '��������', '2014-08-28 16:45:10'),
(11, 56, 3, '�����', '�̰����縶�����̿�', '2014-08-28 16:45:15');

-- --------------------------------------------------------

--
-- ���̺� ���� `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `IDuser` int(11) NOT NULL AUTO_INCREMENT,
  `username` text COLLATE utf8_unicode_ci NOT NULL,
  `password` text COLLATE utf8_unicode_ci NOT NULL,
  `gender` text COLLATE utf8_unicode_ci NOT NULL,
  `dateofbirth` date NOT NULL,
  `name` text COLLATE utf8_unicode_ci NOT NULL,
  `registerDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `viewCnt` int(11) NOT NULL,
  `picPath` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`IDuser`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=5 ;

--
-- ���̺��� ���� ������ `user`
--

INSERT INTO `user` (`IDuser`, `username`, `password`, `gender`, `dateofbirth`, `name`, `registerDate`, `viewCnt`, `picPath`) VALUES
(1, '1', '1', 'm', '1990-02-25', '����ȯ', '0000-00-00 00:00:00', 0, ''),
(2, '2', '2', '��', '1994-08-14', '�����ι�', '0000-00-00 00:00:00', 0, ''),
(3, '3', '3', 'm', '0000-00-00', '�����', '2014-08-25 20:08:16', 0, ''),
(4, '4', '4', 'm', '0000-00-00', '�̱���', '2014-08-25 20:08:16', 0, '');

-- --------------------------------------------------------

--
-- ���̺� ���� `userevaluate`
--

CREATE TABLE IF NOT EXISTS `userevaluate` (
  `IDuserEvaluate` int(11) NOT NULL AUTO_INCREMENT,
  `userID` int(11) NOT NULL,
  `username` text COLLATE utf8_unicode_ci NOT NULL,
  `evaluateID` int(11) NOT NULL,
  `evaluateName` text COLLATE utf8_unicode_ci NOT NULL,
  `view` tinyint(1) NOT NULL,
  `sum` int(11) NOT NULL,
  `count` int(11) NOT NULL,
  PRIMARY KEY (`IDuserEvaluate`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=68 ;

--
-- ���̺��� ���� ������ `userevaluate`
--

INSERT INTO `userevaluate` (`IDuserEvaluate`, `userID`, `username`, `evaluateID`, `evaluateName`, `view`, `sum`, `count`) VALUES
(62, 3, '', 1, '', 0, 5, 1),
(63, 3, '', 2, '', 0, 4, 1),
(64, 4, '', 2, '', 0, 4, 1),
(65, 4, '', 1, '', 0, 4, 1),
(66, 2, '', 1, '', 0, 1, 1),
(67, 1, '', 1, '', 0, 1, 1);

-- --------------------------------------------------------

--
-- ���̺� ���� `useruserevaluate`
--

CREATE TABLE IF NOT EXISTS `useruserevaluate` (
  `IDuserUserEvaluate` int(11) NOT NULL AUTO_INCREMENT,
  `userID1` int(11) NOT NULL,
  `userID2` int(11) NOT NULL,
  `evaluateID` int(11) NOT NULL,
  `rate` int(11) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`IDuserUserEvaluate`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=105 ;

--
-- ���̺��� ���� ������ `useruserevaluate`
--

INSERT INTO `useruserevaluate` (`IDuserUserEvaluate`, `userID1`, `userID2`, `evaluateID`, `rate`, `date`) VALUES
(99, 3, 1, 1, 5, '2014-08-28 18:17:12'),
(100, 3, 1, 2, 4, '2014-08-28 18:16:51'),
(101, 4, 1, 2, 4, '2014-08-28 18:17:26'),
(102, 4, 1, 1, 4, '2014-08-28 18:17:26'),
(103, 2, 1, 1, 1, '2014-09-02 13:03:23'),
(104, 1, 3, 1, 1, '2014-09-02 19:25:41');
