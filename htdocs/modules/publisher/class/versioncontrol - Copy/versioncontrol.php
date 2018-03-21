<?php

/*
*   specialized exception-classes
*   used in this abstract class.
*/

/*
*   Submitted Object Version already Exists in Version Control-Repository
*/

class ObjectVersionAlreadyExistsException extends Exception
{
}

/*
*   Object-Identifier wasn't in Version Control-Repository
*/

class ObjectIdentifierNotFoundException extends Exception
{
}

/*
*   Content management is a very popular type of Web application.
*   Many Web sites are generated automatically by content management systems that let
*   the users publish rich text articles that are stored in the content management database.
*
*   More advanced content management systems let multiple the users colaborate in the edition
*   of the same article, making it possible for different users correct or update the same
*   article after the first revision. Some even let the users list the history of revisions
*   and compare different versions to see what changes where made. This capability is known
*   as version control.
*
*   This class can provide version control capabilities to content management systems that do
*   not have such capabilities. It can successive revisions of articles in a separate table.
*
*   This abstract Class is a complete rebuild of the nominated package libVersionControl which can be
*   found on phpclasses.org (http://www.phpclasses.org/browse/package/1502.html).
*
*   Target of the rebuild is to offer an database-independant version-control-package
*   which can be accessed from not only MYSQL.
*
*   Usage is free for non-commercial work.
*   For feedback, bug reports or enhancements please contact the author at
*   me@homeofevil.com. Thanks a lot!
*
*   The Initial Developer of the Original Code is Cornelius Bolten.
*   Portions created by Cornelius Bolten are Copyright (C) 2004 by Cornelius Bolten.
*   All Rights Reserved.
*
*   benefits:
*       - does automatically verisioning
*       - can retrieve all old versions of a content-object
*       - builds hash to check if a new version is needed
*         when checking in
*
*   required:
*       - implementation for your storage-system (MYSQL, Postgres, Oracle, etc.)
*
*
*   @author     $Author: Cornelius Bolten $
*   @version    $Revision: 1.0 $
*   @package    VersionControl06
*   @link       http://www.phpclasses.org/browse/package/2803.html  latest available version @ phpclasses.org
**/

abstract class VersionControl
{
    /**
     * return the internal id of the
     * latest version of a stored object
     *
     * @param  string $_sIdentifier
     * @return integer internal id
     * @access public
     */
    abstract public function get_latest_version_id($_sIdentifier);

    /**
     * return the latest version number of a stored object
     *
     * @param  string $_sIdentifier
     * @return integer version
     * @access public
     */
    abstract public function get_latest_version_no($_sIdentifier);

    /**
     * return the latest version of a stored object
     *
     * @param  string $_sIdentifier
     * @return integer version
     * @access public
     */
    abstract public function get_latest_version($_sIdentifier);

    /**
     * return a version of a stored object
     *
     * @param  string  $_sIdentifier
     * @param  integer $_iVersionNo
     * @return integer version
     * @access public
     */
    abstract public function get_version($_sIdentifier, $_iVersionNo);

    /**
     * returns all versions of a stored object
     *
     * @param  string $_sIdentifier
     * @return array    version-no's
     * @access public
     */
    abstract public function get_all_version($_sIdentifier);

    /**
     * check if a given hash already exists in the repository
     *
     * @param  string $_sIdentifier
     * @param  string $_sHash
     * @return boolean
     * @access public
     */
    abstract public function object_hash_exists($_sIdentifier, $_sHash);

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
     */
    abstract public function write($_sIdentifier, $_iDate, $_iNextVersionNo, $_sResultData, $_sResultHash, $_iEditor, $_sInformations);

    /**
     *   constuctor
     *
     * @access public
     */
    public function __construct()
    {
        $this->bHasChanged    = false;
        $this->bAlreadyExists = false;
    }

    /**
     *   function encodes data-record
     *
     * @access private
     * @param  array   serialized Array of data
     *   return  string  encoded array
     */
    private function encode($sSerializedArray)
    {
        return addslashes($sSerializedArray);
    }

    /**
     *   function decodes data-record
     *
     * @access private
     * @param  string  encoded array
     *   return  serialized array
     */
    private function decode($sSerializedArray)
    {
        return stripslashes($sSerializedArray);
    }

    /**
     *   get the next version number of an stored object
     *
     * @param  string $_sIdentifier
     * @param  string  encoded array
     * @access private
     * @return integer next version number
     */
    private function get_next_version_no(&$_sIdentifier)
    {
        return $this->get_latest_version_no($_sIdentifier) + 1;
    }

    /**
     * submit an object to the versioncontrol
     *
     * @param  string  $_sIdentifier
     * @param  array   $_aData
     * @param  integer $_iEditor
     * @param  string  $_sInformations OPTIONAL
     * @return integer  internal id
     * @access public
     */
    final public function submit_version(&$_sIdentifier, &$_aData, &$_iEditor, $_sInformations = '')
    {
        $iLatestId = $this->get_latest_version_id($_sIdentifier);

        if ($iLatestId) {
            $sResultData = $this->encode(serialize($_aData));
            $sResultHash = md5($sResultData);
            if ($this->object_hash_changed($_sIdentifier, $sResultHash) && !$this->object_hash_exists($_sIdentifier, $sResultHash)) {
                $iDate          = time();
                $iNextVersionNo = $this->get_next_version_no($_sIdentifier);
                return $this->write($_sIdentifier, $iDate, $iNextVersionNo, $sResultData, $sResultHash, $_iEditor, $_sInformations);
            } else {
                throw new ObjectVersionAlreadyExistsException();
            }
        } else {
            return $this->init_version($_sIdentifier, $_aData, $_iEditor, $_sInformations);
        }
    }

    /**
     * init an object to the versioncontrol-repository
     *
     * @param  string  $_sIdentifier
     * @param  array   $_aData
     * @param  integer $_iEditor
     * @param  string  $_sInformations OPTIONAL
     * @return integer  internal id
     * @access private
     */
    final private function init_version($_sIdentifier, $_aData, $_iEditor, $_sInformations = '')
    {
        $sResultData    = $this->encode(serialize($_aData));
        $sResultHash    = md5($sResultData);
        $iDate          = time();
        $iNextVersionNo = 1;
        return $this->write($_sIdentifier, $iDate, $iNextVersionNo, $sResultData, $sResultHash, $_iEditor, $_sInformations);
    }

    /**
     * check, if an object has changed (since last version)
     *
     * @param  string $_sIdentifier
     * @param  string $_sHash
     * @return boolean
     * @access private
     */
    final private function object_hash_changed($_sIdentifier, $_sHash)
    {
        $aLatestVersion = $this->get_latest_version($_sIdentifier);
        if ($aLatestVersion) {
            if ($aLatestVersion['hash'] == $_sHash) {
                return false;
            } else {
                return true;
            }
        } else {
            throw new ObjectIdentifierNotFoundException();
        }
    }
}
