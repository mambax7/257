<?php


    require_once __DIR__ . '/VersionControl.php';
    require_once __DIR__ . '/VersionControlMySQL.php';


    /*
    *   define a random content-object containing:
    *       - a unique object-identifier (like a unique id, a unique string, or what ever)
    *       - some data as array (e.g. an title, and description and some text)
    *       - an editor id, who created the object (e.g. an editorial journalist)
    *       - an comment as a description of the content
    */
    $sUniqueObjectIdentifier = md5('asdfiupweoiraoidfjasoldfjaef');
    $aObjectData    =   [
        2,'a',
        3,'b',
        4,'c'
    ];
    $iEditorId  =   1;
    $sComment   =   'this is a random content object';


    /*
    *   now initiate a new versioncontrol-MYSQL object
    */
    $oVC = new VersionControlMySQL();
    try {
        /*
        *   try to submit the content-object to the repository
        */
        $oVC->submitVersion($sUniqueObjectIdentifier, $aObjectData, $iEditorId, $sComment);
    } catch (ObjectVersionAlreadyExistsException $e) {
        /*
        *   the first time, this error will NOT occur.
        *   after the content-object has been submitted to the repository,
        *   the versioncontrol will throw the error "Object Already Exists",
        *   which means nothing more than, "there is already an identical object in the repository"
        */

        // return the latest version of our content-object
        echo 'This is the latest version available in the versioncontrol-repository:<br/>';
        print_r(
            $oVC->getLatestVersion($sUniqueObjectIdentifier)
        );
    } catch (Exception $e2) {
        /*
        *   another error occured
        */
        echo $e2->getMessage();
    }
