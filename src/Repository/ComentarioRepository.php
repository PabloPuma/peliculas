<?php

namespace App\Repository;

use App\Entity\Comentario;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Comentario|null find($id, $lockMode = null, $lockVersion = null)
 * @method Comentario|null findOneBy(array $criteria, array $orderBy = null)
 * @method Comentario[]    findAll()
 * @method Comentario[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ComentarioRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Comentario::class);
    }
    public function Buscarcomentario ($id_user){
        return $this->getEntityManager()
            ->createQuery('
                SELECT comentario.id, peliculas.titulo, peliculas.id
                FROM App:Comentario  comentario
                JOIN comentario.peliculas peliculas
                WHERE comentario.user =:user_id
            ')
            ->setParameter('user_id',$id_user)
            ->setMaxResults(10)
            ->getResult();
    }

    public function BuscarcomentarioDeUNPost($post_id){
        return $this->getEntityManager()
            ->createQuery('
                SELECT comentario.comentario, comentario.fecha_publicacion, user.nombre
                FROM App:comentario  comentario
                JOIN comentario.user user
                WHERE comentario.peliculas =:peliculas_id
            ')
            ->setParameter('peliculas_id',$post_id);
    }
//JOIN Comentario.user user
//WHERE Comentario.peliculas =:peliculas.id



    // /**
    //  * @return Comentario[] Returns an array of Comentario objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Comentario
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
