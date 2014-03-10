<?php
/**
 * Test DDS Schema creation
 *
 * @author    Aaron Harris <aharris@intacct.com>
 * @copyright 2000-2014 Intacct Corporation
 *
 * This document contains trade secret data that belongs to Intacct
 * Corporation and is protected by the copyright laws.  Information
 * herein may not be used, copied or disclosed in whole or in part
 * without prior written consent from Intacct Corporation.
 */

// simple test to see if we can create a database
require_once 'intacctws-php/api_session.php';
require_once 'intacctws-php/api_post.php';

require_once 'DdsLoader/DdsDbRedshift.php';
require_once 'DdsLoader/DdsDbManager.php';

try {

    $memcache = new Memcache();
    $memcache->connect("localhost", 11211);
    $key = 'dds_loader_test_session';
    $session = $memcache->get($key);
    if ($session === false) {
        $session = new api_session();
        $session->connectCredentials('DDS_ATLAS', 'Aaron', 'As123456!', 'intacct_dev', 'isa9Shixa');
        $memcache->set($key, $session, null, 300);
    }

    $intacctPg = new DdsDbRedshift(
        "intacct-dds-dmllc.c808ui4qmvc1.us-west-2.redshift.amazonaws.com",
        "ddsdmllc",
        "ddsuser",
        "IntacctDds2014"
    );

    DdsDbManager::rebuildSchema($intacctPg, $session);

    echo "done!";
}
catch (Exception $ex) {
    print_r($ex->getMessage());
    print_r(api_post::getLastRequest());
    print_r(api_post::getLastResponse());
}

