<?php

// Start the session.
session_start();

// Distroy the session.
session_destroy();
header("Location: /");
exit;
