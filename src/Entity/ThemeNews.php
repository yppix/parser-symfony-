<?php

namespace App\Entity;

use App\Repository\ThemeNewsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ThemeNewsRepository::class)
 */
class ThemeNews
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $theme_name;

    /**
     * @ORM\OneToMany(targetEntity=NewsList::class, mappedBy="theme_id")
     */
    private $theme_news_theme;

    public function __construct()
    {
        $this->theme_news_theme = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getThemeName(): ?string
    {
        return $this->theme_name;
    }

    public function setThemeName(string $theme_name): self
    {
        $this->theme_name = $theme_name;

        return $this;
    }

    /**
     * @return Collection|NewsList[]
     */
    public function getThemeNewsTheme(): Collection
    {
        return $this->theme_news_theme;
    }

    public function addThemeNewsTheme(NewsList $themeNewsTheme): self
    {
        if (!$this->theme_news_theme->contains($themeNewsTheme)) {
            $this->theme_news_theme[] = $themeNewsTheme;
            $themeNewsTheme->setThemeId($this);
        }

        return $this;
    }

    public function removeThemeNewsTheme(NewsList $themeNewsTheme): self
    {
        if ($this->theme_news_theme->removeElement($themeNewsTheme)) {
            // set the owning side to null (unless already changed)
            if ($themeNewsTheme->getThemeId() === $this) {
                $themeNewsTheme->setThemeId(null);
            }
        }

        return $this;
    }
}
