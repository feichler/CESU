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
use Elektra\SeedBundle\Entity\Companies\Partner;
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
        $noteParent = $form->get('noteParent')->getData();
        $user = $this->get('security.context')->getToken()->getUser();
        $entity->setTimestamp(time());
        $entity->setUser($user);
        $noteParent->getNotes()->add($entity);
//        if($noteParent instanceof Partner) {
////            echo 'got partner!!!<br />';
//
//        } else {
////            echo 'something else<br />';
//        }
//        echo get_class($entity);
//        echo $this->getCrud()->getDefinition()->getName();
//        echo '<br /><br />';
//        var_dump($form->get('noteParent')->getData()->getId());
//        echo '<br /><br />';
////        echo $form->get('parent')->getData();
//            exit();
return true;
        return parent::beforeAddEntity($entity, $form); // TODO: Change the autogenerated stub
    }
}