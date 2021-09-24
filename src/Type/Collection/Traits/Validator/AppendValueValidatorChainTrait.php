<?php declare(strict_types=1);

namespace LDL\Type\Collection\Traits\Validator;

use LDL\Validators\Chain\AndValidatorChain;
use LDL\Validators\Chain\ValidatorChainInterface;

trait AppendValueValidatorChainTrait
{
    /**
     * @var ValidatorChainInterface
     */
    private $_tAppendValueValidatorChain;

    //<editor-fold desc="HasAppendValueValidatorChainInterface methods">
    public function getAppendValueValidatorChain(string $class=null, iterable $validators=null): ValidatorChainInterface
    {
        if(null !== $this->_tAppendValueValidatorChain){
            return $this->_tAppendValueValidatorChain;
        }

        if(null === $class){
            $class = AndValidatorChain::class;
        }

        if(!class_exists($class)){
            throw new \InvalidArgumentException("Invalid class \"$class\"");
        }

        if(!is_subclass_of($class, ValidatorChainInterface::class)){
            throw new \InvalidArgumentException(
                sprintf(
                    'Given class must be an instance of "%s"; "%s" was given',
                    ValidatorChainInterface::class,
                    $class
                )
            );
        }

        $this->_tAppendValueValidatorChain = new $class($validators);

        $this->getBeforeResolveKey()->append(function($collection, $item, $key){
            $this->_tAppendValueValidatorChain->validate($item, $key, $collection);
        });

        return $this->_tAppendValueValidatorChain;
    }
    //</editor-fold>
}