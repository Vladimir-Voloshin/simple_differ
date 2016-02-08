<?php
require_once('diff-reader/DiffReaderClass.php');
$file = $_GET['file'];
$reader = new DiffReader($_GET['stp']);
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
            body{background-color:#313131;color:#f1f1f1;}
            .added {color: #00AA00;}
            .content-start {color: #00CCCC;}
            .empty-string {background-color: #AA0000;}
            .file-info {color: #E9FD02;}
            .removed {color: #F85F5F;}
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