<?php
return [
    'Uncaught Symfony\\Component\\Dotenv\\Exception\\FormatException: Whitespace are not supported before the value in "/var/www/html/esproxy/.env" at line 10.
...ELASTICSEARCH_URL_1= "http://192.168.0.1...
                                          ^ line 10 offset 305 in /var/www/html/esproxy/vendor/symfony/dotenv/Dotenv.php:546
Stack trace:
#0 /var/www/html/esproxy/vendor/symfony/dotenv/Dotenv.php(305): Symfony\\Component\\Dotenv\\Dotenv->createFormatException(\'Whitespace are ...\')
#1 /var/www/html/esproxy/vendor/symfony/dotenv/Dotenv.php(249): Symfony\\Component\\Dotenv\\Dotenv->lexValue()
#2 /var/www/html/esproxy/vendor/symfony/dotenv/Dotenv.php(562): Symfony\\Component\\Dotenv\\Dotenv->parse(\'KERNEL_CLASS=\'A...\', \'/var/www/html/e...\')
#3 /var/www/html/esproxy/vendor/symfony/dotenv/Dotenv.php(106): Symfony\\Component\\Dotenv\\Dotenv->doLoad(false, Array)
#4 /var/www/html/esproxy/vendor/symfony/dotenv/Dotenv.php(149): Symfony\\Component\\Dotenv\\Dotenv->loadEnv(\'/var/www/html/e...\', \'APP_ENV\', \'dev\', Array, false)
#5 /var/www/html/esproxy/public/index.php(11): Symfony\\Component\\Dotenv\\Dotenv->bootEnv(\'/var/www/html/e...\')
#6 {main}
    thrown [peak: 34 MB]' => [
        'ru' => 'Uncaught Symfony\\Component\\Dotenv\\Exception\\FormatException: Whitespace are not supported before the value in "/var/www/html/esproxy/.env" at line 10.
...ELASTICSEARCH_URL_1= "http://192.168.0.1...
                                          ^ line 10 offset 305 in /var/www/html/esproxy/vendor/symfony/dotenv/Dotenv.php:546
Stack trace:
#0 /var/www/html/esproxy/vendor/symfony/dotenv/Dotenv.php(305): Symfony\\Component\\Dotenv\\Dotenv->createFormatException(\'Whitespace are ...\')
#1 /var/www/html/esproxy/vendor/symfony/dotenv/Dotenv.php(249): Symfony\\Component\\Dotenv\\Dotenv->lexValue()
#2 /var/www/html/esproxy/vendor/symfony/dotenv/Dotenv.php(562): Symfony\\Component\\Dotenv\\Dotenv->parse(\'KERNEL_CLASS=\'A...\', \'/var/www/html/e...\')
#3 /var/www/html/esproxy/vendor/symfony/dotenv/Dotenv.php(106): Symfony\\Component\\Dotenv\\Dotenv->doLoad(false, Array)
#4 /var/www/html/esproxy/vendor/symfony/dotenv/Dotenv.php(149): Symfony\\Component\\Dotenv\\Dotenv->loadEnv(\'/var/www/html/e...\', \'APP_ENV\', \'dev\', Array, false)
#5 /var/www/html/esproxy/public/index.php(11): Symfony\\Component\\Dotenv\\Dotenv->bootEnv(\'/var/www/html/e...\')
#6 {main}
    thrown [peak: 34 MB]',
    ],
    'Uncaught Symfony\\Component\\Dotenv\\Exception\\FormatException: Whitespace characters are not supported after the variable name in "/var/www/html/esproxy/.env" at line 10.
...\\nELASTICSEARCH_URL_1 = "http://192.168.0...
                                                               ^ line 10 offset 304 in /var/www/html/esproxy/vendor/symfony/dotenv/Dotenv.php:546
Stack trace:
#0 /var/www/html/esproxy/vendor/symfony/dotenv/Dotenv.php(284): Symfony\\Component\\Dotenv\\Dotenv->createFormatException(\'Whitespace char...\')
#1 /var/www/html/esproxy/vendor/symfony/dotenv/Dotenv.php(244): Symfony\\Component\\Dotenv\\Dotenv->lexVarname()
#2 /var/www/html/esproxy/vendor/symfony/dotenv/Dotenv.php(562): Symfony\\Component\\Dotenv\\Dotenv->parse(\'KERNEL_CLASS=\'A...\', \'/var/www/html/e...\')
#3 /var/www/html/esproxy/vendor/symfony/dotenv/Dotenv.php(106): Symfony\\Component\\Dotenv\\Dotenv->doLoad(false, Array)
#4 /var/www/html/esproxy/vendor/symfony/dotenv/Dotenv.php(149): Symfony\\Component\\Dotenv\\Dotenv->loadEnv(\'/var/www/html/e...\', \'APP_ENV\', \'dev\', Array, false)
#5 /var/www/html/esproxy/public/index.php(11): Symfony\\Component\\Dotenv\\Dotenv->bootEnv(\'/var/www/html/e...\')
#6 {main}
        thrown [peak: 34 MB]' => [
        'ru' => 'Uncaught Symfony\\Component\\Dotenv\\Exception\\FormatException: Whitespace characters are not supported after the variable name in "/var/www/html/esproxy/.env" at line 10.
...\\nELASTICSEARCH_URL_1 = "http://192.168.0...
                                                               ^ line 10 offset 304 in /var/www/html/esproxy/vendor/symfony/dotenv/Dotenv.php:546
Stack trace:
#0 /var/www/html/esproxy/vendor/symfony/dotenv/Dotenv.php(284): Symfony\\Component\\Dotenv\\Dotenv->createFormatException(\'Whitespace char...\')
#1 /var/www/html/esproxy/vendor/symfony/dotenv/Dotenv.php(244): Symfony\\Component\\Dotenv\\Dotenv->lexVarname()
#2 /var/www/html/esproxy/vendor/symfony/dotenv/Dotenv.php(562): Symfony\\Component\\Dotenv\\Dotenv->parse(\'KERNEL_CLASS=\'A...\', \'/var/www/html/e...\')
#3 /var/www/html/esproxy/vendor/symfony/dotenv/Dotenv.php(106): Symfony\\Component\\Dotenv\\Dotenv->doLoad(false, Array)
#4 /var/www/html/esproxy/vendor/symfony/dotenv/Dotenv.php(149): Symfony\\Component\\Dotenv\\Dotenv->loadEnv(\'/var/www/html/e...\', \'APP_ENV\', \'dev\', Array, false)
#5 /var/www/html/esproxy/public/index.php(11): Symfony\\Component\\Dotenv\\Dotenv->bootEnv(\'/var/www/html/e...\')
#6 {main}
        thrown [peak: 34 MB]',
    ],
];