DROP TABLE IF EXISTS #__burstinfo;
DROP TABLE IF EXISTS #__tree;
DROP TABLE IF EXISTS #__bursts;
DROP EVENT IF EXISTS #__burstcheck;
DROP PROCEDURE IF EXISTS #__check;
DROP PROCEDURE IF EXISTS #__addupdate;
DROP PROCEDURE IF EXISTS #__node;
DROP PROCEDURE IF EXISTS #__zigzag;
DROP PROCEDURE IF EXISTS #__zigzig;
DROP PROCEDURE IF EXISTS #__zig;
DROP TRIGGER IF EXISTS #__info_updated;

SET GLOBAL event_scheduler = ON;
SET GLOBAL max_sp_recursion_depth = 255;

CREATE TABLE #__burstinfo (
`id` int(1) NOT NULL AUTO_INCREMENT,
`visits` int(20) NOT NULL,
`timeframe` DATETIME NOT NULL,
`last_check` DATETIME DEFAULT CURRENT_TIMESTAMP,
PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1;

 CREATE TABLE #__bursts(
`id` int(11) NOT NULL,
`position` int(11) NOT NULL DEFAULT 0,
`title`    VARCHAR(255),
PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1;

 CREATE TABLE #__tree (
`id` int(11) NOT NULL,
`l` int(11) NOT NULL DEFAULT 0,
`r` int(11) NOT NULL DEFAULT 0,
`parent` int(11) NOT NULL DEFAULT 0,
`hitsold` int(20) NOT NULL DEFAULT 0,
`hitsnew` int(20) NOT NULL DEFAULT 0,
`timeofburst` TIMESTAMP ,
PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1;

INSERT INTO #__burstinfo (`visits`,`timeframe`) VALUES
(10,'0000-00-00 00:05:00') ;

SET @timeframetemp := (SELECT timeframe FROM #__burstinfo );

CREATE EVENT #__burstcheck
ON SCHEDULE
EVERY MINUTE(CONVERT(@timeframetemp , DATETIME)) MINUTE
DO
CALL #__check();