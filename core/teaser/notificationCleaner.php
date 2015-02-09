<?php
/**
 * Created by JetBrains PhpStorm.
 * User: JanGolle
 * Date: 12.09.14
 * Time: 17:56
 * To change this template use File | Settings | File Templates.
 */

namespace core;

include dirname(__FILE__)."/../AutoLoader.php";
\AutoLoader::register(false);

cleanOldNotifications();

function cleanOldNotifications(){
    $db = PostgreSQL::getInstance()->getConnection(Session::getInstance(), 'persistent_data');
    $sql = 'DELETE FROM notification WHERE date < \'yesterday\'';
    $statement = $db->prepare($sql);
    $statement->execute();
}