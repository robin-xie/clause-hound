<?php
/**
 * Created by PhpStorm.
 * User: robin
 * Date: 19/02/17
 * Time: 8:16 PM
 */

require_once('vendor/autoload.php');
require_once('templates/ClauseTemplate.class.php');

try {

    $template = new ClauseTemplate();


    // $template->setBaseUri('https://clausehound.com/wp-content/themes/knowhow/actions/load-template.php');
    // $template->setClauseclassName('dat_clause_language');


    echo '<pre>16170 - '. date('Y-m-d H:i:s') . ' - response - '. json_encode($template->fetch('16170')). '</pre>';
    echo '<pre>16170 - '. date('Y-m-d H:i:s') . ' - response - '. json_encode($template). '</pre>';


    echo '<pre>16171 - '. date('Y-m-d H:i:s') . ' - response - '. json_encode($template->fetch('16171')). '</pre>';
    echo '<pre>16171 - '. date('Y-m-d H:i:s') . ' - response - '. json_encode($template). "</pre>";


    echo '<pre>16170 + 16171 -'. date('Y-m-d H:i:s') . ' - response - '. json_encode($template->fetch(['16170','16171'])). '</pre>';
    echo '<pre>16170 + 16171 -'. date('Y-m-d H:i:s') . ' - response - '. json_encode($template). '</pre>';

    echo 'done';
}
catch(Exception $e)
{
    echo $e->getMessage();
}

