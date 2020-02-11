<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\BiensRepository")
 * @UniqueEntity("titre")
 * @Vich\Uploadable
 */
class Biens
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;
    /**
     * @ORM\Column(type="string")
     */
    private $file;

    /**
     * @var string|null
     * @ORM\Column(type="string", length=255)
     */
    private $imageName;
    
    /**
     * @var File|null
     * @Vich\UploadableField(mapping="biens_image", fileNameProperty="imageName")
     */
    private $imageFile;

    /**
     * @var \DateTimeInterface|null
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;

    /**
     * @var string|null
     * @Assert\Length(min=5, max=255)
     * @ORM\Column(type="string", length=255)
     */
    private $titre;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\Column(type="integer")
     * @Assert\Range(
     *      min=15,
     *      max=500,
     *      minMessage = "Soyez honnête pas en dessous de 15m²"
     * )
     */
    private $surface;

    /**
     * @ORM\Column(type="integer")
     */
    private $piece;

    /**
     * @ORM\Column(type="integer")
     */
    private $chambre;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $ville;

    /**
     * @ORM\Column(type="integer")
     * @Assert\Regex("/^[0-9]{5}$/")
     */
    private $codePostale;

    /**
     * @ORM\Column(type="integer")
     */
    private $prix;

    /**
     * @ORM\Column(type="boolean", options={"default": false})
     */
    private $vendu = false;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Option", inversedBy="biens")
     */
    private $options;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->options = new ArrayCollection();
    }
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getSurface(): ?int
    {
        return $this->surface;
    }

    public function setSurface(int $surface): self
    {
        $this->surface = $surface;

        return $this;
    }

    public function getPiece(): ?int
    {
        return $this->piece;
    }

    public function setPiece(int $piece): self
    {
        $this->piece = $piece;

        return $this;
    }

    public function getChambre(): ?int
    {
        return $this->chambre;
    }

    public function setChambre(int $chambre): self
    {
        $this->chambre = $chambre;

        return $this;
    }

    public function getVille(): ?string
    {
        return $this->ville;
    }

    public function setVille(string $ville): self
    {
        $this->ville = $ville;

        return $this;
    }

    public function getCodePostale(): ?int
    {
        return $this->codePostale;
    }

    public function setCodePostale(int $codePostale): self
    {
        $this->codePostale = $codePostale;

        return $this;
    }

    public function getPrix(): ?int
    {
        return $this->prix;
    }

    public function getFormattedPrix(): string
    {
        return number_format($this->prix, 0, '', ' ');
    }

    public function setPrix(int $prix): self
    {
        $this->prix = $prix;

        return $this;
    }

    public function getVendu(): ?bool
    {
        return $this->vendu;
    }

    public function setVendu(bool $vendu): self
    {
        $this->vendu = $vendu;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return Collection|Option[]
     */
    public function getOptions(): Collection
    {
        return $this->options;
    }

    public function addOption(Option $option): self
    {
        if (!$this->options->contains($option)) {
            $this->options[] = $option;
            $option->addBien($this);
        }

        return $this;
    }

    public function removeOption(Option $option): self
    {
        if ($this->options->contains($option)) {
            $this->options->removeElement($option);
            $option->removeBien($this);
        }

        return $this;
    }

    /**
     * Get the value of imageFile
     *
     * @return  File|null
     */ 
    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    /**
     * Set the value of imageFile
     * 
     * @param  File|UploadedFile|null  $imageFile
     *
     * @return  self
     */ 
    public function setImageFile(?File $imageFile = null)
    {
        $this->imageFile = $imageFile;

        if ($this->imageFile instanceof UploadedFile) {
            $this->updatedAt = new \DateTime('now');
        }
        return $this;
    }

    /**
     * Get the value of imageName
     *
     * @return  string|null
     */ 
    public function getImageName()
    {
        return $this->imageName;
    }

    /**
     * Set the value of imageName
     *
     * @param  string|null  $imageName
     *
     * @return  self
     */ 
    public function setImageName($imageName)
    {
        $this->imageName = $imageName;

        return $this;
    }

    /**
     * Get the value of file
     */ 
    public function getFile()
    {
        return $this->file;
    }

    /**
     * Set the value of file
     *
     * @return  self
     */ 
    public function setFile($file)
    {
        $this->file = $file;

        return $this;
    }
}
