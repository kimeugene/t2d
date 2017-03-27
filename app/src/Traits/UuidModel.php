<?php
namespace App\Traits;
use Ramsey\Uuid\Uuid;

/**
 * Trait UuidModel
 *
 * https://humaan.com/blog/using-uuids-with-eloquent-in-laravel/
 *
 * @package App\Traits
 */
trait UuidModel
{
    /**
     * Binds creating/saving events to create UUIDs (and also prevent them from being overwritten).
     *
     * @return void
     */
    public static function bootUuidModel()
    {
        static::creating(function ($model) {
            $model->setAttribute($model->getKeyName(), Uuid::uuid4()->toString());
        });

        static::saving(function ($model) {
            // What's that, trying to change the UUID huh?  Nope, not gonna happen.
            $original_uuid = $model->getOriginal($model->getKeyName());

            if ($original_uuid !== $model->getAttribute($model->getKeyName())) {
                $model->setAttribute($model->getKeyName(), $original_uuid);
            }
        });
    }
}