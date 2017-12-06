<?php
/**
 * Created by PhpStorm.
 * User: Benjamin Zaslavsky
 * Date: 04/12/17
 * Time: 11:41
 */

namespace AppBundle\Services;

/**
 * Class ResponseFormatter
 * @package AppBundle\Services
 */
class ResponseFormatter
{
    /**
     * @var ReqresCaller
     */
    private $caller;

    /**
     * @var array
     */
    private $colors = [
        'cerulean',
        'fuchsia rose',
        'true red',
        'aqua sky',
        'tigerlily',
        'blue turquoise',
        'sand dollar',
        'chili pepper',
        'blue iris',
        'mimosa',
        'turquoise',
        'honeysuckle',
    ];

    /**
     * ResponseFormatter constructor.
     *
     * @param ReqresCaller $caller
     */
    public function __construct(ReqresCaller $caller)
    {
        $this->caller = $caller;
    }

    /**
     * @param int $id
     *
     * @return mixed|\Psr\Http\Message\ResponseInterface
     * @throws \Exception
     */
    public function getColor(int $id)
    {
        return $this->caller->getColor($id);
    }

    /**
     * @param $id
     *
     * @return mixed
     * @throws \Exception
     */
    public function formatColor($id)
    {
        $index = $id;
        if ( ! is_int($id) || ! ctype_digit($id)) {
            $index = array_search($id, $this->colors) + 1;
        }

        if (false === $index) {
            throw new \InvalidArgumentException("Invalid or unknown color: {$id}");
        }
        $response  = $this->getColor($index);
        $resString = $response
            ->getBody()
            ->getContents();
        $color     = json_decode($resString);
        if (false === $color) {
            throw new \Exception("Invalid payload received from API.");
        }
        $color = $color->data;

        return $color;
    }
}