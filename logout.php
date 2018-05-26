<?php

session_start();

if (!isset($_SESSION['user'])) {
	header('Location: /')	;
	exit(0);
} else {
	unset($_SESSION['user']);
	header('Location: /');
	exit(0);
}
