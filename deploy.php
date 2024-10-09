<?php
$repository = 'https://github.com/agusbudionoprastyo/jimpitan.git';
$targetDir = '/home/dafm5634/repositories/jimpitan';

// Change to the target directory
chdir($targetDir);

// Pull the latest changes
$output = [];
$return_var = 0;

exec("git pull $repository main 2>&1", $output, $return_var);

if ($return_var !== 0) {
    // Jika ada kesalahan, tampilkan output
    echo "Error pulling repository: " . implode("\n", $output);
} else {
    echo "Repository updated successfully.";
}
?>