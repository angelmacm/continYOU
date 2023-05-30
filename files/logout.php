<?php

include_once 'functions.php';

session_start();
session_regenerate_id();
session_destroy();
redirectTo('./');

?>