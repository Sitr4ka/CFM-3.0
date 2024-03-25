<?php

namespace App\Entity;

use App\Repository\ChurchesRepository;
use App\Repository\IncomesRepository;
use App\Repository\OutcomesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: ChurchesRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_USERNAME', fields: ['username'])]
#[UniqueEntity(fields: ['username'], message: 'There is already an account with this username')]
class Churches implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    private ?string $username = null;

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 50)]
    private ?string $design = null;

    #[ORM\Column(nullable: true, options: ['default' => 0])]
    private ?int $balance = 0;

    #[ORM\Column(nullable: true)]
    private ?int $incomes = null;

    #[ORM\Column(nullable: true)]
    private ?int $outgoing = null;

    #[ORM\Column(length: 30)]
    private ?string $city = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): static
    {
        $this->username = $username;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->username;
    }

    /**
     * @see UserInterface
     *
     * @return list<string>
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getDesign(): ?string
    {
        return $this->design;
    }

    public function setDesign(string $design): static
    {
        $this->design = $design;

        return $this;
    }

    public function getBalance(): ?int
    {
        return $this->balance;
    }

    public function setBalance(?int $balance): static
    {
        $this->balance = $balance;

        return $this;
    }

    public function getIncomes(): ?int
    {
        return $this->incomes;
    }

    public function setIncomes(?int $incomes): static
    {
        $this->incomes = $incomes;

        return $this;
    }

    public function getOutgoing(): ?int
    {
        return $this->outgoing;
    }

    public function setOutgoing(?int $outgoing): static
    {
        $this->outgoing = $outgoing;

        return $this;
    }
    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): static
    {
        $this->city = $city;

        return $this;
    } 

    public function updateBalance(Churches $church, EntityManagerInterface $em) : void 
    {
        $incomes = $church->getIncomes();
        $outgoing = $church->getOutgoing();
        $balance = $incomes - $outgoing;
        $church->setBalance($balance);
        $em->persist($church);
        $em->flush();
    }

    /*  $balance = $church->getBalance(); 
     *  $marge = $balance - 10000;
     * 
     * if ( !($marge > 0 && $outcomes->getAmount() < $marge)) {
        # Arrêter l'enregistrement;
        }
        Poursuivre l'enregistrement; 
     */

    public function updateIncomes(IncomesRepository $iR, EntityManagerInterface $em, Churches $church):void
    {
        $total = 0; 
        $incomes = $iR->findBy([  
            'churches' => $church,
        ]);
        foreach ($incomes as $value) {
            $total += $value->getAmount();
        }
        $totalIncomes = $church->setIncomes($total);
        $em->persist($church);
        $em->flush();
        
        //Updating balance
        $church->updateBalance($church, $em);
    } 

    public function updateOutgoing(OutcomesRepository $oR, EntityManagerInterface $em, Churches $church):void
    {
        // UPDATING TOTAL INCOMES
        $total = 0; 
        $outcomes = $oR->findBy([  
            'churches' => $church,
        ]);
        foreach ($outcomes as $value) {
            $total += $value->getAmount();
        }
        $totalOutcomes = $church->setOutgoing($total);
        $em->persist($church);
        $em->flush();

        //Updating balance
        $church->updateBalance($church, $em);
    }

}
