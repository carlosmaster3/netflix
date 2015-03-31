<?php

    if (empty($_GET['id'])) die('missing id');
    $name = !empty($_GET['name']) ? $_GET['name'] : null;

    require_once 'inc/addresslist.class.php';

    new AddressList($_GET['id'], $name);


