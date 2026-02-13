<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Enum\UserRole;
use App\Service\UserPasswordHasher;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class UserCrudController extends AbstractCrudController
{
    public function __construct(
        protected UserPasswordHasher $hasher,
    ) {}

    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        $crud = Crud::new();

        $crud->setEntityLabelInSingular('User');
        $crud->setEntityLabelInPlural('Users');
        $crud->setDefaultSort(['id' => 'DESC']);

        return $crud;
    }

    public function configureFields(string $pageName): iterable
    {
        $id = IdField::new('id', 'Id.');
        $id->hideOnForm();

        yield $id;
        yield TextField::new('name', 'Name');

        $password = TextField::new('plainPassword', 'Password');
        $password->setFormType(PasswordType::class);
        $password->setRequired($pageName === Action::NEW);
        $password->onlyOnForms();

        yield $password;

        $roles = ChoiceField::new('roles', 'Roles');
        $roles->setChoices(UserRole::getChoices());
        $roles->allowMultipleChoices();
        $roles->autocomplete();

        yield $roles;

        $updatedAt = DateTimeField::new('updatedAt', 'Updated at');
        $updatedAt->setFormat('dd/MM/yyyy HH:mm:ss');
        $updatedAt->hideOnForm();

        yield $updatedAt;

        $createdAt = DateTimeField::new('createdAt', 'Created at');
        $createdAt->setFormat('dd/MM/yyyy HH:mm:ss');
        $createdAt->hideOnForm();

        yield $createdAt;
    }

    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        if ($entityInstance instanceof User) {
            $this->hasher->hash($entityInstance);
        }

        parent::persistEntity($entityManager, $entityInstance);
    }

    public function updateEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        if ($entityInstance instanceof User) {
            $this->hasher->hash($entityInstance);
        }

        parent::updateEntity($entityManager, $entityInstance);
    }
}
