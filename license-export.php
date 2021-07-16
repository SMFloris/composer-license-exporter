<?php

ob_start();
passthru("composer show -f json");
$output = ob_get_contents();
ob_end_clean();

$packages = json_decode($output)->installed;

$commandOutput = [['name', 'description', 'version', 'license']];
foreach ($packages as $package) {
    $composerFile = file_get_contents('./vendor/'.$package->name.'/composer.json');
    $license = json_decode($composerFile)->license ?? 'unknown';
    $commandOutput[] = [$package->name, $package->description, $package->version, $license];
}

$fp = fopen('licenses.csv', 'w');
foreach ($commandOutput as $row) {
    fputcsv($fp, $row);
}
fclose($fp);
