<?php

namespace Tests;

trait TestHelpers{


    protected function assertDatabaseEmpty($table, $connection = null){
        $total = $this->getConnection($connection)->table($table)->count();
        $this->assertSame(0,$total,sprintf(
            'Failed asserting  the table [%s] is empty. %s %s found.', $table,$total,str_plural('row',$total)
        ));
    }

    protected function assertDatabaseCount($table,$expected,$connection = null){
        $total = $this->getConnection($connection)->table('users')->count();
        $this->assertSame($expected,$total , sprintf(
            'Failed asserting the table [%s] has %s %s. %s %s found', $table, $expected, str_plural('row',$expected),$total,str_plural('row',$total)
        ));
    }


}