<?php

namespace RadioTest\Controller;

use RadioTest\Bootstrap;
use Zend\Mvc\Router\Http\TreeRouteStack as HttpRouter;
use Application\Controller\IndexController;
use Zend\Http\Request;
use Zend\Http\Response;
use Zend\Mvc\MvcEvent;
use Zend\Mvc\Router\RouteMatch;
use PHPUnit_Framework_TestCase;
use Radio\Controller\M3u;

class ControllerTest extends TestBase {

    protected function setUp() {
        $this->initTest("M3u", new \Radio\Controller\M3u());

    }

    public function testgetPrevHalfHour() {
        $start = new \DateTime("2013-10-25 10:22:00");
        $res = M3u::getPrevHalfHour($start->getTimestamp());
        $expected_end = new \DateTime("2013-10-25 10:00:00");
        $expected = $expected_end->getTimestamp();
        $this->assertEquals($expected, $res);
    }

    public function testDownloadAction() {
        //given
        $this->routeMatch->setParam('action', 'download');
        $this->routeMatch->setParam('from', '1360000000');
        $this->routeMatch->setParam('duration', '90');
        //when
        $result = $this->controller->dispatch($this->request);
        $response = $this->controller->getResponse();

        //then        
        $this->assertEquals(200, $response->getStatusCode());


        $body = preg_split("/\n/", $response->getContent());
        $this->assertEquals(8, sizeof($body));
        $this->assertContains("20130204-1900", $body[4]);
    }
}

?>