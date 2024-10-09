<?php
// deploy.php

// Get the raw POST data
$payload = file_get_contents('php://input');
file_put_contents('webhook.log', $payload, FILE_APPEND); // Log the payload

// Decode the JSON data
$data = json_decode($payload, true);

// Check if the action is a push event
if (isset($data['ref']) && strpos($data['ref'], 'refs/heads/') === 0) {
    // Path to your repository
    $repoPath = '/home/dafm5634/repositories/jimpitan';

    // Execute the git pull command
    $output = shell_exec("cd $repoPath && git pull origin main 2>&1");
    
    // Log the output
    file_put_contents('deploy.log', $output, FILE_APPEND);
}

http_response_code(200); // Respond with a 200 OK status
?>
