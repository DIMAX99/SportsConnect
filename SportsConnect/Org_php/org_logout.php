<?php

include'../support_php/db.php';

session_start();
session_unset();
session_destroy();

header('location:/SportsConnect/Org_php/org_login.php');