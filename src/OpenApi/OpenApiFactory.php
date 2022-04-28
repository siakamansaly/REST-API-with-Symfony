<?php
namespace App\OpenApi;

use ApiPlatform\Core\OpenApi\Factory\OpenApiFactoryInterface;
use ApiPlatform\Core\OpenApi\OpenApi;

class OpenApiFactory implements OpenApiFactoryInterface
{
    CONST SUMMARY="Hidden";
    private $decorated;

    public function __construct(OpenApiFactoryInterface $decorated)
    {
        $this->decorated = $decorated;
    }

    public function __invoke(array $context = []): OpenApi
    {
        $openApi = $this->decorated->__invoke($context);
        
        $openApi = $this->disableGetPath($openApi);
        
        return $openApi;
    }

    public function disableGetPath(OpenApi $openApi): OpenApi
    {
        // ** @var PathItem $path */
        foreach ($openApi->getPaths()->getPaths() as $key => $path) {
            if($path->getGet() && $path->getGet()->getSummary() === self::SUMMARY) {
                $openApi->getPaths()->addPath($key, $path->withGet(null));
            }
        }
        return $openApi;
    }


}
