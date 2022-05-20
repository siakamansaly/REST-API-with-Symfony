<?php
namespace App\OpenApi;

use ArrayObject;
use ApiPlatform\Core\OpenApi\Model;
use ApiPlatform\Core\OpenApi\OpenApi;
use ApiPlatform\Core\OpenApi\Model\PathItem;
use ApiPlatform\Core\OpenApi\Model\Operation;
use ApiPlatform\Core\OpenApi\Model\Parameter;
use Symfony\Component\HttpFoundation\Response;
use ApiPlatform\Core\OpenApi\Model\RequestBody;
use ApiPlatform\Core\OpenApi\Factory\OpenApiFactoryInterface;
use Symfony\Component\Security\Core\Security;

class OpenApiFactory implements OpenApiFactoryInterface
{
    const SUMMARY="Admin";
    const TOKEN="Token";
    private $decorated;
    private $security;
    

    public function __construct(OpenApiFactoryInterface $decorated, Security $security)
    {
        $this->decorated = $decorated;
        $this->security = $security;
    }

    public function __invoke(array $context = []): OpenApi
    {
        $openApi = $this->decorated->__invoke($context);
        if ($this->security->isGranted('ROLE_ADMIN')===false) {
            $openApi = $this->disableGetPath($openApi);
            $openApi = $this->disablePostPath($openApi);
            $openApi = $this->disablePatchPath($openApi);
            $openApi = $this->disableDeletePath($openApi);
        }
        
        $openApi = $this->addPathToken($openApi);
        $openApi = $this->addBearer($openApi);
        
        return $openApi;
    }

    public function disableGetPath(OpenApi $openApi): OpenApi
    {
        foreach ($openApi->getPaths()->getPaths() as $key => $path) {
            if ($path->getGet() && substr($path->getGet()->getSummary(), 0, 5) === self::SUMMARY) {
                $openApi->getPaths()->addPath($key, $path->withGet(null));
            }
        }
        return $openApi;
    }
    public function disablePostPath(OpenApi $openApi): OpenApi
    {
        foreach ($openApi->getPaths()->getPaths() as $key => $path) {
            if ($path->getPost() && substr($path->getPost()->getSummary(), 0, 5) === self::SUMMARY) {
                $openApi->getPaths()->addPath($key, $path->withPost(null));
            }
        }
        return $openApi;
    }

    public function disablePatchPath(OpenApi $openApi): OpenApi
    {
        foreach ($openApi->getPaths()->getPaths() as $key => $path) {
            if ($path->getPatch() && substr($path->getPatch()->getSummary(), 0, 5) === self::SUMMARY) {
                $openApi->getPaths()->addPath($key, $path->withPatch(null));
            }
        }
        return $openApi;
    }

    public function disableDeletePath(OpenApi $openApi): OpenApi
    {
        foreach ($openApi->getPaths()->getPaths() as $key => $path) {
            if ($path->getDelete() && substr($path->getDelete()->getSummary(), 0, 5) === self::SUMMARY) {
                $openApi->getPaths()->addPath($key, $path->withDelete(null));
            }
        }
        return $openApi;
    }

    public function addPathToken(OpenApi $openApi): OpenApi
    {
        $schemas = $openApi->getComponents()->getSchemas();
        $schemas['Authentification-credentials'] = new \ArrayObject([
            'type' => 'object',
            'properties' => [
                'username' => [
                    'type' => 'string',
                    'example' => 'johndoe@example.com',
                ],
                'password' => [
                    'type' => 'string',
                    'example' => 'password',
                ],
            ],
        ]);
        $schemas['Authentification-token'] = new \ArrayObject([
            'type' => 'object',
            'properties' => [
                'token' => [
                    'type' => 'string',
                    'readOnly' => true,
                    'example' => 'string',
                ],
            ],
        ]);

        $pathItem = new Model\PathItem(
            'JWT Token',
            null,
            null,
            null,
            null,
            new Model\Operation(
                'postTokenItem',
                ['Authentification'],
                [
                    '200' => [
                        'description' => 'Generate token',
                        'content' => [
                            'application/json' => [
                                'schema' => [
                                    '$ref' => '#/components/schemas/Authentification-token',
                                ],
                            ],
                        ],
                    ],
                    '401' => [
                        'description' => 'Error generating token',
                    ],
                ],
                'Generate token to login. (validity : 1 hour)',
                '',
                null,
                [],
                new Model\RequestBody(
                    'Generate new JWT Token (validity : 1 hour)',
                    new \ArrayObject([
                        'application/json' => [
                            'schema' => [
                                '$ref' => '#/components/schemas/Authentification-credentials',
                            ],
                        ],
                    ]),
                ),
            ),
        );
        $openApi->getPaths()->addPath('/_token', $pathItem);

        return $openApi;
    }




    public function addBearer(OpenApi $openApi): OpenApi
    {
        $schemas = $openApi->getComponents()->getSecuritySchemes();
        $schemas['bearerAuth'] = [
            'type' => 'http',
            'scheme' => 'bearer',
            'bearerFormat' => 'JWT',
        ];
        $openApi = $openApi->withSecurity([
            'bearerAuth' => [],
        ]);
        return $openApi;
    }
}
