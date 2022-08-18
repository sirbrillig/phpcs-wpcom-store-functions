<?php

$data = wp_get_subscription();
$data = get_subscription(); // Disallowed
$data = \get_subscription(); // Disallowed
$data = MyFramework\get_subscription();
echo 'get_subscription';
echo "get_subscription()";
