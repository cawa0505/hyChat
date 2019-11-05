<?php

declare(strict_types=1);

namespace App\Kernel\Http;

use App\Constants\StatusCode;
use Hyperf\Contract\LengthAwarePaginatorInterface;
use Hyperf\Contract\TranslatorInterface;
use Hyperf\Database\Model\Collection;
use Hyperf\DbConnection\Model\Model;
use Hyperf\HttpServer\Contract\ResponseInterface;
use League\Fractal\Manager;
use League\Fractal\Resource\Collection as CollectionResource;
use League\Fractal\Resource\Item as ItemResource;
use Psr\Container\ContainerInterface;

class Response
{
    protected $container;

    protected $response;

    protected $fractal;

    private $translator;

    public function __construct(
        ContainerInterface $container,
        ResponseInterface $response,
        TranslatorInterface $translator,
        Manager $fractal,
        ArraySerializer $serializer
    ) {
        $this->container = $container;
        $this->response = $response;
        $this->translator = $translator;
        $this->fractal = $fractal;
        $this->fractal->setSerializer($serializer);
    }

    public function null()
    {
        return $this->response->json([
            'code' => StatusCode::OK,
            'message' => StatusCode::getMessage(StatusCode::OK),
        ]);
    }

    public function array(array $data = [])
    {
        return $this->response->json([
            'code' => StatusCode::OK,
            'message' => StatusCode::getMessage(StatusCode::OK),
            'data' => $data,
        ]);
    }

    public function error(int $code, string $message = null)
    {
        $statusCode = (int) StatusCode::getHttpCode($code) ?: 500;
        $message = $message ?? StatusCode::getMessage($code);
        if ($message && $this->translator->has("messages.{$message}")) {
            $message = $this->translator->trans("messages.{$message}");
        }

        return $this->response->json([
            'code' => $code,
            'message' => $message,
        ])->withStatus($statusCode);
    }

    public function item(Model $item, string $transformer)
    {
        $resource = new ItemResource($item, $this->container->get($transformer));

        return $this->array($this->fractal->createData($resource)->toArray());
    }

    public function collection(Collection $collection, string $transformer)
    {
        $resource = new CollectionResource($collection, $this->container->get($transformer));

        return $this->array($this->fractal->createData($resource)->toArray());
    }

    public function paginate(LengthAwarePaginatorInterface $paginator, string $transformer)
    {
        $resource = new CollectionResource($paginator, $this->container->get($transformer));
        $resource->setPaginator(new HyperfPaginatorAdapter($paginator));

        $data = $this->fractal->createData($resource)->toArray();
        $meta = $data['meta'];
        unset($data['meta']);

        return $this->response->json([
            'code' => StatusCode::OK,
            'message' => StatusCode::getMessage(StatusCode::OK),
            'data' => $data,
            'meta' => $meta,
        ]);
    }
}
