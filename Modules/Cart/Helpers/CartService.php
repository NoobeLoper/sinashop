<?php


namespace Modules\Cart\Helpers;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cookie;
use Modules\Discount\Entities\Discount;

class CartService
{
    protected $cart;

    public function __construct()
    {
        //Get Cart As Session
        // $this->cart = session()->get('cart') ?? collect([]);

        //Get Cart As Cookie
        $cart = collect(json_decode(request()->cookie('cart'), true));

        //New Structure for Cookie after using discount code
        $this->cart = $cart->count() ? $cart : collect([
            'items' => [],
            'discount' => null
        ]);
        return $this;

    }

    /** Put Prodocut in Cart With Id, Model_Type, ...
    * @param array $value
    * @param null $object
    * @return $this
    */
    public function put(array $value, $object = null)
    {
        if (! is_null($object) && $object instanceof Model)
        {
            $value = array_merge($value, [
                'id' => Str::random(10),
                'subject_id' => $object->id,
                'subject_type' => get_class($object),
                'discount_percent' => 0
            ]);
        } elseif(! isset($value['id'])) {
            $value = array_merge($value, [
                'id' => Str::random(10),
            ]);
        }

        $this->cart['items'] = collect($this->cart['items'])->put($value['id'], $value);
        // session()->put('cart', $this->cart);

        //Saving Cart As Cookie
        Cookie::queue('cart', $this->cart->toJson(), 60 * 24);

        return $this;
    }

    public function update($key, $options)
    {
        $item = collect($this->get($key, false));

        if(is_numeric($options))
        {
            $item = $item->merge([
                'quantity' => $item['quantity'] + $options
            ]);
        }

        if (is_array($options)) {
            $item = $item->merge($options);
        }

        $this->put($item->toArray());

        return $this;
    }

    /** Is Cart Has Product ?
     * has
     *
     * @param  mixed $key
     * @return void
     */
    public function has($key)
    {
        if($key instanceof Model)
        {
            return ! is_null(
                collect($this->cart['items'])
                ->where('subject_id', $key->id)
                ->where('subject_type', get_class($key))
                ->first()
            );
        }
        return ! is_null(
            collect($this->cart['items'])->firstWhere('id', $key)
        );
    }

    /** Counts the number of items in the cart
     * count
     *
     * @param  mixed $key
     * @return void
     */
    public function count($key)
    {
        if (! $this->has($key)) {
            return 0;
        }
        return $this->get($key)['quantity'];
    }

    /** get The Model or Item Id
     * get
     *
     * @param  mixed $key
     * @param  mixed $withRelationship
     * @return void
     */
    public function get($key, $withRelationship = true)
    {
        $item = $key instanceof Model
        ? collect($this->cart['items'])->where('subject_id', $key->id)->where('subject_type', get_class($key))->first()
        : collect($this->cart['items'])->firstWhere('id', $key);

        return $withRelationship ? $this->withRelationshipIfExists($item) : $item;
    }

    /** Mapping/Showing All Products In Cart
     * all
     *
     * @return void
     */
    public function all()
    {
        $cart = $this->cart;
        $cart = collect($this->cart['items'])->map(function ($item) use ($cart) {
            $item = $this->withRelationshipIfExists($item);
            $item = $this->checkDiscountValidate($item, $cart['discount']);
            return $item;
        });

        // dd($cart);
        return ($cart);
    }

    /**
     * withRelationshipIfExists function
     *
     * @param  mixed $item
     * @return void
     */
    protected function withRelationshipIfExists($item)
    {
        if(isset($item['subject_id']) && isset($item['subject_type']))
        {
            $class = $item['subject_type'];
            $subject = (new $class())->find($item['subject_id']);

            $item[strToLower(class_basename($class))] = $subject;

            unset($item['subject_id']);
            unset($item['subject_type']);
        }

        return $item;
    }

    /**
     * delete
     *
     * @param  mixed $key
     * @return void
     */
    public function delete($key)
    {
        if( $this->has($key) ) {
            $this->cart['items'] = collect($this->cart['items'])->filter(function ($item) use ($key) {
                if($key instanceof Model) {
                    return ( $item['subject_id'] != $key->id ) && ( $item['subject_type'] != get_class($key) );
                }

                return $key != $item['id'];
            });

            // session()->put('cart' , $this->cart);

            //Saving Cart As Cookie
            Cookie::queue('cart', $this->cart->toJson(), 60 * 24);

            return true;
        }

        return false;
    }

    public function flush()
    {
        $this->cart = collect([
            'items' => [],
            'discount' => null,
        ]);

        // session()->put('cart' , $this->cart);
        Cookie::queue('cart', $this->cart->toJson(), 60 * 24);

        return $this;
    }

    /**
     * instance/ Changing Name of the Cart (Not Use in This Project) / Be Jaye ..cookie('cart') => cookie($name)
     *
     * @param  mixed $name
     * @return void
     */
    public function instance(string $name)
    {
        $cart = collect(json_decode(request()->cookie('cart'), true));
        $this->cart = $cart->count() ? $cart : collect([
            'items' => [],
            'discount' => null
        ]);
        // $this->name = $name;
        return $this;
    }

    public function addDiscount($discount)
    {
        $this->cart['discount'] = $discount;
        Cookie::queue('cart', $this->cart->toJson(), 60 * 24);
    }

    public function getDiscount()
    {
        return Discount::where('code', $this->cart['discount'])->first();
    }

    /**
     * Check if Discount Code Is Validate
     *
     * @param  mixed $item
     * @param  mixed $discount
     * @return void
     */
    protected function checkDiscountValidate($item, $discount)
    {
        $discount = Discount::where('code' , $discount)->first();
        if($discount && $discount->expired_at > now() ) {
            if(
                (  ! $discount->products->count() && ! $discount->categories->count() ) ||
                (in_array( $item['product']->id , $discount->products->pluck('id')->toArray() )) ||
                (array_intersect($item['product']->categories->pluck('id')->toArray(), $discount->categories->pluck('id')->toArray()))
            ) {
                $item['discount_percent'] = $discount->percent / 100;
            }
        }

        return $item;
    }

}
