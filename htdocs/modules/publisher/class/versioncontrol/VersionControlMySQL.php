<?php namespace XoopsModules\Publisher\versioncontrol;

/**
   MySQL-Implementation of version control Package
   by Cornelius Bolten, 2006.

   This is an example of an MySQL-Implementation of the
   version control package available on phpclasses.org.

   the abstract methods must be filled in each implementation.

   check the code and the method-documentation for required
   informations.

   this code is not just a hack, please be patient of errors.
   fixes and increments please to me@homeofevil.com. thanks a lot!

   required table-structure:

 CREATE TABLE sys_versioncontrol (
 id                  integer(11)     autoincrement NOT NULL,
 object_identifier   VARCHAR(250)    NOT NULL,
 object_version      INTEGER(5)      NOT NULL    default '1',
 object_editor       INTEGER(5),
 object_date         INTEGER(11),
 object_data         longtext        NOT NULL,
 object_hash         VARCHAR(32)     NOT NULL,
 object_informations longtext,
 PRIMARY KEY (id),
 UNIQUE (
 object_identifier,
 object_version,
 object_hash
 )
 )

 CREATE TABLE IF NOT EXISTS `sys_versioncontrol` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `object_identifier` varchar(250) NOT NULL,
 `object_version` int(5) NOT NULL DEFAULT '1',
 `object_editor` int(5) DEFAULT NULL,
 `object_date` int(11) DEFAULT NULL,
 `object_data` longtext NOT NULL,
 `object_hash` varchar(32) NOT NULL,
 `object_informations` longtext,
 PRIMARY KEY (`id`),
 UNIQUE KEY `object_identifier` (`object_identifier`,`object_version`,`object_hash`)
 ) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

   Usage is free for non-commercial work.
   For feedback, bug reports or enhancements please contact the author at
   me@homeofevil.com. Thanks a lot!

   The Initial Developer of the Original Code is Cornelius Bolten.
   Portions created by Cornelius Bolten are Copyright (C) 2004 by Cornelius Bolten.
   All Rights Reserved.


 @author     $Author: Cornelius Bolten $
 @version    $Revision: 1.0 $
 @package    VersionControl06
 @link       http://www.phpclasses.org/browse/package/2803.html  latest available version @ phpclasses.org
*/

class VersionControlMySQL extends VersionControl
{
//    private $oMySQLConnection;
    private $db;

    /**
     * constructor
     * call parent constructor and
     * connect to the database
     * @param \XoopsDatabase $db
     */
    public function __construct(\XoopsDatabase $db)
    {
        parent::__construct();
//        $this->MySQLConnection = mysql_connect('mysql_host', 'mysql_user', 'mysql_password')
//        or throw new RuntimeException($GLOBALS['xoopsDB']->error());
//        mysqli_select_db($GLOBALS['xoopsDB']->conn, 'my_database')
//        or throw new Exception('Could not select database');
        $this->db = $db;
    }

    /**
     * send a query to the mysql-database
     *
     * @param $sQuery
     * @return  array   fetched mysq-data
     * @access  public
     */
    public function query($sQuery)
    {
        $aData    = [];
        $iCounter = 0;

        if ($rQueryResult = $GLOBALS['xoopsDB']->queryF($sQuery)) {
            while ($aRow = $GLOBALS['xoopsDB']->fetchBoth($rQueryResult)) {
                $aData[$iCounter] = $aRow;
                ++$iCounter;
            }

            if ($iCounter <= 0) {
                throw new RuntimeException('no records found');
            }

            $GLOBALS['xoopsDB']->freeRecordSet($rQueryResult);
            return $aData;
        } else {
            throw new RuntimeException('no records found');
        }
    }

    /**
     * return the internal id of the
     * latest version of a stored object
     *
     * @param  string $_sIdentifier
     * @return integer internal id
     * @access public
     * @throws \Exception
     */
    public function getLatestVersionId($_sIdentifier)
    {
        $sQuery  = "
            SELECT  id
            FROM    ' . $this->db->prefix('sys_versioncontrol') . '
            WHERE   object_identifier = '$_sIdentifier'
            AND     object_version = (
                SELECT  max(object_version)
                FROM    ' . $this->db->prefix('sys_versioncontrol') . '
                WHERE   object_identifier = '$_sIdentifier'
            )
        ";
        $aResult = $this->query($sQuery);
        return $aResult[0]['id'];
    }

    /**
     * return the latest version number of a stored object
     *
     * @param  string $_sIdentifier
     * @return integer version
     * @access public
     * @throws \Exception
     */
    public function getLatestVersion_no($_sIdentifier)
    {
        $sQuery  = "
            SELECT  max(object_version) AS object_version
            FROM    ' . $this->db->prefix('sys_versioncontrol') . '
            WHERE   object_identifier = '$_sIdentifier'
        ";
        $aResult = $this->query($sQuery);
        return $aResult[0]['object_version'];
    }

    /**
     * return the latest version of a stored object
     *
     * @param  string $_sIdentifier
     * @return integer version
     * @access public
     * @throws \Exception
     */
    public function getLatestVersion($_sIdentifier)
    {
        $sQuery  = "
            SELECT  id, object_identifier, object_version,
                    object_editor, object_date, object_data, object_hash,
                    object_informations
            FROM    ' . $this->db->prefix('sys_versioncontrol') . '
            WHERE   object_identifier = '$_sIdentifier'
            AND     object_version = (
                SELECT  max(object_version)
                FROM    ' . $this->db->prefix('sys_versioncontrol') . '
                WHERE   object_identifier = '$_sIdentifier'
            )
        ";
        $aResult = $this->query($sQuery);
        return $aResult[0];
    }

    /**
     * return a version of a stored object
     *
     * @param  string  $_sIdentifier
     * @param  integer $_iVersionNo
     * @return integer version
     * @access public
     * @throws \Exception
     */
    public function getVersion($_sIdentifier, $_iVersionNo)
    {
        $sQuery  = "
            SELECT  id, object_identifier, object_version,
                    object_editor, object_date, object_data, object_hash,
                    object_informations
            FROM    ' . $this->db->prefix('sys_versioncontrol') . '
            WHERE   object_identifier = '$_sIdentifier'
            AND     object_version = '$_iVersionNo'
        ";
        $aResult = $this->query($sQuery);
        return $aResult[0];
    }

    /**
     * returns all versions of a stored object
     *
     * @param  string $_sIdentifier
     * @return void version-no's
     * @access public
     */
    public function getAllVersion($_sIdentifier)
    {
        throw new RuntimeException('this abstract method needs to be implemented..');
    }

    /**
     * check if a given hash already exists in the repository
     *
     * @param  string $_sIdentifier
     * @param  string $_sHash
     * @return boolean
     * @access public
     * @throws \Exception
     */
    public function objectHashExists($_sIdentifier, $_sHash)
    {
        $sQuery  = "
            SELECT  id
            FROM    ' . $this->db->prefix('sys_versioncontrol') . '
            WHERE   object_identifier = '$_sIdentifier'
            AND     object_hash = '$_sHash'
        ";
        $aResult = $this->query($sQuery);
        if ($aResult[0]['id'] >= 1) {
            return true;
        }
        return false;
    }

    /**
     * write a new record to the versioncontrol-repository
     *
     * @param  string  $_sIdentifier
     * @param  integer $_iDate
     * @param  integer $_iNextVersionNo
     * @param  string  $_sResultData
     * @param  string  $_sResultHash
     * @param  string  $_iEditor
     * @param  string  $_sInformations
     * @return boolean
     * @access public
     * @throws \Exception
     */
    public function write($_sIdentifier, $_iDate, $_iNextVersionNo, $_sResultData, $_sResultHash, $_iEditor, $_sInformations)
    {
        $sQuery = "
            INSERT INTO ' . $this->db->prefix('sys_versioncontrol') . ' (
                object_identifier, object_version, object_editor, object_date,
                object_data, object_hash, object_informations
            ) VALUES (
                '$_sIdentifier', $_iNextVersionNo, $_iEditor, $_iDate, '$_sResultData', '$_sResultHash', '$_sInformations'
            )
        ";
        $this->query($sQuery);
        return true;
    }
}
