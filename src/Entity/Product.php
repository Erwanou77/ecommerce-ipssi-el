<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $excerpt = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $image = null;

    #[ORM\Column(nullable: true)]
    private ?int $quantity = null;

    #[ORM\Column(nullable: true)]
    private ?float $price = null;

    #[ORM\Column(nullable: true)]
    private ?int $statut = null;

    #[ORM\ManyToOne(inversedBy: 'products')]
    private ?User $seller = null;

    #[ORM\ManyToMany(targetEntity: User::class, mappedBy: 'favoris')]
    private Collection $favorisUser;

    #[ORM\OneToMany(mappedBy: 'product', targetEntity: CartsProducts::class)]
    private Collection $cartsProducts;

    public function __construct()
    {
        $this->favorisUser = new ArrayCollection();
        $this->cartsProducts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getExcerpt(): ?string
    {
        return $this->excerpt;
    }

    public function setExcerpt(?string $excerpt): self
    {
        $this->excerpt = $excerpt;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(?int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(?float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getStatut(): ?int
    {
        return $this->statut;
    }

    public function setStatut(?int $statut): self
    {
        $this->statut = $statut;

        return $this;
    }

    public function getSeller(): ?User
    {
        return $this->seller;
    }

    public function setSeller(?User $seller): self
    {
        $this->seller = $seller;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getFavorisUser(): Collection
    {
        return $this->favorisUser;
    }

    public function addFavorisUser(User $favorisUser): self
    {
        if (!$this->favorisUser->contains($favorisUser)) {
            $this->favorisUser->add($favorisUser);
            $favorisUser->addFavori($this);
        }

        return $this;
    }

    public function removeFavorisUser(User $favorisUser): self
    {
        if ($this->favorisUser->removeElement($favorisUser)) {
            $favorisUser->removeFavori($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, CartsProducts>
     */
    public function getCartsProducts(): Collection
    {
        return $this->cartsProducts;
    }

    public function addCartsProduct(CartsProducts $cartsProduct): self
    {
        if (!$this->cartsProducts->contains($cartsProduct)) {
            $this->cartsProducts->add($cartsProduct);
            $cartsProduct->setProduct($this);
        }

        return $this;
    }

    public function removeCartsProduct(CartsProducts $cartsProduct): self
    {
        if ($this->cartsProducts->removeElement($cartsProduct)) {
            // set the owning side to null (unless already changed)
            if ($cartsProduct->getProduct() === $this) {
                $cartsProduct->setProduct(null);
            }
        }

        return $this;
    }
}
