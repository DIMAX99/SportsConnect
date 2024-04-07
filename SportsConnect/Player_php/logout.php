<?php

include'../support_php/db.php';

session_start();
session_unset();
session_destroy();

header('location:/SportsConnect/Player_php/login.php');