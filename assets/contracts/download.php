<?php
$client = $_GET['client'];
if ($client==null or $client == "") die();
$zipname = $client.'.zip';
$zip = new ZipArchive;

if ($zip->open($zipname, ZIPARCHIVE::CREATE )!==TRUE) {
    exit("cannot open <$zipname>\n");
}
$dir = $client;
if(!is_dir($dir)) {
    echo "Document does not exist.";
    return;
}
$files1 = scandir($dir);
foreach ($files1 as $file) {
    if($file !="."&&$file !=".."){
        $zip->addFile($dir."/".$file);
    }
}
$zip->close();
header("Content-type: application/zip");
header("Content-Disposition: attachment; filename=$zipname");
header("Pragma: no-cache");
header("Expires: 0");
readfile("$zipname");
exit;
?>
