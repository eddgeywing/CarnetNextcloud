<?php
global $currentpath;
global $root;
$currentpath = __DIR__."/CarnetElectron/";
$root = \OCP\Util::linkToAbsolute("carnet","templates");
$root = parse_url($root, PHP_URL_PATH); 

$file = file_get_contents($currentpath."reader/reader.html");

$file = preg_replace_callback('/<script(.*?)src=\"(.*?\.js(?:\?.*?)?)"/s',function ($matches) {
    global $currentpath;
    global $root;
    $src = str_replace("<!ROOTPATH>", $root."/CarnetElectron/", $matches[2]);
    $relativePath = str_replace("<!ROOTPATH>", "", $matches[2]);
    return "<script".$matches[1]."src=\"".$src."?mtime=".filemtime($currentpath.$relativePath)."\"";
}, $file);

$file = preg_replace_callback('/<link(.*?)href=\"(.*?\.css(?:\?.*?)?)"/s',function ($matches) {
    global $currentpath;
    global $root;
    $src = str_replace("<!ROOTPATH>", $root."/CarnetElectron/", $matches[2]);
    $relativePath = str_replace("<!ROOTPATH>", "", $matches[2]);
    return "<link".$matches[1]."href=\"".$src."?mtime=".filemtime($currentpath.$relativePath)."\"";
}, $file);

$file = str_replace("<!ROOTPATH>", $root."/CarnetElectron/", $file);

$root = substr(__DIR__, strlen($_SERVER['DOCUMENT_ROOT']));
$urlGenerator = \OC::$server->getURLGenerator();
$file = str_replace("<!ROOTURL>", $root."/CarnetElectron/", $file);
$file = str_replace("<!APIURL>", parse_url($urlGenerator->linkToRouteAbsolute("carnet.page.index"), PHP_URL_PATH), $file);
echo $file;

?>