<?php
$repository = 'https://github.com/agusbudionoprastyo/jimpitan.git';
$targetDir = '/home/dafm5634/public_html/jimpitan'; // Path to your target directory

// Change to the target directory
chdir($targetDir);

// Pull the latest changes
exec("git pull $repository master");
?>
