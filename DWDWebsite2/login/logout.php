<?php
session_start();
session_destroy();
// Redirect to the login page:
header('Location: https://s2250677.edinburgh.domains/DWDWebsite2/');
exit();
?>