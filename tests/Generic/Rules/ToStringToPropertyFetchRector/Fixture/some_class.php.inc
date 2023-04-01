<?php

$someValue = new SomeObject;
$someOtherValue = new SomeOtherObject;
$result = (string) $someValue;
$result = $someValue->__toString();
$result = 'foo' . $someValue . $someOtherValue;
$result = $someValue . 'bar' . $someOtherValue;

?>
-----
<?php

$someValue = new SomeObject;
$someOtherValue = new SomeOtherObject;
$result = $someValue->propertyName;
$result = $someValue->propertyName;
$result = 'foo' . $someValue->propertyName . $someOtherValue;
$result = $someValue->propertyName . 'bar' . $someOtherValue;

?>
