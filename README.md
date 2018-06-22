# Command builder

## Code example

```php
<?php

require_once 'vendor/autoload.php';

use DigipolisGent\CommandBuilder\CommandBuilder;

$builder = CommandBuilder::create('ls')
        ->addFlag('a')
        ->addFlag('l')
    ->pipeOutputTo('grep')
        ->addArgument('mydir')
    ->onSuccess('echo')
        ->addArgument('mydir already exists')
    ->onFailure(
        CommandBuilder::create('mkdir')
            ->addArgument('mydir')
        ->onSuccess('echo')
            ->addArgument('mydir created')
      );
print $builder;

// Output: { { { ls -a -l | grep 'mydir'; } && echo 'mydir already exists'; } || { mkdir 'mydir' && echo 'mydir created'; }; }
```
