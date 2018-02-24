<?php

namespace Ceres\Contexts;

use IO\Services\ItemLoader\Extensions\TwigLoaderPresets;
use IO\Services\ItemLoader\Services\ItemLoaderService;
use IO\Services\ItemSearch\SearchPresets\CategoryItems;
use IO\Services\ItemSearch\SearchPresets\CrossSellingItems;
use IO\Services\ItemSearch\SearchPresets\Facets;
use IO\Services\ItemSearch\Services\ItemSearchService;
use Plenty\Repositories\Models\PaginatedResult;

class CategoryItemContext extends CategoryContext implements ContextInterface
{
    use ItemListContext;
    
    public function init($params, $templateContainer)
    {
        parent::init($params, $templateContainer);

        $itemListOptions = [
            'page'          => $this->getParam( 'page', 1 ),
            'itemsPerPage'  => $this->getParam( 'itemsPerPage', $this->ceresConfig->pagination->rowsPerPage[0] * $this->ceresConfig->pagination->columnsPerPage ),
            'sorting'       => $this->getParam( 'sorting', $this->ceresConfig->sorting->defaultSorting ),
            'facets'        => $this->getParam( 'facets', '' ),
            'categoryId'    => $this->category->id
        ];

        /** @var ItemSearchService $itemSearchService */
        $itemSearchService = pluginApp( ItemSearchService::class );
        $searchResults = $itemSearchService->getResults([
            'itemList' => CategoryItems::getSearchFactory( $itemListOptions ),
            'facets'   => Facets::getSearchFactory( $itemListOptions )
        ]);

        $this->initItemList( $searchResults, $itemListOptions );
    }
}