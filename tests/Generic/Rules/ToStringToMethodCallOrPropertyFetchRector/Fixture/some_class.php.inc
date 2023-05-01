<?php

$someValue = new SomeObject;
$someOtherValue = new SomeOtherObject;
$result = (string) $someValue;
$result = $someValue->__toString();
$result = 'foo' . $someValue;
$result = $someValue . 'bar';
$result = sprintf('some %s format', $someValue);
$result = sprintf('some %s other %s format', 'replaced', $someValue);
$result = (string) $someOtherValue;
$result = $someOtherValue->__toString();
$result = 'foo' . $someOtherValue;
$result = $someOtherValue . 'bar';
$result = sprintf('some %s format', $someOtherValue);
$result = sprintf('some %s other %s format', 'replaced', $someOtherValue);
?>
-----
<?php

$someValue = new SomeObject;
$someOtherValue = new SomeOtherObject;
$result = $someValue->methodName();
$result = $someValue->methodName();
$result = 'foo' . $someValue->methodName();
$result = $someValue->methodName() . 'bar';
$result = sprintf('some %s format', $someValue->methodName());
$result = sprintf('some %s other %s format', 'replaced', $someValue->methodName());
$result = $someOtherValue->propertyName;
$result = $someOtherValue->propertyName;
$result = 'foo' . $someOtherValue->propertyName;
$result = $someOtherValue->propertyName . 'bar';
$result = sprintf('some %s format', $someOtherValue->propertyName);
$result = sprintf('some %s other %s format', 'replaced', $someOtherValue->propertyName);
?>
