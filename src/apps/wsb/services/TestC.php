<?php
    
    /**
     * pa la pinga el mundo
     */
    class TestC extends ZendExt_Model{
        /**
         *
         * @param int $a
         * @param int $b
         * @return int
         */
        function a($a, $b)
        {
            return ZendExt_Service_Demo::test();
        }

        function b ()
        {

        }
    }

?>