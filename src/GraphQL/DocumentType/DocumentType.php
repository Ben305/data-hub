<?php

/**
 * Pimcore
 *
 * This source file is available under two different licenses:
 * - GNU General Public License version 3 (GPLv3)
 * - Pimcore Enterprise License (PEL)
 * Full copyright and license information is available in
 * LICENSE.md which is distributed with this source code.
 *
 *  @copyright  Copyright (c) Pimcore GmbH (http://www.pimcore.org)
 *  @license    http://www.pimcore.org/license     GPLv3 and PEL
 */

namespace Pimcore\Bundle\DataHubBundle\GraphQL\DocumentType;

use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\UnionType;
use Pimcore\Bundle\DataHubBundle\GraphQL\Service;
use Pimcore\Bundle\DataHubBundle\GraphQL\Traits\ServiceTrait;
use Pimcore\Model\Document;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

class DocumentType extends UnionType implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    use ServiceTrait;

    protected $types;

    /**
     * @var EmailType
     */
    protected $emailType;

    /**
     * @var LinkType
     */
    protected $linkType;

    /**
     * @var SnippetType
     */
    protected $snippetType;

    /**
     * @var HardlinkType
     */
    protected $hardlinkType;

    /**
     * @var PageType
     */
    protected $pageType;

    /**
     * DocumentType constructor.
     * @param Service $graphQlService
     * @param PageType $pageType
     * @param LinkType $linkType
     * @param EmailType $emailType
     * @param HardlinkType $hardlinkType
     * @param SnippetType $snippetType
     * @param array $config
     */
    public function __construct(Service $graphQlService, PageType $pageType, LinkType $linkType, EmailType $emailType, HardlinkType $hardlinkType, SnippetType $snippetType, $config = [])
    {
        $this->pageType = $pageType;
        $this->hardlinkType = $hardlinkType;
        $this->linkType = $linkType;
        $this->emailType = $emailType;
        $this->snippetType = $snippetType;

        $this->types = [$emailType, $hardlinkType, $linkType, $pageType, $snippetType];
        $this->setGraphQLService($graphQlService);

        parent::__construct($config);
    }


    /**
     * @return array
     *
     * @throws \Exception
     */
    public function getTypes()
    {
        return $this->types;
    }

    /**
     * @inheritdoc
     */
    public function resolveType($element, $context, ResolveInfo $info)
    {
        $element = Document::getById($element["id"]);
        if ($element instanceof Document\Page) {
            return $this->pageType;
        } else if ($element instanceof Document\Link) {
            return $this->linkType;
        } else if ($element instanceof Document\Email) {
            return $this->emailType;
        } else if ($element instanceof Document\Hardlink) {
            return $this->hardlinkType;
        } else if ($element instanceof Document\Snippet) {
            return $this->snippetType;
        }

        return null;
    }
}
