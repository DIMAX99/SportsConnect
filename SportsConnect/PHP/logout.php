<?php

include'db.php';

session_start();
session_unset();
session_destroy();

header('location:/SportsConnect/PHP/login.php');