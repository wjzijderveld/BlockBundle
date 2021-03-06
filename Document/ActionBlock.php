<?php

namespace Symfony\Cmf\Bundle\BlockBundle\Document;

use Doctrine\ODM\PHPCR\Mapping\Annotations as PHPCRODM;

use Symfony\Component\HttpFoundation\Request;
use Sonata\BlockBundle\Block\BlockContextInterface;

/**
 * Block that renders a controller action
 *
 * @PHPCRODM\Document(referenceable=true)
 */
class ActionBlock extends BaseBlock
{
    /**
     * The Symfony action string
     *
     * @var string
     * @PHPCRODM\String
     */
    protected $actionName;

    /**
     * List of request attributes or parameters to pass to the subrequest when
     * calling the action.
     *
     * The arguments are fetched with $request->get(), so if a parameter is
     * missing in the original request, the value will be null in the
     * subrequest.
     *
     * Defaults to pass on the _locale
     *
     * @var string[]
     * @PHPCRODM\String(multivalue=true)
     */
    protected $requestParams = array('_locale');

    /**
     * {@inheritDoc}
     */
    public function getType()
    {
        return 'cmf.block.action';
    }

    /**
     * @return string
     */
    public function getActionName()
    {
        return $this->actionName;
    }

    /**
     * @param string $actionName
     */
    public function setActionName($actionName)
    {
        $this->actionName = $actionName;
    }

    /**
     * Initialize default values if not explicitly set.
     *
     * @PHPCRODM\PrePersist
     */
    public function mergeDefaults()
    {
        if (null === $this->actionName) {
            $this->actionName = $this->getDefaultActionName();
        }
        if (null === $this->requestParams) {
            $this->requestParams = $this->getRequestParams();
        }
    }

    /**
     * Set the list of request parameter names to pass to the subrequest when
     * rendering the action.
     *
     * @param array $params
     */
    public function setRequestParams(array $params)
    {
        $this->requestParams = $params;
    }

    /**
     * Set the list of request parameter names to pass to the subrequest when
     * rendering the action.
     *
     * @return array
     */
    public function getRequestParams()
    {
        return $this->requestParams;
    }

    /**
     * Extract information from the request or context to pass on to the
     * subrequest to render this action. Also adds the block and the
     * blockContext to the parameters.
     *
     * Note that the kind of subrequest the ActionBlockService is doing allows
     * to pass objects as request arguments.
     *
     * @param Request               $request      the master request
     * @param BlockContextInterface $blockContext passed in case an extending
     *      block needs the context to determine values to pass to the
     *      subrequest.
     *
     * @return array List of arguments to pass to the subrequest.
     */
    public function resolveRequestParams(Request $request, BlockContextInterface $blockContext)
    {
        $params = array();
        foreach($this->getRequestParams() as $param) {
            $params[$param] = $request->get($param);
        }
        $params['block'] = $this;
        $params['blockContext'] = $blockContext;

        return $params;
    }

    /**
     * Overload this method to define a default action name
     *
     * @return string|null
     */
    public function getDefaultActionName()
    {
        return null;
    }
}
