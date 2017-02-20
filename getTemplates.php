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

    //$template->setBaseUri('https://clausehound.com/wp-content/themes/knowhow/actions/load-template.php');
    //$template->setClauseclassName('dat_clause_language');

    $template->fetch('16170');
    echo "<pre>". date('Y-m-d H:i:s') .json_encode($template). "</pre>";

    $template->fetch('16171');
    echo "<pre>". date('Y-m-d H:i:s') .json_encode($template). "</pre>";

}
catch(Exception $e)
{
    echo $e->getMessage();
}

