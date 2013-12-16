<?php
require_once('diff-reader/DiffReaderClass.php');
$reader = new DiffReader();
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <title>Diff Reader: <?php echo $file ?></title>
		<link media="all" rel="stylesheet" type="text/css" href="/diff-reader/main.css" />
        <style>
        	ol li a {
        		color: #999999;
        		text-decoration: none;
        		border-left: transparent 2px solid;
        		padding-left: 5px;
        	}
        	ol li a:hover {
        		border-left-color: #00AA00;
        	}
        </style>
    </head>
	<body>
		<?php $listing = $reader->listing(__DIR__);
			if (empty($listing)): ?>
			<div class="file-not-found">Files not found</div>
		<?php else: ?>
			<ol>
			<?php foreach ($listing as $i => $item) : ?>
				<li><a href="./<?php echo $item ?>"><?php echo $item ?></a></li>
			<?php endforeach; ?>
			</ol>
		<?php endif; ?>
		<!--<div class="command-input">[<?php echo $username ?>@dating-<?php echo $location ?> <?php echo $currentDir ?>]$&nbsp;</div>-->
	</body>
</html>