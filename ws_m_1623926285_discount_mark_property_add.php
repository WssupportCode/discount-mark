<?php
use WS\ReduceMigrations\Builder\Entity\Iblock;
use WS\ReduceMigrations\Builder\IblockBuilder;
/**
 * Class definition update migrations scenario actions
 **/
class ws_m_1623926285_discount_mark_property_add extends \WS\ReduceMigrations\Scenario\ScriptScenario {

    /**
     * Name of scenario
     **/
    static public function name() {
        return "discount mark property add";
    }

    /**
     * Priority of scenario
     **/
    static public function priority() {
        return self::PRIORITY_HIGH;
    }

    /**
     * @return string hash
     */
    static public function hash() {
        return "6a10c5f51dac8bd1e68012fc68139df5d07783b0";
    }

    /**
     * @return int approximately time in seconds
     */
    static public function approximatelyTime() {
        return 0;
    }

    /**
     * Write action by apply scenario. Use method `setData` for save need rollback data
     **/
    public function commit() {
        $builder = new IblockBuilder();
        $builder->updateIblock(26, function (Iblock $iblock) {
            $prop = $iblock->addProperty("Скидка")
                ->code("DISCOUNT")
                ->sort(1000)
                ->typeNumber();
        });
        // my code
    }

    /**
     * Write action by rollback scenario. Use method `getData` for getting commit saved data
     **/
    public function rollback() {
        // my code
    }
}