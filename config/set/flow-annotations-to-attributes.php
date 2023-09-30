<?php
declare (strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Php80\Rector\Class_\AnnotationToAttributeRector;
use Rector\Php80\ValueObject\AnnotationToAttribute;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->ruleWithConfiguration(AnnotationToAttributeRector::class, [
        new AnnotationToAttribute('Neos\\Flow\\Annotations\\After'),
        new AnnotationToAttribute('Neos\\Flow\\Annotations\\AfterReturning'),
        new AnnotationToAttribute('Neos\\Flow\\Annotations\\AfterThrowing'),
        new AnnotationToAttribute('Neos\\Flow\\Annotations\\Around'),
        new AnnotationToAttribute('Neos\\Flow\\Annotations\\Aspect'),
        new AnnotationToAttribute('Neos\\Flow\\Annotations\\Autowiring'),
        new AnnotationToAttribute('Neos\\Flow\\Annotations\\Before'),
        new AnnotationToAttribute('Neos\\Flow\\Annotations\\CompileStatic'),
        new AnnotationToAttribute('Neos\\Flow\\Annotations\\Entity'),
        new AnnotationToAttribute('Neos\\Flow\\Annotations\\FlushesCaches'),
        new AnnotationToAttribute('Neos\\Flow\\Annotations\\Identity'),
        new AnnotationToAttribute('Neos\\Flow\\Annotations\\IgnoreValidation'),
        new AnnotationToAttribute('Neos\\Flow\\Annotations\\Inject'),
        new AnnotationToAttribute('Neos\\Flow\\Annotations\\InjectConfiguration'),
        new AnnotationToAttribute('Neos\\Flow\\Annotations\\Internal'),
        new AnnotationToAttribute('Neos\\Flow\\Annotations\\Introduce'),
        new AnnotationToAttribute('Neos\\Flow\\Annotations\\Lazy'),
        new AnnotationToAttribute('Neos\\Flow\\Annotations\\MapRequestBody'),
        new AnnotationToAttribute('Neos\\Flow\\Annotations\\Pointcut'),
        new AnnotationToAttribute('Neos\\Flow\\Annotations\\Proxy'),
        new AnnotationToAttribute('Neos\\Flow\\Annotations\\Scope'),
        new AnnotationToAttribute('Neos\\Flow\\Annotations\\Session'),
        new AnnotationToAttribute('Neos\\Flow\\Annotations\\Signal'),
        new AnnotationToAttribute('Neos\\Flow\\Annotations\\SkipCsrfProtection'),
        new AnnotationToAttribute('Neos\\Flow\\Annotations\\Transient'),
        new AnnotationToAttribute('Neos\\Flow\\Annotations\\Validate'),
        new AnnotationToAttribute('Neos\\Flow\\Annotations\\ValidationGroups'),
        new AnnotationToAttribute('Neos\\Flow\\Annotations\\ValueObject'),
    ]);
};
