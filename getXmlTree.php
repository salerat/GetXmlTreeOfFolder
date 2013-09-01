<?php
/**
 * Created by JetBrains PhpStorm.
 * User: solov
 * Date: 6/24/13
 * Time: 4:56 PM
 * To change this template use File | Settings | File Templates.
 */


class GetXmlTree extends DOMElement
{
    function __construct($parent, $path)
    {
        $path = rtrim($path, '/');
        $fileType = filetype($path);
        parent::__construct($fileType);
        $parent->appendChild($this);
        $curPath = explode('/', $path);
        $this->setAttribute('name', end($curPath));
        $this->setAttribute('path', $path);
        if (is_dir($path) && $handle = opendir($path)) {
            while (false !== ($file = readdir($handle))) {
                if ($file[0] != '.') {
                    new GetXmlTree($this, "$path/$file");
                }
            }
            closedir($handle);
        }
    }
}

$document = new DOMDocument();

$node = $document->createElement("items");
$document->appendChild($node);

//$folders array of paths
//example: $folders = array('./../');

foreach ($folders as $folder) {
    new GetXmlTree($node, $folder);
}
$document->formatOutput = true;

$count = $document->save(dirname(__FILE__) . "/tree.xml");

?>