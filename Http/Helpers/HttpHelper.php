<?php
namespace Modules\Payment\Http\Helpers;

/**
 *
 */
trait HttpHelper
{
    /**
     * @var int
     */
    private int $timeout=30;
    /**
     * @var array
     */
    private array $response=['status'=>true,'message'=>'','data'=>[]];
    /**
     * @var array|string[]
     */
    public array $header=['Content-Type' => 'application/json'];

    /**
     * @param $uri
     * @param $data
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function post($uri , $data): array
    {

      $httpClient = new \GuzzleHttp\Client();

      try{
          $response = $httpClient->request('POST', $uri, [
              'headers' => $this->header,
              'body' => json_encode($data),
              "connect_timeout"=>$this->timeout, 'timeout'=>$this->timeout
          ]);
          $this->response['data']=$response->getBody()->getContents();
      }
      catch(\Exception $e){
          $this->response['status']=false;
          $this->response['message']=$e->getMessage();
      }
      return $this->response;
    }
}
