<?php

    /**
    *   $RCSfile: example.VersionControl.php,v $
    *   @author     $Author: Cornelius Bolten $
    *   @version    $Revision: 1.9 $
    *
    *
    *   @description:
    *       - this is the example-file for libVersionControl by
    *         Cornelius Bolten
    *       - as example we work with a news-table.
    *         the data is checked in, retrieved, overwritten and
    *         again checked in.
    *         finally we receive all available versions.
    *         this should fit your needs and show what is possible
    *         with libVersionControl.
    *
    *   @required:
    *       - MySQLQueryContainer by Cornelius Bolten.
    *       - libVersionControl by Cornelius Bolten.
    *
    *       --> these packages can be retrieved via http://www.phpclasses.org/
    *
    *   so now..here we go!
    *
    **/

    error_reporting(E_ALL);

    define('USE_LIB', 'use_lib');
    require_once('mysql/mysqlQuery.inc.php');
    require_once('lib.VersionControl.php');
    require_once('lib.gzVersionControl.php');

    /**
    *   create MySQL-Container named "myConnection"
    */
    $myContainer    =   new mysqlquery();
    if (!$myContainer->addNewConnection('myConnection', 'localhost', 'cb', 'root', '', 3306)) {
        echo('error: ' . $myContainer->containerError);
    }

    /**
    *   open Database-Connection "myConnection"
    */
    if (!$myContainer->openConnection('myConnection')) {
        echo('error: ' . $myContainer->containerError);
    }

    /**
    *   create versionControl-Object (DatabaseContainer, ConnectionName, VersionControl-Tablename, [(only gz)compression-level])
    *   you can choose between VersionControl() and gzVersionControl()
    */
    $myVC   =   new gzVersionControl($myContainer, 'myConnection', 'libVersionControl', 9);

    /**
    *   create example-data (news-table entry),
    *   that we would normaly have in the news-table
    */
    $News['table']  = 'simple_news';  // table-name where data is stored originally
    $News['id']     =   '1';            // uid of news-entry
    $News['data']   =   [          // news-data.. like header, teaser, text whatever!
                                   'title'  => 'my news-entry',
                                   'teaser' =>  "this is my tiny news-teaser, isn't it cute?!",
                                   'text'   =>  "no matter how often the script is executet, there will be only 2 entries in\nthe database. This is because of having the same texts everytime the\nscript is executed, so there is no need to add new Versions to the\nVersionControl except the two (original,2nd) Versions."
    ];
    $News['editor'] =   '12';           // user-id of example-newseditor

    // print-out original news-data
    echo '<b>original news-data:</b><br>';
    print_r($News['data']);

    /**
    *   after the user just added a news-entry
    *   we save the data to VersionControl
    *   saveVersion(RecordID, TableNameWhereRecordisOriginallyStored, RecordItSelf, EditorID)
    */
    $myVC->saveVersion($News['id'], $News['table'], $News['data'], $News['editor'], 'First CheckIn');
    echo '<br><br>';

    /**
    *   some days later..the user wants to edit the news-entry
    *   so we need to receive it from the VersionControl
    *   getLatestVersion(RecordID, TableNameWhereRecordisOriginallyStored)
    */
    $News['data']   =   $myVC->getLatestVersion($News['id'], $News['table']);
    // print-out retrieved news-data
    echo '<b>latest Version of news-record:</b> (when executing the script the 1st time, it will be the same as the original-data)<br>';
    print_r($News['data']);
    echo '<br><br><hr>';

    /**
    *   now we update some of the received data..
    *   lets say, we edit the text of the news-entry
    */
    $News['data']['text']   =   "no matter how often the script is Executed, there will be only 2 entries in\nthe database. This is because of having the same texts everytime the\nscript is executed, so there is no need to add new Versions to the\n\nThis is an additional line\n\nVersionControl except the two origin Versions.";

    /**
    *   after changed the text (added new line..), we commit the data
    *   to the VersionControl.
    *   this is the pretty same call as we did the first time above.
    */
    $myVC->saveVersion($News['id'], $News['table'], $News['data'], $News['editor'], 'added a new line and some linebreaks');

    /**
    *   finally we receive and print_r both news-versions!
    */
    echo '<b>finally all versions of the news-entry:</b><br>';
    foreach ($myVC->getAllVersions($News['id'], $News['table']) as $version) {
        echo "<i>version $version</i><br>";
        print_r($myVC->getOldVersion($News['id'], $News['table'], $version));
        // you can now get content-articles' the Editor and Date via..
        echo '--> EDITOR: ' . $myVC->contentEditor;
        echo ' (' . date('d.m.Y H:i:s', $myVC->contentDate) . ')';
        echo '<br><br>';
    }
    echo '<br>';

    echo '<b>Information:</b><br>';
    echo nl2br(wordwrap('no matter how often the script is executed, there will be only 2 entries in the database. This is because of having the same texts everytime the script is executed, so there is no need to add new Versions to the VersionControl except the two (original,2nd) Versions.'));

    echo '<br><br><b>show Differences the two versions..(news[text]-data only) (requires unix diff-tool installed)</b><br>';

    // sett diffTool-Binary-Path (/usr/bin/diff also as default available)
    $myVC->setDiffPath('/usr/bin/diff');
    echo nl2br($myVC->diff($News['id'], $News['table'], '1', '2', 'text'));

    /**
    *   close Database-Connection
    */
    $myContainer->closeConnection('myConnection');
    $myContainer->flushConnectionContainer();

    /**
    *   get errormessages if we got any and show them
    */
    // $myVC->getErrorMessage();
