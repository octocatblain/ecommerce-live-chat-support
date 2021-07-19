<?php
header('HTTP/1.1 404 Not Found', true, 404);
/*
 * @author Balaji
 * @name: Rainbow PHP Framework
 * @copyright 2021 ProThemes.Biz
 *
 */
?>
<!DOCTYPE HTML>
<html>
    <head>
        <title>404 - <?php trans('Page Not Found!', $lang['CH9']); ?></title>
    </head>
    <body>
        <?php trans('Page Not Found!', $lang['CH9']); ?>
    </body>
</html>
<?php exit(); ?>