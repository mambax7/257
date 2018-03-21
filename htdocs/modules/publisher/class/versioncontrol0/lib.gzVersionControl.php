<?php

    /**
    *   $RCSfile: lib.gzVersionControl.php,v $
    *   @author   $Author: Cornelius Bolten $
    *   @version  $Revision: 1.2 $
    *
    *   @description
    *       this is the gz-Version of VersionControl
    *       The sub-class is capable of storing and retrieving the content articles in a
    *       compressed format using the gzip library, so it can use less database table space.
    *
    *   @see lib.VersionControl.php
    */

class gzVersionControl extends VersionControl
{

        /**
        *   class variables
        */
    public $gzLevel;           // Level of compression
        public $originalSize;      // original size of content article in uncompressed state
        public $compressedSize;    // size of content article in compressed state


        /**
        *   gzVersionControl
        *
        *   init-function
        *   @access public
        *   @param  Object  valid database-object
        *   @param  String  valid database-connection-name
        *   @param  String  TableName of VersionTable
        *   @param  int     compression-level
        */
    public function __construct($dbHandle, $dbHandleName, $vc_tablename, $compressionLevel)
    {
        $this->dbHandle     =   $dbHandle;
        $this->dbHandleName =   $dbHandleName;
        $this->vc_tablename =   $vc_tablename;
        $this->gzLevel      =   $compressionLevel;
    }

    /**
    *   encode
    *
    *   function zipps and encodes data-record
    *
    *   @access private
    *   @param  serializedArray
    *   return  string  encoded array
    */
    public function encode($serialized)
    {
        $compressed =   gzcompress($serialized, $this->gzLevel);
        $encArray   =   base64_encode($compressed);

        // save sizes
        $this->originalSize = strlen($serialized);
        $this->compressedSize = strlen($encArray);
        return ($encArray);
    }

    /**
    *   decode
    *
    *   function unzips and decodes data-record
    *   @access private
    *   @param  string  encoded array
    *   return  serialized array
    */
    public function decode($encArray)
    {
        $decoded    =   base64_decode($encArray);
        $serialized =   gzuncompress($decoded);
        return ($serialized);
    }
}
