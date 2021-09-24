<?php declare(strict_types=1);

namespace LDL\Type\Collection;

use LDL\Framework\Base\Collection\Contracts\AppendableInterface;
use LDL\Framework\Base\Collection\Contracts\AppendInPositionInterface;
use LDL\Framework\Base\Collection\Contracts\BeforeAppendInterface;
use LDL\Framework\Base\Collection\Contracts\BeforeRemoveInterface;
use LDL\Framework\Base\Collection\Contracts\BeforeReplaceInterface;
use LDL\Framework\Base\Collection\Contracts\BeforeResolveKeyInterface;
use LDL\Framework\Base\Collection\Contracts\CollectionInterface;
use LDL\Framework\Base\Collection\Contracts\KeyFilterInterface;
use LDL\Framework\Base\Collection\Contracts\LockAppendInterface;
use LDL\Framework\Base\Collection\Contracts\LockRemoveInterface;
use LDL\Framework\Base\Collection\Contracts\LockReplaceInterface;
use LDL\Framework\Base\Collection\Contracts\RemoveByCallbackInterface;
use LDL\Framework\Base\Collection\Contracts\RemoveByKeyInterface;
use LDL\Framework\Base\Collection\Contracts\RemoveByValueInterface;
use LDL\Framework\Base\Collection\Contracts\ReplaceByCallbackInterface;
use LDL\Framework\Base\Collection\Contracts\ReplaceByKeyInterface;
use LDL\Framework\Base\Collection\Contracts\ReplaceByValueInterface;
use LDL\Framework\Base\Contracts\LockableObjectInterface;

interface TypedCollectionInterface extends CollectionInterface, LockableObjectInterface, BeforeAppendInterface, AppendableInterface, BeforeResolveKeyInterface, AppendInPositionInterface, LockAppendInterface, BeforeRemoveInterface, RemoveByKeyInterface, RemoveByValueInterface, RemoveByCallbackInterface, LockRemoveInterface, BeforeReplaceInterface, ReplaceByKeyInterface, ReplaceByValueInterface, ReplaceByCallbackInterface, LockReplaceInterface, KeyFilterInterface
{

}