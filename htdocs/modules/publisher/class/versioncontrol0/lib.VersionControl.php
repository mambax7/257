<?php
    /**
    *   This package is meant to manage archives of successive revisions of content articles stored in a database.
    *   It enables version control of the articles in similar way to what the CVS program provides.
    *   It is capable of storing in a separate database table all the versions of content articles that are stored in other tables.
    *   This version control package makes it possible to retrieve any of the past versions of a content article,
    *   the date of each revision and the identification of the author that submitted each revision.
    *   The base class archives content articles that may contain a variable number of fields of arbitrary length.
    *   It also computes an MD5 hash of the submitted content to verify whether the content has changed between revisions
    *   and avoids creating a new revision if nothing was changed.
    *   The class can retrieve one or more revisions of a content article and output the differences between two articles using
    *   the diff utility program
    *   This package comes with an optional sub-class that is capable of storing and retrieving the content articles in a
    *   compressed format using the gzip library, so it can use less database table space.
    *   This package version requires MySQLQueryContainer package to access to the database.
    *
    *   @author     $Author: Cornelius Bolten $
    **/

    /**
    *   This package is meant to manage archives of successive revisions of content articles stored in a database.
    *   It enables version control of the articles in similar way to what the CVS program provides.
    *   It is capable of storing in a separate database table all the versions of content articles that are stored in other tables.
    *   This version control package makes it possible to retrieve any of the past versions of a content article,
    *   the date of each revision and the identification of the author that submitted each revision.
    *   The base class archives content articles that may contain a variable number of fields of arbitrary length.
    *   It also computes an MD5 hash of the submitted content to verify whether the content has changed between revisions
    *   and avoids creating a new revision if nothing was changed.
    *   The class can retrieve one or more revisions of a content article and output the differences between two articles using
    *   the diff utility program
    *   This package comes with an optional sub-class that is capable of storing and retrieving the content articles in a
    *   compressed format using the gzip library, so it can use less database table space.
    *   This package version requires MySQLQueryContainer package to access to the database.
    *
    *   Usage is free for non-commercial work.
    *   For feedback, bug reports or enhancements please contact the author at
    *   c.bolten@grafiknews.de. Thanks a lot!
    *
    *   The Initial Developer of the Original Code is Cornelius Bolten.
    *   Portions created by Cornelius Bolten are Copyright (C) 2004 by Cornelius Bolten.
    *   All Rights Reserved.
    *
    *   benefits:
    *       - does automatically verisioning
    *       - can retrieve all old versions of a content-object
    *       - bulids hash to check if a new version is needed
    *         when checking in
    *       - does GZcompression if enabled
    *
    *   required:
    *       - this version expected MySQLQueryContainer-Class
    *         by Cornelius Bolten.
    *         this package can be retrieved via http://www.phpclasses.org/browse/package/1152.html
    *
    *
    *   @author     $Author: Cornelius Bolten $
    *   @version    $Revision: 1.14 $
    *   @package    VersionControl
    *   @example    example.VersionControl.php  example on how to get it work!
    *   @link       http://www.phpclasses.org/browse/package/1502.html  latest available version @ phpclasses.org
    **/
    class VersionControl
    {

        /**
        * @access private
        * @var object dbHandle
        */
        public $dbHandle;

        /**
        * @access private
        * @var string dbHandleName
        */
        public $dbHandleName;

        /**
        * @access private
        * @var string vc_tablename
        */
        public $vc_tablename;

        /**
        * @access private
        * @var string vc_error
        */
        public $vc_error;

        /**
        * @access private
        * @var boolean hasChanged
        */
        public $hasChanged;

        /**
        * @access private
        * @var boolean alreadExists
        */
        public $alreadExists;

        /**
        * @access private
        * @var string DiffToolBinaryPath
        */
        public $DiffToolBinaryPath;

        /**
        * editor of last checked-out contents
        * @access public
        * @var string contentEditor
        */
        public $contentEditor;

        /**
        * date of last checked-out contents
        * @access public
        * @var string contentDate
        */
        public $contentDate;

        /**
        * version of last checked-out contents
        * @access public
        * @var string contentVersion
        */
        public $contentVersion;


        /**
        *   constuctor
        *
        *   @access public
        *   @param  Object  valid database-object
        *   @param  String  valid database-connection-name
        *   @param  String  TableName of VersionTable
        */
        public function __construct($dbHandle, $dbHandleName, $vc_tablename)
        {
            $this->dbHandle     =   $dbHandle;
            $this->dbHandleName =   $dbHandleName;
            $this->vc_tablename =   $vc_tablename;
            $this->vc_error     =   [];
            $this->hasChanged   =   false;
            $this->alreadyExists=   false;
        }


        /**
        *   function returns last occured error
        *
        *   @access public
        *   @return String  error-message
        */
        public function getErrorMessage()
        {
            print_r($this->vc_error);
            unset($this->vc_error);
        }


        /**
        *   function encodes data-record
        *
        *   @access private
        *   @param  array   serialized Array of data
        *   return  string  encoded array
        */
        public function encode($serialized)
        {
            $encArray   =   addslashes($serialized);
            return ($encArray);
        }


        /**
        *   function decodes data-record
        *
        *   @access private
        *   @param  string  encoded array
        *   return  serialized array
        */
        public function decode($encArray)
        {
            $serialized =   stripslashes($encArray);
            return ($serialized);
        }


        /**
        *   function returns id of requested content (latest version)
        *
        *   @access private
        *   @param  integer     origin content-article-id
        *   @param  string      table name where content-article comes from originally
        *   @return int         latest version of content-article
        */
        public function getLatestVersionID($id, $table)
        {
            $query  = 'SELECT max(vc_content_version)
                       FROM `'.$this->vc_tablename.'`
                       WHERE `vc_content_id`    = \''.$id.'\'
                       AND  `vc_content_table`  = \''.$table.'\'';
            $version= $this->dbHandle->executeSelect($this->dbHandleName, $query);
            if (strlen($version[0][0])<=0) {
                // no record found with this id/table combination
                $this->vc_error[]   =   "getLatestVersion() -> no record found for $id::$table";
                return false;
            } else {
                $latest_content_version = $version[0][0];
                $query  =   'SELECT `vc_id`,`vc_content_version`
                             FROM `'.$this->vc_tablename.'`
                             WHERE `vc_content_version` = \''.$latest_content_version.'\'
                             AND `vc_content_id`        = \''.$id.'\'
                             AND `vc_content_table`     = \''.$table.'\'';
                $id     =   $this->dbHandle->executeSelect($this->dbHandleName, $query);
                if (!$id) {
                    // definitely error in SQL-Statement.
                    $this->vc_error[]   =   "getLatestVersion() -> error in SQL-Statement max($latest_content_version) doesn't exist in VersionTable";
                    return false;
                } else {
                    return $id[0][1];
                }
            }
        } // end getLatestVersionID


        /**
        *   function returns latest version of a content-object.
        *
        *   @access public
        *   @param  integer content-article-id
        *   @param  string  table name where content-article comes from originally
        *   @return string  latest version of content-article
        */
        public function getLatestVersion($id, $table)
        {
            if ($id && $table) {
                $latest_id = $this->getLatestVersionID($id, $table);
                if ($latest_id) {
                    $query  =   'SELECT `vc_content_data`, `vc_content_editor`, `vc_content_date`, `vc_content_version`, `vc_content_changes`
                                 FROM `'.$this->vc_tablename.'`
                                 WHERE `vc_id` = \''.$latest_id.'\'';
                    $content=   $this->dbHandle->executeSelect($this->dbHandleName, $query);
                    if (!$content) {
                        // definitely error in SQL-Statement.
                        $this->vc_error[]   = 'getLatestVersion() -> ' . $this->dbHandle->sqlError;
                        return false;
                    } else {
                        if (isset($oldContent)) {
                            $this->contentEditor    =   $oldContent[0]['vc_content_editor'];
                            $this->contentDate      =   $oldContent[0]['vc_content_date'];
                            $this->contentVersion   =   $oldContent[0]['vc_content_version'];
                            $this->contentChanges   =   stripslashes($oldContent[0]['vc_content_changes']);
                        }
                        return unserialize($this->decode($content[0]['vc_content_data']));
                    }
                } else {
                    $this->vc_error[]   = 'getLatestVersion() -> ' . $this->dbHandle->sqlError;
                    return false;
                }
            } else {
                $this->vc_error[]   = 'getLatestVersion() -> no id or table-name given';
                return false;
            }
        } // end getLatestVersion()


        /**
        *   function adds a new entry to VersionControl-Table.
        *
        *   @access public
        *   @param  integer     content-article-id
        *   @param  string      table name where content-article comes from originally
        *   @param  array       content-data to save, each field as array-value
        *   @param  integer     editor-id who edited this text
        *   @param  string      changes that have been made
        *   @return integer     if saved, return true
        */
        public function saveVersion($id, $table, $data, $editor, $changes= '')
        {
            $latest_id = $this->getLatestVersion($id, $table);
            if ($latest_id) {
                $result_data    =   $this->encode(serialize($data));
                $result_hash    =   md5($result_data);
                if ($this->checkHashChanged($id, $table, $result_hash) && !$this->checkHashExists($id, $table, $result_hash)) {
                    $vc_date    =   time();
                    $next_id    =   $this->contentVersion+1;
                    $query      =   'INSERT INTO `'.$this->vc_tablename.'` (`vc_content_id`,`vc_content_table`,`vc_content_version`,`vc_content_editor`,`vc_content_date`,`vc_content_data`,`vc_content_hash`,`vc_content_changes`)
                                     VALUES (\''.$id.'\', \''.$table.'\', \''.$next_id.'\', \''.$editor.'\', \''.$vc_date.'\',\''.$result_data.'\',\''.$result_hash.'\',\''.$changes.'\')';
                    echo $query . '<br>';
                    $result     =   $this->dbHandle->executeInsert($this->dbHandleName, $query);
                    if ($result) {
                        return true;
                    } else {
                        $this->vc_error[]   = 'saveVersion() -> ' . $this->dbHandle->sqlError;
                        return false;
                    }
                } else {
                    $this->vc_error[]   =   "saveVersion() -> did not add new Version. record wasn't changed or already exists in VersionControl (as old version).";
                    return false;
                }
            } else {
                $this->vc_error[]   = 'saveVersion() -> record will be initiated now via initVersion()';
                return $this->initVersion($id, $table, $data, $editor, $changes);
            }
        } // end saveVersion


        /**
        *   function adds a new entry to VersionControl-Table with version No.1
        *
        *   @access private
        *   @param  integer     content-article-id
        *   @param  string      table name where content-article comes from originally
        *   @param  string      content-data to save
        *   @param  integer     editor-id who edited this text
        *   @return integer     if saved, return true
        */
        public function initVersion($id, $table, $data, $editor, $changes)
        {
            $result_data    =   $this->encode(serialize($data));
            $changes        =   addslashes($changes);
            $result_hash    =   md5($result_data);
            $vc_date        =   time();
            $next_id        =   '1';
            $query          =   'INSERT INTO `'.$this->vc_tablename.'` (`vc_content_id`,`vc_content_table`,`vc_content_version`,`vc_content_editor`,`vc_content_date`,`vc_content_data`,`vc_content_hash`,`vc_content_changes`)
                                 VALUES (\''.$id.'\', \''.$table.'\', \''.$next_id.'\', \''.$editor.'\', \''.$vc_date.'\', \''.$result_data.'\', \''.$result_hash.'\',\''.$changes.'\')';

            $result     =   $this->dbHandle->executeInsert($this->dbHandleName, $query);
            if ($result) {
                return true;
            } else {
                $this->vc_error[]   = 'initVersion() -> ' . $this->dbHandle->sqlError;
            }
        } // end initVersion


        /**
        *   function returns an old version of a stored Record.
        *
        *   @access public
        *   @param  integer     content-article-id
        *   @param  string      table name where content-article comes from originally
        *   @param  string      requested version of content
        *   @return array       record in version $version
        */
        public function getOldVersion($id, $table, $version)
        {
            $query  = 'SELECT `vc_content_data`, `vc_content_editor`, `vc_content_date`, `vc_content_version`, `vc_content_changes`
                       FROM `'.$this->vc_tablename.'`
                       WHERE `vc_content_id`    = \''.$id.'\'
                       AND  `vc_content_table`  = \''.$table.'\'
                       AND  `vc_content_version`= \''.$version.'\'';
            $oldContent= $this->dbHandle->executeSelect($this->dbHandleName, $query);
            if (!$oldContent) {
                // no record found with this id/table combination
                $this->vc_error[]   =   "getOldVersion() -> version $version not found for $id::$table";
                return false;
            } else {
                $old_content_version = unserialize($this->decode($oldContent[0]['vc_content_data']));
                $this->contentEditor    =   $oldContent[0]['vc_content_editor'];
                $this->contentDate      =   $oldContent[0]['vc_content_date'];
                $this->contentVersion   =   $oldContent[0]['vc_content_version'];
                $this->contentChanges   =   stripslashes($oldContent[0]['vc_content_changes']);
                return $old_content_version;
            }
        } // end getOldVersion


        /**
        *   function returns a list of all available version-ids of a stored Record
        *
        *   @access public
        *   @param  integer     content id
        *   @param  string      table name where content-article comes from originally
        *   @return array       list of versions
        */
        public function getAllVersions($id, $table)
        {
            // build query
            $query      =   'SELECT `vc_content_version`
                             FROM   `'.$this->vc_tablename.'`
                             WHERE  `vc_content_id`     = \''.$id.'\'
                             AND    `vc_content_table`  = \''.$table.'\'';
            $versions   =   $this->dbHandle->executeSelect($this->dbHandleName, $query);
            if ($versions) {
                foreach ($versions as $version) {
                    $vList[] = $version['vc_content_version'];
                }
                return $vList;
            } else {
                $this->vc_error[]   =   "getAllVersions() -> no versions available ($id::$table)";
            }
        }


        /**
        *   function checks weather a records hash has changed (compared to latest old version)
        *
        *   @access private
        *   @param  integer     content id
        *   @param  string      table name where content-article comes from originally
        *   @param  string      hash of record
        *   @return bool        has changed
        */
        public function checkHashChanged($id, $table, $hash)
        {
            // get latest content_version
            $LatestVersion  =   $this->getLatestVersionID($id, $table);

            // build hash-query
            $query  =   'SELECT `vc_content_hash`
                         FROM   `'.$this->vc_tablename.'`
                         WHERE  `vc_content_id`     = \''.$id.'\'
                         AND    `vc_content_table`  = \''.$table.'\'
                         AND    `vc_content_version`= \''.$LatestVersion.'\'';

            // get hash from database
            $hashData   =   $this->dbHandle->executeSelect($this->dbHandleName, $query);
            if ($hashData) {
                $latestHashKey  =   $hashData[0]['vc_content_hash'];
                if ($latestHashKey   ==  $hash) {
                    $this->vc_error[]   = 'checkHashChanged() -> same hash. no change to submitted version';
                    return false;
                } else {
                    $this->hasChanged   =   true;
                    return true;
                }
            } else {
                $this->vc_error[]   =   "checkHashChanged() -> no hash found. ($id, $table)";
                return true;
            }
        }


        /**
        *   function checks weather a hash alread exists
        *
        *   @access private
        *   @param  integer     content id
        *   @param  string      table name where content-article comes from originally
        *   @param  string      hash of record
        *   @return bool        hash exists
        */
        public function checkHashExists($id, $table, $hash)
        {
            // build hash-query
            $query  =   'SELECT count(*)
                         FROM   `'.$this->vc_tablename.'`
                         WHERE  `vc_content_id`     = \''.$id.'\'
                         AND    `vc_content_table`  = \''.$table.'\'
                         AND    `vc_content_hash`   = \''.$hash.'\'';

            $hashData   =   $this->dbHandle->executeSelect($this->dbHandleName, $query);
            if ($hashData) {
                $count_result       =   $hashData[0][0];
                if ($count_result >= 1) {
                    $this->alreadExists =   true;
                    $this->vc_error[]   =   "checkHashExists() -> hash found in db ($id::$table::$hash)";
                    return true;
                } else {
                    $this->vc_error[]   =   "checkHashExists() -> hash not found ($id::$table::$hash)";
                    return false;
                }
            } else {
                $this->vc_error[]   =   "checkHashExists() -> query-error ($id::$table::$hash::$query)";
                return false;
            }
        }


        /**
        *   function sets path to DiffTool-Binary on the running system.
        *   default is "/usr/bin/diff"
        *
        *   @access public
        *   @param  string      path to DiffTool-Binary
        */
        public function setDiffPath($pathToDiffBinary = '/usr/bin/diff')
        {
            $this->DiffToolBinaryPath = $pathToDiffBinary;
        }


        /**
        *   function diff shows differences between versions of a text
        *
        *   @access public
        *   @param  integer     content id
        *   @param  string      table name where content-article comes from originally
        *   @param  string      version1 to check
        *   @param  string      version2 to check
        *   @return string      differences as diff-output
        */
        public function diff($id, $table, $version1, $version2, $dataField)
        {
            if (!$this->DiffToolBinaryPath) {
                $this->vc_error[]   = 'diff() -> DiffToolBinaryPath not set. please call setDiffPath(pathToDiffTool-Binary as string) first.';
                return false;
            }

            // create files with version-content
            $file1  =   tempnam('/tmp', 'libVC');
            $file2  =   tempnam('/tmp', 'libVC');
            $fh1    =   fopen($file1, 'w');
            $fh2    =   fopen($file2, 'w');

            // retrieve versions
            $c1 =   $this->getOldVersion($id, $table, $version1);
            $c2 =   $this->getOldVersion($id, $table, $version2);

            // write temporary files
            fwrite($fh1, $c1[$dataField]."\n");
            fwrite($fh2, $c2[$dataField]."\n");

            // close files
            fclose($fh1);
            fclose($fh2);

            // diff files..
            $handle = popen($this->DiffToolBinaryPath." $file1 $file2", 'r');
            $read = fread($handle, 2096);
            pclose($handle);

            // delete files
            unlink($file1);
            unlink($file2);

            return $read;
        }
    } // end class
