<?php
/**
 * Access point for listing diffs
 *
 * @author  Sergey boonya Buynitskiy boonya41@gmail.com
 * @url     https://github.com/boonya/Diff-Reader
 */
require_once('./diff-reader/DiffReaderClass.php');
$reader = new DiffReader();
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <title>Diff Reader: Listing</title>
        <link media="all" rel="stylesheet" type="text/css" href="./diff-reader/main.css" />
        <style>
            .total {
                font-size: 150%;
                color: #AA0000;
            }
            ol li {
                margin: 5px 0;
            }
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
        <?php
            $listing = $reader->listing(__DIR__);
            if (empty($listing)):
        ?>
            <div class="file-not-found">Files not found</div>
        <?php else: ?>
            <div class="total">Total: <?php echo count($listing) ?></div>
            <ol>
            <?php foreach ($listing as &$item) : ?>
                <li><a href="./<?php echo $item ?>"><?php echo $item ?></a></li>
            <?php endforeach; ?>
            </ol>
        <?php endif; ?>
    </body>
</html>