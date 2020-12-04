<?php declare(strict_types=1);

require '../vendor/autoload.php';

use LDL\Type\Collection\Types\String\StringCollection;
use LDL\Type\Exception\TypeMismatchException;

$str = new StringCollection();

echo "Append item with value: '123'\n";
$str->append('123');

echo "Append item with value: '456'\n";
$str->append('456');

echo "Append item with value: (integer) 789, exception must be thrown\n";

try {

    $str->append(789);

}catch(TypeMismatchException $e){

    echo "EXCEPTION: {$e->getMessage()}\n";

}

echo "Iterate through elements:\n";

foreach($str as $string){
    echo "String: $string"."\n";
}