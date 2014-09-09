<?php
/**
 * @author    Florian Eichler <florian@eichler.co.at>
 * @author    Alexander Spengler <alexander.spengler@habanero-it.eu>
 * @copyright 2014 Florian Eichler, Alexander Spengler. All rights reserved.
 * @license   MINOR add a license
 * @version   0.1-dev
 */

namespace Elektra\SeedBundle\Controller\Notes;

use Elektra\CrudBundle\Controller\Controller;
use Elektra\SeedBundle\Entity\EntityInterface;
use Symfony\Component\Form\FormInterface;

/**
 * Class NoteController
 *
 * @package   Elektra\SeedBundle\Controller\Notes
 *
 * @version   0.1-dev
 */
class NoteController extends Controller
{

    /**
     * {@inheritdoc}
     */
    protected function getDefinition()
    {

        return $this->get('navigator')->getDefinition('Elektra', 'Seed', 'Notes', 'Note');
    }

    public function beforeAddEntity(EntityInterface $entity, FormInterface $form = null)
    {

        $noteParent = null;
        if ($form->has('noteParent')) {
            $noteParent = $form->get('noteParent')->getData();
        } else if ($form->has('group_common')) {
            if ($form->get('group_common')->has('noteParent')) {
                $noteParent = $form->get('group_common')->get('noteParent')->getData();
            }
        }
        if ($noteParent === null) {
            throw new \RuntimeException('cannot get the parent for this note');
        }

        $user = $this->get('security.context')->getToken()->getUser();
        $entity->setTimestamp(time());
        $entity->setUser($user);
        $noteParent->getNotes()->add($entity);

        return true;
    }
}