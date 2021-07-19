<?php
defined('APP_NAME') or die(header('HTTP/1.1 403 Forbidden'));

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="content-type" content="text/html" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="author" content="Balaji" />

	<title><?php echo $pageTitle; ?></title>
</head>

<body>
<script type="text/javascript" src="<?php createLink('widget.js'. ($inline ? '&inline': '')); ?>"></script>
<!-- The core Firebase JS SDK is always required and must be listed first -->
<script src="/__/firebase/8.7.1/firebase-app.js"></script>

<!-- TODO: Add SDKs for Firebase products that you want to use
     https://firebase.google.com/docs/web/setup#available-libraries -->
<script src="/__/firebase/8.7.1/firebase-analytics.js"></script>

<!-- Initialize Firebase -->
<script src="/__/firebase/init.js"></script>
<?php if(isset($footerAddArr)){ 
    foreach($footerAddArr as $footerCodes)
        echo $footerCodes;
} ?>
</body>
</html>