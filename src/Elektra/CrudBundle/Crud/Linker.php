<?php

namespace Elektra\CrudBundle\Crud;

use Elektra\SeedBundle\Entity\EntityInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\HttpKernel;

final class Linker
{

    /**
     * @var Crud;
     */
    protected $crud;

    /**
     * @var string
     */
    protected $activeRoute;

    /**
     * @param Crud $crud
     */
    public function __construct(Crud $crud)
    {

        $this->crud = $crud;

        $this->activeRoute = $this->getCrud()->getRequest()->get('_route');
        if ($this->activeRoute === null) {
            $requestStack  = $this->getCrud()->getService('request_stack');
            $masterRequest = $requestStack->getMasterRequest();
            if ($masterRequest != null) {
                $this->activeRoute = $masterRequest->get('_route');
            }
        }

        $this->getCrud()->setData('route', $this->activeRoute);
    }

    /**
     * @return Crud
     */
    protected function getCrud()
    {

        return $this->crud;
    }

    /**
     * @return Navigator
     */
    protected function getNavigator()
    {

        return $this->getCrud()->getNavigator();
    }

    /**
     * @return string
     */
    public function getActiveRoute()
    {

        return $this->activeRoute;
    }

    /**
     * @return string
     */
    public function getListAddLink()
    {

        if ($this->getCrud()->hasParent()) {
            // child list view
            $parts = explode('.', $this->getCrud()->getParentRoute());
            // drop the action from the parts
            array_pop($parts);

            $routeName = implode('.', $parts) . '.' . $this->getCrud()->getDefinition()->getRouteSingular() . '.add';
            $params    = array('id' => $this->getCrud()->getParentId());

            $link = $this->getNavigator()->getLinkFromRoute($routeName, $params);
        } else {
            // from root route
            $rootDefinition = $this->getNavigator()->getDefinition($this->getActiveRoute());

            $routeName = $rootDefinition->getRouteSingular() . '.add';

            $link = $this->getNavigator()->getLinkFromRoute($routeName);
        }

        return $link;
    }

    /**
     * @param EntityInterface $entity
     *
     * @return string
     */
    public function getListViewLink($entity)
    {

        if ($this->getCrud()->hasParent()) {
            // child list view
            $parts = explode('.', $this->getCrud()->getParentRoute());
            // drop the action from the parts
            array_pop($parts);

            $routeName = implode('.', $parts) . '.' . $this->getCrud()->getDefinition()->getRouteSingular() . '.view';
            $params    = array('id' => $entity->getId());

            $link = $this->getNavigator()->getLinkFromRoute($routeName, $params);
        } else {
            // from root route
            $rootDefinition = $this->getNavigator()->getDefinition($this->getActiveRoute());

            $routeName = $rootDefinition->getRouteSingular() . '.view';
            $params    = array('id' => $entity->getId());

            $link = $this->getNavigator()->getLinkFromRoute($routeName, $params);
        }

        return $link;
    }

    /**
     * @param EntityInterface $entity
     *
     * @return string
     */
    public function getListEditLink($entity)
    {

        if ($this->getCrud()->hasParent()) {
            // child list view
            $parts = explode('.', $this->getCrud()->getParentRoute());
            // drop the action from the parts
            array_pop($parts);

            $routeName = implode('.', $parts) . '.' . $this->getCrud()->getDefinition()->getRouteSingular() . '.edit';
            $params    = array('id' => $entity->getId());

            $link = $this->getNavigator()->getLinkFromRoute($routeName, $params);
        } else {
            // from root route
            $rootDefinition = $this->getNavigator()->getDefinition($this->getActiveRoute());

            $routeName = $rootDefinition->getRouteSingular() . '.edit';
            $params    = array('id' => $entity->getId());

            $link = $this->getNavigator()->getLinkFromRoute($routeName, $params);
        }

        return $link;
    }

    /**
     * @param EntityInterface $entity
     *
     * @return string
     */
    public function getListDeleteLink($entity)
    {

        if ($this->getCrud()->hasParent()) {
            // child list view
            $parts = explode('.', $this->getCrud()->getParentRoute());
            // drop the action from the parts
            array_pop($parts);

            $routeName = implode('.', $parts) . '.' . $this->getCrud()->getDefinition()->getRouteSingular() . '.delete';
            $params    = array('id' => $entity->getId());

            $link = $this->getNavigator()->getLinkFromRoute($routeName, $params);
        } else {
            // from root route
            $rootDefinition = $this->getNavigator()->getDefinition($this->getActiveRoute());

            $routeName = $rootDefinition->getRouteSingular() . '.delete';
            $params    = array('id' => $entity->getId());

            $link = $this->getNavigator()->getLinkFromRoute($routeName, $params);
        }

        return $link;
    }

    /**
     * @param EntityInterface $entity
     *
     * @return string
     */
    public function getFormCloseLink($entity)
    {

        // have to use the route parts here -> the form itself has no parent
        $parts = explode('.', $this->getActiveRoute());
        // drop the action part
        array_pop($parts);

        if (count($parts) == 1) {
            $rootDefinition = $this->getNavigator()->getDefinition($parts[0]);

            $routeName = $rootDefinition->getRoutePlural();
            $params    = array('page' => $this->getCrud()->getData('page', 'browse'));

            $link = $this->getNavigator()->getLinkFromRoute($routeName, $params);
        } else {
            // current last part is the current entity type -> this won't be needed for the close link
            array_pop($parts);

            $routeName = implode('.', $parts) . '.view';
            $id        = $this->getCrud()->getParentId();
            $params    = array('id' => $id);

            $link = $this->getNavigator()->getLinkFromRoute($routeName, $params);
        }

        return $link;
    }

    /**
     * @param EntityInterface $entity
     *
     * @return string
     */
    public function getFormEditLink($entity)
    {

        // have to use the route parts here -> the form itself has no parent
        $parts = explode('.', $this->getActiveRoute());
        // drop the action part
        array_pop($parts);

        if (count($parts) == 1) {
            $rootDefinition = $this->getNavigator()->getDefinition($parts[0]);

            $routeName = $rootDefinition->getRouteSingular() . '.edit';
            $params    = array('id' => $entity->getId());

            $link = $this->getNavigator()->getLinkFromRoute($routeName, $params);
        } else {
            $routeName = implode('.', $parts) . '.edit';
            $params    = array('id' => $entity->getId());

            $link = $this->getNavigator()->getLinkFromRoute($routeName, $params);
        }

        return $link;
    }

    /**
     * @param EntityInterface $entity
     *
     * @return string
     */
    public function getFormDeleteLink($entity)
    {

        // have to use the route parts here -> the form itself has no parent
        $parts = explode('.', $this->getActiveRoute());
        // drop the action part
        array_pop($parts);

        if (count($parts) == 1) {
            $rootDefinition = $this->getNavigator()->getDefinition($parts[0]);

            $routeName = $rootDefinition->getRouteSingular() . '.delete';
            $params    = array('id' => $entity->getId());

            $link = $this->getNavigator()->getLinkFromRoute($routeName, $params);
        } else {
            $routeName = implode('.', $parts) . '.delete';
            $params    = array('id' => $entity->getId());

            $link = $this->getNavigator()->getLinkFromRoute($routeName, $params);
        }

        return $link;
    }

    /**
     * @param EntityInterface $entity
     *
     * @return string
     */
    public function getRedirectAfterProcess($entity, $returnToView = false)
    {

        //        echo $this->getActiveRoute();
        $link  = '';
        $parts = explode('.', $this->getActiveRoute());
        // in this case the last part must be the action -> this is not needed now
        array_pop($parts);

        if ($returnToView) {
            $routeName = implode(".", $parts) . '.view';
            $params = array('id'=> $entity->getId());
            $link = $this->getNavigator()->getLinkFromRoute($routeName,$params);
        } else {

            if (count($parts) == 1) {
                // return to root list
                $rootDefinition = $this->getNavigator()->getDefinition($parts[0]);
                $routeName      = $rootDefinition->getRoutePlural();
                $params         = array('page' => $this->getCrud()->getData('page', 'browse'));
                $link           = $this->getNavigator()->getLinkFromRoute($routeName, $params);
            } else {
                // last part of the array is the current entity type
                array_pop($parts);
                // last part is the parent
                $last = end($parts);
                // if not at a root definition, the last page was a "view"
                $routeName = implode('.', $parts) . '.view';
                // try to get the parent's id
                $returnId = $this->getCrud()->getParentId();
                if (empty($returnId)) {
                    $returnId = $entity->getId();
                }
                // generate the link
                $params = array('id' => $returnId);
                $link   = $this->getNavigator()->getLinkFromRoute($routeName, $params);
            }
        }

        return $link;
    }
}