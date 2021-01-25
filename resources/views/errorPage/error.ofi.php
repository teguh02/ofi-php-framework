<?php
    /**
     * Tips :
     * > show error status with $status
     * > show error title with $title
     * > show error message with $msg
     */
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error <?= $status ?> </title>
</head>
<body>
    <h1>Error</h1>
    <?= $msg ?>
</body>
</html>