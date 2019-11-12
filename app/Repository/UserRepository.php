<?php
/**
 * @author Mike Alvarez <mike@hallohallo.ph>
 */

namespace App\Repository;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Support\Repository\EloquentRepository;

/**
 * eloquent repository class for user model
 */
class UserRepository extends EloquentRepository
{
    /**
     * create instance
     *
     * @param \App\Models\User $user
     */
    public function __construct(User $user)
    {
        parent::__construct($user);
    }

    /**
     * store newly created user to the storage
     *
     * @param  array $attributes
     *
     * @return void
     */
    public function store(array $attributes)
    {
        $model = $this->newModel();

        // place attribute values to the model
        foreach ($attributes as $attribute => $value) {
            $model->setAttribute($attribute, $value);
        }

        return ($model->save()) ? $model : null;
    }

    /**
     * for fetching all users
     *
     *
     * @return void
     */
    public function showAll($type)
    {
        $model = $this->newModel();

        return $model->where('type', $type)
                    ->where('is_deleted', 'N')
                    ->orderBy('created_at', 'desc')                
                    ->paginate(10, ['*']);
    }

    public function showUser($id, $type)
    {
        $model = $this->newModel();

        $results = $model->where('id', $id)
                        ->where('type', $type)
                        ->first();

        if (is_null($results)) {
            return;
        }

        return $results;
    }

    /**
     * for user counts
     *
     *
     * @return void
     */
    public function countData($type = null)
    {
        $model = $this->newModel();

        if (is_null($type)) {
            return $model
                ->all()
                ->count();
        }
        
        return $model
                ->where('type', $type)
                ->get()
                ->count();
    }

    public function checkId($id)
    {
        $model = $this->newModel();

        $result = $model->where('id',$id)
            ->where('is_deleted', 'N')
            ->first();

        if (is_null($result)) {
            return;
        }

        return $result;
    }

    public function checkAccountIfExisting ($data, $type = null, $isSearch = false)
    {
        $model = $this->newModel();

        $result = $model->where(function ($query) use ($data, $isSearch){

            foreach ($data as $attribute => $value) {
                if ($isSearch) {
                    $query->where($attribute, 'like' ,"%{$value}%");
                } else {
                    $query->where($attribute, $value);
                }
           }
        });

        if ($type != null){
            $result->where('type', $type);
        }

        if ($isSearch) {
            return $result->paginate(10, ['*']);
        }

        return $result->first();
    }

    /**
     * checks if current email is already exists in the repository
     *
     * @param  string                $email
     * @param  \App\Models\User|null $user
     *
     * @return boolean
     */
    public function isEmailExists($email, User $user = null)
    {
        $builder = $this->builder()
            ->where('email', $email);

        if (! is_null($user)) {
            $builder->where($user->getKeyName(), $user->getKey());
        }

        return $builder->first() ? true : false;
    }

    /**
     * @param  string  $number
     *
     * @return boolean
     */
    public function isValidCellphoneFormat($number)
    {
        // checks if current length === 10 means we check if current number starts with 9 then followed by 9 digit number
        // check if not does not start with the following ff 09|+63|63
        if (strlen($number) === 10 || preg_match('/^(?!09|\+63|63)/', $number)) {
            $assert = preg_match('/^(9)[0-9]{9}$/', $number);
        } else {
            $assert = preg_match('/^(0|\+63|63)[0-9]{10}$/', $number);
        }

        return $assert ? true : false;
    }

    /**
     * @param  string $number
     *
     * @return string
     */
    public function normalizeCellphone($number)
    {
        /**
         * we assume that this argument number pass through self::isValidFormat validator method
         * so no need to validate here and proceed on normalizing the data in order
         * to match specifics
         *
         * e.g 09221711247   - nothing to change
         *     +639221711247 - +(63) to '0'
         *     6309221711247 - (63) to '0'
         *     9221711247    - concatenate leading '0'
         */

        if (strlen($number) === 10) {
            $phone = sprintf('0%s', $number);
        } elseif (preg_match('/^(\+63|63)/', $number)) {
            $phone = preg_replace('/^(\+63|63)/', '0', $number);
        } else {
            $phone = $number;
        }

        return $phone;
    }

    /**
     * @param  string $number
     *
     * @return array
     */
    public function createCellphoneFormats($number)
    {
        /**
         * creates list of possible format of cellphone number and can be used in the query
         *
         * e.g (09221711247) becomes ff.
         *     - 09221711247
         *     - +639221711247
         *     - 639221711247
         *     - 9221711247
         */
        $list = [];

        // remove ^(+63|63|0)
        $number = preg_replace('/^(\+63|63|0)/', '', $number);

        // append formats
        $list[] = $number;
        $list[] = sprintf('0%s', $number);
        $list[] = sprintf('+63%s', $number);
        $list[] = sprintf('63%s', $number);

        return $list;
    }
}
