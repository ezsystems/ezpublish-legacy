-- START: from 4.6.0 using DFS cluster setup
ALTER TABLE `ezdfsfile` CHANGE `datatype` `datatype` VARCHAR(255);
-- END: from 4.6.0 using DFS cluster setup

-- START: from 4.6.0 using DB cluster setup
ALTER TABLE `ezdbfile` CHANGE `datatype` `datatype` VARCHAR(255);
-- END: from 4.6.0 using DB cluster setup
