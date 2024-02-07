<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Controller\Application\UpdateApplicationController;
use App\Repository\ApplicationRepository;
use App\State\PostApplicationProcessor;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ApplicationRepository::class)]
#[ApiResource(
    operations: [
        new GetCollection(),

        new Get(security: "is_granted('ROLE_CLIENT')"),
        new Post(
            security: "is_granted('ROLE_CLIENT')",
            validationContext: ['groups' => ['Default', 'application:post']],
            processor: PostApplicationProcessor::class
        ),

        new Put(
            controller: UpdateApplicationController::class,
            security: "is_granted('ROLE_MANAGER')",
            validationContext: ['groups' => ['Default', 'application:patch']]
        ),
    ],
    paginationEnabled: false,
    security: "is_granted('ROLE_USER')",
)]
class Application
{
    public const STATUS_NEW = 'new';
    public const STATUS_IN_PROGRESS = 'in_progress';
    public const STATUS_COMPLETED = 'completed';

    public const STATUSES = [
        self::STATUS_NEW,
        self::STATUS_IN_PROGRESS,
        self::STATUS_COMPLETED,
    ];

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[ApiProperty(securityPostDenormalize: "is_granted('ROLE_CLIENT')")]
    #[Assert\NotBlank(groups: ['application:post'])]
    private ?string $topic = null;

    #[ORM\Column(length: 255)]
    #[ApiProperty(securityPostDenormalize: "is_granted('ROLE_CLIENT')")]
    #[Assert\NotBlank(groups: ['application:post'])]
    private ?string $message = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[ApiProperty(securityPostDenormalize: "is_granted('ROLE_MANAGER')")]
    #[Assert\NotBlank(groups: ['application:patch'])]
    private ?string $comment = null;

    #[ORM\Column(length: 255, nullable: false)]
    #[ApiProperty(securityPostDenormalize: "is_granted('ROLE_MANAGER')")]
    #[Assert\Choice(self::STATUSES, groups: ['application:patch'])]
    #[Assert\NotBlank(groups: ['application:patch'])]
    private ?string $status = null;

    #[ORM\ManyToOne(inversedBy: 'applications')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $owner = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTopic(): ?string
    {
        return $this->topic;
    }

    public function setTopic(string $topic): static
    {
        $this->topic = $topic;

        return $this;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(string $message): static
    {
        $this->message = $message;

        return $this;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $comment): static
    {
        $this->comment = $comment;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(?string $status): static
    {
        $this->status = $status;
        return $this;
    }

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(?User $owner): static
    {
        $this->owner = $owner;

        return $this;
    }
}
