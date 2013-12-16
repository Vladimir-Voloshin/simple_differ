<?php
require_once('diff-reader/DiffReaderClass.php');
$file = $_GET['file'];
$reader = new DiffReader();
$reader->getFile($file);
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
		<?php $content = $reader->getContent();
			if (empty($content)): ?>
			<div class="file-not-found">File not found</div>
		<?php else: ?>
			<ol>
			<?php foreach ($content as $i => $line) : ?>
				<li<?php echo (!empty($line['class'])) ? ' class="' . $line['class'] . '"' : '' ?>><?php echo $line['content'] ?></li>
			<?php endforeach; ?>
			</ol>
		<?php endif; ?>
		<!--<div class="command-input">[<?php echo $username ?>@dating-<?php echo $location ?> <?php echo $currentDir ?>]$&nbsp;</div>-->
	</body>
</html>