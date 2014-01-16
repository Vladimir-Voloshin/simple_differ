<?php
require_once('diff-reader/DiffReaderClass.php');
$file = $_GET['file'];
$reader = new DiffReader();
try {
    $content = $reader->getFile($file)
                    ->getContent();
} catch (InvalidArgumentException $exc) {
    header("HTTP/1.0 404 Not Found");
    $error[1] = $exc->getMessage();
} catch (LengthException $exc) {
    $error[2] = $exc->getMessage();
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <title>Diff Reader: <?php echo $file ?></title>
        <link media="all" rel="stylesheet" type="text/css" href="/diff-reader/main.css" />
        <style>
            .file-info {color: #FFFFFF;}
            .content-start {color: #00CCCC;}
            .added {color: #00AA00;}
            .removed {color: #AA0000;}
            .empty-string {background-color: #AA0000;}
        </style>
    </head>
    <body>
        <?php if (!empty($error[1])): ?>
            <div class="file-not-found"><?php echo $error[1] ?></div>
        <?php elseif (!empty($error[2])): ?>
            <div class="file-not-found"><?php echo $error[2] ?></div>
        <?php else: ?>
            <ol>
            <?php foreach ($content as $i => $line) : ?>
                <li<?php echo (!empty($line['class'])) ? ' class="' . $line['class'] . '"' : '' ?>><?php echo $line['content'] ?></li>
            <?php endforeach; ?>
            </ol>
        <?php endif; ?>
    </body>
</html>