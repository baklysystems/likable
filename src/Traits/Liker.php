<?php

namespace AmrNRD\Likeable\Traits;

use Illuminate\Database\Eloquent\Model;
use AmrNRD\Likeable\Like;

trait Liker
{
    /**
     * @param \Illuminate\Database\Eloquent\Model $model
     * @return Like|null
     */
    public function like(Model $model)
    {
        if ($this->isLikeable($model)) {
            if($this->doLike($model)){
                $this->unlike($model);
            } else {
                $this->setLikeable($model,'like');
            }
        }else{
            $like = app(config('like.like_model'));
            $like->{config('like.user_foreign_key')} = $this->getKey();
            $like->like = 'like';
            $model->likes()->save($like);
        }
        return true;
    }

    /**
     * @param \Illuminate\Database\Eloquent\Model $model
     * @return Like|null
     */
    public function dislike(Model $model)
    {
        if ($this->isLikeable($model)) {
            if($this->doesnotLike($model)){
                $this->unlike($model);
            }else{
                $this->setLikeable($model,'dislike');
            }
        }else{
            $like = app(config('like.like_model'));
            $like->{config('like.user_foreign_key')} = $this->getKey();
            $like->like = 'dislike';
            $model->likes()->save($like);
        }
        return true;
    }

    /**
     * @param \Illuminate\Database\Eloquent\Model $model
     *
     * @return null
     */
    private function unlike(Model $model)
    {
        $relation = $model->likes()
            ->where('likeable_id', $model->getKey())
            ->where('likeable_type', $model->getMorphClass())
            ->where(config('like.user_foreign_key'), $this->getKey())
            ->first();

        if ($relation) {
            $relation->delete();
        }
    }

    /**
     * @param \Illuminate\Database\Eloquent\Model $model
     * @param string $type
     *
     * @return null
     */
    private function setLikeable(Model $model,$type = 'like')
    {
        app(config('like.like_model'))::query()
        ->where('user_id',$this->id)
        ->where('likeable_id',$model->id)
        ->where('likeable_type',$model->getMorphClass())
        ->update(['like'=> $type]);

        return true;
    }


    /**
     * @param \Illuminate\Database\Eloquent\Model $model
     *
     * @return bool
     */
    private function isLikeable(Model $model)
    {
        $likeable_relation = $this->relationLoaded('likes') ? $this->likes : $this->likes();

        return $likeable_relation
                ->where('likeable_id', $model->getKey())
                ->where('likeable_type', $model->getMorphClass())
                ->exists();
    }

    /**
     * @param \Illuminate\Database\Eloquent\Model $model
     *
     * @return bool
     */
    public function doLike(Model $model)
    {
        $likeable_relation = $this->relationLoaded('likes') ? $this->likes : $this->likes();

        return $likeable_relation
                ->where('likeable_id', $model->getKey())
                ->where('likeable_type', $model->getMorphClass())
                ->where('like', 'like')
                ->exists();
    }

    /**
     * @param \Illuminate\Database\Eloquent\Model $model
     *
     * @return bool
     */
    public function doesnotLike(Model $model)
    {
        $likeable_relation = $this->relationLoaded('likes') ? $this->likes : $this->likes();
        return $likeable_relation
                ->where('likeable_id', $model->getKey())
                ->where('likeable_type', $model->getMorphClass())
                ->where('like', 'dislike')
                ->exists();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function likes()
    {
        return $this->hasMany( config('like.like_model'), config('like.user_foreign_key'), $this->getKeyName() );
    }
}
