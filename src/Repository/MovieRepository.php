<?php
/**
 * 2019-06-28.
 */

declare(strict_types=1);

namespace App\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * Class MovieRepository.
 */
class MovieRepository extends EntityRepository
{
    public function allWithLike(int $user_id = null) {
        $result = $this->createQueryBuilder('m')
            ->getQuery()
            ->getResult();

//        dd($user_id);
        foreach ($result as $item) {
            $item->setLike($user_id ? $this->getLike($user_id, $item->getId()) : false);
        }

//        dd($result);
        return $result;
    }

    public function getLike(int $user_id, int $movie_id): bool {
        $sub = $this->createQueryBuilder('m')
            ->select('count(um)')
            ->join('m.users', 'um')
            ->where('um.id = :user_id')
            ->andwhere('m.id = :movie_id')
            ->setParameter('user_id', $user_id)
            ->setParameter('movie_id', $movie_id);

        return (bool) $sub->getQuery()->getSingleScalarResult();
    }


}
