@ECHO OFF
SET BIN_TARGET=%~dp0/../zendframework/zend-servicemanager/bin/generate-factory-for-class
php "%BIN_TARGET%" %*
