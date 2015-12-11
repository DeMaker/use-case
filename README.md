# DeMaker/UseCase

## Installation
```
composer require demaker/use-case
```

## Usage
```
php bin/demaker-use-case <command> <fully qualified class name> -i attribute1:string,attribute2:\\Carbon\\Carbon
```

In example:

```
php bin/demaker-use-case use-case Foo\\BarCommand -i firstname:string,lastname:string,dob:\\Carbon\\Carbon
```

Available commands:

* command - builds command class
* command-validator - builds command validator class
* command-response - builds command response class
* command-handler - builds command handler class
* use-case - builds command, command handler and command response classes
