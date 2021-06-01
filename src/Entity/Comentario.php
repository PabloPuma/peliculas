<?php

namespace App\Entity;

use App\Repository\ComentarioRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ComentarioRepository::class)
 */
class Comentario
{
    const COMENTARIO_AGREGADO_EXITOSAMENTE = '¡Comentario Agregado exitosamente!';

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $comentario;

    /**
     * @ORM\Column(type="datetime")
     */
    private $fecha_publicacion;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="comentario")
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Peliculas", inversedBy="comentario")
     */
    private $peliculas;

    /**
     * Posts constructor.
     */
    public function __construct()
    {
        $this->fecha_publicacion= new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getComentario(): ?string
    {
        return $this->comentario;
    }

    public function setComentario(string $comentario): self
    {
        $this->comentario = $comentario;

        return $this;
    }

    public function getFechaPublicacion(): ?\DateTimeInterface
    {
        return $this->fecha_publicacion;
    }

    public function setFechaPublicacion(\DateTimeInterface $fecha_publicacion): self
    {
        $this->fecha_publicacion = $fecha_publicacion;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     */
    public function setUser($user): void
    {
        $this->user = $user;
    }

    /**
     * @return mixed
     */
    public function getPeliculas()
    {
        return $this->peliculas;
    }

    /**
     * @param mixed $peliculas
     */
    public function setPeliculas($peliculas): void
    {
        $this->peliculas = $peliculas;
    }

}
