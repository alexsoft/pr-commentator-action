<?php

declare(strict_types=1);

use Github\AuthMethod;
use Github\Client;

require 'vendor/autoload.php';

// CHECK FOR TOKEN, GET IT
$githubToken = getenv('GITHUB_TOKEN');

if (!is_string($githubToken) || $githubToken === '') {
    $error = 'Missing GitHub token. It has to be passed as GITHUB_TOKEN env variable.';
//    throw new InvalidArgumentException($error);
    echo $error . PHP_EOL;
    exit(2);
}

// READ EVENT
$event = json_decode(
    file_get_contents(getenv('GITHUB_EVENT_PATH')),
    true,
    512,
    JSON_THROW_ON_ERROR,
);

$github = new Client();
$github->authenticate($githubToken, authMethod: AuthMethod::ACCESS_TOKEN);

// GET MESSAGE
$message = $argv[1] ?? '';

if (!is_string($message) || $message === '') {
    $error = 'Missing `message`. It must be passed to action.';
//    throw new InvalidArgumentException($error);
    echo $error . PHP_EOL;
    exit(2);
}

$repositoryName = $event['repository']['full_name'];

if (getenv('GITHUB_EVENT_NAME') === 'pull_request') {
    $prId = $event['number'];

    [$username, $repo] = explode('/', $repositoryName);

    $github->issues()->comments()->create(
        $username,
        $repo,
        $prId,
        [
            'body' => $message,
        ],
    );
}

exit(0);
