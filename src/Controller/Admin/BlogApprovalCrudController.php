<?php

namespace App\Controller\Admin;

use App\Enum\StatusType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FilterCollection;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\SearchDto;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\RedirectResponse;

class BlogApprovalCrudController extends BlogCrudController
{
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->onlyOnIndex(),
            TextField::new('title'),
            TextEditorField::new('content')->formatValue(function ($value, $entity) {
                return strip_tags($value);
            }),
            AssociationField::new('author'),
            DateTimeField::new('createdAt')->onlyOnIndex(),
            DateTimeField::new('updatedAt')->onlyOnIndex()
        ];
    }

    public function createIndexQueryBuilder(
        SearchDto $searchDto,
        EntityDto $entityDto,
        FieldCollection $fields,
        FilterCollection $filters
    ): QueryBuilder {
        return parent::createIndexQueryBuilder($searchDto, $entityDto, $fields, $filters)
            ->andWhere('entity.status = :status')
            ->setParameter('status', StatusType::WAITING);
    }

    public function configureActions(Actions $actions): Actions
    {
        $approvedAction = Action::new('approve', 'Approve', 'fas fa-check')
            ->linkToCrudAction('approve')
            ->setCssClass('btn btn-success')
            ->setHtmlAttributes([
                'style' => 'line-height: 20px;'
            ]);

        return $actions
            ->disable(Action::NEW)
            ->add(Crud::PAGE_INDEX, $approvedAction);
    }

    public function approve(AdminContext $context, AdminUrlGenerator $adminUrlGenerator): RedirectResponse
    {
        $blog = $context->getEntity()->getInstance();

        $url = $adminUrlGenerator
            ->setController(__CLASS__)
            ->setAction(Crud::PAGE_INDEX)
            ->generateUrl();

        $blog->setStatus(StatusType::APPROVED);

        $this->getEntityManager()->flush();

        $this->addFlash('success', 'Your blog approval has been successfully completed.');

        return $this->redirect($url);
    }

    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        parent::persistEntity($entityManager, $entityInstance);

        $this->addFlash('success', 'Blog has been successfully created.');
    }

    public function updateEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        parent::updateEntity($entityManager, $entityInstance);

        $this->addFlash('success', 'Blog has been successfully updated.');
    }
}
