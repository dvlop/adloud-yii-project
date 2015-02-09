<?php
date_default_timezone_set('UTC');
session_start();

$_SESSION['lastPageRequest'] = (new DateTime())->format('Y-m-d H:i:s');