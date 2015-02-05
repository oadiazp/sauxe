<?php
class TestA
{

    /**
     * @param int $a --
     * @param int $b --
     * @return int
     */
    public function Suma($a, $b)
    {
        return $a + $b;
    }

    /**
     * @param int $a --
     * @param int $b --
     * @return int
     */
    public function Resta($a, $b)
    {
        return $a-$b;
    }

    /**
     * @param int $a --
     * @param int $b --
     * @return int
     */
    public function Multiplicacion($a, $b)
    {
        return $a*$b;
    }

    /**
     * asdf
     *
     * @param int $a --
     * @param int $b --
     * @return int
     */
    public function Division($a, $b)
    {
        if($b!=0)return $a/$b;
    }

    /**
     * devuelve la misma cadena tres veces.
     *
     * @param string $a --
     * @return string
     */
    public function Test($a)
    {
        return $a.$a.$a;
    }

    /**
     * suma dos números enteros
     *
     * @param int $r --
     * @param int $e --
     * @return int
     */
    public function Test2($r, $e)
    {
        return $e + $r ;
    }

    /**
     * hola mundo
     *
     * @return string
     *
     */
    public function Test3()
    {
        return "Hola Mundo!!";
    }


}
?>