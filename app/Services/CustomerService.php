<?php

namespace App\Services;

use App\Classes\Currency;
use App\Events\AfterCustomerAccountHistoryCreatedEvent;
use App\Events\CustomerAfterUpdatedEvent;
use App\Events\CustomerRewardAfterCouponIssuedEvent;
use App\Events\CustomerRewardAfterCreatedEvent;
use App\Exceptions\NotAllowedException;
use App\Exceptions\NotFoundException;
use App\Models\Coupon;
use App\Models\Customer;
use App\Models\CustomerAccountHistory;
use App\Models\CustomerAddress;
use App\Models\CustomerCoupon;
use App\Models\CustomerGroup;
use App\Models\CustomerReward;
use App\Models\Order;
use App\Models\RewardSystem;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class CustomerService
{
    /**
     * get all the defined customers
     *
     * @param array customers
     */
    public function get( $id = null )
    {
        if ( $id === null ) {
            return Customer::with( 'billing' )
                ->with( 'shipping' )
                ->where( 'group_id', '<>', null )
                ->orderBy( 'created_at', 'desc' )->get();
        } else {
            try {
                return Customer::with( 'addresses' )->find( $id );
            } catch ( Exception $exception ) {
                throw new Exception( __( 'Unable to find the customer using the provided id.' ) );
            }
        }
    }

    /**
     * Retrieve the recent active customers.
     *
     * @param int $limit
     * @return Collection
     */
    public function getRecentlyActive( $limit = 30 )
    {
        return Customer::with( 'billing' )
            ->with( 'shipping' )
            ->where( 'group_id', '<>', null )
            ->limit( $limit )
            ->notBanned()
            ->orderBy( 'updated_at', 'desc' )->get();
    }

    /**
     * delete a specific customer
     * using a provided id
     *
     * @param int customer id
     * @return array resopnse
     */
    public function delete( $id )
    {
        /**
         * @todo dispatch event while
         * deleting a customer
         * @todo check if the action is performed by
         * an authorized user
         */
        $customer = Customer::find( $id );

        if ( ! $customer instanceof Customer ) {
            throw new NotFoundException( __( 'Unable to find the customer using the provided id.' ) );
        }

        Customer::find( $id )->delete();
        CustomerAddress::where( 'customer_id', $id )->delete();

        return [
            'status' => 'success',
            'message' => __( 'The customer has been deleted.' ),
        ];
    }

    /**
     * Search customers having the defined argument.
     */
    public function search( int | string $argument ): Collection
    {
        $parts = preg_split('/\s+/', str($argument));
        $customers = Customer::with( 'billing' )
            ->with( 'shipping' )
            ->where(function(Builder $query) use ($parts) {
                foreach ($parts as $part) {
                    $query->where(function(Builder $subquery) use ($part) {
                        $subquery
                            ->where( 'first_name', 'like', '%' . $part . '%' )
                            ->orWhere( 'last_name', 'like', '%' . $part . '%' );
                    });
                }
            })
            ->orWhere( 'email', 'like', '%' . $argument . '%' )
            ->orWhere( 'phone', 'like', '%' . $argument . '%' )
            ->notBanned()
            ->limit(10)
            ->get();

        return $customers;
    }

    public function precheckCustomers( $fields, $id = null )
    {
        if ( $id === null ) {
            /**
             * Let's find if a similar customer exist with
             * the provided email
             */
            $customer = Customer::byEmail( $fields[ 'email' ] )->first();
        } else {
            /**
             * Let's find if a similar customer exist with
             * the provided  and which is not the actula customer.
             */
            $customer = Customer::byEmail( $fields[ 'email' ] )
                ->where( 'nexopos_users.id', '<>', $id )
                ->first();
        }

        if ( $customer instanceof Customer && ! empty( $fields[ 'email' ] ) ) {
            throw new NotAllowedException( sprintf( __( 'The email "%s" is already used for another customer.' ), $fields[ 'email' ] ) );
        }
    }

    /**
     * Create customer fields.
     */
    public function create( array $fields ): array
    {
        $this->precheckCustomers( $fields );

        /**
         * saving a customer
         * by looping only
         * the allowed fields
         */
        $customer = new Customer;

        foreach ( $fields as $field => $value ) {
            if ( $field !== 'address' ) {
                $customer->$field = $value;
            }
        }

        $customer->author = Auth::id();
        $customer->save();

        /**
         * Let's check if the customer
         * address informations has been provided
         */
        $address = $fields[ 'address' ];

        if ( is_array( $address ) ) {
            foreach ( $address as $type => $fields ) {
                if ( in_array( $type, [ 'billing', 'shipping' ] ) ) {
                    $customerAddress = new CustomerAddress;
                    $customerAddress->type = $type;
                    $customerAddress->author = Auth::id();
                    $customerAddress->customer_id = $customer->id;

                    foreach ( $fields as $field => $value ) {
                        $customerAddress->$field = $value;
                    }

                    $customerAddress->save();
                }
            }
        }

        $customer = $customer->fresh();
        $customer->addresses;

        return [
            'status' => 'success',
            'message' => __( 'The customer has been created.' ),
            'data' => compact( 'customer' ),
        ];
    }

    /**
     * Update a specific customer
     * using a provided informations.
     */
    public function update( int $id, array $fields ): array
    {
        $customer = Customer::find( $id );

        if ( ! $customer instanceof Customer ) {
            throw new NotFoundException( __( 'Unable to find the customer using the provided ID.' ) );
        }

        $this->precheckCustomers( $fields, $id );

        foreach ( $fields as $field => $value ) {
            if ( $field !== 'address' ) {
                $customer->$field = $value;
            }
        }

        $customer->author = Auth::id();
        $customer->update();

        /**
         * Let's check if the customer
         * address informations has been provided
         */
        $address = $fields[ 'address' ];

        if ( is_array( $address ) ) {
            foreach ( $address as $type => $addressFields ) {
                if ( in_array( $type, [ 'billing', 'shipping' ] ) ) {
                    $customerAddress = CustomerAddress::from( $customer, $type )->first();

                    /**
                     * If the customer address type has
                     * already been saved before
                     */
                    if ( $customerAddress instanceof CustomerAddress ) {
                        $customerAddress->type = $type;
                        $customerAddress->author = Auth::id();
                        $customerAddress->customer_id = $customer->id;

                        foreach ( $addressFields as $field => $value ) {
                            $customerAddress->$field = $value;
                        }

                        $customerAddress->save();
                    } else {
                        $customerAddress = new CustomerAddress;
                        $customerAddress->type = $type;
                        $customerAddress->author = Auth::id();
                        $customerAddress->customer_id = $customer->id;

                        foreach ( $addressFields as $field => $value ) {
                            $customerAddress->$field = $value;
                        }

                        $customerAddress->save();
                    }
                }
            }
        }

        $customer = $customer->fresh();
        $customer->addresses;

        return [
            'status' => 'success',
            'message' => __( 'The customer has been edited.' ),
            'data' => compact( 'customer' ),
        ];
    }

    /**
     * get customers addresses
     */
    public function getCustomerAddresses( int $id ): array
    {
        $customer = $this->get( $id );

        return $customer->addresses;
    }

    /**
     * Delete a specific customer
     * havign the defined email
     */
    public function deleteUsingEmail( string $email ): array
    {
        $customer = Customer::byEmail( $email )->first();

        if ( ! $customer instanceof Customer ) {
            throw new NotFoundException( __( 'Unable to find the customer using the provided email.' ) );
        }

        CustomerAddress::where( 'customer_id', $customer->id )->delete();
        $customer->delete();

        return [
            'status' => 'success',
            'message' => __( 'The customer has been deleted.' ),
        ];
    }

    /**
     * save a customer transaction.
     */
    public function saveTransaction( Customer $customer, string $operation, float $amount, string $description = '', array $details = [] ): array
    {
        if ( in_array( $operation, [
            CustomerAccountHistory::OPERATION_DEDUCT,
            CustomerAccountHistory::OPERATION_PAYMENT,
        ]) && $customer->account_amount - $amount < 0 ) {
            throw new NotAllowedException( sprintf(
                __( 'Not enough credits on the customer account. Requested : %s, Remaining: %s.' ),
                Currency::fresh( abs( $amount ) ),
                Currency::fresh( $customer->account_amount ),
            ) );
        }

        /**
         * We'll pull the last recent record
         * and base on that we'll populate the
         * previous_amount
         */
        $beforeRecord = CustomerAccountHistory::where( 'customer_id', $customer->id )
            ->orderBy( 'id', 'desc' )
            ->first();

        $previousNextAmount = $beforeRecord instanceof CustomerAccountHistory ? $beforeRecord->next_amount : 0;

        /**
         * We'll make sure to define that are the previous and next amount.
         */
        if ( in_array( $operation, [
            CustomerAccountHistory::OPERATION_DEDUCT,
            CustomerAccountHistory::OPERATION_PAYMENT,
        ] ) ) {
            $next_amount = $previousNextAmount - $amount;
        } elseif ( in_array( $operation, [
            CustomerAccountHistory::OPERATION_ADD,
            CustomerAccountHistory::OPERATION_REFUND,
        ]) ) {
            $next_amount = $previousNextAmount + $amount;
        }

        $customerAccountHistory = new CustomerAccountHistory;
        $customerAccountHistory->operation = $operation;
        $customerAccountHistory->customer_id = $customer->id;
        $customerAccountHistory->previous_amount = $previousNextAmount;
        $customerAccountHistory->amount = $amount;
        $customerAccountHistory->next_amount = $next_amount;
        $customerAccountHistory->description = $description;
        $customerAccountHistory->author = Auth::id();

        /**
         * We can now optionally provide
         * additional details while storing the customer history
         */
        if ( ! empty( $details ) ) {
            foreach ( $details as $key => $value ) {
                /**
                 * Some details must be sensitive
                 * and not changed.
                 */
                if ( ! in_array( $key, [
                    'id',
                    'next_amount',
                    'previous_amount',
                    'amount',
                ]) ) {
                    $customerAccountHistory->$key = $value;
                }
            }
        }

        $customerAccountHistory->save();

        event( new AfterCustomerAccountHistoryCreatedEvent( $customerAccountHistory ) );

        return [
            'status' => 'success',
            'message' => __( 'The customer account has been updated.' ),
            'data' => compact( 'customerAccountHistory' ),
        ];
    }

    /**
     * Updates the customers account.
     */
    public function updateCustomerAccount( CustomerAccountHistory $history ): void
    {
        if ( in_array( $history->operation, [
            CustomerAccountHistory::OPERATION_DEDUCT,
            CustomerAccountHistory::OPERATION_PAYMENT,
        ] ) ) {
            $history->customer->account_amount -= $history->amount;
        } elseif ( in_array( $history->operation, [
            CustomerAccountHistory::OPERATION_ADD,
            CustomerAccountHistory::OPERATION_REFUND,
        ]) ) {
            $history->customer->account_amount += $history->amount;
        }

        $history->customer->save();
    }

    public function increasePurchases( Customer $customer, $value )
    {
        $customer->purchases_amount += $value;
        $customer->save();

        CustomerAfterUpdatedEvent::dispatch( $customer );

        return $customer;
    }

    public function decreasePurchases( Customer $customer, $value )
    {
        $customer->purchases_amount -= $value;
        $customer->save();

        CustomerAfterUpdatedEvent::dispatch( $customer );

        return $customer;
    }

    public function canReduceCustomerAccount( Customer $customer, $value )
    {
        if ( $customer->account_amount - $value < 0 ) {
            throw new NotAllowedException( __( 'The customer account doesn\'t have enough funds to proceed.' ) );
        }
    }

    /**
     * compute a reward assigned to a customer group
     * and issue a coupon if necessary
     *
     * @param Order $order
     * @return void
     */
    public function computeReward( Order $order )
    {
        $order->load( 'customer.group.reward' );

        $reward = $order->customer->group->reward;

        if ( $reward instanceof RewardSystem ) {
            $points = 0;
            $reward->rules->each( function( $rule ) use ( $order, &$points ) {
                if ( $order->total >= $rule->from && $order->total <= $rule->to ) {
                    $points += (float) $rule->reward;
                }
            });

            $currentReward = CustomerReward::where( 'reward_id', $reward->id )
                ->where( 'customer_id', $order->customer->id )
                ->first();

            if ( ! $currentReward instanceof CustomerReward ) {
                $currentReward = new CustomerReward;
                $currentReward->customer_id = $order->customer->id;
                $currentReward->reward_id = $reward->id;
                $currentReward->points = 0;
                $currentReward->target = $reward->target;
                $currentReward->reward_name = $reward->name;
            }

            $currentReward->points += $points;
            $currentReward->save();
            $currentReward->load( 'reward' );

            CustomerRewardAfterCreatedEvent::dispatch( $currentReward, $order->customer, $reward );
        }
    }

    public function applyReward( CustomerReward $customerReward, Customer $customer, RewardSystem $reward )
    {
        /**
         * the user has reached or exceeded the reward.
         * we'll issue a new coupon and update the customer
         * point counter
         */
        if ( $customerReward->points >= $customerReward->target ) {
            $coupon = $reward->coupon;

            if ( $coupon instanceof Coupon ) {
                $customerCoupon = new CustomerCoupon;
                $customerCoupon->coupon_id = $coupon->id;
                $customerCoupon->name = $coupon->name;
                $customerCoupon->active = true;
                $customerCoupon->code = $coupon->code . '-' . ns()->date->format( 'dmi' );
                $customerCoupon->customer_id = $customer->id;
                $customerCoupon->limit_usage = $coupon->limit_usage;
                $customerCoupon->author = $customerReward->reward->author;
                $customerCoupon->save();

                $customerReward->points = abs( $customerReward->points - $customerReward->target );
                $customerReward->save();

                CustomerRewardAfterCouponIssuedEvent::dispatch( $customerCoupon );
            } else {
                /**
                 * @var NotificationService
                 */
                $notify = app()->make( NotificationService::class );
                $notify->create([
                    'title' => __( 'Issuing Coupon Failed' ),
                    'description' => sprintf(
                        __( 'Unable to apply a coupon attached to the reward "%s". It looks like the coupon no more exists.' ),
                        $reward->name
                    ),
                    'identifier' => 'coupon-issuing-issue-' . $reward->id,
                    'url' => ns()->route( 'ns.dashboard.rewards-edit', [ 'reward' => $reward->id ]),
                ])->dispatchForGroupNamespaces([ 'admin', 'nexopos.store.administrator' ]);
            }
        }
    }

    /**
     * load specific coupon using a code and optionnaly
     * the customer id for verification purpose.
     *
     * @param string $code
     * @param string $customer_id
     * @return array
     */
    public function loadCoupon( string $code, $customer_id = null )
    {
        $coupon = Coupon::code( $code )
            ->with( 'products.product' )
            ->with( 'categories.category' )
            ->with( 'customers' )
            ->with([ 'customerCoupon' => function( $query ) use ( $customer_id ) {
                $query->where( 'customer_id', $customer_id );
            }])
            ->with( 'groups' )
            ->first();

        if ( $coupon instanceof Coupon ) {
            if ( $coupon->customers()->count() > 0 ) {
                $customers_id    =   $coupon->customers()
                    ->get( 'customer_id' )
                    ->map( fn( $coupon ) => $coupon->customer_id )
                    ->flatten()
                    ->toArray();

                if ( ! in_array( $customer_id, $customers_id ) ) {
                    throw new Exception( __( 'The provided coupon cannot be loaded for that customer.' ) );
                }
            }

            if ( $coupon->groups()->count() > 0 ) {
                $customer      =   Customer::with( 'group' )->find( $customer_id );
                $groups_id  =   $coupon->groups()
                    ->get( 'group_id' )
                    ->map( fn( $coupon ) => $coupon->group_id )
                    ->flatten()
                    ->toArray();

                if ( ! in_array( $customer->group->id, $groups_id ) ) {
                    throw new Exception( __( 'The provided coupon cannot be loaded for the group assigned to the selected customer.' ) );
                }
            }

            return $coupon;
        }

        throw new Exception( __( 'Unable to find a coupon with the provided code.' ) );
    }

    /**
     * @todo this method doesn't seems used.
     */
    public function setCouponHistory( $fields, Coupon $coupon )
    {
        $customerCoupon = new CustomerCoupon;
        $customerCoupon->name = $coupon->name;
        $customerCoupon->limit_usage = $coupon->limit_usage;
        $customerCoupon->code = $coupon->code;
        $customerCoupon->coupon_id = $coupon->id;
        $customerCoupon->customer_id = $fields[ 'customer_id' ];
        $customerCoupon->order_id = $fields[ 'order_id' ];
        $customerCoupon->author = Auth::id();
        $customerCoupon->save();

        $this->setActiveStatus( $customerCoupon );

        return [
            'status' => 'sucess',
            'message' => __( 'The coupon has been updated.' ),
        ];
    }

    public function setActiveStatus( CustomerCoupon $coupon )
    {
        if ( $coupon->limit_usage > $coupon->usage ) {
            $coupon->active = true;
        }

        if ( (int) $coupon->limit_usage === 0 ) {
            $coupon->active = true;
        }

        $coupon->save();
    }

    public function deleteRelatedCustomerCoupon( Coupon $coupon )
    {
        CustomerCoupon::couponID( $coupon->id )
            ->get()
            ->each( function( $coupon ) {
                $coupon->delete();
            });
    }

    /**
     * Will refresh the owed amount
     * for the provided customer
     */
    public function updateCustomerOwedAmount( Customer $customer )
    {
        $unpaid = Order::where( 'customer_id', $customer->id )->whereIn( 'payment_status', [
            Order::PAYMENT_UNPAID,
        ])->sum( 'total' );

        /**
         * Change here will be negative, so we
         * want to be an absolute value.
         */
        $orders = Order::where( 'customer_id', $customer->id )->whereIn( 'payment_status', [
            Order::PAYMENT_PARTIALLY,
        ]);

        $change = abs( $orders->sum( 'change' ) );

        $customer->owed_amount = ns()->currency->getRaw( $unpaid + $change );
        $customer->save();
    }

    /**
     * Create customer group using
     * provided fields
     *
     * @param array $fields
     * @param array $group
     * @return array $response
     */
    public function createGroup( $fields, CustomerGroup $group = null )
    {
        if ( $group === null ) {
            $group = CustomerGroup::where( 'name', $fields[ 'name' ] )->first();
        }

        if ( ! $group instanceof CustomerGroup ) {
            $group = new CustomerGroup;
        }

        foreach ( $fields as $name => $value ) {
            $group->$name = $value;
        }

        $group->author = Auth::id();
        $group->save();

        return [
            'status' => 'sucecss',
            'message' => __( 'The group has been created.' ),
            'data' => compact( 'group' ),
        ];
    }

    /**
     * return the customer account operation label
     *
     * @param string $label
     * @return string
     */
    public function getCustomerAccountOperationLabel( $label )
    {
        switch ( $label ) {
            case CustomerAccountHistory::OPERATION_ADD: return __( 'Crediting' );
                break;
            case CustomerAccountHistory::OPERATION_DEDUCT: return __( 'Deducting' );
                break;
            case CustomerAccountHistory::OPERATION_PAYMENT: return __( 'Order Payment' );
                break;
            case CustomerAccountHistory::OPERATION_REFUND: return __( 'Order Refund' );
                break;
            default: return __( 'Unknown Operation' );
                break;
        }
    }

    /**
     * Will increase the customer purchase
     * when an order is flagged as paid
     */
    public function increaseCustomerPurchase( Order $order )
    {
        if ( in_array( $order->payment_status, [
            Order::PAYMENT_PAID,
        ]) ) {
            $this->increasePurchases(
                customer: $order->customer,
                value: $order->total
            );
        }
    }

    /**
     * If a customer tries to use a coupon. That coupon is assigned to his account
     * with the rule defined by the parent coupon.
     */
    public function assignCouponUsage( int $customer_id, Coupon $coupon ): CustomerCoupon
    {
        $customerCoupon     =   CustomerCoupon::where( 'customer_id', $customer_id )->where( 'coupon_id', $coupon->id )->first();

        if( ! $customerCoupon instanceof CustomerCoupon ) {
            $customerCoupon                 =   new CustomerCoupon;
            $customerCoupon->customer_id    =   $customer_id;
            $customerCoupon->coupon_id      =   $coupon->id;
            $customerCoupon->name           =   $coupon->name;
            $customerCoupon->author         =   $coupon->author;
            $customerCoupon->active         =   true;
            $customerCoupon->code           =   $coupon->code;
            $customerCoupon->limit_usage    =   $coupon->limit_usage;
            $customerCoupon->save();
        }

        return $customerCoupon;
    }

    public function checkCouponExistence( array $couponConfig, $fields ): Coupon
    {
        $coupon = Coupon::find( $couponConfig[ 'id' ] );

        if ( ! $coupon instanceof Coupon ) {
            throw new NotFoundException( sprintf( __( 'Unable to find a reference to the attached coupon : %s' ), $couponConfig[ 'name' ] ?? __( 'N/A' ) ) );
        }

        /**
         * we'll check if the coupon is still valid.
         */
        if ( $coupon->valid_until !== null && ns()->date->lessThan( Carbon::parse( $coupon->valid_until ) ) ) {
            throw new NotAllowedException( sprintf( __( 'Unable to use the coupon %s as it has expired.' ), $coupon->name ) );
        }

        /**
         * @todo check products on the order
         * @todo check category on the order
         */

        /**
         * We'll now check if we're about to use
         * the coupon during a period is supposed to be active.
         *
         * @todo Well we're doing this because we don't yet have a proper time picker. As we're using a date time picker
         * we're extracting the hours from it :(.
         */
        $hourStarts     =   ! empty( $coupon->valid_hours_start ) ? Carbon::parse( $coupon->valid_hours_start )->format( 'H:i' ) : null;
        $hoursEnds      =   ! empty( $coupon->valid_hours_end ) ? Carbon::parse( $coupon->valid_hours_end )->format( 'H:i' ) : null;

        if (
            $hourStarts !== null &&
            $hoursEnds !== null ) {

            $todayStartDate =   ns()->date->format( 'Y-m-d' ) . ' ' . $hourStarts;
            $todayEndDate   =   ns()->date->format( 'Y-m-d' ) . ' ' . $hoursEnds;

            if (
                ns()->date->between(
                    date1: Carbon::parse( $todayStartDate ),
                    date2: Carbon::parse( $todayEndDate )
                )
            ) {
                throw new NotAllowedException( sprintf( __( 'Unable to use the coupon %s at this moment.' ), $coupon->name ) );
            }
        }

        /**
         * We'll now check if the customer has an ongoing
         * coupon with the provided parameters
         */
        $customerCoupon     =   CustomerCoupon::where( 'coupon_id', $couponConfig[ 'id' ] )
            ->where( 'customer_id', $fields[ 'customer_id' ] ?? 0 )
            ->first();

        if ( $customerCoupon instanceof CustomerCoupon ) {
            if ( ! $customerCoupon->active ) {
                throw new NotAllowedException( sprintf( __( 'You\'re not allowed to use this coupon as it\'s no longer active' ) ) );
            }

            /**
             * We're trying to use a coupon that is already exhausted
             * this should be prevented here.
             */

            if ( $customerCoupon->limit_usage > 0 && $customerCoupon->usage + 1 > $customerCoupon->limit_usage ) {
                throw new NotAllowedException( sprintf( __( 'You\'re not allowed to use this coupon it has reached the maximum usage allowed.' ) ) );
            }
        }

        return $coupon;
    }

    /**
     * Will check if a coupon is a eligible for an increase
     * and will perform a usage increase.
     */
    public function increaseCouponUsage( CustomerCoupon $customerCoupon )
    {
        if ( $customerCoupon->limit_usage > 0 ) {
            if ( $customerCoupon->usage + 1 < $customerCoupon->limit_usage ) {
                $customerCoupon->usage += 1;
                $customerCoupon->save();
            } elseif ( $customerCoupon->usage + 1 === $customerCoupon->limit_usage ) {
                $customerCoupon->usage += 1;
                $customerCoupon->active = false;
                $customerCoupon->save();
            }
        }
    }
}
