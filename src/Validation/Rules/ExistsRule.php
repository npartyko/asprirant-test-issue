<?php


namespace App\Validation\Rules;



use App\Entity\Movie;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Respect\Validation\Rules\AbstractRule;


class ExistsRule extends AbstractRule
{

    protected $em;
    protected $model;
    protected $param;


    public function __construct(EntityManagerInterface $em, string $model, string $param)
    {
        $this->em = $em;
        $this->model = $model;
        $this->param = $param;
    }


    public function validate($input): bool
    {
        $result = $this->em->getRepository($this->model)
            ->createQueryBuilder('t')
            ->select('count(t.'. $this->param .')')
            ->andWhere('t.'. $this->param .' = :param')
            ->setParameter('param', $input)
            ->getQuery()
            ->getSingleScalarResult();

        return (bool) $result;
    }


}