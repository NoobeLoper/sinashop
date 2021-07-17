<?php


namespace Modules\Cart\Helpers;


use Illuminate\Support\Facades\Facade;

/**
 * Cart
 * @package App\Helpers\Cart
 * @method static bool has($id)
 * @method static Collection all();
 * @method static array get($id);
 * @method static Cart put(array $value, Model $object = null)
 */
class Cart extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'cart';
    }
}
