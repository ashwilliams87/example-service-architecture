<?php

use PhpCsFixer\Fixer\ClassNotation\NoNullPropertyInitializationFixer;
use PhpCsFixer\Fixer\Import\FullyQualifiedStrictTypesFixer;
use SlevomatCodingStandard\Sniffs\Variables\UnusedVariableSniff;
use Symplify\EasyCodingStandard\Config\ECSConfig;
use PhpCsFixer\Fixer\Import\NoUnusedImportsFixer;
use PhpCsFixer\Fixer\FunctionNotation\VoidReturnFixer;

return static function (ECSConfig $ecsConfig): void {
    $ecsConfig->paths([
        __DIR__ . '/app',
        __DIR__ . '/routes',
        __DIR__ . '/Lan',
        __DIR__ . '/tests'
    ]);

    $ecsConfig->skip([
        __DIR__ . '/tests/_output',
        __DIR__ . '/tests/Support/_generated',
    ]);

    $ecsConfig->rule(NoNullPropertyInitializationFixer::class); // предотвращает инициализацию свойств класса значением null
    $ecsConfig->rule(NoUnusedImportsFixer::class); // Удаление неиспользуемых use-операторов
    $ecsConfig->rule(VoidReturnFixer::class); // Автоматически добавляет void в методы, которые ничего не возвращают
    $ecsConfig->rule(UnusedVariableSniff::class); // Проверяем неиспользуемые переменные
//    $ecsConfig->rule(PropertyTypeHintSniff::class);

    // Removes the leading part of FQCN
    $ecsConfig->ruleWithConfiguration(
        FullyQualifiedStrictTypesFixer::class,
        ['import_symbols' => true], // Also import symbols from other namespaces than in current file
    );

    // Правила, которые будем использовать в будущем:
    //    $ecsConfig->rule(StrictComparisonFixer::class); // Запрещает `==` и `!=`, требует `===` и `!==`
    //    $ecsConfig->rule(DeclareStrictTypesFixer::class); // Обязательное `declare(strict_types=1);`
};
