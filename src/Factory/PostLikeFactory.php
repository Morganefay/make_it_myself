<?php

namespace App\Factory;

use App\Entity\PostLike;
use App\Repository\PostLikeRepository;
use Zenstruck\Foundry\RepositoryProxy;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;

/**
 * @method static PostLike|Proxy createOne(array $attributes = [])
 * @method static PostLike[]|Proxy[] createMany(int $number, $attributes = [])
 * @method static PostLike|Proxy find($criteria)
 * @method static PostLike|Proxy findOrCreate(array $attributes)
 * @method static PostLike|Proxy first(string $sortedField = 'id')
 * @method static PostLike|Proxy last(string $sortedField = 'id')
 * @method static PostLike|Proxy random(array $attributes = [])
 * @method static PostLike|Proxy randomOrCreate(array $attributes = [])
 * @method static PostLike[]|Proxy[] all()
 * @method static PostLike[]|Proxy[] findBy(array $attributes)
 * @method static PostLike[]|Proxy[] randomSet(int $number, array $attributes = [])
 * @method static PostLike[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method static PostLikeRepository|RepositoryProxy repository()
 * @method PostLike|Proxy create($attributes = [])
 */
final class PostLikeFactory extends ModelFactory
{
    public function __construct()
    {
        parent::__construct();

        // TODO inject services if required (https://github.com/zenstruck/foundry#factories-as-services)
    }

    protected function getDefaults(): array
    {
        return [
            'user' => UserFactory::random(),
            'post' => PostFactory::random()
        ];
    }

    protected function initialize(): self
    {
        // see https://github.com/zenstruck/foundry#initialization
        return $this
            // ->afterInstantiate(function(PostLike $postLike) {})
        ;
    }

    protected static function getClass(): string
    {
        return PostLike::class;
    }
}
