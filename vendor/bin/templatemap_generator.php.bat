@ECHO OFF
SET BIN_TARGET=%~dp0/../zendframework/zend-view/bin/templatemap_generator.php
php "%BIN_TARGET%" %*
