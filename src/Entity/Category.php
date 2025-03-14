<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[
    ORM\Entity(repositoryClass: CategoryRepository::class),
    ORM\Index(columns: ['path'], name: 'idx_path'),
]
class Category
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 512)]
    private ?string $name = null;

    /**
     * @var Collection<int, Product>
     */
    #[ORM\OneToMany(targetEntity: Product::class, mappedBy: 'category')]
    private Collection $products;

    #[ORM\ManyToOne(targetEntity: self::class)]
    private ?self $parent = null;
    private int $parent_id;

    // /**
    //  * @var Collection<int, self>
    //  */
    // #[ORM\OneToMany(targetEntity: self::class, mappedBy: 'parent')]
    // private Collection $children;

    #[ORM\Column(length: 1024, unique: true)]
    private ?string $path = null;

    #[ORM\Column]
    private ?bool $is_leaf = null;

    public function __construct()
    {
        $this->products = new ArrayCollection();
        // $this->children = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, Product>
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function addProduct(Product $product): static
    {
        if (!$this->products->contains($product)) {
            $this->products->add($product);
            $product->setCategory($this);
        }

        return $this;
    }

    public function removeProduct(Product $product): static
    {
        if ($this->products->removeElement($product)) {
            // set the owning side to null (unless already changed)
            if ($product->getCategory() === $this) {
                $product->setCategory(null);
            }
        }

        return $this;
    }

    public function getParent(): ?self
    {
        return $this->parent;
    }

    public function setParent(?self $parent): static
    {
        $this->parent = $parent;

        return $this;
    }

    public function setParentId(int $parentId): static
    {
        $this->parent_id = $parentId;

        return $this;
    }

    // /**
    //  * @return Collection<int, self>
    //  */
    // public function getChildren(): Collection
    // {
    //     return $this->children;
    // }

    // public function addChild(self $child): static
    // {
    //     if (!$this->children->contains($child)) {
    //         $this->children->add($child);
    //         $child->setParent($this);
    //     }

    //     return $this;
    // }

    // public function removeChild(self $child): static
    // {
    //     if ($this->children->removeElement($child)) {
    //         // set the owning side to null (unless already changed)
    //         if ($child->getParent() === $this) {
    //             $child->setParent(null);
    //         }
    //     }

    //     return $this;
    // }

    public function getPath(): ?string
    {
        return $this->path;
    }

    public function setPath(string $path): static
    {
        $this->path = $path;

        return $this;
    }

    public function isLeaf(): ?bool
    {
        return $this->is_leaf;
    }

    public function setIsLeaf(bool $is_leaf): static
    {
        $this->is_leaf = $is_leaf;

        return $this;
    }
}
