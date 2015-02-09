<?php
include "../AutoLoader.php";
AutoLoader::register();

$rm = new \core\RatingManager();


$rm->updateCategoriesRatings();


