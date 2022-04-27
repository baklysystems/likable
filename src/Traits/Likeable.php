<?php

namespace AmrNRD\Likeable\Traits;

use Illuminate\Database\Eloquent\Model;

/**
 * Trait Likeable.
 */
trait Likeable
{
    /**
     * @param \Illuminate\Database\Eloquent\Model $user
     *
     * @return bool
     */
    public function isLikedBy(Model $user)
    {
        if ( is_a($user, config('auth.providers.users.model')) ) {

            if ($this->relationLoaded('likers')) {
                return $this->likers->contains($user);
            }

            return ($this->relationLoaded('likes') ? $this->likes : $this->likes())
                    ->where(\config('like.user_foreign_key'), $user->getKey())->count() > 0;
        }

        return false;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function likes()
    {
        return $this->morphMany(config('like.like_model'), 'likeable');
    }

    /**
     * Return followers.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function likers()
    {
        return $this->belongsToMany(
            config('auth.providers.users.model'),
            config('like.likes_table'),
            'likeable_id',
            config('like.user_foreign_key')
        )
            ->where('likeable_type', $this->getMorphClass());
    }
}
