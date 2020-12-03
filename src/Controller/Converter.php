<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

class Converter
{
    protected $twig;

    public function __construct(Environment $twig) {
        $this->twig = $twig;
    }

    public function index() {
        return $this->render('converter.html.twig');
    }

    public function cartesianToPolar() {
        $x = $_GET['xcoordinate'];
        $y = $_GET['ycoordinate'];

        $data = $this->convertCartesianToPolar($x, $y);
        $data['x'] = $x;
        $data['y'] = $y;
        return $this->render('converter.html.twig', $data);
    }

    public function polarToCartesian() {
        $length = $_GET['length'];
        $angle = $_GET['angle'];

        $data = $this->convertPolarToCartesian($length, $angle);
        $data['length'] = $length;
        $data['angle'] = $angle;
        return $this->render('converter.html.twig', $data);
    }

    protected function convertCartesianToPolar($x, $y) {
        $angle = 0;
        if ($x == 0 && $y == 0) {
            $angle = 0;
        }
        elseif ($x == 0 && $y > 0) {
            $angle = 45;
        }
        elseif ($x == 0 && $y < 0) {
            $angle = 135;
        }
        elseif ($y == 0 && $x > 0) {
            $angle = 0;
        }
        elseif ($y == 0 && $x < 0) {
            $angle = 180;
        }
        elseif ($x > 0 && $y > 0) {
            $angle = rad2deg(atan($y / $x));
        }
        elseif ($x < 0 && $y > 0) {
            $angle = 180 + rad2deg(atan($y / $x));
        }
        elseif ($x < 0 && $y < 0) {
            $angle = 180 + rad2deg(atan($y / $x));
        }
        elseif ($x > 0 && $y < 0) {
            $angle = 360 + rad2deg(atan($y / $x));
        } else {
            dd('todo');
        }

        $length = sqrt(pow($x, 2) + pow($y, 2));

        $result['angle'] = $angle;
        $result['length'] = $length;

        return $result;
    }

    protected function convertPolarToCartesian($length, $angle) {


        $x = cos(deg2rad($angle)) * $length;
        $y = sin(deg2rad($angle)) * $length;

        $result['x'] = $x;
        $result['y'] = $y;

        return $result;
    }

    protected function render(string $path, array $variables =[]) {
        $html = $this->twig->render($path, $variables);
        return new Response($html);
    }
}
