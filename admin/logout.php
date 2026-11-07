<?php

require __DIR__ . '/_init.php';

require_local_page();
logout_owner();
header('Location: ../login.php');
exit;
