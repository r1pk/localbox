<?php

namespace App\Controller\Admin;

use App\Entity\File;
use App\Exception\FileStorageAccessException;
use App\Service\FileDeleter;
use App\Service\FileSizeFormatter;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class FileCrudController extends AbstractCrudController
{
    public function __construct(
        protected FileSizeFormatter $formatter,
        protected FileDeleter $deleter,
    ) {}

    public static function getEntityFqcn(): string
    {
        return File::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        $crud = Crud::new();

        $crud->setEntityLabelInSingular('File');
        $crud->setEntityLabelInPlural('Files');
        $crud->setDefaultSort(['id' => 'DESC']);

        return $crud;
    }

    public function configureActions(Actions $actions): Actions
    {
        $actions->disable(Action::EDIT);
        $actions->disable(Action::NEW);

        return $actions;
    }

    public function configureFields(string $pageName): iterable
    {
        $id = IdField::new('id', 'Id.');
        $id->hideOnForm();

        yield $id;

        $token = TextField::new('token', 'Token');
        $token->hideOnForm();

        yield $token;

        $groupToken = TextField::new('groupToken', 'Group token');
        $groupToken->hideOnForm();

        yield $groupToken;

        $clientFilename = TextField::new('clientFilename', 'Client filename');
        $clientFilename->hideOnForm();

        yield $clientFilename;

        $serverFilename = TextField::new('serverFilename', 'Server filename');
        $serverFilename->hideOnForm();

        yield $serverFilename;

        $size = NumberField::new('size', 'Size');
        $size->formatValue(fn (?int $bytes) => $this->formatter->format($bytes));
        $size->hideOnForm();

        yield $size;

        $createdAt = DateTimeField::new('createdAt', 'Created at');
        $createdAt->setFormat('dd/MM/yyyy HH:mm:ss');
        $createdAt->hideOnForm();

        yield $createdAt;
    }

    /**
     * @throws FileStorageAccessException
     */
    public function deleteEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        if ($entityInstance instanceof File) {
            $this->deleter->delete($entityInstance);

            return;
        }

        parent::deleteEntity($entityManager, $entityInstance);
    }
}
